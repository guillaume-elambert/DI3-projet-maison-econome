<form id="formInscription" method="POST" action="?uc=<?php echo $uc; ?>&action=confirmerInscription">
   <fieldset>
      <legend>
         <h3>Inscription</h3>
      </legend>

      <table>
         <tbody>

            <tr class="champ">
               <td>
                  Nom <span class="red">*</span>
               </td>
               <td>
                  <input id="nom" type="text" name="nom" value="<?php echo $nom ?>" maxlength="50" required>
               </td>
            </tr>

            <tr class="champ">
               <td>
                  Prénom <span class="red">*</span>
               </td>
               <td>
                  <input id="prenom" type="text" name="prenom" value="<?php echo $prenom ?>" maxlength="50" required>
               </td>
            </tr>

            <tr class="champ">
               <td>
                  Date de naissance <span class="red">*</span>
               </td>
               <td>
                  <input id="dateNaiss" type="date" name="dateNaiss" value="<?php echo $dateNaiss ?>" max="<?php echo date("Y-m-d"); ?>" required>
               </td>
            </tr>

            <tr class="champ">
               <td>
                  Mail <span class="red">*</span>
               </td>
               <td>
                  <input id="mail" type="email" name="mail" value="<?php echo $mail ?>" maxlength="50" required>
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

            <tr class="champ">
               <td>
                  Vérification mot de passe <span class="red">*</span>
               </td>

               <td>
                  <input id="verifMdp" type="password" name="verifMdp" maxlength="255" placeholder="Re-saisissez votre mot de passe">
               </td>
            </tr>

            <?php include("vues/v_champsAjoutLocPoss.php"); ?>

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

<br /><a href="?uc=<?php echo $uc; ?>&action=connexion">Déjà membre ? Connectez-vous ici !</a>