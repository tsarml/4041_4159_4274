<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'BNGRC') ?> - BNGRC</title>
    <link href="<?= BASE_URL ?>/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/bootstrap/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/">
                <i class="bi bi-shield-fill-check"></i> BNGRC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/accueil">
                            <i class="bi bi-house-fill"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/dashboard">
                            <i class="bi bi-grid-fill"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-clipboard-heart-fill"></i> Besoins
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/besoin">Liste</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/besoin/create">Nouveau</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-gift-fill"></i> Dons
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/don">Liste</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/don/create">Nouveau</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-arrow-left-right"></i> Attributions
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/attribution">Liste</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/attribution/create">Nouvelle</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-cart-fill"></i> Achats
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/achat">Liste</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/achat/create">Nouvel achat</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-tags-fill"></i> Ventes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/vente">Liste</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/vente/create">Nouvelle vente</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/ville">
                            <i class="bi bi-geo-alt-fill"></i> Villes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/recap">
                            <i class="bi bi-graph-up"></i> Récap
                        </a>
                    </li>
                </ul>

                <a href="<?= BASE_URL ?>/reinitialiser" class="btn btn-outline-danger btn-sm fw-bold ms-3">
                    <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser
                </a>
            </div>
        </div>
    </nav>

    <main class="container-fluid py-4">
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show">
                <i class="bi bi-<?= $_SESSION['flash']['type'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php unset($_SESSION['flash']);
        endif; ?>

        <?= $content ?>
    </main>

    <footer class="text-center py-3 mt-5">
        <small>BNGRC - Gestion des dons <?= date('Y') ?><br>4041_4159_4274</small>
    </footer>

    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>