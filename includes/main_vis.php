<!-- body -->

<!-- MENU (GAUCHE) -->
<nav>
	<img src=""> <!-- Photo de l'utilisateur -->
	<div class="nav">
		<ul>
			<li><a class="nav-link icon-home" href="">Accueil</a></li>
			<li><a class="nav-link" href="">Nouvelle fiche de frais</a></li>
			<li><a class="nav-link" href="">Gestion des fiches de frais</a></li>
			<li><a class="nav-link" href="?logout">Déconnexion</a></li>
		</ul>
	</div>
</nav>

<?= '<img src="/img/avatar/'.$_SESSION['user']['login'].'.png" />'; ?>
<!-- CONTENU (DROITE) -->
<div id="main">
	
	<!-- Résumé du mois en cours -->
	<div id="summary">
		<span class="">Mois de <?php /* Mois actuel */ ?></span>
		<button type="button" id="add">Ajouter</button>

		<div>
			<span>Étapes</span>
			<span id="etapes">0</span>
		</div>
		<div>
			<span>Nuitées</span>
			<span id="nuite">0</span>
		</div>
		<div>
			<span>Repas</span>
			<span id="repas">0</span>
		</div>
		<div>
			<span>Voyages</span>
			<span id="voyage">0 Km</span>
		</div>
	</div>

	<!-- Historique -->
	<!-- Il peut déclarer des fiches d'y y'a 1 an ou moins MAX -->
	<div id="history">
		<div class=""></div>
	</div>
</div>

<!-- /body -->