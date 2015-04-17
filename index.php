<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/class/gsb.php");
if (isset($_GET['logout'])) {
	session_destroy();
	header("Location: /");
}

if(!isset($_SESSION['logged'])) {
	include("login.php");
} else {
	if($_SESSION['user']['type'] == 'vis') {
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