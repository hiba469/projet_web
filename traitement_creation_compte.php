<?php
session_start();
require_once "config.php";
if($_SERVER["REQUEST_METHOD"] === "POST")
{
	$nom = htmlspecialchars(trim($_POST['nom']));
	$prenom = htmlspecialchars(trim($_POST['prenom']));
	$password = htmlspecialchars(trim($_POST['password']));
	$email = htmlspecialchars(trim($_POST['mail']));
	$telephone = htmlspecialchars(trim($_POST['telephone']));
	$adresse = htmlspecialchars(trim($_POST['adresse']));
	$req = "select * from client where nom ='$nom' and prenom = '$prenom' and password = '$password'
	and email = '$email' and telephone = '$telephone' and adresse = '$adresse'";
	$res= mysqli_query($conn , $req);
	if(!$res)
	{
		echo "erreur";
	}
	if(mysqli_num_rows($res)!=0)
	{
		$_SESSION['info'] = '<strong>Compte existant<br>
		</strong>';
		header("location:creation_compte.php");
		exit;
	}
    $req = "insert into client (nom, prenom, password, email, telephone, adresse) values ('$nom', '$prenom', '$password', '$email', '$telephone', '$adresse')";
    $res= mysqli_query($conn , $req);   
    if(!$res)                                       
    {
        echo "erreur";
    }
	$req = "select * from client where nom ='$nom' and prenom = '$prenom' and password = '$password' and email = '$email' and telephone = '$telephone' and adresse = '$adresse'";
	$res= mysqli_query($conn , $req);
	if(!$res)
	{
		echo "erreur";
	}
	$data = mysqli_fetch_assoc($res); 
	$_SESSION['user'] = $data;
	header("location:produit.php");
	
}



?>