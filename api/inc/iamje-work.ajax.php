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
require( $g_page_arbo . "inc/filtre_params_alpa.req.php" ) ;

/* -------------------------------------- TRAVAIL DE RESTITUTION ---------------------------------------- */
// on lance un nouvelle apprentissage
// il doit tenir compte de l'enseignement précédent
/* ------------------------------ CONTEXTUALISATION DU TRAVAIL 3 --------------------------------------- */

// 0) ---------------- PARAMETRAGE ---------------- 
// l'outil est tiré de la liste des fonctions à apprendre/travailler
// le type de matière est fonction/contraint de/à l'outils
$g_Tressources = array ( "matieres"  => 1, "outils" => "addition" ) ;
$g_TobjectifsListe = [20] ;
$g_Tobjectifs = array ( "objectif"  => $g_TobjectifsListe, "distance" => 1, "precision" => 0.9, "delais" => 0.1 ) ;
// objectif[0] = initial, objectif[1] = final (utilisé pour ALPA en série) ;

// ---------------------------------------------------------------------------------------------- 
// 1) CREATION DE LA NOUVELLE INSTANCE DE TRAVAIL "ALPA":"A" */
$l_ALPA = "ALPA3" ;
$l_mode = "WORK" ;
$$l_ALPA = new OAlpa( $l_ALPA, $g_Tobjectifs, $g_Tressources, $l_mode, $LFUNC, $BFUNC, $RESSOURCES ) ;

// ------------------------------------- RECHERCHE DE POSSIBLES ----------------------------------------- 
// ------- utilise un objet "resultat ALPA" pour formater facilement des tableau de ce type en créant des instances du formatage unique
// ----------- fonction bouclée 
// ------------- créer des fonctions secondaires communes (de nommage, de calcul de précision, de timming, et compteur, ou de mise à jour du tableau de resultat "solution")
//$RESULTAT = $$l_ALPA->A( 0 ) ;
$$l_ALPA->serie_A( ) ;
// ou anciennement -----> include($g_page_arbo . FOLD_INC . 'body-recherche-des-possibles-graphique.old.inc.php') ;
//-------
