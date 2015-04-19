<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] ."/includes/bdd.php");
//http://gsb.dev/ajax/change-states.php?etat=CL&id_fiche=1
if(!isset($_GET['etat']) && !isset($_GET['id_fiche'])) {
	header("Location: /fiches.php");
	die();
}

$etat = $_GET['etat'];
$id = $_GET['id_fiche'];

// test
$lastMonth = date("m")-1;
$thisMonth = (int)date("m");
// date du jour
$dateNowMonth = date("m");
$dateNowYear = date("Y");

$Le = $dateNowYear."-".($dateNowMonth-1)."-10";
var_dump($Le);
$Au = $Le = $dateNowYear."-".$dateNowMonth."-18";
var_dump($Au);
// Jusqu'au 10 du mois pour rembourser des fiches du mois précédent
//2015-03-10 AND 2015-04-10
$req = $bdd->query("UPDATE fiche SET id_etat='$etat' WHERE id=$id AND date BETWEEN '$Le' AND '$Au'");
//$req->execute(array($etat,$id,$Le,$Au));
var_dump($req->fetch());
die();


// Modification de l'état de la fiche de frais
$req = $bdd->prepare("UPDATE fiche SET id_etat=? WHERE id = ?");
$req->execute(array($etat, $id));
if($req->rowCount() > 0) {
	$callback = true;
} else {
	$callback = false;
}

echo json_encode($callback);

?>