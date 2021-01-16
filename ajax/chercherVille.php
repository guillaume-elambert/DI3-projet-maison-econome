<?php
require_once("../util/config.php");
require_once("../util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();
$lesVilles = array();
$lesVilles = json_encode($pdo->chercherVille($_REQUEST['recherche']));
header('Content-Type: application/json');
echo $lesVilles;