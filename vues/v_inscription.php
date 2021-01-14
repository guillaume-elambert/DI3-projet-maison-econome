<form id="formInscription" method="POST" action="?uc=utilisateur&action=confirmerInscription">
   <fieldset>
      <legend>Inscription</legend>

      <table>
         <tbody>

            <tr class="champ">
               <td>
                  <label for="nom">Nom <span class="red">*</span></label>
               </td>
               <td>
                  <input id="nom" type="text" name="nom" value="<?php echo $nom ?>" size="30" maxlength="30" required>
               </td>
            </tr>

            <tr class="champ">
               <td>
                  <label for="prenom">Prénom <span class="red">*</span></label>
               </td>
               <td>
                  <input id="prenom" type="text" name="prenom" value="<?php echo $prenom ?>" size="30" maxlength="30" required>
               </td>
            </tr>

            <tr class="champ">
               <td>
                  <label for="dateNaiss">Date de naissance <span class="red">*</span></label>
               </td>
               <td>
                  <input id="dateNaiss" type="date" name="dateNaiss" value="<?php echo $dateNaiss ?>" max="<?php echo date("Y-m-d"); ?>" required>
               </td>
            </tr>

            <tr class="champ">
               <td>
                  <label for="mail">Mail <span class="red">*</span> </label>
               </td>
               <td>
                  <input id="mail" type="email" name="mail" value="<?php echo $mail ?>" size="90" maxlength="90" required>
               </td>
            </tr>

            <tr class="champ">
               <td>
                  <label for="mdp">Mot de passe <span class="red">*</span> </label>
               </td>
               <td>
                  <input id="mdp" type="password" name="mdp" size="90" maxlength="90" required>
               </td>
            </tr>

            <tr class="champ" id="ville">
               <td>
                  <label for="rechercheVille">Ville <span class="red">*</span> </label>
               </td>
               <td>
                  <input id="rechercheVille" placeholder="Recherche par nom et/ou CP et/ou département et/ou région">

                  <select id="selectVille" name="ville" required disabled></select>

               </td>
            </tr>

            <tr class="champ" id="rue">
               <td>
                  <label for="rechercheRue">Rue <span class="red">*</span> </label>
               </td>
               <td>
                  <input id="rechercheRue" placeholder="Recherche par nom" disabled>

                  <select id="selectRue" name="rue" required disabled></select>

               </td>
            </tr>

            <tr class="champ" id="immeuble">
               <td>
                  <label for="rechercheImmeuble">Immeuble <span class="red">*</span> </label>
               </td>
               <td>
                  <input id="rechercheImmeuble" placeholder="Recherche par numéro" disabled>

                  <select id="selectImmeuble" name="immeuble" required disabled></select>

               </td>
            </tr>

            <tr class="champ" id="appartement">
               <td>
                  <label for="selectAppartement">Appartement <span class="red">*</span> </label>
               </td>
               <td>
                  <select id="selectAppartement" name="appartement" required disabled></select>
               </td>
            </tr>

            <tr class="champ">
               <td>
                  <label for="debutLocation">Date de début de location <span class="red">*</span></label>
               </td>
               <td>
                  <input id="debutLocation" type="date" name="debutLocation" value="<?php echo $debutLocation ?>" required>
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

<br /><a href="?uc=utilisateur&action=connexion">Déjà membre ? Connectez-vous ici !</a>