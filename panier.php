<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user'])) {
    header("location:connexion.php");
    exit;
}

$id_client = $_SESSION['user']['id'];

$req = "SELECT p.id_panier, p.quantite, pr.id, pr.nom, pr.prix, pr.image
        FROM panier p
        JOIN produit pr ON p.id_produit = pr.id
        WHERE p.id_client = $id_client";
$res = mysqli_query($conn, $req);
if (!$res) {
    die("Erreur SQL: " . mysqli_error($conn));
}
$articles = mysqli_fetch_all($res, MYSQLI_ASSOC);

$total = 0;
foreach ($articles as $article) {
    $total += $article['prix'] * $article['quantite'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eclat - Mon Panier</title>

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
        .text-gold { color: var(--color-primaire); }
        .btn-eclat {
            background-color: var(--eclat-green);
            color: white;
            border: none;
        }
        .btn-eclat:hover {
            background-color: var(--color-primaire);
            color: white;
        }
        .table th { color: var(--eclat-green); }
        .product-img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; }
        .cart-summary {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

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
                    <li class="nav-item"><a class="nav-link active" href="panier.php"><i class="bi bi-bag"></i> Panier</a></li>
                    <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4">Mon Panier</h1>

        <?php if (empty($articles)): ?>
            <div class="text-center py-5">
                <i class="bi bi-bag-x display-3 text-muted"></i>
                <p class="mt-3 fs-5 text-muted">Votre panier est vide.</p>
                <a href="produit.php" class="btn btn-eclat rounded-pill mt-2">
                    <i class="bi bi-arrow-left me-2"></i>Continuer mes achats
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="bg-white rounded-3 shadow-sm p-3">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th>Sous-total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($articles as $a): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="<?= htmlspecialchars($a['image']) ?>" class="product-img" alt="<?= htmlspecialchars($a['nom']) ?>">
                                                <span class="fw-semibold"><?= htmlspecialchars($a['nom']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= number_format($a['prix'], 0) ?> DT</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <form method="POST" action="modifier_quantite.php">
                                                    <input type="hidden" name="id_panier" value="<?= $a['id_panier'] ?>">
                                                    <input type="hidden" name="action" value="moins">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:30px;height:30px;padding:0;">-</button>
                                                </form>
                                                <span class="mx-2 fw-bold"><?= $a['quantite'] ?></span>
                                                <form method="POST" action="modifier_quantite.php">
                                                    <input type="hidden" name="id_panier" value="<?= $a['id_panier'] ?>">
                                                    <input type="hidden" name="action" value="plus">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:30px;height:30px;padding:0;">+</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-gold"><?= number_format($a['prix'] * $a['quantite'], 0) ?> DT</td>
                                        <td>
                                            <a href="supprimer_panier.php?id_panier=<?= $a['id_panier'] ?>"
                                               class="btn btn-sm btn-outline-danger rounded-pill"
                                               onclick="return confirm('Supprimer cet article ?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <a href="produit.php" class="btn btn-outline-secondary rounded-pill mt-3">
                        <i class="bi bi-arrow-left me-2"></i>Continuer mes achats
                    </a>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary p-4">
                        <h5 class="mb-3">Récapitulatif</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total</span>
                            <span><?= number_format($total, 0) ?> DT</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Livraison</span>
                            <span class="text-success">Gratuite</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                            <span>Total</span>
                            <span class="text-gold"><?= number_format($total, 0) ?> DT</span>
                        </div>
                        <a href="confirmation_commande.php" class="btn btn-eclat w-100 rounded-pill py-2">
                            <i class="bi bi-bag-check me-2"></i>Commander
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
