<table class="niceLookingTable">
    <thead>
        <tr>
            <th>
                Libelle
            </th>

            <th>
                Type
            </th>

            <th>
                Pièce
            </th>

            <th>
                Descr. position
            </th>

            <th>
                État
            </th>

            <?php
            if (!empty($appareils) && isset($actions) && isset($actions['appareils'])) {
                echo "<th>Actions</th>";
            }
            ?>

        </tr>
    </thead>

    <tbody>
        <?php
        if (!empty($appareils)) {
            foreach ($appareils as $unAppareil) {
        ?>

                <tr id="<?php echo "appareil-".$unAppareil['idAppareil']; ?>">

                    <td>
                        <?php echo $unAppareil['libelleAppareil']; ?>
                    </td>

                    <td>
                        <?php echo $unAppareil['libelleTypeAppareil']; ?>
                    </td>

                    <td>
                        <?php echo $unAppareil['nomPiece']; ?>
                    </td>

                    <td>
                        <?php echo $unAppareil['descriptionPosition']; ?>
                    </td>

                    <td>
                        <?php echo $unAppareil['etat']==1 ? "Allumé" : "Éteint"; ?>
                    </td>


                    <?php
                    if (isset($actions) && isset($actions['appareils'])) {
                        echo "<td><div class=\"divActions\">";

                        foreach ($actions['appareils'] as $nomAction => $attributsAction) {
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