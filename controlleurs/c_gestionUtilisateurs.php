<?php
if (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = "mes-infos";
}


$actionsImmeubles = array(
	'Mettre fin'
		=> array(
			'class' 	=>	'action fas fa-times red',
			'onclick'	=> 	'ajaxDateFin(this.closest(\'tr\'));',
			'title'	=>	'Déclarer mettre fin à la possession'
		)
);


switch ($action) {

	case 'connexion':

		//Entrée : l'utilisateur est déjà connecté
		if (isset($_SESSION['user'])) {
			unset($_SESSION['user']);

			if (isset($_SESSION['admin'])) {
				unset($_SESSION['admin']);
			}
			var_dump($_SESSION);
			$redirect	= HOME;
			$success[]	= "Vous êtes bien déconnectez !";
		}
		//Entrée : l'utilisateur vient d'envoyer des info de connexion via le formulaire
		else if (isset($_POST['mail']) && isset($_POST['mdp'])) {

			$resConnexion = $pdo->connexionUtilisateur($_POST['mail'], $_POST['mdp']);

			//Entrée : la fonction connexionUtilisateur n'a pas retournée d'erreur
			if (isset($_SESSION['user'])) {
				$redirect	= "?uc=$uc&action=mes-infos";
				$success[]	= "Vous êtes bien connectez !";
			}
			//Entrée : il y a eu une erreur lors de la connexion
			else {
				$erreurs 			= $resConnexion;
				$redirect			= "?uc=$uc&action=$action";
			}
		}
		//Entrée : l'utilisateur viens d'arriver sur la page de connexion
		else {

			//On initialise les champs du tableaux s'il le faut sinon on les copie juste
			$mail = '';
			include("vues/v_connexion.php");
		}
		break;

	case 'inscription':
		//Entrée : l'utilisateur est déjà connecté
		if (isset($_SESSION['user'])) {
			$messages[] = "Vous êtes connectez, pas besoin de vous inscrire.";
			$redirect 	= HOME;
		} else {

			//On initialise les champs du tableaux s'il le faut sinon on les copie juste
			if (isset($_SESSION['nom'])) {
				$nom = $_SESSION['nom'];
				unset($_SESSION['nom']);
			} else $nom = '';

			if (isset($_SESSION['prenom'])) {
				$prenom = $_SESSION['prenom'];
				unset($_SESSION['prenom']);
			} else $prenom = '';

			if (isset($_SESSION['dateNaiss'])) {
				$dateNaiss = $_SESSION['dateNaiss'];
				unset($_SESSION['dateNaiss']);
			} else $dateNaiss = '';

			if (isset($_SESSION['debutLocation'])) {
				$debutLocation = $_SESSION['debutLocation'];
				unset($_SESSION['debutLocation']);
			} else $debutLocation = '';

			if (isset($_SESSION['mail'])) {
				$mail = $_SESSION['mail'];
				unset($_SESSION['mail']);
			} else $mail = '';

			if (isset($_SESSION['rue'])) {
				$rue = $_SESSION['rue'];
				unset($_SESSION['rue']);
			} else $rue = '';

			if (isset($_SESSION['ville'])) {
				$ville = $_SESSION['ville'];
				unset($_SESSION['ville']);
			} else $ville = '';

			if (isset($_SESSION['cp'])) {
				$cp = $_SESSION['cp'];
				unset($_SESSION['cp']);
			} else $cp = '';

			include("vues/v_inscription.php");
			$javascript[] = HOME . 'script/inscription.js';
		}
		break;

	case 'confirmerInscription':

		//Entrée : l'utilisateur est connecté
		//	=> il ne doit pas avoir accès à cette page : redirection
		if (isset($_SESSION['user'])) {
			$messages[] = "Vous êtes connectez, pas besoin de créer un compte !";
			$redirect = HOME;
		} else if (isset($_POST['mail'])) {

			$erreurs = array();
			$setSessionValues = false;


			$nom 		 	= $_POST['nom'];
			$prenom 	 	= $_POST['prenom'];
			$dateNaiss		= date("Y-m-d", strtotime($_POST['dateNaiss']));
			$debutLocation 	= date("Y-m-d", strtotime($_POST['debutLocation']));
			$mdp 			= password_hash($_POST['mdp'], PASSWORD_DEFAULT);;
			$mail 		 	= $_POST['mail'];
			$ville 		 	= $_POST['ville'];
			$rue 		 	= $_POST['rue'];
			$immeuble 	 	= $_POST['immeuble'];
			$appartement 	= $_POST['appartement'];
			$situationUser	= $_POST['situationUser'];

			$nom = addslashes($nom);
			$prenom = addslashes($prenom);
			$mail = addslashes($mail);


			$erreurs = getErreursSaisieInscription($nom, $prenom, $mail, $mdp, $ville, $rue, $immeuble, $appartement, $situationUser);

			//Entrée : il n'y a pas eu d'erreurs de saisie
			if (empty($erreurs)) {

				//Entrée : il n'existe aucun compte avec l'adresse utilisée
				if (!$pdo->getUserInfos($mail)) {
					$erreurInsertionUser = $pdo->creerUser($mail, $mdp, $nom, $prenom, $dateNaiss);
					
					if (empty($erreurInsertionUser)) {
						$_SESSION['user'] = $mail;
						$erreursInsertionLocPoss = false;

						if (strcmp($situationUser, "locataire") == 0) {
							$erreursInsertionLocPoss = $pdo->nouvelleLocation($mail, $debutLocation, $immeuble, $appartement);
						} else {
							$erreursInsertionLocPoss = $pdo->nouvellePossession($mail, $debutLocation, $immeuble);
						}

						if(!empty($erreurInsertionUser)){
							$erreurs = array_merge($erreurs, $erreursInsertionLocPoss);
						}

						$redirect 	= "?uc=$uc&action=mes-infos";
						$messages[] = "Vous êtes bien inscris et connectez. A l'avenir votre identifiant sera : " . $_POST['mail'] . " !";
					} else {
						$setSessionValues = true;

						$erreurs 	= array_merge($erreurs, $erreurInsertionUser);
						$redirect 	= "?uc=$uc&action=inscription"; 
					}
				} else {
					$setSessionValues = true;

					$erreurs[] = 'Cette adresse mel est déjà utilisée...';
					$redirect = "?uc=$uc&action=inscription";
				}

			} else {
				$setSessionValues = true;

				$erreurs[] = "Les champs n'ont pas été correctement saisis";
				$redirect = "?uc=$uc&action=inscription";
			}

			if ($setSessionValues) {
				$_SESSION['nom'] 		 	= $nom;
				$_SESSION['prenom'] 	 	= $prenom;
				$_SESSION['dateNaiss']		= $dateNaiss;
				$_SESSION['debutLocation'] 	= $debutLocation;
				$_SESSION['mail'] 		 	= $mail;
				$_SESSION['ville'] 		 	= $ville;
				$_SESSION['rue'] 		 	= $rue;
			}
		} else{
			$messages[] = "Vous remplir le formulaire";
			$redirect = "?uc=$uc&action=inscription";
		}

		break;

	case "mes-infos":
		if(isset($_SESSION['user'])){
			include("vues/v_mesinfos.php");
			$javascript[] = HOME . 'script/actionsImmeubles.js';
		} else {
			$messages[] = "Vous devez être connectez pour accéder à cette page.";
			$redirect	= "?uc=$uc&action=connexion";
		}
		break;

	default:
		$redirect = "?uc=$uc&action=mes-infos";
		break;
}

if (isset($success)) {
	$_SESSION['success'] = $success;
}

if (isset($erreurs)) {
	$_SESSION['erreurs'] = $erreurs;
}

if (isset($messages)) {
	$_SESSION['messages'] = $messages;
}
