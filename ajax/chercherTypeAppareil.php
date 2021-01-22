<?php
require_once("../util/config.php");
require_once("../util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();
$lesAppareils = array();
$lesAppareils = json_encode($pdo->chercherTypeAppareil($_REQUEST['recherche']));
header('Content-Type: application/json');
echo $lesAppareils;