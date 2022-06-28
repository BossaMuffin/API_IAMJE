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
     $g_page = "api/inc/iamje-work.ajax.php" ;
}

/* Charge tous les fichiers require_once si besoin */
include( $g_page_arbo . "inc/includes_loader.inc.php" ) ;

/* Filtre tous les paramètres GET nécessairent au bon fonctionnement d'Alpa */
require( FOLD_INC . "filtre_params_alpa.req.php" ) ;

/* les variables attendues sint dans le GET
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
$test = $BFUNC->get_type($_GET["obj"]) ;

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
$BFUNC->show( $$l_ALPA->affichage_serie_A_ordres( ) ) ;
//ou (si apres la methode serieA) : echo $$l_ALPA->p_Caffichage_serie_A ;


// Resultats
$BFUNC->show( $$l_ALPA->p_Tresultats ) ;

// Archives
$BFUNC->show( $RESSOURCES->p_Tarchives ) ;

// Ressources
$BFUNC->show( $RESSOURCES->p_Tressources ) ;

echo "TRACES NOMS:" ;
(string) $BFUNC->show( $BDD->p_TnewTtraces ) ;
echo "RESSOURCES NOMS:" ;
$BFUNC->show( $BDD->p_TnewTressources ) ;
echo "ARCHIVES NOMS:" ;
$BFUNC->show( $BDD->p_TnewTarchives ) ;

include( $g_page_arbo . FOLD_INC . "body-dev-erreurs.inc.php" ) ;

/* TEST BDD -> NEW TABLE, DROP TABLE, NEW COLUMN*/ 
/*
$Ctab_nom = "bite" ;

$newT = $BDD->tab_create( $Ctab_nom ) ;
if ( $newT[0] ){ echo "TABLE CREE" ; }else{ echo "OUPS" ; } ;

$newT = $BDD->tab_drop( "hello" ) ;
if ( $newT[0] ){ echo "TABLE SUPPRIMEE" ; }else{ echo "OUPS" ; } ;

$newT = $BDD->tab_show( ) ;
if ( $newT[0] ){ $BFUNC->show( $newT ) ; }else{ echo "OUPS" ; } ;


$newT = $BDD->col_show( $Ctab_nom ) ;
if ( $newT[0] ){ $BFUNC->show( $newT ) ; }else{ echo "OUPS" ; } ;





$newT = $BDD->col_show( $Ctab_nom ) ;
if ( $newT[0] ){ $BFUNC->show( $newT ) ; }else{ echo "OUPS" ; } ;

$l_var = "ee\"e" ;
$l_type = $BFUNC->get_type( $l_var ) ;
$BFUNC->show( $l_type["val"] ) ;
echo "<br/>"; 

$l_web = $BFUNC->validate_web("https://hello@)comozone.com") ;
echo $l_web[0] ;
echo "</br>" ;
echo $l_web["val"] ;


$newT = $BDD->col_show( "string" ) ;
if ( $newT[0] ){ $BFUNC->show( $newT ) ; }else{ echo "OUPS" ; } ;

$l_newRessource = $BDD->check_ressource(2.3) ;
$BFUNC->show( $l_newRessource[0] ) ;

$newT = $BDD->col_show( "string" ) ;
if ( $newT[0] ){ $BFUNC->show( $newT ) ; }else{ echo "OUPS" ; } ;



$newT = $BDD->check_ressource("string") ;
echo $newT["err"] ;

$CtabName  = "test2" ;
$Ccol = "valeur" ;
$CcolId = "Cid" ;
$CcolObj = "objectif" ;
$CcolMat = "matieres" ;
$CcolOuts = "outils" ;
$CcolSeq = "sequence" ;
$CcolResult = "resultat" ;
$CcolDist = "distance" ;
$CcolRatio = "precision" ;
$CcolDelais = "delais" ;
$CcolCompt = "compteur" ;
$l_newArchiveT = $BDD->tab_archive_A_create( $CtabName, $CcolId, $CcolObj, $CcolMat, $CcolOuts, $CcolSeq, $CcolResult, $CcolDist, $CcolRatio, $CcolDelais, $CcolCompt ) ;
echo $l_newArchiveT["err"] ;
*/
// ---------------------------------------------------------------------------------------------- 