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

/* arborescence dans le site */
if ( ! isset( $g_page_arbo ) )
{
     $g_page_arbo = "../../" ;
}

if ( ! isset( $g_page ) )
{
     $g_page = "api/inc/iamje-alpa.ajax.php" ;
}

/* Charge tous les fichiers require_once si besoin */
include( $g_page_arbo . "inc/includes_loader.inc.php" ) ;

/* Filtre tous les paramètres GET nécessaires au bon fonctionnement d'Alpa */
$l_Cprefixe_1 = "" ; // on utilise se préfixe pour pouvoir appeler la page directement.
// le jeton n'est VRAI que si l'utilisateur est passé par l'index, faux sinon
if ( isset( $g_Bjeton_index_1 ) ){ $l_Cprefixe_1 = FOLD_INC ;  } else { header('Content-type: application/json'); }
require( $l_Cprefixe_1 . "filtre_params_alpa.req.php" ) ;

/* les variables attendues sont dans le GET
*   ?ia=alpa
*   &mode=learn|work
*   &mat=1
*   &outs=addition
*   &obj=10&dist=0.5
*   &delais=1000
*   &ratio=1
*   &dev=(defo)false
*   &err=(defo)true

/* ----------------------------------- CONTEXTUALISATION DU TRAVAIL 1 ------------------------------------ */

// 0) ---------------- PARAMETRAGE ---------------- 
// l'outil est tiré de la liste des fonctions à apprendre/travailler
// le type de matière est fonction/contraint de/à l'outils

$g_Tressources = array ( "matieres"  => $_GET["mat"], "outils" => $_GET["outs"] ) ;
$g_TobjectifsListe = explode( ",", $_GET["obj"] ) ;

//$test = $BFUNC->get_type($_GET["obj"]) ;

$g_Tobjectifs = array ( "objectif"  => $g_TobjectifsListe, "distance" => $_GET["dist"], "precision" => $_GET["ratio"], "delais" => $_GET["delais"] ) ;


// ---------------------------------------------------------------------------------------------- 
// 1) CREATION DE LA NOUVELLE INSTANCE DE TRAVAIL "ALPA":"A" 
$l_ALPA = "ALPA1" ;
$$l_ALPA = new OAlpa( $l_ALPA, $g_Tobjectifs, $g_Tressources, $_GET["mode"] ) ;
// ---------------------------------------------------------------------------------------------- 
// 2) APPEL DE LA FONCTION ELEMENTAIRE EN MODE D'APPRENTISSAGE "ALPA":"A" dans CONSTRUCT
// $g_Tressources["outils"] se trouve dans $LFUNC
// Dans CONSTRUCT : $$l_ALPA->serie_A( ) ;




/* --------------------------------------  --------------------- -------------------------------------- */
/* --------------------------------------  ----- AFFICHAGE ----- -------------------------------------- */
/* --------------------------------------  --------------------- -------------------------------------- */

// AFFICHAGE DES ORDRES DE RESTITUTION 
//$BFUNC->show( $$l_ALPA->affichage_serie_A_ordres( ) ) ;
//ou (si apres la methode serieA) : echo $$l_ALPA->p_Caffichage_serie_A ;


// on aiguille en fonction du mode demandé
/*
$tMode[0] = "learn" ;
$tMode[1] = "work" ;
*/
switch ($_GET["mode"]) :
    case $tMode[0]:
        // Retour ALPA en mode LEARN (vardump si besoin par le formulaire  ou un affichage "show" si passage en direct )
        $BFUNC->showOrJson( $$l_ALPA->p_Tresultats, true ) ; // ajouter param false pour obtenir un tableau PHP au lieu du JSON
        break ;

    case $tMode[1]:
        // Retour ALPA en mode WORK
        $BFUNC->showOrJson( $$l_ALPA->p_Tresultats, true ) ;
        break;

    default:
        echo $tErrMess["api"];
        exit;
endswitch;


include( $g_page_arbo . FOLD_INC . "body-dev-erreurs.inc.php" ) ;

//$BFUNC->showOrJson( $BFUNC->p_Terreurs, "off") ; 




/* on va enregister les errurs dans un fichier qu'on rouvrira en js coté client
// 1 : on ouvre le fichier
$monfichier = fopen( 'http://iamje/api/inc/erreurs.txt', 'a' );

// 2 : on execute et on recupere le fichier à inclure dans le fichier text
//$devErreurs = include( $g_page_arbo . FOLD_INC . "body-dev-erreurs.inc.php" ) ;
// 3 : on ecrit dans le fichier
fputs( $monfichier, 'bite' );

// 4 : quand on a fini de l'utiliser, on ferme le fichier
fclose( $monfichier ) ;
*/



/*
echo "TRACES NOMS:" ;
(string) $BFUNC->show( $BDD->p_TnewTtraces ) ;
echo "<br/>" ;
echo "RESSOURCES NOMS:" ;
$BFUNC->show( $BDD->p_TnewTressources ) ;
echo "ARCHIVES NOMS:" ;
$BFUNC->show( $BDD->p_TnewTarchives ) ;
*/

// ---------------------------------------------------------------------------------------------- 