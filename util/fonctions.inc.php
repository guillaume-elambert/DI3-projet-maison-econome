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
function estUnMail($mail){
	return preg_match ('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#', $mail);
}


/**
 * Retourne un tableau d'erreurs de saisie pour la création d'un nouvel utilisateur
 *
 * @param string $nom Nom de l'utilisateur
 * @param string $prenom Prenom de l'utilisateur
 * @param string $mail Mail de l'utilisateur
 * @param string $mdp Mot de passe de l'utilisateur
 * @return array $lesErreurs un tableau de chaînes d'erreurs
*/
function getErreursSaisieInscription($nom, $prenom, $mail, $mdp){
	$lesErreurs = array();
	if($nom == "")
	{
		$lesErreurs[] = "Il faut saisir le champ nom";
	}
	if($prenom == "")
	{
		$lesErreurs[] = "Il faut saisir le champ prénom";
	}
	if($mdp == "")
	{
		$lesErreurs[] = "Il faut saisir le champ mot de passe";
	}
	if($mail == "")
	{
		$lesErreurs[] = "Il faut saisir le champ mail";
	}
	else
	{
		if(!estUnMail($mail))
		{
			$lesErreurs[] =  "erreur de mail";
		}
	}
	return $lesErreurs;
}

/**
* Fonction qui creer une image dans le fichier images/
*
* @return boolean $exec Résultat de l'execution
*/
function creerImage(){
	
	$exec = false;

	$fichier = $_FILES['image'];
	$emplacementFichier = "images/" . basename($fichier["name"]);
	$extensionFichier = strtolower(pathinfo($fichier["name"],PATHINFO_EXTENSION));
	
	$listeExtAccpetees = array('png','jpg','jpeg','gif');
	
	//Vérifie si le fichier est une image
    if(in_array($extensionFichier, $listeExtAccpetees)){
    	if(getimagesize($fichier["tmp_name"])) {
			if(move_uploaded_file($fichier["tmp_name"], $emplacementFichier)){
				$exec = true;
			}
	    }
	}

    return $exec;
}
