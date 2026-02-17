
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/liste.css">

<?php $title = 'Liste des ventes'; ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">
            <i class="bi bi-tags-fill text-warning me-2"></i>Liste des ventes
        </h1>
        <p style="color: #94a3b8; opacity: 0.8; " class="mb-0 small">
            <?= count($ventes) ?> vente(s) —
            Total : <strong class="text-success"><?= number_format($total, 0, ',', ' ') ?> Ar</strong>
        </p>
    </div>
    <a href="<?= BASE_URL ?>/vente/create" class="btn btn-warning fw-bold">
        <i class="bi bi-plus-circle me-1"></i>Nouvelle vente
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (empty($ventes)): ?>
            <div class="text-center py-5 ">
                <i class="bi bi-tags-fill text-warning me-2"></i>
                <p style="color: #94a3b8; opacity: 0.8; " class="mt-3">Aucune vente enregistrée.</p>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
                <thead class="text-white" style="background:#1a4f8a;">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th>Don vendu</th>
                        <th>Donateur</th>
                        <th class="text-end">Quantité</th>
                        <th class="text-end">Val. unitaire</th>
                        <th class="text-end">Remise</th>
                        <th class="text-end">Montant total</th>
                        <th>Remarque</th>
                        <th>Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($ventes as $i => $v): ?>
                <tr>
                    <td class="px-4 text-muted"><?= $i + 1 ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($v['don_desc']) ?></td>
                    <td class="text-muted small"><?= htmlspecialchars($v['donateur']) ?></td>
                    <td class="text-end">
                        <?= number_format($v['quantite'], 2, ',', ' ') ?>
                        <small class="text-muted"><?= htmlspecialchars($v['unite']) ?></small>
                    </td>
                    <td class="text-end"><?= number_format($v['valeur_unitaire'], 0, ',', ' ') ?> Ar</td>
                    <td class="text-end">
                        <?php if ($v['remise_pct'] > 0): ?>
                            <span class="badge bg-warning text-dark"><?= $v['remise_pct'] ?>%</span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end fw-bold text-success">
                        <?= number_format($v['montant_total'], 0, ',', ' ') ?> Ar
                    </td>
                    <td class="text-muted small"><?= htmlspecialchars($v['description'] ?? '—') ?></td>
                    <td class="text-muted small"><?= date('d/m/Y', strtotime($v['created_at'])) ?></td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>/vente/delete/<?= $v['id'] ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Supprimer cette vente ?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot style="background:#f8f9fa;">
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-end fw-bold">TOTAL</td>
                        <td class="text-end fw-bold text-success fs-6">
                            <?= number_format($total, 0, ',', ' ') ?> Ar
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>