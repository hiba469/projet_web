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
  <title>Eclat - Création de compte</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    :root {
        --eclat-green: #0d2323;
        --eclat-beige: #f3e9dc;
        --color-primaire: #c5a059;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f4f0;
        color: var(--eclat-green);
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 20px 0;
    }

    .card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        max-width: 800px; 
        margin: auto;
    }

    .card-header {
        background-color: var(--eclat-green) !important;
        color: var(--eclat-beige) !important;
        padding: 20px;
        border: none;
    }

    h2 {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        margin-bottom: 0;
    }

    .form-label {
        font-size: 0.85rem;
        margin-bottom: 5px;
        color: #555;
    }

    .form-control {
        border: 1px solid #dee2e6;
        padding: 10px;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .form-control:focus {
        border-color: var(--color-primaire);
        box-shadow: 0 0 0 0.2rem rgba(197, 160, 89, 0.15);
    }

    .btn-eclat {
        background-color: var(--eclat-green);
        color: var(--eclat-beige);
        border-radius: 50px;
        padding: 12px;
        font-weight: 600;
        transition: 0.3s;
        width: 100%;
        margin-top: 10px;
    }

    .btn-eclat:hover {
        background-color: var(--color-primaire);
        color: white;
    }

    .link-alt {
        text-decoration: none;
        color: var(--eclat-green);
        font-size: 0.85rem;
        transition: 0.3s;
    }

    .link-alt:hover {
        color: var(--color-primaire);
    }
  </style>
</head>
<body>

<div class="container">
    <div class="text-center mb-3">
        <a href="index.php" class="link-alt"><i class="bi bi-arrow-left"></i> Retour à la boutique</a>
    </div>

    <div class="card shadow-lg">
        <div class="card-header text-center">
            <h2>Eclat</h2>
            <p class="small mb-0 opacity-75">Créer votre espace joaillerie</p>
        </div>
        
        <div class="card-body p-4 p-md-5">
            <form action="traitement_creation_compte.php" method="post">
                <div class="row">
                    <!-- Colonne Gauche -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nom</label>
                        <input type="text" class="form-control shadow-none" placeholder="Nom" name="nom" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Prénom</label>
                        <input type="text" class="form-control shadow-none" placeholder="Prénom" name="prenom" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control shadow-none" placeholder="email@exemple.com" name="mail" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Téléphone</label>
                        <input type="text" class="form-control shadow-none" placeholder="ex: 22 111 222" name="telephone" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Mot de passe</label>
                        <input type="password" class="form-control shadow-none" placeholder="••••••••" name="password" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Adresse</label>
                        <input type="text" class="form-control shadow-none" placeholder="Adresse complète" name="adresse" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-eclat">Finaliser l'inscription</button>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <span class="small text-muted">Déjà client ?</span> 
                    <a href="connexion.php" class="link-alt fw-bold ms-1">Se connecter</a>
                </div>
            </form>
            
            <?php if (!empty($info)): ?>
                <div class="alert alert-danger mt-3 text-center small py-2"><?= $info; ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>