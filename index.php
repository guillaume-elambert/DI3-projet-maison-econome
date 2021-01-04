<?php
session_start();

$base = "/projet-bdd-3a/";

date_default_timezone_set('Europe/Paris');
require_once("util/class.PdoProjet3A.inc.php");


include("vues/v_header.php");

if(!isset($_GET['uc'])) {
    $uc = 'accueil'; // si $_GET['uc'] n'existe pas , $uc reçoit une valeur par défaut
}
else {
	$uc = $_GET['uc'];
}

$superpdo = PdoProjet3A::getPdo();


switch($uc)
{
	case "accueil": {
		include("vues/v_accueil.php");
		break;
	}

	case "info": {
		include("vues/v_apropos.html");
		break;
	}

	case "utilisateur": {
		include("controlleurs/c_gestionUtilisateurs.php");
		break;
	}

	default : {
		header('Location:?');
		break;
	}
}

include("vues/v_footer.php");

?>
