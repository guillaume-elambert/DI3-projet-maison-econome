<?php
session_start();
date_default_timezone_set('Europe/Paris');

require_once("util/config.php");
require_once("util/class.PdoProjet3A.inc.php");
require_once('util/fonctions.inc.php');

ob_start();

$pdo = PdoProjet3A::getPdo();

include_once("vues/v_header.php");

if(!isset($_GET['uc'])) {
    $uc = 'accueil'; // si $_GET['uc'] n'existe pas , $uc reçoit une valeur par défaut
}
else {
	$uc = $_GET['uc'];
}

switch($uc)
{
	case "accueil":
		//echo "TU ES ".isset($_SESSION['user']);
		include("vues/v_accueil.php");
		break;
	

	case "info": 
		include("controlleurs/c_informations.php");
		break;
	

	case "utilisateur": 
		include("controlleurs/c_gestionUtilisateurs.php");
		break;
	

	case "test": 
		//$pdo->insertRueVille();
		//$pdo->insertRootUser();
		//var_dump(password_hash("root", PASSWORD_DEFAULT));
		break;

	

	default : 
		$redirect = HOME;
		break;
	
}

include("vues/v_footer.php");

if (isset($redirect)) {
	ob_end_clean();
	header("Location: " . $redirect);
}

ob_end_flush();

?>
