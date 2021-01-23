<?php
session_start();
require_once("../util/config.php");
require_once("util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();

$messages = array();

if($pdo->checkUserLocationAppartement($_SESSION['user'],$_REQUEST['idImmeuble'], $_REQUEST['idAppartement'])){
    if($pdo->setFinLocation($_SESSION['user'],$_REQUEST['idImmeuble'], $_REQUEST['idAppartement'])){
        $message['success'] = "Vous n'êtes désormais plus locataire de cet appartement.";
    } else {
        $message['erreurs'] = "Une erreur est survenue...";
    }
} else {
    $message['erreurs'] = "Vous ne louez pas cet appartement";
}

header('Content-Type: application/json');
echo  json_encode($message);