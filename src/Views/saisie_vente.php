<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/saisie.css">


<?php $title = 'Vendre un don'; ?>

<div class="row justify-content-center">
<div class="col-12 col-lg-8 col-xl-7">

    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-3 p-3 bg-warning bg-opacity-10">
            <i class="bi bi-tags-fill text-warning fs-4"></i>
        </div>
        <div>
            <h1 class="h4 fw-bold mb-0">Vendre un don</h1>
            <p style="color: #94a3b8; opacity: 0.8; "class="mb-0 small">Vente d'un don recu avec remise configurable</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header py-3 px-4" style="background:linear-gradient(90deg,#fff4e0,#fff);border-bottom:1px solid #dee2e6;">
            <i class="bi bi-pencil-square me-2 text-warning"></i><span class="fw-600">Informations de la vente</span>
        </div>
        <div class="card-body p-4">

            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger d-flex gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0 fs-5"></i>
                <div>
                    <strong>Erreur(s) :</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <?php if (empty($dons)): ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    Aucun don disponible pour la vente (stock épuisé ou tout attribué).
                </div>
            <?php else: ?>

            <form action="<?= BASE_URL ?>/vente/store" method="POST">

                <div class="mb-4">
                    <label for="don_id" class="form-label fw-600">
                        <i class="bi bi-gift-fill me-1 text-warning"></i>Don à vendre <span class="text-danger">*</span>
                    </label>
                    <select id="don_id" style="font-size: 15px; color: #94a3b8; opacity: 0.8; " name="don_id"
                            class="form-select form-select-lg <?= isset($errors['don_id']) ? 'is-invalid' : '' ?>"
                            onchange="updateDonInfo(this)">
                        <option value="">-- Sélectionner un don --</option>
                        <?php foreach ($dons as $d): ?>
                        <option value="<?= $d['id'] ?>"
                                data-stock="<?= $d['stock_dispo'] ?>"
                                data-unite="<?= htmlspecialchars($d['unite']) ?>"
                                data-valeur="<?= $d['valeur_unitaire'] ?? '' ?>"
                                data-type="<?= $d['type_don'] ?>"
                                <?= (($old['don_id'] ?? '') == $d['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['donateur']) ?> — <?= htmlspecialchars($d['description']) ?>
                            (Dispo : <?= number_format($d['stock_dispo'], 0, ',', ' ') ?> <?= $d['unite'] ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <div id="stock-info" class="mt-2 <?= !empty($old['don_id']) ? '' : 'd-none' ?>">
                        <div class="alert alert-info py-2 px-3 mb-0 d-flex align-items-center gap-2 small">
                            <i class="bi bi-info-circle-fill"></i>
                            Stock disponible : <strong id="stock-value" class="ms-1"></strong>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="quantite" class="form-label fw-600">
                        <i class="bi bi-123 me-1 text-warning"></i>Quantité à vendre <span class="text-danger">*</span>
                    </label>
                    <input style="font-size: 15px; color: #94a3b8; opacity: 0.8; "type="number" id="quantite" name="quantite" min="0.01" step="0.01"
                           class="form-control form-control-lg <?= isset($errors['quantite']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($old['quantite'] ?? '') ?>"
                           oninput="calculerMontant()">
                    <?php if (isset($errors['quantite'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['quantite']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="valeur_unitaire" class="form-label fw-600">
                        <i class="bi bi-cash-coin me-1 text-warning"></i>Valeur unitaire réelle (Ar) <span class="text-danger">*</span>
                    </label>
                    <input style="font-size: 15px; color: #94a3b8; opacity: 0.8;" type="number" id="valeur_unitaire" name="valeur_unitaire" min="0.01" step="0.01"
                           class="form-control form-control-lg <?= isset($errors['valeur_unitaire']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($old['valeur_unitaire'] ?? '') ?>"
                           oninput="calculerMontant()">
                    <p style="margin-top: 0.25rem; font-size: 10px; color: #94a3b8; opacity: 0.8;">Valeur marchande réelle de l'unité</p>
                </div>

                <div class="mb-4">
                    <label for="remise_pct" class="form-label fw-600">
                        <i class="bi bi-percent me-1 text-warning"></i>Commission (%) <span class="fw-400">(0 = pas de commission)</span>
                    </label>
                    <div class="input-group input-group-lg">
                        <input style="font-size: 15px; color: #94a3b8; opacity: 0.8;" type="number" id="remise_pct" name="remise_pct" min="0" max="100" step="0.5"
                               class="form-control <?= isset($errors['remise_pct']) ? 'is-invalid' : '' ?>"
                               value="<?= htmlspecialchars($old['remise_pct'] ?? '0') ?>"
                               oninput="calculerMontant()">
                        <span class="input-group-text">%</span>
                    </div>
                </div>

                <div class="mb-4 p-3 rounded-3 border" style="background:#f0fdf4;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-600"><i class="bi bi-calculator me-2 text-success"></i>Montant total calculé</span>
                        <span id="montant-affiche" class="fs-4 fw-bold text-success">—</span>
                    </div>
                    <small class="text-muted">= Quantité × Valeur unitaire × (1 - Remise%/100)</small>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-600">
                        <i class="bi bi-card-text me-1"></i>Remarque <span class=" fw-400">(optionnel)</span>
                    </label>
                    <input style="font-size: 15px; color: #94a3b8; opacity: 0.8;" type="text" id="description" name="description" class="form-control"
                           placeholder="Ex : Vendu à un particulier..."
                           value="<?= htmlspecialchars($old['description'] ?? '') ?>">
                </div>

                <div class="d-flex gap-3 pt-2">
                    <button type="submit" class="btn btn-warning btn-lg px-5 fw-bold text-dark">
                        <i class="bi bi-check-circle-fill me-2"></i>Enregistrer la vente
                    </button>
                    <a href="<?= BASE_URL ?>/vente" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>

            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>

<script>
function updateDonInfo(select) {
    const opt   = select.options[select.selectedIndex];
    const info  = document.getElementById('stock-info');
    const val   = document.getElementById('stock-value');
    const vuVal = document.getElementById('valeur_unitaire');

    if (opt.dataset.stock) {
        val.textContent = parseFloat(opt.dataset.stock).toLocaleString('fr-FR') + ' ' + opt.dataset.unite;
        info.classList.remove('d-none');
        if (opt.dataset.valeur) vuVal.value = opt.dataset.valeur;
    } else {
        info.classList.add('d-none');
    }
    calculerMontant();
}

function calculerMontant() {
    const qte    = parseFloat(document.getElementById('quantite').value)        || 0;
    const valeur = parseFloat(document.getElementById('valeur_unitaire').value) || 0;
    const remise = parseFloat(document.getElementById('remise_pct').value)      || 0;
    const montant = qte * valeur * (1 - remise / 100);
    const el = document.getElementById('montant-affiche');
    if (qte > 0 && valeur > 0) {
        el.textContent = Math.round(montant).toLocaleString('fr-FR') + ' Ar';
        el.className = 'fs-4 fw-bold text-success';
    } else {
        el.textContent = '—';
        el.className = 'fs-4 fw-bold text-muted';
    }
}
</script>