<form id="formAjoutImmeuble" method="POST" action="?uc=<?php echo $uc; ?>&action=<?php echo $action; ?>">
    <fieldset>
        <legend>
            <h3>Ajouter un immeuble</h3>
        </legend>

        <table>
            <tbody>

                <?php
                include("vues/v_champsRechercheVilleRue.php");
                ?>

                <tr class="champ">
                    <td>
                        Immeuble <span class="red">*</span>
                    </td>
                    <td>
                        <input id="immeuble" name="immeuble" value="<?php echo $immeuble; ?>" maxlength="5" placeholder="Saisissez le numéro de l'immeuble" disabled required>

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