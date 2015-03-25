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
	$gsb->insert("main.php");
	$gsb->insert("footer.php");
}
?>