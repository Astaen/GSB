<?php 
session_start();
/*
if(!isset($_GET['search'])) {
	header("Location: /fiches.php");
	die();
}
*/
include($_SERVER['DOCUMENT_ROOT'] ."/class/gsb.php");

// Récupère les mots clés
if(sizeof(explode(" ", $_GET['search'])) == 1) { // Si il y a un seul mot clé lorsque l'on décompose la chaine de caractère
	$user_search = $_GET['search'];
}
else {
	$user_search = explode(" ", $_GET['search']);
}

//var_dump(sizeof($user_search));

// SI ON A QU'UN SEUL ÉLÉMENT DANS LA RECHERCHE
if(sizeof($user_search) == 1) {
	// Permet de transformer un Mois en son Index (ex: Mars = 3), SI c'est un Mois
	foreach ($gsb->month as $key => $month) {
		if(strtolower($month) == strtolower($user_search)) {
			$user_search = $key+1;
		}
	}

	// Vérifie le type d'utilisateur connecté
	if($_SESSION['user']['type'] == 'vis') {
		// Recherche si une date(mois ou année) correspond à la recherche
		$callback = $gsb->searchSheetsByDate($gsb->FirstToUpper($user_search), $_SESSION['user']['id']);
		
		// Si le aucune fiche n'a été trouver au dessus alors on essaye de chercher un nom ou prenom
		if(!$callback) {
			// Cherche dans le nom et prenom
			$callback = $gsb->searchSheetsByKeyword($gsb->FirstToUpper($user_search), $_SESSION['user']['id']);
		}
	}
	else {
		$callback = $gsb->searchSheetsByDate($gsb->FirstToUpper($user_search));
		if(!$callback) {
			$callback = $gsb->searchSheetsByKeyword($gsb->FirstToUpper($user_search));
		}
	}

	// RETOUR
	if(!$callback) {
		echo json_encode(false);
	}
	else {
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

		// Si c'est un compta | On ajoute un champ contenant le nom du propriétaire de la fiche
		if($_SESSION['user']['type'] == 'com') {
			for ($i=0; $i < count($callback); $i++) { 
				$info_user = $gsb->getUserInfo($callback[$i]['id_utilisateur']);
				$name = $info_user['nom']." ".$info_user['prenom'];
				$callback[$i]['fiche_de'] = $name;
			}
		}
		echo json_encode($callback);
	}
}
// SI ON A PLUSIEURS ELEMENT DE RECHERCHE
else {
	// Permet de transformer un Mois en son Index (ex: Mars = 3), SI c'est un Mois
	for ($i=0; $i < count($gsb->month); $i++) {
		for ($j=0; $j < count($user_search); $j++) {
			if(strtolower($gsb->month[$i]) == strtolower($user_search[$j])) {
				$user_search[$j] = $i+1;
			}
		}
	}

	// Vérifie le type d'utilisateur connecté
	if($_SESSION['user']['type'] == 'vis') {
		// Recherche si une date(mois ou année) correspond à la recherche
		$callback = $gsb->searchSheetsByDate($gsb->FirstToUpper($user_search), $_SESSION['user']['id']);
		// Si le aucune fiche n'a été trouver au dessus alors on essaye de chercher un nom ou prenom
		if(!$callback) {
			// Cherche dans le nom et prenom
			$callback = $gsb->searchSheetsByKeyword($gsb->FirstToUpper($user_search), $_SESSION['user']['id']);
		}
	}
	else {
		$callback = $gsb->searchSheetsByDate($gsb->FirstToUpper($user_search));
		if(!$callback) {
			$callback = $gsb->searchSheetsByKeyword($gsb->FirstToUpper($user_search));
		}
	}

	// RETOUR
	if(!$callback) {
		echo json_encode(false);
	}
	else {
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

		// Si c'est un compta | On ajoute un champ contenant le nom du propriétaire de la fiche
		if($_SESSION['user']['type'] == 'com') {
			for ($i=0; $i < count($callback); $i++) { 
				$info_user = $gsb->getUserInfo($callback[$i]['id_utilisateur']);
				$name = $info_user['nom']." ".$info_user['prenom'];
				$callback[$i]['fiche_de'] = $name;
			}
		}
		echo json_encode($callback);
	}
}
?>