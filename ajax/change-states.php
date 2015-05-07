<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] ."/class/gsb.php");
//http://gsb.dev/ajax/change-states.php?etat=CL&id_fiche=1
if(!isset($_GET['etat']) && !isset($_GET['id_fiche'])) {
	header("Location: /fiches.php");
	die();
}

$etat = $_GET['etat'];
$id = $_GET['id_fiche'];

echo json_encode($gsb->changeStates($etat, $id));

?>