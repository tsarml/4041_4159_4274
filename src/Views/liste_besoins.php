<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/liste.css">

<header class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0"><i class="bi bi-clipboard-heart-fill text-success"></i> Liste des besoins</h1>
        <p class="mb-0 small"><?= count($besoins) ?> besoin(s) enregistré(s)</p>
    </div>
    <a href="<?= BASE_URL ?>/besoin/create" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nouveau
    </a>
</header>

<section class="card">
    <div class="card-body p-0">
        <?php if (empty($besoins)): ?>
        <p class="text-center py-5 mb-0">Aucun besoin enregistré</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Ville</th>
                        <th>Type</th>
                        <th>Article</th>
                        <th class="text-end">Besoin</th>
                        <th class="text-end">Attribué</th>
                        <th class="text-end">Restant</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($besoins as $i => $b):
                    $pct = $b['quantite'] > 0 ? min(100, round(($b['total_attribue']/$b['quantite'])*100)) : 0;
                    $restant = max(0, $b['quantite'] - $b['total_attribue']);
                    $typeColor = match($b['type_besoin']) {
                        'nature' => 'success',
                        'materiau' => 'warning',
                        'argent' => 'primary',
                        default => 'secondary'
                    };
                ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($b['ville_nom']) ?></td>
                    <td><span class="badge bg-<?= $typeColor ?>"><?= ucfirst($b['type_besoin']) ?></span></td>
                    <td><?= htmlspecialchars($b['description']) ?></td>
                    <td class="text-end fw-bold"><?= number_format($b['quantite'], 0, ',', ' ') ?> <?= $b['unite'] ?></td>
                    <td class="text-end text-success"><?= number_format($b['total_attribue'], 0, ',', ' ') ?> <?= $b['unite'] ?></td>
                    <td class="text-end text-<?= $restant > 0 ? 'danger' : 'success' ?> fw-bold"><?= number_format($restant, 0, ',', ' ') ?> <?= $b['unite'] ?></td>
                    <td class="text-center">
                        <?php if ($pct >= 100): ?>
                            <span class="badge bg-success">Couvert</span>
                        <?php elseif ($pct >= 50): ?>
                            <span class="badge bg-warning text-dark">Partiel</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Urgent</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>/besoin/edit/<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= BASE_URL ?>/besoin/delete/<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce besoin ?')">
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