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
     $g_page_arbo = "../" ;
}

if ( ! isset( $g_page ) )
{
     $g_page = "index.php" ;
}

/* Charge tous les fichiers require_once si besoin */
require( $g_page_arbo . "inc/includes_loader.inc.php" ) ;

/* ------------------------------------------------------------------------------------------- */
/* HEADER */
echo '
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>IAM-JE-API</title>
        <base href="' . $BFUNC->g_url . '">
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
        <link rel="canonical" href="' . $BFUNC->g_url . $g_page . '" />
        <!-- CSS FILES  --> 
        <link type="text/css" rel="stylesheet" href="' . $g_page_arbo . FOLD_CSS . 'chess.css" />
        <link type="text/css" rel="stylesheet" href="' . $g_page_arbo . FOLD_CSS . FOLD_SKINS . 'gnome-chess.css" /> 
    </head>
    <body>' ;
// Nous n'exposons aucune information si la page est appelée  sans precision du mode de fonctionnement de la page
if ( ! $tFiltre["ia"] ) 
{
	echo '<h1>IAMJE ERROR</h1><u>index.php</u><br/>' . $tErrMess["api"] ;
	exit ;
}


switch ($_GET["ia"]) :
    case $tIA[0]:
        require_once( FOLD_INC . "iamje-alpa.ajax.php" );
        break;
    case $tIA[1]:
       	require_once( FOLD_INC . "iamje-alpa-1.ajax.php" );
        break;
    default:
       	echo $tErrMess["api"];
		exit;
endswitch;





echo '<body>
<html>' ;