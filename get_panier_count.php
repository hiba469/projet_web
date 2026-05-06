<?php
session_start();
require_once "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$id_client = $_SESSION['user']['id'];
$req = "SELECT SUM(quantite) AS total FROM panier WHERE id_client = $id_client";
$res = mysqli_query($conn, $req);
$row = mysqli_fetch_assoc($res);
$count = $row['total'] ? intval($row['total']) : 0;

echo json_encode(['count' => $count]);
exit;
?>
