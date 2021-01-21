<form id="formAjoutImmeuble" method="POST" action="?uc=<?php echo $uc; ?>&action=<?php echo $action; ?>">
    <fieldset>
        <legend>
            <h3>Ajouter un Apartement</h3>
        </legend>

        <table>
            <tbody>

                <?php
                include("vues/v_champsRechercheVilleRueImmeuble.php");
                ?>

                <tr class="champ">
                    <td>
                        Type d'appartement <span class="red">*</span>
                    </td>
                    <td>
                        <select id="typeAppart" name="typeAppart" required disabled>
                            <option value="">--- Veuillez choisir le type de l'appartement ---</option>
                            <?php

                            foreach ($typesAppart as $unTypeAppart) {
                                echo '<option value="' . $unTypeAppart['idTypeAppart'] . "\" $selected>" . $unTypeAppart['libelleTypeAppart'] . '</option>';
                            }

                            ?>
                    </td>
                </tr>


                <tr class="champ">
                    <td>
                        Degré de sécurité <span class="red">*</span>
                    </td>
                    <td>
                        <select id="degreSecurite" name="degreSecurite" required disabled>
                            <option value="">--- Veuillez choisir le degré de sécurité de l'appartement ---</option>
                            <?php

                            foreach ($degresSecurite as $unDegreSecurite) {
                                echo '<option value="' . $unDegreSecurite['idDegreSecurite'] . "\" $selected>" . $unDegreSecurite['libelleDegreSecurite'] . '</option>';
                            }

                            ?>
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