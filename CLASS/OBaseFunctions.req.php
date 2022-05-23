<?php

//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste à répartir les fonctions dans plusieurs classes et renomer correctement les instances

class OBaseFunctions 
{

  const   CHARMAX = 40 ;
  const   KEYMAX = 6 ; // doit être supérieur ou égal à 6
  const   MDPMIN = 7 ;
  const   MDPMAX = 12 ;
  const   MDPDEFO = "" ;
  const   AUTHDELAY = 129600 ;
  const   COOKIEDELAY = 129600 ;
  const   PASREQUETE = 20 ;
  const   DEBREQUETE = 0 ;
  const   EXPMAIL = "";
  const   EXPNOM = "YOUR NAME";

  function __CONSTRUCT()
  {         
  } // fin construct 

//----------------------------------------------------------------------------------------------------------------------------------------------------------------
// FONCTIONS PHP ---------------------------------- FONCTIONS
//----------------------------------------------------------------------------------------------------------------------------------------------------------------  
  
/* -------------------------------------- recupere lurl appelé sans parametre --------------------------- */
  function UrlSansParametres()
  {
    $l_urlCourante = $_SERVER['REQUEST_URI'] ;
    $l_TurlGet = explode( "?", $l_urlCourante ) ;
    return  $l_TurlGet[0] ;
  }

/* ---------------------------------- annonce si le parametre est le sous domaine de lurl appelée -------------- */
  function UrlSousDomaine( $sousDomaine )
  {
            
    $l_urlCourante = $_SERVER['HTTP_HOST'] ;
    $l_reponse = false ;
    $l_Ttest = explode( $sousDomaine, $l_urlCourante ) ;
    
    if ( isset( $l_Ttest[1] ) )
    {
      $l_reponse = true ;
    }
    else
    {
      $l_reponse = false ;
    }

    return  $l_reponse;
  }


  /* GENERE IA CHAR KEY -------------------------------------------------------------------- GENERE IA CHAR KEY 
   * Genere une chaine de caractere lettre minuscule et majuscule et chiffre avec des char spéciaux ( -!$ )
   @Input :
   [option] len int : longeur de la clé (supérieur à 4!!!)
   
   @Return: une table avec 0: BOOL 0 -> pb , 1-> ok ;
    [value] : la clé demandée
   et [erreur] 
  */
  function genereCharKey( $len = self::KEYMAX ) 
  {
    $l_Treponse[0] = false ;
    $l_Treponse['erreur'] = 0 ;

    $l_Treponse["value"] = "" ;
    $l_i_car = 0 ;
    $l_car = "" ;
    $l_newcar = "" ;
    $l_chaine = "!abc-def!ghi-jkl!mnp-qrs!tuv-wxy!ABC-DEF!GHI-JKL!MNO-PQR!STU-VWX!YZ1-234!567-980!" ;
    $l_chainechiffre = "123456798" ;
    $l_chainecoz = "cozCOZ" ;
    $l_chainesep = "?-?!?" ;
    srand( (double)microtime()*1000000 );

  //premier caractere de la chaine sera un chiffre

    $l_newcar = $l_chainechiffre[rand()%strlen( $l_chainechiffre )] ;
    $l_Treponse["value"] .= $l_newcar ;
    $l_i_car++ ;
    $l_car = $l_newcar ;

  //deuxieme caractere de la chaine sera un separatif
    $l_newcar = $l_chainesep[rand()%strlen( $l_chainesep )] ;
    $l_Treponse["value"] .= $l_newcar ;
    $l_i_car++ ;
    $l_car = $l_newcar ;

  // les len - 2 autres caracteres sont generés depuis $chaine dans une boucle while
    while( $l_i_car < $len-4 ) 
    {
      $l_newcar = $l_chaine[rand()%strlen($l_chaine)] ;
      if( $l_newcar == "-" or $l_newcar == "$" )
      {
        if( $l_newcar != $l_car )
        {
          $l_Treponse["value"] .= $l_newcar ;
          $l_i_car++ ;
        }
      }
      else
      { 
        $l_Treponse["value"] .= $l_newcar ;
        $l_i_car++ ; 
      }
      
      $l_car = $l_newcar ;
    }
  //lavant dernier separatif
    while( $l_i_car < $len-3 ) 
    {
      $l_newcar = $l_chainesep[rand()%strlen( $l_chainesep )] ;
      if( $l_newcar == "-" or $l_newcar == "!" )
      {
        if($l_newcar != $l_car)
        {
          $l_Treponse["value"] .= $l_newcar ;
          $l_i_car++ ;
        }
      }
      else
      { 
        $l_Treponse["value"] .= $l_newcar ;
        $l_i_car++ ;
      }
      
      $l_car = $l_newcar ;
    }

  //les 3 derniers caracteres
    while( $l_i_car < $len )
    {
      $l_newcar = $l_chainecoz[rand()%strlen( $l_chainecoz )] ;
      $l_Treponse["value"] .= $l_newcar ;
      $l_i_car++ ;
      $l_car = $l_newcar ;
    }
    $l_Treponse[0] = true ;
    return $l_Treponse;
  }
// ------------------------------------


/* ----------------------------------FIN------------------------------------- */
/* Fin Class OBaseFunctions */
}