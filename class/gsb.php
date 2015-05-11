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
		} else {
			$name = "";
			$url = substr($_SERVER['REQUEST_URI'], 1); // Enlève le caractère "/"
			if(!$url)						{ $name = "Tableau de bord"; } // Dans le cas où on est à la racine (page d'accueil)
			else if($url == "index.php") 	{ $name = "Tableau de bord"; }
			else if($url == "fiches.php") 	{ $name = "Gestion des frais"; }
			else 							{ $name = "Gestion des frais"; } // Dans le cas où on serait sur la page détail
			return "<strong>Interface Comptable ></strong> $name";
		}
	}

	public function openNewSheet($user_id) {
		$bdd = $this->MySQLInit();
		$bdd->query("UPDATE fiche SET id_etat='CL' WHERE id_etat='CR' AND id_utilisateur = '$user_id'");
		$bdd->query("INSERT INTO fiche(id_utilisateur, id_etat) VALUES('$user_id','CR')");
		return $this->getCurrentSheet($user_id);
	}

	public function closeLastMonthSheets() {
		$bdd = $this->MySQLInit();
		$lastMonth = date("m")-1;
		$bdd->query("UPDATE fiche SET id_etat='CL' WHERE id_etat='CR' AND MONTH(date) = '$lastMonth'");
	}

	public function updateSheet($id, $type_frais, $qty) {
		$bdd = $this->MySQLInit();
		$date = date("Y-m-d H:i:s");
		$bdd->query("INSERT INTO `ligne_frais_forfait`(`id_fiche`, `id_typefrais`, `quantite`, `derniere_modif`) VALUES ('$id','$type_frais','$qty','$date') ON DUPLICATE KEY UPDATE quantite = quantite+'$qty', derniere_modif = '$date'");
	}

	public function addFee($id, $lib, $amt, $date) {
		$bdd = $this->MySQLInit();
		$bdd->query("INSERT INTO `ligne_frais_horsforfait`(`id_fiche`, `montant`, `libelle`, `date`) VALUES ('$id','$amt','$lib','$date')");
	}

	public function getOutsideFeesTotal($id_fiche) {
		$bdd = $this->MySQLInit();
		$res = $bdd->query("SELECT SUM(montant) FROM ligne_frais_horsforfait WHERE id_fiche = '$id_fiche'");
		return $res->fetch();
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
	 * @start 	(int)	Début de la recherche
	 * @qty 	(int)	Quantité de résultat max
	 * @return un tableau contenant les fiches
	 */ 	
	public function getSheetsFromUser($user_id, $start = null, $qty = null) {
		if($qty == null || $start = null) {
			$sql = "SELECT * FROM fiche WHERE id_utilisateur=$user_id ORDER BY date DESC";
		} else {
			$sql = "SELECT * FROM fiche WHERE id_utilisateur=$user_id ORDER BY date DESC LIMIT $start,$qty";
		}
		$result = Array();
		$bdd = $this->MySQLInit();
		$res = $bdd->query($sql);
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

	/**
	 * Retourne toute les fiches recherché avant une date ou plusieurs date
	 *
	 * @keyword 	(string/int)	Date clé(s)
	 * @user_id		(int)			ID de l'utilisateur
	 * 
	 * @return 		(array)			Contenant toute les fiches
	 */
	public function searchSheetsByDate($keyword, $user_id = null) {
		if(empty($keyword)) { return false; }
		$found = array();
		$bdd = $this->MySQLInit();
		// Si on veut rechercher les fiches d'un utilisateur en particulier (visiteur)
		if(isset($user_id)) {
			// Si il y a 1 mot clé
			if(sizeof($keyword) == 1) { // Recherche l'année ou le mois
				$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur = ? AND (YEAR(date) = ? OR MONTH(date) = ?) ORDER BY date DESC");
				$res->execute(array($user_id, $keyword, $keyword));
				while($data = $res->fetch(PDO::FETCH_ASSOC)) {
					array_push($found, $data);
				}
			}
			else { // Recherche le mois puis l'année
				$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur = ? AND MONTH(date) = ? AND YEAR(date) = ? ORDER BY date DESC");
				$res->execute(array($user_id, $keyword[0], $keyword[1]));
				while($data = $res->fetch(PDO::FETCH_ASSOC)) {
					array_push($found, $data);
				}
				// Si on trouve rien on test l'autre possibilité | Recherche l'année puis le mois
				if(empty($found)) {
					$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur = ? AND YEAR(date) = ? AND MONTH(date) = ? ORDER BY date DESC");
					$res->execute(array($user_id, $keyword[0], $keyword[1]));
					while($data = $res->fetch(PDO::FETCH_ASSOC)) {
						array_push($found, $data);
					}
				}
			}
		}
		// Sinon on recherche toutes les fiches de tout les utilisteurs (compta)
		else {
			// Si il y a 1 mot clé
			if(sizeof($keyword) == 1) {
				$res = $bdd->prepare("SELECT * FROM fiche WHERE MONTH(date) = ? OR YEAR(date) = ? ORDER BY date DESC");
				$res->execute(array($keyword, $keyword));
				while($data = $res->fetch(PDO::FETCH_ASSOC)) {
					array_push($found, $data);
				}
			}
			else {
				$res = $bdd->prepare("SELECT * FROM fiche WHERE MONTH(date) = ? AND YEAR(date) = ? ORDER BY date DESC");
				$res->execute(array($keyword[0], $keyword[1]));
				while($data = $res->fetch(PDO::FETCH_ASSOC)) {
					array_push($found, $data);
				}
				if(empty($found)) {
					$res = $bdd->prepare("SELECT * FROM fiche WHERE YEAR(date) = ? AND MONTH(date) = ? ORDER BY date DESC");
					$res->execute(array($keyword[0], $keyword[1]));
					while($data = $res->fetch(PDO::FETCH_ASSOC)) {
						array_push($found, $data);
					}
				}
			}
		}
		// Retour
		if(sizeof($found)) {
			return $found;
		} else {
			return false;
		}
	}

	/**
	 * Renvoie toute les fiches dont le mot clé est dans le champ nom ou prénom
	 *
	 * @keyword 	(string)	Mot clé pour la recherche
	 * @return un tableau contenant les fiches ou false si aucune n'est ouverte
	 */
	public function searchSheetsByKeyword($keyword, $user_id = null) {
		if(empty($keyword)) { return false; }
		$bdd = $this->MySQLInit();
		if(isset($user_id)) { //Si on veut rechercher les fiches d'un utilisateur en particulier (visiteur)
			if(sizeof($keyword) == 1) {
				// Cherche des fiches par un nom ou prenom
				$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur = ? AND id_utilisateur = (SELECT id FROM utilisateur WHERE nom LIKE ? OR prenom LIKE ?)");
				$res->execute(array($user_id, "%".$keyword."%", "%".$keyword."%"));
				$found = $res->fetchAll(PDO::FETCH_ASSOC);
			}
			else {
				$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur = ? AND id_utilisateur = (SELECT id FROM utilisateur WHERE nom LIKE ? AND prenom LIKE ?)");
				$res->execute(array($user_id, "%".$keyword[0]."%", "%".$keyword[1]."%"));
				$found = $res->fetchAll(PDO::FETCH_ASSOC);
				if (empty($found)) {
					$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur = ? AND id_utilisateur = (SELECT id FROM utilisateur WHERE prenom LIKE ? AND nom LIKE ?)");
					$res->execute(array($user_id, "%".$keyword[0]."%", "%".$keyword[1]."%"));
					$found = $res->fetchAll(PDO::FETCH_ASSOC);
				}
			}
		}
		else { //Sinon on recherche toutes les fiches de tout les utilisteurs (compta)
			if(sizeof($keyword) == 1) {
				// Cherche des fiches par un nom ou prenom
				$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur = (SELECT id FROM utilisateur WHERE nom LIKE ? OR prenom LIKE ?)");
				$res->execute(array("%".$keyword."%", "%".$keyword."%"));
				$found = $res->fetchAll(PDO::FETCH_ASSOC);
			}
			else {
				$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur = (SELECT id FROM utilisateur WHERE nom LIKE ? AND prenom LIKE ?)");
				$res->execute(array("%".$keyword[0]."%", "%".$keyword[1]."%"));
				$found = $res->fetchAll(PDO::FETCH_ASSOC);
				if(empty($found)) {
					$res = $bdd->prepare("SELECT * FROM fiche WHERE id_utilisateur = (SELECT id FROM utilisateur WHERE prenom LIKE ? AND nom LIKE ?)");
					$res->execute(array("%".$keyword[0]."%", "%".$keyword[1]."%"));
					$found = $res->fetchAll(PDO::FETCH_ASSOC);
				}
			}
		}
		// Retour
		if(sizeof($found)) {
			return $found;
		}
		else {
			return false;
		}
	}

	/**
	 * Retourne un mot avec la premier lettre en Majuscule (pour la recherche dans la DB)
	 */
	public function FirstToUpper($word) {
		if(is_array($word)) {
			foreach ($word as $keyword) {
				$keyword = ucfirst(strtolower($keyword));
			}
			return $word;
		}
		else { return ucfirst(strtolower($word)); }
	}

	/**
	 * Renvoie toute les fiches qui on l'état "En cours" dans un tableau
	 *
	 * @return un tableau contenant les fiches ou false si aucune n'est ouverte
	 */ 
	public function getOpenedSheets() {
		$open = array();
		$bdd = $this->MySQLInit();
		$res = $bdd->query("SELECT * FROM fiche WHERE id_etat = 'CR'");
		while ($sheet = $res->fetch(PDO::FETCH_ASSOC)) {
			array_push($open, $sheet);
		}
		if(sizeof($open))
			return $open;
		else
			return false;
	}

	/**
	 * Renvoie le nom et le prénom d'un utilisateur
	 *
	 * @user_id		(int)	ID de l'utilisateur
	 * @return Un tableau
	 */
	public function getUserInfo($user_id) {
		$bdd = $this->MySQLInit();
		$res = $bdd->prepare("SELECT nom, prenom FROM utilisateur WHERE id = ?");
		$res->execute(array($user_id));
		$data = $res->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	/**
	 * Renvoie toute les fiches de frais qui ne son pas en état "En cours"
	 *
	 * @return Si aucun résultat renvoie FALSE, sinon une tableau à 3 dimenssions
	 */
	public function getEverySheetsNotOpen() {
		$sheets = array();
		$bdd = $this->MySQLInit();
		$res = $bdd->query("SELECT * FROM fiche WHERE id_etat NOT IN('CR') ORDER BY date DESC");
		while($fiche = $res->fetch(PDO::FETCH_ASSOC)) {
			array_push($sheets, $fiche);
		}
		if(sizeof($sheets))
			return $sheets;
		else
			return false;
	}

	public function changeStates($etat, $id) {
		$bdd = $this->MySQLInit();
		$req = $bdd->prepare("UPDATE fiche SET id_etat=? WHERE id = ?");
		$req->execute(array($etat, $id));
		if($req->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getClosedSheets() {
		$closed = array();
		$bdd = $this->MySQLInit();
		$res = $bdd->query("SELECT * FROM fiche WHERE id_etat = 'CL' ORDER BY date DESC");
		while ($sheet = $res->fetch(PDO::FETCH_ASSOC)) {
			array_push($closed, $sheet);
		}
		if(sizeof($closed))
			return $closed;
		else
			return false;
	}

	public function getRefundedSheets(){
		$refunded = array();
		$bdd = $this->MySQLInit();
		$res = $bdd->query("SELECT * FROM fiche WHERE id_etat = 'RB' ORDER BY date DESC");
		while ($sheet = $res->fetch(PDO::FETCH_ASSOC)) {
			array_push($refunded, $sheet);
		}
		if(sizeof($refunded))
			return $refunded;
		else
			return false;
	}

	public function getValidatedSheets(){
		$validated = array();
		$bdd = $this->MySQLInit();
		$res = $bdd->query("SELECT * FROM fiche WHERE id_etat = 'VA' ORDER BY date DESC");
		while ($sheet = $res->fetch(PDO::FETCH_ASSOC)) {
			array_push($validated, $sheet);
		}
		if(sizeof($validated))
			return $validated;
		else
			return false;
	}
}

$gsb = new GSB();

?>