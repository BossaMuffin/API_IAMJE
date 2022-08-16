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
$bddIncludeJeton = true ;

//BDD DECLARATION + APPEL BDD CLASS------------------------  BDD + CLASS
  require( $g_page_arbo . FOLD_CLASS . 'OBdd_connexion_local.req.php' ) ;
  require( $g_page_arbo . FOLD_CLASS . 'OBdd_exe.req.php' ) ;
  $BDD = new OBdd( 'iamje-1' ) ;
 
