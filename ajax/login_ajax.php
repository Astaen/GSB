<?php
session_start();
if(!isset($_POST['username'])) {
	header("Location: /");
}
include("../class/gsb.php");
echo $gsb->userLogin(htmlspecialchars($_POST['username']), htmlspecialchars($_POST['password']));
?>