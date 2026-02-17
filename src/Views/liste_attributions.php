<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/liste.css">

<header class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0"><i class="bi bi-arrow-left-right text-primary"></i> Liste des attributions</h1>
        <p class="mb-0 small"><?= count($attributions) ?> attribution(s)</p>
    </div>
    <a href="<?= BASE_URL ?>/attribution/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouvelle
    </a>
</header>

<section class="card">
    <div class="card-body p-0">
        <?php if (empty($attributions)): ?>
        <p class="text-center text-muted py-5 mb-0">Aucune attribution</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Ville</th>
                        <th>Besoin</th>
                        <th>Don (donateur)</th>
                        <th class="text-end">Quantité</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($attributions as $i => $a): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($a['ville_nom']) ?></td>
                    <td><?= htmlspecialchars($a['besoin_desc']) ?></td>
                    <td class="fw-bold">
                        <?= htmlspecialchars($a['don_desc']) ?>
                        <small class="d-block "><?= htmlspecialchars($a['donateur']) ?></small>
                    </td>
                    <td class="text-end fw-bold text-success"><?= number_format($a['quantite'], 0, ',', ' ') ?> <?= $a['unite'] ?></td>
                    <td class="small"><?= date('d/m/Y', strtotime($a['created_at'])) ?></td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>/attribution/delete/<?= $a['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette attribution ?')">
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