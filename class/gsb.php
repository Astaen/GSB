<?php

class GSB {

	/* Attributs */
	public $slogan = "";
	private $site_title_default = "Portail GSB";
	public $site_title = "Portail GSB";
	private $logged = true;
	public $SITE_PATH;
	public $INCLUDE_PATH;

	/* Constructeurs */
	public function __construct() {
		$this->SITE_PATH = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
		$this->INCLUDE_PATH = $this->SITE_PATH."includes/";
	}

	/* Méthodes */
	public function setTitle($update = true, $title) {
		if($update) {
			$this->site_title = $this->site_title_default. " | " . $title;
		} else {
			$this->site_title = $title;
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