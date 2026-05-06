<?php
session_start();
require_once "config.php";
if($_SERVER["REQUEST_METHOD"] === "POST")
{
	$nom = htmlspecialchars(trim($_POST['nom']));
	$prenom = htmlspecialchars(trim($_POST['prenom']));
	$password = htmlspecialchars(trim($_POST['password']));
	$req = "select * from client where nom ='$nom' and prenom = '$prenom' and password = '$password'";
	
	$res= mysqli_query($conn , $req);
	if(!$res)
	{
		echo "erreur";
	}
	if(mysqli_num_rows($res)==0)
	{
		$_SESSION['info'] = '<strong>Compte introuvable<br>
		</strong>';
		
	}
	$data = mysqli_fetch_assoc($res); 
	$_SESSION['user'] = $data;
	header("location:produit.php");
	exit;
}



?>