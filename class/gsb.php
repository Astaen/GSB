<?php
class GSB {

	/* Attributs */
	public $slogan = "";
	private $site_title_default = "Portail GSB";
	public $site_title = "Portail GSB";
	public $month = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre","Novembre", "Décembre");
	public $location = "";
	public $SITE_PATH;
	public $INCLUDE_PATH;

	/* Constructeurs */
	public function __construct() {
		$this->SITE_PATH = $_SERVER['CONTEXT_DOCUMENT_ROOT'];
		$this->INCLUDE_PATH = $this->SITE_PATH."includes/";
	}

	/* Méthodes */

	//Change le nom de
	public function setTitle($title, $update = true) {
		if($update) {
			$this->site_title = $this->site_title_default. " | " . $title;
		} else {
			$this->site_title = $title;
		}
	}

	public function MySQLInit() {
		if(!isset($bdd)) {
			require($this->SITE_PATH."includes/bdd.php");
		}
		return $bdd;
	}

	public function userLogin($username, $password) {
		$bdd = $this->MySQLInit();
		$res = $bdd->prepare("SELECT * FROM utilisateur WHERE login=? AND mdp=?");
		$res->execute(array($username, hash("sha256", $password)));
		$user = $res->fetch();
		if(!empty($user)) {
			$_SESSION['logged'] = true;
			$_SESSION['user'] = $user;
			return true;
		} else {
			return false;
		}
	}

	public function printAriane() {
		if($_SESSION['user']['type'] == 'vis') {
			return "<strong>Interface Visiteur ></strong> Tableau de bord";
		}
	}

	public function logged() {
		return $this->logged;
	}

	//renvoie toutes les fiches de l'utilisateur dont l'ID est passé en paramètre, dans un tableau
	public function getSheetsFromUser($user_id) {
		$result = Array();
		$bdd = $this->MySQLInit();
		$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur=?");
		$res->execute(array($user_id));
		while($data = $res->fetch()) {
			array_push($result, $data);
		}
		return $result;
	}

	//renvoie LA fiche de l'utilisateur dont l'ID est passé en paramètre, dans un tableau (sans les détails)
	public function getCurrentSheet($user_id) {
		$bdd = $this->MySQLInit();
		$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur=? AND id_etat='CR'");
		$res->execute(array($user_id));
		return $res->fetch();
	}

	public function getOpenedSheets() {

	}

	//renvoie dans un tableau à 3 dimensions, les lignes de frais
	public function getSheetDetails($sheet_id) {
		$bdd = $this->MySQLInit();

		//selection de toutes les lignes de frais forfaitaires
		$res = $bdd->prepare("SELECT * FROM ligne_frais_forfait WHERE id_fiche=?");
		$res->execute(array($sheet_id));
		$frais_forfait = $res->fetchAll();

		//selection de toutes les lignes de frais hors forfait
		$res = $bdd->prepare("SELECT * FROM ligne_frais_horsforfait WHERE id_fiche=?");
		$res->execute(array($sheet_id));
		$frais_h_forfait = $res->fetchAll();

		//création du tableau de retour
		$frais = Array(
			'forfait' => $frais_forfait,
			'hors_forfait' => $frais_h_forfait
			);	
		return $frais;
	}

	public function getFeeAmounts() {
		$result = Array();
		$bdd = $this->MySQLInit();
		$res = $bdd->query("SELECT * FROM type_frais");
		while($data = $res->fetch()) {
			$result[$data['id']] = $data['montant'];
		}
		return $result;
	}

	public function getStates() {
		$result = Array();
		$bdd = $this->MySQLInit();
		$res = $bdd->query("SELECT * FROM etat");
		while($data = $res->fetch()) {
			$result[$data['id']] = $data['libelle'];
		}
		return $result;
	}

	public function getMonth($date) {
		return $this->month[(int)date("n", strtotime($date))-1];
	}

	public function getYear($date) {
		return date("Y", strtotime($date));
	}	

}

$gsb = new GSB();

?>