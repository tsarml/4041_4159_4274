<?php $title = 'Accueil'; ?>

<style>
/* ── Fonts ── */
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Crimson+Pro:ital,wght@0,300;0,400;1,300&display=swap');

/* ── Variables cohérentes avec style.css ── */
:root {
    --noir:    #0f172a;
    --nuit:    #1e293b;
    --bord:    #334155;
    --vert:    #10b981;
    --vert2:   #059669;
    --texte:   #94a3b8;
    --blanc:   #f1f5f9;
}

/* ── HERO ── */
.hero {
    position: relative;
    min-height: 92vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: var(--noir);
    margin: -1.5rem -1.5rem 0;
    padding: 4rem 2rem;
}

.hero-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 70% 40%, rgba(16,185,129,.12) 0%, transparent 60%),
        radial-gradient(ellipse 50% 50% at 20% 80%, rgba(15,23,42,.9) 0%, transparent 70%);
    z-index: 1;
}

.hero-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: .25;
    filter: grayscale(.4) brightness(.6);
    z-index: 0;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 680px;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(16,185,129,.15);
    border: 1px solid rgba(16,185,129,.4);
    color: var(--vert);
    font-size: .75rem;
    font-family: 'Crimson Pro', serif;
    letter-spacing: .15em;
    text-transform: uppercase;
    padding: .4rem 1rem;
    border-radius: 99px;
    margin-bottom: 2rem;
    animation: fadeDown .6s ease both;
}

.hero-titre {
    font-family: 'Cinzel', serif;
    font-size: clamp(2.2rem, 5vw, 3.8rem);
    font-weight: 700;
    color: var(--blanc);
    line-height: 1.1;
    margin-bottom: 1rem;
    animation: fadeDown .7s .1s ease both;
}

.hero-titre span {
    color: var(--vert);
}

.hero-sous {
    font-family: 'Crimson Pro', serif;
    font-size: clamp(1rem, 2vw, 1.25rem);
    font-style: italic;
    color: var(--texte);
    margin-bottom: 2.5rem;
    animation: fadeDown .7s .2s ease both;
}

.hero-btns {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    animation: fadeDown .7s .3s ease both;
}

.btn-hero-primary {
    background: var(--vert);
    color: #fff;
    border: none;
    padding: .85rem 2.2rem;
    border-radius: 8px;
    font-family: 'Cinzel', serif;
    font-size: .85rem;
    letter-spacing: .05em;
    text-decoration: none;
    transition: background .2s, transform .2s;
}
.btn-hero-primary:hover {
    background: var(--vert2);
    transform: translateY(-2px);
    color: #fff;
}

.btn-hero-outline {
    background: transparent;
    color: var(--blanc);
    border: 1px solid var(--bord);
    padding: .85rem 2.2rem;
    border-radius: 8px;
    font-family: 'Cinzel', serif;
    font-size: .85rem;
    letter-spacing: .05em;
    text-decoration: none;
    transition: border-color .2s, transform .2s;
}
.btn-hero-outline:hover {
    border-color: var(--vert);
    color: var(--vert);
    transform: translateY(-2px);
}

/* Compteur droit */
.hero-stats {
    position: absolute;
    right: 6%;
    bottom: 12%;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
    animation: fadeLeft .8s .4s ease both;
}

.hero-stat {
    background: rgba(30,41,59,.85);
    border: 1px solid var(--bord);
    border-left: 3px solid var(--vert);
    padding: 1rem 1.5rem;
    border-radius: 10px;
    backdrop-filter: blur(8px);
    text-align: right;
}

.hero-stat .num {
    font-family: 'Cinzel', serif;
    font-size: 1.8rem;
    color: var(--vert);
    display: block;
    line-height: 1;
}

.hero-stat .lbl {
    font-family: 'Crimson Pro', serif;
    font-size: .8rem;
    color: var(--texte);
    letter-spacing: .08em;
    text-transform: uppercase;
}

/* ── LIGNE SÉPARATRICE ── */
.divider {
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--vert), transparent);
    margin: 0 -1.5rem;
}

/* ── SECTION TEXTE MALAGASY ── */
.section-mala {
    padding: 5rem 0;
}

.section-mala .label {
    font-family: 'Cinzel', serif;
    font-size: .7rem;
    letter-spacing: .25em;
    text-transform: uppercase;
    color: var(--vert);
    margin-bottom: 1rem;
}

.section-mala h2 {
    font-family: 'Cinzel', serif;
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    color: var(--blanc);
    margin-bottom: 2rem;
    line-height: 1.3;
}

.mala-quote {
    border-left: 3px solid var(--vert);
    padding: 1.2rem 1.8rem;
    background: rgba(16,185,129,.05);
    border-radius: 0 10px 10px 0;
    margin-bottom: 2.5rem;
    font-family: 'Crimson Pro', serif;
    font-size: 1.15rem;
    font-style: italic;
    color: var(--blanc);
    line-height: 1.9;
}

.mala-body {
    font-family: 'Crimson Pro', serif;
    font-size: 1.05rem;
    color: var(--texte);
    line-height: 1.9;
}

.mala-body p + p {
    margin-top: 1.2rem;
}

/* ── CARTES PRÉVENTION ── */
.section-prev {
    padding: 4rem 0;
    background: var(--nuit);
    margin: 0 -1.5rem;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

.prev-card {
    background: var(--noir);
    border: 1px solid var(--bord);
    border-radius: 14px;
    padding: 2rem 1.5rem;
    height: 100%;
    transition: border-color .25s, transform .25s;
    position: relative;
    overflow: hidden;
}

.prev-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--vert), transparent);
    opacity: 0;
    transition: opacity .25s;
}

.prev-card:hover {
    border-color: var(--vert);
    transform: translateY(-4px);
}

.prev-card:hover::before {
    opacity: 1;
}

.prev-icon {
    width: 52px;
    height: 52px;
    background: rgba(16,185,129,.12);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.2rem;
    font-size: 1.4rem;
    color: var(--vert);
}

.prev-card h5 {
    font-family: 'Cinzel', serif;
    font-size: .95rem;
    color: var(--blanc);
    margin-bottom: .8rem;
}

.prev-card p {
    font-family: 'Crimson Pro', serif;
    font-size: .95rem;
    color: var(--texte);
    line-height: 1.7;
    margin: 0;
}

/* ── CTA FINAL ── */
.section-cta {
    padding: 5rem 0;
    text-align: center;
}

.section-cta h3 {
    font-family: 'Cinzel', serif;
    font-size: clamp(1.4rem, 3vw, 2rem);
    color: var(--blanc);
    margin-bottom: 1rem;
}

.section-cta p {
    font-family: 'Crimson Pro', serif;
    font-size: 1.1rem;
    color: var(--texte);
    margin-bottom: 2rem;
}

/* ── Animations ── */
@keyframes fadeDown {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeLeft {
    from { opacity: 0; transform: translateX(20px); }
    to   { opacity: 1; transform: translateX(0); }
}

@media (max-width: 768px) {
    .hero-stats { display: none; }
    .hero { min-height: 70vh; }
}
</style>

<!-- ═══════════════════════════════════ HERO ═══════════════════════════════════ -->
<section class="hero">
    <img class="hero-img"
         src="https://images.unsplash.com/photo-1547683905-f686c993aae5?w=1400&q=80"
         alt="Catastrophe naturelle Madagascar">
    <div class="hero-bg"></div>

    <div class="hero-content">
        <div class="hero-badge">
            <i class="bi bi-shield-fill-check"></i>
            Bureau National de Gestion des Risques et des Catastrophes
        </div>
        <h1 class="hero-titre">
            Miaro ny Firenena,<br>
            <span>Miaraka isika.</span>
        </h1>
        <p class="hero-sous">
            Protecting the Nation — Together we stand.<br>
            Système de suivi des dons et distributions aux sinistrés.
        </p>
        <div class="hero-btns">
            <a href="<?= BASE_URL ?>/dashboard" class="btn-hero-primary">
                <i class="bi bi-grid-fill me-2"></i>Accéder au tableau de bord
            </a>
            <a href="<?= BASE_URL ?>/besoin/create" class="btn-hero-outline">
                <i class="bi bi-clipboard-heart me-2"></i>Saisir un besoin
            </a>
        </div>
    </div>

    <div class="hero-stats">
        <div class="hero-stat">
            <span class="num">24/7</span>
            <span class="lbl">Surveillance active</span>
        </div>
        <div class="hero-stat">
            <span class="num">6</span>
            <span class="lbl">Modules de gestion</span>
        </div>
        <div class="hero-stat">
            <span class="num">100%</span>
            <span class="lbl">Traçabilité des dons</span>
        </div>
    </div>
</section>

<div class="divider"></div>

<!-- ═══════════════════════════ TEXTE MALAGASY ═══════════════════════════════ -->
<section class="section-mala">
    <div class="row">
        <div class="col-lg-7">
            <p class="label">Hafatra ho an'ny vahoaka</p>
            <h2>Misoroka ny loza,<br>miaro ny ankohonanao.</h2>

            <div class="mala-quote">
                « Ny fiarovana ny firenena dia adidy iombonana —
                ny fiombonan'ny vahoaka no hery lehibe indrindra eo
                anatrehan'ny loza voajanahary. »
            </div>

            <div class="mala-body">
                <p>
                    <strong style="color:var(--blanc);">Ny tany Malagasy dia miatrika loza voajanahary maro</strong>
                    toy ny rivodoza, tondra-drano, sy horohoron-tany.
                    Ireo loza ireo dia miteraka faharavan'ny trano, fahaverezan'ny fanana,
                    ary matetika dia very ain'ny olona.
                    Ny BNGRC dia miasa tsy an-kijanona mba hamaly ireo zavatra ireo.
                </p>
                <p>
                    Ny <strong style="color:var(--blanc);">fisorohana</strong> no fiarovana mahomby indrindra.
                    Tokony ho fantatr'ny fianakaviana tsirairay ny dingana tokony atao :
                    ny mifindra any amin'ny toerana azo antoka rehefa misy filazana loza,
                    ny mitahiry sakafo sy rano ampy ho an'ny andro maromaro,
                    ary ny mahalala ny lalana ho any amin'ny foibe fangonana akaiky.
                </p>
                <p>
                    <strong style="color:var(--blanc);">Miaraha miasa isika</strong> —
                    ny fanolorana fanampiana, ny fizarana vaovao marina,
                    ary ny fanarenana ny vondron'olona voakasika
                    dia samy zava-dehibe avokoa.
                    Ny rindranasa BNGRC dia natao mba hanamora
                    ny fanaraha-maso ny fanampiana sy ny fizarana amin'ny mahantra.
                </p>
            </div>
        </div>

        <div class="col-lg-5 d-flex align-items-center justify-content-center mt-4 mt-lg-0">
            <div style="position:relative;">
                <img src="https://images.unsplash.com/photo-1584036561566-baf8f5f1b144?w=600&q=80"
                     alt="Aide humanitaire"
                     style="width:100%;border-radius:16px;border:1px solid var(--bord);
                            box-shadow: 0 0 60px rgba(16,185,129,.15);">
                <div style="position:absolute;bottom:-1rem;right:-1rem;
                            background:var(--vert);color:#fff;
                            padding:.8rem 1.4rem;border-radius:10px;
                            font-family:'Cinzel',serif;font-size:.85rem;
                            box-shadow:0 4px 20px rgba(16,185,129,.4);">
                    <i class="bi bi-heart-fill me-1"></i>Miaraka isika
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════ CARTES PRÉVENTION ════════════════════════════ -->
<section class="section-prev">
    <p class="label text-center mb-2" style="font-family:'Cinzel',serif;font-size:.7rem;letter-spacing:.25em;text-transform:uppercase;color:var(--vert);">
        Fepetra fiarovana
    </p>
    <h2 class="text-center mb-4"
        style="font-family:'Cinzel',serif;color:var(--blanc);font-size:clamp(1.3rem,3vw,1.9rem);">
        Dimy dingana fototra ho an'ny fiarovana
    </h2>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="prev-card">
                <div class="prev-icon"><i class="bi bi-broadcast"></i></div>
                <h5>Mandrenesà ny filazana</h5>
                <p>Araho hatrany ny vaovao avy amin'ny fitaovana fampitam-baovao ofisialy sy ny fampitondraan'ny manam-pahefana.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="prev-card">
                <div class="prev-icon"><i class="bi bi-bag-fill"></i></div>
                <h5>Omeo sakafo sy rano</h5>
                <p>Tahiry hatrany ny sakafo, rano fisotro, fanafody fototra ary fitaovana ilaina ho an'ny andro telo farafahakely.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="prev-card">
                <div class="prev-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <h5>Fantaro ny lalana fandosirana</h5>
                <p>Fantaro tsara ny lalana mankany amin'ny foibe fangonana akaiky indrindra amin'ny fonenanao.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="prev-card">
                <div class="prev-icon"><i class="bi bi-people-fill"></i></div>
                <h5>Miara-miasa ny vondron'olona</h5>
                <p>Mifampikarakara amin'ny mpiray tanana. Ny fiaraha-miasan'ny fiarahamonina no fiarovana mateza indrindra.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="prev-card">
                <div class="prev-icon"><i class="bi bi-house-fill"></i></div>
                <h5>Aoharo ny tananao</h5>
                <p>Hevero ny fanamafisana ny trano mba hahazaka rivotra mahery sy ranonorana be. Hatanjaho ny rafi-pifehezana.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="prev-card">
                <div class="prev-icon"><i class="bi bi-heart-pulse-fill"></i></div>
                <h5>Fikarakarana ara-pahasalamana</h5>
                <p>Mianara ny voalohany fanampiana. Mitahiry fanafody fototra sy fanitsiana ary mahalala ny foibem-pahasalamana akaiky.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════ CTA FINAL ════════════════════════════════ -->
<section class="section-cta">
    <h3>Vonona hamaly ny loza —<br>Entro ny fanampianao.</h3>
    <p>Saisir les besoins, enregistrer les dons, suivre les attributions.<br>
       Chaque action compte pour les familles sinistrées.</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="<?= BASE_URL ?>/dashboard" class="btn-hero-primary">
            <i class="bi bi-grid-fill me-2"></i>Tableau de bord
        </a>
        <a href="<?= BASE_URL ?>/don/create" class="btn-hero-outline" style="display:inline-flex;align-items:center;">
            <i class="bi bi-gift me-2"></i>Enregistrer un don
        </a>
    </div>
</section>