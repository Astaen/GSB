<?php

class GSB {

	/* Attributs */
	public $slogan = "";
	private $site_title_default = "Portail GSB";
	public $site_title = "Portail GSB";
	public $SITE_PATH;
	public $INCLUDE_PATH;

	/* Constructeurs */
	public function __construct() {
		$this->SITE_PATH = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
		$this->INCLUDE_PATH = $this->SITE_PATH."includes/";
	}

	/* Méthodes */
	public function setTitle($title, $update = true) {
		if($update) {
			$this->site_title = $this->site_title_default. " | " . $title;
		} else {
			$this->site_title = $title;
		}
	}

	public function MySQLInit() {
		require($this->SITE_PATH."includes/bdd.php");
		return $bdd;
	}

	public function userLogin($username, $password) {
		$bdd = $this->MySQLInit();
		$res = $bdd->prepare("SELECT * FROM visiteur WHERE login=? AND mdp=?");
		$res->execute(array($username, $password));
		$user = $res->fetch();
		if(!empty($user)) {
			$_SESSION['logged'] = true;
			$_SESSION['user'] = $user;
			return true;
		} else {
			return false;
		}
	}

	public function logged() {
		return $this->logged;
	}

	public function insert($file) {
		$gsb = $this;
		include($this->INCLUDE_PATH.$file);
	}

}

$gsb = new GSB();

?>