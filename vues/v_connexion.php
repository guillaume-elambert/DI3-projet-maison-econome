<form method="POST" id="formConnexion" action="?uc=<?php echo $uc; ?>&action=connexion">
    <fieldset>
        <legend>
            <h3>Connexion</h3>
        </legend>
        <table>
            <tbody>
                <tr class="champ">
                    <td>
                        Mail <span class="red">*</span>
                    </td>
                    <td>
                        <input id="mail" type="text" name="mail" value="<?php echo $mail; ?>" maxlength="50" required>
                    </td>
                </tr>

                <tr class="champ">
                    <td>
                        Mot de passe <span class="red">*</span>
                    </td>
                    <td>
                        <input id="mdp" type="password" name="mdp" maxlength="255" required>
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

<br /><a href="?uc=<?php echo $uc; ?>&action=inscription">Pas encore membre ? Inscrivez-vous ici !</a>