<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/class/gsb.php");

//En cas de déconnexion, on supprime la session et redirige à la racine
if (isset($_GET['logout'])) {
	session_destroy();
	header("Location: /");
}

if(!isset($_SESSION['logged'])) { 					// Si l'utilisateur n'est pas connecté ...
	include("login.php");
} else {											//Sinon
	if($_SESSION['user']['type'] == 'vis') {		//On vérifie que l'utilisateur est comptable ou visiteur
		$gsb->setTitle("Tableau de bord", true);
		include("header.php");
		include("gsb_visitor/main.php");
	} else {
		$gsb->setTitle("Tableau de bord", true);
		include("header.php");
		include("gsb_accountant/main.php");
	}	
	include("footer.php");
}
?>