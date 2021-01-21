Projet BDD 3A
=============

## Table des matières

- [1. Introduction](#1-introduction)
- [2. Configuration](#2-configuration)
  - [2.1. Connexion à la base de données](#21-connexion-à-la-base-de-données)
    - [2.1.1. Template](#211-template)
  - [2.2. Définir les actions utilisateurs](#22-définir-les-actions-utilisateurs)
    - [2.2.1. Template](#221-template)
- [3. Application](#3-application)
  - [3.1. Script base de données](#31-script-base-de-données)
  - [3.2. Comptes utilisateurs](#32-comptes-utilisateurs)
  - [3.3. Etat d'avancement](#33-etat-davancement)
- [4. Contributeurs](#4-contributeurs)

<br>

## 1. Introduction

Application web qui permet de suivre la consommation des citoyens en diverses ressources (Ex: Electricité, gaz, eau,...etc) et l’´emission de substances nocives pour l’environnement (Ex: CO2).

<br>

## 2. Configuration

### 2.1. Connexion à la base de données

Le fichier ["util/config.php"](util/config.php) contient les paramètres fonctionnels de l'application :



#### 2.1.1. Template

~~~php
/** URL de base du site */
define( "HOME", "http://link-to-your-site.ext" );

/** Nom de la base de données */
define( "DB_NAME", "my-awesome-database" );

/** Nom d"utilisateur BDD */
define( "DB_USER", "my-handsome-user" );

/** Mot de passe BDD */
define( "DB_PASSWORD", "a-R3@L1y-#_STr0n6_Pa$\$w0R|)_" );

/** MySQL hostname */
define( "DB_HOST", "your-database-host" );

/** MySQL port */
define( "DB_PORT", "your-mysql-listening-port" );
~~~

<br>

### 2.2. Définir les actions utilisateurs

Le fichier ["util/configActionsTable.inc.php"](util/configActionsTable.inc.php) contient un tableau de tableaux qui permet de définir les attributs des actions des utilisateurs :



#### 2.2.1. Template
~~~php
$actions = array(
    'immeubles' => array(
        'actionIndex1-1' => array(
            'class'     =>    'a-pretty class',
            'onclick'   =>    'callAwesomeFunction(this);',
            'title'     =>    'This is a title'
        )
    ),
    'admin' => array(
        'actionIndex2-1' => array(
            'id'        =>    'myObject',
            'class'     =>    'an-really cool-class',
            'onclick'   =>    'alert("You clicked on object "+$this.id)',
            'title'     =>    'Alert object\'s id'
        )
    )
);
~~~

<br>

## 3. Application

### 3.1. Script base de données


Le fichier ["util/sql/3a_di_projet.sql"](util/sql/3a_di_projet.sql) contient le script de la base de donées avec quelques données pré-insérées.

### 3.2. Comptes utilisateurs

|   Identifiant   | Mot de passe |      Rôle      |
| :-------------: | :----------: | :------------: |
| `root@root.fr`  |    `root`    | Administrateur |
| `root1@root.fr` |    `root`    |  Utilisateur   |
| `root2@root.fr` |    `root`    |  Utilisateur   |


### 3.3. Etat d'avancement

- [ ] Actions utilisateur
    - [x] Inscription
    - [x] Connexion
    - [x] Modifier les informations
    - [ ] Gestion Locations
        - [x] Ajouter une location
        - [ ] Supprimer une location
    - [x] Gestion possessions
        - [x] Ajouter une possession
        - [x] Supprimer une possession
- [ ] Actions administrateur
    - [x] Supprimer un utilisateur
    - [x] Modifier les informations utilisateur
    - [ ] Modifier rôle utilisateur

<br>

## 4. Contributeurs

* [Paul Poitrineau](mailto:paul.poitrineau@etu.univ-tours.fr)
* [Loann Duplessis](mailto:loann.duplessis@etu.univ-tours.fr)
* [Guillaume Elambert](mailto:guillaume.elambert@yahoo.fr)