<?php

class VenteController
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function index(): void
    {
        $ventes = $this->db->query("
            SELECT v.*, d.description AS don_desc, d.unite, d.donateur
            FROM vente v
            JOIN don d ON d.id = v.don_id
            ORDER BY v.created_at DESC
        ")->fetchAll();

        $total = array_sum(array_column($ventes, 'montant_total'));

        Flight::render('liste_ventes', [
            'title'  => 'Liste des ventes',
            'ventes' => $ventes,
            'total'  => $total,
        ]);
    }

    public function create(): void
    {
        $dons = $this->db->query("
            SELECT d.*,
                   ROUND(d.quantite
                    - COALESCE((SELECT SUM(a.quantite) FROM attribution a WHERE a.don_id = d.id), 0)
                    - COALESCE((SELECT SUM(ve.quantite) FROM vente ve WHERE ve.don_id = d.id), 0)
                   , 2) AS stock_dispo
            FROM don d
            HAVING stock_dispo > 0
            ORDER BY d.type_don, d.description
        ")->fetchAll();

        Flight::render('saisie_vente', [
            'title'  => 'Vendre un don',
            'dons'   => $dons,
            'errors' => [],
            'old'    => [],
        ]);
    }

    public function store(): void
    {
        $data   = Flight::request()->data->getData();
        $errors = [];

        if (empty($data['don_id']))                                          $errors['don_id']          = 'Selectionnez un don.';
        if (empty($data['quantite']) || (float)$data['quantite'] <= 0)      $errors['quantite']        = 'Quantite invalide.';
        if (!isset($data['remise_pct']) || $data['remise_pct'] < 0 || $data['remise_pct'] > 100)
                                                                              $errors['remise_pct']      = 'Remise entre 0 et 100%.';
        if (empty($data['valeur_unitaire']) || (float)$data['valeur_unitaire'] <= 0)
                                                                              $errors['valeur_unitaire'] = 'Valeur unitaire invalide.';

        if (empty($errors)) {
            $stmt = $this->db->prepare("
                SELECT d.*,
                       ROUND(d.quantite
                        - COALESCE((SELECT SUM(a.quantite) FROM attribution a WHERE a.don_id = d.id), 0)
                        - COALESCE((SELECT SUM(ve.quantite) FROM vente ve WHERE ve.don_id = d.id), 0)
                       , 2) AS stock_dispo
                FROM don d WHERE d.id = ?
            ");
            $stmt->execute([$data['don_id']]);
            $don = $stmt->fetch();

            if (!$don) {
                $errors['don_id'] = 'Don introuvable.';
            } elseif ((float)$data['quantite'] > (float)$don['stock_dispo']) {
                $errors['quantite'] = 'Stock insuffisant ! Disponible : '
                    . number_format($don['stock_dispo'], 2, ',', ' ') . ' ' . $don['unite'] . '.';
            } else {
                $stmt2 = $this->db->prepare("
                    SELECT b.description, b.quantite, b.unite, v.nom AS ville_nom,
                           COALESCE(SUM(a.quantite), 0) AS total_attribue
                    FROM besoin b
                    JOIN ville v ON v.id = b.ville_id
                    LEFT JOIN attribution a ON a.besoin_id = b.id
                    WHERE LOWER(b.description) = LOWER((SELECT description FROM don WHERE id = ?))
                    GROUP BY b.id
                    HAVING total_attribue < b.quantite
                ");
                $stmt2->execute([$data['don_id']]);
                $bloquants = $stmt2->fetchAll();

                if (!empty($bloquants)) {
                    $liste = implode(' | ', array_map(
                        fn($b) => $b['ville_nom'] . ' — ' . $b['description']
                            . ' (manque ' . number_format($b['quantite'] - $b['total_attribue'], 0, ',', ' ')
                            . ' ' . $b['unite'] . ')',
                        $bloquants
                    ));
                    $errors['besoin_bloquant'] = 'Impossible de vendre : besoins non satisfaits — ' . $liste;
                }
            }
        }

        if (!empty($errors)) {
            $dons = $this->db->query("
                SELECT d.*,
                       ROUND(d.quantite
                        - COALESCE((SELECT SUM(a.quantite) FROM attribution a WHERE a.don_id = d.id), 0)
                        - COALESCE((SELECT SUM(ve.quantite) FROM vente ve WHERE ve.don_id = d.id), 0)
                       , 2) AS stock_dispo
                FROM don d HAVING stock_dispo > 0
            ")->fetchAll();

            Flight::render('saisie_vente', [
                'title'  => 'Vendre un don',
                'dons'   => $dons,
                'errors' => $errors,
                'old'    => $data,
            ]);
            return;
        }

        $montant = round(
            (float)$data['quantite'] * (float)$data['valeur_unitaire'] * (1 - (float)$data['remise_pct'] / 100),
            2
        );

        $stmt = $this->db->prepare("
            INSERT INTO vente (don_id, quantite, valeur_unitaire, remise_pct, montant_total, description, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['don_id'],
            $data['quantite'],
            $data['valeur_unitaire'],
            $data['remise_pct'] ?? 0,
            $montant,
            trim($data['description'] ?? ''),
        ]);

        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => 'Vente enregistree ! Montant : ' . number_format($montant, 0, ',', ' ') . ' Ar'
        ];
        Flight::redirect('/vente');
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM vente WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Vente supprimee.'];
        Flight::redirect('/vente');
    }
}