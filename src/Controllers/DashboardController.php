<?php

class DashboardController
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function index()
    {
        $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();

        foreach ($villes as &$ville) {
            $stmt = $this->db->prepare("
                SELECT b.*, COALESCE(SUM(a.quantite), 0) AS total_attribue,
                       (b.quantite - COALESCE(SUM(a.quantite), 0)) AS restant
                FROM besoin b
                LEFT JOIN attribution a ON a.besoin_id = b.id
                WHERE b.ville_id = ?
                GROUP BY b.id
            ");
            $stmt->execute([$ville['id']]);
            $ville['besoins'] = $stmt->fetchAll();

            $stmt = $this->db->prepare("
                SELECT COALESCE(SUM(montant_total), 0) AS total_achats, COUNT(*) AS nb_achats
                FROM achat WHERE ville_id = ?
            ");
            $stmt->execute([$ville['id']]);
            $achats = $stmt->fetch();
            $ville['total_achats'] = $achats['total_achats'] ?? 0;
            $ville['nb_achats'] = $achats['nb_achats'] ?? 0;
        }

        Flight::render('dashboard', ['title' => 'Tableau de bord', 'villes' => $villes]);
    }
}