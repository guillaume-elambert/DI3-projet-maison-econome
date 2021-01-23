<?php

/** 
 * fichier class.PdoProjet3A.inc.php
 * contient la classe PdoProjet3A qui fournit 
 * un objet pdo et des méthodes pour récupérer des données d'une BD
 */

/** 
 * PdoProjet3A
 *
 * classe PdoProjet3A : classe d'accès aux données. 
 * Utilise les services de la classe PDO
 * pour l'application GsbParam
 * Les attributs sont tous statiques,
 * les 5 premiers pour la connexion
 *
 * @package  projet-bdd-3a\util
 * @date 16/12/2020
 * @version 1
 * @author Guillaume ELAMBERT
 */

class PdoProjet3A
{

	/**
	 * type et nom du serveur de bdd
	 * @var string $serveur
	 */
	private static $serveur = "mysql:host=" . DB_HOST . ";";


	/**
	 * Port du serveur de BDD
	 * @var string $port
	 */
	private static $port = "port=" . DB_PORT . ";";

	/**
	 * nom de la BD 
	 * @var string $bdd
	 */
	private static $bdd = "dbname=" . DB_NAME . ";";

	/**
	 * nom de l'utilisateur utilisé pour la connexion 
	 * @var string $user
	 */
	private static $user = DB_USER;

	/**
	 * mdp de l'utilisateur utilisé pour la connexion 
	 * @var string $mdp
	 */
	private static $mdp = DB_PASSWORD;

	/**
	 * Options du pdo (exemple: affichage des erreurs)
	 */
	private static $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

	/**
	 * objet pdo de la classe Pdo pour la connexion 
	 * @var string $monPdo
	 */
	private static $monPdo = null;

	private static $monPdoProjet3A = null;


	/**
	 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
	 * pour toutes les méthodes de la classe
	 */
	private function __construct()
	{
		try {
			PdoProjet3A::$monPdo = new PDO(PdoProjet3A::$serveur . PdoProjet3A::$port . PdoProjet3A::$bdd, PdoProjet3A::$user, PdoProjet3A::$mdp, PdoProjet3A::$options);
			$statement = PdoProjet3A::$monPdo->prepare("SET CHARACTER SET utf8");
			$statement->execute();
		} catch (PDOException $e) {
			echo "Erreur!: " . $e->getMessage() . "<br/>";
			die();
		}
	}


	/**
	 * Destructeur
	 */
	public function _destruct()
	{
		PdoProjet3A::$monPdo = null;
	}


	/**
	 * Fonction statique qui crée l'unique instance de la classe
	 *
	 * Appel : $instancePdo = PdoProjet3A::getPdo();
	 * @return Pdo $monPdoProjet3A l'unique objet de la classe Pdo
	 */
	public static function getPdo()
	{
		if (PdoProjet3A::$monPdoProjet3A == null) {
			PdoProjet3A::$monPdoProjet3A = new PdoProjet3A();
		}
		return PdoProjet3A::$monPdoProjet3A;
	}

	/**
	 * Connecte un utilisateur au site
	 *
	 * @param string $mail le mail de l'utilisateur
	 * @param string $mdp le mot de passe de l'utilisateur
	 * @return array $lesErreurs l'ensemble des potentielles erreurs lors de la connexion
	 */
	public function connexionUtilisateur($mail, $mdp)
	{
		$lesErreurs = array();

		if (PdoProjet3A::$monPdo) {
			$req = PdoProjet3A::$monPdo->prepare("SELECT * FROM utilisateur NATURAL JOIN role WHERE mail='$mail';");
			$req->execute();
			$lesErreurs = array();

			if ($req) {

				$req = $req->fetch();

				//L'utilisateur existe
				if ($req) {
					$mdpBDD = $req['motDePasse'];
					$role = $req['libelleRole'];

					//Le mot de passe correspond au mot de passe haché dans la BDD
					if (password_verify($mdp, $mdpBDD)) {
						if (strcmp($role, 'admin') == 0) {
							$_SESSION['admin'] = true;
						}
						$_SESSION['user'] = $mail;
					} else {
						$lesErreurs[] = 'Mot de passe incorrect !';
					}
				} else {
					$lesErreurs[] = "L'adresse mail renseignée ne correspond pas à un compte utilisateur...";
				}
			} else {
				$lesErreurs[] = "Une erreur s'est produite...";
			}
		} else {
			$lesErreurs[] = "Impossible de se connecter à la base de données...";
		}
		return $lesErreurs;
	}

	/**
	 * Fonction qui insert un nouvel utilisateur dans la base de données
	 * 
	 * @param string $mail Addresse mail du nouvel utilisateur
	 * @param string $mdp Mot de passe du nouvel utilisateur
	 * @param string $nom Nom du nouvel utilisateur
	 * @param string $prenom Prenom du nouvel utilisateur
	 * @param date $dateNaiss Date de naissance du nouvel utilisateur
	 * @return array $lesErreurs l'ensemble des potentielles erreurs lors de l'inscription
	 */
	public function creerUser($mail, $mdp, $nom, $prenom, $dateNaiss)
	{
		$lesErreurs = array();
		$msg = "Une erreur s'est produite lors de la création de votre compte...";

		if (PdoProjet3A::$monPdo) {
			$sql = "INSERT INTO utilisateur 
			(mail    , nomUtilisateur, prenomUtilisateur, dateNaissance, motDePasse, dateCreation, etat, idRole) VALUES
			('$mail' , '$nom'		 , '$prenom'		, '$dateNaiss' , '$mdp'    , CURRENT_DATE, 1   , (
				SELECT idRole 
				FROM role
				WHERE libelleRole = 'user'
			));";

			echo $sql;

			try {
				$req = PdoProjet3A::$monPdo->prepare($sql);
				if (!$req->execute()) {
					$lesErreurs[] = $msg;
				}
			} catch (PDOException $exceptionUtilisateur) {
				$lesErreurs[] = $msg;
			}
		}

		return $lesErreurs;
	}

	/**
	 * Fonction qui ajoute une location à un utilisateur
	 * 
	 * @param string $mail Addresse mail de l'utilisateur
	 * @param date $debutLocation Date de début de location de l'appartement de l'utilisateur
	 * @param int $immeuble Identifiant de l'immeuble de l'utilisateur
	 * @param int $appartement Identifiant de l'appartement de l'utilisateur (relatif à l'immeuble)
	 * @return array $lesErreurs l'ensemble des potentielles erreurs lors de l'insertion des données
	 */
	public function nouvelleLocation($mail, $debutLocation, $immeuble, $appartement)
	{
		$lesErreurs = array();
		$msg = "Une erreur s'est produite lors l'initialisation de votre docimicle, rendez-vous dans vous espace client.";

		$sql = "INSERT INTO louer
		(mail   , idImmeuble, idAppartement, debutLocation) VALUES
		('$mail', $immeuble, $appartement , '$debutLocation');";

		try {
			$req = PdoProjet3A::$monPdo->prepare($sql);
			if (!$req->execute()) {
				$lesErreurs[] = $msg;
			}
		} catch (PDOException $exceptionLouer) {
			$lesErreurs[] = $msg;
		}

		return $lesErreurs;
	}


	/**
	 * Fonction qui ajoute une possession à un utilisateur
	 * 
	 * @param string $mail Addresse mail de l'utilisateur
	 * @param date $debutPossession Date de début de possession de l'appartement de l'utilisateur
	 * @param int $immeuble Identifiant de l'immeuble de l'utilisateur
	 * @return array $lesErreurs l'ensemble des potentielles erreurs lors de l'insertion des données
	 */
	public function nouvellePossession($mail, $debutPossession, $immeuble)
	{
		$lesErreurs = array();
		$msg = "Une erreur s'est produite lors l'initialisation de votre docimicle, rendez-vous dans vous espace client.";

		$sql = "INSERT INTO posseder
		(mail   , idImmeuble, debutPossession) VALUES
		('$mail', $immeuble , '$debutPossession');";

		try {
			$req = PdoProjet3A::$monPdo->prepare($sql);
			if (!$req->execute()) {
				$lesErreurs[] = $msg;
			}
		} catch (PDOException $exceptionLouer) {
			$lesErreurs[] = $msg;
		}

		return $lesErreurs;
	}

	/**
	 * Fonction qui supprime l'utilisateur dont le mail est passé en paramètre
	 * 
	 * @param string $mailUtilisateur L'adresse mail de l'utilisateur à supprimer
	 * @return boolean $toReturn : true en cas de succès
	 */
	public function deleteUser($mailUtilisateur)
	{
		$sql = "DELETE FROM utilisateur WHERE mail = '$mailUtilisateur';";

		$toReturn = false;
		$statement = PdoProjet3A::$monPdo->prepare($sql);

		if ($statement->execute()) {
			$toReturn = true;
		}

		return $toReturn;
	}

	/**
	 * Fonction qui retourne les 25 premiers utilisateurs (par ordre alphabétique)
	 * 
	 * @return array $infosUtilisateurs Les enregistrements des utilisateurs
	 */
	public function get25Utilisateurs()
	{
		$infosUtilisateurs = array();
		$sql = "SELECT * FROM utilisateur NATURAL JOIN role ORDER BY nomUtilisateur, prenomUtilisateur, mail LIMIT 25";
		$req = PdoProjet3A::$monPdo->prepare($sql);
		$req->execute();

		if ($req) {
			$infosUtilisateurs = $req->fetchAll();
		}

		return $infosUtilisateurs;
	}

	/**
	 * Fonction qui retourne les 25 premiers utilisateurs (par ordre alphabétique)
	 * correspondant à la recherche passée en paramètre
	 * 
	 * @param string $recherche Recherche à effectuer
	 * @return array $infosUtilisateurs Les enregistrements des utilisateurs
	 */
	public function chercherUtilisateur($recherche)
	{
		$infosUtilisateurs = array();
		$recherche = explode(" ", $recherche);

		$sql = "SELECT * FROM utilisateur 
		NATURAL JOIN role
		WHERE ";
		$i = 0;

		foreach ($recherche as $elm) {
			if ($i == 0) {
				++$i;
			} else {
				$sql .= " AND ";
			}

			$sql .= "(
				UPPER(nomUtilisateur)	 		LIKE UPPER('%$elm%')
				OR UPPER(prenomUtilisateur) 	LIKE UPPER('%$elm%')
				OR UPPER(mail) 					LIKE UPPER('%$elm%')
				OR UPPER(libelleRole)			LIKE UPPER('%$elm%')
			)";
		}
		$sql .= " ORDER BY nomUtilisateur, prenomUtilisateur, mail LIMIT 25;";
		$req = PdoProjet3A::$monPdo->prepare($sql);
		$req->execute();

		if ($req) {
			$infosUtilisateurs = $req->fetchAll();
		}

		return $infosUtilisateurs;
	}

	/**
	 * Fonction qui retourne toutes les villes
	 */
	public function getVilles()
	{
		$infosVilles = array();

		if (PdoProjet3A::$monPdo) {

			$req = PdoProjet3A::$monPdo->prepare("SELECT * FROM ville");
			$req->execute();

			if ($req) {
				$infosVilles = $req->fetchAll();
			}
		}

		return $infosVilles;
	}


	/**
	 * Fonction qui recherche une/plusieurs villes dans la BDD
	 * 
	 * @param string $recherche La chaîne de caractères à chercher
	 * @return array Le résultat de la requête
	 */
	public function chercherVille($recherche)
	{
		$toReturn = 1;
		//ma super ville 95000
		$recherche = explode(" ", $recherche);

		if (PdoProjet3A::$monPdo) {
			$sql = "SELECT idVille, nomVille, cp, v.codeDep FROM ville v
			NATURAL JOIN departement 
			NATURAL JOIN region
			WHERE ";

			$i = 0;

			foreach ($recherche as $elm) {
				if ($i == 0) {
					++$i;
				} else {
					$sql .= " AND ";
				}

				$sql .= "(UPPER(cp)	 		LIKE UPPER('%$elm%')
				OR UPPER(nomVille) 			LIKE UPPER('%$elm%')
				OR UPPER(nomDepartement) 	LIKE UPPER('%$elm%')
				OR UPPER(nomRegion)			LIKE UPPER('%$elm%'))";
			}
			$sql .= ";";

			$req = PdoProjet3A::$monPdo->prepare($sql);
			$req->execute();

			if ($req) {
				$infosVilles = $req->fetchAll();
				$toReturn = $infosVilles;
			}
		}

		return $toReturn;
	}

	/**
	 * Fonction qui recherche un/plusieurs types d'appareil dans la BDD
	 * 
	 * @param string $recherche La chaîne de caractères à chercher
	 * @return array Le résultat de la requête
	 */
	public function chercherTypeAppareil($recherche)
	{
		$toReturn = 1;
		$recherche = explode(" ", $recherche);

		if (PdoProjet3A::$monPdo) {
			$sql = "SELECT * FROM typeappareil 
			WHERE ";

			$i = 0;

			foreach ($recherche as $elm) {
				if ($i == 0) {
					++$i;
				} else {
					$sql .= " AND ";
				}

				$sql .= "(
					UPPER(libelleTypeAppareil)	 		LIKE UPPER('%$elm%')
				)";
			}
			$sql .= ";";
			$req = PdoProjet3A::$monPdo->prepare($sql);
			$req->execute();

			if ($req) {
				$infosAppareil = $req->fetchAll();
				$toReturn = $infosAppareil;
			}
		}

		return $toReturn;
	}

	/**
	 * Fonction qui recherche une/plusieurs rues dans la ville passée en paramètre
	 * 
	 * @param int $ville Identifiant de la ville où il faut chercher la/les rues
	 * @param string $recherche La chaîne de caractères à chercher
	 * @return array Le résultat de la requête
	 */
	public function chercherRueDansVille($ville, $recherche)
	{
		$toReturn = 1;

		if (PdoProjet3A::$monPdo) {
			$sql = "SELECT idRue, nomRue FROM rue
			NATURAL JOIN ville
			WHERE idVille = $ville 
			AND	UPPER(nomRue) LIKE UPPER('%$recherche%');";

			$req = PdoProjet3A::$monPdo->prepare($sql);
			$req->execute();

			if ($req) {
				$infosVilles = $req->fetchAll();
				$toReturn = $infosVilles;
			}
		}

		return $toReturn;
	}


	/**
	 * Fonction qui recherche un/plusieurs immeubles dans la rue passée en paramètre
	 * 
	 * @param int $rue Identifiant de la rue où il faut chercher le/les immeubles
	 * @param string $recherche La chaîne de caractères à chercher
	 * @return array Le résultat de la requête
	 */
	public function chercherImmeubleDansRue($rue, $recherche)
	{
		$toReturn = 1;

		if (PdoProjet3A::$monPdo) {
			$sql = "SELECT idImmeuble, numeroImmeuble FROM immeuble
			NATURAL JOIN rue
			WHERE idRue = $rue 
			AND	numeroImmeuble LIKE '%$recherche%';";

			$req = PdoProjet3A::$monPdo->prepare($sql);
			$req->execute();

			if ($req) {
				$infosVilles = $req->fetchAll();
				$toReturn = $infosVilles;
			}
		}

		return $toReturn;
	}


	/**
	 * Fonction qui recherche un/plusieurs immeubles qui ne sont possédés par personne dans la rue passée en paramètre
	 * 
	 * @param int $rue Identifiant de la rue où il faut chercher le/les immeubles
	 * @param string $recherche La chaîne de caractères à chercher
	 * @return array Le résultat de la requête
	 */
	public function chercherImmeublesLibresDansRue($rue, $recherche)
	{
		$toReturn = 1;

		if (PdoProjet3A::$monPdo) {
			$sql = "SELECT idImmeuble, numeroImmeuble 
			FROM immeuble
			WHERE idRue = $rue
			AND	numeroImmeuble LIKE \"%$recherche%\"
			AND idImmeuble NOT IN (
				SELECT idImmeuble 
				FROM posseder
				WHERE finPossession IS NULL
			);";

			$req = PdoProjet3A::$monPdo->prepare($sql);
			$req->execute();

			if ($req) {
				$infosVilles = $req->fetchAll();
				$toReturn = $infosVilles;
			}
		}

		return $toReturn;
	}



	/**
	 * Fonction qui recherche un/plusieurs appartement qui ne sont loués par personne dans l'immeuble passé en paramètre
	 * 
	 * @param int $immeuble Identifiant de la'immeuble où il faut chercher le/les appartement
	 * @return array Le résultat de la requête
	 */
	public function listerAppartementsLibresDansImmeuble($immeuble)
	{
		$toReturn = 1;

		if (PdoProjet3A::$monPdo) {
			$sql = "SELECT idAppartement 
			FROM appartement
			WHERE idImmeuble = $immeuble
			AND idAppartement NOT IN (
				SELECT idAppartement 
				FROM louer
				WHERE finLocation IS NULL
			);";

			$req = PdoProjet3A::$monPdo->prepare($sql);
			$req->execute();

			if ($req) {
				$infosVilles = $req->fetchAll();
				$toReturn = $infosVilles;
			}
		}

		return $toReturn;
	}

	/**
	 * Fonction qui retourne l'ensemble des informations de l'utilisateur
	 * 
	 * @param string $mail L'adresse mail (identifiant) de l'utilisateur
	 * @return array Le résultat de la requète
	 */
	public function getUserInfos($mail)
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM utilisateur WHERE mail = '$mail'");

		if ($req) {
			$res = $req->fetch();
			$req->closeCursor();
		}
		return $res;
	}

	/**
	 * Fonction qui retourne tous les rôles utilisateur
	 */
	public function getRoles()
	{
		$infosRoles = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM role");

		if ($req) {
			$infosRoles = $req->fetchAll();
			$req->closeCursor();
		}
		return $infosRoles;
	}


	/**
	 * Fonction qui retourne l'ensemble des possession de l'utilisateur passé en paramètre
	 * 
	 * @param string $mail L'adresse mail (identifiant) de l'utilisateur
	 * @return array Le résultat de la requête
	 */
	public function getUserPossession($mail)
	{
		$res = array();

		$sql = "SELECT * FROM posseder
		NATURAL JOIN immeuble
		NATURAL JOIN rue
		NATURAL JOIN ville
		WHERE mail = '$mail' 
		AND finPossession IS NULL;";

		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}
		return $res;
	}


	/**
	 * Fonction qui retourne l'ensemble des locations de l'utilisateur passé en paramètre
	 * 
	 * @param string $mail L'adresse mail (identifiant) de l'utilisateur
	 * @return array Le résultat de la requête
	 */
	public function getUserLocInfos($mail)
	{
		$sql = "SELECT * FROM louer 
		NATURAL JOIN appartement
		NATURAL JOIN immeuble 
		NATURAL JOIN rue 
		NATURAL JOIN ville
		WHERE mail = '$mail' AND finLocation IS NULL";

		$res = array();
		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}
		return $res;
	}




	/**
	 * Fonction qui retourne l'ensemble des informations de l'immeuble passé en paramètre
	 * 
	 * @param int $idImmeuble Identifiant de l'immeuble
	 * @return array Le résultat de la requête
	 */
	public function getInfosImmeuble($idImmeuble)
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM immeuble WHERE idImmeuble = '$idImmeuble'");

		if ($req) {
			$res = $req->fetch();
			$req->closeCursor();
		}
		return $res;
	}


	/**
	 * Fonction qui retourne l'ensemble des informations de la rue passée en paramètre
	 * 
	 * @param int $rue Identifiant de la rue
	 * @return array Le résultat de la requête
	 */
	public function getRueInfos($rue)
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM rue WHERE idRue = '$rue'");

		if ($req) {
			$res = $req->fetch();
			$req->closeCursor();
		}
		return $res;
	}



	/**
	 * Fonction qui retourne l'ensemble des informations de la ville passée en paramètre
	 * 
	 * @param int $rue Identifiant de la ville
	 * @return array Le résultat de la requête
	 */
	public function getVilleInfos($ville)
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM ville WHERE idVille = '$ville'");

		if ($req) {
			$res = $req->fetch();
			$req->closeCursor();
		}
		return $res;
	}



	/**
	 * Fonction qui met à jour les informations d'un utilisateur
	 * 
	 * @param string $mail Adresse mail (identifiant) actuelle de l'utilisateur
	 * @param string $nouvMail Nouvelle adresse mail de l'utilisateur
	 * @param string $nom Nouveau nom de l'utilisateur
	 * @param date $dateNaiss Nouvelle date de naissance de l'utilisateur
	 * @param string $prenom Nouveau prénom de l'utilisateur
	 * @param string $mdp Nouveau mot de passe de l'utilisateur
	 * @param int $role Identifiant du nouveau rôle de l'utilisateur
	 * @return boolean Résultat de la requête (true si réussie, false sinon) 
	 */
	public function updateUserInfos($mail, $nouvMail, $nom, $dateNaiss, $prenom, $mdp, $role)
	{
		$succeeded = true;

		$sql = "UPDATE utilisateur
		SET nomUtilisateur 		= '$nom',
			prenomUtilisateur 	= '$prenom',
			dateNaissance		= '$dateNaiss',
			mail				= '$nouvMail'";

		if ($mdp != "") {
			$sql .= ",
			motDePasse			= '$mdp'";
		}

		if ($role != -1) {
			$sql .= ",
			idRole				= $role";
		}

		$sql .= "
		WHERE mail = '$mail';";

		$statement = PdoProjet3A::$monPdo->prepare($sql);

		$succeeded = $statement->execute();

		return $succeeded;
	}


	/**
	 * @param int $idRue Identifiant de la rue de l'appartement
	 * @param string $numImmeuble Numéro de l'immeuble à ajouter
	 * @return boolean Renvoie true si la requête à réussi, false sinon
	 */
	public function insertImmeuble($idRue, $numImmeuble)
	{
		$res = false;

		$sql = "INSERT INTO immeuble (numeroImmeuble, idRue) VALUES ($numImmeuble, $idRue);";

		$statement = PdoProjet3A::$monPdo->prepare($sql);

		if ($statement->execute()) {
			$res = true;
		}

		return $res;
	}


	/**
	 * @param int $idImmeuble Identifiant de l'immeuble de l'appartement
	 * @param int $degreSecurite Identifiant du degré de sécurité de l'appartement
	 * @param int $idTypeAppart Identifiant du type de l'appartement
	 * @return boolean Renvoie true si la requête à réussi, false sinon
	 */
	public function insertAppartement($idImmeuble, $idTypeAppart, $degreSecurite)
	{
		$sql = "INSERT INTO appartement (idImmeuble, idDegreSecurite, idTypeAppart) VALUES ($idImmeuble, $degreSecurite, $idTypeAppart);";

		$statement = PdoProjet3A::$monPdo->prepare($sql);

		$res = $statement->execute();

		return $res;
	}

	/**
	 * @param int $idImmeuble Identifiant de l'immeuble de l'appartement
	 * @param int $degreSecurite Identifiant du degré de sécurité de l'appartement
	 * @param int $idTypeAppart Identifiant du type de l'appartement
	 * @return boolean Renvoie true si la requête à réussi, false sinon
	 */
	public function insertPiece($idImmeuble, $idAppartement, $nomPiece, $idTypePiece)
	{
		$sql = "INSERT INTO piece (idImmeuble, idAppartement, nomPiece, idTypePiece) VALUES ($idImmeuble, $idAppartement, '$nomPiece', $idTypePiece);";

		$statement = PdoProjet3A::$monPdo->prepare($sql);

		$res = $statement->execute();

		return $res;
	}

	/**
	 * Fonction qui retourne l'ensemble des degré de sécurité
	 */
	public function getDegresSecurite()
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM degresecurite");

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}

		return $res;
	}


	/**
	 * Fonction qui retourne l'ensemble des type d'appartement
	 */
	public function getTypesAppartement()
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM typeappartement");

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}

		return $res;
	}

	/**
	 * Fonction qui retourne l'ensemble des appartements n'ayant pas de pièces
	 */
	public function getAppartsSansPiece(){
		$sql = "SELECT a.*
		FROM appartement a
		LEFT JOIN piece p
		ON  a.idImmeuble = p.idImmeuble
		AND a.idAppartement = p.idAppartement
		WHERE idPiece IS NULL;";

		$res = array();
		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}

		return $res;

	}


	/**
	 * Fonction qui retourne l'ensemble des type d'appartement
	 */
	public function getTypesPiece()
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM typepiece");

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}

		return $res;
	}


	/**
	 * Fonction qui retourne l'ensemble des immeubles
	 */
	public function getImmeubles()
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM immeuble");

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}

		return $res;
	}

	/**
	 * Fonction qui retourne l'ensemble des immeubles
	 */
	public function getApparts($immeuble)
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM appartement WHERE idImmeuble = $immeuble");

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}

		return $res;
	}


	/**
	 * Fonction qui récupère le nom et le type de toutes les pièces 'un appartement
	 * 
	 * @param int $immeuble L'identifiant de l'immeuble
	 * @param int $appartement L'identifiant de l'appartement
	 * 
	 * @return array le résultat de la recherche
	 */
	public function getPiecesAppart($immeuble, $appartement)
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM piece WHERE idAppartement = $appartement AND idImmeuble=$immeuble");

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}
		return $res;
	}

	/**
	 * Fonction qui récupère toutes le degré de sécurité et le type d'un appartement
	 * 
	 * @param int $immeuble L'identifiant de l'immeuble
	 * @param int $appartement L'identifiant de l'appartement
	 * 
	 * @return array le résultat de la recherche
	 * 
	 */
	public function getApptInfos($immeuble, $appartement)
	{
		$res = array();
		$req = PdoProjet3A::$monPdo->query("SELECT * FROM appartement WHERE idAppartement = $appartement AND idImmeuble=$immeuble;");

		if ($req) {
			$res = $req->fetch();
			$req->closeCursor();
		}
		return $res;
	}

	/**
	 * Fonction qui retourne l'ensemble des appareils d'une appartement
	 * 
	 * @param int $immeuble Identifiant de l'immeuble de l'appartement
	 * @param int $appartement Identifiant de l'appartement
	 * @return array Le résulat de la requête
	 */
	public function getAppareilsAppart($immeuble, $appartement)
	{
		$sql = "SELECT * 
		FROM appareil 
		NATURAL JOIN typeappareil 
		NATURAL JOIN piece 
		WHERE idAppartement = $appartement 
		AND idImmeuble=$immeuble
		ORDER BY idPiece, libelleAppareil ASC;";

		$res = array();
		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}
		return $res;
	}

	/**
	 * Fonction qui retourne l'ensemble des appareils d'une pièce d'un appartement
	 * 
	 * @param int $immeuble Identifiant de l'immeuble de l'appartement
	 * @param int $appartement Identifiant de l'appartement
	 * @param int $piece Identifiant de la pièce
	 * @return array Le résulat de la requête
	 */
	public function getAppareilsPiece($immeuble, $appartement, $piece)
	{
		$sql = "SELECT * 
		FROM appareil 
		NATURAL JOIN typeappareil 
		NATURAL JOIN piece 
		WHERE idAppartement = $appartement 
		AND idImmeuble = $immeuble
		AND idPiece = $piece
		ORDER BY libelleAppareil ASC;";

		$res = array();
		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}
		return $res;
	}

	/**
	 * Fonction qui récupère toute les informations d'un appareil
	 * 
	 * @param int $appareil L'identifiant de l'appareil
	 * 
	 * @return array Le résultat de la recherche
	 * 
	 */

	public function getConsoInfosAppareil($appareil)
	{
		$sql = "SELECT *
		FROM consommer
		NATURAL JOIN typeenergie
		NATURAL JOIN substance_energie
		WHERE idTypeAppareil = $appareil";

		$res = array();
		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}
		return $res;
	}
	/**
	 * Fonction qui récupere toute les informations de chaque appareil allumé d'un appartement
	 * 
	 * @param int $immeuble l'identifiant de l'immeuble
	 * @param int $appartement l'identifiant de l'appartement
	 * 
	 * @return array le résultat de la requète 
	 * 
	 */
	public function getConsoInfosAppartement($immeuble, $appartement)
	{
		$sql = ("SELECT *
		FROM appareil  
		NATURAL JOIN typeappareil
		NATURAL JOIN typeenergie 
		NATURAL JOIN consommer
		NATURAL JOIN substance_energie 
		WHERE idAppartement = $appartement
		AND idImmeuble=$immeuble
		AND etat = 1;");

		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}
		return $res;
	}

	/**
	 * Fonction qui récupère la consommation horaire de chaque appareil
	 * d'un appartement
	 * 
	 * @param int $idAppartement L'identifiant de l'appartement
	 * 
	 * @return array le resultat de la recherche
	 * 
	 */
	public function getConsoAppart($idAppartement)
	{
		$sql = "SELECT se.libelle, SUM(c.consommationHoraire) AS 'consommation' FROM appartement
		NATURAL JOIN piece
		NATURAL JOIN appareil
		NATURAL JOIN typeappareil
		NATURAL JOIN consommer c
		NATURAL JOIN typeenergie
		NATURAL JOIN substance_energie se
		WHERE etat = 1
		AND idAppartement = $idAppartement
		GROUP BY libelle;";

		$res = array();
		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			$res = $req->fetchAll();
			$req->closeCursor();
		}

		return $res;
	}

	/**
	 * Fonction qui vérifie si un immeuble appartient bien à un utilisateur
	 * 
	 * @param string $mailUtilisateur Mail de l'utilisateur
	 * @param int $idImmeuble Identifiant de l'immeuble
	 * @return boolean $toReturn : true si l'immeuble appartient à l'utilisateur, false sinon
	 */
	public function checkUserPossedeImmeuble($mailUtilisateur, $idImmeuble)
	{
		$sql = "SELECT * FROM posseder
		WHERE mail = '$mailUtilisateur'
		AND idImmeuble = $idImmeuble
		AND finPossession IS NULL;";

		$toReturn = false;
		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			if (!empty($req->fetch())) {
				$toReturn = true;
			}
		}

		return $toReturn;
	}

	/**
	 * Fonction qui modifie la date de fin de possession d'un immeuble 
	 * appartiennant à un utilisateur
	 * 
	 * @param string $mailUtilisateur Mail de l'utilisateur
	 * @param int $idImmeuble Identifiant de l'immeuble
	 * @return boolean $toReturn : true en cas de succès
	 */
	public function setFinPossession($mailUtilisateur, $idImmeuble)
	{
		$sql = "UPDATE posseder
		SET finPossession = CURRENT_DATE
		WHERE mail = '$mailUtilisateur'
		AND idImmeuble = $idImmeuble
		AND finPossession IS NULL;";

		$toReturn = false;
		$statement = PdoProjet3A::$monPdo->prepare($sql);

		if ($statement->execute()) {
			$toReturn = true;
		}

		return $toReturn;
	}


	/**
	 * Fonction qui vérifie si un appartement appartient bien à un utilisateur
	 * 
	 * @param string $mailUtilisateur Mail de l'utilisateur
	 * @param int $idImmeuble Identifiant de l'immeuble de l'appartement
	 * @param int $idAppartement Identifiant de l'appartement par rapport à l'immeuble
	 * @return boolean $toReturn : true si l'immeuble appartient à l'utilisateur, false sinon
	 */
	public function checkUserLocationAppartement($mailUtilisateur, $idImmeuble, $idAppartement)
	{
		$sql = "SELECT * FROM louer
		WHERE mail = '$mailUtilisateur'
		AND idImmeuble = $idImmeuble
		AND idAppartement = $idAppartement
		AND finLocation IS NULL;";

		$toReturn = false;
		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			if (!empty($req->fetch())) {
				$toReturn = true;
			}
		}

		return $toReturn;
	}

	/**
	 * Fonction qui modifie la date de fin de location d'un appartement d'un utilisateur
	 * 
	 * @param string $mailUtilisateur Mail de l'utilisateur
	 * @param int $idImmeuble Identifiant de l'immeuble de l'appartement
	 * @param int $idAppartement Identifiant de l'appartement par rapport à l'immeuble
	 * @return boolean $toReturn : true en cas de succès
	 */
	public function setFinLocation($mailUtilisateur, $idImmeuble, $idAppartement)
	{
		$sql = "UPDATE louer
		SET finLocation = CURRENT_DATE
		WHERE mail = '$mailUtilisateur'
		AND idImmeuble = $idImmeuble
		AND idAppartement = $idAppartement
		AND finLocation IS NULL;";

		$toReturn = false;
		$statement = PdoProjet3A::$monPdo->prepare($sql);

		$toReturn = $statement->execute();

		return $toReturn;
	}

	/**
	 * Fonction qui ajoute un appareil dans la BDD
	 * 
	 * @param string $libelleAppareil Libelle de l'appareil nom de l'appareil
	 * @param boolean $etat Etat de l'appareil (1=allumé, 0=éteint) allumé ou éteint
	 * @param int $idImmeuble Identifiant de l'immeuble de l'appartement de la pièce
	 * @param int $idAppartement Identifiant de l'appartement de la pièce
	 * @param int $idPiece Identifiant de la piece de l'appartement
	 * @param int $idTypeAppareil Identifiant du type d'appareil
	 * @param string $descriptionPosition Position de l'appareil dans la piece
	 * 
	 * @author Loann ^^
	 */
	public function insertAppareil($libelleAppareil, $etat, $idImmeuble, $idAppartement, $idPiece, $idTypeAppareil, $descriptionPosition)
	{
		$res = false;

		$sql = "INSERT INTO appareil 
			   (libelleAppareil,    etat,   descriptionPosition,  idImmeuble,   idAppartement,  idPiece,  idTypeAppareil) 
		VALUES ('$libelleAppareil', $etat, '$descriptionPosition', $idImmeuble, $idAppartement, $idPiece, $idTypeAppareil);";

		$statement = PdoProjet3A::$monPdo->prepare($sql);

		$res = $statement->execute();

		return $res;
	}

	/**
	 * Fonction qui insert un nouvel enregistrement dans la table `historiquefonctionnement`
	 * 
	 * @param int $idImmeuble L'identifiant de l'immeuble de l'appartement
	 * @param int $idAppartement L'identifiant de l'appartement
	 * @param int $idPiece L'identifiant de la pièce où se trouve l'appareil
	 * @param int $idAppareil L'identifiant de l'appareil
	 * @return boolean True si la requête à réussie, false sinon
	 */
	public function insertHistoriqueFonctionnement($idImmeuble, $idAppartement, $idPiece, $idAppareil)
	{
		$res = false;

		$sql = "INSERT INTO historiquefonctionnement 
			   (idImmeuble,  idAppartement,  idPiece,  idAppareil, debutFonctionnement)
		VALUES ($idImmeuble, $idAppartement, $idPiece, $idAppareil, NOW());";

		$statement = PdoProjet3A::$monPdo->prepare($sql);

		$res = $statement->execute();

		return $res;
	}


	/**
	 * Fonction qui change l'état d'un appareil
	 * 
	 * @param int $idImmeuble L'identifiant de l'immeuble de l'appartement
	 * @param int $idAppartement L'identifiant de l'appartement
	 * @param int $idPiece L'identifiant de la pièce où se trouve l'appareil
	 * @param int $idAppareil L'identifiant de l'appareil
	 * @param boolean $etat Le nouvel état de l'appareil (ture=allumé, false sinon)
	 * @return boolean True si la requête à réussie, false sinon
	 */
	public function updateEtatAppareil($idImmeuble, $idAppartement, $idPiece, $idAppareil, $etat)
	{
		$sql = "UPDATE appareil
		SET etat = $etat
		WHERE idImmeuble = $idImmeuble
		AND idAppartement = $idAppartement
		AND idPiece = $idPiece
		AND idAppareil = $idAppareil;";

		$statement = PdoProjet3A::$monPdo->prepare($sql);

		$res = $statement->execute();

		return $res;
	}

	/**
	 * Fonction qui supprime un appareil
	 * 
	 * @param int $idImmeuble L'identifiant de l'immeuble de l'appartement
	 * @param int $idAppartement L'identifiant de l'appartement
	 * @param int $idPiece L'identifiant de la pièce où se trouve l'appareil
	 * @param int $idAppareil L'identifiant de l'appareil
	 * @return boolean True si la requête à réussie, false sinon
	 */
	public function deleteAppareil($idImmeuble, $idAppartement, $idPiece, $idAppareil)
	{
		$sql = "DELETE FROM appareil
		WHERE idImmeuble = $idImmeuble
		AND idAppartement = $idAppartement
		AND idPiece = $idPiece
		AND idAppareil = $idAppareil;";

		$statement = PdoProjet3A::$monPdo->prepare($sql);

		$res = $statement->execute();

		return $res;
	}

	/**
	 * Fonction qui retourne les infosmations d'un appareil
	 * 
	 * @param int $idImmeuble L'identifiant de l'immeuble de l'appartement
	 * @param int $idAppartement L'identifiant de l'appartement
	 * @param int $idPiece L'identifiant de la pièce où se trouve l'appareil
	 * @param int $idAppareil L'identifiant de l'appareil
	 * @return array Le résultat de la requête
	 */
	public function getInfosAppareil($idImmeuble, $idAppartement, $idPiece, $idAppareil)
	{
		$sql = "SELECT *
		FROM appareil
		WHERE idImmeuble = $idImmeuble
		AND idAppartement = $idAppartement
		AND idPiece = $idPiece
		AND idAppareil = $idAppareil;";

		$res = array();
		$req = PdoProjet3A::$monPdo->query($sql);

		if ($req) {
			$res = $req->fetch();
			$req->closeCursor();
		}

		return $res;
	}


	/**
	 * Fonction qui ajoute un appareil dans la BDD
	 * 
	 * @param int $idImmeuble Identifiant de l'immeuble de l'appartement de la pièce
	 * @param int $idAppartement Identifiant de l'appartement de la pièce
	 * @param int $idPiece Identifiant de la piece de l'appartement
	 * @param int $idAppareil Identifiant de l'appareil à modifier
	 * @param string $libelleAppareil Libelle de l'appareil nom de l'appareil
	 * @param boolean $etat Etat de l'appareil (1=allumé, 0=éteint) allumé ou éteint
	 * @param int $idTypeAppareil Identifiant du type d'appareil
	 * @param string $descriptionPosition Position de l'appareil dans la piece
	 * 
	 * @author Loann ^^
	 */
	public function updateAppareil($idImmeuble, $idAppartement, $idPiece, $idAppareil, $libelleAppareil, $etat, $idTypeAppareil, $descriptionPosition)
	{
		$res = false;

		$sql = "UPDATE appareil 
		SET libelleAppareil = '$libelleAppareil',
		    etat = $etat,
			descriptionPosition = '$descriptionPosition',
			idTypeAppareil = $idTypeAppareil
		WHERE idImmeuble = $idImmeuble
		AND idAppartement = $idAppartement
		AND idPiece = $idPiece
		AND idAppareil = $idAppareil;";

		$statement = PdoProjet3A::$monPdo->prepare($sql);

		$res = $statement->execute();

		return $res;
	}
}
