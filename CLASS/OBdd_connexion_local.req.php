<?php 

/**
 *Class OBdd_local -> Base de Données
 * Config des connexions à la base
 *Preparation des requetes
*/

class OBdd_connexion{
//instance PDO
	private $pdo ;


/* CONSTRUCTEUR-------------------- CONSTRUCTEUR
 *
 @Param: str, str, str, str / Données de connexion
 @Return: NULL / modifie les constantes de linstance PDO
*/


 // LOCAL BDD PROD
	public function __CONSTRUCT(){
	}

//------------------------------------


/* GENERE PDO---------------------- GENERE PDO
 * cree une instance PDO de la base de donnee demandee
 @Param: NULL 
 @Return: NULL 
*/

// BDD LOCAL
	protected function getPDO(){
		// Accesseur BDD 
		if($this->pdo === null){
			try{ 
				$pdo = new PDO('mysql:host=YOURDBHOS;dbname=YOURDBNAME', 'root', 'YOURPASSWORD', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));
				//die(PDO::ATTR_ERRMODE);
				//Retour derreur syntaxe
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				//creation dune instance pdo private en interne
				$this->pdo = $pdo;

				
			}
			catch(PDOException $e){
				//retour derreur connexion
				exit('BDD fait la gueule');
			}
		}
		return $this->pdo ;


	}
// ------------------------------------



/* ----------------------------------FIN------------------------------------- */
/* Fin Class OBdd_connexion */
}






