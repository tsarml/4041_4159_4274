<?php

class AttributionController
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function index()
    {
        $attributions = $this->db->query("
            SELECT a.*,
                   b.description AS besoin_desc, b.unite, b.quantite AS besoin_qte,
                   v.nom AS ville_nom,
                   d.description AS don_desc, d.donateur
            FROM attribution a
            JOIN besoin b ON b.id = a.besoin_id
            JOIN ville v ON v.id = b.ville_id
            JOIN don d ON d.id = a.don_id
            ORDER BY a.created_at DESC
        ")->fetchAll();

        Flight::render('liste_attributions', ['title' => 'Liste des attributions', 'attributions' => $attributions]);
    }

    public function create()
    {
        $besoins = $this->db->query("
            SELECT b.*, v.nom AS ville_nom,
                   COALESCE(SUM(a.quantite), 0) AS total_attribue,
                   (b.quantite - COALESCE(SUM(a.quantite), 0)) AS restant
            FROM besoin b
            JOIN ville v ON v.id = b.ville_id
            LEFT JOIN attribution a ON a.besoin_id = b.id
            GROUP BY b.id
            HAVING restant > 0
            ORDER BY v.nom, b.description
        ")->fetchAll();

        $dons = $this->db->query("
            SELECT d.*,
                   COALESCE(SUM(a.quantite), 0) AS total_attribue,
                   (d.quantite - COALESCE(SUM(a.quantite), 0)) AS stock_dispo
            FROM don d
            LEFT JOIN attribution a ON a.don_id = d.id
            GROUP BY d.id
            HAVING stock_dispo > 0
            ORDER BY d.description
        ")->fetchAll();

        Flight::render('attribution', ['title' => 'Nouvelle attribution', 'besoins' => $besoins, 'dons' => $dons, 'errors' => [], 'old' => []]);
    }

    public function store()
    {
        $data = Flight::request()->data->getData();
        $errors = [];

        if (empty($data['besoin_id'])) $errors['besoin_id'] = 'Veuillez sélectionner un besoin';
        if (empty($data['don_id'])) $errors['don_id'] = 'Veuillez sélectionner un don';
        if (empty($data['quantite']) || (float)$data['quantite'] <= 0) $errors['quantite'] = 'La quantité doit être positive';

        if (empty($errors)) {
            $stmt = $this->db->prepare("
                SELECT b.unite AS besoin_unite, d.unite AS don_unite,
                       d.quantite AS don_qte,
                       COALESCE(SUM(a.quantite), 0) AS total_attribue,
                       (d.quantite - COALESCE(SUM(a.quantite), 0)) AS stock_dispo
                FROM besoin b
                JOIN don d ON d.id = ?
                LEFT JOIN attribution a ON a.don_id = d.id
                WHERE b.id = ?
                GROUP BY b.id, d.id
            ");
            $stmt->execute([$data['don_id'], $data['besoin_id']]);
            $check = $stmt->fetch();

            if (!$check) {
                $errors['don_id'] = 'Don ou besoin introuvable';
            } else {
                if ($check['besoin_unite'] !== $check['don_unite']) {
                    $errors['unite'] = sprintf(
                        'Unités incompatibles : Besoin en %s mais Don en %s',
                        $check['besoin_unite'],
                        $check['don_unite']
                    );
                }

                if ((float)$data['quantite'] > (float)$check['stock_dispo']) {
                    $errors['quantite'] = sprintf(
                        'Quantité insuffisante ! Stock disponible : %s %s',
                        number_format($check['stock_dispo'], 2, ',', ' '),
                        $check['don_unite']
                    );
                }
            }
        }

        if (!empty($errors)) {
            $besoins = $this->db->query("
                SELECT b.*, v.nom AS ville_nom,
                       COALESCE(SUM(a.quantite), 0) AS total_attribue,
                       (b.quantite - COALESCE(SUM(a.quantite), 0)) AS restant
                FROM besoin b
                JOIN ville v ON v.id = b.ville_id
                LEFT JOIN attribution a ON a.besoin_id = b.id
                GROUP BY b.id
                HAVING restant > 0
            ")->fetchAll();

            $dons = $this->db->query("
                SELECT d.*,
                       COALESCE(SUM(a.quantite), 0) AS total_attribue,
                       (d.quantite - COALESCE(SUM(a.quantite), 0)) AS stock_dispo
                FROM don d
                LEFT JOIN attribution a ON a.don_id = d.id
                GROUP BY d.id
                HAVING stock_dispo > 0
            ")->fetchAll();

            Flight::render('attribution', ['title' => 'Nouvelle attribution', 'besoins' => $besoins, 'dons' => $dons, 'errors' => $errors, 'old' => $data]);
            return;
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO attribution (besoin_id, don_id, quantite, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$data['besoin_id'], $data['don_id'], $data['quantite']]);
            
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Attribution enregistrée'];
            Flight::redirect(  '/attribution');
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'unités') !== false) {
                $errors['unite'] = 'Erreur : Les unités du besoin et du don doivent correspondre (validation DB)';
                
                $besoins = $this->db->query("SELECT b.*, v.nom AS ville_nom, (b.quantite - COALESCE(SUM(a.quantite), 0)) AS restant FROM besoin b JOIN ville v ON v.id = b.ville_id LEFT JOIN attribution a ON a.besoin_id = b.id GROUP BY b.id HAVING restant > 0")->fetchAll();
                $dons = $this->db->query("SELECT d.*, (d.quantite - COALESCE(SUM(a.quantite), 0)) AS stock_dispo FROM don d LEFT JOIN attribution a ON a.don_id = d.id GROUP BY d.id HAVING stock_dispo > 0")->fetchAll();
                
                Flight::render('attribution', ['title' => 'Nouvelle attribution', 'besoins' => $besoins, 'dons' => $dons, 'errors' => $errors, 'old' => $data]);
            } else {
                throw $e;
            }
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM attribution WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Attribution supprimée'];
        Flight::redirect(  '/attribution');
    }
}