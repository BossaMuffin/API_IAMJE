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
$OBfuncIncludeJeton = true ;

//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste à répartir les fonctions dans plusieurs classes et renomer correctement les instances

class OBaseFunctions 
{

// Constantes
  const   KEYMAX = 6 ; // doit être supérieur ou égal à 6

// Propriété 
  // initiée dans route_serv.req.php
  public $g_url ;
  // les types possibles dépendes d'abord de la fonction php gettype
  // a chaque type correspond un type SQL (utilisé pour créer les colonnes ou on enregistrera les données)
  // et une taille maximale par défaut
  // ATTENTION il ne peut pas y avoir d'espace dans les types car ils sont utilisé pour etre les noms des tables !! XXXXXXXXXXXXXXx
  // Gérer les types none !!!!!!!!!!!!!!!
  public $p_Ttypes = [  0 => [  "name"  => "unknown type",
                                "bdd"   => "none",
                                "size"  => 0 ],

                        1 => [  "name"  => "NULL",
                                "bdd"   => "none",
                                "size"  => 0 ],

                        2 => [  "name"  => "integer", 
                                "bdd"   => "INT",
                                "size"  => 255 ],

                        3 => [  "name"  => "double", 
                                "bdd"   => "FLOAT",
                                "size"  =>  255 ],

                        4 => [  "name"  => "string", 
                                "bdd"   => "VARCHAR",
                                "size"  => 255 ],

                        5 => [  "name"  => "email", 
                                "bdd"   => "VARCHAR",
                                "size"  => 255 ],

                        6 => [  "name"  => "web", 
                                "bdd"   => "VARCHAR",
                                "size"  => 255 ],

                        7 => [  "name"  => "array", 
                                "bdd"   => "none",
                                "size"  => 0 ],

                        8 => [  "name"  => "object", 
                                "bdd"   => "none",
                                "size"  => 0 ],

                        9 => [  "name"  => "resource", 
                                "bdd"   => "none",
                                "size"  => 0 ],
                        10 => [ "name"  => "timestamp", 
                                "bdd"   => "TIMESTAMP",
                                "size"  => 0 ],
                        11 => [ "name"  => "boolean", 
                                "bdd"   => "BOOL",
                                "size"  => 1 ],
                        12 => [ "name"  => "text", 
                                "bdd"   => "TEXT",
                                "size"  => 0 ]
                      ] ;

  // tableau qui contiendra la trace des erreurs de chaque fonction appelée
  public $p_Terreurs = array() ;
  // variable qui indique si on doit enregistrer la trâce des erreurs des fonctions appelées (par défaut, on garde la trâce)
  public $p_BdevModeRun = true ;
  // affiche le mode développement
  public $p_BdevModeShow ;


/* ---------------- CONSTRUCTEUR ----------------------------- 
* @value : none
* @return : none
*/
  function  __construct( $B_DEVMODESHOW, $B_DEVMODERUN = true )
  {

    $this->p_BdevModeRun = $B_DEVMODERUN ;
    $this->p_BdevModeShow = $B_DEVMODESHOW ;

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



/* --------- DEFINE URL -------------------------------------------------------- 
* Fonction qui enregistre l'url de base de la page dans une propriété 
* @param url de la page
* @return charge l'url dans une propriété de la classe
*/
  function define_url( $g_url )
  {
      $this->g_url = $g_url ;
  // Fin define url
  }
  // ------------------------------------


/* --------- DEV MODE -------------------------------------------------------- 
* Permet d'ajouter à la propriété  p_Terreurs le retour d'erreur d'un fonction appelée
* @param 
*       $err = l_Treponses["err"] ; 
*       $Cmethode = __METHOD__ ; 
*       $Ntabulation = le nombre de tabulation pour faciliter la lecture du tableau p_Terreurs ;
* @return charge le retour d'erreur dans le tableau p_Terreurs propriété de BFUNC
*/
  function dev_mode( $Cmethod, $err )
  {
      // cette chaine contiendra le nombre de tabulation php "&#9" suffisantes par ligne 
      // (en fonction de la longueur du nom de la methode) pour faciliter la lecture du tableau
          $Ctabulation = "" ;

      if ( $this->p_BdevModeRun )
      {
        if ( is_string( $Cmethod ) and isset( $err["id"] ) and isset( $err["com"] ) )
        {
          // on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
          $l_T["method"] = $Cmethod ;
          $l_T["id"] = $err["id"] ;
          $l_T["com"] = $err["com"] ;

          array_push( $this->p_Terreurs, $l_T ) ;

        }

      }

  // fin dev_mode
  }
  // ------------------------------------



/* --------- SHOW OR JSON -------------------------------------------------------- 
* Permet soit un echo pour une var scalable, soit l'appelle de la méthode printr si c'est une var non scalable 
* @param url de la page
* @return charge l'url dans une propriété de la classe
*/
  function showOrJson( $var, $Cjeton, $json = true)
  {
    //if ( $json ){ $var = preg_replace('/[\[\]]/', '', json_encode( $var ) ) ; } // sans les crochets
    if ( $json ){ $var = json_encode( $var ) ; }
    if ( isset( $_GET["show"] ) )
    { 
        if ( $_GET["show"] == $Cjeton )
        {
          if ( $json ) { echo $var  ; } else { var_dump( $var ) ; }
        }
    }
    else 
    {
        $this->show( $var ) ; 
    }

  // fin show or json
  }
  // ------------------------------------

/* --------- SHOW VAR -------------------------------------------------------- 
* Permet soit un echo pour une var scalable, soit l'appelle de la méthode printr si c'est une var non scalable 
* @param url de la page
* @return charge l'url dans une propriété de la classe
*/
  function show( $var, $pIsOpen = true, $pIsSQL = false )
  {
      if ( is_scalar( $var ) )
      {
        echo $var ;
      }
      else
      {
        $this->printr( $var, $pIsOpen = true, $pIsSQL = false ) ;
      }
  // fin show
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
                src="' . $this->g_url . 'img/sort-down.png" border="none" height="30px" />
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

  // fin printr
  }
  // ------------------------------------


/* -------------------------------------- VALIDATE WEB : EMAIL ou URL  --------------------------- 
* valide une url (par defo) ou un email, en fonction de mode (url ou mail)
* @param : une chaine de caractere ressemblant à un email ou une url
* @value : none
* @return : bool de resussite (mail ou url) + val (le type mail ou url) + subval (true si le domaine est valide) 
*/
  function validate_web( $c_var, $option = false )
  {
    $l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
    $l_Treponse["val"] = "" ; 
    $l_Treponse["subval"] = false ; // le domaine de l'url n'est pas validé
    $l_Treponse[0] = false ;

    if ( ! empty( $c_var ) )
    {
      if ( is_string( $c_var ) )
      {
        $l_Cfiltre = array(); 
        // on filtre sur les url
        $l_Cfiltre["url"] = filter_var( $c_var, FILTER_VALIDATE_URL ) ;
        // on filtre sur les email
        $l_Cfiltre["mail"] = filter_var( $c_var, FILTER_VALIDATE_EMAIL ) ;
        
        if ( $l_Cfiltre["mail"] != false )
        {
          // le filtre mail est ok
          $l_Treponse["val"] = $this->p_Ttypes[5]["name"] ;
          $l_Treponse[0] = true ;
        }
        else 
        {
          if ( $l_Cfiltre["url"] != false )
          {
            // le filtre url est ok
            $l_Treponse["val"] = $this->p_Ttypes[6]["name"] ;
            $l_Treponse[0] = true ;
          }
          else 
          {
            // auncun des filtres 'url ou mail n'a fonctionné
            $l_Treponse["err"]["id"] = "3" ;
          }
        } // fin des filtres email et url 

        if ( $l_Treponse[0] )
        {
          
          if ( phpversion() >= 7 )
          {
            // les filtres ont fonctionné, on va tester le domaine
            
            // si l'option est activée, on filtre les domaines selon les règles HOSTNAME :
            // commence par un caractère alphanumberic et contenir uniquement des caractères alphanumériques ou des traits d'union
            if ( $option )
            {
              $l_Cfiltre["domain"] = filter_var( $c_var, FILTER_VALIDATE_DOMAIN ) ;
            }
            else
            {
              $l_Cfiltre["domain"] = filter_var( $c_var, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME ) ;
            }
      
            // on filtre le domaine
            if ( $l_Cfiltre["domain"] != false )
            {
              // le domaine est ok selon le filtre ( norme RFC 1034, RFC 1035, RFC 952, RFC 1123, RFC 2732, RFC 2181 et RFC 1123)
              $l_Treponse["subval"] = true ;        
            } 
          }
          else
          {
            // si c'est un mail
            if ( $l_Treponse["val"] == $this->p_Ttypes[5]["name"] )
            {
              // Regex de nom de domaine
              // $l_regex = '^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$' ;
              // $l_string = preg_replace( $l_regex, '', $c_var ) ;
              //if(empty($l_string)){$l_Treponse[0]=true;}
              $l_TurlGet = explode( "@", $c_var ) ;

              if ( isset( $l_TurlGet[1] ) )
              {
                // on a recupereé la partie domain.com du mail
                $l_Treponse["subval"] = checkdnsrr( $l_TurlGet[1] , "A") ;
              }
              else 
              {
                $l_Treponse["err"]["id"] = "3" ;
              }

            }
            else
            {
              // c'est une url 
              $l_Chostname = parse_url( $c_var, PHP_URL_HOST ) ;
              // on a recupereé la partie domain.com du mail
              $l_Treponse["subval"] = checkdnsrr( $l_Chostname , "A") ;
            }
            
          }


        } // du filtre domaine
        
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
    $this->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    // on charge la reponse à retourner
    return $l_Treponse ;

  // fin validate_web  
  }
  // ---------------------------------


/* -------------------------------------- GETTYPE  --------------------------- 
* determine le type du parametre
* @param : tout type
* @value : none
* @return : 
* Les chaînes de caractères que peut retourner la fonction PHP gettype (injecté dans ["val"]) sont les suivantes :
* "boolean"
* "integer"
* "double"(pour des raisons historiques, "double" est retournée lorsqu'une valeur de type float est fournie, au lieu de la chaîne "float")
* "string"
* "array"
* "object"
* "resource"
* "resource (closed)" à partir de PHP 7.2.0
* "NULL"
* "unknown type"
*/
  function get_type( $var, $option = false )
  {

    $l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
    $l_Treponse["val"] = "" ;
    $l_Treponse["subval"] = "" ;
    $l_Treponse[0] = false ;

    $l_type = gettype( $var ) ;
   
    $l_Treponse["val"] = $l_type ;
    $l_Treponse["subval"] = $l_type ;

    if ( $l_type == "string" )
    {
      if ( ! is_numeric($var) )
      {
        $l_TfiltreWeb = $this->validate_web( $var, $option ) ;
        
        if ( $l_TfiltreWeb[0] )
        {
          $l_Treponse["subval"] = $l_TfiltreWeb["val"] ;
          // c'est un email ou une url
        }
        
      }
      else
      {
        // ATTENTION : pas toujours DOUBLE OU FLOTTANT, 
        // identifie une donnée du type "78" au lieu de 78
        // distingue les entiers ou flottant ou double ... 
        // même sous forme de chaine de caractère (entre guillement) car récupérer d'un "explode" depuis les GET 
        
        $l_type_expl = explode( ".", $var ) ;

        if ( isset( $l_type_expl[1] ) )
        {
          $l_type = "double" ;
        }
        else
        {
          $l_type = "integer" ;
        }

        $l_Treponse["subval"] = $l_type ;

      }
      
    }   

   // on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    $this->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    // on charge la reponse à retourner
    return  $l_Treponse ;

  // fin get type
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

  // fin Url sans parametre
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

  // fin url sans parametre
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
    $l_Treponse["err"] = array( "id" => "0", "com" => "" ) ; ;

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
    

    // on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    $this->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    // on charge la reponse à retourner
    return $l_Treponse;

  // fin genere char key
  }
  // ------------------------------------


/* ----------------------------------FIN------------------------------------- */
/* Fin Class OBaseFunctions */
}