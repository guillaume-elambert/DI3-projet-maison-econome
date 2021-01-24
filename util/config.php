<?php

// ** Paramètres MySQL ** //

/** Nom du dossier du projet **/
define( "FOLDER_NAME", "projet-bdd-3a");

/** URL de base du site */
define( "HOME", "http://localhost:8080/".FOLDER_NAME."/" );

/** Nom de la base de données */
define( "DB_NAME", "di_3a_projet" );

/** Nom d"utilisateur BDD */
define( "DB_USER", "projet_bdd_3a" );

/** Mot de passe BDD */
define( "DB_PASSWORD", "OTdWAeC4qiLaNmzT" );

/** MySQL hostname */
define( "DB_HOST", "localhost" );

/** MySQL port */
define( "DB_PORT", "3308" );



//On redirige tous les include vers la racine du projet
define( "PATH_TO_PROJECT", $_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR . FOLDER_NAME. DIRECTORY_SEPARATOR );
set_include_path(get_include_path() . PATH_SEPARATOR . PATH_TO_PROJECT);

?>