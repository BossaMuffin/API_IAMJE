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
// on affiche le header que si l'option show n'est pas dans les GET -> show=off indique que l'on passe par le formulaire et pas en direct
if ( ! isset($_GET['show']) )
{
    /* HEADER */
    echo '
    <!DOCTYPE html>
    <html lang="fr">
        <head>
            <title>IAM-JE-API</title>
            <base href="' . $BFUNC->g_url . FOLD_API . $g_page . '">
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta http-equiv="content-type" content="application/json" />
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
            <link rel="canonical" href="' . $BFUNC->g_url .  FOLD_API . $g_page . '" />
        </head>
        <body>' ;
    // Nous n'exposons aucune information si la page est appelée  sans precision du mode de fonctionnement de la page
    if ( ! $tFiltre["ia"] ) 
    {
    	echo '<h1>IAMJE ERROR IA</h1><u>' . $BFUNC->g_url .  FOLD_API . $g_page . '</u><br/>' . $tErrMess["api"] ;
    	exit ;
    }
}
// on defini une variable jeton qui permet d'identifier le passage de l'utilisateur sur cette page
$g_Bjeton_index_1 = true ;

// on aiguille en fonction de l'ia demandée
switch ($_GET["ia"]):
    case $tIA[0]:
        require_once( FOLD_INC . "iamje-alpa.ajax.php" ) ;
        break ;
    case $tIA[1]:
       	require_once( FOLD_INC . "iamje-alpa-1.ajax.php" ) ;
        break ;
    default:
       	echo $tErrMess["api"] ;
		exit;
endswitch ;




// on affiche le footer que si l'option show n'est pas dans les GET -> show=off indique que l'on passe par le formulaire et pas en direct
if ( ! isset($_GET['show']) )
{
    echo '<body>
    <html>' ;
}