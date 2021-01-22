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
        if(!empty($valeurTypeEnergie)){
            foreach ($valeurTypeEnergie as $libTypeEnergie => $uneValeur) {

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
        } else {
            if(!isset($messageTable)){
                $messageTable = "Aucune informations de consommations pour cet appartement...";
            }
            echo "<tr><td class='centeredText italic' colspan='2'></td></tr>";
        }
        ?>
    </tbody>
</table>