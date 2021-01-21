<?php
require_once("../util/config.php");
require_once("../util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();
$lesAppartements = array();
$lesAppartements = json_encode($pdo->listerAppartementsLibresDansImmeuble($_REQUEST['immeuble']));
header('Content-Type: application/json');
echo $lesAppartements;