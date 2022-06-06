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
$servIncludeJeton = true ;


// GET----------------------- GET
//Recuperation des variables GET
 //détermination du SERVEUR (local OU ops par ex)
 //pour la navigation entre les pages par exemple
  $s = "local" ;
  if( isset( $_GET['s']) )
  {
    if( $_GET['s'] === "local" )
    {
      $s = $_GET['s'] ;
    }  
  }
  //$get_p = (isset($_GET['p'])) ? $p=$_GET['p'] : $p='tunnel' ;
  // $get_s = (isset($_GET['s'])) ? $s=$_GET['s'] : $s='ops' ;

  // $g_url_courante = $_SERVER['REQUEST_URI'] ;

//-----------------------
//-----------------------
// VARIABLES META GLOBALES-----------------VARIABLES META GLOBALES 
  // var meta et directory /!\ $inc_folder nest pas encore défini 
  if( $s === 'local' ) 
  {
    /*url racine */
    if( $BFUNC->UrlSousDomaine( "localhost" ) )
    {
      // on initie la propriété g_url de BFUNC
      $BFUNC->define_url( HTPROT . "localhost/www/2018-IAMJE/" ) ;
    }
    else
    {
      exit( 'Je ne me sens pas chez moi' ) ;
    } 

  }

