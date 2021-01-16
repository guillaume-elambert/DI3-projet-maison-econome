<?php
session_start();
require_once("../util/config.php");
require_once("../util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();

$messages = array();

if($pdo->checkUserPossedeImmeuble($_SESSION['user'],$_REQUEST['idImmeuble'])){
    if($pdo->setFinPossession($_SESSION['user'],$_REQUEST['idImmeuble'])){
        $message['success'] = "Vous n'êtes désormais plus propriétaire de cet immeuble.";
    } else {
        $message['erreurs'] = "Une erreur est survenue...";
    }
} else {
    $message['erreurs'] = "Vous ne possédez pas cet immeuble";
}

header('Content-Type: application/json');
echo  json_encode($message);