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
  - [3.1. Scripts de la base de données](#31-scripts-de-la-base-de-données)
  - [3.2. Comptes utilisateurs (non exhaustif)](#32-comptes-utilisateurs-non-exhaustif)
  - [3.3. Villes possédant des rues, immeubles, appartements et pièces (non exhaustif)](#33-villes-possédant-des-rues-immeubles-appartements-et-pièces-non-exhaustif)
  - [3.4. Etat d'avancement](#34-etat-davancement)
- [4. Contributeurs](#4-contributeurs)
<br>

## 1. Introduction

Application web qui permet de suivre la consommation des citoyens en diverses ressources (Ex: Electricité, gaz, eau,...etc) et l’émission de substances nocives pour l’environnement (Ex: CO2).

<br>

## 2. Configuration

### 2.1. Connexion à la base de données

Le fichier [`util/config.php`](util/config.php) contient les paramètres fonctionnels de l'application.
L'utilisateur par défaut de la base de données ainsi que son mot de passe sont renseignés dans le fichier [`util/sql/di_3a_projet_full.sql`](util/sql/di_3a_projet_full.sql), pensez à le modifier si vous souhaitez utiliser un autre utilisateur.



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

Le fichier [`util/configActionsTables.inc.php`](util/configActionsTables.inc.php) contient un tableau de tableaux qui permet de définir les attributs des actions des utilisateurs :



#### 2.2.1. Template
~~~php
$actions = array(
    'possessions' => array(
        'actionIndex1-1' => array(
            'class'         =>    'a-pretty class',
            'onclick'       =>    'callAwesomeFunction(this);',
            'title'         =>    'This is a title'
        )
    ),
    'locations' => array(
        'actionIndex2-1' => array(
            'attribute1'    =>    'something1',
        ),
        'actionIndex2-2' => array(
            'attribute2'    =>    'whatYouWant',
            'attribute3'    =>    'theValueOfAttribute3',
            'attribute4'    =>    'itCanBeAnythingAndEverything'
        ),
    ),
    'appareils' => array(
        'actionIndex3-1' => array(
            'class'     =>    'fas fa-linux',
            'title'     =>    'It\'s a cool icon isn\'t it?'
        )
    ),
    'admin' => array(
        'actionIndex4-1' => array(
            'id'            =>    'myObject',
            'class'         =>    'an-really cool-class',
            'onclick'       =>    'alert("You clicked on object "+$this.id)',
            'title'         =>    'Alert object\'s id'
        )
    )
);
~~~

<br>

## 3. Application

### 3.1. Scripts de la base de données

Le fichier [`util/sql/di_3a_projet_full.sql`](util/sql/di_3a_projet_full.sql) contient le script de de la base de données (structure + données + utilisateur).
Le fichier [`util/sql/di_3a_projet_struct.sql`](util/sql/di_3a_projet_struct.sql) contient le script de création de la structure de la BDD. Il peut être généré/mit à jour par un compte administrateur via le dashboard.
Le fichier [`util/sql/di_3a_projet_data.sql`](util/sql/di_3a_projet_data.sql) contient un set de données. Il peut être généré/mit à jour par un compte administrateur via le dashboard.

### 3.2. Comptes utilisateurs (non exhaustif)

|   Identifiant   | Mot de passe |      Rôle      |
| :-------------: | :----------: | :------------: |
| `root@root.fr`  |    `root`    | Administrateur |
| `root1@root.fr` |    `root`    |  Utilisateur   |
| `root2@root.fr` |    `root`    |  Utilisateur   |
| `root3@root.fr` |    `root`    |  Utilisateur   |
| `root4@root.fr` |    `root`    |  Utilisateur   |

Pour plus de comptes, se référer à la base de données.
<br/>

### 3.3. Villes possédant des rues, immeubles, appartements et pièces (non exhaustif)

| idVille |     Nom de la ville      |  CP   |
| :-----: | :----------------------: | :---: |
|    1    |           Ozan           | 01190 |
|    2    |  Cormoranche-sur-Saône   | 0129  |
|    3    |          Plagne          | 01130 |
|    4    |         Tossiat          | 01250 |
|   ...   |           ...            |  ...  |
|   96    |   Le Petit-Abergement    | 01260 |
|  36700  | Saint-Pierre-et-Miquelon | 97500 |
|  25550  |         Pradeaux         | 63500 |

Pour plus de villes, se référer à la base de données.
<br/>
<details>
<summary>Requête SQL</summary>

Executer cette requête vous renverra la liste des villes qui possèdent des rues, des immeubles, des appartements et des pièces.

~~~sql
SELECT v.*
FROM ville v
INNER JOIN rue r
ON r.idVille = v.idVille
INNER JOIN immeuble i
ON i.idRue = r.idRue
INNER JOIN appartement a
LEFT JOIN piece p
ON  a.idImmeuble = p.idImmeuble
AND a.idAppartement = p.idAppartement
WHERE idPiece IS NOT NULL
GROUP BY v.idVille;
~~~

</details>
<br/>

### 3.4. Etat d'avancement

- [x] Actions utilisateur
    - [x] Inscription
    - [x] Connexion
    - [x] Modifier les informations
    - [x] Gestion Locations
        - [x] Ajouter une location
        - [x] Supprimer une location
        - [x] Afficher la consommation de l'appartement
        - [x] Appareils
          - [x] Afficher la liste des appareils
          - [x] Ajouter des appareils
          - [x] Modification des appareils
          - [x] Supprimer des appareils
    - [x] Gestion possessions
        - [x] Ajouter une possession
        - [x] Supprimer une possession
- [x] Actions administrateur
    - [x] Supprimer un utilisateur
    - [x] Modifier les informations utilisateur
    - [x] Modifier rôle utilisateur
    - [x] Ajouter un bâtiment
    - [x] Ajouter un appartement

<br>

## 4. Contributeurs

* [Paul Poitrineau](mailto:paul.poitrineau@etu.univ-tours.fr)
* [Loann Duplessis](mailto:loann.duplessis@etu.univ-tours.fr)
* [Guillaume Elambert](mailto:guillaume.elambert@yahoo.fr)