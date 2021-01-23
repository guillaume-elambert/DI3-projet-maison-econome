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

<?php include("vues/v_tableLocations.php"); ?>