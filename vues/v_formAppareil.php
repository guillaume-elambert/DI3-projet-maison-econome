<form id="formAjoutAppareil" method="POST" action="<?php echo "?uc=$uc&action=$action"; ?>">

    <input class="hidden" value="<?php echo $immeuble; ?>" id="immeuble" name="immeuble" required readonly>
    <input class="hidden" value="<?php echo $appartement; ?>" id="appartement" name="appartement" required readonly>

    <?php
    if (isset($appareil) && !empty($appareil)) {
        $modifAppareil = true;
        echo '<input class="hidden" value="' . $appareil['idAppareil'] . '" id="appareil" name="appareil" required readonly>';
    } else {
        $modifAppareil = false;
    }
    ?>

    <fieldset>
        <legend>
            <h3><?php echo $modifAppareil?"Modifier":"Ajouter"; ?> un appareil dans l'appt. <?php echo $appartement; ?> de l'immeuble <?php echo $immeuble; ?></h3>
        </legend>

        <table>
            <tbody>
                <tr class="champ" id="nom">
                    <?php
                    if ($modifAppareil) {
                        $libelle = $appareil['libelleAppareil'];
                    } else $libelle = "";
                    ?>
                    <td>
                        Nom de votre appareil <span class="red">*</span>
                    </td>
                    <td>
                        <input id="nomAppareil" name="nomAppareil" maxlength="50" value="<?php echo $libelle; ?>" placeholder="Nom de votre appareil">
                    </td>
                </tr>
                <tr class="champ" id="Appareil">
                    <td>
                        Type d'appareil <span class="red">*</span>
                    </td>
                    <td>
                        <input id="rechercheAppareil" type="search" placeholder="Affiner votre recherche d'appareil">

                        <select id="selectTypeAppareil" name="typeAppareil" required disabled></select>

                    </td>
                </tr>
                <tr class="champ" id="Pièce">
                    <td>
                        Pièce <span class="red">*</span>
                    </td>
                    <td>
                        <select id="selectPiece" name="piece" required>
                            <option val="">--- Veuillez choisir la pièce où se trouve l'appareil ---</option>

                            <?php
                            foreach ($pieces as $unePiece) {
                                if ($modifAppareil && $appareil['idPiece'] == $unePiece['idPiece']) {
                                    $selected = "selected";
                                } else {
                                    $selected = "";
                                }
                                echo "<option value='" . $unePiece['idPiece'] . "' $selected>" . $unePiece['nomPiece'] . "</option>";
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr class="champ" id="position">
                    <td>
                        Position dans la pièce <span class="red">*</span>
                    </td>
                    <td>
                        <?php
                        if ($modifAppareil) {
                            $position = $appareil['descriptionPosition'];
                        } else {
                            $position = "";
                        }
                        ?>
                        <input id="positionPiece" name="positionPiece" maxlength="30" value="<?php echo $position; ?>" placeholder="Décrivez la position de l'appareil dans la pièce">
                    </td>
                </tr>
                <tr class="champ" id="situation">
                    <td>
                        Etat <span class="red">*</span>
                    </td>
                    <td>
                        <?php
                        if( !$modifAppareil || ($modifAppareil && $appareil['etat']==1)){

                            $radioAllume = "checked";
                            $radioEteint = "";

                        } else {
                            $radioAllume = "";
                            $radioEteint = "checked";
                        }
                        ?>
                        <input type="radio" id="etatAllume" name="etatAppareil" value="1" <?php echo $radioAllume ?> >
                        <label for="etatAllume">Allumé</label></br>

                        <input type="radio" id="etatEteint" name="etatAppareil" value="0" <?php echo $radioEteint ?> >
                        <label for="etatEteint">Éteint</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Valider" name="valider">
                        <input type="reset" value="Annuler" name="annuler">
                    </td>
                </tr>

            </tbody>
        </table>
    </fieldset>
</form>