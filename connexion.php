<?php
session_start();
if(isset($_SESSION['info']))  
{
    $info = $_SESSION['info'];
    unset($_SESSION['info']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eclat - Connexion</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    :root {
        --eclat-green: #0d2323;    /* Vert profond */
        --eclat-beige: #f3e9dc;    /* Beige sable */
        --color-primaire: #c5a059; /* Gold */
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f4f0; /* Fond très clair pour faire ressortir la carte */
        color: var(--eclat-green);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    h2 {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        background-color: var(--eclat-green) !important;
        color: var(--eclat-beige) !important;
        padding: 30px;
        border: none;
    }

    .btn-eclat {
        background-color: var(--eclat-green);
        color: var(--eclat-beige);
        border-radius: 50px;
        padding: 10px 30px;
        border: none;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-eclat:hover {
        background-color: var(--color-primaire);
        color: white;
    }

    .form-control {
        border: 1px solid #dee2e6;
        padding: 12px;
        border-radius: 8px;
    }

    .form-control:focus {
        border-color: var(--color-primaire);
        box-shadow: 0 0 0 0.2rem rgba(197, 160, 89, 0.25);
    }

    .text-gold {
        color: var(--color-primaire);
    }

    /* Lien retour accueil */
    .back-home {
        text-decoration: none;
        color: var(--eclat-green);
        font-size: 0.9rem;
        transition: 0.3s;
    }
    .back-home:hover {
        color: var(--color-primaire);
    }
  </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <div class="text-center mb-4">
                <a href="index.php" class="back-home"><i class="bi bi-arrow-left"></i> Retour à la boutique</a>
            </div>

            <div class="card shadow-lg">
                <div class="card-header text-center">
                    <h2 class="mb-0">Eclat</h2>
                    <p class="small mb-0 opacity-75">Espace Client</p>
                </div>
                <div class="card-body p-4">
                    <form action="traitement_connexion.php" method="post">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nom</label>
                            <input type="text" class="form-control shadow-none" placeholder="Votre nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Prénom</label>
                            <input type="text" class="form-control shadow-none" placeholder="Votre prénom" name="prenom" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Mot de passe</label>
                            <input type="password" class="form-control shadow-none" placeholder="Votre mot de passe" name="password" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-eclat">Se connecter</button>
                        </div>
                        <div class="text-center mb-4">
                            <a href="creation_compte.php" class="back-home"> créer un nouveau compte</a>
                        </div>
                    </form>
                    
                    <?php if (!empty($info)): ?>
                        <div class="alert alert-danger mt-3 text-center small py-2"><?= $info; ?></div>
                    <?php endif; ?>
                </div>
            </div>

            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>