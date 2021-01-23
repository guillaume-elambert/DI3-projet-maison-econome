<?php
require_once("../util/config.php");
require_once("util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();

$lesImmeubles = array();
$lesImmeubles = json_encode($pdo->chercherImmeublesLibresDansRue($_REQUEST['rue'], $_REQUEST['recherche']));

header('Content-Type: application/json');
echo $lesImmeubles;