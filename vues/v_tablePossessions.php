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
            if(isset($actions) && isset($actions['immeubles'])){
                echo "<th>Actions</th>";
            }
            ?>
        </tr>
    </thead>

    <tbody>
        <?php
        foreach ($immeubles as $unImmeuble) {
        ?>

            <tr id="immeuble-<?php echo $unImmeuble['idImmeuble']; ?>">
                <td>
                    <?php echo $unImmeuble['numeroImmeuble']; ?>
                </td>
                <td>
                    <?php echo $unImmeuble['nomRue']; ?>
                </td>
                <td>
                    <?php echo $unImmeuble['cp']; ?>
                </td>
                <td>
                    <?php echo $unImmeuble['nomVille']; ?>
                </td>
                <?php
                if(isset($actions) && isset($actions['immeubles'])){
                    echo "<td><div class=\"divActions\">";

                    foreach($actions['immeubles'] as $nomAction => $attributsAction ){
                        echo "<a";
                        foreach($attributsAction as $nomAttribut => $contenuAttribut){
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
        ?>
    </tbody>
</table>