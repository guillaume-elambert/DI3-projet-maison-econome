<h1>Locations et possession</h1>

</br></br>

<h2> Vos immeubles :</h2>

<?php include("vues/v_tablePossessions.php"); ?>

</br>
<a class='styledLink' href='?uc=<?php echo $uc; ?>&action=ajoutLocPoss'>
    Ajouter une location/possession
</a>

</br></br></br>
<h2> Vos Locations : </h2>

<div id='divLocations'>
    <?php include("vues/v_tableLocations.php"); ?>

    <div id='divConsommationAppart' class="overlay">

        <div class="popup">
            <h2 id="titrePopUpConsommation"></h2>
            <a class="close fa fa-times"></a>
            <div class="popup-content"></div>
        </div>

    </div>