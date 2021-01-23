<?php
session_start();
require_once("../util/config.php");
require_once("util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();

$messages = array();

$idImmeuble     = $_REQUEST['idImmeuble'];
$idAppartement  = $_REQUEST['idAppartement'];
$idPiece        = $_REQUEST['idPiece'];
$idAppareil     = $_REQUEST['idAppareil'];
$etat           = $_REQUEST['etat'];

if($pdo->checkUserLocationAppartement($_SESSION['user'], $idImmeuble, $idAppartement)){
    if($pdo->updateEtatAppareil($idImmeuble, $idAppartement, $idPiece, $idAppareil, $etat)){
        $etat = ($etat==1) ? "allumé" : "éteint";

        $message['success'] = "Votre appareil est maintenant enregistré comme ".$etat;
    } else {
        $message['erreurs'] = "Une erreur est survenue...";
    }
} else {
    $message['erreurs'] = "Vous ne louez pas cet appartement";
}

header('Content-Type: application/json');
echo  json_encode($message);