<!-- body -->

<!-- MENU (GAUCHE) -->
<div class="nav-main">
	<div class="logo"></div>
	<div class="nav">
		<ul>
			<li><a class="nav-link icon-home" href="">Accueil</a></li>
			<li><a class="nav-link" href="">Nouvelle fiche de frais</a></li>
			<li><a class="nav-link" href="">Gestion des fiches de frais</a></li>
			<li><a class="nav-link" href="?logout">Déconnexion</a></li>
		</ul>
	</div>
</div>

<!-- CONTENU (DROITE) -->
<div class="container">
	<h2>Gestion des frais :</h2>

	<!-- FORMULAIRE -->
	<form action="" method="GET">
		<table>
			<tr>
				<td>Période d'engagement : </td>
				<td><input type="text" id="PE" name="PE" placeholder="Ex: 10-2014" /></td>
			</tr>
		</table>

		<!-- FRAIS AU FORFAIT -->
		<table class="FF">
			<tr>
				<th>Repas du midi</th>
				<th>Nuitées</th>
				<th>Étape</th>
				<th>Km</th>
			</tr>
			<tr>
				<td><input type="text" id="" name="FF_repasMidi" /></td>
				<td><input type="text" id="" name="FF_nuit" /></td>
				<td><input type="text" id="" name="FF_eta" /></td>
				<td><input type="text" id="" name="FF_km" /></td>
			</tr>
		</table>
		<!-- FIN frais au forfait -->

		<!-- hors forfait -->
		<table class="HF">
			<tr>
				<th></th>
				<th>Date</th>
				<th>Libellé</th>
				<th>Montant (en €)</th>
				<th></th>
			</tr>
			<tr>
				<td></td>
				<td><input id="HF_date" name="HF_date" type="text" /></td>
				<td><input id="HF_lib" name="HF_lib" type="text" /></td>
				<td><input id="HF_amount" name="HF_amount" type="text" /></td>
				<td><button class="btn-add" id=""></button></td>
			</tr>
		</table>
		<!-- FIN hors forfait -->

		<!-- BOUTTONS DU FORMULAIRES -->
		<div>
			<input type="button" class="btn-valid" value="Valider" />
			<input type="button" class="btn-reset" value="Annulé" />
		</div>
	</form>
</div>

<!-- /body -->