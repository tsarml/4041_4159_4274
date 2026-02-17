<?php

class BesoinController
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function index()
    {
        $besoins = $this->db->query("
            SELECT b.*, v.nom AS ville_nom,
                   COALESCE(SUM(a.quantite), 0) AS total_attribue,
                   (b.quantite - COALESCE(SUM(a.quantite), 0)) AS restant
            FROM besoin b
            LEFT JOIN ville v ON v.id = b.ville_id
            LEFT JOIN attribution a ON a.besoin_id = b.id
            GROUP BY b.id
            ORDER BY b.created_at DESC
        ")->fetchAll();

        Flight::render('liste_besoins', ['title' => 'Liste des besoins', 'besoins' => $besoins]);
    }

    public function create()
    {
        $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
        Flight::render('saisie_besoins', ['title' => 'Saisir un besoin', 'villes' => $villes, 'errors' => [], 'old' => []]);
    }

    public function store()
    {
        $data = Flight::request()->data->getData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
            Flight::render('saisie_besoins', ['title' => 'Saisir un besoin', 'villes' => $villes, 'errors' => $errors, 'old' => $data]);
            return;
        }

        $prix_unitaire = null;
        if (in_array($data['type_besoin'], ['nature', 'materiau']) && !empty($data['prix_unitaire'])) {
            $prix_unitaire = (float)$data['prix_unitaire'];
        }

        $stmt = $this->db->prepare("INSERT INTO besoin (ville_id, type_besoin, description, quantite, unite, prix_unitaire, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$data['ville_id'], $data['type_besoin'], $data['description'], $data['quantite'], $data['unite'], $prix_unitaire]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Besoin enregistré'];
        Flight::redirect(  '/besoin');
    }


    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM besoin WHERE id = ?");
        $stmt->execute([$id]);
        $besoin = $stmt->fetch();
        
        if (!$besoin) Flight::halt(404, 'Besoin introuvable');

        $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
        Flight::render('saisie_besoins', ['title' => 'Modifier le besoin', 'villes' => $villes, 'errors' => [], 'old' => $besoin, 'edit' => true, 'id' => $id]);
    }

    
    public function update($id)
    {
        $data = Flight::request()->data->getData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
            Flight::render('saisie_besoins', ['title' => 'Modifier le besoin', 'villes' => $villes, 'errors' => $errors, 'old' => $data, 'edit' => true, 'id' => $id]);
            return;
        }

        $prix_unitaire = null;
        if (in_array($data['type_besoin'], ['nature', 'materiau']) && !empty($data['prix_unitaire'])) {
            $prix_unitaire = (float)$data['prix_unitaire'];
        }

        $stmt = $this->db->prepare("UPDATE besoin SET ville_id=?, type_besoin=?, description=?, quantite=?, unite=?, prix_unitaire=? WHERE id=?");
        $stmt->execute([$data['ville_id'], $data['type_besoin'], $data['description'], $data['quantite'], $data['unite'], $prix_unitaire, $id]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Besoin modifié'];
        Flight::redirect(  '/besoin');
    }


    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM besoin WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Besoin supprimé'];
        Flight::redirect(  '/besoin');
    }

    private function validate($data)
    {
        $errors = [];
        
        if (empty($data['ville_id'])) $errors['ville_id'] = 'La ville est obligatoire';
        if (empty($data['type_besoin'])) $errors['type_besoin'] = 'Le type est obligatoire';
        if (empty($data['description'])) $errors['description'] = 'La description est obligatoire';
        if (empty($data['quantite']) || (float)$data['quantite'] <= 0) $errors['quantite'] = 'La quantité doit être positive';
        if (empty($data['unite'])) $errors['unite'] = 'L\'unité est obligatoire';
        
        return $errors;
    }
}