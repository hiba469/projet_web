<?php

// Connexion a MySQL
$conn = mysqli_connect("localhost", "root","", "bijouterie");
// Verification de la connexion
if (!$conn) {
    die('Erreur de connexion a la base de donnees : ' . mysqli_connect_error());
}

?>