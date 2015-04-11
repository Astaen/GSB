<?php 
session_start();
include($_SERVER['DOCUMENT_ROOT'] ."/class/gsb.php");

$gsb->setTitle("Historique", true);
include("header.php");

if($_SESSION['user']['type'] == 'vis') {
	include("gsb_visitor/navigation.php"); // Affiche l'interface du visiteur
} else {
	include("gsb_accountant/navigation.php"); // Affiche l'interface du comptable
}

$fiches = $gsb->getSheetsFromUser($_SESSION['user']['id'], 1, 8); // ... toutes les fiches de l'utilisateur actuel en tableau
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
include("footer.php");
?>