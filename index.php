<?php
session_start();
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
		$gsb->insert("main_vis.php");
	} else {
		$gsb->insert("main_acc.php");
	}	
	$gsb->insert("footer.php");
}
?>