<?php
require_once("../util/config.php");
require_once("../util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();
$infosConso = array();

$infosConso = $pdo->getConsoInfosAppartement($_REQUEST['idImmeuble'], $_REQUEST['idAppartement']);

include("../util/configActionsTable.inc.php");
include("../vues/v_tableUtilisateurs.php");