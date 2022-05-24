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
$RESSOURCES = new ORessources( $BFUNC ) ;

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

//regroupe l'ensemble des fonctions nécessaires aux fonctionnement du coeur de l'IA : les fonction ALPA
require( $g_page_arbo . FOLD_CLASS . 'OAlpa.req.php' ) ;


/* --------------------------------------  --------------------- -------------------------------------- */
/* --------------------------------------  --------------------- -------------------------------------- */


/* ----------------------------------- CONTEXTUALISATION DU TRAVAIL 1 ------------------------------------ */

// 0) ---------------- PARAMETRAGE ---------------- 
// l'outil est tiré de la liste des fonctions à apprendre/travailler
// le type de matière est fonction/contraint de/à l'outils
$g_Tressources = array ( "matieres"  => 1, "outils" => "addition" ) ;
$g_Tobjectifs = array ( "objectif"  => [2, 6], "distance" => 0.5, "precision" => 0.9, "delais" => 1 ) ;
// objectif[0] = initial, objectif[1] = final (utilisé pour ALPA en série) ;

// ---------------------------------------------------------------------------------------------- 
// 1) CREATION DE LA NOUVELLE INSTANCE DE TRAVAIL "ALPA":"A" */
$l_ALPA = "ALPA1" ;
$l_mode = "LEARN" ;
$$l_ALPA = new OAlpa( $l_ALPA, $g_Tobjectifs, $g_Tressources, $l_mode, $LFUNC, $BFUNC, $RESSOURCES ) ;

// ---------------------------------------------------------------------------------------------- 
// AFFICHAGE DES ORDRES D'APPRENTISSAGE 
echo $$l_ALPA->affichage_serie_A_ordres( $g_Tobjectifs["objectif"][1] ) ;
//ou (si apres la methode serieA) : echo $$l_ALPA->p_Caffichage_serie_A_ordres ;

// ---------------------------------------------------------------------------------------------- 
// 2) APPEL DE LA FONCTION ELEMENTAIRE EN MODE D'APPRENTISSAGE "ALPA":"A" 
// $g_Tressources["outils"] se trouve dans $LFUNC
$$l_ALPA->serie_A( $g_Tobjectifs["objectif"][1], $g_Tressources["outils"] ) ;

// ---------------------------------------------------------------------------------------------- 
// AFFICHAGE DE LA SEQUENCE D'APPRENTISSAGE 
//echo $$l_ALPA->p_Caffichage_serie_A ;


// ---------------------------------------------------------------------------------------------- 
// 3) FORMATAGE DE L'APPRENTISSAGE  
// on rend l'enseignement intelligible
echo "<br/><br/>" ;
echo "<b><u>Résultat formaté</u> :</b><br/>" ;
$BFUNC->printr( $$l_ALPA->p_Tresultats, false ) ;


/* --------------------------------------  --------------------- -------------------------------------- */
/* --------------------------------------  --------------------- -------------------------------------- */

/* ------------------------------ CONTEXTUALISATION DU TRAVAIL 2 --------------------------------------- */

// 0) ---------------- PARAMETRAGE ---------------- 
// l'outil est tiré de la liste des fonctions à apprendre/travailler
// le type de matière est fonction/contraint de/à l'outils
$g_Tressources = array ( "matieres"  => 10, "outils" => "addition" ) ;
$g_Tobjectifs = array ( "objectif"  => [100, 130], "distance" => 0.5, "precision" => 0.9, "delais" => 1 ) ;
// objectif[0] = initial, objectif[1] = final (utilisé pour ALPA en série) ;
// ---------------------------------------------------------------------------------------------- 
// 1) CREATION DE LA NOUVELLE INSTANCE DE TRAVAIL "ALPA":"A" */
$l_ALPA = "ALPA2" ;
$l_mode = "LEARN" ;
$$l_ALPA = new OAlpa( $l_ALPA, $g_Tobjectifs, $g_Tressources, $l_mode, $LFUNC, $BFUNC, $RESSOURCES ) ;

// ---------------------------------------------------------------------------------------------- 
// AFFICHAGE DES ORDRES D'APPRENTISSAGE 
echo $$l_ALPA->affichage_serie_A_ordres( $g_Tobjectifs["objectif"][1] ) ;
//ou (si apres la methode serieA) : echo $$l_ALPA->p_Caffichage_serie_A_ordres ;

// ---------------------------------------------------------------------------------------------- 
// 2) APPEL DE LA FONCTION ELEMENTAIRE EN MODE D'APPRENTISSAGE "ALPA":"A" 
// $g_Tressources["outils"] se trouve dans $LFUNC
$$l_ALPA->serie_A( $g_Tobjectifs["objectif"][1], $g_Tressources["outils"] ) ;

// ---------------------------------------------------------------------------------------------- 
// AFFICHAGE DE LA SEQUENCE D'APPRENTISSAGE 
//echo $$l_ALPA->p_Caffichage_serie_A ;

// ---------------------------------------------------------------------------------------------- 
// 3) FORMATAGE DE L'APPRENTISSAGE  
//on rend l'enseignement intelligible
//echo "<br/><br/>" ;
echo "<b><u>Résultat formaté</u> :</b><br/>" ;
$BFUNC->printr( $$l_ALPA->p_Tresultats, false ) ;


/* --------------------------------------  --------------------- -------------------------------------- */
/* --------------------------------------  --------------------- -------------------------------------- */

/* -------------------------------------- ENREGISTREMENT DE L'APPRENTISSAGE ---------------------------------------- */
// 4) ENREGISTREMENT DES RESULTATS ET DES RESSOURCES DÉCOUVERTES  
// on rend réutilisable
echo "<br/><br/>" ;
echo "<b><u>Mémoire</u> :</b><br/>" ;
$BFUNC->printr( $RESSOURCES->p_Tarchives ) ;

echo "<br/><br/>" ;
echo "<h2> > 1er RÉSULTAT D'APPRENTISSAGE</h2>" ;

/* -------------------------------------- AFFICHAGE DES NOUVELLES RESSOURCES -------------------------------------- */
echo "<b><u>Nouvelles ressources</u> :</b><br/>" ;
$BFUNC->printr( $RESSOURCES->p_Tressources, false)  ;



/* --------------------------------------  --------------------- -------------------------------------- */
/* --------------------------------------  --------------------- -------------------------------------- */

/* -------------------------------------- TRAVAIL DE RESTITUTION ---------------------------------------- */
// on lance un nouvelle apprentissage
// il doit tenir compte de l'enseignement précédent
/* ------------------------------ CONTEXTUALISATION DU TRAVAIL 3 --------------------------------------- */

// 0) ---------------- PARAMETRAGE ---------------- 
// l'outil est tiré de la liste des fonctions à apprendre/travailler
// le type de matière est fonction/contraint de/à l'outils
$g_Tressources = array ( "matieres"  => 5, "outils" => "addition" ) ;
$g_Tobjectifs = array ( "objectif"  => [20, 100], "distance" => 0.5, "precision" => 0.9, "delais" => 1 ) ;
// objectif[0] = initial, objectif[1] = final (utilisé pour ALPA en série) ;

// ---------------------------------------------------------------------------------------------- 
// 1) CREATION DE LA NOUVELLE INSTANCE DE TRAVAIL "ALPA":"A" */
$l_ALPA = "ALPA3" ;
$l_mode = "WORK" ;
$$l_ALPA = new OAlpa( $l_ALPA, $g_Tobjectifs, $g_Tressources, $l_mode, $LFUNC, $BFUNC, $RESSOURCES ) ;

// ---------------------------------------------------------------------------------------------- 
// AFFICHAGE DES ORDRES DE RESTITUTION 
echo $$l_ALPA->affichage_serie_A_ordres( $g_Tobjectifs["objectif"][1] ) ;
//ou (si apres la methode serieA) : echo $$l_ALPA->p_Caffichage_serie_A_ordres ;



// ------------------------------------- RECHERCHE DE POSSIBLES ----------------------------------------- 
// On verifie si l'objectif n'a pas déjà été atteint
// En comparant l'objectif demandée aux ressources existantes
$g_Tpossibles = $RESSOURCES->recherche_des_possibles( $g_Tobjectifs["objectif"][0] ) ;
$BFUNC->printr( $g_Tpossibles ) ;
$parcours = "g_Tpossibles['relais'][0]['value']" ;
echo $$parcours ;
// -------------------------- CONSTRUCTION ET PARCOURS DE L'ARBRE DES POSSIBLES  ------------------------------------
// fonction boucle qui s'appelle elle même avec des limite de temps de calcul comme contrainte 
    // timestamp en millisecondes du début du script
$l_timestamp_ms_global_debut = microtime( true ) ;
$l_timestamp_ms_local_debut = microtime( true ) ;


//$l_TarbresDesRecherches = boucle( $g_Tpossibles, $l_timestamp_ms_local_debut, $l_timestamp_ms_global_debut, $RESSOURCES ) ;

//$BFUNC->printr( $l_TarbresDesRecherches ) ;

/* --------------------------------------  --------------------- -------------------------------------- */
/* --------------------------------------  --------------------- -------------------------------------- */


echo "<br/><br/>" ;
echo "<h2> > RÉSULTAT DE RESTITUTION</h2>" ;

/* -------------------------------------- AFFICHAGE DES RESSOURCES APRÈS RESTITUTION -------------------------------------- */
echo "<b><u>Nouvelles ressources</u> :</b><br/>" ;
$BFUNC->printr( $RESSOURCES->p_Tressources, false)  ;





echo "<br/><br/><b> FIN </b><br/><br/>" ;
/* ----------------------------------FIN------------------------------------- */
/* Fin de la page */
?>
    </body>
</html>