<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/saisie.css">

<div class="row justify-content-center">
    <section class="col-lg-8">
        <header class="mb-4">
            <h1 class="h4 fw-bold"><i class="bi bi-clipboard-heart-fill text-success"></i> <?= $title ?></h1>
            <p class="small">Enregistrez un besoin pour une ville sinistrée</p>
        </header>
        
        <article class="card">
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <strong>Erreurs :</strong>
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/besoin/<?= isset($edit) ? 'update/'.$id : 'store' ?>">
                    <div class="mb-3">
                        <label for="ville_id" class="form-label">Ville <span class="text-danger">*</span></label>
                        <select id="ville_id" name="ville_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($villes as $v): ?>
                            <option value="<?= $v['id'] ?>" <?= (($old['ville_id'] ?? '') == $v['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($v['nom']) ?>
                                <?php if (!empty($v['region'])): ?>(<?= htmlspecialchars($v['region']) ?>)<?php endif; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type de besoin <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="type_besoin" id="type_nature" value="nature" <?= (($old['type_besoin'] ?? '') === 'nature') ? 'checked' : '' ?> required>
                                <label class="btn btn-outline-success w-100" for="type_nature">
                                    <i class="bi bi-basket2-fill d-block fs-3"></i>
                                    <small>Nature</small>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="type_besoin" id="type_materiau" value="materiau" <?= (($old['type_besoin'] ?? '') === 'materiau') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-warning w-100" for="type_materiau">
                                    <i class="bi bi-tools d-block fs-3"></i>
                                    <small>Matériaux</small>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="type_besoin" id="type_argent" value="argent" <?= (($old['type_besoin'] ?? '') === 'argent') ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary w-100" for="type_argent">
                                    <i class="bi bi-cash-coin d-block fs-3"></i>
                                    <small>Argent</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <input type="text" id="description" name="description" class="form-control" value="<?= htmlspecialchars($old['description'] ?? '') ?>" placeholder="Ex: Riz blanc" required>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                            <input type="number" id="quantite" name="quantite" class="form-control" step="0.01" min="0.01" value="<?= htmlspecialchars($old['quantite'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="unite" class="form-label">Unité <span class="text-danger">*</span></label>
                            <select id="unite" name="unite" class="form-select" required>
                                <option value="">-- Choisir --</option>
                                <optgroup label="Poids/Volume">
                                    <option value="kg" <?= (($old['unite'] ?? '') === 'kg') ? 'selected' : '' ?>>kg</option>
                                    <option value="litre" <?= (($old['unite'] ?? '') === 'litre') ? 'selected' : '' ?>>litre</option>
                                    <option value="sac" <?= (($old['unite'] ?? '') === 'sac') ? 'selected' : '' ?>>sac</option>
                                </optgroup>
                                <optgroup label="Matériaux">
                                    <option value="feuille" <?= (($old['unite'] ?? '') === 'feuille') ? 'selected' : '' ?>>feuille</option>
                                    <option value="unite" <?= (($old['unite'] ?? '') === 'unite') ? 'selected' : '' ?>>unité</option>
                                </optgroup>
                                <optgroup label="Argent">
                                    <option value="Ar" <?= (($old['unite'] ?? '') === 'Ar') ? 'selected' : '' ?>>Ar</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3" id="prix_div" style="display:none;">
                            <label for="prix_unitaire" class="form-label">Prix unitaire (Ar)</label>
                            <input type="number" id="prix_unitaire" name="prix_unitaire" class="form-control" step="0.01" min="0" value="<?= htmlspecialchars($old['prix_unitaire'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> <?= isset($edit) ? 'Modifier' : 'Enregistrer' ?>
                        </button>
                        <a href="<?= BASE_URL ?>/besoin" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </article>
    </section>
</div>

<script>
document.querySelectorAll('input[name="type_besoin"]').forEach(r => {
    r.addEventListener('change', function() {
        document.getElementById('prix_div').style.display = 
            (this.value === 'nature' || this.value === 'materiau') ? 'block' : 'none';
    });
});
const checked = document.querySelector('input[name="type_besoin"]:checked');
if (checked) checked.dispatchEvent(new Event('change'));
</script>