<?php

//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste à répartir les fonctions dans plusieurs classes et renomer correctement les instances

class OBaseFunctions 
{

// Constantes
  const   KEYMAX = 6 ; // doit être supérieur ou égal à 6


/* ---------------- CONSTRUCTEUR ----------------------------- 
* @value : none
* @return : none
*/
  function  __construct()
  {
  // fin construct
  }
  // ------------------------------------

/* ------------------- CLONE ----------------------- 
* Empêche le clonage
* @value : none
* @return : none
*/
  private function  __clone()
  {
  // fin clone
  }
  // ------------------------------------


/* --------- PRINTR -------------------------------------------------------- 
* Fonction d'affichage préformaté de variable non typée pour nos débuggages
* @param unknow $var : variable, tableau, objet... à afficher
* @param string $pIsSQL : True => Mode affichage SQL / String => Couleur du conteneur
* @param bool $pIsOpen : True => Conteneur déplié par défaut
* @return Code HTML d'un conteneur dépliable / repliable avec scrollbar auto
*/
  function printr( $var, $pIsOpen = true, $pIsSQL = false )
  {
      $lColor = ( is_string( $pIsSQL ) ? $pIsSQL : ( $pIsSQL === true ? '#FFF5DD' : '#F2FFEE' ) ) ;
      $pIsSQL = ( $pIsSQL === true || $lColor == '#FEE' ) ;
      $var = ( $pIsSQL === true ? wordwrap( $var . ";\n", 100 ) : $var ) ;
      $lHeight = ( $pIsSQL === true ? '100px' : '200px' ) ;
      $lUniqId = uniqid( md5( rand() ) ) ;

      echo '<table  cellspacing="0" cellpadding="0" 
                    style=" width:100%;
                            border:1px dashed gray;
                            background-color:' . $lColor . ';">
        <tr>
          <td>
            <a  style=" display:block;
                        padding:4px;" 
                title="Cliquer pour ouvrir ou fermer l\'affichage détaillé"
                href="javascript:void(0);"
                onClick="var tr = document.getElementById(\'printr_' . $lUniqId . '\');
                  if (tr.style.display!=\'none\') tr.style.display = \'none\';
                  else tr.style.display = \'table-row\';"><img
                src="img/sort-down.png" border="none" height="30px" />
            </a>
          </td>
        </tr>
        <tr style="display:' . ( $pIsOpen ? 'table-row':'none') .';"
          id="printr_' . $lUniqId . '"><td><textarea
          style=" padding:2 5px;
                  width:100%;
                  overflow:auto;
                  height:' . $lHeight . ';
                  background-color:transparent;
                  border:none;
                  border-top:1px dashed gray;
                  font-size:11px;
                  font-family:monospace;"
        title="Affichage avec print_r() pour debug" ' . ($pIsSQL===true ? ' onFocus="select();"' : '' ) . '>' ;

      @print_r( $var ) ;

      echo '</textarea></td></tr></table>' ;
  }
  // ------------------------------------



/* -------------------------------------- recupere lurl appelé sans parametre --------------------------- 
* @param :
* @value : none
* @return : char $l_TurlGet[0]
*/
  function UrlSansParametres()
  {
    $l_urlCourante = $_SERVER['REQUEST_URI'] ;
    $l_TurlGet = explode( "?", $l_urlCourante ) ;
    return  $l_TurlGet[0] ;
  }
  // ------------------------------------



/* ---------------------------------- annonce si le parametre est le sous domaine de lurl appelée -------------- 
* @param :
* @value : 
* @return : booléen
*/
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
  // ------------------------------------


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
    while ( $l_i_car < $len-4 ) 
    {
      $l_newcar = $l_chaine[rand()%strlen($l_chaine)] ;
      if ( $l_newcar == "-" or $l_newcar == "$" )
      {
        if ( $l_newcar != $l_car )
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
    while ( $l_i_car < $len-3 ) 
    {
      $l_newcar = $l_chainesep[rand()%strlen( $l_chainesep )] ;
      if ( $l_newcar == "-" or $l_newcar == "!" )
      {
        if ( $l_newcar != $l_car )
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
    while ( $l_i_car < $len )
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