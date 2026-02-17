<?php

class RecapController
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function index(): void
    {
        Flight::render('recapitulation', ['title' => 'Recapitulation']);
    }

    public function data(): void
    {
        header('Content-Type: application/json');

        try {
            $besoins = $this->db->query("
                SELECT COUNT(*) AS nb,
                       SUM(CASE 
                           WHEN type_besoin IN ('nature','materiau') THEN quantite * COALESCE(prix_unitaire, 0)
                           ELSE quantite 
                       END) AS montant_total
                FROM besoin
            ")->fetch();

            $attribue = $this->db->query("
                SELECT COALESCE(SUM(
                    CASE 
                        WHEN b.type_besoin IN ('nature','materiau') THEN a.quantite * COALESCE(b.prix_unitaire, 0)
                        ELSE a.quantite 
                    END
                ), 0) AS montant
                FROM attribution a
                JOIN besoin b ON b.id = a.besoin_id
            ")->fetch();

            $dons_argent = $this->db->query("
                SELECT COALESCE(SUM(quantite), 0) AS total
                FROM don WHERE type_don = 'argent'
            ")->fetch();

            $achats = $this->db->query("
                SELECT COALESCE(SUM(montant_total), 0) AS total FROM achat
            ")->fetch();

            $ventes = $this->db->query("
                SELECT COALESCE(SUM(montant_total), 0) AS total,
                       COUNT(*) AS nb
                FROM vente
            ")->fetch();

            $attributions_argent = $this->db->query("
                SELECT COALESCE(SUM(a.quantite), 0) AS total
                FROM attribution a
                JOIN don d ON d.id = a.don_id
                WHERE d.type_don = 'argent'
            ")->fetch();

            $montant_total  = (float)($besoins['montant_total'] ?? 0);
            $montant_sat    = (float)($attribue['montant'] ?? 0);
            $taux           = $montant_total > 0 ? round(($montant_sat / $montant_total) * 100, 1) : 0;

            $dons_recus     = (float)($dons_argent['total'] ?? 0);
            $revenus_ventes = (float)($ventes['total'] ?? 0);
            $total_entrees  = $dons_recus + $revenus_ventes; // Dons + ventes
            $dons_utilises  = (float)($achats['total'] ?? 0) + (float)($attributions_argent['total'] ?? 0);
            $solde          = $total_entrees - $dons_utilises;

            echo json_encode([
                'success'           => true,
                'timestamp'         => date('d/m/Y H:i:s'),
                'besoins_total'     => $montant_total,
                'besoins_satisfaits'=> $montant_sat,
                'taux_satisfaction' => $taux,
                'dons_recus'        => $dons_recus,
                'revenus_ventes'    => $revenus_ventes,
                'total_entrees'     => $total_entrees,
                'dons_utilises'     => $dons_utilises,
                'solde'             => $solde,
                'achats_total'      => (float)($achats['total'] ?? 0),
                'ventes_nb'         => (int)($ventes['nb'] ?? 0),
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}