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
    public $p_Ttypes = ["VARCHAR", "INT", "FLOAT", "TIMESTAMP", "BOOL", "TEXT"] ;
    public $p_TnewTressources = array() ;
    public $p_TnewTarchives = array() ;
    public $p_TnewTtraces = false ;
    public $p_Tprefixes = [	"cpx" 	=> "C_",
    						"mat" 	=> "R_",
    						"arch" 	=> "A_",
    						"outs" 	=> "O_"	] ;
    public $p_Tcol = array() ;						
    // l'id de l'ordre de calcul (id de ligne entrée dans la BDD table T_ordres par trace_ordres)
    public $p_NtraceId ;

// constantes
	private $CLIENT_KEY_ID = "coz-IAMJE-001" ;


/* ---------------- CONSTRUCTEUR ----------------------------- 
* @value : utilise l'instance $BDD 
* @return : PARENT
*/
    function __CONSTRUCT( )
    {
        parent::__CONSTRUCT( ) ;

		// liste des champs que l'on retrouve dans toutes les tables
        $this->p_Tcol["all"]["client_key"] 	= $this->p_Tprefixes["cpx"] . "client_key" ;
        $this->p_Tcol["all"]["date"] 		= $this->p_Tprefixes["cpx"] . "datetime" ;

		// liste des champs que l'on retrouve dans la table de trace des ordres ALPA
		$this->p_Tcol["trace"]["mode"] 		= $this->p_Tprefixes["cpx"] . "mode" ;
		$this->p_Tcol["trace"]["run"] 		= $this->p_Tprefixes["cpx"] . "run" ;
		$this->p_Tcol["trace"]["obj"] 		= $this->p_Tprefixes["cpx"] . "objectif" ;
		$this->p_Tcol["trace"]["mat"] 		= $this->p_Tprefixes["cpx"] . "matieres" ;
		$this->p_Tcol["trace"]["outs"] 		= $this->p_Tprefixes["cpx"] . "outils" ;
		$this->p_Tcol["trace"]["dist"] 		= $this->p_Tprefixes["cpx"] . "distance" ;
		$this->p_Tcol["trace"]["ratio"] 	= $this->p_Tprefixes["cpx"] . "precision" ;
		$this->p_Tcol["trace"]["delais"] 	= $this->p_Tprefixes["cpx"] . "delais" ;
		$this->p_Tcol["trace"]["dev"] 		= $this->p_Tprefixes["cpx"] . "dev" ;
		$this->p_Tcol["trace"]["err"] 		= $this->p_Tprefixes["cpx"] . "err" ;

		// liste des champs que l'on retrouve dans la table des arvhives ALPA
        $this->p_Tcol["arch"]["id"] 		= $this->p_Tprefixes["cpx"] . "id" ;
		$this->p_Tcol["arch"]["obj"] 		= $this->p_Tprefixes["cpx"] . "objectif" ;
		$this->p_Tcol["arch"]["mat"] 		= $this->p_Tprefixes["cpx"] . "matieres" ;
		$this->p_Tcol["arch"]["outs"] 		= $this->p_Tprefixes["cpx"] . "outils" ;
		$this->p_Tcol["arch"]["seq"] 		= $this->p_Tprefixes["cpx"] . "sequence" ;
		$this->p_Tcol["arch"]["result"] 	= $this->p_Tprefixes["cpx"] . "resultat" ;
		$this->p_Tcol["arch"]["dist"] 		= $this->p_Tprefixes["cpx"] . "distance" ;
		$this->p_Tcol["arch"]["ratio"] 		= $this->p_Tprefixes["cpx"] . "precision" ;
		$this->p_Tcol["arch"]["delais"] 	= $this->p_Tprefixes["cpx"] . "delais" ;
		$this->p_Tcol["arch"]["compt"] 		= $this->p_Tprefixes["cpx"] . "compteur" ;
		$this->p_Tcol["arch"]["trace"] 		= $this->p_Tprefixes["cpx"] . "trace" ;
		
		// liste des champs que l'on retrouve dans la table des ressources ALPA
		$this->p_Tcol["ress"]["valeur"] 	= $this->p_Tprefixes["cpx"] . "valeur" ;
		$this->p_Tcol["ress"]["trace"] 		= $this->p_Tprefixes["cpx"] . "trace" ;


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
	protected function demande( $requete, $lastID = false )
	{
		//$req = $this->getPDO()->query('SELECT * FROM events');
		$result = $this->getPDO()->query( $requete ) ;
		return $result ;
	}
	
/* lastID --------------------------- lastID
 * retourne le dernier ID INSERT dans la base
 @Param: aucun
 @Return: int(id)
*/
	function lastInsertID( )
	{
		return $this->getPDO()->lastInsertId() ;
	}

/* SHOW TABLES --------------------------- SHOW TABLES
 * requete
 @Param: aucun
 @Return: $reponse[0] = temoin ; $reponse["val"] = contient la liste des tables présente dans la BDD
*/
	function tab_show() 
	{

		global $BFUNC ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"] = array() ;
		$l_Treponse[0] = false ;

		if ( $req = $this->demande( 'SHOW TABLES' ) )
		{
			$l_Treponse[0] = true ;
			$l_Treponse["val"] = $req->fetchAll(PDO::FETCH_COLUMN) ;
		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
		}
			

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
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
		global $BFUNC ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"] = array() ;
		$l_Treponse[0] = false ;

		if ( $req = $this->demande( 'SHOW COLUMNS FROM ' . $Ctab_nom ) )
		{
			$l_Treponse[0] = true ;
			$l_Treponse["val"] = $req->fetchAll(PDO::FETCH_COLUMN) ;
		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
		}

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
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
		global $BFUNC ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;

		// on check si une table existe avec ce nom
		$Tnoms = $this->tab_show( ) ;
		if ( ! in_array( $Ctab_nom, $Tnoms["val"] ) )
		{
			// on ne crée la table que si le nom est disponnible
			if ( $req = $this->demande( 'CREATE TABLE ' . $Ctab_nom . ' ( id INT NOT NULL AUTO_INCREMENT PRIMARY KEY )' ) )
			{
				$l_Treponse[0] = true ;
			}
			else
			{
				$l_Treponse["err"]["id"] = "2" ;
			}

		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
			$l_Treponse["err"]["com"] = "tab not new " ;
		}


		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
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
		global $BFUNC ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;

		// on check si une table existe avec ce nom
		$Tnoms = $this->tab_show( ) ;
		if ( in_array( $Ctab_nom, $Tnoms["val"] ) )
		{
			// on ne detruit la table que si le nom existe
			if ( $req = $this->demande( 'DROP TABLE ' . $Ctab_nom ) )
			{
				$l_Treponse[0] = true ;
			}
			else
			{
				$l_Treponse["err"]["id"] = "2" ;
			}

		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
		}

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
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
		global $BFUNC ;
		
		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
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
				$l_Treponse["err"]["id"] = "2" ;
			}
		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
		}

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
		return $l_Treponse ;

	// Fin CREATE TYPE
	}
	// ------------------------------------



/* ADD COLONNE --------------------------- ADD COLONNE
 * requete
 @Param: le nom de la table à modifier, la colonne à ajouter, le type de colone, la taille du champs 
 * et l'option qui permet de spécifier les caractéristique du champ/colonne
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = ""
*/
	function tab_add_col( $Ctab_nom, $Ccol_nom, $Ccol_type, $Ntype_size, $Coption = "" ) 
	{
		global $BFUNC ;


		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
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
					if ( $req = $this->demande( 'ALTER TABLE ' . $Ctab_nom . ' ADD ' . $Ccol_nom . ' ' . $l_Ccoltype["val"] . ' ' . $Coption) )
					{
						$l_Treponse[0] = true ;
					}
					else
					{
						$l_Treponse["err"]["id"] = "4" ;
					}

				}
				else
				{
					$l_Treponse["err"]["id"] = "3" ;
				}

			}
			else
			{
				$l_Treponse["err"]["id"] = "2" ;
				$l_Treponse["err"]["com"] = "col not new" ;
			}

		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
		}

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
		return $l_Treponse ;

	// Fin ADD COL TABLE
	}
	// ------------------------------------

// ------------------------------------------------------------------------------------------
// -------------------------------------- TRACE DES ORDRES ----------------------------------
// ------------------------------------------------------------------------------------------


/* -------------------------------------- CREATE TRACE TAB  --------------------------- 
* cree la tab de la trace des ordres demandés à ALPA
* @param : le nom de la table par defaut
* @value : none
* @return : bool de reussite + val void 
*/
  function tab_trace_A_create( $CtabName = "T_ordres"  )
  {
    global $BFUNC ;

    $l_jeton = true ;

    $l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
    $l_Treponse["val"] = "void" ;
    $l_Treponse[0] = false ;


    $l_TnewTab = $this->tab_create( $CtabName ) ;
    if ( ! $l_TnewTab[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 1 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    }

// Col MODE -> si le calcul issu de la trace à abouti
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["trace"]["mode"], $BFUNC->p_Ttypes[4]["bdd"], 10 ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 2 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    } 

// Col RUN -> si le calcul issu de la trace à abouti
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["trace"]["run"], $BFUNC->p_Ttypes[11]["bdd"], $BFUNC->p_Ttypes[11]["size"], "DEFAULT FALSE NOT NULL" ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 3 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    } 
// Col OBJECTIFS
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["trace"]["obj"], $BFUNC->p_Ttypes[4]["bdd"], $BFUNC->p_Ttypes[4]["size"] ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 4 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    }
// Col MATIERES
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["trace"]["mat"], $BFUNC->p_Ttypes[4]["bdd"], $BFUNC->p_Ttypes[4]["size"] ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 5 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    }
// Col OUTILS
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["trace"]["outs"], $BFUNC->p_Ttypes[4]["bdd"], 40 ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 6 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    }
// Col DISTANCE MIN
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["trace"]["dist"], $BFUNC->p_Ttypes[3]["bdd"], 0 ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 7 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    }
// Col RATIO / PRECISION  MIN
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["trace"]["ratio"], $BFUNC->p_Ttypes[3]["bdd"], 0 ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 8 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    }
// Col DELAIS DE CALCUL MAX
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["trace"]["delais"], $BFUNC->p_Ttypes[3]["bdd"], 0 ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 9 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    }
// Col CLIENT KEY
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["all"]["client_key"], $BFUNC->p_Ttypes[4]["bdd"], $BFUNC->p_Ttypes[4]["size"]  ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 10 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    }
// Col DATETIME -> timestamp de création
	$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["all"]["date"], $BFUNC->p_Ttypes[10]["bdd"], $BFUNC->p_Ttypes[10]["size"], 'DEFAULT CURRENT_TIMESTAMP NOT NULL'  ) ;
	if ( ! $l_TnewCol[0] )
	{
		$l_jeton = false ;
		$l_Treponse["err"]["id"] .= "- 11 " ;
		$l_Treponse["err"]["com"] = "cols not new" ;
	}

// Col DEV affichage du mode developpement
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["trace"]["dev"], $BFUNC->p_Ttypes[11]["bdd"], $BFUNC->p_Ttypes[11]["size"], "DEFAULT FALSE NOT NULL" ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 12 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    }
// Col ERR - enregistrement des erreurs au sein des fonctions -> visible en mode développement 
    $l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["trace"]["err"], $BFUNC->p_Ttypes[12]["bdd"], $BFUNC->p_Ttypes[12]["size"] ) ;
    if ( ! $l_TnewCol[0] )
    {
        $l_jeton = false ;
        $l_Treponse["err"]["id"] .= "- 13 " ;
        $l_Treponse["err"]["com"] = "cols not new" ;
    }

    if ( $l_jeton  )
    {
        $l_Treponse[0] = true ;
    }
    else
    {
        // propriété $BDD qui garde en memoire le check + create table colonne
        $this->p_TnewTtraces = true ;
    }

    // on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    $BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    // on charge la reponse à retourner
    return $l_Treponse ;

  // fin trace ordres
  }
  // ------------------------------------


/* PUSH TRACE ORDRE --------------------------- PUSH TRACE ORDRE
 * requete qui permet de d'enregistrer une nouvelle TRACE d'ordres dans la BDD
 * il faudra compléter les champs RUN et ERR après l'execution d'ALPA 
 @Param:
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = "void"
*/
	function push_trace_A( $Ctab = "T_ordres" ) 
	{
		global $BFUNC ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;
// ATTENTION LORSQUE LES DONNEES A ENREGISTRER SONT EN VARCHAR OU EN INT IL FAUT METTRE OU ENLEVER LES GUILLEMET, LE MIEUX SERAIT D4ENREGITRER TOUT EN VARCHAR ET D'ASSOCIER LE TYPE AVEC LA DONNEE !!!  999999999999999999
		if ( $this->demande( 'INSERT INTO ' . $Ctab . ' (' . $this->p_Tcol["trace"]["mode"] . ', ' . $this->p_Tcol["trace"]["obj"] . ', ' . $this->p_Tcol["trace"]["mat"] . ', ' . $this->p_Tcol["trace"]["outs"] . ', ' . $this->p_Tcol["trace"]["dist"] . ', ' . $this->p_Tcol["trace"]["ratio"] . ', ' . $this->p_Tcol["trace"]["delais"] . ', ' . $this->p_Tcol["all"]["client_key"] . ', ' . $this->p_Tcol["trace"]["dev"] . ', ' . $this->p_Tcol["trace"]["err"] . ') 
							VALUES ("' . $_GET["mode"] . '", "' . $_GET["obj"] . '", "' . $_GET["mat"] . '", "' . $_GET["outs"] . '", ' . $_GET["dist"] . ', ' . $_GET["ratio"] . ', ' . $_GET["delais"] . ', "' . $this->CLIENT_KEY_ID . '", "' . $BFUNC->p_BdevModeShow . '", "' . print_r( $BFUNC->p_Terreurs, true ) . '")' ) )
		{
			$l_Treponse[0] = true ;
		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
		}

	// Fin push trace
		return $l_Treponse;
	}
	// ------------------------------------

/* PUSH TRACE ORDRE RUNNING  --------------------------- PUSH TRACE ORDRE RUNNING 
 * requete qui permet valider une trace après l'execution d'ALPA
 @Param: lastInsertId
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = "void"
*/
	function push_trace_A_running( $l_NlastInsertID, $Ctab = "T_ordres" ) 
	{
		global $BFUNC ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ; ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;
// ATTENTION LORSQUE LES DONNEES A ENREGISTRER SONT EN VARCHAR OU EN INT IL FAUT METTRE OU ENLEVER LES GUILLEMET, LE MIEUX SERAIT D4ENREGITRER TOUT EN VARCHAR ET D'ASSOCIER LE TYPE AVEC LA DONNEE !!!  999999999999999999
		if ( $this->demande(   	'UPDATE ' . $Ctab . 
								' SET ' 	. $this->p_Tcol["trace"]["run"] . ' = 1, ' 
										. $this->p_Tcol["trace"]["err"] . ' = "' . print_r( $BFUNC->p_Terreurs, true ) . '" 
								WHERE id = "' . $l_NlastInsertID . '"' ) )
		{
			$l_Treponse[0] = true ;
		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
		}

	// Fin push trace running
		return $l_Treponse ;
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
	function tab_ressource_create( $CtabName )
	{
		global $BFUNC ;

		$this->tab_create( $CtabName ) ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;
		
		// jeton qui permet de suivre les différentes création de colonne dans la table
		$l_jeton = true ;

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
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["ress"]["valeur"], $l_Ccol_type, $l_Ntype_size ) ;			
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 2 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
		// Col TRACE ID -> clé secondaire de la table T_ordres (permet de relier la ressource avec les ordres lors de sa création)
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["ress"]["trace"], $BFUNC->p_Ttypes[2]["bdd"], 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 3 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}

		// Col CLIENT KEY
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["all"]["client_key"], $BFUNC->p_Ttypes[4]["bdd"], $BFUNC->p_Ttypes[4]["size"] ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 4 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}

		// Col DATETIME -> timestamp de création
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["all"]["date"], $BFUNC->p_Ttypes[10]["bdd"], $BFUNC->p_Ttypes[10]["size"], 'DEFAULT CURRENT_TIMESTAMP NOT NULL'  ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 5 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}

		if ( $l_jeton )
		{
			$l_Treponse[0] = true ;
		}
		else
		{
			// propriété $BDD qui garde en memoire le check + create table colonne
			array_push( $this->p_TnewTressources, $CtabName ) ;
		}

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

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
		global $BFUNC ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"]["tab"] = "" ;
		$l_Treponse["val"]["col"] = "" ;		
		$l_Treponse[0] = false ;

		// on  recupere le type de la ressource à enregistrer
		// ATTENTION AU TYPE EXOTIQUE (NON SCALABLE -> une type SQL "none" !!!!!!!!!! )   XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxxx
		$l_Ttype = $BFUNC->get_type( $ressource ) ; 
		 
		// on cree une table spécifique à ce type de ressource s'il elle n'existe pas déjà
		$l_CtabName = $this->p_Tprefixes["mat"] . $l_Ttype["subval"] ;
		
		$l_Treponse["val"]["tab"] = $l_CtabName ;

// echo $ressource . " type : " . $l_Ttype["val"] . "-> " . $l_Ttype["subval"] . " err : " . $l_Ttype["err"] . " tabName :" . $l_CtabName ;

		// propriété $BDD qui garde en memoire le check + create table colonne
		if ( ! in_array( $l_CtabName, $this->p_TnewTressources ) )
		{
			// le type de $ressource est analysé, une table et les colonne sont crées si besoin
			$this->tab_ressource_create( $l_CtabName ) ;	
		}
		

		// VERIF dans la base 
		if ( $l_Oreq = $this->demande( 'SELECT id
						FROM ' . $l_CtabName . ' 
						WHERE ' . $this->p_Tcol["ress"]["valeur"] . 	' ="' . $ressource . '"
						AND ' . $this->p_Tcol["all"]["client_key"] . 	' ="' . $this->CLIENT_KEY_ID . '"' )  )
		{

			//$l_Tr = array();
			$l_i_doublon = 0 ;

			while ( $l_Od = $l_Oreq->fetch(PDO::FETCH_OBJ ) ) 
			{
				$l_i_doublon++;
				//$l_Tr[$l_i_doublon]['id'] = $l_Od->id ;
			}

			if ( $l_i_doublon == 1 or $l_i_doublon > 1 )
			{
				//la cle existe
				$l_Treponse[0] = true ;
				// on pourra enregistrer la ressource
				if ( $l_i_doublon == 1 )
				{
					$l_Treponse["err"]["id"] = "3" ;
					$l_Treponse["err"]["com"] = "ressource not new" ;
				}
				else if ( $l_i_doublon > 1 )
				{
					$l_Treponse["err"]["id"] = "2" ;
					$l_Treponse["err"]["com"] = "ressource not new -> multiple" ;
				}
			}
			

		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
		}

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
		return $l_Treponse;

	// Fin Check Ressource
	}
	// ------------------------------------

/* PUSH RESSOURCE --------------------------- PUSH RESSOURCE
 * requete qui permet de d'enregistrer une nouvelle ressource dans la BDD
 * on ne recommence pas les verifications de table et colonne 
 * car on considère que cette fonction n'est exploitable qu'une fois la ressource check methode appelée
 @Param: la ressource à enregistrer ->
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = "void"
*/
	function push_ressource( $ressource, $tab ) 
	{
		global $BFUNC ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;

		if ( $this->demande( 'INSERT INTO ' . $tab . ' 
										(' . $this->p_Tcol["ress"]["valeur"] . ', ' . $this->p_Tcol["ress"]["trace"] . ', ' . $this->p_Tcol["all"]["client_key"] . ') 
										VALUES ("' . $ressource . '", "' . $this->p_NtraceId . '", "' . $this->CLIENT_KEY_ID . '")' ) )
		{
			$l_Treponse[0] = true ;
		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
		}

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
		return $l_Treponse;

	// Fin push Ressource
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
 function tab_archive_A_create( $CtabName )
	{
		global $BFUNC ;

		$l_jeton = true ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;

		$l_TnewTab = $this->tab_create( $CtabName ) ;
		if ( ! $l_TnewTab[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 1 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col ID de calcul
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["id"], $BFUNC->p_Ttypes[4]["bdd"], $BFUNC->p_Ttypes[4]["size"] ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 2 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col OBJECTIFS
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["obj"], $BFUNC->p_Ttypes[3]["bdd"], 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 3 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col MATIERES
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["mat"], $BFUNC->p_Ttypes[3]["bdd"], 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 4 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col OUTILS
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["outs"], $BFUNC->p_Ttypes[4]["bdd"], 40 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 5 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col SEQUENCE DE CALCUL
// 999999999999999999999  ATTENTION : la taille de la donnée grandit vite, mieux vaux un texte ou tinytext 
		// ... mais "text" n'est pas compris par la méthode build type  ------------  99999999999 XXXXXXXXXx
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["seq"], $BFUNC->p_Ttypes[4]["bdd"], $BFUNC->p_Ttypes[4]["size"] ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 6 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col RESULTAT
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["result"], $BFUNC->p_Ttypes[3]["bdd"], 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 7 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col DISTANCE à l'objectif
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["dist"], $BFUNC->p_Ttypes[3]["bdd"], 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 8 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col RATIO / precision du resultat
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["ratio"], $BFUNC->p_Ttypes[3]["bdd"], 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 9 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col DELAIS de calcul
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["delais"], $BFUNC->p_Ttypes[3]["bdd"], 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 10 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col COMPTEUR de nombre de calcul
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["compt"], $BFUNC->p_Ttypes[2]["bdd"], 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 11 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col TRACE l'id de la trace des ordres données - clé secondaire de la table T_ordres
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["arch"]["trace"], $BFUNC->p_Ttypes[2]["bdd"], 0 ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 12 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col CLIENT KEY qui identifie l'origine du calcul
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["all"]["client_key"], $BFUNC->p_Ttypes[4]["bdd"], $BFUNC->p_Ttypes[4]["size"]  ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 13 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}
// Col DATETIME -> timestamp de création
		$l_TnewCol = $this->tab_add_col( $CtabName, $this->p_Tcol["all"]["date"], $BFUNC->p_Ttypes[10]["bdd"], $BFUNC->p_Ttypes[10]["size"], 'DEFAULT CURRENT_TIMESTAMP NOT NULL'  ) ;
		if ( ! $l_TnewCol[0] )
		{
			$l_jeton = false ;
			$l_Treponse["err"]["id"] .= "- 14 " ;
			$l_Treponse["err"]["com"] = "cols not new" ;
		}

		if ( $l_jeton  )
		{
			$l_Treponse[0] = true ;
			
		}
		 else
	    {
	        // propriété $BDD qui garde en memoire le check + create table colonne
			array_push( $this->p_TnewTarchives, $CtabName ) ;
	    }

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
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
		global $BFUNC ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"]["tab"] = "" ;
		
		$l_Treponse[0] = false ;

		// on  recupere le type de la ressource à enregistrer
		// ATTENTION AU TYPE EXOTIQUE (NON SCALABLE -> une type SQL "none" !!!!!!!!!! )   XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxxx
		$l_Ttype = $BFUNC->get_type( $Tarchive["objectif"]["value"] ) ; 

		// on cree une table spécifique à ce type de ressource s'il elle n'existe pas déjà
		$l_CtabName = $this->p_Tprefixes["arch"] . $l_Ttype["subval"] ;
		
		$l_Treponse["val"]["tab"] = $l_CtabName ;

		// propriété $BDD qui garde en memoire le check + create table colonne
		if ( ! in_array( $l_CtabName, $this->p_TnewTarchives ) )
		{
			// le type de $Tarchive["objectif"]["value"] est analysé, une table et les colonnes sont crées si besoin
			$this->tab_archive_A_create( $l_CtabName ) ;	
		}

		// VERIF dans la base 
		if ( $l_Oreq = $this->demande( 'SELECT id
						FROM ' . $l_CtabName . ' 
						WHERE ' . $this->p_Tcol["arch"]["obj"]  .		' ="' . $Tarchive["objectif"]["value"] . '" 
						AND ' 	. $this->p_Tcol["arch"]["mat"] .		' ="' . $Tarchive["matieres"]["value"][0] . '"
						AND '	. $this->p_Tcol["arch"]["outs"] .		' ="' . $Tarchive["outils"]["value"][0] . '"
						AND '	. $this->p_Tcol["arch"]["seq"] .	 	' ="' . $Tarchive["sequence"]["value"] . '"
						AND '	. $this->p_Tcol["arch"]["result"] . 	' ="' . $Tarchive["resultat"]["value"] . '"
						AND '	. $this->p_Tcol["all"]["client_key"] . ' ="' . $this->CLIENT_KEY_ID . '"'
						)  )
		{
				
			//$l_Tr = array();
			$l_i_doublon = 0 ;

			while ( $l_Od = $l_Oreq->fetch(PDO::FETCH_OBJ ) )
			{
				$l_i_doublon++;
				//$l_Tr[$l_i_doublon]['id'] = $l_Od->id ;
			}

			if ( $l_i_doublon == 1 or $l_i_doublon > 1 )
			{
				//la cle existe
				$l_Treponse[0] = true ;
				// on pourra enregistrer la ressource
				if ( $l_i_doublon == 1 )
				{
					$l_Treponse["err"]["id"] = "4" ;
					$l_Treponse["err"]["com"] = "archive not new" ;
				}
				else if ( $l_i_doublon > 1 )
				{
					$l_Treponse["err"]["id"] = "3" ;
					$l_Treponse["err"]["com"] = "archive not new -> multiple" ;
				}
			}
			else
			{
				$l_Treponse["err"]["id"] = "2" ;
			}
		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
		}

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
		return $l_Treponse ;

	// Fin Check archive
	}
	// ------------------------------------


/* PUSH ARCHIVE --------------------------- PUSH ARCHIVE
 * requete qui permet de d'enregistrer une nouvelle ARCHIVE dans la BDD
 * on ne recommence pas les verifications de table et colonne 
 * car on considère que cette fonction n'est exploitable qu'une fois l'ARCHIVE check methode appelée
 @Param: l'ARCHIVE à enregistrer ->
 @Return: $l_Treponse[0] = temoin ; $l_Treponse["val"] = "void"
*/
	function push_archive_A( $Tarchive, $tab ) 
	{
		global $BFUNC ;

		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
		$l_Treponse["val"] = "void" ;
		$l_Treponse[0] = false ;
// ATTENTION LORSQUE LES DONNEES A ENREGISTRER SONT EN VARCHAR OU EN INT IL FAUT METTRE OU ENLEVER LES GUILLEMET, LE MIEUX SERAIT D4ENREGITRER TOUT EN VARCHAR ET D'ASSOCIER LE TYPE AVEC LA DONNEE !!!  999999999999999999
		if ( $this->demande( 'INSERT INTO ' . $tab . ' (' . $this->p_Tcol["arch"]["id"] . ', '. $this->p_Tcol["arch"]["obj"] . ', ' . $this->p_Tcol["arch"]["mat"] . ', ' . $this->p_Tcol["arch"]["outs"] . ', ' . $this->p_Tcol["arch"]["seq"] . ', ' . $this->p_Tcol["arch"]["result"] . ', ' . $this->p_Tcol["arch"]["dist"] . ', ' . $this->p_Tcol["arch"]["ratio"] . ', ' . $this->p_Tcol["arch"]["delais"] . ', ' . $this->p_Tcol["arch"]["compt"] . ', ' . $this->p_Tcol["arch"]["trace"] . ', ' . $this->p_Tcol["all"]["client_key"] . ') 
							VALUES ("' . $Tarchive["id"] . '", "' . $Tarchive["objectif"]["value"] . '", "' . $Tarchive["matieres"]["value"][0] . '", "' . $Tarchive["outils"]["value"][0] . '", "' . $Tarchive["sequence"]["value"] . '", "' . $Tarchive["resultat"]["value"] . '", ' . $Tarchive["datas"]["distance"] . ', ' . $Tarchive["datas"]["precision"] . ', ' . $Tarchive["datas"]["delais"] . ', ' . $Tarchive["datas"]["compteur"] . ', "' . $this->p_NtraceId . '", "' . $this->CLIENT_KEY_ID . '")' ) )
		{
			$l_Treponse[0] = true ;
		}
		else
		{
			$l_Treponse["err"]["id"] = "1" ;
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
		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;

		// on  recupere le type de la ressource à enregistrer
        // ATTENTION AU TYPE EXOTIQUE (NON SCALABLE -> une type SQL "none" !!!!!!!!!! )   XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxxx
        $l_Ttype = $BFUNC->get_type( $objectif ) ; 
    
        // on recree le nom de la table ou sont les archives
        $l_CtabName = $this->p_Tprefixes["arch"] . $l_Ttype["subval"] ;

        // VERIF dans la base 
        if ( $req = $this->demande( 'SELECT *
                        FROM ' . $l_CtabName . ' 
                        WHERE ' . $this->p_Tcol["arch"]["result"] .		' ="' . $objectif . '" 
                        AND '	. $this->p_Tcol["all"]["client_key"] .	' ="' . $this->CLIENT_KEY_ID . '"
                        ORDER BY ' . $this->p_Tcol["arch"]["delais"] ) )
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
        		$l_Treponse["err"]["id"] = "2" ;
        	}
            
        }
        else
        {
        	$l_Treponse["err"]["id"] = "1" ;
        }

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
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
	function get_archives_A_all( $objectif ) 
	{
		global $BFUNC ;

		$l_Treponse[0] = false ;
		$l_Treponse["val"] = array() ;
		$l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;

		// on  recupere le type de la ressource à enregistrer
        // ATTENTION AU TYPE EXOTIQUE (NON SCALABLE -> une type SQL "none" !!!!!!!!!! )   XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxxx
        $l_Ttype = $BFUNC->get_type( $objectif ) ; 
    
        // on recree le nom de la table ou sont les archives
        $l_CtabName = $this->p_Tprefixes["arch"] . $l_Ttype["subval"] ;

        // VERIF dans la base 
        if ( $req = $this->demande( 'SELECT *
                        FROM ' . $l_CtabName . ' 
                        WHERE '	. $this->p_Tcol["all"]["client_key"] . ' ="' . $this->CLIENT_KEY_ID . '"
                        ORDER BY ' . $this->p_Tcol["arch"]["delais"] ) )
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
        		$l_Treponse["err"]["id"] = "2" ;
        	}
            
        }
        else
        {
        	$l_Treponse["err"]["id"] = "1" ;
        }

		// on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    	$BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    	// on charge la reponse à retourner
		return $l_Treponse;

	// Fin recup archives ALL
	}
	// ------------------------------------




/* ----------------------------------FIN------------------------------------- */
/* Fin Class OBdd_connexion */
}



