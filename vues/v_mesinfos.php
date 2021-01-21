<fieldset class="sameAsForm">
    <legend>
        <h3>Vos informations personnelles </h3>
    </legend>

    <table>
        <tbody>

            <tr class="champ">
                <td>
                    <h4>Adresse mail</h4>
                </td>

                <td>
                    <?php echo $donneesUser['mail']; ?>
                </td>
            </tr>

            <tr class="champ">
                <td>
                    <h4>Nom</h4>
                </td>

                <td>
                    <?php echo $donneesUser['nomUtilisateur']; ?>
                </td>
            </tr>

            <tr class="champ">
                <td>
                    <h4>Prénom</h4>
                </td>

                <td>
                    <?php echo $donneesUser['prenomUtilisateur']; ?>
                </td>
            </tr>

            <tr class="champ">
                <td>
                    <h4>Date de naissance</h4>
                </td>
                <td>
                    <?php echo $donneesUser['dateNaissance'] == "" ? "NaN"  : strftime("%d %b %Y", strtotime($donneesUser['dateNaissance'])); ?>
                </td>
            </tr>

        </tbody>
    </table>
</fieldset>

</br></br>

<a class='styledLink' href='?uc=<?php echo $uc; ?>&action=modifier-mes-infos'>
    Modifier mes informations
</a>