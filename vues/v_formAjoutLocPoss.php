<form id="formAjoutLocPoss" method="POST" action="?uc=<?php echo $uc; ?>&action=ajoutLocPoss">
    <fieldset>
        <legend>
            <h3>Ajouter une location/possession</h3>
        </legend>

        <table>
            <tbody>

                <?php include("vues/v_champsAjoutAppart.php"); ?>

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