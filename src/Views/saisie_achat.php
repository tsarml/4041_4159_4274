<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/saisie.css">

<div class="row justify-content-center">
    <section class="col-lg-8">
        <header class="mb-4">
            <h1 class="h4 fw-bold"><i class="bi bi-cart-plus text-info"></i> Nouvel achat</h1>
            <p  class="small">Achetez des biens avec les dons en argent</p>
        </header>
        
        <article class="card">
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/achat/store">
                    <div class="mb-3">
                        <label for="ville_id" class="form-label">Ville <span class="text-danger">*</span></label>
                        <select id="ville_id" name="ville_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($villes as $v): ?>
                            <option value="<?= $v['id'] ?>" <?= (($old['ville_id'] ?? '') == $v['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($v['nom']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="don_id" class="form-label">Don en argent <span class="text-danger">*</span></label>
                        <select id="don_id" name="don_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($dons_argent as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= (($old['don_id'] ?? '') == $d['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['donateur']) ?> (<?= number_format($d['solde_dispo'], 2, ',', ' ') ?> Ar)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="besoin_id" class="form-label">Besoin à couvrir <span class="text-danger">*</span></label>
                        <select id="besoin_id" name="besoin_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($besoins as $b): ?>
                            <option value="<?= $b['id'] ?>" data-prix="<?= $b['prix_unitaire'] ?>" <?= (($old['besoin_id'] ?? '') == $b['id']) ? 'selected' : '' ?>>
                                [<?= htmlspecialchars($b['ville_nom']) ?>] <?= htmlspecialchars($b['description']) ?> — <?= number_format($b['prix_unitaire'], 2, ',', ' ') ?> Ar/<?= $b['unite'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                            <input type="number" id="quantite" name="quantite" class="form-control" step="0.01" min="0.01" value="<?= htmlspecialchars($old['quantite'] ?? '') ?>" required oninput="calculer()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Montant total</label>
                            <div class="form-control bg-light fw-bold" id="montant">0 Ar</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="date_achat" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" id="date_achat" name="date_achat" class="form-control" value="<?= htmlspecialchars($old['date_achat'] ?? date('Y-m-d')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="2"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Enregistrer
                        </button>
                        <a href="<?= BASE_URL ?>/achat" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </article>
    </section>
</div>

<script>
function calculer() {
    const select = document.getElementById('besoin_id');
    const prix = parseFloat(select.options[select.selectedIndex].dataset.prix) || 0;
    const qte = parseFloat(document.getElementById('quantite').value) || 0;
    document.getElementById('montant').textContent = (prix * qte).toLocaleString('fr-FR') + ' Ar';
}
document.getElementById('besoin_id').addEventListener('change', calculer);
</script>