<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/liste.css">

<header class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0"><i class="bi bi-gift-fill text-success"></i> Liste des dons</h1>
        <p class="mb-0 small"><?= count($dons) ?> don(s) enregistré(s)</p>
    </div>
    <a href="<?= BASE_URL ?>/don/create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nouveau
    </a>
</header>

<section class="card">
    <div class="card-body p-0">
        <?php if (empty($dons)): ?>
            <p class="text-center py-5 mb-0">Aucun don enregistré</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Donateur</th>
                        <th>Type</th>
                        <th>Article</th>
                        <th class="text-end">Quantité</th>
                        <th class="text-end">Attribué</th>
                        <th class="text-end">Disponible</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($dons as $i => $d):
                    $typeColor = match($d['type_don']) {
                        'nature' => 'success',
                        'materiau' => 'warning',
                        'argent' => 'primary',
                        default => 'secondary'
                    };
                    $dispo = $d['stock_dispo'] ?? $d['quantite'] - $d['total_attribue'];
                ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($d['donateur']) ?></td>
                    <td><span class="badge bg-<?= $typeColor ?>"><?= ucfirst($d['type_don']) ?></span></td>
                    <td><?= htmlspecialchars($d['description']) ?></td>
                    <td class="text-end fw-bold"><?= number_format($d['quantite'], 0, ',', ' ') ?> <?= htmlspecialchars($d['unite']) ?></td>
                    <td class="text-end text-success"><?= number_format($d['total_attribue'] ?? 0, 0, ',', ' ') ?> <?= htmlspecialchars($d['unite']) ?></td>
                    <td class="text-end <?= $dispo > 0 ? 'text-success' : 'text-danger' ?> fw-bold">
                        <?= number_format($dispo, 0, ',', ' ') ?> <?= htmlspecialchars($d['unite']) ?>
                        <?php if ($dispo <= 0): ?>
                            <span class="badge bg-danger ms-2">Épuisé / Vendu</span>
                        <?php elseif ($dispo < 0.2 * $d['quantite']): ?>
                            <span class="badge bg-warning ms-2">Faible</span>
                        <?php endif; ?>
                    </td>
                    <td class="small"><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>/don/edit/<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/don/delete/<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce don ?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</section>