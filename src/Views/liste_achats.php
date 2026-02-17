<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/liste.css">
<header class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-0"><i class="bi bi-cart text-info"></i> Liste des achats</h1>
        <p class="mb-0 small">Total: <?= number_format($total_achats, 2, ',', ' ') ?> Ar</p>
    </div>
    <a href="<?= BASE_URL ?>/achat/create" class="btn btn-info text-white">
        <i class="bi bi-plus-circle"></i> Nouveau
    </a>
</header>

<section class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>/achat" class="row g-2">
            <div class="col-md-4">
                <select name="ville_id" class="form-select">
                    <option value="">-- Toutes les villes --</option>
                    <?php foreach ($villes as $v): ?>
                    <option value="<?= $v['id'] ?>" <?= ($ville_filtre == $v['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['nom']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filtrer</button>
            </div>
            <?php if ($ville_filtre): ?>
            <div class="col-md-2">
                <a href="<?= BASE_URL ?>/achat" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</section>

<section class="card">
    <div class="card-body p-0">
        <?php if (empty($achats)): ?>
        <p class="text-center text-muted py-5 mb-0">Aucun achat</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Ville</th>
                        <th>Article</th>
                        <th class="text-end">Qté</th>
                        <th class="text-end">Prix U.</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($achats as $a): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($a['date_achat'])) ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($a['ville_nom']) ?></td>
                    <td><?= htmlspecialchars($a['besoin_desc']) ?></td>
                    <td class="text-end"><?= number_format($a['quantite'], 2, ',', ' ') ?> <?= $a['unite'] ?></td>
                    <td class="text-end"><?= number_format($a['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                    <td class="text-end fw-bold text-info"><?= number_format($a['montant_total'], 2, ',', ' ') ?> Ar</td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>/achat/delete/<?= $a['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')">
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