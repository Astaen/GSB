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
			<li><a class="nav-link icon-home" href="/">Tableau de bord</a></li>
			<li><a class="nav-link" href="/fiches.php">Gestion des frais</a></li>
			<li><a class="nav-link" href="?logout">Déconnexion</a></li>
		</ul>
	</nav>