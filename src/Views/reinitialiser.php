<?php $title = 'Reinitialiser les donnees'; ?>

<div class="row justify-content-center">
<div class="col-12 col-md-6 col-lg-5">
    <div class="card border-0 shadow-sm border-danger">
        <div class="card-header bg-danger text-white py-3">
            <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Attention — Action irréversible</h5>
        </div>
        <div class="card-body p-4 text-center">
            <div class="mb-4">
                <i class="bi bi-arrow-clockwise text-danger" style="font-size:4rem;"></i>
                <h4 class="mt-3 fw-bold">Réinitialiser les données</h4>
                <p class="text-muted">Cette action va <strong>supprimer toutes les données</strong> actuelles (ventes, attributions, achats, besoins, dons, villes) et <strong>restaurer les données initiales</strong> du professeur.</p>
            </div>

            <div class="alert alert-warning text-start">
                <strong>Ce qui sera supprimé :</strong>
                <ul class="mb-0 mt-1">
                    <li>Toutes les ventes</li>
                    <li>Toutes les attributions</li>
                    <li>Tous les achats</li>
                    <li>Tous les besoins</li>
                    <li>Tous les dons</li>
                    <li>Toutes les villes</li>
                </ul>
            </div>

            <div class="d-flex gap-3 justify-content-center mt-4">
                <form action="<?= BASE_URL ?>/reinitialiser/confirmer" method="POST">
                    <button type="submit" class="btn btn-danger btn-lg px-4 fw-bold">
                        <i class="bi bi-arrow-clockwise me-2"></i>Oui, réinitialiser
                    </button>
                </form>
                <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-secondary btn-lg px-4">
                    <i class="bi bi-x-circle me-2"></i>Annuler
                </a>
            </div>
        </div>
    </div>
</div>
</div>