<!-- body -->
<div id="main">
<?php if(isset($_GET['login'])) { echo "loooool"; } ?>
	<!-- MENU (GAUCHE) -->
	<nav>
		<?php 
		if(file_exists($gsb->SITE_PATH.'img/avatar/'.$_SESSION['user']['login'].'.png')) {
			$file = '/img/avatar/'.$_SESSION['user']['login'].'.png';
		} else {
			$file = '/img/avatar/anon.jpg';
		}
		?>
		<img class="avatar" src="<?= $file; ?>" />

		<div class="bio">
			<p class="user-name"><?= $_SESSION['user']['prenom'] . " <strong>" . strtoupper($_SESSION['user']['nom']) ."</strong>"; ?></p>
			<p class="user-hiring-date">Employé depuis <?= date("Y",strtotime(str_replace("-", "/", $_SESSION['user']['date_embauche']))); ?></p>					
		</div>

		<ul class="nav-menu">
			<li><a class="nav-link icon-home" href="">Tableau de bord</a></li>
			<li><a class="nav-link" href="">Gestion des frais</a></li>
			<li><a class="nav-link" href="?logout">Déconnexion</a></li>
		</ul>
	</nav>

	<!-- CONTENU (DROITE) -->
	<div id="entry-content">
		
		<!-- Résumé du mois en cours -->
		<div id="summary">
			<div class="summary-header">
				<span>Mois de <?= $gsb->month[(int)date("n", time())-1]; ?></span>
				<button type="button" id="add">Ajouter</button>			
			</div>
			
			<div class="summary-detail">
				<div>
					<span>Étapes</span>
					<span id="etapes">5</span>
				</div>
				<div>
					<span>Nuitées</span>
					<span id="nuite">0</span>
				</div>
				<div>
					<span>Repas</span>
					<span id="repas">11</span>
				</div>
				<div>
					<span>Voyages</span>
					<span id="voyage">245 km</span>
				</div>				
			</div>

			<div class="total">
				<span><strong>Total forfaitaire :</strong> 150€<?php //total ?></span>
				<span><strong>Total hors-forfait :</strong> 250€<?php //total ?></span>
			</div>
		</div>

		<!-- Historique -->
		<!-- Il peut déclarer des fiches d'y y'a 1 an ou moins MAX -->
		<div id="history">
			<div class="history-header">
				<span>Historique</span>
			</div>

			<div class="history-list">
				<ul>
					<li>
						<span class="date">Février 2015</span>
					</li>
					<li>
						<span class="date">Janvier 2015</span>
					</li>
					<li>
						<span class="date">Décembre 2014</span>
					</li>
					<li>
						<span class="date">Novembre 2014</span>
					</li>										
				</ul>
			</div>

		</div><!-- /historique -->

	</div><!-- /entry-content -->

</div>
<!-- /body -->