<?php

$action = $_REQUEST['action'];

switch ($action) {        

    case "equipe":
        include("vues/v_apropos.php");
        break;

    case "avancement":
        include("vues/v_apropos2.php");
        break;

    default:
        $redirect = "?uc=$uc&action=equipe";
        break;
}
