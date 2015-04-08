
<?php
// On récupère ...
$fiches = $gsb->getSheetsFromUser($_SESSION['user']['id']); // ... tputes les fiches de l'utilisateur actuel en tableau
$ficheCourante = $gsb->getCurrentSheet($_SESSION['user']['id']); // ... la fiche courante de l'utilisateur en tableau
$detailFiche = $gsb->getSheetDetails($ficheCourante['id']); // ... les détails de cette même fiche (les lignes)
$montantsType = $gsb->getFeeAmounts(); // ... les coûts de chaque type
$libellesEtat = $gsb->getStates(); // ... les libéllés des types

//Initialiser les valeurs des quantitées affichées à 0
$QTE_ETP = 0;
$QTE_KM = 0;
$QTE_NUI = 0;
$QTE_REP = 0;

//totaux
$total_forfait = 0;
$total_hors_forfait = 0;

//addition des quantité de frais et du montant total
foreach ($detailFiche['forfait'] as $key => $frais) {
	switch ($frais['id_typefrais']) {
		case 'ETP':
			$QTE_ETP+=$frais['quantite'];
			break;
		case 'KM':
			$QTE_KM+=$frais['quantite'];
			break;
		case 'NUI':
			$QTE_NUI+=$frais['quantite'];
			break;
		case 'REP':
			$QTE_REP+=$frais['quantite'];
			break;					
	}
	$total_forfait += $frais['quantite']*$montantsType[$frais['id_typefrais']];
}

//addition du total des frais hors forfait
foreach ($detailFiche['hors_forfait'] as $key => $frais) {
	$total_hors_forfait += $frais['montant'];
}

include("navigation.php");
?>

	<!-- CONTENU (DROITE) -->
	<div id="entry-content">
		
		<!-- Résumé du mois en cours -->
		<div id="summary">
			<div class="entry-header">
				<span>Mois de <?= $gsb->month[(int)date("n", time())-1]; ?></span>
				<button type="button" id="add">Ajouter</button>			
			</div>
			
			<div class="summary-detail">
				<div>
					<span>Étapes</span>
					<span id="etapes"><?= $QTE_ETP; ?></span>
				</div>
				<div>
					<span>Nuitées</span>
					<span id="nuite"><?= $QTE_NUI; ?></span>
				</div>
				<div>
					<span>Repas</span>
					<span id="repas"><?= $QTE_REP; ?></span>
				</div>
				<div>
					<span>Voyages</span>
					<span id="voyage"><?= $QTE_KM; ?> km</span>
				</div>				
			</div>

			<div class="total">
				<span><strong>Total forfaitaire : </strong><?= $total_forfait; ?> €</span>
				<span><strong>Total hors-forfait : </strong><?= $total_hors_forfait; ?> €</span>
			</div>
		</div>
		<a class="more" href="/details.php?fiche=<?=$ficheCourante['id'];?>">Voir plus ...</a>

		<!-- Historique -->
		<!-- Il peut déclarer des fiches d'y y'a 1 an ou moins MAX -->
		<div id="history">
			<div class="entry-header">
				<span>Historique</span>
			</div>

			<div class="history-list">
				<ul>
					<?php
					foreach ($fiches as $key => $fiche) {
						$month = $gsb->month[(int)date("n", strtotime($fiche['date']))-1];
						$year = date("Y", strtotime($fiche['date']));
						$etat = $libellesEtat[$fiche['id_etat']];
					?>
					<li>
						<span class="date"><?= $month . " " . $year; ?></span>
						<span class="etat"><strong>Etat : </strong><?= $etat; ?></span>
					</li>
					<?php
					}
					?>									
				</ul>
			</div>

		</div><!-- /historique -->

		<div id="add-popup">
			<div class="entry-header">
				<span>Ajouter un frais</span>
				<button type="button" class="red" id="cancel">Annuler</button>
			</div>		
			<form action="">
				<p>Catégorie :</p>
				<input type="radio" name="cat_frais" value="Forfaitaire">
				<input type="radio" name="cat_frais" value="Hors forfait">
				<p>Type :</p>
				<select name="type_frais">
					<option value="ETP"></option>
					<option value="KM"></option>
					<option value="NUI"></option>
					<option value="REP"></option>
				</select>
				<p>Quantité :</p>
				<input type="number" name="qty" value="0" min="0">
			</form>
		</div>
		

	</div><!-- /entry-content -->