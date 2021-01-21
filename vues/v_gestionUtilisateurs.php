<h1>Gestion des utilisateurs</h1>

<div id="divRechercheUtilisateur">
    <h2>Rechercher un utilisateur</h2>
    <div class="search">
        <input type="search" class="searchTerm" id="rechercheUtilisateur" placeholder="Chercher un utilisateur par nom et/ou prénom et/ou mail">
        <button type="submit" id="btnRechercheUser" class="searchButton">
            <i class="fa fa-search"></i>
        </button>
    </div>
</div>

<div id="divTableUtilisateurs">
    <?php
    include("vues/v_tableUtilisateurs.php");
    ?>
</div>