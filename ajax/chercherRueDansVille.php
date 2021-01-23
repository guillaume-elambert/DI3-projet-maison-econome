<?php
require_once("../util/config.php");
require_once("util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();

$lesRues = array();
$lesRues = json_encode($pdo->chercherRueDansVille($_REQUEST['ville'], $_REQUEST['recherche']));

header('Content-Type: application/json');
echo $lesRues;