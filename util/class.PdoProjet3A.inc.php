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
	 * destructeur
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
			$req = PdoProjet3A::$monPdo->prepare("SELECT * FROM utilisateur WHERE mail='$mail';");
			$req->execute();
			$lesErreurs = array();

			if ($req) {

				$userInfo = $req->fetch();

				//L'utilisateur existe
				if ($userInfo) {
					$mdpBDD = $userInfo['motDePasse'];
					$role = $userInfo['idRole'];

					//Le mot de passe correspond au mot de passe haché dans la BDD
					if (password_verify($mdp, $mdpBDD)) {
						if ($role == 1) {
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
	 * @param date $debutLocation Date de début de location de l'appartement du nouvel utilisateur
	 * @param int $immeuble Identifiant de l'immeuble du nouvel utilisateur
	 * @param int $appartement Identifiant de l'appartement du nouvel utilisateur (relatif à l'immeuble)
	 */
	public function creerUser($mail, $mdp, $nom, $prenom, $dateNaiss, $debutLocation, $immeuble, $appartement)
	{
		$lesErreurs = array();
		$mdp = password_hash($mdp, PASSWORD_DEFAULT);

		if (PdoProjet3A::$monPdo) {
			$sql = "INSERT INTO utilisateur 
			(mail    , nomUtilisateur, prenomUtilisateur, dateNaissance, motDePasse, dateCreation, etat, idRole) VALUES
			('$mail' , '$nom'		 , '$prenom'		, '$dateNaiss' , '$mdp'    , CURRENT_DATE, 1   , 2     );";


			try {
				$req = PdoProjet3A::$monPdo->prepare($sql);
				$req->execute();

				$sql = "INSERT INTO louer
				(mail   , idImmeuble, idAppartement, debutLocation) VALUES
				('$mail', $immeuble, $appartement , '$debutLocation');";

				try {
					$req = PdoProjet3A::$monPdo->prepare($sql);
					$req->execute();
				} catch (PDOException $exceptionLouer) {
					$lesErreurs[] = "Une erreur s'est produite lors l'initialisation de votre docimicle, rendez-vous dans vous espace client.";
				}
			} catch (PDOException $exceptionUtilisateur) {
				$lesErreurs[] = "Une erreur s'est produite lors de la création de votre compte...";
			}
		}

		return $lesErreurs;
	}


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

	public function chercherVille($recherche)
	{
		$toReturn = 1;

		if (PdoProjet3A::$monPdo) {
			$sql = "SELECT * FROM ville
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

	public function listerAppartementsDansImmeuble($immeuble)
	{
		$toReturn = 1;

		if (PdoProjet3A::$monPdo) {
			$sql = "SELECT idAppartement FROM appartement
			NATURAL JOIN immeuble
			WHERE idImmeuble = $immeuble;";

			$req = PdoProjet3A::$monPdo->prepare($sql);
			$req->execute();

			if ($req) {
				$infosVilles = $req->fetchAll();
				$toReturn = $infosVilles;
			}
		}

		return $toReturn;
	}


	public function insertRueVille()
	{
		$infosVilles = PdoProjet3A::$monPdoProjet3A->getVilles();
		$sql = "INSERT INTO rue (`nomRue`,`idVille`) VALUES";
		$i = 0;
		foreach ($infosVilles as $ville) {
			if ($i != 0) {
				$sql .= ", ";
			} else {
				++$i;
			}
			$sql .= ' ("Une super rue de ' . $ville['nomVille'] . '","' . $ville['idVille'] . '")';
		}
		$sql .= ";";
		
		PdoProjet3A::$monPdo->prepare($sql)->execute();
	}

	public function insertRootUser()
	{
		$sql = "INSERT INTO utilisateur (`mail`,`motDePasse`, `idRole`, `dateCreation`) VALUES ('root@root.fr','" . password_hash("root", PASSWORD_DEFAULT) . "', 1, CURRENT_DATE);";
		PdoProjet3A::$monPdo->prepare($sql)->execute();
	}

	public function test()
	{
		$reponse = PdoProjet3A::$monPdo->query('SELECT * FROM ville');

		$donnees = $reponse->fetchAll();
		$reponse->closeCursor(); // Termine le traitement de la requête
		return $donnees;
	}


	public function getUserInfos($mail)
	{
		$userInfo = PdoProjet3A::$monPdo->query("SELECT * FROM utilisateur WHERE mail = '$mail'");

		$donneesUser = $userInfo->fetch();
		$userInfo->closeCursor();
		return $donneesUser;
	}
}
