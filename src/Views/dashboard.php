<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">

<header class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0"><i class="bi bi-grid-fill text-primary"></i> Tableau de bord</h1>
        <p class="mb-0 small ">Vue globale des besoins et distributions</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/besoin/create" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus-circle"></i> Besoin
        </a>
        <a href="<?= BASE_URL ?>/don/create" class="btn btn-sm btn-success">
            <i class="bi bi-gift"></i> Don
        </a>
        <a href="<?= BASE_URL ?>/reinitialiser" class="btn btn-sm btn-outline-danger fw-bold">
            <i class="bi bi-arrow-clockwise"></i> Réinitialiser
        </a>
    </div>
</header>

<?php if (empty($villes)): ?>
    <section class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-geo-alt text-muted" style="font-size:3rem;"></i>
            <p class="text-muted mt-3">Aucune ville enregistrée</p>
            <a href="<?= BASE_URL ?>/ville/create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Ajouter une ville
            </a>
        </div>
    </section>
<?php else: ?>
    <?php foreach ($villes as $ville): ?>
    <article class="card mb-4">
        <header class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-geo-alt-fill text-primary"></i>
                <strong><?= htmlspecialchars($ville['nom']) ?></strong>
                <?php if (!empty($ville['region'])): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($ville['region']) ?></span>
                <?php endif; ?>
            </div>
            <a href="<?= BASE_URL ?>/besoin/create" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-circle"></i> Besoin
            </a>
        </header>

        <div class="card-body p-0">
            <?php if (empty($ville['besoins'])): ?>
                <p class="text-center text-muted py-3 mb-0">Aucun besoin</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Article</th>
                            <th>Type</th>
                            <th class="text-end">Besoin</th>
                            <th class="text-end">Attribué</th>
                            <th class="text-end">Restant</th>
                            <th class="text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ville['besoins'] as $b):
                            $restant = max(0, $b['quantite'] - $b['total_attribue']);
                            $typeColor = match($b['type_besoin']) {
                                'nature'   => 'success',
                                'materiau' => 'warning',
                                'argent'   => 'primary',
                                default    => 'secondary'
                            };
                            $couvert = $b['total_attribue'] >= $b['quantite'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($b['description']) ?></td>
                            <td><span class="badge bg-<?= $typeColor ?>"><?= ucfirst($b['type_besoin']) ?></span></td>
                            <td class="text-end fw-bold"><?= number_format($b['quantite'], 0, ',', ' ') ?> <?= $b['unite'] ?></td>
                            <td class="text-end text-success"><?= number_format($b['total_attribue'], 0, ',', ' ') ?> <?= $b['unite'] ?></td>
                            <td class="text-end text-<?= $restant > 0 ? 'danger' : 'success' ?> fw-bold">
                                <?= number_format($restant, 0, ',', ' ') ?> <?= $b['unite'] ?>
                            </td>
                            <td class="text-center">
                                <?php if ($couvert): ?>
                                    <span class="badge bg-success">Couvert</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary text-dark">En attente</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($ville['nb_achats'] > 0): ?>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">
                <i class="bi bi-cart"></i> <?= $ville['nb_achats'] ?> achat(s)
            </small>
            <strong class="text-success"><?= number_format($ville['total_achats'], 0, ',', ' ') ?> Ar</strong>
        </div>
        <?php endif; ?>
    </article>
    <?php endforeach; ?>
<?php endif; ?>