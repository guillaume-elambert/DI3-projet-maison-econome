<form id="formAjoutAppareil" method="POST" action="?uc=<?php echo $uc; ?>&action=ajouter-un-appareil">

    <input class="hidden" value="<?php echo $immeuble; ?>" id="immeuble" name="immeuble" required readonly>
    <input class="hidden" value="<?php echo $appartement; ?>" id="appartement" name="appartement" required readonly>

    <fieldset>
        <legend>
            <h3>Ajouter un appareil dans l'appt. <?php echo $appartement; ?> de l'immeuble <?php echo $immeuble; ?></h3>
        </legend>

        <table>
            <tbody>
                <tr class="champ" id="nom">
                    <td>
                        Nom de votre appareil <span class="red">*</span>
                    </td>
                    <td>
                        <input id="nomAppareil" name="nomAppareil" maxlength="50" placeholder="Nom de votre appareil">
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
                        foreach($pieces as $unePiece){
                            echo "<option value='".$unePiece['idPiece']."'>".$unePiece['nomPiece']."</option>";
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
                        <input id="positionPiece" name="positionPiece" maxlength="30" placeholder="Décrivez la position de l'appareil dans la pièce">
                    </td>
                </tr>
                <tr class="champ" id="situation">
                    <td>
                        Etat <span class="red">*</span>
                    </td>
                    <td>
                        <input type="radio" id="etatAllume" name="etatAppareil" value="1" checked>
                        <label for="etatAllume">Allumé</label></br>

                        <input type="radio" id="etatEteint" name="etatAppareil" value="0">
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