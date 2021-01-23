<?php
require_once("../util/config.php");
require_once("../util/class.PdoProjet3A.inc.php");
$pdo = PdoProjet3A::getPdo();

$utilisateurs = array();
$utilisateurs = $pdo->chercherUtilisateur($_REQUEST['recherche']);

require_once("util/configActionsTables.inc.php");
include("vues/v_tableUtilisateurs.php");