<?php

/** 
* fichier class.PdoProjet3A.inc.php
* contient la classe PdoProjet3A qui fournit 
* un objet pdo et des méthodes pour récupérer des données d'une BD
 */

 /** 
 * PdoProjet3A
 *
 * classe PdoProjet3A : classe d'accès aux données. 
 * Utilise les services de la classe PDO
 * pour l'application GsbParam
 * Les attributs sont tous statiques,
 * les 5 premiers pour la connexion
 *
* @package  projet-bdd-3a\util
* @date 16/12/2020
* @version 1
* @author Guillaume ELAMBERT
*/

class PdoProjet3A {

	/**
	* type et nom du serveur de bdd
	* @var string $serveur
	*/
    private static $serveur="mysql:host=localhost;";
      

    /**
     * Port du serveur de BDD
     * @var string $port
     */   
    private static $port = "port=3308;";
    
    /**
	* nom de la BD 
	* @var string $bdd
	*/
  	private static $bdd="dbname=3a_di_projet;";
    
    /**
	* nom de l'utilisateur utilisé pour la connexion 
	* @var string $user
	*/   		
  	private static $user="projet_bdd_3a";   
    
    /**
	* mdp de l'utilisateur utilisé pour la connexion 
	* @var string $mdp
	*/  		
  	private static $mdp="OTdWAeC4qiLaNmzT";
    
    /**
	* objet pdo de la classe Pdo pour la connexion 
	* @var string $monPdo
	*/ 		
	private static $monPdo=null;
	

	/**
	 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
	 * pour toutes les méthodes de la classe
	 */				
	private function __construct()
	{        
        try {
            $dbh = new PDO(PdoProjet3A::$serveur.PdoProjet3A::$port.PdoProjet3A::$bdd, PdoProjet3A::$user, PdoProjet3A::$mdp);
            $statement = $dbh->prepare("SET CHARACTER SET utf8");
            $statement->execute();
        } catch (PDOException $e) {
            echo "Erreur!: " . $e->getMessage() . "<br/>";
            die();
        }
	}


	/**
    * destructeur
    */
	public function _destruct(){
		PdoProjet3A::$monPdo = null;
	}


	/**
	 * Fonction statique qui crée l'unique instance de la classe
	 *
	 * Appel : $instancePdo = PdoProjet3A::getPdo();
	 * @return Pdo $monPdo l'unique objet de la classe Pdo
	 */
	public static function getPdo()
	{
		if(PdoProjet3A::$monPdo == null)
		{
			PdoProjet3A::$monPdo= new PdoProjet3A();
		}
		return PdoProjet3A::$monPdo;  
    }
}


?>
