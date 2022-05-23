<?php 
//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste à répartir les fonctions dans plusieurs classes et renomer correctement les instances
/* SCHEMA */
/*
    abstract class A{
        protected $bdd;

        protected function __construct(){
             $this -> bdd = new bdd();
        }
    }

    class B extends A{
        function __construct(){
            parent::__construct();
        }

       function select($query){
           $this -> bdd -> query($query);
        }
    }
*/
class OBdd extends OBdd_connexion {

    function __CONSTRUCT(){
        parent::__CONSTRUCT();
	} // fin construct 

/* REQUETE --------------------------- REQUETE 
 * requete
 @Param: REQ STATEMENT 
 @Return: 
*/
	protected function demande($requete){
		//$req = $this->getPDO()->query('SELECT * FROM events');
		$result = $this->getPDO()->query($requete);
		return $result ;
	}

/* ----------------------------------FIN------------------------------------- */
/* Fin Class OBdd_connexion */
}



