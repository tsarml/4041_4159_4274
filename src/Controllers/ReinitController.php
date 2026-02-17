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
                ('Antananarivo', 'Analamanga'),
                ('Toamasina', 'Atsinanana'),
                ('Fianarantsoa', 'Haute Matsiatra'),
                ('Mahajanga', 'Boeny')
            ");

            $this->db->exec("INSERT INTO don (donateur, type_don, description, quantite, unite, valeur_unitaire) VALUES
                ('Croix-Rouge Madagascar', 'nature', 'Riz blanc', 2000, 'kg', 4500),
                ('Croix-Rouge Madagascar', 'nature', 'Huile vegetale', 500, 'litre', 8000),
                ('Ministere des Finances', 'argent', 'Fonds urgence', 5000000, 'Ar', 1),
                ('ONG Tafita', 'materiau', 'Tole ondulee', 300, 'feuille', 15000),
                ('Anonyme', 'nature', 'Eau potable', 1000, 'litre', 500)
            ");

            $this->db->exec("INSERT INTO besoin (ville_id, type_besoin, description, quantite, unite, prix_unitaire) VALUES
                (1, 'nature', 'Riz blanc', 500, 'kg', 4500),
                (1, 'nature', 'Huile vegetale', 100, 'litre', 8000),
                (1, 'argent', 'Fonds urgence', 1000000, 'Ar', NULL),
                (2, 'nature', 'Riz blanc', 800, 'kg', 4500),
                (2, 'materiau', 'Tole ondulee', 150, 'feuille', 15000),
                (3, 'materiau', 'Clous', 50, 'kg', 3000),
                (4, 'nature', 'Eau potable', 500, 'litre', 500)
            ");

            $this->db->exec("INSERT INTO attribution (besoin_id, don_id, quantite) VALUES
                (1, 1, 200),
                (2, 2, 50),
                (3, 3, 500000),
                (4, 1, 300),
                (5, 4, 80)
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