<?php 
session_start();
require_once "config.php";

// Vérifier que l'utilisateur est connecté
if(!isset($_SESSION['user'])) {
    header("location:connexion.php");
    exit;
}

$id_user = $_SESSION['user']['id'];

// Récupérer les informations de l'utilisateur
$req = "SELECT * FROM client WHERE id = $id_user";
$res = mysqli_query($conn, $req);
$user = mysqli_fetch_assoc($res);

// Récupérer l'historique des commandes (Corriger les noms de colonnes)
$req_commandes = "SELECT * FROM commande WHERE id_client = $id_user ORDER BY date DESC";
$res_commandes = mysqli_query($conn, $req_commandes);
$commandes = mysqli_fetch_all($res_commandes, MYSQLI_ASSOC);

// Traiter les mises à jour
$message = "";
$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])) {
        if($_POST['action'] == 'update_profile') {
            $nom = mysqli_real_escape_string($conn, $_POST['nom']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
            $adresse = mysqli_real_escape_string($conn, $_POST['adresse']);
            $ville = mysqli_real_escape_string($conn, $_POST['ville']);
            $codepostal = mysqli_real_escape_string($conn, $_POST['codepostal']);

            $req_update = "UPDATE client SET nom='$nom', email='$email', telephone='$telephone', adresse='$adresse' WHERE id=$id_user";
            
            if(mysqli_query($conn, $req_update)) {
                $_SESSION['user']['nom'] = $nom;
                $message = "✓ Profil mis à jour avec succès";
                // Rafraîchir les données
                $user = array_merge($user, [
                    'nom' => $nom,
                    'email' => $email,
                    'telephone' => $telephone,
                    'adresse' => $adresse
                ]);
            } else {
                $error = "✗ Erreur lors de la mise à jour";
            }
        }
        
        if($_POST['action'] == 'change_password') {
            $ancien_mdp = $_POST['ancien_mdp'];
            $nouveau_mdp = $_POST['nouveau_mdp'];
            $confirmer_mdp = $_POST['confirmer_mdp'];

            if($nouveau_mdp !== $confirmer_mdp) {
                $error = "✗ Les mots de passe ne correspondent pas";
            } elseif(strlen($nouveau_mdp) < 6) {
                $error = "✗ Le mot de passe doit contenir au moins 6 caractères";
            } elseif($ancien_mdp != $user['password']) {
                $error = "✗ L'ancien mot de passe est incorrect";
            } else {
                $req_mdp = "UPDATE client SET password='$nouveau_mdp' WHERE id=$id_user";
                
                if(mysqli_query($conn, $req_mdp)) {
                    $message = "✓ Mot de passe changé avec succès";
                } else {
                    $error = "✗ Erreur lors de la modification";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Eclat</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --color-primaire: #c5a059;
            --eclat-green: #0d2323;
            --eclat-beige: #f3e9dc;
            --color-light: #f8f4f0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--color-light);
            color: var(--eclat-green);
        }

        h1, h2, h3, .navbar-brand {
            font-family: 'Playfair Display', serif;
        }

        /* NAVBAR */
        .navbar-nav .nav-link {
            position: relative;
            padding-bottom: 5px;
            color: var(--eclat-green) !important;
            font-weight: 500;
        }

        .navbar-nav .nav-link::after {
            content: "";
            display: block;
            width: 0;
            height: 3px;
            margin: 0.3rem auto 0;
            background-color: var(--color-primaire);
            border-radius: 4px;
            transition: width 0.4s ease;
        }

        .navbar-nav .nav-link.active::after,
        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        /* SIDEBAR */
        .sidebar {
            background: white;
            border-radius: 16px;
            padding: 30px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .sidebar-item {
            padding: 15px 25px;
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: 0.3s;
            color: var(--eclat-green);
            font-weight: 500;
        }

        .sidebar-item:hover {
            background-color: var(--color-light);
            border-left-color: var(--color-primaire);
            color: var(--color-primaire);
        }

        .sidebar-item.active {
            background-color: rgba(197,160,89,0.1);
            border-left-color: var(--color-primaire);
            color: var(--color-primaire);
        }

        /* CONTENT */
        .content-section {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: none;
        }

        .content-section.active {
            display: block;
        }

        /* PROFIL HEADER */
        .profil-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid var(--color-light);
        }

        .profil-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-primaire), var(--eclat-green));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .profil-info h2 {
            margin-bottom: 5px;
            font-size: 1.8rem;
        }

        .profil-info p {
            color: #666;
            margin: 0;
        }

        /* FORM */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: var(--eclat-green);
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--color-primaire);
            box-shadow: 0 0 0 3px rgba(197,160,89,0.1);
            outline: none;
        }

        .row-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        /* BUTTONS */
        .btn-eclat {
            background-color: var(--eclat-green);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 35px;
            font-weight: 600;
            transition: 0.3s;
            cursor: pointer;
        }

        .btn-eclat:hover {
            background-color: var(--color-primaire);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #f0f0f0;
            color: var(--eclat-green);
            border: none;
            border-radius: 50px;
            padding: 12px 35px;
            font-weight: 600;
            transition: 0.3s;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
        }

        /* ALERTS */
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* COMMANDES */
        .commande-card {
            background: var(--color-light);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid var(--color-primaire);
        }

        .commande-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .commande-number {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .commande-status {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .commande-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            font-size: 0.9rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 3px;
        }

        .detail-value {
            font-weight: 600;
            color: var(--eclat-green);
        }

        /* FOOTER */
        .footer-custom {
            background-color: var(--eclat-green);
            color: var(--eclat-beige);
            padding: 80px 0 40px;
            margin-top: 60px;
        }

        .footer-custom i {
            color: var(--color-primaire);
        }

        .social-link {
            color: var(--eclat-beige);
            font-size: 1.5rem;
            transition: color 0.3s;
            text-decoration: none;
        }

        .social-link:hover {
            color: var(--color-primaire);
        }

        .footer-custom a {
            color: var(--eclat-beige);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-custom a:hover {
            color: var(--color-primaire);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .profil-header {
                flex-direction: column;
                text-align: center;
            }
            
            .sidebar {
                position: static;
                margin-bottom: 30px;
            }

            .row-form {
                grid-template-columns: 1fr;
            }

            .commande-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .commande-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fs-3" href="index.php">Eclat</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="produit.php">Collections</a></li>
                <li class="nav-item"><a class="nav-link" href="histoire.php">Notre Histoire</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="panier.php">
                        <i class="bi bi-bag"></i> Panier
                        <span class="badge bg-warning text-dark" id="panier-count" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">0</span>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link active" href="profil.php"><i class="bi bi-person-circle"></i> Mon Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENU PRINCIPAL -->
<div class="container py-5">
    <div class="row g-4">
        <!-- SIDEBAR -->
        <div class="col-lg-3">
            <div class="sidebar">
                <div class="sidebar-item active" onclick="showTab('infos')">
                    <i class="bi bi-person me-2"></i>Informations
                </div>
                <div class="sidebar-item" onclick="showTab('securite')">
                    <i class="bi bi-shield-lock me-2"></i>Sécurité
                </div>
                <div class="sidebar-item" onclick="showTab('commandes')">
                    <i class="bi bi-box me-2"></i>Commandes
                </div>
                <div class="sidebar-item" onclick="showTab('adresses')">
                    <i class="bi bi-geo-alt me-2"></i>Adresses
                </div>
            </div>
        </div>

        <!-- CONTENU -->
        <div class="col-lg-9">
            <!-- TAB: INFORMATIONS -->
            <div id="infos" class="content-section active">
                <div class="profil-header">
                    <div class="profil-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="profil-info">
                        <h2><?= htmlspecialchars($user['nom']) ?></h2>
                        <p><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($user['email']) ?></p>
                        <p><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($user['telephone'] ?? 'Non renseigné') ?></p>
                    </div>
                </div>

                <?php if($message): ?>
                    <div class="alert-success">
                        <i class="bi bi-check-circle"></i>
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="alert-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <h4 class="mb-4">Informations personnelles</h4>

                    <div class="row-form">
                        <div class="form-group">
                            <label class="form-label">Nom complet</label>
                            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>">
                        </div>
                    </div>

                    <h4 class="mb-4 mt-5">Adresse</h4>

                    <div class="row-form">
                        <div class="form-group">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($user['adresse'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville" class="form-control" placeholder="Entrez votre ville">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Code postal</label>
                            <input type="text" name="codepostal" class="form-control" placeholder="Entrez votre code postal">
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-5">
                        <button type="submit" class="btn-eclat">
                            <i class="bi bi-check me-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB: SÉCURITÉ -->
            <div id="securite" class="content-section">
                <h2 class="mb-4">Sécurité</h2>

                <?php if($message): ?>
                    <div class="alert-success">
                        <i class="bi bi-check-circle"></i>
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="alert-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <div style="background: var(--color-light); padding: 30px; border-radius: 12px; margin-bottom: 30px;">
                    <h4 class="mb-3">Changer votre mot de passe</h4>
                    <p class="text-muted">Nous vous recommandons d'utiliser un mot de passe unique et complexe.</p>
                </div>

                <form method="POST">
                    <input type="hidden" name="action" value="change_password">

                    <div class="form-group">
                        <label class="form-label">Ancien mot de passe</label>
                        <input type="password" name="ancien_mdp" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="nouveau_mdp" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirmer le mot de passe</label>
                        <input type="password" name="confirmer_mdp" class="form-control" required>
                    </div>

                    <button type="submit" class="btn-eclat">
                        <i class="bi bi-lock me-2"></i>Changer le mot de passe
                    </button>
                </form>
            </div>

            <!-- TAB: COMMANDES -->
            <div id="commandes" class="content-section">
                <h2 class="mb-4">Mes Commandes</h2>

                <?php if(empty($commandes)): ?>
                    <div style="text-align: center; padding: 60px 20px;">
                        <i class="bi bi-box" style="font-size: 4rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">Vous n'avez pas encore passé de commande.</p>
                        <a href="produit.php" class="btn-eclat" style="margin-top: 20px;">
                            <i class="bi bi-shopping-bag me-2"></i>Découvrir nos collections
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach($commandes as $commande): ?>
                        <div class="commande-card">
                            <div class="commande-header">
                                <div class="commande-number">
                                    Commande #<?= $commande['id_commande'] ?>
                                </div>
                                <span class="commande-status status-completed">
                                    Livrée
                                </span>
                            </div>
                            <div class="commande-details">
                                <div class="detail-item">
                                    <span class="detail-label">Date</span>
                                    <span class="detail-value"><?= date('d/m/Y', strtotime($commande['date'])) ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Montant total</span>
                                    <span class="detail-value"><?= number_format($commande['montant'], 0) ?> DT</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- TAB: ADRESSES -->
            <div id="adresses" class="content-section">
                <h2 class="mb-4">Mes Adresses</h2>

                <div style="background: var(--color-light); padding: 30px; border-radius: 12px;">
                    <h4 class="mb-3"><i class="bi bi-house me-2"></i>Adresse principale</h4>
                    <p>
                        <?= htmlspecialchars($user['adresse'] ?? 'Non renseignée') ?><br>
                        Tunis, Tunisie
                    </p>
                    <button class="btn-secondary mt-3" onclick="showTab('infos')">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer-custom">
    <div class="container text-center text-md-start">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h3 class="mb-4">Eclat</h3>
                <p>L'art de briller avec authenticité</p>
                <div class="d-flex justify-content-center justify-content-md-start gap-4 mt-3">
                    <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <h5 style="color: var(--color-primaire);" class="mb-4">Nous Contacter</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> 123 Rue de la Bijouterie, Tunis</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i> +216 22 000 000</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i> contact@eclat-bijoux.tn</li>
                </ul>
            </div>

            <div class="col-md-4 mb-4">
                <h5 style="color: var(--color-primaire);" class="mb-4">Navigation</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php">Accueil</a></li>
                    <li class="mb-2"><a href="produit.php">Collections</a></li>
                    <li class="mb-2"><a href="histoire.php">Notre Histoire</a></li>
                    <li class="mb-2"><a href="profil.php">Mon Profil</a></li>
                </ul>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,0.1);">
        <p class="text-center small opacity-50 mt-3 mb-0">© 2024 Eclat Bijouterie. Tous droits réservés.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Fonction pour afficher les onglets
function showTab(tabName) {
    // Masquer tous les onglets
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => section.classList.remove('active'));
    
    // Retirer la classe active des items de sidebar
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    sidebarItems.forEach(item => item.classList.remove('active'));
    
    // Afficher l'onglet sélectionné
    document.getElementById(tabName).classList.add('active');
    
    // Ajouter la classe active au sidebar item
    event.target.closest('.sidebar-item').classList.add('active');
}

// Charger le nombre de produits dans le panier
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

document.addEventListener('DOMContentLoaded', function() {
    updatePanierCount();
});
</script>

</body>
</html>