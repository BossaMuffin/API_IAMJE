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
// on utilise cette variable pour valider l'include du fichier 
$metaIncludeJeton = true ;

//  ------------------------------   VARIABLES META GLOBALES -->
const HTPROT = "http://" ;

// --------------------------------    MODE DEV  ----->
// les variables de mode développement

// permet d'activer le mode d'enregistrement des erreurs
	// doit on trâcer les erreurs ? par defaut on garde la trâce
$B_DEVMODERUN = true ;
	
if ( isset( $_GET["err"] ) && ! empty( $_GET["err"] ) && strlen( $_GET["err"] ) <= 5 ) 
{
    $B_DEVMODERUN = filter_var( $_GET["err"], FILTER_VALIDATE_BOOLEAN ) ;
}


// permet d'afficher la trace des erreurs des fonctions appelées
	// doit on afficher la trâce des erreurs ? par defaut on ne l'affiche pas
$B_DEVMODESHOW = false ;
	
if ( isset( $_GET["dev"] ) && ! empty( $_GET["dev"] ) && strlen( $_GET["dev"] ) <= 5 ) 
{
    $B_DEVMODESHOW = filter_var( $_GET["dev"], FILTER_VALIDATE_BOOLEAN ) ;
}
// --------   Fin MODE DEV  ----->


/* MAIN FOLDERS */
const	FOLD_CSS = "css/" ;
const	FOLD_INC = "inc/" ;
const	FOLD_JS = "js/" ;
const	FOLD_AJAX = "AJAX/" ;
const	FOLD_CLASS = "class/" ;
const	FOLD_COMMON = "common/" ;
const	FOLD_SKINS = "skins/" ;


//donne lip de user 
$ip = $_SERVER['REMOTE_ADDR'] ;

define( 'COOKIEDELAY', 129600 ) ;
define( 'KEYMAX', 12 ) ;
/*
define('ADMIN_COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '.equartier.fr');
define('COOKIEPATH', '');
define('SITECOOKIEPATH', '');
*/

define('EQ_API_KEY', 'CoZ') ;

$timeActu = time() ;


// on defini les modes dutilisation

$tIA = array() ;
$tIA[0] = "alpa" ;
$tIA[1] = "alpa" ;

$tMode = array() ;
$tMode[0] = "learn" ;
$tMode[1] = "work" ;