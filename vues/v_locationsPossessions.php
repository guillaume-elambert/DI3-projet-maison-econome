<?php
echo "<h1>Locations et possession</h1></br></br><h2> Vos immeubles :</h2>";
$immeubles = $pdo->getUserPossession($_SESSION['user']);

if (is_array($immeubles) && !empty($immeubles)) {
    include("util/configActionsTable.inc.php");
    include("vues/v_tablePossessions.php");
} else {
    echo "<strong>Vous ne possedez aucun immeuble.</strong>";
}

echo "</br><a class='styledLink' href='?uc=$uc&action=ajoutLocPoss'>Ajouter une location/possession</a>";

echo "</br></br></br><h2> Vos Locations : </h2>";
$location = $pdo->getUserLocInfos($_SESSION['user']);

if (is_array($location) && !empty($location)) {
    $immeuble = $pdo->getInfosImmeuble($location['idImmeuble']);
    $rue = $pdo->getRueInfos($immeuble['idRue']);
    $ville = $pdo->getVilleInfos($rue['idVille']);
    $piece = $pdo->getPieceInfos($location['idImmeuble'], $location['idAppartement']);
    $appt = $pdo->getApptInfos($location['idImmeuble'], $location['idAppartement']);
    if (is_array($immeuble) && !empty($immeuble) && is_array($rue) && !empty($rue)) {
        echo "</strong>Vous louez l'appartement n°<strong>" . $location['idAppartement'] . " </strong> au <strong> " . $immeuble['numeroImmeuble'] . " " . $rue['nomRue'] . "</strong> à <strong>" . $ville['nomVille'] . ".</strong></br>";
    }
    if (is_array($piece) && !empty($piece)) {
        echo "liste de vos pièces :</br><ul>";
        foreach ($piece as $oui) {
            echo "<li><strong>" . $oui['nomPiece'] . "</strong></li>";
        }

        echo "</ul>Votre appartement est donc de type <strong>T" . $appt['idTypeAppart'] . "</strong></br>";
    } else {
        echo "<strong>Merci de renseigner les pieces de votre appartement.</strong></br>";
    }
    $appareils = $pdo->getAppareilInfos($location['idImmeuble'], $location['idAppartement']);

    //Entrée : on à récupéré des appareils depuis la BDD
    if (is_array($appareils) && !empty($appareils)) {
        $valeurTypeEnergie = array();
        $consommationTotale = 0;

        //Parcours de l'ensemble des appareilss
        foreach ($appareils as $unAppareil) {


            //Entrée : l'appareil est en route
            if ($unAppareil['etat'] != 0) {
                $consommation = $pdo->getConsoInfos($unAppareil['idTypeAppareil']);

                //Parcours des types d'énergie consommés par l'appareil
                foreach ($consommation as $conso) {

                    if (!isset($valeurTypeEnergie[$conso['libelle']])) {
                        $valeurTypeEnergie[$conso['libelle']] = floatval($conso['consommationHoraire']);
                    } else {
                        $valeurTypeEnergie[$conso['libelle']] += floatval($conso['consommationHoraire']);
                    }
                    $consommationTotale += floatval($conso['consommationHoraire']);
                }
            }
        }


        if (!empty($valeurTypeEnergie)) {
            $valeurTypeEnergie['Total'] = $consommationTotale;
            include("vues/v_tableLocations.php");
            
        } else {
            echo "Aucune consommation pour votre appartement";
        }
    } else {
        echo "<strong>Merci de renseigner les appareil dont vous disposez</strong>";
    }
} else {
    echo "<strong>Vous n'etes pas en location.</strong>";
}
