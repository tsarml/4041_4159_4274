<?php

class ReinitController
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function index(): void
    {
        Flight::render('reinitialiser', ['title' => 'Réinitialiser les données']);
    }

    public function confirmer(): void
    {
        try {
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");

            $tablesToEmpty = ['vente', 'attribution', 'achat', 'besoin', 'don', 'ville'];
            foreach ($tablesToEmpty as $t) {
                $this->db->exec("DELETE FROM `$t`");
                $this->db->exec("ALTER TABLE `$t` AUTO_INCREMENT = 1");
            }

            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");

            $this->db->exec("INSERT INTO ville (nom, region) VALUES
                ('Toamasina', 'Atsinanana'),
                ('Mananjary', 'Vatovavy-Fitovinany'),
                ('Farafangana', 'Atsimo-Atsinanana'),
                ('Nosy Be', 'Diana'),
                ('Morondava', 'Menabe')
            ");

            $this->db->exec("INSERT INTO besoin (ville_id, type_besoin, description, quantite, unite, prix_unitaire) VALUES
                (1, 'nature', 'Riz', 800, 'kg', 3000),
                (1, 'nature', 'Eau', 1500, 'L', 1000),
                (1, 'materiau', 'Tôle', 120, 'feuille', 25000),
                (1, 'materiau', 'Bâche', 200, 'feuille', 15000),
                (1, 'argent', 'Argent', 12000000, 'Ar', NULL),
                (2, 'nature', 'Riz', 500, 'kg', 3000),
                (2, 'nature', 'Huile', 120, 'L', 6000),
                (2, 'materiau', 'Tôle', 80, 'feuille', 25000),
                (2, 'materiau', 'Clous', 60, 'kg', 8000),
                (2, 'argent', 'Argent', 6000000, 'Ar', NULL),
                (3, 'nature', 'Riz', 600, 'kg', 3000),
                (3, 'nature', 'Eau', 1000, 'L', 1000),
                (3, 'materiau', 'Bâche', 150, 'feuille', 15000),
                (3, 'materiau', 'Bois', 100, 'unité', 10000),
                (3, 'argent', 'Argent', 8000000, 'Ar', NULL),
                (4, 'nature', 'Riz', 300, 'kg', 3000),
                (4, 'nature', 'Haricots', 200, 'kg', 4000),
                (4, 'materiau', 'Tôle', 40, 'feuille', 25000),
                (4, 'materiau', 'Clous', 30, 'kg', 8000),
                (4, 'argent', 'Argent', 4000000, 'Ar', NULL),
                (5, 'nature', 'Riz', 700, 'kg', 3000),
                (5, 'nature', 'Eau', 1200, 'L', 1000),
                (5, 'materiau', 'Bâche', 180, 'feuille', 15000),
                (5, 'materiau', 'Bois', 150, 'unité', 10000),
                (5, 'argent', 'Argent', 10000000, 'Ar', NULL),
                (1, 'materiau', 'groupe', 3, 'unité', 6750000)
            ");

            $this->db->exec("INSERT INTO don (donateur, type_don, description, quantite, unite, valeur_unitaire) VALUES
                ('Croix-Rouge', 'nature', 'Riz', 5000, 'kg', 3000),
                ('Croix-Rouge', 'nature', 'Eau', 3000, 'L', 1000),
                ('Gouvernement', 'argent', 'Fonds urgence', 25000000, 'Ar', 1),
                ('ONG Tafita', 'materiau', 'Tôle', 500, 'feuille', 25000),
                ('Anonyme', 'materiau', 'Bâche', 400, 'feuille', 15000),
                ('Privé', 'nature', 'Huile', 500, 'L', 6000),
                ('Privé', 'materiau', 'Clous', 200, 'kg', 8000),
                ('Privé', 'nature', 'Haricots', 300, 'kg', 4000),
                ('Privé', 'materiau', 'Bois', 250, 'unité', 10000)
            ");

            $this->db->exec("INSERT INTO attribution (besoin_id, don_id, quantite) VALUES
                (1, 1, 300),
                (2, 2, 800),
                (6, 1, 200),
                (7, 6, 60)
            ");

            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => 'Données réinitialisées avec succès.'
            ];
            
            Flight::redirect('/dashboard');

        } catch (Exception $e) {
            $_SESSION['flash'] = [
                'type'    => 'danger',
                'message' => 'Erreur : ' . $e->getMessage()
            ];
            Flight::redirect('/reinitialiser');
        }
    }
}