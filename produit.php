<?php
session_start();
require_once "config.php";

$is_connected = isset($_SESSION['user']);
$id_client = $is_connected ? $_SESSION['user']['id'] : null;

$res_max = mysqli_query($conn, "SELECT MAX(prix) as max_prix FROM produit");
$row_max = mysqli_fetch_assoc($res_max);
$prix_max_global = (int)$row_max['max_prix'];
 
$categorie_filtre = isset($_GET['categorie']) ? intval($_GET['categorie']) : 0;
$prix_min = isset($_GET['prix_min']) ? max(0, intval($_GET['prix_min'])) : 0;
$prix_max = isset($_GET['prix_max']) ? min($prix_max_global, intval($_GET['prix_max'])) : $prix_max_global;
if ($prix_min > $prix_max) { $prix_min = 0; $prix_max = $prix_max_global; }

// --- Pagination ---
$produits_par_page = 8;
$page_courante = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// --- Conditions SQL ---
$where_parts = [
    "p.prix >= $prix_min",
    "p.prix <= $prix_max",
];
if ($categorie_filtre > 0) {
    $where_parts[] = "p.categorie = $categorie_filtre";
}
$where = "WHERE " . implode(" AND ", $where_parts);

// --- Compte total pour pagination ---
$res_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM produit p JOIN categorie c ON p.categorie = c.id $where");
if (!$res_count) { die("Erreur SQL: " . mysqli_error($conn)); }
$total_produits = (int)mysqli_fetch_assoc($res_count)['total'];
$total_pages = max(1, (int)ceil($total_produits / $produits_par_page));
if ($page_courante > $total_pages) { $page_courante = $total_pages; }
$offset = ($page_courante - 1) * $produits_par_page;

// --- Récupérer les produits de la page ---
$req = "SELECT p.*, c.nom AS categorie_nom FROM produit p JOIN categorie c ON p.categorie = c.id $where ORDER BY p.id LIMIT $produits_par_page OFFSET $offset";
$res = mysqli_query($conn, $req);
if (!$res) { die("Erreur SQL: " . mysqli_error($conn)); }
$produits = mysqli_fetch_all($res, MYSQLI_ASSOC);

// --- Récupérer les catégories ---
$res_cats = mysqli_query($conn, "SELECT * FROM categorie");
$categories = mysqli_fetch_all($res_cats, MYSQLI_ASSOC);

// --- Helper : construire une URL en conservant les filtres actifs ---
function buildUrl($overrides = []) {
    global $categorie_filtre, $prix_min, $prix_max, $prix_max_global;
    $params = array_merge([
        'categorie' => $categorie_filtre,
        'prix_min'  => $prix_min,
        'prix_max'  => $prix_max,
        'page'      => 1,
    ], $overrides);
    if ((int)$params['categorie'] === 0)            unset($params['categorie']);
    if ((int)$params['prix_min']  === 0)            unset($params['prix_min']);
    if ((int)$params['prix_max']  >= $prix_max_global) unset($params['prix_max']);
    if ((int)$params['page']      === 1)            unset($params['page']);
    return 'produit.php' . (empty($params) ? '' : '?' . http_build_query($params));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eclat - Collections</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --color-primaire: #c5a059;
            --eclat-green: #0d2323;
            --eclat-beige: #f3e9dc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f4f0;
            color: var(--eclat-green);
        }

        h1, h2 {
            font-family: 'Playfair Display', serif;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
        }

        .card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .card:hover {
            transform: translateY(-10px);
        }

        /* --- Uniformiser les cartes produits (aligner prix/bouton) --- */
        .product-card {
            display: flex;
            flex-direction: column;
        }

        .product-card .product-image {
            height: 260px;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .product-card .product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .product-card .card-body {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .product-card .price-row {
            margin-top: auto;
        }

        .text-gold {
            color: var(--color-primaire);
        }

        .btn-ajouter {
            background-color: var(--eclat-green);
            color: white;
            border: none;
        }

        .btn-ajouter:hover {
            background-color: var(--color-primaire);
            color: white;
        }

        /* --- Filtre carte --- */
        .filter-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            padding: 1.5rem;
            margin-bottom: 1.75rem;
        }

        /* --- Dual range slider --- */
        .range-slider-container {
            position: relative;
            height: 36px;
            margin: 8px 4px 4px;
        }

        .slider-track-base,
        .slider-track-active {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            height: 5px;
            border-radius: 3px;
            pointer-events: none;
        }

        .slider-track-base  { width: 100%; background: #ddd; }
        .slider-track-active { background: var(--color-primaire); }

        .range-slider-container input[type="range"] {
            position: absolute;
            width: 100%;
            background: transparent;
            pointer-events: none;
            -webkit-appearance: none;
            appearance: none;
            height: 5px;
            top: 50%;
            transform: translateY(-50%);
            margin: 0;
            padding: 0;
            outline: none;
        }

        .range-slider-container input[type="range"]::-webkit-slider-thumb {
            pointer-events: all;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--color-primaire);
            cursor: pointer;
            -webkit-appearance: none;
            border: 3px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
            transition: transform 0.15s;
        }

        .range-slider-container input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }

        .range-slider-container input[type="range"]::-moz-range-thumb {
            pointer-events: all;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--color-primaire);
            cursor: pointer;
            border: 3px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        }

        /* --- Pagination --- */
        .pagination-container .btn {
            min-width: 40px;
        }

        /* Badge panier */
        .badge-panier {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Eclat</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="produit.php">Collections</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="panier.php">
                            <i class="bi bi-bag"></i> Panier
                            <span class="badge bg-warning text-dark badge-panier" id="panier-count">0</span>
                        </a>
                    </li>
                    <?php if($is_connected): ?>
                        <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu -->
    <div class="container py-5">
        <h1 class="mb-4">Nos Collections</h1>

        <!-- Filtres (catégorie + prix) -->
        <div class="filter-card">
            <div class="row align-items-start g-4">

                <!-- Filtre catégorie -->
                <div class="col-12 col-lg-6">
                    <p class="fw-semibold mb-2"><i class="bi bi-tag me-1"></i>Catégorie</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?= htmlspecialchars(buildUrl(['categorie' => 0, 'page' => 1])) ?>"
                           class="btn btn-sm rounded-pill <?= $categorie_filtre === 0 ? 'btn-ajouter' : 'btn-outline-secondary' ?>">
                            Toutes
                        </a>
                        <?php foreach($categories as $cat): ?>
                            <a href="<?= htmlspecialchars(buildUrl(['categorie' => (int)$cat['id'], 'page' => 1])) ?>"
                               class="btn btn-sm rounded-pill <?= $categorie_filtre === (int)$cat['id'] ? 'btn-ajouter' : 'btn-outline-secondary' ?>">
                                <?= htmlspecialchars($cat['nom']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Filtre prix (range slider) -->
                <div class="col-12 col-lg-5">
                    <p class="fw-semibold mb-2">
                        <i class="bi bi-currency-exchange me-1"></i>Prix :
                        <span class="text-gold" id="prix-display"><?= $prix_min ?> DT – <?= $prix_max ?> DT</span>
                    </p>
                    <div class="range-slider-container">
                        <div class="slider-track-base"></div>
                        <div class="slider-track-active" id="track-active"></div>
                        <input type="range" id="range-min" min="0" max="<?= $prix_max_global ?>"
                               value="<?= $prix_min ?>" step="10" aria-label="Prix minimum">
                        <input type="range" id="range-max" min="0" max="<?= $prix_max_global ?>"
                               value="<?= $prix_max ?>" step="10" aria-label="Prix maximum">
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-1">
                        <span>0 DT</span>
                        <span><?= $prix_max_global ?> DT</span>
                    </div>
                </div>

                <!-- Bouton réinitialiser -->
                <div class="col-12 col-lg-1 d-flex align-items-center">
                    <a href="produit.php" class="btn btn-sm btn-outline-secondary rounded-pill" title="Réinitialiser les filtres">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Info résultats -->
        <p class="text-muted small mb-3">
            <?= $total_produits ?> produit<?= $total_produits > 1 ? 's' : '' ?> trouvé<?= $total_produits > 1 ? 's' : '' ?>
            <?php if($total_pages > 1): ?> — Page <?= $page_courante ?> sur <?= $total_pages ?><?php endif; ?>
        </p>

        <!-- Grille de produits -->
        <div class="row g-4">
            <?php foreach($produits as $produit): ?>
                <div class="col-md-3">
                    <div class="card h-100 product-card">
                        <div class="product-image">
                            <img src="<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                        </div>

                        <div class="card-body text-center">
                            <span class="badge mb-2" style="background-color:#c5a059;color:#fff;"><?= htmlspecialchars($produit['categorie_nom']) ?></span>
                            <h5 class="card-title"><?= htmlspecialchars($produit['nom']) ?></h5>
                            <p class="card-text text-muted small"><?= htmlspecialchars(substr($produit['description'], 0, 50)) ?>...</p>

                            <div class="price-row">
                                <p class="text-gold fw-bold fs-5 mb-3"><?= number_format($produit['prix'], 0) ?> DT</p>

                                <?php if($is_connected): ?>
                                    <form method="POST" action="ajouter_panier.php">
                                        <input type="hidden" name="id_produit" value="<?= htmlspecialchars($produit['id']) ?>">
                                        <button type="submit" class="btn btn-ajouter btn-sm rounded-pill w-100">
                                            <i class="bi bi-cart-plus"></i> Ajouter au panier
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <a href="connexion.php" class="btn btn-ajouter btn-sm rounded-pill w-100">
                                        <i class="bi bi-box-arrow-in-right"></i> Se connecter pour ajouter
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if(empty($produits)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <p class="text-muted fs-5 mt-3">Aucun produit ne correspond à vos filtres.</p>
                    <a href="produit.php" class="btn btn-ajouter rounded-pill mt-2">Réinitialiser les filtres</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <nav class="pagination-container mt-5" aria-label="Pagination des produits">
            <div class="d-flex justify-content-center align-items-center flex-wrap gap-2">

                <!-- Précédent -->
                <?php if($page_courante > 1): ?>
                    <a href="<?= htmlspecialchars(buildUrl(['page' => $page_courante - 1])) ?>"
                       class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-chevron-left"></i> Précédent
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary rounded-pill px-3" disabled>
                        <i class="bi bi-chevron-left"></i> Précédent
                    </button>
                <?php endif; ?>

                <!-- Numéros de page -->
                <?php
                $window = 2; // pages autour de la page courante
                for($i = 1; $i <= $total_pages; $i++):
                    if ($i === 1 || $i === $total_pages || abs($i - $page_courante) <= $window):
                ?>
                    <a href="<?= htmlspecialchars(buildUrl(['page' => $i])) ?>"
                       class="btn rounded-pill px-3 <?= $i === $page_courante ? 'btn-ajouter' : 'btn-outline-secondary' ?>">
                        <?= $i ?>
                    </a>
                <?php
                    elseif (abs($i - $page_courante) === $window + 1):
                ?>
                    <span class="px-1 text-muted">…</span>
                <?php
                    endif;
                endfor;
                ?>

                <!-- Suivant -->
                <?php if($page_courante < $total_pages): ?>
                    <a href="<?= htmlspecialchars(buildUrl(['page' => $page_courante + 1])) ?>"
                       class="btn btn-outline-secondary rounded-pill px-3">
                        Suivant <i class="bi bi-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary rounded-pill px-3" disabled>
                        Suivant <i class="bi bi-chevron-right"></i>
                    </button>
                <?php endif; ?>

            </div>
        </nav>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Charger le nombre de produits dans le panier au chargement de la page
    function updatePanierCount() {
        fetch('get_panier_count.php')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('panier-count');
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Mettre à jour le badge au chargement
    document.addEventListener('DOMContentLoaded', function() {
        updatePanierCount();
    });

    // Mettre à jour le badge lors de l'ajout au panier
    document.querySelectorAll('form[action="ajouter_panier.php"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Pas de prévention du défaut, juste mise à jour après redirection
            setTimeout(updatePanierCount, 500);
        });
    });

    // Slider de prix
    (function () {
        const rangeMin    = document.getElementById('range-min');
        const rangeMax    = document.getElementById('range-max');
        const display     = document.getElementById('prix-display');
        const trackActive = document.getElementById('track-active');
        const maxVal      = parseInt(rangeMin.max, 10);

        function updateVisual() {
            const min = parseInt(rangeMin.value, 10);
            const max = parseInt(rangeMax.value, 10);
            display.textContent = min + ' DT\u2013' + max + ' DT';
            const leftPct  = (min / maxVal) * 100;
            const rightPct = (max / maxVal) * 100;
            trackActive.style.left  = leftPct + '%';
            trackActive.style.width = (rightPct - leftPct) + '%';
        }

        function applyPriceFilter() {
            const min = parseInt(rangeMin.value, 10);
            const max = parseInt(rangeMax.value, 10);
            const url = new URL(window.location.href);
            if (min === 0)       url.searchParams.delete('prix_min'); else url.searchParams.set('prix_min', min);
            if (max >= maxVal)   url.searchParams.delete('prix_max'); else url.searchParams.set('prix_max', max);
            url.searchParams.delete('page'); // retour page 1
            window.location.href = url.toString();
        }

        rangeMin.addEventListener('input', function () {
            if (parseInt(rangeMin.value, 10) > parseInt(rangeMax.value, 10)) {
                rangeMin.value = rangeMax.value;
            }
            updateVisual();
        });

        rangeMax.addEventListener('input', function () {
            if (parseInt(rangeMax.value, 10) < parseInt(rangeMin.value, 10)) {
                rangeMax.value = rangeMin.value;
            }
            updateVisual();
        });

        rangeMin.addEventListener('change', applyPriceFilter);
        rangeMax.addEventListener('change', applyPriceFilter);

        updateVisual();
    })();
    </script>

</body>
</html>
