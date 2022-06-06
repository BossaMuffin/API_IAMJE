<?php 
/*
* 2015-2020 SAS COM O ZONE
*
* NOTICE OF LICENSE
*
*  @author Jean-Eudes Méhus <com@comozone.com>
*  @copyright  2015-2020 COM O ZONE
*  @license    Jean-Eudes Méhus property
*/

/**
 *Class OBdd_local -> Base de Données
 * Config des connexions à la base
 *Preparation des requetes
*/

class OBdd_connexion{

//instance PDO
	private $pdo ;

/* ---------------- CONSTRUCTEUR ----------------------------- 
* @value : none
* @return : none
ou 
* Param: str, str, str, str / Données de connexion
* @Return: NULL / modifie les constantes de linstance PDO
*/
   function  __construct()
   {
   } // fin construct

/* ------------------- CLONE ----------------------- 
* Empêche le clonage
* @value : none
* @return : none
*/
   private function  __clone()
   {
   }


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
				$pdo = new PDO('mysql:YOURHOST=localhost;dbname=YOURDB', 'root', 'PASSWORD' array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));
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






