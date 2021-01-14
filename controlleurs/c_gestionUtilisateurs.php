<?php
if (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = "connexion";
}

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
				$redirect	= HOME;
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
		$erreurs = array();
		if (isset($_SESSION['user'])) {
			$messages[] = "Vous êtes connectez, pas besoin de créer un compte !";
		} else {
			if (isset($_POST['nom'])) {
				$nom 		 	= $_POST['nom'];
				$prenom 	 	= $_POST['prenom'];
				$dateNaiss		= date("Y-m-d", strtotime($_POST['dateNaiss']));
				$debutLocation 	= date("Y-m-d", strtotime($_POST['debutLocation']));
				$mdp 		 	= $_POST['mdp'];
				$mail 		 	= $_POST['mail'];
				$ville 		 	= $_POST['ville'];
				$rue 		 	= $_POST['rue'];
				$immeuble 	 	= $_POST['immeuble'];
				$appartement 	= $_POST['appartement'];

				$nom = addslashes($nom);
				$prenom = addslashes($prenom);
				$mail = addslashes($mail);
			}

			$erreurs = getErreursSaisieInscription($nom, $prenom, $mail, $mdp);

			//Entrée : il n'y a pas eu d'erreurs de saisie
			if (empty($erreurs)) {

				//Entrée : il n'existe aucun compte avec l'adresse utilisée
				if (!$pdo->getUserInfos($mail)) {
					if ($pdo->creerUser($mail, $mdp, $nom, $prenom, $dateNaiss, $debutLocation, $immeuble, $appartement)) {
						$_SESSION['user'] = $mail;

						$messages[] = "Vous êtes bien inscris et connectez. A l'avenir votre identifiant sera : " . $_POST['mail'] . " !";
						$redirect 	= HOME;
					}
				} else {
					$_SESSION['nom'] 		 	= $nom;
					$_SESSION['prenom'] 	 	= $prenom;
					$_SESSION['dateNaiss']		= $dateNaiss;
					$_SESSION['debutLocation'] 	= $debutLocation;
					$_SESSION['mdp'] 		 	= $mdp;
					$_SESSION['mail'] 		 	= $mail;
					$_SESSION['ville'] 		 	= $ville;
					$_SESSION['rue'] 		 	= $rue;
					$_SESSION['immeuble'] 	 	= $immeuble;
					$_SESSION['appartement'] 	= $appartement;
					
					$erreurs[] = 'Cette adresse mel est déjà utilisée...';
					$redirect = "?uc=$uc&action=inscription";
				}
			} else {
				$_SESSION['nom'] 		 	= $nom;
				$_SESSION['prenom'] 	 	= $prenom;
				$_SESSION['dateNaiss']		= $dateNaiss;
				$_SESSION['debutLocation'] 	= $debutLocation;
				$_SESSION['mdp'] 		 	= $mdp;
				$_SESSION['mail'] 		 	= $mail;
				$_SESSION['ville'] 		 	= $ville;
				$_SESSION['rue'] 		 	= $rue;
				$_SESSION['immeuble'] 	 	= $immeuble;
				$_SESSION['appartement'] 	= $appartement;

				$erreurs[] = "Les champs n'ont pas été correctement saisis";
				$redirect = "?uc=$uc&action=inscription";
			}
		}
		break;

	case "mes-infos":
		include("vues/v_mesinfos.php");
		break;

	default:
		$redirect = "?uc=$uc&action=connexion";
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
