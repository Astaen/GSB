<?php

class GSB {

	/* Attributs */
	public $slogan = "";
	private $site_title_default = "Portail GSB";
	public $site_title = "Portail GSB";
	public $site_path;

	/* Constructeurs */
	public function __construct() {
		$this->site_path = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
	}

	/* Méthodes */
	public function setTitle($update = true, $title) {
		if($update) {
			$this->site_title = $this->site_title_default. " | " . $title;
		} else {
			$this->site_title = $title;
		}
	}
}

$gsb = new GSB();

?>