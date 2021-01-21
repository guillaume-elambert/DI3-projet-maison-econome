<?php

if (!isset($_SESSION['user'])) {
    $redirect = HOME;
    $messages[] = "Vous devez être connectez pour accéder à cette page.";
} else if (!isset($_SESSION['admin'])) {
    $redirect = HOME;
    $messages[] = "Vous devez être administrateur pour accéder à cette page.";
} else {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = "dashboard";
    }

    switch ($action) {
        case "gerer-utilisateurs":
            $utilisateurs = $pdo->get25Utilisateurs();

            include("util/configActionsTable.inc.php");
            include("vues/v_gestionUtilisateurs.php");
            
            $javascript[] = HOME . 'script/gestionUtilisateurs.js';
            $javascript[] = HOME . 'script/actionsAdministrateurs.js';
            break;

        default:
            $redirect = "?uc=$uc&action=gerer-utilisateurs";
            break;
    }
}
