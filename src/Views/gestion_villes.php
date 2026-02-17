<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/gestion_villes.css">

<div class="row">
    <aside class="col-md-4">
        <section class="card">
            <header class="card-header bg-primary text-white">
                <i class="bi bi-geo-alt-fill"></i> <?= isset($edit) ? 'Modifier' : 'Ajouter' ?> une ville
            </header>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $e): ?>
                        <div><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="<?= BASE_URL ?>/ville/<?= isset($edit) ? 'update/'.$edit_id : 'store' ?>">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="region" class="form-label">Région</label>
                        <input type="text" id="region" name="region" class="form-control" value="<?= htmlspecialchars($old['region'] ?? '') ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle"></i> <?= isset($edit) ? 'Modifier' : 'Ajouter' ?>
                        </button>
                        <?php if (isset($edit)): ?>
                            <a href="<?= BASE_URL ?>/ville" class="btn btn-secondary">
                                <i class="bi bi-x"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </section>
    </aside>
    
    <section class="col-md-8">
        <article class="card">
            <header class="card-header bg-light">
                <i class="bi bi-list"></i> Villes (<?= count($villes) ?>)
            </header>
            <div class="card-body p-0">
                <?php if (empty($villes)): ?>
                <p class="text-center text-muted py-4 mb-0">Aucune ville enregistrée</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Ville</th>
                                <th>Région</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($villes as $i => $v): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($v['nom']) ?></td>
                            <td><?= htmlspecialchars($v['region'] ?? '—') ?></td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>/ville/edit/<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/ville/delete/<?= $v['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette ville ?')">
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
        </article>
    </section>
</div>