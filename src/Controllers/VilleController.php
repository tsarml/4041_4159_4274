<?php

class VilleController
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function index()
    {
        $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
        Flight::render('gestion_villes', ['title' => 'Gestion des villes', 'villes' => $villes, 'errors' => [], 'old' => []]);
    }

    public function create()
    {
        $this->index();
    }

    public function store()
    {
        $data = Flight::request()->data->getData();
        $errors = [];
        
        if (empty(trim($data['nom'] ?? ''))) {
            $errors['nom'] = 'Le nom est obligatoire';
        }

        if (!empty($errors)) {
            $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
            Flight::render('gestion_villes', ['title' => 'Ajouter une ville', 'villes' => $villes, 'errors' => $errors, 'old' => $data]);
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO ville (nom, region, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([trim($data['nom']), trim($data['region'] ?? '')]);
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Ville ajoutée'];
        Flight::redirect(  '/ville');
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM ville WHERE id = ?");
        $stmt->execute([$id]);
        $ville = $stmt->fetch();
        
        if (!$ville) Flight::halt(404, 'Ville introuvable');

        $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
        Flight::render('gestion_villes', ['title' => 'Modifier la ville', 'villes' => $villes, 'errors' => [], 'old' => $ville, 'edit' => true, 'edit_id' => $id]);
    }

    public function update($id)
    {
        $data = Flight::request()->data->getData();
        $errors = [];
        
        if (empty(trim($data['nom'] ?? ''))) {
            $errors['nom'] = 'Le nom est obligatoire';
        }

        if (!empty($errors)) {
            $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
            Flight::render('gestion_villes', ['title' => 'Modifier la ville', 'villes' => $villes, 'errors' => $errors, 'old' => $data, 'edit' => true, 'edit_id' => $id]);
            return;
        }

        $stmt = $this->db->prepare("UPDATE ville SET nom=?, region=? WHERE id=?");
        $stmt->execute([trim($data['nom']), trim($data['region'] ?? ''), $id]);
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Ville modifiée'];
        Flight::redirect(  '/ville');
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM ville WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Ville supprimée'];
        Flight::redirect(  '/ville');
    }
}