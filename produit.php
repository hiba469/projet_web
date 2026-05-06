<?php
session_start();
require_once "config.php";

// Vérifier que l'utilisateur est connecté
if(!isset($_SESSION['user'])) {
    header("location:connexion.php");
    exit;
}

$id_client = $_SESSION['user']['id'];

// Récupérer tous les produits
$req = "SELECT * FROM produit";
$res = mysqli_query($conn, $req);

if(!$res) {
    die("Erreur SQL: " . mysqli_error($conn));
}

$produits = mysqli_fetch_all($res, MYSQLI_ASSOC);
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
                    <li class="nav-item"><a class="nav-link" href="panier.php"><i class="bi bi-bag"></i> Panier</a></li>
                    <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu -->
    <div class="container py-5">
        <h1 class="mb-5">Nos Collections</h1>

        <div class="row g-4">
            <?php foreach($produits as $produit): ?>
                <div class="col-md-3">
                    <div class="card h-100">
                        <img src="<?= $produit['image'] ?>" class="card-img-top p-3" alt="<?= $produit['nom'] ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= $produit['nom'] ?></h5>
                            <p class="card-text text-muted small"><?= substr($produit['description'], 0, 50) ?>...</p>
                            <p class="text-gold fw-bold fs-5"><?= $produit['prix'] ?> DT</p>
                            <form method="POST" action="ajouter_panier.php">
                                <input type="hidden" name="id_produit" value="<?= $produit['id'] ?>">
                                <button type="submit" class="btn btn-ajouter btn-sm rounded-pill w-100">
                                    <i class="bi bi-cart-plus"></i> Ajouter au panier
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>