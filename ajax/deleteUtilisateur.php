<?php
session_start();
require_once("../util/config.php");
require_once("util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();

$messages = array();
$mailUtilisateur = $_REQUEST['mailUtilisateur'];


//Entrée : l'utilisateur est un administrateur
//      OU l'utilisateur essaie de se supprimer lui-même
if(isset($_SESSION['admin']) || (isset($_SESSION['user']) && strcmp($_SESSION['user'], $mailUtilisateur) == 0 ) ){
    if($pdo->deleteUser($mailUtilisateur)){
        
        //On supprime la session si l'utilisateur se supprime
        if(strcmp($mailUtilisateur, $_SESSION['user']) == 0){
            unset($_SESSION);
        }

        $message['success'] = "L'utilisateur $mailUtilisateur a bien été supprimé";
    } else {
        $message['erreurs'] = "Une erreur est survenue...";
    }
} else {
    $message['erreurs'] = "Vous n'avez pas le droit d'effectuer cette action'";
}

header('Content-Type: application/json');
echo  json_encode($message);