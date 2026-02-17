<?php

class DonController
{
    private $db;

    public function __construct()
    {
        $this->db = Flight::db();
    }

    public function index()
    {
        $dons = $this->db->query("
            SELECT d.*,
                   COALESCE(SUM(a.quantite), 0) AS total_attribue,
                   ROUND(
                       d.quantite 
                       - COALESCE(SUM(a.quantite), 0) 
                       - COALESCE((SELECT SUM(v.quantite) FROM vente v WHERE v.don_id = d.id), 0)
                   , 2) AS stock_dispo
            FROM don d
            LEFT JOIN attribution a ON a.don_id = d.id
            GROUP BY d.id
            ORDER BY d.created_at DESC
        ")->fetchAll();

        Flight::render('liste_dons', [
            'title' => 'Liste des dons',
            'dons'  => $dons
        ]);
    }

    public function create()
    {
        $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
        Flight::render('saisie_dons', [
            'title' => 'Saisir un don',
            'villes' => $villes,
            'errors' => [],
            'old' => []
        ]);
    }

    public function store()
    {
        $data = Flight::request()->data->getData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
            Flight::render('saisie_dons', [
                'title' => 'Saisir un don',
                'villes' => $villes,
                'errors' => $errors,
                'old' => $data
            ]);
            return;
        }

        $ville_id = !empty($data['ville_id']) ? (int)$data['ville_id'] : null;

        $stmt = $this->db->prepare("
            INSERT INTO don (donateur, type_don, description, quantite, unite, ville_id, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['donateur'],
            $data['type_don'],
            $data['description'],
            $data['quantite'],
            $data['unite'],
            $ville_id
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Don enregistré'];
        Flight::redirect('/don');
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM don WHERE id = ?");
        $stmt->execute([$id]);
        $don = $stmt->fetch();
        if (!$don) Flight::halt(404, 'Don introuvable');

        $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
        Flight::render('saisie_dons', [
            'title' => 'Modifier le don',
            'villes' => $villes,
            'errors' => [],
            'old' => $don,
            'edit' => true,
            'id' => $id
        ]);
    }

    public function update($id)
    {
        $data = Flight::request()->data->getData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $villes = $this->db->query("SELECT * FROM ville ORDER BY nom")->fetchAll();
            Flight::render('saisie_dons', [
                'title' => 'Modifier le don',
                'villes' => $villes,
                'errors' => $errors,
                'old' => $data,
                'edit' => true,
                'id' => $id
            ]);
            return;
        }

        $stmt = $this->db->prepare("
            UPDATE don SET donateur=?, type_don=?, description=?, quantite=?, unite=?, ville_id=?
            WHERE id=?
        ");
        $stmt->execute([
            $data['donateur'],
            $data['type_don'],
            $data['description'],
            $data['quantite'],
            $data['unite'],
            !empty($data['ville_id']) ? (int)$data['ville_id'] : null,
            $id
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Don modifié'];
        Flight::redirect('/don');
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM don WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Don supprimé'];
        Flight::redirect('/don');
    }

    private function validate($data)
    {
        $errors = [];
        if (empty(trim($data['donateur'] ?? '')))    $errors['donateur']    = 'Le nom du donateur est obligatoire';
        if (empty($data['type_don']))                $errors['type_don']    = 'Le type de don est obligatoire';
        if (empty(trim($data['description'] ?? ''))) $errors['description'] = 'La description est obligatoire';
        if (empty($data['quantite']) || (float)$data['quantite'] <= 0) $errors['quantite'] = 'La quantité doit être positive';
        if (empty(trim($data['unite'] ?? '')))       $errors['unite']       = "L'unité est obligatoire";
        return $errors;
    }
}