<h1> Bienvenue sur votre profil </h1>
<?php
if (isset($_SESSION['user'])) {
    echo "<h2> Vos informations personnel :  </h2>";
    $donneesUser = $pdo->getUserInfos($_SESSION['user']);
    //var_dump($donneesUser);
    echo "<div id='utilisateur" . $donneesUser['mail'] . "'> Nom :   <strong>" . $donneesUser['nomUtilisateur'] . " </strong></br> Prenom :   <strong> " . $donneesUser['prenomUtilisateur'] . "</strong></br> mail :   <strong>" . $donneesUser['mail'] . "</strong></br>date de Naissance :   <strong>" . $donneesUser['dateNaissance'] . "</strong></div>";

    echo "<h2> Vos immeubles :</h2>";
    $immeubles = $pdo->getUserPossession($_SESSION['user']);

	if(is_array($immeubles) && !empty($immeubles)){
        
        include("vues/v_tableImmeubles.php");
        
    }
    else{
        echo "<strong>Vous ne possedez aucun immeuble.</strong>";
    }



    echo "<h2> Vos Locations : </h2>";
    $location = $pdo->getUserLocInfos($_SESSION['user']);
   
    if (is_array($location) && !empty($location)) {
        $immeuble = $pdo->getInfosImmeuble($location['idImmeuble']);
        $rue = $pdo->getRueInfos($immeuble['idRue']);
        $ville = $pdo->getVilleInfos($rue['idVille']);
        $piece = $pdo->getPieceInfos($location['idImmeuble'],$location['idAppartement']);
        $appt = $pdo->getApptInfos($location['idImmeuble'],$location['idAppartement']);
        if (is_array($immeuble) && !empty($immeuble) && is_array($rue) && !empty($rue)) {
            echo "</strong>Vous louez l'appartement n°<strong>" . $location['idAppartement'] . " </strong> au <strong> ". $immeuble['numeroImmeuble'] . " " . $rue['nomRue'] . "</strong> à <strong>" . $ville['nomVille'] . ".</strong></br>";
        }
        if (is_array($piece) && !empty($piece)){
            echo "liste de vos pièces :</br>";
            foreach($piece as $oui){
                echo "-<strong>" . $oui['nomPiece']."</strong></br>";

            }
            
            echo "votre appartement est donc de type <strong>T" . $appt['idTypeAppart'] . "</strong>";
        }
        else{
            echo "<strong>Merci de renseigner les pieces de votre appartement.</strong></br>";
        }
        $appareils= $pdo->getAppareilInfos($location['idImmeuble'],$location['idAppartement']);
        
        //Entrée : on à récupéré des appareils depuis la BDD
        if (is_array($appareils) && !empty($appareils)){
            $valeurTypeEnergie = array();

            //Parcours de l'ensemble des appareilss
            foreach($appareils as $unAppareil){
                
                //Entrée : l'appareil est en route
                if ($unAppareil['etat'] != 0){
                    $consommation = $pdo->getConsoInfos($unAppareil['idTypeAppareil']);
                    $consommationTotale = 0;

                    //Parcours des types d'énergie consommés par l'appareil
                    foreach($consommation as $conso){

                        if(!isset($valeurTypeEnergie[$conso['libelle']])){
                            $consommationTotale += ( $valeurTypeEnergie[$conso['libelle']] = floatval($conso['consommationHoraire']) );
                        } else {
                            $consommationTotale += ( $valeurTypeEnergie[$conso['libelle']] += floatval($conso['consommationHoraire']) );
                        }
                    }
                    
                    $valeurTypeEnergie['Consommation de l\'appartement'] = $consommationTotale;
                }
            }

            if(!empty($valeurTypeEnergie)){
                ?>

                <table class="niceLookingTable">
                    <thead>
                        <tr>
                            <th>
                                Type d'énergie
                            </th>
                            <th>
                                Consommation
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                foreach($valeurTypeEnergie as $libTypeEnergie => $uneValeur){

                    echo "
                        <tr>
                            <td>
                                $libTypeEnergie
                            </td>
                            <td>
                                $uneValeur W/h
                            </td>
                        </tr>
                    ";
                }

                echo "</tbody></table>";
            } else {
                echo "Aucune consommation pour votre appartement";
            }
        }
        else {
            echo "<strong>Merci de renseigner les appareil dont vous disposez</strong>";
        }
        
    }
    else{
        echo "<strong>Vous n'etes pas en location.</strong>";
    }
} else {
    echo '<div> Cette page sera accessible lorsque vous serez connecté, utilisez <a href="?uc=utilisateur&action=connexion">"MON ESPACE"</a> pour vous connecter.</div>';
}
?>