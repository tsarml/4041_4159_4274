<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/recap.css">

<div class="row justify-content-center">
    <section class="col-lg-8">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h4 fw-bold mb-0"><i class="bi bi-graph-up text-primary"></i> Récapitulation</h1>
            </div>
            <button onclick="actualiser()" class="btn btn-primary" id="btn-actu">
                <i class="bi bi-arrow-clockwise"></i> Actualiser
            </button>
        </header>

        <div class="alert alert-info">
            Dernière actualisation: <strong id="last-update">—</strong>
        </div>

        <div class="row g-3 mb-4">
            <article class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-clipboard-heart text-danger fs-1"></i>
                        <h3 id="besoins-total">—</h3>
                        <small class="text-muted">Besoins totaux</small>
                    </div>
                </div>
            </article>
            <article class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-check-circle text-success fs-1"></i>
                        <h3 id="taux">—%</h3>
                        <small class="text-muted">Satisfaction</small>
                    </div>
                </div>
            </article>
            <article class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-cash text-primary fs-1"></i>
                        <h3 id="total-entrees">—</h3>
                        <small class="text-muted">Total entrées</small>
                    </div>
                </div>
            </article>
            <article class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="bi bi-wallet2 text-info fs-1"></i>
                        <h3 id="solde">—</h3>
                        <small class="text-muted">Solde</small>
                    </div>
                </div>
            </article>
        </div>

        <article class="card">
            <div class="card-header">Détails</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Besoins satisfaits:</td>
                        <td class="text-end fw-bold" id="besoins-sat">—</td>
                    </tr>
                    <tr>
                        <td>Dons reçus:</td>
                        <td class="text-end fw-bold" id="dons-recus">—</td>
                    </tr>
                    <tr>
                        <td>Revenus ventes:</td>
                        <td class="text-end fw-bold" id="ventes">—</td>
                    </tr>
                    <tr>
                        <td>Total entrées:</td>
                        <td class="text-end fw-bold text-success" id="detail-entrees">—</td>
                    </tr>
                    <tr>
                        <td>Dons utilisés:</td>
                        <td class="text-end fw-bold text-danger" id="dons-util">—</td>
                    </tr>
                    <tr>
                        <td>Solde disponible:</td>
                        <td class="text-end fw-bold text-info" id="detail-solde">—</td>
                    </tr>
                </table>
            </div>
        </article>
    </section>
</div>

<script>
async function actualiser() {
    document.getElementById('btn-actu').disabled = true;
    
    try {
        const res = await fetch('<?= BASE_URL ?>/recap/data');
        const data = await res.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Erreur serveur');
        }
        
        document.getElementById('last-update').textContent = data.timestamp;
        document.getElementById('besoins-total').textContent = data.besoins_total.toLocaleString('fr-FR') + ' Ar';
        document.getElementById('taux').textContent = data.taux_satisfaction + '%';
        document.getElementById('total-entrees').textContent = data.total_entrees.toLocaleString('fr-FR') + ' Ar';
        document.getElementById('solde').textContent = data.solde.toLocaleString('fr-FR') + ' Ar';
        
        document.getElementById('besoins-sat').textContent = data.besoins_satisfaits.toLocaleString('fr-FR') + ' Ar';
        document.getElementById('dons-recus').textContent = data.dons_recus.toLocaleString('fr-FR') + ' Ar';
        document.getElementById('ventes').textContent = data.revenus_ventes.toLocaleString('fr-FR') + ' Ar (' + data.ventes_nb + ' vente(s))';
        document.getElementById('detail-entrees').textContent = data.total_entrees.toLocaleString('fr-FR') + ' Ar';
        document.getElementById('dons-util').textContent = data.dons_utilises.toLocaleString('fr-FR') + ' Ar';
        document.getElementById('detail-solde').textContent = data.solde.toLocaleString('fr-FR') + ' Ar';
        
    } catch (e) {
        alert('Erreur: ' + e.message);
        console.error(e);
    }
    
    document.getElementById('btn-actu').disabled = false;
}

actualiser();
</script>