<?php

//Entrée : Il y a des pièces dans l'appartement
//      => on affiche un lien pour ajouter un appareil
if (!empty($pieces)) {
    $idImmeuble = $pieces[0]['idImmeuble'];
    $idAppartement = $pieces[0]['idAppartement'];
    echo "<a href='" . HOME . "?uc=espace&action=ajouter-un-appareil&immeuble=$idImmeuble&appartement=$idAppartement'>Ajouter un appareil à l'appartement</a>";
}

?>
<?php
if (!empty($pieces)) {
    echo '<select id="selectPieceListeAppareils">';

    $valueDefault = "immeuble-$idImmeuble-appartement-$idAppartement";

    echo "<option value='$valueDefault'>--- Toutes les pièces de l'appartement ---</option>";
    foreach ($pieces as $unePiece) {
        echo '<option value="immeuble-' . $unePiece['idImmeuble'] . '-appartement-' . $unePiece['idAppartement'] . '-piece-' . $unePiece['idPiece'] . '">' . $unePiece['nomPiece'] . '</option>';
    }
    echo '</select>';
}
?>


<div id="divTableListeAppart">
    <?php include("vues/v_tableAppareils.php"); ?>
</div>