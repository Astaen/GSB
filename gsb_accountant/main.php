<!-- body -->
<div id="main">
<?php
include("navigation.php");
?>

	<!-- CONTENU (DROITE) -->
	<div id="entry-content">
		
		<!-- Résumé du mois en cours -->
		<div id="summary">
			<div class="summary-header">
				<span>Mois de <?= $gsb->month[(int)date("n", time())-1]; ?></span>
			</div><br/>
			<p>Interface comptable ... a faire.</p>
		</div>

	</div><!-- /entry-content -->

</div>
<!-- /body -->