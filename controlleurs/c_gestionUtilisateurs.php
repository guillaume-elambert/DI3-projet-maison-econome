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
			$success[]	= "Vous êtes bien déconnecté !";
		}
		//Entrée : l'utilisateur vient d'envoyer des info de connexion via le formulaire
		else if (isset($_POST['mail']) && isset($_POST['mdp'])) {

			$resConnexion = $pdo->connexionUtilisateur($_POST['mail'], $_POST['mdp']);

			//Entrée : la fonction connexionUtilisateur n'a pas retournée d'erreur
			if (isset($_SESSION['user'])) {
				$redirect	= "?uc=espace&action=dashboard";
				$success[]	= "Vous êtes bien connecté !";
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
			$messages[] = "Vous êtes connecté, pas besoin de vous inscrire.";
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

			include("vues/v_inscription.php");
			$javascript[] = HOME . 'script/formLocPoss.js';
		}
		break;

	case 'confirmerInscription':

		//Entrée : l'utilisateur est connecté
		//	=> il ne doit pas avoir accès à cette page : redirection
		if (isset($_SESSION['user'])) {
			$messages[] = "Vous êtes connecté, pas besoin de créer un compte !";
			$redirect = HOME;
		} else if (isset($_POST['mail'])) {

			$erreurs = array();
			$setSessionValues = false;


			$nom 		 	= $_POST['nom'];
			$prenom 	 	= $_POST['prenom'];
			$dateNaiss		= date("Y-m-d", strtotime($_POST['dateNaiss']));
			$debutLocation 	= date("Y-m-d", strtotime($_POST['debutLocation']));
			$mdp 			= $_POST['mdp'];
			$verifMdp		= $_POST['verifMdp'];
			$mail 		 	= $_POST['mail'];
			$ville 		 	= $_POST['ville'];
			$rue 		 	= $_POST['rue'];
			$immeuble 	 	= $_POST['immeuble'];
			$appartement 	= $_POST['appartement'];
			$situationUser	= $_POST['situationUser'];

			$nom = addslashes($nom);
			$prenom = addslashes($prenom);
			$mail = addslashes($mail);


			$erreurs = getErreursSaisieInscription($nom, $prenom, $mail, $mdp, $verifMdp, $ville, $rue, $immeuble, $appartement, $situationUser, $debutLocation);

			//Entrée : il n'y a pas eu d'erreurs de saisie
			if (empty($erreurs)) {

				//Entrée : il n'existe aucun compte avec l'adresse utilisée
				if (!$pdo->getUserInfos($mail)) {
					$mdp = password_hash($mdp, PASSWORD_DEFAULT);

					$erreurInsertionUser = $pdo->creerUser($mail, $mdp, $nom, $prenom, $dateNaiss);

					if (empty($erreurInsertionUser)) {
						$_SESSION['user'] = $mail;
						$erreursInsertionLocPoss = false;

						if (strcmp($situationUser, "locataire") == 0) {
							$erreursInsertionLocPoss = $pdo->nouvelleLocation($mail, $debutLocation, $immeuble, $appartement);
						} else {
							$erreursInsertionLocPoss = $pdo->nouvellePossession($mail, $debutLocation, $immeuble);
						}

						if (!empty($erreurInsertionUser)) {
							$erreurs = array_merge($erreurs, $erreursInsertionLocPoss);
						}

						$redirect 	= "?uc=espace&action=dashboard";
						$messages[] = "Vous êtes bien inscrit et connecté. A l'avenir votre identifiant sera : " . $_POST['mail'] . " !";
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
		} else {
			$messages[] = "Vous devez remplir le formulaire";
			$redirect = "?uc=$uc&action=inscription";
		}

		break;

	default:
		$redirect = "?uc=$uc&action=connexion";
		break;
}