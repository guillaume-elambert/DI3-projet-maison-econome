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
        ?>
    </tbody>
</table>