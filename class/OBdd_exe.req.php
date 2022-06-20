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

//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de l'erreur de cette fonction)
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


class OBdd extends OBdd_connexion
{

// Propriétés
    public $p_Ttypes = ["VARCHAR", "INT", "FLOAT"] ;
    public $p_TnewTressources = array() ;
    public $p_TnewTarchives = array() ;
    public $p_Tprefixes = [	"cpx" 	=> "C_",
    						"mat" 	=> "R_",
    						"arch" 	=> "A_",
    						"outs" 	=> "O_"	] ;
// constantes


/* ---------------- CONSTRUCTEUR ----------------------------- 
* @value : utilise l'instance $BDD 
* @return : PARENT
*/
    function __CONSTRUCT( )
    {
        parent::__CONSTRUCT( ) ;
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
	protected function demande( $requete )
	{
		//$req = $this->getPDO()->query('SELECT * FROM events');
		$result = $this->getPDO()->query( $requete ) ;
		return $result ;
	}


/* SHOW TABLES --------------------------- SHOW TABLES
 * requete
 @Param: aucun
 @Return: $reponse[0] = temoin ; $reponse["val"] = contient la liste des tables présente dans la BDD
*/
	function tab_show() 
	{
		global $BDD;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"] = array() ;
		$l_Treponse[0] = false ;

		if ( $req = $BDD->demande( 'SHOW TABLES' ) )
		{
			$l_Treponse[0] = true ;
			$l_Treponse["val"] = $req->fetchAll(PDO::FETCH_COLUMN) ;
		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}
			
		return $l_Treponse ;

	// Fin SHOW TABLES
	}
	// ------------------------------------

/* SHOW COLONNES --------------------------- SHOW COLUMNS
 * requete
 @Param: aucun
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = contient la liste des tables présente dans la BDD
*/
	function col_show( $Ctab_nom ) 
	{
		global $BDD;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"] = array() ;
		$l_Treponse[0] = false ;

		if ( $req = $BDD->demande( 'SHOW COLUMNS FROM ' . $Ctab_nom ) )
		{
			$l_Treponse[0] = true ;
			$l_Treponse["val"] = $req->fetchAll(PDO::FETCH_COLUMN) ;
		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}
			
		return $l_Treponse ;

	// Fin SHOW COLUMNS
	}
	// ------------------------------------


/* NEW TABLE --------------------------- NEW TABLE
 * On cree une table à partir d'un nom, elle n'aura que la colonne id
 @Param: Nom de la table à créer
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = chaine vide
*/
	function tab_create( $Ctab_nom ) 
	{
		global $BDD ;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;

		// on check si une table existe avec ce nom
		$Tnoms = $this->tab_show( ) ;
		if ( ! in_array( $Ctab_nom, $Tnoms["val"] ) )
		{
			// on ne crée la table que si le nom est disponnible
			if ( $req = $BDD->demande( 'CREATE TABLE ' . $Ctab_nom . ' ( id INT NOT NULL AUTO_INCREMENT PRIMARY KEY )' ) )
			{
				$l_Treponse[0] = true ;
			}
			else
			{
				$l_Treponse["err"] = 2 ;
			}

		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}

		return $l_Treponse ;

	// Fin CREATE TABLE
	}
	// ------------------------------------


/* DELETE TABLE --------------------------- DROP TABLE
 * requete
 @Param: le nom de la table à détruire
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = ""
*/
	function tab_drop( $Ctab_nom ) 
	{
		global $BDD ;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;

		// on check si une table existe avec ce nom
		$Tnoms = $this->tab_show( ) ;
		if ( in_array( $Ctab_nom, $Tnoms["val"] ) )
		{
			// on ne detruit la table que si le nom existe
			if ( $req = $BDD->demande( 'DROP TABLE ' . $Ctab_nom ) )
			{
				$l_Treponse[0] = true ;
			}
			else
			{
				$l_Treponse["err"] = 2 ;
			}

		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}

		return $l_Treponse ;

	// Fin DROP TABLE
	}
	// ------------------------------------

/* BUILD TYPE --------------------------- BUILD TYPE
 * requete
 @Param: le nom du type à construire, la taille du type
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = ""
*/
	function build_type( $Ctype, $Nsize ) 
	{
		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"] = "" ;
		$l_Treponse[0] = false ;

		// on check si le type existe et si la taille est numeric
		if ( in_array( $Ctype, $this->p_Ttypes ) )
		{
			if ( is_int( $Nsize ) )
			{
			// la construction est differente en fonction du type de Champ SQL 
				$l_Treponse[0] = true ;

				if ($Ctype == "VARCHAR")
				{
					$l_Treponse["val"] = $Ctype . "(" . $Nsize . ")" ;
				}
				else 
				{
					$l_Treponse["val"] = $Ctype ; 
				} 
			}
			else
			{
				$l_Treponse["err"] = 2 ;
			}
		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}

		return $l_Treponse ;

	// Fin CREATE TYPE
	}
	// ------------------------------------



/* ADD COLONNE --------------------------- ADD COLONNE
 * requete
 @Param: le nom de la table à modifier, la colonne à ajouter, le type de colone, la taille du champs
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = ""
*/
	function tab_add_col( $Ctab_nom, $Ccol_nom, $Ccol_type, $Ntype_size ) 
	{
		global $BDD ;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;

		// on check si une table existe avec ce nom
		$Tnoms = $this->tab_show( ) ;
		if ( in_array( $Ctab_nom, $Tnoms["val"] ) )
		{

			// on check si une colonne existe avec ce nom
			$Tnoms = $this->col_show( $Ctab_nom ) ;
			if ( ! in_array( $Ccol_nom, $Tnoms["val"] ) )
			{
				// on construit le type du champs pour la nouvelle colonne (check si le type existe)
				$l_Ccoltype = $this->build_type( $Ccol_type, $Ntype_size ) ;
				if ( $l_Ccoltype[0] )
				{
					// on ne cree la colonne que si le nom est disponnible
					if ( $req = $BDD->demande( 'ALTER TABLE ' . $Ctab_nom . ' ADD ' . $Ccol_nom . ' ' . $l_Ccoltype["val"] ) )
					{
						$l_Treponse[0] = true ;
					}
					else
					{
						$l_Treponse["err"] = 4 ;
					}

				}
				else
				{
					$l_Treponse["err"] = 3 ;
				}

			}
			else
			{
				$l_Treponse["err"] = 2 ;
			}

		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}

		return $l_Treponse ;

	// Fin ADD COL TABLE
	}
	// ------------------------------------

// ------------------------------------------------------------------------------------------
// -------------------------------------- RESSOURCES -----------------------------------------
// ------------------------------------------------------------------------------------------

/* TAB RESSOURCE CREATE --------------------------- TAB CREATE RESSOURCE
 * requete qui permet de verifier l'existence d'une table spécifique à un type de ressource 
 * ou d'en créer une nouvelle si besoin 
 * le but est simple d'éviter de recommencer inutilement se check puisque que la table sera créée une fois (au début) 
 * puisque exploitée de très nombreuses fois
 @Param: la ressource à enregistrer -> son type est analysé, une table et les colonne sont crées si besoin
 * la colonne et la table
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = "void"
*/
	function tab_ressource_create( $CtabName, $Ccol )
	{
		global $BDD ;
		global $BFUNC ;

		$BDD->tab_create( $CtabName ) ;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;
		// on check si une colonne existe avec ce nom
		$Tcols = $BDD->col_show( $CtabName ) ;

		if ( ! in_array( $Ccol, $Tcols["val"] ) )
		{
			// on recupere le type SQL equivalent au type de la ressource
			foreach ($BFUNC->p_Ttypes as $key => $Ttype) 
			{
				if ( $CtabName == $this->p_Tprefixes["mat"] . $Ttype["name"]  )
				{
					$l_Ccol_type = $Ttype["bdd"] ;
					$l_Ntype_size = $Ttype["size"] ;
				}
			}
			// $Ctab_nom, $Ccol_nom, $Ccol_type, $Ntype_size 
			// on crée un champ "ressource" si besoin pour enregistrer la ressource 
			$l_TnewCol = $BDD->tab_add_col( $CtabName, $Ccol, $l_Ccol_type, $l_Ntype_size ) ;
			
			if ( ! $l_TnewCol[0]  )
			{
				$l_Treponse[0] = true ;
				// propriété $BDD qui garde en memoire le check + create table colonne
				array_push( $this->p_TnewTressources, $CtabName ) ;
			}
			else
			{
				$l_Treponse["err"] = 2 ;
			}
		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}
	// Fin tab ressource create
	}
	// ------------------------------------


/* CHECK RESSOURCE --------------------------- CHECK RESSOURCE
 * requete qui permet de savoir si une ressource est dejà enregistrer dans une table qui porte le nom de son type
 @Param: la ressource à enregistrer ->
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = "void"
*/

	function check_ressource( $ressource ) 
	{
		global $BDD ;
		global $BFUNC ;

		$l_Ccol = $this->p_Tprefixes["cpx"] . "valeur" ;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"]["tab"] = "" ;
		$l_Treponse["val"]["col"] = "" ;		
		$l_Treponse[0] = false ;

		// on  recupere le type de la ressource à enregistrer
		// ATTENTION AU TYPE EXOTIQUE (NON SCALABLE -> une type SQL "none" !!!!!!!!!! )   XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxxx
		$l_Ttype = $BFUNC->get_type( $ressource ) ; 
		
		// on cree une table spécifique à ce type de ressource s'il elle n'existe pas déjà
		$l_CtabName = $this->p_Tprefixes["mat"] . $l_Ttype["subval"] ;
		
		$l_Treponse["val"]["tab"] = $l_CtabName ;
		$l_Treponse["val"]["col"] = $l_Ccol ;

		// propriété $BDD qui garde en memoire le check + create table colonne
		if ( ! in_array( $l_CtabName, $this->p_TnewTressources ) )
		{
			// le type de $ressource est analysé, une table et les colonne sont crées si besoin
			$BDD->tab_ressource_create( $l_CtabName, $l_Ccol ) ;	
		}
		

		// VERIF dans la base 
		if( $l_Oreq = $BDD->demande( 'SELECT id
						FROM ' . $l_CtabName . ' 
						WHERE ' . $l_Ccol .' ="' . $ressource . '"' )  )
		{

			$l_Tr = array();
			$l_i_doublon = 0 ;

			while ($l_Od = $l_Oreq->fetch(PDO::FETCH_OBJ)){
				$l_i_doublon++;
				$l_Tr[$l_i_doublon]['id'] = $l_Od->id ;

			}

			if ( $l_i_doublon == 1 or $l_i_doublon > 1 )
			{
				//la cle existe
				$l_Treponse[0] = true ;
				// on pourra enregistrer la ressource
				if ( $l_i_doublon == 1 )
				{
					$l_Treponse["err"] = 3 ;
				}
				else if ( $l_i_doublon > 1 )
				{
					$l_Treponse["err"] = 2 ;
				}
			}
			

		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}

	// Fin Check Ressource
		return $l_Treponse;
	}

/* PUSH RESSOURCE --------------------------- PUSH RESSOURCE
 * requete qui permet de d'enregistrer une nouvelle ressource dans la BDD
 * on ne recommence pas les verifications de table et colonne 
 * car on considère que cette fonction n'est exploitable qu'une fois la ressource check methode appelée
 @Param: la ressource à enregistrer ->
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = "void"
*/

	function push_ressource( $ressource, $tab, $col ) 
	{
		global $BDD ;
		global $BFUNC ;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;

		if ( $BDD->demande( 'INSERT INTO ' . $tab . ' (' . $col . ') VALUES (' . $ressource . ')' ) )
		{
			$l_Treponse[0] = true ;
		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}

	// Fin push Ressource
		return $l_Treponse;
	}
	// -------------

// ------------------------------------------------------------------------------------------
// -------------------------------------- ARCHIVES -----------------------------------------
// ------------------------------------------------------------------------------------------
//XXXXXXXXXXXXXXXXXXXXXXXXXX 99999999999999999999999999

/* TAB ARCHIVE CREATE --------------------------- TAB CREATE ARCHIVE
 * requete qui permet de verifier l'existence d'une table spécifique au type d'objectif de la ressource 
 * ou d'en créer une nouvelle si besoin 
 * le but est simple d'éviter de recommencer inutilement se check puisque que la table sera créée une fois (au début) 
 * puisque exploitée de très nombreuses fois
 * On pourrrait vérifier le type d'objectif et de ressource de l'archive en amont et l'enregistré dans un champ de l'archive 99999999XXXXXXXXXXXXXXXXXXX
 @Param: l'archive à enregistrer -> le type de son objectif est analysé, une table et les colonne sont crées si besoin
 * la colonne et la table
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = "void"
*/

 // XXXX 9999999999999999999 FAIRE EN SORTE QUE LE TYPE SOIT DETERMINÉ EN FONCTION DE LA DONN2E A ENREGISTREE -> TEST A FAIRE SUR L'ARCHIVE EN AMONT !!!!!
 function tab_archive_A_create( $CtabName, $Tcol )
	{
		global $BDD ;
		global $BFUNC ;

		$l_jeton = true ;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;

		$l_TnewTab = $BDD->tab_create( $CtabName ) ;
		if ( ! $l_TnewTab[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 1 ;
		}
		
		$l_TnewCol = $BDD->tab_add_col( $CtabName, $Tcol["id"], "VARCHAR", 255 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 2 ;
		}

		$l_TnewCol = $BDD->tab_add_col( $CtabName, $Tcol["obj"], "FLOAT", 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 3 ;
		}

		$l_TnewCol = $BDD->tab_add_col( $CtabName, $Tcol["mat"], "FLOAT", 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 4 ;
		}

		$l_TnewCol = $BDD->tab_add_col( $CtabName, $Tcol["outs"], "VARCHAR", 40 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 5 ;
		}
// 999999999999999999999  ATTENTION : la taille de la donnée grandit vite, mieux vaux un texte ou tinytext 
		// ... mais "text" n'est pas compris par la méthode build type  ------------  99999999999 XXXXXXXXXx
		$l_TnewCol = $BDD->tab_add_col( $CtabName, $Tcol["seq"], "VARCHAR", 255 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 6 ;
		}

		$l_TnewCol = $BDD->tab_add_col( $CtabName, $Tcol["result"], "FLOAT", 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 7 ;
		}

		$l_TnewCol = $BDD->tab_add_col( $CtabName, $Tcol["dist"], "FLOAT", 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 8 ;
		}

		$l_TnewCol = $BDD->tab_add_col( $CtabName, $Tcol["ratio"], "FLOAT", 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 9 ;
		}

		$l_TnewCol = $BDD->tab_add_col( $CtabName, $Tcol["delais"], "FLOAT", 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 10 ;
		}

		$l_TnewCol = $BDD->tab_add_col( $CtabName, $Tcol["compt"], "INT", 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"] = 11 ;
		}

		if ( $l_jeton  )
		{
			$l_Treponse[0] = true ;
			// propriété $BDD qui garde en memoire le check + create table colonne
			array_push( $this->p_TnewTarchives, $CtabName ) ;
		}

		return $l_Treponse ;

	// Fin tab ressource create
	}
	// ------------------------------------


/* CHECK ARCHIVE --------------------------- CHECK ARCHIVE
 * requete qui permet de savoir si une archive est dejà enregistrer dans une table qui porte le nom du type de son objectif
 @Param: l'archive à enregistrer ->
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = "void"
*/

	function check_archive_A( $Tarchive ) 
	{
		global $BDD ;
		global $BFUNC ;

		$l_Tcol["id"] = $this->p_Tprefixes["cpx"] . "id" ;
		$l_Tcol["obj"] = $this->p_Tprefixes["cpx"] . "objectif" ;
		$l_Tcol["mat"] = $this->p_Tprefixes["cpx"] . "matieres" ;
		$l_Tcol["outs"] = $this->p_Tprefixes["cpx"] . "outils" ;
		$l_Tcol["seq"] = $this->p_Tprefixes["cpx"] . "sequence" ;
		$l_Tcol["result"] = $this->p_Tprefixes["cpx"] . "resultat" ;
		$l_Tcol["dist"] = $this->p_Tprefixes["cpx"] . "distance" ;
		$l_Tcol["ratio"] = $this->p_Tprefixes["cpx"] . "precision" ;
		$l_Tcol["delais"] = $this->p_Tprefixes["cpx"] . "delais" ;
		$l_Tcol["compt"] = $this->p_Tprefixes["cpx"] . "compteur" ;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"]["tab"] = "" ;
		$l_Treponse["val"]["col"] = "" ;		
		$l_Treponse[0] = false ;

		// on  recupere le type de la ressource à enregistrer
		// ATTENTION AU TYPE EXOTIQUE (NON SCALABLE -> une type SQL "none" !!!!!!!!!! )   XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxxx
		$l_Ttype = $BFUNC->get_type( $Tarchive["objectif"]["value"] ) ; 

		// on cree une table spécifique à ce type de ressource s'il elle n'existe pas déjà
		$l_CtabName = $this->p_Tprefixes["arch"] . $l_Ttype["subval"] ;
		
		$l_Treponse["val"]["tab"] = $l_CtabName ;
		$l_Treponse["val"]["col"] = $l_Tcol ;

		// propriété $BDD qui garde en memoire le check + create table colonne
		if ( ! in_array( $l_CtabName, $this->p_TnewTarchives ) )
		{
			// le type de $Tarchive["objectif"]["value"] est analysé, une table et les colonnes sont crées si besoin
			$new = $BDD->tab_archive_A_create( $l_CtabName, $l_Tcol  ) ;	
		}

		// VERIF dans la base 
		if( $l_Oreq = $BDD->demande( 'SELECT id
						FROM ' . $l_CtabName . ' 
						WHERE ' . $l_Tcol["obj"]  .	  ' ="' . $Tarchive["objectif"]["value"] . '" 
						AND ' 	. $l_Tcol["mat"] .	  ' ="' . $Tarchive["matieres"]["value"][0] . '"
						AND '	. $l_Tcol["outs"] .	  ' ="' . $Tarchive["outils"]["value"][0] . '"
						AND '	. $l_Tcol["seq"] .	  ' ="' . $Tarchive["sequence"]["value"] . '"
						AND '	. $l_Tcol["result"] . ' ="' . $Tarchive["resultat"]["value"] . '"'
						)  )
		{

			$l_Tr = array();
			$l_i_doublon = 0 ;

			while ($l_Od = $l_Oreq->fetch(PDO::FETCH_OBJ)){
				$l_i_doublon++;
				$l_Tr[$l_i_doublon]['id'] = $l_Od->id ;

			}

			if ( $l_i_doublon == 1 or $l_i_doublon > 1 )
			{
				//la cle existe
				$l_Treponse[0] = true ;
				// on pourra enregistrer la ressource
				if ( $l_i_doublon == 1 )
				{
					$l_Treponse["err"] = 3 ;
				}
				else if ( $l_i_doublon > 1 )
				{
					$l_Treponse["err"] = 2 ;
				}
			}
			

		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}

	// Fin Check archive
		return $l_Treponse;
	}



/* PUSH ARCHIVE --------------------------- PUSH ARCHIVE
 * requete qui permet de d'enregistrer une nouvelle ARCHIVE dans la BDD
 * on ne recommence pas les verifications de table et colonne 
 * car on considère que cette fonction n'est exploitable qu'une fois l'ARCHIVE check methode appelée
 @Param: l'ARCHIVE à enregistrer ->
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = "void"
*/

	function push_archive_A( $Tarchive, $tab, $Tcol ) 
	{
		global $BDD ;
		global $BFUNC ;

		$l_Treponse["err"] = 0 ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;
// ATTENTION LORSQUE LES DONNEES A ENREGISTRER SONT EN VARCHAR OU EN INT IL FAUT METTRE OU ENLEVER LES GUILLEMET, LE MIEUX SERAIT D4ENREGITRER TOUT EN VARCHAR ET D'ASSOCIER LE TYPE AVEC LA DONNEE !!!  999999999999999999
		if ( $BDD->demande( 'INSERT INTO ' . $tab . ' (' . $Tcol["id"] . ', '. $Tcol["obj"] . ', ' . $Tcol["mat"] . ', ' . $Tcol["outs"] . ', ' . $Tcol["seq"] . ', ' . $Tcol["result"] . ', ' . $Tcol["dist"] . ', ' . $Tcol["ratio"] . ', ' . $Tcol["delais"] . ', ' . $Tcol["compt"] . ') 
							VALUES ("' . $Tarchive["id"] . '", ' . $Tarchive["objectif"]["value"] . ', ' . $Tarchive["matieres"]["value"][0] . ', "' . $Tarchive["outils"]["value"][0] . '", "' . $Tarchive["sequence"]["value"] . '", ' . $Tarchive["resultat"]["value"] . ', ' . $Tarchive["datas"]["distance"] . ', ' . $Tarchive["datas"]["precision"] . ', ' . $Tarchive["datas"]["delais"] . ', ' . $Tarchive["datas"]["compteur"] . ')' ) )
		{
			$l_Treponse[0] = true ;
		}
		else
		{
			$l_Treponse["err"] = 1 ;
		}

	// Fin push Archive
		return $l_Treponse;
	}
	// ------------------------------------


/* RECUP ARCHIVES --------------------------- RECUPERES ARCHIVES 
 * requete recupere toutes les archives dont le resultat de calcul est égal à $obj 
 @Param: $obj : l'objectif à atteindre
 @Return: une table avec 0: BOOL ; val -> object STDClass à transformer en array si besoin  ; 
 "err" : letat derreur de la fonction
*/

	function get_archives_A_good( $objectif ){

		global $BFUNC ;

		$l_Treponse[0] = false ;
		$l_Treponse["val"] = array() ;
		$l_Treponse["err"] = 0 ;

		// on  recupere le type de la ressource à enregistrer
        // ATTENTION AU TYPE EXOTIQUE (NON SCALABLE -> une type SQL "none" !!!!!!!!!! )   XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxxx
        $l_Ttype = $BFUNC->get_type( $objectif ) ; 
    
        // on recree le nom de la table ou sont les archives
        $l_CtabName = $this->p_Tprefixes["arch"] . $l_Ttype["subval"] ;

        // VERIF dans la base 
        if ( $req = $this->demande( 'SELECT *
                        FROM ' . $l_CtabName . ' 
                        WHERE ' . $this->p_Tprefixes["cpx"] . 'resultat="' . $objectif . '" 
                        ORDER BY ' . $this->p_Tprefixes["cpx"] . 'delais' ) )
        {
        	$l_Treponse[0] = true ;
        	$l_i = 0 ;

        	while ( $d = $req->fetch( PDO::FETCH_OBJ ) )
        	{
				$l_Treponse["val"][$d->id] = $d ;
        		$l_i++ ;
        	}

        	if ( $l_i == 0 )
        	{
        		$l_Treponse["err"] = 2 ;
        	}
            
        }
        else
        {
        	$l_Treponse["err"] = 1 ;
        }

		return $l_Treponse;

	// Fin recup archives
	}
	// ------------------------------------


/* RECUP ARCHIVES ALL--------------------------- RECUPERES ARCHIVES ALL
 * requete recupere toutes les archives dont le resultat de calcul est égal à $obj 
 @Param: $obj : l'objectif à atteindre
 @Return: une table avec 0: BOOL ; val -> object STDClass à transformer en array si besoin  ; 
 "err" : letat derreur de la fonction
*/

	function get_archives_A_all( $objectif ){

		global $BFUNC ;

		$l_Treponse[0] = false ;
		$l_Treponse["val"] = array() ;
		$l_Treponse["err"] = 0 ;

		// on  recupere le type de la ressource à enregistrer
        // ATTENTION AU TYPE EXOTIQUE (NON SCALABLE -> une type SQL "none" !!!!!!!!!! )   XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxxx
        $l_Ttype = $BFUNC->get_type( $objectif ) ; 
    
        // on recree le nom de la table ou sont les archives
        $l_CtabName = $this->p_Tprefixes["arch"] . $l_Ttype["subval"] ;

        // VERIF dans la base 
        if ( $req = $this->demande( 'SELECT *
                        FROM ' . $l_CtabName . ' 
                        ORDER BY ' . $this->p_Tprefixes["cpx"] . 'delais' ) )
        {
        	$l_Treponse[0] = true ;
        	$l_i = 0 ;

        	while ( $d = $req->fetch( PDO::FETCH_OBJ ) )
        	{
				$l_Treponse["val"][$d->id] = $d ;
        		$l_i++ ;
        	}

        	if ( $l_i == 0 )
        	{
        		$l_Treponse["err"] = 2 ;
        	}
            
        }
        else
        {
        	$l_Treponse["err"] = 1 ;
        }

		return $l_Treponse;

	// Fin recup archives ALL
	}
	// ------------------------------------

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
				$l_Treponse[0] = true ;

			}
			else if($i_doublon == 1){$l_Treponse["err"] = 4 ;}
			else if($i_doublon > 1){$l_Treponse["err"] = 5 ;}

		}else{$l_Treponse["err"] = 3 ;}

			
	return $l_Treponse;
	}
	// ------------------------------------

*/



/* ----------------------------------FIN------------------------------------- */
/* Fin Class OBdd_connexion */
}



