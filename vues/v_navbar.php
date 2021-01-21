<nav id="navBar">

  <div class="logo">
    <a href="<?php echo HOME; ?>">
      <img src="assets/logo.png" alt="logo">
      <span id="logoTitle">Les P<span class="red">D</span>G</span>
    </a>
  </div>

  <ul class="navigation">

    <li class="parent"><a class="link" href="<?php echo HOME; ?>">Accueil</a></li>

    <li class="parent parentWithSubNav" id="actionsUser">
      <a class="link"><i class="fas fa-minus"></i>
        <?php
        if (isset($_SESSION['user'])) {
          $infos = $pdo->getUserInfos($_SESSION['user']);
          echo $infos['prenomUtilisateur'] . " " . $infos['nomUtilisateur'];
        } else {
          echo "Mon espace";
        }
        ?>
        <i class="fas fa-plus"></i></a>
      <ul class="subnavigation">
        <?php
        if (isset($_SESSION['user'])) {
        ?>
          <li><a class="link" href="<?php echo HOME; ?>?uc=espace&action=dashboard">Mon espace</a></li>
          <li><a class="link" href="<?php echo HOME; ?>?uc=utilisateur&action=connexion">Déconnexion</a></li>
        <?php
        } else {
        ?>
          <li><a class="link" href="<?php echo HOME; ?>?uc=utilisateur&action=connexion">Connexion</a></li>
          <li><a class="link" href="<?php echo HOME; ?>?uc=utilisateur&action=inscription">Inscription</a></li>
        <?php } ?>

      </ul>
    </li>

    <li class="parent parentWithSubNav" id="actionsAPropos">
      <a class="link">
        <i class="fas fa-minus"></i>À propos<i class="fas fa-plus"></i>
      </a>

      <ul class="subnavigation">
        <li><a class="link" href="<?php echo HOME; ?>?uc=info&action=equipe">Les développeurs</a></li>
        <li><a class="link" href="<?php echo HOME; ?>?uc=info&action=avancement">Le site</a></li>
      </ul>
    </li>
  </ul>

</nav>