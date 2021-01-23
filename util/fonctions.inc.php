<?php

/**
 * fichier qui contient les fonctions qui ne font pas accès aux données de la BD
 *
 * regroupe les fonctions pour gérer le panier, et les erreurs de saisie dans le formulaire de commande
 *
 * @package  projet-bdd-3a\util
 * @version 2019_v2
 *
 */


/**
 * Teste si une chaîne a le format d'un mail
 *
 * Utilise les expressions régulières
 *
 * @param string $mail la chaîne testée
 * @return boolean $ok vrai ou faux
 */
function estUnMail($mail)
{
	return preg_match('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#', $mail);
}


/**
 * Retourne un tableau d'erreurs de saisie pour la création d'un nouvel utilisateur
 *
 * @param string $nom Nom de l'utilisateur
 * @param string $prenom Prenom de l'utilisateur
 * @param string $mail Mail de l'utilisateur
 * @param string $mdp Mot de passe de l'utilisateur
 * @param string $verifMdp Confirmation du mot de passe de l'utilisateur
 * @param int $ville Identifiant de la ville
 * @param int $rue Identifiant de la rue
 * @param int $immeule Identifiant de l'immeuble
 * @param int $appartement Identifiant de l'appartement
 * @param string $situationUser "locataire" ou "proprietaire"
 * @param date $debutLocation Date de début de la location/possession
 * @return array $lesErreurs un tableau de chaînes d'erreurs
 */
function getErreursSaisieInscription($nom, $prenom, $mail, $mdp, $verifMdp, $ville, $rue, $immeuble, $appartement, $situationUser, $debutLocation)
{
	$lesErreurs = array();
	if ($nom == "") {
		$lesErreurs[] = "Il faut saisir le champ nom";
	}
	if ($prenom == "") {
		$lesErreurs[] = "Il faut saisir le champ prénom";
	}
	if ($mail == "") {
		$lesErreurs[] = "Il faut saisir le champ mail";
	}
	if (!estUnMail($mail)) {
		$lesErreurs[] =  "Erreur de mail";
	}


	$mdpEmpty = false;
	if ($mdp == "") {
		$mdpEmpty = true;
	}

	$verifMdpEmpty = false;
	if ($verifMdp == "") {
		$verifMdpEmpty = true;
	}

	//Entrée : le champ du mot de passe est saisie mais pas celui de la verification du mot de passe
	//		OU le champ du mot de pas n'est pas saisie mais celui de la vérification du mot de passe l'est
	if ((!$mdpEmpty &&  $verifMdpEmpty)
		||	($mdpEmpty && !$verifMdpEmpty)
	) {
		$lesErreurs[] = "Il faut saisir votre mot de passe 2 fois.";
	} else if (strcmp($mdp, $verifMdp) != 0) {
		$lesErreurs[] = "Les champs du mot de passe ne correspondent pas.";
	}


	$lesErreurs = array_merge($lesErreurs, getErreursSaisieAjoutLocPoss($ville, $rue, $immeuble, $appartement, $situationUser, $debutLocation));

	return $lesErreurs;
}


/**
 * Retourne un tableau d'erreurs de saisie pour la création d'un nouvel utilisateur
 *
 * @param int $ville Identifiant de la ville
 * @param int $rue Identifiant de la rue
 * @param int $immeule Identifiant de l'immeuble
 * @param int $appartement Identifiant de l'appartement
 * @param string $situationUser "locataire" ou "proprietaire"
 * @param date $debutLocation Date de début de la location/possession
 * @return array $lesErreurs un tableau de chaînes d'erreurs
 */
function getErreursSaisieAjoutLocPoss($ville, $rue, $immeuble, $appartement, $situationUser, $debutLocation)
{
	$lesErreurs = array();
	if ($ville == "") {
		$lesErreurs[] = "Il faut séléctionner une ville";
	}
	if ($rue == "") {
		$lesErreurs[] = "Il faut séléctionner une rue";
	}
	if ($immeuble == "") {
		$lesErreurs[] = "Il faut séléctionner un immeuble";
	}
	if ($debutLocation == "") {
		$lesErreurs[] = "Il faut saire une date de début de location/possession";
	}

	//Entrée : l'utilisateur n'a pas présisé s'il était propriétaire ou location
	//		OU la valeur n'a rien à voir avec ce qui était prévu
	if ($situationUser == "" || (strcmp($situationUser, "locataire") != 0 && strcmp($situationUser, "proprietaire") != 0)) {
		$lesErreurs[] = "Il faut spécifier si vous êtes propriétaire ou locataire ";
	}
	//Entrée : L'utilisateur est locataire mais n'a pas spécifié d'appartement
	else if (strcmp($situationUser, "locataire") == 0 && $appartement == "") {
		$lesErreurs[] = "Il faut séléctionner un appartement";
	}
	return $lesErreurs;
}


/**
 * Retourne un tableau d'erreurs de saisie pour la création d'un nouvel utilisateur
 *
 * @param string $nom Nouveau nom de l'utilisateur
 * @param string $prenom Nouveau prenom de l'utilisateur
 * @param string $mdp Nouveau mot de passe de l'utilisateur
 * @param string $verifMdp Confirmation du nouveau mot de passe de l'utilisateur
 * @param int $roel Identifiant du rôle de l'utilisateur
 * @return array $lesErreurs un tableau de chaînes d'erreurs
 */
function getErreursSaisieModifInfos($mail, $nom, $prenom, $dateNaiss, $mdp, $verifMdp, $role)
{
	$lesErreurs = array();

	if (!estUnMail($mail)) {
		$lesErreurs[] =  "Erreur de mail";
	}

	if ($nom == "") {
		$lesErreurs[] = "Il faut saisir votre nom.";
	}
	if ($prenom == "") {
		$lesErreurs[] = "Il faut saisir votre prénom.";
	}
	if ($dateNaiss == "") {
		$lesErreurs[] = "Vous devez saisir votre date de naissance.";
	}

	$mdpEmpty = false;
	if ($mdp == "") {
		$mdpEmpty = true;
	}

	$verifMdpEmpty = false;
	if ($verifMdp == "") {
		$verifMdpEmpty = true;
	}

	//Entrée : le champ du mot de passe est saisie mais pas celui de la verification du mot de passe
	//		OU le champ du mot de pas n'est pas saisie mais celui de la vérification du mot de passe l'est
	if ((!$mdpEmpty &&  $verifMdpEmpty)
		||	($mdpEmpty && !$verifMdpEmpty)
	) {
		$lesErreurs[] = "Il faut saisir votre mot de passe 2 fois.";
	} else if (strcmp($mdp, $verifMdp) != 0) {
		$lesErreurs[] = "Les champs du mot de passe ne correspondent pas.";
	}

	if ($role == "") {
		$lesErreurs[] = "Il faut selectionner le rôle de l'utilisateur";
	}

	return $lesErreurs;
}


/**
 * Retourne un tableau d'erreurs de saisie pour la création d'un nouvel immeuble
 *
 * @param int $rue Identifiant de la rue de l'immeuble
 * @param string $immeuble Numéro de l'immeuble à ajouter
 * @return array $lesErreurs un tableau de chaînes d'erreurs
 */
function getErreursSaisieAjoutImmeuble($rue, $immeuble)
{
	$lesErreurs = array();

	if ($rue == "") {
		$lesErreurs[] = "Il faut séléctionner la rue.";
	}
	if ($immeuble == "") {
		$lesErreurs[] = "Il faut saisir le numéro de l'immeuble.";
	}

	return $lesErreurs;
}


/**
 * Retourne un tableau d'erreurs de saisie pour la création d'un nouvel appartement
 *
 * @param int $immeuble Identifiant de l'immeuble de l'appartement
 * @return array $lesErreurs un tableau de chaînes d'erreurs
 */
function getErreursSaisieAjoutAppartement($immeuble, $typeAppart, $degreSecurite)
{
	$lesErreurs = array();

	if ($immeuble == "") {
		$lesErreurs[] = "Il faut séléctionner l'immeuble'.";
	}
	if ($typeAppart == "") {
		$lesErreurs[] = "Il faut séléctionner le type de l'appartement.";
	}
	if ($degreSecurite == "") {
		$lesErreurs[] = "Il faut séléctionner le degré de sécurité de l'appartement.";
	}

	return $lesErreurs;
}



/**
 * Fonction qui creer une image dans le fichier images/
 *
 * @return boolean $exec Résultat de l'execution
 */
function creerImage()
{

	$exec = false;

	$fichier = $_FILES['image'];
	$emplacementFichier = "images/" . basename($fichier["name"]);
	$extensionFichier = strtolower(pathinfo($fichier["name"], PATHINFO_EXTENSION));

	$listeExtAccpetees = array('png', 'jpg', 'jpeg', 'gif');

	//Vérifie si le fichier est une image
	if (in_array($extensionFichier, $listeExtAccpetees)) {
		if (getimagesize($fichier["tmp_name"])) {
			if (move_uploaded_file($fichier["tmp_name"], $emplacementFichier)) {
				$exec = true;
			}
		}
	}

	return $exec;
}


/**
 * Retourne un tableau d'erreurs de saisie pour la création d'un nouvel appareil
 *
 * @param string $libelleAppareil nom de l'appareil
 * @param int $idTypeAppareil Identifiant du type de l'appareil
 * @param int $idPiece Identifiant de la pièce
 * @param string $descriptionPosition description de la position de l'objet dans la pièce
 * @param bool $etat si l'appareil est allumé ou non 
 * @param int $idImmeuble identifiant de l'immeuble
 * @param int $idAppartement identifiant de l'appartement
 * @return array $lesErreurs un tableau de chaînes d'erreurs
 */
function getErreursSaisieAjoutAppareil($libelleAppareil, $idTypeAppareil, $idPiece, $descriptionPosition, $etat, $idImmeuble, $idAppartement)
{
	$lesErreurs = array();
	if ($libelleAppareil == "") {
		$lesErreurs[] = "Il faut entrer un nom pour votre appareil";
	}
	if ($idTypeAppareil == "") {
		$lesErreurs[] = "Il faut sélectionner un type à votre appareil";
	}
	if ($idPiece == "") {
		$lesErreurs[] = "Il faut sélectionner une pièce de votre appartement";
	}
	if ($descriptionPosition == "") {
		$lesErreurs[] = "Il faut saisir une position pour votre appareil";
	}
	//l'état doit être égal à 1 ou 0
	if ($etat != 1 && $etat != 0) {
		$lesErreurs[] = "Il faut sélectionner un état pour votre appareil";
	}
	if ($idImmeuble == "") {
		$lesErreurs[] = "L'idImmeuble est vide";
	}
	if ($idAppartement == "") {
		$lesErreurs[] = "L'idAppartement est vide";
	}
	return $lesErreurs;
}


/**
 * Retourne un tableau d'erreurs de saisie pour la modification d'un appareil
 *
 * @param string $libelleAppareil nom de l'appareil
 * @param int $idTypeAppareil Identifiant de l'appareil
 * @param int $idPiece Identifiant de la pièce
 * @param string $descriptionPosition description de la position de l'objet dans la pièce
 * @param bool $etat si l'appareil est allumé ou non 
 * @param int $idImmeuble identifiant de l'immeuble
 * @param int $idAppartement identifiant de l'appartement
 * @param int $idAppareil L'identifiant de l'appareil à modififer
 * @return array $lesErreurs un tableau de chaînes d'erreurs
 */
function getErreursSaisieModifAppareil($libelleAppareil, $idTypeAppareil, $idPiece, $descriptionPosition, $etat, $idImmeuble, $idAppartement, $idAppareil)
{
	$lesErreurs = getErreursSaisieAjoutAppareil($libelleAppareil, $idTypeAppareil, $idPiece, $descriptionPosition, $etat, $idImmeuble, $idAppartement);

	if ($idAppareil == "") {
		$lesErreurs[] = "Il faut séléctionner un appareil";
	}

	return $lesErreurs;
}

/**
 * Fonction qui ajoute des pièces pour chaque appartement n'ayant pas de pieces
 * 
 * @param PdoProjet3A $pdo Le pdo vers la BDD
 */
function insertPiecesAppartSansPiece($pdo)
{

	$typeAppart = $pdo->getTypesAppartement();
	$typesPiece = $pdo->getTypesPiece();


	$apparts = $pdo->getAppartsSansPiece();

	//Parcours de tous les appartements n'ayant pas de piece
	foreach ($apparts as $unAppart) {
		//On récupère le nombre de pièces du type de l'appartement
		$nbPieces = $typeAppart[$unAppart['idTypeAppart']-1]['nbMinPieces'];
		
		//On ajoute autant de piece que le nombre de pièces du type de l'appartement
		for ($i = 0; $i < $nbPieces; ++$i) {
			//On récupère un type de pièce aléatoirement
			$leType = $typesPiece[rand(0, sizeof($typesPiece) - 1)];
			$pdo->insertPiece($unAppart['idImmeuble'], $unAppart['idAppartement'], $leType['libelleTypePiece'], $leType['idTypePiece']);
		}
	}
}
