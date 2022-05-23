<?php
/* arborescence dans le site */
    $g_page_arbo = "" ;
    $g_page = "index.php" ;

/* VARIABLES META GLOBALES : var meta et directory /!\ FOLD_INC nest pas encore défini */
// regroupe l'ensemble des variables globales
require($g_page_arbo . 'INC/meta_global_var.req.php') ;
//regroupe les fonctions liées au fonctionnement global de l'IA
require( $g_page_arbo . FOLD_CLASS . 'OBaseFunctions.req.php' ) ;
$BFUNC = new OBaseFunctions() ;
//permet d'aiguiller l'IA entre les différents serveurs
require($g_page_arbo . FOLD_INC . 'route_serv.req.php') ;
// permet d'aiguiller l'IA sur les différentes BDD
require($g_page_arbo . FOLD_INC . 'route_bdd.req.php') ;
// regroupe l'ensemble des fonctions à apprendre
//require($g_page_arbo . FOLD_INC . 'functions_liste.req.php') ;
require( $g_page_arbo . FOLD_CLASS . 'OListeFunctions.req.php' ) ;
$LFUNC = new OListeFunctions() ;
// accumule les ressources acquises au cours des séries d'apprentissages ALPA 
require( $g_page_arbo . FOLD_CLASS . 'ORessources.req.php' ) ;
$RESSOURCES = new ORessources() ;

/* ------------------------------------------------------------------------------------------- */
/* HEADER */
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>IAM-JE</title>
        <base href="<?php echo $g_url ; ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="language" content="fr-FR" />
        <!-- Core Meta Data -->
        <meta name="author" content="Jean-Eudes Méhus" >
        <meta name="publisher" content="Agence Comozone" >
        <meta name="email" content="coz.web@comozone.com">
        <meta name="description" content="Prmière Intelligence Artificielle Par Enseigenment Renforcé." />
        <meta name="keywords" content="Intelligence Artificielle, IA, AI, artificial intelligence" />
        <!-- Humans -->
        <link rel="author" href="https://www.comozone.com/page-ourteam.txt"/>
        <!-- Page Canonique -->
        <link rel="canonical" <?php echo 'href="' . $g_url . $g_page . '"';?> />
        <!-- CSS FILES -->  
        <link rel="stylesheet" href="" >
    </head>
    <body>
        <h1>IAMJE</h1>
<?php
/* Fin header */


/* INTRODUCTION */
// Affichage d'un mot d'accueil
echo 'Je veux apprendre.';
echo '<br/>';
echo 'Je vais commencer par l\'addition';
echo '<br/>';
echo '+';
echo '<br/>';
/* Fin Introdution */

/* ------------------------------------------------------------------------------------------- */
/* FONCTION ELEMENTAIRE "ALPA":"A" */

// EX : L'ADDITION
// On commence par donner un résultat simple à décortiquer en addition, ex : 2
// Si la ressource initiale est basique comme l'identité numérique 1
// 2 = 1+1 ; -> 1 solution
// Je vais créer le module élémentaire d'"apprentissage"
// Il prend en entrée 2 arguments :
//		un tableau regroupant les objectifs de l'apprentissage T_objectifs
//			Valeur à atteindre (valeur finale attendue, type), contraintes (univers), limites(temps de calcul max, nb de calcul max, nb d'opérations max),  
//		un tableau regroupant les ressources disponibles T_ressources
//			Matière de travail (inée ou résultat des apprentissage passés), Outils (opérateurs, séquences d'opérations déjà acquises)
// 		une valeur déterminant le mode d'utilisation de la fonction
//			Apprentissage ou Travail

const MODE = "L" ;
//regroupe l'ensemble des fonctions nécessaires aux fonctionnement du coeur de l'IA : les fonction ALPA
require( $g_page_arbo . FOLD_CLASS . 'OAlpa.req.php' ) ;


/* ----------------------------------- CONTEXTUALISATION DU TRAVAIL 1 ------------------------------------ */

// 0) ---------------- PARAMETRAGE ---------------- 
// l'outil est tiré de la liste des fonctions à apprendre/travailler
// le type de matière est fonction/contraint de/à l'outils
$g_Tressources = array ("matieres"  => 1, "outils" => "addition") ;
$g_Tobjectifs = array ("objectif"  => 2, "distance" => 0.5, "precision" => 0.9, "delais" => 1) ;

// ---------------------------------------------------------------------------------------------- 
// 1) CREATION DE LA NOUVELLE INSTANCE DE TRAVAIL "ALPA":"A" */
$ALPA1 = new OAlpa($g_Tobjectifs, $g_Tressources, MODE, $LFUNC, $BFUNC, $RESSOURCES) ;

// ---------------------------------------------------------------------------------------------- 
// 2) APPEL DE LA FONCTION ELEMENTAIRE EN MODE D'APPRENTISSAGE "ALPA":"A" 
// $g_Tressources["outils"] se trouve dans $LFUNC
$g_objectif_max = 5 ;
$ALPA1->serie_A( $g_objectif_max, $g_Tressources["outils"] ) ;


// ---------------------------------------------------------------------------------------------- 
// AFFICHAGE DE LA SEQUENCE D'APPRENTISSAGE 
//echo $ALPA1->Caffichage_serie_A_ordres ;
//echo $ALPA1->Caffichage_serie_A ;

// ---------------------------------------------------------------------------------------------- 
// FORMATAGE DE L'APPRENTISSAGE  
// on rend l'enseignement intelligible
//echo "<br/><br/>" ;
//print_r( $ALPA1->Tresultats ) ;


/* ------------------------------ CONTEXTUALISATION DU TRAVAIL 2 --------------------------------------- */

// 0) ---------------- PARAMETRAGE ---------------- 
// l'outil est tiré de la liste des fonctions à apprendre/travailler
// le type de matière est fonction/contraint de/à l'outils
$g_Tressources = array ("matieres"  => 5, "outils" => "addition") ;
$g_Tobjectifs = array ("objectif"  => 10, "distance" => 0.5, "precision" => 0.9, "delais" => 1) ;

// ---------------------------------------------------------------------------------------------- 
// 1) CREATION DE LA NOUVELLE INSTANCE DE TRAVAIL "ALPA":"A" */
$ALPA2 = new OAlpa($g_Tobjectifs, $g_Tressources, MODE, $LFUNC, $BFUNC, $RESSOURCES) ;

// ---------------------------------------------------------------------------------------------- 
// 2) APPEL DE LA FONCTION ELEMENTAIRE EN MODE D'APPRENTISSAGE "ALPA":"A" 
// $g_Tressources["outils"] se trouve dans $LFUNC
$g_objectif_max = 100 ;
$ALPA2->serie_A( $g_objectif_max, $g_Tressources["outils"] ) ;

// ---------------------------------------------------------------------------------------------- 
// AFFICHAGE DE LA SEQUENCE D'APPRENTISSAGE 
//echo $ALPA2->p_Caffichage_serie_A_ordres ;
//echo $ALPA2->p_Caffichage_serie_A ;

// ---------------------------------------------------------------------------------------------- 
// FORMATAGE DE L'APPRENTISSAGE  
// on rend l'enseignement intelligible
// echo "<br/><br/>" ;
//print_r( $ALPA2->Tresultats ) ;


/* -------------------------------------- AFFICHAGE DES NOUVELLES RESSOURCES -------------------------------------- */
echo "<br/><br/>" ;
echo "<b><u>Nouvelles ressources</u> :</b><br/>" ;
print_r( $RESSOURCES->p_Tressources) ;


/* ---------------------------------------------------------------------------------------------- */
// 4) ENREGISTREMENT DE L'APPRENTISSAGE  
// on rend réutilisable
echo "<br/><br/>" ;
echo "<b><u>Mémoire</u> :</b><br/>" ;
print_r( $RESSOURCES->p_Tarchives ) ;








/* ----------------------------------FIN------------------------------------- */
/* Fin de la page */
?>
    </body>
</html>