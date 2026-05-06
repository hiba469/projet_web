<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eclat - Bijouterie Fine & Traditionnelle</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --color-primaire: #c5a059; /* Gold */
            --eclat-green: #0d2323;    /* Vert profond */
            --eclat-beige: #f3e9dc;    /* Beige sable */
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            color: var(--eclat-green);
        }

        h1, h2, h3, .navbar-brand { 
            font-family: 'Playfair Display', serif; 
        }

        /* Menu avec effet after */
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

        .navbar-nav .nav-link.acc::after, .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        /* Sections */
        .hero-section {
            background: #f8f4f0;
            padding: 100px 0;
        }

        .bg-dark-custom { 
            background-color: var(--eclat-green); 
        }

        .text-gold { 
            color: var(--color-primaire); 
        }

        /* Produits */
        .card { 
            transition: transform 0.3s; 
            border: none; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .card:hover { 
            transform: translateY(-10px); 
        }

        /* Footer */
        .footer-custom {
            background-color: var(--eclat-green);
            color: var(--eclat-beige);
            padding: 80px 0 40px;
        }

        .footer-custom i {
            color: var(--color-primaire);
        }

        .social-link {
            color: var(--eclat-beige);
            font-size: 1.5rem;
            transition: color 0.3s;
        }

        .social-link:hover {
            color: var(--color-primaire);
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fs-3" href="#">Eclat</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link acc" href="#accueil">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#produits">Collections</a></li>
                    <li class="nav-item"><a class="nav-link" href="notrehistoire.php">Notre Histoire</a></li>
                    <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="accueil" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-3 mb-4">L'élégance à l'état pur</h1>
                    <p class="lead mb-5 text-muted">Découvrez notre collection raffinée de bagues, colliers et bracelets. De l'or pur à l'argent délicat, incluant l'authenticité des bijoux Tunisiens.</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&q=80&w=800" class="img-fluid rounded-circle shadow-lg" alt="Bijoux Eclat">
                </div>
            </div>
        </div>
    </section>

    <!-- Produits -->
    <section id="produits" class="py-5 bg-dark-custom text-white">
        <div class="container">
            <h2 class="display-5 mb-5 text-center">Nos Nouveautés</h2>
            <div class="row g-4">
                <!-- Produit 1 -->
                <div class="col-md-3">
                    <div class="card h-100 bg-white text-dark">
                        <img src="https://plus.unsplash.com/premium_photo-1681276169450-4504a2442173?q=80&w=400&auto=format&fit=crop" class="card-img-top p-3" alt="Bague">
                        <div class="card-body text-center">
                            <h5 class="card-title">Deux colliers fins</h5>
                            <p class="text-gold fw-bold">500 DT</p>
                            <form method="POST" action="ajouter_panier.php" class="d-inline">
                                <input type="hidden" name="id_produit" value="14">
                                <button type="submit" class="btn btn-dark btn-sm rounded-pill"><i class="bi bi-cart-plus"></i> Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 bg-white text-dark">
                        <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&q=80&w=400" class="card-img-top p-3" alt="Collier">
                        <div class="card-body text-center">
                            <h5 class="card-title">Collier Argent</h5>
                            <p class="text-gold fw-bold">98 DT</p>
                            <form method="POST" action="ajouter_panier.php" class="d-inline">
                                <input type="hidden" name="id_produit" value="15">
                                <button type="submit" class="btn btn-dark btn-sm rounded-pill"><i class="bi bi-cart-plus"></i> Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 bg-white text-dark">
                        <img src="https://plus.unsplash.com/premium_photo-1681276168324-a6f1958aa191?q=80&w=400&auto=format&fit=crop" class="card-img-top p-3" alt="Bracelet">
                        <div class="card-body text-center">
                            <h5 class="card-title">Bracelet Rihana</h5>
                            <p class="text-gold fw-bold">40 DT</p>
                            <form method="POST" action="ajouter_panier.php" class="d-inline">
                                <input type="hidden" name="id_produit" value="16">
                                <button type="submit" class="btn btn-dark btn-sm rounded-pill"><i class="bi bi-cart-plus"></i> Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 bg-white text-dark">
                        <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&q=80&w=400" class="card-img-top p-3" alt="Boucles">
                        <div class="card-body text-center">
                            <h5 class="card-title">Boucles Perles Bleu</h5>
                            <p class="text-gold fw-bold">120 DT</p>
                            <form method="POST" action="ajouter_panier.php" class="d-inline">
                                <input type="hidden" name="id_produit" value="19">
                                <button type="submit" class="btn btn-dark btn-sm rounded-pill"><i class="bi bi-cart-plus"></i> Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bouton Voir tous les produits -->
            <div class="text-center mt-5">
                <a href="produit.php" class="btn btn-lg rounded-pill px-5 py-3" style="background-color:#c5a059;color:#fff;font-weight:600;letter-spacing:1px;">
                    <i class="bi bi-grid me-2"></i>Voir tous les produits
                </a>
            </div>
        </div>
    </section>

    <footer id="contact" class="footer-custom">
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
                    <h5 class="text-gold mb-4">Nous Contacter</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> 123 Rue de la Bijouterie, Tunis</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> +216 22 000 000</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> contact@eclat-bijoux.tn</li>
                    </ul>
                </div>
 
                <div class="col-md-4 mb-4">
                    <h5 class="text-gold mb-4">Paiement & RIB</h5>
                    <div class="p-3 border border-secondary rounded bg-white bg-opacity-10">
                        <p class="small mb-1 text-uppercase">RIB :</p>
                        <p class="fw-bold mb-0">1234 5678 9012 3456</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>