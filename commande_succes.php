<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("location:connexion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eclat - Commande confirmée</title>

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
        h1, h2 { font-family: 'Playfair Display', serif; }
        .navbar-brand { font-family: 'Playfair Display', serif; font-size: 1.8rem; }
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
        .success-icon { font-size: 5rem; color: var(--color-primaire); }
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
                    <li class="nav-item"><a class="nav-link" href="panier.php"><i class="bi bi-bag"></i> Panier</a></li>
                    <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5 text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="bg-white rounded-3 shadow-sm p-5">
                    <i class="bi bi-check-circle-fill success-icon mb-4 d-block"></i>
                    <h1 class="mb-3">Commande confirmée !</h1>
                    <p class="text-muted mb-4">
                        Merci pour votre commande. Nous avons bien reçu votre demande et nous vous contacterons très prochainement.
                    </p>
                    <a href="produit.php" class="btn btn-eclat rounded-pill px-4 py-2 me-2">
                        <i class="bi bi-bag me-2"></i>Continuer mes achats
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                        <i class="bi bi-house me-2"></i>Accueil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
