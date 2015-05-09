<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] ."/class/gsb.php");

$gsb->setTitle("Détail de la fiche " . $_GET['fiche'], true);
include("header.php");

$fiche = $gsb->getSheetById($_GET['fiche']); // Récupère les infos de la fiches toute entière
$detailsFiche = $gsb->getSheetDetails($_GET['fiche']); // Contient les détails de la fiche

if($_SESSION['user']['type'] == 'vis') {
	include("gsb_visitor/navigation.php"); // Affiche l'interface du visiteur
} else {
	include("gsb_accountant/navigation.php"); // Affiche l'interface du comptable
}
$montantsType = $gsb->getFeeAmounts();
$total_forfait = 0;
$total_hors_forfait = 0;
?>

<?php if (!$fiche) { // Vérifie si la fiche de frais existe sinon on redirige vers la page d'accueil
	header("Location: /");
	die();
}
?>
<div id="entry-content">

	<!-- Forfaitaire -->
	<div class="summary">
		<div class="entry-header">
			<span>Frais du mois de <?= $gsb->getMonth($fiche['date'])." ".$gsb->getYear($fiche['date']); ?></span>
			<?php if($_SESSION['user']['type'] == 'com'): ?>
				<form id="form-change-etat" method="POST" action="">
					<span class="alert-success">Modification réussie</span>
					<span class="alert-error">Modification impossible</span>
					<span class="etat-vis">État :</span>
					<select id="action-etat" name="action-etat">
						<option value="CL" <?= ($fiche['id_etat'] == "CL")?"selected":""; ?>>Cloturer</option>
						<option value="RB" <?= ($fiche['id_etat'] == "RB")?"selected":""; ?>>Rembourser</option>
						<option value="VA" <?= ($fiche['id_etat'] == "VA")?"selected":""; ?>>Valider</option>
					</select>
					<input type="submit" name="change-state" value="Valider" />
				</form>
			<?php endif; ?>
		</div>
		<?php if($_SESSION['user']['type'] == 'com'): ?>
		<div class="entry-header">
			<?php $nom = $gsb->getUserInfo($fiche['id_utilisateur']); ?>
			<span>Fiche de <?= $nom['nom'] . " " . $nom['prenom']; ?></span>
		</div>
		<?php endif; ?>
		<div class="entry-header">
			<span>Frais forfaitaire</span>
		</div>
		<?php if(sizeof($detailsFiche['forfait'])) { ?>
		<table class="forf">
			<tr>
				<th>Libellé</th>
				<th>Quantité</th>
				<th>Dernière modification</th>
				<th>Total</th>
			</tr>
			<?php foreach ($detailsFiche['forfait'] as $key => $frais):	?>
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
		<?php
		}
		else { echo "<p class=\"no-frais\">Aucun frais n'a été rentré</p>"; }
		?>
	</div>

	<!-- Hors forfait -->
	<div class="summary">
		<div class="entry-header">
			<span>Frais hors forfait</span>
		</div>
		<?php if(sizeof($detailsFiche['hors_forfait'])) { ?>
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
		<?php
		}
		else { echo "<p class=\"no-frais\">Aucun frais n'a été rentré</p>"; }
		?>
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