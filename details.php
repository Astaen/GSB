<?php
session_start();
include("/class/gsb.php");

$gsb->setTitle("Détail de la fiche " . $_GET['fiche'], true);
include("header.php");

if($_SESSION['user']['type'] == 'vis') {
	include("gsb_visitor/navigation.php");
	$detailsFiche = $gsb->getSheetDetails($_GET['fiche']);
} else {
	include("gsb_accountant/navigation.php");
}
$montantsType = $gsb->getFeeAmounts();
$total_forfait = 0;
$total_hors_forfait = 0;
?>

<div id="entry-content">

	<!-- Forfaitaire -->
	<div class="summary">
		<div class="entry-header">
			<span>Frais forfaitaire</span>
		</div>
		<table class="forf">
			<tr>
				<th>Libellé</th>
				<th>Quantité</th>
				<th>Dernière modification</th>
				<th>Total</th>
			</tr>
			<?php foreach ($detailsFiche['forfait'] as $key => $frais): ?>
			<tr>
				<td>
					<?php
					if($frais['id_typefrais'] == "ETP") echo "Étape";
					if($frais['id_typefrais'] == "KM")  echo "Kilomètre";
					if($frais['id_typefrais'] == "NUI") echo "Nuitée";
					if($frais['id_typefrais'] == "REP") echo "Repas";
					?>
				</td>
				<td><?= $frais['quantite']; ?></td>
				<td><?= $frais['derniere_modif']; ?></td>
				<td><?= $frais['quantite'] * $montantsType[$frais['id_typefrais']]; ?></td>
			</tr>
			<?php $total_forfait += $frais['quantite'] * $montantsType[$frais['id_typefrais']]; ?>
			<?php endforeach; ?>
		</table>
	</div>

	<!-- Hors forfait -->
	<div class="summary">
		<div class="entry-header">
			<span>Frais hors forfait</span>
		</div>
		<table class="forf">
			<tr>
				<th>Libellé</th>
				<th>Date</th>
				<th>Montant (€)</th>
			</tr>
			<?php foreach ($detailsFiche['hors_forfait'] as $key => $frais): ?>
			<tr>
				<td><?= $frais['libelle']; ?></td>
				<td><?= $frais['date']; ?></td>
				<td><?= $frais['montant']; ?></td>
			</tr>
			<?php $total_hors_forfait += $frais['montant']; ?>
			<?php endforeach; ?>
		</table>
	</div>

	<div class="amount-details">
		<div><strong>Total forfaitaire : </strong><?= $total_forfait; ?> €</div>
		<div><strong>Total hors-forfait : </strong><?= $total_hors_forfait; ?> €</div>
		<div><strong>Total : </strong><?= $total_forfait + $total_hors_forfait; ?> €</div>
	</div>

</div>

<?php
include("footer.php");
?>