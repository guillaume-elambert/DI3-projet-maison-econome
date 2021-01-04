<div id="inscriptionClient">
<form method="POST" action="?uc=utilisateur&action=confirmerInscription">
   <fieldset>
     <legend>Inscription</legend>
      <p>
         <label for="nom">Nom*</label>
         <input id="nom" type="text" name="nom" value="<?php echo $nom ?>" size="30" maxlength="30" required>
      </p>
      <p>
         <label for="prenom">Prénom*</label>
         <input id="prenom" type="text" name="prenom" value="<?php echo $prenom ?>" size="30" maxlength="30" required>
      </p>
      <p>
         <label for="rue">Adresse*</label>
          <input id="rue" type="text" name="rue" value="<?php echo $rue ?>" size="60" maxlength="60" required>
      </p>
      <p>
         <label for="cp">Code postal* </label>
         <input id="cp" type="text" name="cp" value="<?php echo $cp ?>" size="5" maxlength="5" required>
      </p>
      <p>
         <label for="ville">Ville* </label>
         <input id="ville" type="text" name="ville"  value="<?php echo $ville ?>" size="60" maxlength="60" required>
      </p>
      <p>
         <label for="mail">Mail* </label>
         <input id="mail" type="email"  name="mail" value="<?php echo $mail ?>" size ="90" maxlength="90" required>
      </p>
      <p>
         <label for="mdp">Mot de passe* </label>
         <input id="mdp" type="password"  name="mdp" size ="90" maxlength="90" required>
      </p> 
      <p>
         <input type="submit" value="Valider" name="valider">
         <input type="reset" value="Annuler" name="annuler"> 
      </p>
     </fieldset>
</form>
</div>

<br/><a href="?uc=utilisateur&action=connexion">Déjà membre ? Connectez-vous ici !</a>





