<?php

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
      //exit("Local");
      $g_url = HTPROT . "localhost/www/2018-IAMJE/" ;
    }
    else
    {
      exit( 'Je ne me sens pas chez moi' ) ;
    } 

  }

