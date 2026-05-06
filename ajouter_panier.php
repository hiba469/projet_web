<?php
session_start();
require_once "config.php";

// Vérifier que l'utilisateur est connecté
if(!isset($_SESSION['user'])) {
    header("location:connexion.php");
    exit;
}

// Vérifier que c'est une requête POST
if($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['id_produit'])) {
    header("location:produit.php");
    exit;
}

$id_client = $_SESSION['user']['id'];
$id_produit = intval($_POST['id_produit']);

// Vérifier si le produit existe
$req_check = "SELECT * FROM produit WHERE id = $id_produit";
$res_check = mysqli_query($conn, $req_check);

if(mysqli_num_rows($res_check) == 0) {
    header("location:produit.php?erreur=produit_introuvable");
    exit;
}

// Vérifier si le produit est déjà dans le panier
$req_existe = "SELECT * FROM panier WHERE id_client = $id_client AND id_produit = $id_produit";
$res_existe = mysqli_query($conn, $req_existe);

if(mysqli_num_rows($res_existe) > 0) {
    // Augmenter la quantité
    $req_update = "UPDATE panier SET quantite = quantite + 1 WHERE id_client = $id_client AND id_produit = $id_produit";
    mysqli_query($conn, $req_update);
} else {
    // Ajouter le produit
    $req_insert = "INSERT INTO panier (id_client, id_produit, quantite) VALUES ($id_client, $id_produit, 1)";
    mysqli_query($conn, $req_insert);
}

// Rediriger vers le panier
header("location:panier.php");
exit;
?>