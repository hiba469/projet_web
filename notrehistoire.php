<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eclat - Notre Histoire</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    :root {
      --eclat-green: #0d2323;
      --eclat-beige: #f3e9dc;
      --color-primaire: #c5a059;
      --color-light: #f8f4f0;
    }

    body { font-family: 'Poppins', sans-serif; color: var(--eclat-green); }
    h1, h2, h3, .navbar-brand { font-family: 'Playfair Display', serif; }

    /* ── NAVBAR ── */
    .navbar-nav .nav-link {
      position: relative; padding-bottom: 5px;
      color: var(--eclat-green) !important; font-weight: 500;
    }
    .navbar-nav .nav-link::after {
      content: ""; display: block; width: 0; height: 3px;
      margin: 0.3rem auto 0; background-color: var(--color-primaire);
      border-radius: 4px; transition: width 0.4s ease;
    }
    .navbar-nav .nav-link.active::after,
    .navbar-nav .nav-link:hover::after { width: 100%; }

    /* ── GALERIE IMAGES EN HAUT ── */
    .gallery-header {
      background: linear-gradient(135deg, var(--eclat-green) 0%, #1a3d3d 100%);
      padding: 60px 0;
      overflow: hidden;
    }

    .gallery-container {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      margin-bottom: 0;
    }

    .gallery-item {
      height: 280px;
      border-radius: 8px;
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.3s;
    }

    .gallery-item:hover {
      transform: scale(1.05);
    }

    .gallery-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .gallery-item.instagram-promo {
      background: var(--color-primaire);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-align: center;
      flex-direction: column;
      gap: 15px;
    }

    .gallery-item.instagram-promo i {
      font-size: 2.5rem;
    }

    .gallery-item.instagram-promo p {
      margin: 0;
      font-size: 0.9rem;
      font-weight: 500;
    }

    /* ── HERO ── */
    .hero-histoire {
      background: #f8f4f0;
      padding: 80px 0 60px;
    }

    .hero-histoire h1 {
      font-size: 3rem;
      margin-bottom: 30px;
    }

    /* ── TIMELINE ── */
    .timeline { position: relative; padding: 40px 0; }
    .timeline::before {
      content: "";
      position: absolute; left: 50%; top: 0; bottom: 0;
      width: 2px; background: var(--color-primaire);
      transform: translateX(-50%);
    }
    .timeline-item { position: relative; margin-bottom: 60px; }
    .timeline-item:last-child { margin-bottom: 0; }

    .timeline-dot {
      position: absolute; left: 50%; top: 10px;
      width: 18px; height: 18px;
      background: var(--color-primaire);
      border: 3px solid white;
      border-radius: 50%;
      transform: translateX(-50%);
      z-index: 2;
      box-shadow: 0 0 0 4px rgba(197,160,89,0.2);
    }

    .timeline-content {
      background: white; border-radius: 16px;
      padding: 28px 32px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.07);
      width: 44%;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .timeline-content:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    .timeline-item:nth-child(odd) .timeline-content { margin-left: 56%; }
    .timeline-item:nth-child(even) .timeline-content { margin-left: 0; }

    .timeline-year {
      font-family: 'Playfair Display', serif;
      font-size: 2rem; font-weight: 700;
      color: var(--color-primaire); line-height: 1;
      margin-bottom: 8px;
    }
    .timeline-title { font-weight: 600; font-size: 1.1rem; margin-bottom: 8px; }
    .timeline-text { color: #666; font-size: 0.9rem; line-height: 1.7; }

    /* ── VALEURS ── */
    .valeurs-section { background: var(--eclat-green); color: white; padding: 100px 0; }
    .valeur-card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(197,160,89,0.25);
      border-radius: 16px; padding: 40px 30px;
      text-align: center;
      transition: background 0.3s, transform 0.3s;
    }
    .valeur-card:hover {
      background: rgba(197,160,89,0.1);
      transform: translateY(-6px);
    }
    .valeur-icon {
      width: 70px; height: 70px;
      background: var(--color-primaire);
      border-radius: 50%; display: flex;
      align-items: center; justify-content: center;
      margin: 0 auto 20px; font-size: 1.8rem; color: white;
    }
    .valeur-card h4 { font-family: 'Playfair Display', serif; margin-bottom: 12px; }
    .valeur-card p { color: rgba(255,255,255,0.7); font-size: 0.9rem; line-height: 1.7; }

    /* ── ARTISANS (Sans images) ── */
    .artisan-card {
      border: none; border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s;
      background: white;
      padding: 30px;
      text-align: center;
    }
    .artisan-card:hover { transform: translateY(-8px); }
    
    .artisan-role { 
      color: var(--color-primaire); 
      font-size: 0.8rem; 
      font-weight: 600; 
      letter-spacing: 2px; 
      text-transform: uppercase; 
    }

    .artisan-card h5 {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      margin: 12px 0;
    }

    .artisan-card p {
      color: #666;
      font-size: 0.9rem;
      line-height: 1.6;
    }

    /* ── CHIFFRES ── */
    .chiffres-section { background: var(--color-light); padding: 80px 0; }
    .chiffre-item { text-align: center; padding: 20px; }
    .chiffre-number {
      font-family: 'Playfair Display', serif;
      font-size: 3.5rem; font-weight: 700;
      color: var(--color-primaire); line-height: 1;
    }
    .chiffre-label { color: #666; font-size: 0.9rem; margin-top: 8px; }
    .divider-gold { width: 60px; height: 3px; background: var(--color-primaire); margin: 16px auto 0; border-radius: 4px; }

    /* ── CTA ── */
    .cta-section { background: var(--eclat-beige); padding: 100px 0; }
    .btn-gold {
      background: var(--color-primaire); color: white;
      border: none; border-radius: 50px;
      padding: 14px 40px; font-weight: 600; font-size: 1rem;
      transition: 0.3s; text-decoration: none; display: inline-block;
    }
    .btn-gold:hover { background: var(--eclat-green); color: white; transform: translateY(-2px); }

    /* ── FOOTER ── */
    .footer-custom { 
      background-color: var(--eclat-green); 
      color: var(--eclat-beige); 
      padding: 80px 0 40px; 
    }

    .footer-custom h3, .footer-custom h5 {
      font-family: 'Playfair Display', serif;
    }

    .footer-custom i { color: var(--color-primaire); }

    .social-link { 
      color: var(--eclat-beige); 
      font-size: 1.5rem; 
      transition: color 0.3s; 
    }

    .social-link:hover { color: var(--color-primaire); }

    .footer-custom a {
      color: var(--eclat-beige);
      text-decoration: none;
      transition: color 0.3s;
    }

    .footer-custom a:hover {
      color: var(--color-primaire);
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      .timeline::before { left: 20px; }
      .timeline-dot { left: 20px; }
      .timeline-content { width: 80%; margin-left: 50px !important; }
      .gallery-container { grid-template-columns: repeat(2, 1fr); }
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
        <li class="nav-item"><a class="nav-link active" href="histoire.php">Notre Histoire</a></li>
        <li class="nav-item">
          <a class="nav-link" href="panier.php">
            <i class="bi bi-bag"></i> Panier
            <span class="badge bg-warning text-dark" id="panier-count" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">0</span>
          </a>
        </li>
        <?php if(isset($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="connexion.php">Connexion</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>


<!-- HERO -->
<section class="hero-histoire">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-7">
        <h1 class="display-4">Notre Histoire</h1>
        <p class="lead text-muted">De l'atelier familial au cœur de Tunis aux vitrines les plus raffinées, Eclat perpétue l'art de la bijouterie fine depuis près d'un demi-siècle.</p>
      </div>
    </div>
  </div>
</section>

<!-- CHIFFRES CLÉS -->
<section class="chiffres-section">
  <div class="container">
    <div class="row g-4 justify-content-center">
      <div class="col-6 col-md-3">
        <div class="chiffre-item">
          <div class="chiffre-number">48+</div>
          <div class="divider-gold"></div>
          <div class="chiffre-label">Années d'expertise</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="chiffre-item">
          <div class="chiffre-number">3</div>
          <div class="divider-gold"></div>
          <div class="chiffre-label">Générations de joailliers</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="chiffre-item">
          <div class="chiffre-number">200+</div>
          <div class="divider-gold"></div>
          <div class="chiffre-label">Créations uniques</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="chiffre-item">
          <div class="chiffre-number">2k+</div>
          <div class="divider-gold"></div>
          <div class="chiffre-label">Clients satisfaits</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TIMELINE -->
<section class="py-5" style="background:#fff;">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-5">Une saga familiale</h2>
      <p class="text-muted">Chaque étape a façonné notre savoir-faire unique.</p>
      <div class="divider-gold mx-auto mt-3"></div>
    </div>

    <div class="timeline">

      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <div class="timeline-year">1978</div>
          <div class="timeline-title">La naissance d'un rêve</div>
          <p class="timeline-text">Hassen Ben Salah, artisan joaillier de formation, ouvre son premier atelier dans la Médina de Tunis. Avec une simple table et des outils ancestraux, il crée ses premières pièces en argent inspirées du patrimoine tunisien.</p>
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <div class="timeline-year">1992</div>
          <div class="timeline-title">L'essor de l'atelier</div>
          <p class="timeline-text">La réputation de la maison grandit. L'atelier s'agrandit et accueille les premières collections en or 18 carats. Les créations Eclat sont désormais reconnues pour leur finesse et leur authenticité.</p>
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <div class="timeline-year">2005</div>
          <div class="timeline-title">La deuxième génération</div>
          <p class="timeline-text">Sonia Ben Salah, fille du fondateur, rejoint la maison après ses études à Paris. Elle apporte une touche contemporaine tout en préservant l'âme artisanale, lançant les premières collections alliant tradition et modernité.</p>
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <div class="timeline-year">2015</div>
          <div class="timeline-title">Ouverture de la boutique</div>
          <p class="timeline-text">Eclat inaugure sa première boutique à Tunis, alliant l'esthétique d'une galerie d'art à l'intimité d'un espace joaillerie. Un cadre pensé pour sublimer chaque pièce et offrir une expérience unique.</p>
        </div>
      </div>

      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <div class="timeline-content">
          <div class="timeline-year">2026</div>
          <div class="timeline-title">Eclat, la boutique en ligne</div>
          <p class="timeline-text">Fidèle à son ambition de rendre la bijouterie fine accessible, Eclat lance sa plateforme digitale. L'art de la joaillerie tunisienne s'invite désormais dans tous les foyers.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- VALEURS -->
<section class="valeurs-section">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-5 text-white">Nos Valeurs</h2>
      <p style="color:rgba(255,255,255,0.6);">Les piliers qui guident chacune de nos créations.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="valeur-card">
          <div class="valeur-icon"><i class="bi bi-gem"></i></div>
          <h4>Authenticité</h4>
          <p>Chaque bijou raconte une histoire ancrée dans le patrimoine tunisien. Nous utilisons des techniques ancestrales transmises de génération en génération.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="valeur-card">
          <div class="valeur-icon"><i class="bi bi-award"></i></div>
          <h4>Excellence</h4>
          <p>Aucun compromis sur la qualité. De la sélection des matières premières à la finition, chaque détail est soigné avec une rigueur absolue.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="valeur-card">
          <div class="valeur-icon"><i class="bi bi-heart"></i></div>
          <h4>Passion</h4>
          <p>La bijouterie n'est pas un métier, c'est une vocation. Cette passion se ressent dans chaque pièce que nous créons et dans chaque relation que nous tissons.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ARTISANS (Sans images) -->
<section class="py-5" style="background: var(--color-light);">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-5">Nos Artisans</h2>
      <p class="text-muted">Des mains expertes au service de votre éclat.</p>
      <div class="divider-gold mx-auto mt-3"></div>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-md-4">
        <div class="artisan-card">
          <div class="artisan-role">Fondateur</div>
          <h5>Hassen Ben Salah</h5>
          <p>48 ans de métier, maître joaillier formé aux techniques traditionnelles du souk des bijoutiers de Tunis.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="artisan-card">
          <div class="artisan-role">Directrice Artistique</div>
          <h5>Sonia Ben Salah</h5>
          <p>Alliance unique entre formation classique à Paris et sensibilité tunisienne. Créatrice des collections contemporaines d'Eclat.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="artisan-card">
          <div class="artisan-role">Maître Gemmologue</div>
          <h5>Khalil Mansour</h5>
          <p>Expert en pierres précieuses certifié GIA, il garantit la sélection rigoureuse de chaque gemme qui entre dans nos ateliers.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section text-center">
  <div class="container">
    <h2 class="display-5 mb-4">Prêt à trouver votre bijou ?</h2>
    <p class="text-muted mb-5 fs-5">Explorez nos collections ou contactez-nous pour une création sur mesure.</p>
    <div class="d-flex flex-wrap gap-3 justify-content-center">
      <a href="produit.php" class="btn-gold">Voir les collections</a>
      <a href="connexion.php" class="btn-gold">Mon espace client</a>
    </div>
  </div>
</section>

<!-- FOOTER (Style index.php) -->
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

<script>
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