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



/* ----------------------------------- CONTEXTUALISATION DU TRAVAIL 1 ------------------------------------ */

// 0) ---------------- PARAMETRAGE ---------------- 
// l'outil est tiré de la liste des fonctions à apprendre/travailler
// le type de matière est fonction/contraint de/à l'outils

$g_Tressources = array ( "matieres"  => $_GET["mat"], "outils" => $_GET["outs"] ) ;
$g_TobjectifsListe = explode( ",", $_GET["obj"] ) ;

$g_Tobjectifs = array ( "objectif"  => $g_TobjectifsListe, "distance" => $_GET["dist"], "precision" => $_GET["ratio"], "delais" => $_GET["delais"] ) ;


// ---------------------------------------------------------------------------------------------- 
// 1) CREATION DE LA NOUVELLE INSTANCE DE TRAVAIL "ALPA":"A" 
$l_ALPA = "ALPA1" ;
$$l_ALPA = new OAlpa( $l_ALPA, $g_Tobjectifs, $g_Tressources, $_GET["mode"], $LFUNC, $BFUNC, $RESSOURCES ) ;

// ---------------------------------------------------------------------------------------------- 
// 2) APPEL DE LA FONCTION ELEMENTAIRE EN MODE D'APPRENTISSAGE "ALPA":"A" 
// $g_Tressources["outils"] se trouve dans $LFUNC
$$l_ALPA->serie_A( ) ;


/* --------------------------------------  --------------------- -------------------------------------- */
/* --------------------------------------  ----- AFFICHAGE ----- -------------------------------------- */
/* --------------------------------------  --------------------- -------------------------------------- */

// AFFICHAGE DES ORDRES DE RESTITUTION 
echo $$l_ALPA->affichage_serie_A_ordres( ) ;
//ou (si apres la methode serieA) : echo $$l_ALPA->p_Caffichage_serie_A ;


// Resultats
$BFUNC->printr( $$l_ALPA->p_Tresultats, $g_url ) ;

// Archives
$BFUNC->printr( $RESSOURCES->p_Tarchives, $g_url ) ;

// Ressources
$BFUNC->printr( $RESSOURCES->p_Tressources, $g_url ) ;


// ---------------------------------------------------------------------------------------------- 