<?php 
session_start();

/*if(!isset($_GET['search'])) {
	header("Location: /");
	die();
}*/
include($_SERVER['DOCUMENT_ROOT'] ."/class/gsb.php");

// Récupération de la demande en base de données
$callback = $gsb->searchSheetsByDate($_SESSION['user']['id'], $_GET['search']);

$libellesEtat = $gsb->getStates(); // ... les libéllés des types

// Parcours la réponse de la DB (tableau à 2Dim)
for ($i=0; $i < count($callback); $i++) { 
	// Parcours les fiches de frais
	for ($j=0; $j < count($callback[$i]); $j++) { 
		// L'état abrégé est remplacé par le nom complet
		if($j == "id_etat") {
			$etat = $libellesEtat[$callback[$i]['id_etat']];
			$callback[$i]["id_etat"] = $etat;
		}
		// Change la date(format anglais) pour obtenir (ex: Avril 2015)
		if($j == "date") {
			$month = $gsb->getMonth($callback[$i]['date']);
			$year = $gsb->getYear($callback[$i]['date']);
			$callback[$i]["date"] = $month . " " . $year;
		}
	}
}

echo json_encode($callback);
?>