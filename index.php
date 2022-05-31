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
     $g_page_arbo = "" ;
}

if ( ! isset( $g_page ) )
{
     $g_page = "index.php" ;
}
/* VARIABLES META GLOBALES : var meta et directory /!\ FOLD_INC nest pas encore défini */
// regroupe l'ensemble des variables globales (PATH et META DATAS)
require_once( $g_page_arbo . 'inc/global_var_meta.req.php') ;
// regroupe l'ensemble des variables globales (ERRORS et MESSAGES)
include( $g_page_arbo . FOLD_INC . 'global_var.inc.php' ) ;
//regroupe les fonctions liées au fonctionnement global de l'IA
require_once( $g_page_arbo . FOLD_CLASS . 'OBaseFunctions.req.php' ) ;
$BFUNC = new OBaseFunctions() ;
//permet d'aiguiller l'IA entre les différents serveurs
require_once( $g_page_arbo . FOLD_INC . 'route_serv.req.php' ) ;
// permet d'aiguiller l'IA sur les différentes BDD
require_once( $g_page_arbo . FOLD_INC . 'route_bdd.req.php' ) ;

/* ------------------------------------------------------------------------------------------- */
/* HEADER */
echo '
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>IAM-JE</title>
        <base href="' . $g_url . '">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="language" content="fr-FR" />
        <!-- Core Meta Data -->
        <meta name="author" content="Jean-Eudes Méhus" >
        <meta name="publisher" content="Agence Comozone" >
        <meta name="email" content="coz.web@comozone.com">
        <meta name="description" content="Première Intelligence Artificielle Par Enseigenment Renforcé." />
        <meta name="keywords" content="Intelligence Artificielle, IA, AI, artificial intelligence" />
        <!-- Humans -->
        <link rel="author" href="https://www.comozone.com/page-ourteam.txt"/>
        <!-- Page Canonique -->
        <link rel="canonical" href="' . $g_url . $g_page . '" />
        <!-- CSS FILES  --> 
        <link type="text/css" rel="stylesheet" href="' . $g_page_arbo . FOLD_CSS . 'chess.css" />
        <link type="text/css" rel="stylesheet" href="' . $g_page_arbo . FOLD_CSS . FOLD_SKINS . 'gnome-chess.css" /> 
    </head>
    <body>
        <h1>IAMJE</h1>' ;
/* Fin header */


/* INTRODUCTION */
// Affichage d'un mot d'accueil
echo 'Je veux apprendre.';
echo '<br/>';
echo 'Je vais commencer par l\'addition';
echo '<br/>';
echo '+';

/* Fin Introdution */





/* -------------------------------------- REPRESENTATION GRAPHIQUE 1D DU CALCUL -------------------------------------- */

echo "<h2> REPRÉSENTATION GRAPHIQUE </h2>" ;
echo "<br/>" ;

// Construction d'une grille horizontale pour la progression d'un piont graphique de 0 à Objectif 
// Construction d'une grille horizontale pour la progression d'un piont graphique de 0 à Objectif 
include($g_page_arbo . FOLD_INC . 'body-representation-graphique-1d.inc.php') ;

echo "<br/><br/><b> FIN </b><br/><br/>" ;
/* ----------------------------------FIN------------------------------------- */
/* Fin de la page de clacul */



echo '<body>
<html>' ;