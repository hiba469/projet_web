<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user'])) {
    header("location:connexion.php");
    exit;
}

$id_client = $_SESSION['user']['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirmer'])) {
    // Fetch cart items
    $req_items = "SELECT p.id_produit, p.quantite, pr.prix
                  FROM panier p
                  JOIN produit pr ON p.id_produit = pr.id
                  WHERE p.id_client = $id_client";
    $res_items = mysqli_query($conn, $req_items);
    $items = mysqli_fetch_all($res_items, MYSQLI_ASSOC);

    if (!empty($items)) {
        $montant = 0;
        foreach ($items as $item) {
            $montant += $item['prix'] * $item['quantite'];
        }
        $date = date('Y-m-d');

        mysqli_begin_transaction($conn);
        $ok = true;

        $req_cmd = "INSERT INTO commande (id_client, date, montant) VALUES ($id_client, '$date', $montant)";
        if (!mysqli_query($conn, $req_cmd)) { $ok = false; }
        $id_commande = mysqli_insert_id($conn);

        if ($ok) {
            foreach ($items as $item) {
                for ($i = 0; $i < $item['quantite']; $i++) {
                    $req_ligne = "INSERT INTO ligne_commande (id_commande, id_produit) VALUES ($id_commande, {$item['id_produit']})";
                    if (!mysqli_query($conn, $req_ligne)) { $ok = false; break 2; }
                }
            }
        }

        if ($ok) {
            $req_clear = "DELETE FROM panier WHERE id_client = $id_client";
            if (!mysqli_query($conn, $req_clear)) { $ok = false; }
        }

        if ($ok) {
            mysqli_commit($conn);
            header("location:commande_succes.php");
            exit;
        } else {
            mysqli_rollback($conn);
        }
    }
}

$req = "SELECT p.id_panier, p.quantite, pr.nom, pr.prix, pr.image
        FROM panier p
        JOIN produit pr ON p.id_produit = pr.id
        WHERE p.id_client = $id_client";
$res = mysqli_query($conn, $req);
if (!$res) {
    die("Erreur SQL: " . mysqli_error($conn));
}
$articles = mysqli_fetch_all($res, MYSQLI_ASSOC);

if (empty($articles)) {
    header("location:panier.php");
    exit;
}

$total = 0;
foreach ($articles as $a) {
    $total += $a['prix'] * $a['quantite'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eclat - Confirmation de commande</title>

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
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .summary-card {
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
                    <li class="nav-item"><a class="nav-link" href="panier.php"><i class="bi bi-bag"></i> Panier</a></li>
                    <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-2">Confirmation de commande</h1>
        <p class="text-muted mb-4">Veuillez vérifier votre commande avant de confirmer.</p>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="summary-card p-4">
                    <h5 class="mb-3">Articles commandés</h5>
                    <?php foreach ($articles as $a): ?>
                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                            <img src="<?= htmlspecialchars($a['image']) ?>" class="product-img" alt="<?= htmlspecialchars($a['nom']) ?>">
                            <div class="flex-grow-1">
                                <div class="fw-semibold"><?= htmlspecialchars($a['nom']) ?></div>
                                <div class="text-muted small">Quantité : <?= $a['quantite'] ?></div>
                                </div>
                            <div class="fw-bold text-gold"><?= number_format($a['prix'] * $a['quantite'], 0) ?> DT</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-card p-4">
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
                    <form method="POST">
                        <button type="submit" name="confirmer" class="btn btn-eclat w-100 rounded-pill py-2">
                            <i class="bi bi-check-circle me-2"></i>Confirmer la commande
                        </button>
                    </form>
                    <a href="panier.php" class="btn btn-outline-secondary w-100 rounded-pill py-2 mt-2">
                        <i class="bi bi-arrow-left me-2"></i>Retour au panier
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
