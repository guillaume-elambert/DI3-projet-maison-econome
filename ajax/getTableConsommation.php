<?php
require_once("../util/config.php");
require_once("util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();

$infosConso = array();
$infosConso = $pdo->getConsoInfosAppartement($_REQUEST['idImmeuble'], $_REQUEST['idAppartement']);

require_once("util/configActionsTables.inc.php");
include("vues/v_tableUtilisateurs.php");