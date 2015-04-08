<!DOCTYPE html>
<html>
<head>
	<title><?= $gsb->site_title; ?></title>
	<meta charset="utf-8">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/client.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300italic,500' rel='stylesheet' type='text/css'>
	<?php if($_SESSION['user']['type'] == 'vis') { ?>
	<link rel="stylesheet" href="/css/style.css">
	<?php } else { ?>
	<link rel="stylesheet" href="/css/style_acc.css">
	<?php }?>
</head>
<body class="<?php if(isset($_POST['login'])) { echo "login"; } ?>">

<!-- Header -->
<header>
	<img src="/img/logo.png" id="logo"> <!-- Ici le logo -->
	<span class="ariane"><?= $gsb->printAriane(); ?></span>
	<span class="welcome">Bonjour, <strong><?= $_SESSION['user']['prenom']; ?></strong></span>
</header>

<!-- body -->
<div id="main">
