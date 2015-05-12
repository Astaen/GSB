<?php
session_start();
include("../class/gsb.php");
$fiche = $gsb->getCurrentSheet($_SESSION['user']['id']);

if(isset($_POST['data'])) {
	$data = $_POST['data'];
	if($data['cat_frais'] == "f") {
		$gsb->updateSheet($fiche['id'], $data['type_frais'], $data['qty']);
		$details = $gsb->getSheetDetails($fiche['id']);
		$couts = $gsb->getFeeAmounts();
		$frais = Array(
			'frais' => $details['forfait'],
			'couts' => $couts
			);
		echo json_encode($frais);		
	} else {
		$LastYear = time() - (365 * 24 * 60 *60);
		if( (strtotime($data['date']) > $LastYear) && (strtotime($data['date']) < time()) ) {
			$gsb->addOverageFee($fiche['id'], $data['libelle'], $data['montant'], $data['date']);
			$total = $gsb->getOverageFeesTotal($fiche['id']);
			echo json_encode($total[0]);
		}
		else {
			echo json_encode("Erreur");
		}
	}
}
