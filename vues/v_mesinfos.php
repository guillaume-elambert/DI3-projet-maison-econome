<h1> Bienvenue sur votre profil </h1>
<?php
if (isset($_SESSION['user'])) {
    $donneesUser = $pdo->getUserInfos($_SESSION['user']);

    //var_dump($donneesUser);
    echo "<div id='utilisateur" . $donneesUser['mail'] . "'>" . $donneesUser['nomUtilisateur'] . " (" . $donneesUser['prenomUtilisateur'] . ")</div>";

} else {
    echo "vous non connecté";
}
?>