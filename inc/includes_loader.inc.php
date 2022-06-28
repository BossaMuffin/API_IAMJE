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

//on charge les variables de contexte si le fichier est appelé directement

// VARIABLES META GLOBALES : var meta et directory /!\ FOLD_INC nest pas encore défini 
if ( ! isset( $metaIncludeJeton ) )
{
         require_once( $g_page_arbo . "inc/global_var_meta.req.php" ) ;
}

// regroupe les fonctions liées au fonctionnement global de l'IA
if ( ! isset( $OBfuncIncludeJeton )  )
{
    require_once( $g_page_arbo . FOLD_CLASS . 'OBaseFunctions.req.php' ) ;
    $BFUNC = new OBaseFunctions( $B_DEVMODESHOW, $B_DEVMODERUN ) ;
}

// permet d'aiguiller l'IA entre les différents serveurs
if ( ! isset( $servIncludeJeton ) )
{
    // on initie la propriété g_url de BFUNC
    require_once( $g_page_arbo . FOLD_INC . 'route_serv.req.php' ) ;
}



// permet de charger les messages d'erreurs
if ( ! isset( $messagesIncludeJeton ) )
{
    require_once( $g_page_arbo .  FOLD_INC . "global_var_messages.req.php" ) ;
}

// permet d'aiguiller l'IA sur les différentes BDD
if ( ! isset( $bddIncludeJeton ) )
{
    require_once( $g_page_arbo . FOLD_INC . 'route_bdd.req.php' ) ;
}

// permet de générer des tableaux de résultats préformatés (propriété instanciée à la construction)
if ( ! isset( $OTresultIncludeJeton ) )
{
    require_once( $g_page_arbo . FOLD_CLASS . 'OTresultat.req.php' ) ;
}

// regroupe l'ensemble des fonctions à apprendre
if ( ! isset( $OLfuncIncludeJeton ) )
{
    require_once( $g_page_arbo . FOLD_CLASS . 'OListeFunctions.req.php' ) ;
    $LFUNC = new OListeFunctions() ;
}

// accumule les ressources acquises au cours des séries d'apprentissages ALPA 
if ( ! isset( $ORessourcesIncludeJeton ) )
{
    require_once( $g_page_arbo . FOLD_CLASS . 'ORessources.req.php' ) ;
    $RESSOURCES = new ORessources( $BFUNC ) ;
}

// regroupe l'ensemble des fonctions nécessaires aux fonctionnement du coeur de l'IA : les fonction ALPA
if ( ! isset( $OAlpaIncludeJeton ) )
{
    require_once( $g_page_arbo . FOLD_CLASS . 'OAlpa.req.php' ) ;
}

// permet de charger les filtres de paramètres Alpa
if ( ! isset( $filtresIncludeJeton ) )
{
    require_once( $g_page_arbo . FOLD_INC . "global_var_filtres.req.php" );
}    

