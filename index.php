<?php
session_start();
include("/class/gsb.php");
include("/includes/const.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?= $gsb->site_title;?></title>
	</head>

	<body>
	<h1><?= $gsb->site_path; ?></h1>
	</body>
</html>