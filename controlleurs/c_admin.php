<?php

if (!isset($_SESSION['user'])) {
    $redirect = HOME;
    $messages[] = "Vous devez être connecté pour accéder à cette page.";
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

        case "modifier-utilisateur":
            if (isset($_POST['nom'])) {
                $nouvMail   = $_POST['mail'];
                $nom        = $_POST['nom'];
                $prenom     = $_POST['prenom'];
                $role       = $_POST['role'];

                $dateNaiss  = date("Y-m-d", strtotime($_POST['dateNaiss']));
                $mdp        = $_POST['mdp'];
                $verifMdp   = $_POST['verifMdp'];

                $erreurs = getErreursSaisieModifInfos($nom, $prenom, $dateNaiss, $mdp, $verifMdp, $role);

                if (empty($erreurs)) {

                    if ($pdo->updateUserInfos($_GET['user'], $nouvMail, $nom, $dateNaiss, $prenom, $mdp, $role)) {


                        //Entrée : l'utilisateur se modifie lui même
                        if (strcmp($_GET['user'], $_SESSION['user']) == 0) {

                            //Entrée : l'administrateur à modifié son rôle
                            //  => On supprime sa session administrateur
                            if ($role != 1) {
                                unset($_SESSION['admin']);
                            }
                            $_SESSION['user'] = $nouvMail;
                        }

                        $success[] = "Modifications enregistrées avec succès";
                        $redirect = "?uc=$uc&action=gerer-utilisateurs";
                    } else {
                        $messages[] = "Une erreur s'est produite...";
                        $redirect = "?uc=$uc&action=$action";
                    }
                } else {
                    $setSessionValues = true;

                    $erreurs[] = "Les champs n'ont pas été correctement saisis";
                    $redirect = "?uc=$uc&action=$action";
                }
            } else {
                $donneesUser = $pdo->getUserInfos($_GET['user']);
                $roles = $pdo->getRoles();

                if (!empty($donneesUser) && !empty($roles)) {
                    $nouvMail   = $_GET['user'];
                    $nom        = $donneesUser['nomUtilisateur'];
                    $prenom     = $donneesUser['prenomUtilisateur'];
                    $dateNaiss  = $donneesUser['dateNaissance'];
                    $role       = $donneesUser['idRole'];

                    include("vues/v_formModifInfos.php");
                    $javascript[] = HOME . 'script/modifInfosUtilisateur.js';
                } else {
                    $messages[] = "Une erreur s'est produite...";
                    $redirect = "?uc=$uc&action=gerer-utilisateurs";
                }
            }
            break;

        default:
            $redirect = "?uc=$uc&action=gerer-utilisateurs";
            break;
    }
}
