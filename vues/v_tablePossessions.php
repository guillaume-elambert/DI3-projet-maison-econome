<table class="niceLookingTable">
    <thead>
        <tr>
            <th>
                N°
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
            if (!empty($immeubles) && isset($actions) && isset($actions['possessions'])) {
                echo "<th>Actions</th>";
            }
            ?>
        </tr>
    </thead>

    <tbody>
        <?php
        if (!empty($immeubles)) {
            foreach ($immeubles as $unImmeuble) {
        ?>

                <tr id="immeuble-<?php echo $unImmeuble['idImmeuble']; ?>">

                    <td class="centeredText">
                        <?php echo $unImmeuble['numeroImmeuble']; ?>
                    </td>

                    <td>
                        <?php echo $unImmeuble['nomRue']; ?>
                    </td>

                    <td class="centeredText">
                        <?php echo $unImmeuble['cp']; ?>
                    </td>

                    <td>
                        <?php echo $unImmeuble['nomVille']; ?>
                    </td>

                    <?php
                    if (isset($actions) && isset($actions['possessions'])) {
                        echo "<td><div class=\"divActions\">";

                        foreach ($actions['possessions'] as $nomAction => $attributsAction) {
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
            echo "<tr><td class='centeredText italic' colspan='4'>Vous ne possédez aucun immeuble...</td></tr>";
        }
        ?>

    </tbody>
</table>