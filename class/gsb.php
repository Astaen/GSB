<?php
class GSB {

	/************************* 
	* Attributs 
	*************************/
	public $slogan = "";
	private $site_title_default = "Portail GSB";
	public $site_title = "Portail GSB";
	public $month = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre","Novembre", "Décembre");
	public $location = "";
	public $SITE_PATH;
	public $INCLUDE_PATH;

	/************************* 
	* Constructeurs 
	*************************/
	public function __construct() {
		$this->SITE_PATH = $_SERVER['DOCUMENT_ROOT'];
		$this->INCLUDE_PATH = $this->SITE_PATH."includes/";
	}

	/************************* 
	* Méthodes 
	*************************/

	/**
	 * Change le nom du site ou le met à jour
	 *
	 * @title	(string)	Nouveau nom du site
	 * @update	(bool)	Si true, le nom du site sera mis à jour avec un séparateur "Nom du site | Ajout", sinon le nom entier sera modifié
	 * @return	(bool) false si le titre est vide
	 */ 
	public function setTitle($title, $update = true) {
		if(empty($title)) {
			return false;
		}
		if($update) {
			$this->site_title = $this->site_title_default. " | " . $title;
		} else {
			$this->site_title = $title;
		}
	}

	/**
	 * Initialise PDO
	 *
	 * @return l'objet de connexion PDO
	 */ 
	public function MySQLInit() {
		if(!isset($bdd)) {
			require($this->SITE_PATH."includes/bdd.php");
		}
		return $bdd;
	}

	/**
	 * Connecte l'utilisateur au site
	 *
	 * @username	(string)	Identifiant de l'utilisateur
	 * @password	(string)	Mot de passe de l'utilisateur
	 * @return	(bool) true si l'utilisateur existe dans la base de données, sinon false
	 */ 
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

	/**
	 * Affiche le fil d'ariane
	 *
	 * @return	(string) Fil d'ariane sous forme de string
	 */ 
	public function printAriane() {
		if($_SESSION['user']['type'] == 'vis') {
			$name = "";
			$url = substr($_SERVER['REQUEST_URI'], 1); // Enlève le caractère "/"
			if(!$url)						{ $name = "Tableau de bord"; } // Dans le cas où on est à la racine (page d'accueil)
			else if($url == "index.php") 	{ $name = "Tableau de bord"; }
			else if($url == "fiches.php") 	{ $name = "Gestion des frais"; }
			else 							{ $name = "Gestion des frais"; } // Dans le cas où on serait sur la page détail
			return "<strong>Interface Visiteur ></strong> $name";
		}
	}

	public function openNewSheet($user_id) {
		$bdd = $this->MySQLInit();
		$bdd->query("UPDATE fiche SET id_etat='CL' WHERE id_etat='CR' AND id_utilisateur = '$user_id'");
		$bdd->query("INSERT INTO fiche(id_utilisateur, id_etat) VALUES('$user_id','CR')");
		return $this->getCurrentSheet($user_id);
	}

	//renvoie LA fiche de l'utilisateur ouverte ce mois-ci dont l'ID est passé en paramètre, dans un tableau (sans les détails)
	/**
	 * Renvoie toutes les fiches de l'utilisateur dont l'ID est passé en paramètre, dans un tableau
	 *
	 * @user_id	(int)	ID de l'utilsateur
	 * @return un tableau contenant les fiches
	 */ 		
	public function getCurrentSheet($user_id) {
		$bdd = $this->MySQLInit();
		$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur=? AND id_etat='CR' AND MONTH(date) =?");
		$res->execute(array($user_id, date("m", time())));
		return $res->fetch();
	}

	/**
	 * Renvoie toutes les fiches de l'utilisateur dont l'ID est passé en paramètre, dans un tableau
	 *
	 * @user_id	(int)	ID de l'utilsateur
	 * @return un tableau contenant les fiches
	 */ 	
	public function getSheetsFromUser($user_id, $start, $qty) {
		$result = Array();
		$bdd = $this->MySQLInit();
		$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur=? ORDER BY date DESC LIMIT $start,$qty");
		$res->execute(array($user_id));
		while($data = $res->fetch()) {
			array_push($result, $data);
		}
		if(sizeof($result)) {
			return $result;
		} else {
			return false;
		}		
	}	

	/**
	 * Renvoie la fiche dont l'ID est passé en paramètre dans un tableau
	 *
	 * @id	(int)	ID de la fiche
	 * @return un tableau contenant la fiche ou false si elle n'existe pas
	 */ 	
	public function getSheetById($id) {
		$bdd = $this->MySQLInit();
		$res = $bdd->prepare("SELECT * FROM fiche WHERE id=?");
		$res->execute(array($id));
		return $res->fetch();
	}	

	public function getUnvalidatedSheets() {

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

	// Fonction test pour recherche
	public function searchSheetsByDate($user_id, $keyword) {
		$found = array();
		if(empty($keyword)) { return false; }
		$bdd = $this->MySQLInit();
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Pour debugg, à enlever plus tard
		$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur = ? AND YEAR(date) = ? OR MONTH(date) = ?");
		$res->execute(array($user_id, $keyword, $keyword));
		while($data = $res->fetch(PDO::FETCH_ASSOC)) {
			array_push($found, $data);
		}
		if(sizeof($found)) {
			return $found;
		} else {
			return false;
		}
	}
}

$gsb = new GSB();

?>