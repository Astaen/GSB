<!DOCTYPE html>
<html>
<head>
	<title><?= $gsb->site_title; ?></title>
	<meta charset="utf-8">
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300italic,500' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="/css/style.css">
</head>
<body>

<!-- Header -->
<header>
	<img src="/img/logo.png" id="logo"> <!-- Ici le logo -->
	<span class="ariane"><?= $gsb->printAriane(); ?></span>
	<span class="welcome">Bonjour, <?= $_SESSION['user']['prenom']; ?></span>
</header>