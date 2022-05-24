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


/* ---------------- CONSTRUCTEUR ----------------------------- 
* @value : 
* @return : PARENT
*/
    function __CONSTRUCT()
    {
        parent::__CONSTRUCT();
	} // fin construct


/* ------------------- CLONE ----------------------- 
* Empêche le clonage
*
* @value : none
* @return : none
*/
	private function  __clone()
	{
	} // fin clone


/* REQUETE --------------------------- REQUETE 
 * requete
 @Param: REQ STATEMENT 
 @Return: 
*/
 /*
	public function demandeObjet($requete){
		//$req = $this->getPDO()->query('SELECT * FROM events');
		$result = $this->getPDO()->query($requete);
		$datas = $result->fetchAll(PDO::FETCH_OBJ);
		return $datas ;
	}
*/
	protected function demande($requete){
		//$req = $this->getPDO()->query('SELECT * FROM events');
		$result = $this->getPDO()->query($requete);
		return $result ;
	}

/*
	function test($arg) {
		global $BDD;
		if($req = $BDD->demande('SELECT id
						FROM tab 
						WHERE cpx="'.$arg.'"'))
		{

			$r = array();
			$i_doublon = 0 ;

			while ($d = $req->fetch(PDO::FETCH_OBJ)){
				$i_doublon++;
				$r[$i_doublon]['id'] = $d->id ;

			}

			if ($i_doublon == 0){
				//la cle nexiste pas
				$reponse[0] = true ;

			}
			else if($i_doublon == 1){$reponse["erreur"] = 4 ;}
			else if($i_doublon > 1){$reponse["erreur"] = 5 ;}

		}else{$reponse["erreur"] = 3 ;}

			
	return $reponse;
	}
	// ------------------------------------

*/



/* ----------------------------------FIN------------------------------------- */
/* Fin Class OBdd_connexion */
}



