<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php $gsb->setTitle("Connexion"); echo $gsb->site_title; ?></title>
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300italic,500' rel='stylesheet' type='text/css'>	
	<link rel="stylesheet" href="/css/login.css">
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/login.js"></script>
</head>

<body>
	<div id="login_form">
		<h2>Gestion de frais GSB</h2>
		<form action="" method="">
			<label for="username">Nom d'utilisateur :</label>
			<input type="text" id="username" autocomplete="off" autofocus required>
			<label for="password">Mot de passe :</label>
			<input type="password" id="password" autocomplete="off" required>
			<input id="submit" type="submit" value="✔">
		</form>
		<img src="/img/loader.gif" class="loader" />
	</div>
</body>

</html>