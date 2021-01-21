<table id="tableUtilisateurs" class="niceLookingTable">
    <thead>
        <tr>
            <th>
                Nom
            </th>
            <th>
                Prénom
            </th>
            <th>
                Mail
            </th>
            <th>
                Role
            </th>
            <?php
            if(isset($actions) && isset($actions['admin'])){
                echo "<th>Actions</th>";
            }
            ?>
        </tr>
    </thead>

    <tbody>
        <?php
        foreach ($utilisateurs as $unUtilisateur) {
        ?>

            <tr id="utilisateur-<?php echo $unUtilisateur['mail']; ?>">
                <td>
                    <?php echo $unUtilisateur['nomUtilisateur']; ?>
                </td>
                <td>
                    <?php echo $unUtilisateur['prenomUtilisateur']; ?>
                </td>
                <td>
                    <?php echo $unUtilisateur['mail']; ?>
                </td>
                <td>
                    <?php echo $unUtilisateur['libelleRole']; ?>
                </td>
                <?php
                if(isset($actions) && isset($actions['admin'])){
                    echo "<td><div class=\"divActions\">";

                    foreach($actions['admin'] as $nomAction => $attributsAction ){
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