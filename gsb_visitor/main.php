<?php
$user_id = $_SESSION['user']['id'];
// On récupère ...
$ficheCourante = $gsb->getCurrentSheet($user_id); // ... la fiche courante de l'utilisateur en tableau
if(!$ficheCourante) { //Si aucune fiche n'est ouverte pour le mois en cours, on en crée une nouvelle
	$ficheCourante = $gsb->openNewSheet($user_id);
}
$fiches = $gsb->getSheetsFromUser($user_id); // ... toutes les fiches de l'utilisateur actuel en tableau
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
				<span>Mois de <?= $gsb->getMonth($ficheCourante['date']); ?></span>
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
					if($fiches) {
						foreach ($fiches as $key => $fiche) {
							$month = $gsb->month[(int)date("n", strtotime($fiche['date']))-1];
							$year = date("Y", strtotime($fiche['date']));
							$etat = $libellesEtat[$fiche['id_etat']];
					?>
					<li>
						<a href="/details.php?fiche=<?=$fiche['id'];?>">
						<span class="date"><?= $month . " " . $year; ?></span>
						<span class="etat"><strong>Etat : </strong><?= $etat; ?></span>
						</a>
					</li>
					<?php
						}
						echo '<a class="more" href="/fiches.php">Afficher plus ...</a>';
					} else {
						echo "<p class=\"no-frais\">Aucune fiche de frais n'a été rentrée</p>";
					}
					?>									
				</ul>
			</div>
			<!-- Lien ajouter dans la condition juste au dessus, si il y a des fiches de frais -->
			<!--	<a class="more" href="/fiches.php">Afficher plus ...</a>	-->
		</div><!-- /historique -->

		<div id="add-popup">
			<div class="entry-header">
				<span>Ajouter un frais</span>
				<button type="button" class="red" id="cancel">Annuler</button>
			</div>		
			<form action="" method="post">
				<p>Catégorie :</p>
				<label for="cat_fraisf">Frais forfaitaire</label>
				<input type="radio" name="cat_frais" id="cat_fraisf" checked>
				<label for="cat_fraish">Frais hors-forfait</label>
				<input type="radio" name="cat_frais" id="cat_fraish">
				<p class="typ">Type de frais :</p>
				<select class="typ" name="type_frais">
					<optgroup label="Type de frais" default>
						<option value="ETP">Etapes</option>
						<option value="KM">Voyage</option>
						<option value="NUI">Nuitées</option>
						<option value="REP">Repas</option>					
					</optgroup>
				</select>
				<p class="lib">Libellé :</p>
				<input type="text" class="lib" name="libelle" placeholder="Intitulé du frais...">				
				<p class="date_valeur">Date valeur : </p>
				<input type="date" class="date_valeur" name="date_valeur">
				<p class="qty">Quantité :</p>
				<input type="number" class="qty" name="qty" value="0" min="0">
				<button type="submit">Envoyer</button>
			</form>
		</div>
		

	</div><!-- /entry-content -->