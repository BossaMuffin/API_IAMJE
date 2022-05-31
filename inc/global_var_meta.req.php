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