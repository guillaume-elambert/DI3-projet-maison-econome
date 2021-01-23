<?php
require_once("../util/config.php");
require_once("../util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();

$infosConso = array();

$idImmeuble = $_REQUEST['idImmeuble'];
$idAppartement = $_REQUEST['idAppartement'];

$infosConso = $pdo->getConsoInfosAppartement($idImmeuble, $idAppartement);

if (!empty($infosConso)) {
    $valeurTypeEnergie = array();
    $consommationTotale = 0;

    //Parcours des types d'énergie consommés par l'appareil
    foreach ($infosConso as $conso) {

        if (!isset($valeurTypeEnergie[$conso['libelle']])) {
            $valeurTypeEnergie[$conso['libelle']] = floatval($conso['consommationHoraire']);
        } else {
            $valeurTypeEnergie[$conso['libelle']] += floatval($conso['consommationHoraire']);
        }
        $consommationTotale += floatval($conso['consommationHoraire']);
    }
    if (!empty($valeurTypeEnergie)) {
        $valeurTypeEnergie['Total'] = $consommationTotale;
    } else {
        $messageTable = "Un problème est survenu...";
    }
} else {
    $messageTable = "Aucun appareil dans cet appartement...<br/>
    <a href='" . HOME . "?uc=espace&action=ajouter-un-appareil&immeuble=$idImmeuble&appartement=$idAppartement'>Ajouter un appareil à l'appartement</a>";
}

require_once("util/configActionsTables.inc.php");
include("vues/v_tableConsommationAppart.php");
