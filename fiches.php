<?php 
session_start();
include($_SERVER['DOCUMENT_ROOT'] ."/class/gsb.php");

$gsb->setTitle("Historique", true);
include("header.php");

if($_SESSION['user']['type'] == 'vis') { // SI C'EST UN VISITEUR
	include("gsb_visitor/navigation.php"); // Affiche l'interface du visiteur
	$fiches = $gsb->getSheetsFromUser($_SESSION['user']['id']); // ... toutes les fiches de l'utilisateur actuel en tableau
	$libellesEtat = $gsb->getStates(); // ... les libéllés des types
?>

<div id="entry-content">
	<div class="summary">
		<div class="entry-header">
			<span>Historique des frais</span>
		</div>
		<form class="search">
			<input type="text" name="search" id="search-history" class="search-history" placeholder="Recherche" autocomplete="off"/>
			<a href="" class="search-icon"></a>
		</form>
	</div>

	<div class="history-list">
		<?php if($fiches) { // Si il y a des fiches de frais... ?>
		<ul>
			<?php
			// Affiches les fiches de frais
			foreach ($fiches as $key => $fiche):
				$month = $gsb->month[(int)date("n", strtotime($fiche['date']))-1];
				$year = date("Y", strtotime($fiche['date']));
				$etat = $libellesEtat[$fiche['id_etat']];
			?>
			<li>
				<a href="<?php echo"/details.php?fiche=".$fiche['id']; ?>">
					<span class="date"><?= $month . " " . $year; ?></span>
					<span class="etat"><strong>Etat : </strong><?= $etat; ?></span>
				</a>
			</li>
			<?php
			endforeach;
			?>
		</ul>
		<?php
		} // Sinon
		else { echo "<p class=\"no-frais\">Aucune fiche de frais n'a été rentrée</p>"; }
		?>
	</div>
</div>

<?php 
} else { // SI C'EST UN COMPTABLE
	include("gsb_accountant/navigation.php"); // Affiche l'interface du comptable

	$fiches = $gsb->getEverySheetsNotOpen();
	$libellesEtat = $gsb->getStates(); // ... les libéllés des types
?>
<div id="entry-content">
	<div class="summary">
		<div class="entry-header">
			<span>Historique des frais</span>
		</div>
		<form class="search">
			<input type="text" name="search" id="search-history" class="search-history" placeholder="Recherche" autocomplete="off"/>
			<a href="" class="search-icon"></a>
		</form>
	</div>
	<div class="search-filter">
		<span>Trié par état : </span>
		<select class="search-filter-value" id="search-filter-value">
			<option value="">Choisir un état...</option>
			<option value="CL">Cloturé</option>
			<option value="VA">Validé</option>
			<option value="RB">Remboursé</option>
		</select>
	</div>

	<div class="history-list">
		<?php if($fiches) { // Si il y a des fiches de frais... ?>
		<ul>
			<?php
			// Affiches les fiches de frais
			foreach ($fiches as $key => $fiche):
				$month = $gsb->month[(int)date("n", strtotime($fiche['date']))-1];
				$year = date("Y", strtotime($fiche['date']));
				$etat = $libellesEtat[$fiche['id_etat']];
				$ficheDe = $gsb->getUserInfo($fiche['id_utilisateur']);
			?>
			<li>
				<a href="<?php echo"/details.php?fiche=".$fiche['id']; ?>">
					<span class="date"><?= $month . " " . $year; ?></span>
					<span class="proprietaire"><strong>Fiche de : </strong><?= $ficheDe['nom']." ".$ficheDe['prenom']; ?></span>
					<span class="etat"><strong>Etat : </strong><?= $etat; ?></span>
				</a>
			</li>
			<?php
			endforeach;
			?>
		</ul>
		<?php
		} // Sinon
		else { echo "<p class=\"no-frais\">Aucune fiche de frais n'a été rentrée</p>"; }
		?>
	</div>
</div>
<?php
}

include("footer.php");
?>