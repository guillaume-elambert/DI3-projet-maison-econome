<div id="formConnexion">

    <form method="POST" action="?uc=utilisateur&action=connexion">
        <fieldset>
            <legend>Connexion</legend>
            <p></p>
            <label for="mail">mail <span class="red">*</span></label>
            <input id="mail" type="text" name="mail" value="<?php echo $mail ?>" size="50" maxlength="50" required>
            </p>
            <p>
                <label for="mdp">Mot de passe <span class="red">*</span></label>
                <input id="mdp" type="password" name="mdp" size="50" maxlength="50" required>
            </p>
            <p>
                <input type="submit" value="Valider" name="valider">
                <input type="reset" value="Annuler" name="annuler">
            </p>
        </fieldset>
    </form>
</div>

<br/><a href="?uc=utilisateur&action=inscription">Pas encore membre ? Inscrivez-vous ici !</a>