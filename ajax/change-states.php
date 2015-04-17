<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] ."/includes/bdd.php");

if(!isset($_POST['etat']) && !isset($_POST['id_fiche'])) {
	header("Location: /fiches.php");
	die();
}

$etat = (string)$_POST['etat'];
$id = (int)$_POST['id_fiche'];

$req = $bdd->prepare("UPDATE fiche SET id_etat=? WHERE id = ?");
$req->execute(array($etat, $id));
if($req->rowCount() > 0) {
	$callback = true;
} else {
	$callback = false;
}

echo json_encode($callback);

?>