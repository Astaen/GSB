<?php
include("navigation.php");
$fiches = $gsb->getOpenedSheets(); // Récupère les fiches En cours
$libellesEtat = $gsb->getStates(); // ... les libéllés des types
?>

<!-- CONTENU (DROITE) -->
<div id="entry-content">
	
	<!-- Résumé du mois en cours -->
	<div id="summary">
		<div class="summary-header">
			<span>Mois de <?= $gsb->month[(int)date("n", time())-1]; ?></span>
			<?php $pluriel = (count($fiches) > 1) ? "fiches en cours" : "fiche en cours"; ?> 
			<span><?php echo "<strong>".count($fiches)."</strong> " . $pluriel; ?></span>
		</div>

		<div class="history-list">
			<ul>
				<?php
				if($fiches) {
					foreach ($fiches as $key => $fiche) {
						$month = $gsb->month[(int)date("n", strtotime($fiche['date']))-1];
						$year = date("Y", strtotime($fiche['date']));
						$etat = $libellesEtat[$fiche['id_etat']];
						$ficheDe = $gsb->getUserInfo($fiche['id_utilisateur']);
				?>
				<li>
					<a href="/details.php?fiche=<?=$fiche['id'];?>">
					<span class="date"><?= $month . " " . $year; ?></span>
					<span class="proprietaire"><strong>Fiche de : </strong><?= $ficheDe['nom']." ".$ficheDe['prenom']; ?></span>
					<span class="etat"><strong>Etat : </strong><?= $etat; ?></span>
					</a>
				</li>
				<?php
					}
					echo '<a class="more" href="/fiches.php">Afficher plus ...</a>';
				}
				else {
					echo "<p class=\"no-frais\">Aucune fiche de frais n'a été rentrée</p>";
				}
				?>
			</ul>
		</div>
	</div>

</div><!-- /entry-content -->