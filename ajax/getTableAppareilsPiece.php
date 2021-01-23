<?php
require_once("../util/config.php");
require_once("util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();

$appareils = array();

$idImmeuble = $_REQUEST['idImmeuble'];
$idAppartement = $_REQUEST['idAppartement'];
$idPiece = $_REQUEST['idPiece'];

$appareils = $pdo->getAppareilsPiece($idImmeuble, $idAppartement, $idPiece);

$printIfEmpty = "Il n'y a aucun appareil dans cette pièce...<br/>
<a href='" . HOME . "?uc=espace&action=ajouter-un-appareil&immeuble=$idImmeuble&appartement=$idAppartement'>Ajouter un appareil à l'appartement</a>";

require_once("util/configActionsTables.inc.php");
include("vues/v_tableAppareils.php");