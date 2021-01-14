<form method="POST" id="formConnexion" action="?uc=utilisateur&action=connexion">
    <fieldset>
        <legend>Connexion</legend>
        <table>
            <tbody>
                <tr class="champ">
                    <td>
                        <label for="mail">Mail <span class="red">*</span></label>
                    </td>
                    <td>
                        <input id="mail" type="text" name="mail" value="<?php echo $mail; ?>" size="50" maxlength="50" required>
                    </td>
                </tr>

                <tr class="champ">
                    <td>
                        <label for="mdp">Mot de passe <span class="red">*</span></label>
                    </td>
                    <td>
                        <input id="mdp" type="password" name="mdp" size="50" maxlength="50" required>
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

<br/><a href="?uc=utilisateur&action=inscription">Pas encore membre ? Inscrivez-vous ici !</a>