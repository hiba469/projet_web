<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user'])) {
    header("location:connexion.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['id_panier'], $_POST['action'])) {
    header("location:panier.php");
    exit;
}

$id_client = $_SESSION['user']['id'];
$id_panier = intval($_POST['id_panier']);
$action    = $_POST['action'];

if ($action === 'plus') {
    $req = "UPDATE panier SET quantite = quantite + 1 WHERE id_panier = $id_panier AND id_client = $id_client";
    mysqli_query($conn, $req);
} elseif ($action === 'moins') {
    // Decrease quantity; delete row if it reaches 0
    $req = "UPDATE panier SET quantite = quantite - 1 WHERE id_panier = $id_panier AND id_client = $id_client AND quantite > 1";
    mysqli_query($conn, $req);
    $req_del = "DELETE FROM panier WHERE id_panier = $id_panier AND id_client = $id_client AND quantite < 1";
    mysqli_query($conn, $req_del);
}

header("location:panier.php");
exit;
?>
