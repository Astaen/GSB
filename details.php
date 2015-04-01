<?php
session_start();
include("/class/gsb.php");

$gsb->insert("header.php");
if($_SESSION['user']['type'] == 'vis') {
	$gsb->setTitle("Tableau de bord", true);
	$gsb->insert("main_vis.php");
} else {
	$gsb->setTitle("Tableau de bord", true);
	$gsb->insert("main_acc.php");
}	
$gsb->insert("footer.php");

?>