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

<tr class="champ" id="ville">
    <td>
        Ville <span class="red">*</span>
    </td>
    <td>
        <input id="rechercheVille" type="search" placeholder="Recherche par nom et/ou CP et/ou département et/ou région">

        <select id="selectVille" name="ville" required disabled></select>

    </td>
</tr>

<tr class="champ" id="rue">
    <td>
        Rue <span class="red">*</span>
    </td>
    <td>
        <input id="rechercheRue" type="search" placeholder="Recherche par nom" disabled>

        <select id="selectRue" name="rue" required disabled></select>

    </td>
</tr>

<tr class="champ" id="immeuble">
    <td>
        Immeuble <span class="red">*</span>
    </td>
    <td>
        <input id="rechercheImmeuble" type="search" placeholder="Recherche par numéro" disabled>

        <select id="selectImmeuble" name="immeuble" required disabled></select>

    </td>
</tr>

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