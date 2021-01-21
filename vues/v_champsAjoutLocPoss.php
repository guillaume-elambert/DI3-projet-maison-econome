<tr class="champ" id="situation">
    <td>
        Vous êtes <span class="red">*</span>
    </td>
    <td>
        <input type="radio" id="locataire" name="situationUser" value="locataire" checked>
        <label for="locataire">Locataire</label></br>

        <input type="radio" id="proprietaire" name="situationUser" value="proprietaire">
        <label for="proprietaire">Propriétaire</label>
    </td>
</tr>

<?php
include("vues/v_champsRechercheVilleRueImmeuble.php");
?>

<tr class="champ" id="appartement">
    <td>
        Appartement <span class="red">*</span>
    </td>
    <td>
        <select id="selectAppartement" name="appartement" required disabled></select>
    </td>
</tr>

<tr class="champ">
    <td>
        Date de début de <br />location/possession <span class="red">*</span>
    </td>
    <td>
        <input id="debutLocation" type="date" name="debutLocation" value="<?php echo $debutLocation ?>" required>
    </td>
</tr>