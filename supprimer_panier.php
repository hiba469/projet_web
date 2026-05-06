<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user'])) {
    header("location:connexion.php");
    exit;
}

$id_client = $_SESSION['user']['id'];
$id_panier = isset($_GET['id_panier']) ? intval($_GET['id_panier']) : 0;

if ($id_panier > 0) {
    $req = "DELETE FROM panier WHERE id_panier = $id_panier AND id_client = $id_client";
    mysqli_query($conn, $req);
}

header("location:panier.php");
exit;
?>
