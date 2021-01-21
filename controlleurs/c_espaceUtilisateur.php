<?php

if (!isset($_SESSION['user'])) {
    $redirect = HOME;
    $messages[] = "Vous devez être connecté pour accéder à cette page.";
} else {
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = "dashboard";
    }

    /*$actionsImmeubles = array(
        'Mettre fin' => array(
            'class'     =>    'action fas fa-times red',
            'onclick'   =>     'ajaxDateFin(this.closest(\'tr\'));',
            'title'     =>    'Déclarer mettre fin à la possession'
        )
    );*/

    switch ($action) {

        case 'dashboard':
            if (isset($_SESSION['admin'])) {
                include("vues/v_dashboardAdministrateur.html");
            } else {
                include("vues/v_dashboardUtilisateur.html");
            }
            break;


        case "mes-infos":

            $donneesUser = $pdo->getUserInfos($_SESSION['user']);
            include("vues/v_mesInfos.php");
            break;


        case "modifier-mes-infos":

            if (isset($_POST['nom'])) {
                $nouvMail   = $_POST['mail'];
                $nom        = $_POST['nom'];
                $prenom     = $_POST['prenom'];

                $dateNaiss  = date("Y-m-d", strtotime($_POST['dateNaiss']));
                $mdp        = $_POST['mdp'];
                $verifMdp   = $_POST['verifMdp'];

                $erreurs = getErreursSaisieModifInfos($nom, $prenom, $dateNaiss, $mdp, $verifMdp, -1);

                if (empty($erreurs)) {

                    if ($pdo->updateUserInfos($_SESSION['user'], $nouvMail, $nom, $dateNaiss, $prenom, $mdp, -1)) {
                        $_SESSION['user'] = $nouvMail;
                        
                        $success[] = "Modifications enregistrées avec succès";
                        $redirect = "?uc=$uc&action=mes-infos";
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
                $donneesUser = $pdo->getUserInfos($_SESSION['user']);

                if (!empty($donneesUser)) {
                    $nouvMail   = $_SESSION['user'];
                    $nom        = $donneesUser['nomUtilisateur'];
                    $prenom     = $donneesUser['prenomUtilisateur'];
                    $dateNaiss  = $donneesUser['dateNaissance'];

                    include("vues/v_formModifInfos.php");
                    $javascript[] = HOME . 'script/modifInfosUtilisateur.js';
                } else {
                    $messages[] = "Une erreur s'est produite...";
                    $redirect = "?uc=$uc&action=mes-infos";
                }
            }
            break;



        case "mes-locations-posessions":
            include("vues/v_locationsPossessions.php");
            $javascript[] = HOME . 'script/actionsImmeubles.js';
            break;


        case "ajoutLocPoss":

            if (isset($_POST['situationUser'])) {

                $erreurs = array();
                $setSessionValues = false;

                $debutLocation     = date("Y-m-d", strtotime($_POST['debutLocation']));
                $ville              = $_POST['ville'];
                $rue              = $_POST['rue'];
                $immeuble          = $_POST['immeuble'];
                $appartement     = $_POST['appartement'];
                $situationUser    = $_POST['situationUser'];

                $erreurs = getErreursSaisieAjoutLocPoss($ville, $rue, $immeuble, $appartement, $situationUser, $debutLocation);

                if (empty($erreurs)) {
                    $mail = $_SESSION['user'];

                    if (strcmp($situationUser, "locataire") == 0) {
                        $erreursInsertionLocPoss = $pdo->nouvelleLocation($mail, $debutLocation, $immeuble, $appartement);
                    } else {
                        $erreursInsertionLocPoss = $pdo->nouvellePossession($mail, $debutLocation, $immeuble);
                    }

                    if (!empty($erreurInsertionUser)) {
                        $erreurs = array_merge($erreurs, $erreursInsertionLocPoss);
                    }

                    $redirect     = "?uc=$uc&action=mes-infos";

                    if (strcmp($situationUser, "locataire") == 0) {
                        $msg = "Votre location a bien été enregistrée.";
                    } else {
                        $msg = "Votre possession a bien été enregistrée.";
                    }
                    $messages[] = $msg;
                } else {
                    $setSessionValues = true;

                    $erreurs[] = "Les champs n'ont pas été correctement saisis";
                    $redirect = "?uc=$uc&action=$action";
                }

                if ($setSessionValues) {
                    $_SESSION['debutLocation']  = $debutLocation;
                    $_SESSION['ville']          = $ville;
                    $_SESSION['rue']            = $rue;
                }
            } else {

                if (isset($_SESSION['debutLocation'])) {
                    $debutLocation = $_SESSION['debutLocation'];
                    unset($_SESSION['debutLocation']);
                } else $debutLocation = '';

                if (isset($_SESSION['rue'])) {
                    $rue = $_SESSION['rue'];
                    unset($_SESSION['rue']);
                } else $rue = '';

                if (isset($_SESSION['ville'])) {
                    $ville = $_SESSION['ville'];
                    unset($_SESSION['ville']);
                } else $ville = '';

                include("vues/v_formAjoutLocPoss.php");
                $javascript[] = HOME . 'script/formLocPoss.js';
            }

            break;

        default:
            $redirect = "?uc=$uc&action=dashboard";
            break;
    }
}
