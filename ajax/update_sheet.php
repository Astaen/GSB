<?php

include("../class/gsb.php");
$fiche = $gsb->getCurrentSheet($_SESSION['user']['id']);
// $gsb->updateSheet($id, $)
// $gsb->addFee($id)

if(isset($_POST['data'])) {
	$data = $_POST['data'];
	if($data['cat_frais'] == "f") {
		var_dump($data);
	} else {
		var_dump($data);
	}
}
