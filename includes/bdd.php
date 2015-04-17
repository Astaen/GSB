<?php
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=gsb', 'root', '');
}
catch (Exception $e)
{
		die('Erreur : ' . $e->getMessage());
}

$bdd->query('SET NAMES utf8');
//commentaire
?>
