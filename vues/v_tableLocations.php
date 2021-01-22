<table class="niceLookingTable">
    <thead>
        <tr>
            <th>
                N° immeuble
            </th>

            <th>
                N° appt.
            </th>

            <th>
                Rue
            </th>

            <th>
                CP
            </th>

            <th>
                Ville
            </th>

            <?php
            if (!empty($locations) && isset($actions) && isset($actions['locations'])) {
                echo "<th>Actions</th>";
            }
            ?>

        </tr>
    </thead>

    <tbody>
        <?php
        if (!empty($locations)) {
            foreach ($locations as $uneLocation) {
        ?>

            <tr id="<?php echo "immeuble-" . $uneLocation['idImmeuble'] . "-appartement-" . $uneLocation['idAppartement']; ?>">

                <td class="centeredText">
                    <?php echo $uneLocation['numeroImmeuble']; ?>
                </td>

                <td class="centeredText">
                    <?php echo $uneLocation['idAppartement']; ?>
                </td>

                <td>
                    <?php echo $uneLocation['nomRue']; ?>
                </td>

                <td class="centeredText">
                    <?php echo $uneLocation['cp']; ?>
                </td>

                <td>
                    <?php echo $uneLocation['nomVille']; ?>
                </td>

                <?php
                if (isset($actions) && isset($actions['locations'])) {
                    echo "<td><div class=\"divActions\">";

                    foreach ($actions['locations'] as $nomAction => $attributsAction) {
                        echo "<a";
                        foreach ($attributsAction as $nomAttribut => $contenuAttribut) {
                            echo " $nomAttribut=\"$contenuAttribut\"";
                        }
                        echo "></a>";
                    }
                    echo "</div></td>";
                }

                ?>

                </tr>
        <?php
            }
        } else {
            echo "<tr><td class='centeredText italic' colspan='5'>Vous ne louez aucun appartement...</td></tr>";
        }
        ?>

    </tbody>
</table>