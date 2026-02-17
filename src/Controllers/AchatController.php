<?php

class AchatController
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function index()
    {
        $ville_id = $_GET['ville_id'] ?? '';
        
        $sql = "SELECT a.*, v.nom AS ville_nom, b.description AS besoin_desc, b.unite, b.type_besoin 
                FROM achat a 
                JOIN ville v ON v.id = a.ville_id 
                JOIN besoin b ON b.id = a.besoin_id 
                WHERE 1=1";
        $params = [];
        
        if (!empty($ville_id)) {
            $sql .= " AND a.ville_id = ?";
            $params[] = $ville_id;
        }
        
        $sql .= " ORDER BY a.date_achat DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $achats = $stmt->fetchAll();

        $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
        $total_achats = array_sum(array_column($achats, 'montant_total'));

        Flight::render('liste_achats', [
            'title' => 'Liste des achats',
            'achats' => $achats,
            'villes' => $villes,
            'ville_filtre' => $ville_id,
            'total_achats' => $total_achats
        ]);
    }

    public function create()
    {
        $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
        
        $dons_argent = $this->db->query("
            SELECT d.*, COALESCE(SUM(a.montant_total), 0) AS total_utilise,
                   (d.quantite - COALESCE(SUM(a.montant_total), 0)) AS solde_dispo
            FROM don d
            LEFT JOIN achat a ON a.don_id = d.id
            WHERE d.type_don = 'argent'
            GROUP BY d.id
            HAVING solde_dispo > 0
        ")->fetchAll();

        $besoins = $this->db->query("
            SELECT b.*, v.nom AS ville_nom,
                   (b.quantite - COALESCE(SUM(att.quantite), 0)) AS besoin_restant
            FROM besoin b
            JOIN ville v ON v.id = b.ville_id
            LEFT JOIN attribution att ON att.besoin_id = b.id
            WHERE b.type_besoin IN ('nature', 'materiau')
            AND b.prix_unitaire IS NOT NULL
            AND b.prix_unitaire > 0
            GROUP BY b.id
            HAVING besoin_restant > 0
        ")->fetchAll();

        Flight::render('saisie_achat', [
            'title' => 'Nouvel achat',
            'villes' => $villes,
            'dons_argent' => $dons_argent,
            'besoins' => $besoins,
            'errors' => [],
            'old' => []
        ]);
    }

    public function store()
    {
        $data = Flight::request()->data->getData();
        $errors = [];

        if (empty($data['ville_id'])) $errors['ville_id'] = 'La ville est obligatoire';
        if (empty($data['besoin_id'])) $errors['besoin_id'] = 'Le besoin est obligatoire';
        if (empty($data['don_id'])) $errors['don_id'] = 'Le don est obligatoire';
        if (empty($data['quantite']) || (float)$data['quantite'] <= 0) {
            $errors['quantite'] = 'La quantité doit être positive';
        }
        if (empty($data['date_achat'])) $errors['date_achat'] = 'La date est obligatoire';

        if (empty($errors)) {
            $stmt = $this->db->prepare("
                SELECT d.quantite, COALESCE(SUM(a.montant_total), 0) AS total_utilise,
                       (d.quantite - COALESCE(SUM(a.montant_total), 0)) AS solde_dispo
                FROM don d
                LEFT JOIN achat a ON a.don_id = d.id
                WHERE d.id = ? AND d.type_don = 'argent'
                GROUP BY d.id
            ");
            $stmt->execute([$data['don_id']]);
            $don = $stmt->fetch();

            $stmt = $this->db->prepare("SELECT prix_unitaire FROM besoin WHERE id = ?");
            $stmt->execute([$data['besoin_id']]);
            $besoin = $stmt->fetch();

            $montant_total = (float)$data['quantite'] * (float)$besoin['prix_unitaire'];
            
            if ($montant_total > (float)$don['solde_dispo']) {
                $errors['quantite'] = 'Solde insuffisant !';
            }
        }

        if (!empty($errors)) {
            $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
            $dons_argent = $this->db->query("
                SELECT d.*, COALESCE(SUM(a.montant_total), 0) AS total_utilise,
                       (d.quantite - COALESCE(SUM(a.montant_total), 0)) AS solde_dispo
                FROM don d
                LEFT JOIN achat a ON a.don_id = d.id
                WHERE d.type_don = 'argent'
                GROUP BY d.id
                HAVING solde_dispo > 0
            ")->fetchAll();
            $besoins = $this->db->query("
                SELECT b.*, v.nom AS ville_nom
                FROM besoin b
                JOIN ville v ON v.id = b.ville_id
                WHERE b.type_besoin IN ('nature', 'materiau')
                AND b.prix_unitaire IS NOT NULL
            ")->fetchAll();

            Flight::render('saisie_achat', [
                'title' => 'Nouvel achat',
                'villes' => $villes,
                'dons_argent' => $dons_argent,
                'besoins' => $besoins,
                'errors' => $errors,
                'old' => $data
            ]);
            return;
        }

        $stmt = $this->db->prepare("SELECT prix_unitaire FROM besoin WHERE id = ?");
        $stmt->execute([$data['besoin_id']]);
        $prix_unitaire = $stmt->fetch()['prix_unitaire'];
        $montant_total = (float)$data['quantite'] * (float)$prix_unitaire;

        $stmt = $this->db->prepare("
            INSERT INTO achat (ville_id, besoin_id, don_id, quantite, prix_unitaire, montant_total, date_achat, description, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['ville_id'],
            $data['besoin_id'],
            $data['don_id'],
            $data['quantite'],
            $prix_unitaire,
            $montant_total,
            $data['date_achat'],
            $data['description'] ?? null
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Achat enregistré'];
        Flight::redirect(  '/achat');
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM achat WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Achat supprimé'];
        Flight::redirect(  '/achat');
    }
}