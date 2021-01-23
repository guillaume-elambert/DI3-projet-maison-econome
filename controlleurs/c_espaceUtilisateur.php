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


        case "ajouter-un-appareil":

            //Entrée : la personne à rempli et valider le formulaire
            if (isset($_POST['immeuble'])) {

                $idImmeuble          = $_POST['immeuble'];
                $idAppartement       = $_POST['appartement'];
                $etat                = $_POST['etatAppareil'];
                $libelleAppareil     = $_POST['nomAppareil'];
                $idTypeAppareil      = $_POST['typeAppareil'];
                $idPiece             = $_POST['piece'];
                $descriptionPosition = $_POST['positionPiece'];


                $erreurs = getErreursSaisieAjoutAppareil($libelleAppareil, $idTypeAppareil, $idPiece, $descriptionPosition, $etat, $idImmeuble, $idAppartement);

                if (empty($erreurs)) {
                    if ($pdo->checkUserLocationAppartement($_SESSION['user'], $idImmeuble, $idAppartement)) {

                        //Entrée : l'appareil a bien été enregistré dans la BDD
                        if ($pdo->insertAppareil($libelleAppareil, $etat, $idImmeuble, $idAppartement, $idPiece, $idTypeAppareil, $descriptionPosition)) {

                            $success[] = "Appareil enregistré avec succès";
                        } else {
                            $messages[] = "Une erreur s'est produite...";
                        }
                        $redirect = "?uc=$uc&action=$action&immeuble=$idImmeuble&appartement=$idAppartement";
                        
                    } else {
                        $messages[] = "Vous n'êtes pas locataire de cet appartement...";
                        $redirect   = HOME . "?uc=$uc&action=mes-locations-possessions";
                    }
                } else {

                    $erreurs[] = "Les champs n'ont pas été correctement saisis";
                    $redirect = "?uc=$uc&action=$action&immeuble=$idImmeuble&appartement=$idAppartement";
                }
            } else {

                //Entrée : si l'identifiant de l'appartement et de l'immeuble ont été passés dans l'URL
                if (isset($_GET['immeuble']) && isset($_GET['appartement'])) {


                    $immeuble = $_GET['immeuble'];
                    $appartement = $_GET['appartement'];
                    $pieces = $pdo->getPiecesAppart($immeuble, $appartement);

                    include("vues/v_formAppareil.php");
                    $javascript[] = HOME . 'script/formAppareil.js';
                } else {
                    $messages[] = "Vous devez sélectionner l'appartement dans la liste de vos locations";
                    $redirect   = HOME . "?uc=$uc&action=mes-locations-possessions";
                }
            }
            break;

        case "modifier-appareil":


            //Entrée : la personne à rempli et valider le formulaire
            if (isset($_POST['immeuble'])) {

                $idImmeuble          = $_POST['immeuble'];
                $idAppartement       = $_POST['appartement'];
                $idPiece             = $_POST['piece'];
                $idAppareil          = $_POST['appareil'];
                $etat                = $_POST['etatAppareil'];
                $libelleAppareil     = $_POST['nomAppareil'];
                $idTypeAppareil      = $_POST['typeAppareil'];
                $descriptionPosition = $_POST['positionPiece'];


                $erreurs = getErreursSaisieModifAppareil($libelleAppareil, $idTypeAppareil, $idPiece, $descriptionPosition, $etat, $idImmeuble, $idAppartement, $idAppareil);

                if (empty($erreurs)) {
                    if ($pdo->checkUserLocationAppartement($_SESSION['user'], $idImmeuble, $idAppartement)) {

                        //Entrée : l'appareil a bien été modifié dans la BDD
                        if ($pdo->updateAppareil($idImmeuble, $idAppartement, $idPiece, $idAppareil, $libelleAppareil, $etat, $idTypeAppareil, $descriptionPosition)) {

                            $success[] = "Modifications enregistrées avec succès";
                            $redirect = "?uc=$uc&action=mes-locations-possessions";
                        } else {
                            $messages[] = "Une erreur s'est produite...";
                            $redirect = "?uc=$uc&action=$action&immeuble=$idImmeuble&appartement=$idAppartement&piece=$idPiece&appareil=$idAppareil";
                        }
                    } else {
                        $messages[] = "Vous n'êtes pas locataire de cet appartement...";
                        $redirect   = HOME . "?uc=$uc&action=mes-locations-possessions";
                    }
                } else {

                    $erreurs[] = "Les champs n'ont pas été correctement saisis";
                    $redirect = "?uc=$uc&action=$action&immeuble=$idImmeuble&appartement=$idAppartement&piece=$idPiece&appareil=$idAppareil";
                }
            } else {

                //Entrée : si l'identifiant de l'appartement et de l'immeuble ont été passés dans l'URL
                if (isset($_GET['immeuble']) && isset($_GET['appartement']) && isset($_GET['piece']) && isset($_GET['appareil'])) {


                    $immeuble = $_GET['immeuble'];
                    $appartement = $_GET['appartement'];
                    $piece = $_GET['piece'];

                    $appareil = $pdo->getInfosAppareil($immeuble, $appartement, $piece, $_GET['appareil']);
                    $pieces = $pdo->getPiecesAppart($immeuble, $appartement);

                    include("vues/v_formAppareil.php");
                    $javascript[] = HOME . 'script/formAppareil.js';
                } else {
                    $messages[] = "Vous devez sélectionner l'appareil dans la liste des appareils de votre location";
                    $redirect   = HOME . "?uc=$uc&action=mes-locations-possessions";
                }
            }
            break;


        case "modifier-mes-infos":

            if (isset($_POST['nom'])) {
                $nouvMail   = $_POST['mail'];
                $nom        = $_POST['nom'];
                $prenom     = $_POST['prenom'];

                $dateNaiss  = date("Y-m-d", strtotime($_POST['dateNaiss']));
                $mdp        = $_POST['mdp'];
                $verifMdp   = $_POST['verifMdp'];

                $erreurs = getErreursSaisieModifInfos($nouvMail, $nom, $prenom, $dateNaiss, $mdp, $verifMdp, -1);

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



        case "mes-locations-possessions":
            $immeubles = $pdo->getUserPossession($_SESSION['user']);
            $locations = $pdo->getUserLocInfos($_SESSION['user']);

            require_once("util/configActionsTables.inc.php");
            include("vues/v_locationsPossessions.php");
            $javascript[] = HOME . 'script/actionsPossessions.js';
            $javascript[] = HOME . 'script/actionsLocations.js';
            $javascript[] = HOME . 'script/listeAppareils.js';
            $javascript[] = HOME . 'script/actionsAppareils.js';
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


                    if (strcmp($situationUser, "locataire") == 0) {
                        $msg = "Votre location a bien été enregistrée.";
                    } else {
                        $msg = "Votre possession a bien été enregistrée.";
                    }
                    $messages[] = $msg;
                    $redirect   = "?uc=$uc&action=mes-locations-possessions";
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
