<nav id="navBar">

  <div class="logo">
    <a href="<?php echo $base; ?>">
      <img src="assets/logo.png" alt="logo">
      <span id="logoTitle">Les P<span class="red">D</span>G</span>
    </a>
  </div>

  <ul class="navigation">

    <li class="parent"><a class="link" href="<?php echo $base; ?>">Accueil</a></li>

    <li class="parent" id="actionsUser">
      <a class="link" href="#"><i class="fas fa-minus"></i>Mon espace<i class="fas fa-plus"></i></a>
      <ul class="subnavigation">
        <?php if (!isset($_SESSION["username"])) { ?>
          <li><a class="link" href="<?php echo $base; ?>?uc=utilisateur&action=connexion">Connexion</a></li>
          <li><a class="link" href="<?php echo $base; ?>?uc=utilisateur&action=inscription">Inscription</a></li>
        <?php } else { ?>

        <?php } ?>

      </ul>
    </li>

    <li class="parent"><a class="link" href="<?php echo $base; ?>?uc=info">À propos des développeurs</a></li>

  </ul>

</nav>