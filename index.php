<?php
session_start();
date_default_timezone_set("GMT");
include("/class/gsb.php");
if (isset($_GET['logout'])) {
	session_destroy();
	header("Location: /");
}

if(!isset($_SESSION['logged'])) {
	$gsb->insert("login.php");
} else {
	$gsb->insert("header.php");
	if($_SESSION['user']['type'] == 'vis') {
		$gsb->setTitle("Tableau de bord", true);
		$gsb->insert("main_vis.php");
	} else {
		$gsb->setTitle("Tableau de bord", true);
		$gsb->insert("main_acc.php");
	}	
	$gsb->insert("footer.php");
}
?>