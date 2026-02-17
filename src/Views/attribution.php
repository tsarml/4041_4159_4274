<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/saisie.css">


<div class="row justify-content-center">
    <section class="col-lg-8">
        <header class="mb-4">
            <h1 class="h4 fw-bold"><i class="bi bi-arrow-left-right text-primary"></i> Nouvelle attribution</h1>
            <p class="small">Attribuez un don à un besoin</p>
        </header>
        
        <article class="card">
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <strong>Erreur :</strong>
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/attribution/store">
                    <div class="mb-3">
                        <label for="besoin_id" class="form-label">
                            <i class="bi bi-clipboard-heart-fill text-danger"></i> Besoin à couvrir <span class="text-danger">*</span>
                        </label>
                        <select id="besoin_id" name="besoin_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($besoins as $b): ?>
                            <option value="<?= $b['id'] ?>" data-unite="<?= htmlspecialchars($b['unite']) ?>" <?= (($old['besoin_id'] ?? '') == $b['id']) ? 'selected' : '' ?>>
                                [<?= htmlspecialchars($b['ville_nom']) ?>] <?= htmlspecialchars($b['description']) ?> — Restant: <?= number_format($b['restant'], 0, ',', ' ') ?> <?= $b['unite'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="don_id" class="form-label">
                            <i class="bi bi-gift-fill text-success"></i> Don à utiliser <span class="text-danger">*</span>
                        </label>
                        <select id="don_id" name="don_id" class="form-select" onchange="updateStock(this)" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($dons as $d): ?>
                            <option value="<?= $d['id'] ?>" data-stock="<?= $d['stock_dispo'] ?>" data-unite="<?= htmlspecialchars($d['unite']) ?>" <?= (($old['don_id'] ?? '') == $d['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['donateur']) ?> — <?= htmlspecialchars($d['description']) ?> (Dispo: <?= number_format($d['stock_dispo'], 0, ',', ' ') ?> <?= $d['unite'] ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div id="stock-info" class="mt-2 d-none">
                            <div class="alert alert-info py-2 mb-0">
                                <i class="bi bi-info-circle"></i> Stock disponible: <strong id="stock-value"></strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="quantite" class="form-label">
                            <i class="bi bi-123 text-primary"></i> Quantité à attribuer <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="quantite" name="quantite" class="form-control" step="0.01" min="0.01" value="<?= htmlspecialchars($old['quantite'] ?? '') ?>" required>
                        <small class="text-warning">⚠️ La quantité ne peut pas dépasser le stock disponible</small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Important:</strong> Le besoin et le don doivent avoir la même unité (validation automatique)
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Confirmer
                        </button>
                        <a href="<?= BASE_URL ?>/attribution" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </article>
    </section>
</div>

<script>
function updateStock(select) {
    const opt = select.options[select.selectedIndex];
    const info = document.getElementById('stock-info');
    const val = document.getElementById('stock-value');
    if (opt.dataset.stock) {
        val.textContent = parseFloat(opt.dataset.stock).toLocaleString('fr-FR') + ' ' + opt.dataset.unite;
        info.classList.remove('d-none');
    } else {
        info.classList.add('d-none');
    }
}
</script>