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

// ------------------------------------- RECHERCHE DE POSSIBLES ----------------------------------------- XXXXXXXXXXXXXXXXXXXXXXX
// -------créer un objet "resultat ALPA" pour formater facilement des tableau de ce type en créant des instances du formatage unique
// -----------créer une fonction globale bouclée 
// ------------- créer des fonctions secondaires communes (de nommage, de calcul de précision, de timming, et compteur, ou de mise à jour du tableau de resultat "solution")

   
// la table qui contiendra la chaine de calcul trouvé pour atteindre l'objectif demandé 
// on implémente l'objectif au tableau de résultat
$RESULTAT = new OTresultat( $g_Tobjectifs["objectif"][0], $BFUNC ) ;
// Initialisation de la limite du temps de calcul
$l_timestamp_ms_difference = 0 ;
// timestamp en millisecondes du début de la boucle
$l_timestamp_ms_debut = microtime( true ) ;
// jeton de boucle while 
$l_while_continue = true ;
$l_while_compteur = 0 ;

while ( $l_while_continue 
        and  ( ( abs( 1 - abs( $RESULTAT->p_T["datas"]["precision"] ) ) > ( 1 - $g_Tobjectifs["precision"] ) ) 
            or $RESULTAT->p_T["datas"]["distance"] <= $g_Tobjectifs["distance"] )
        and $l_timestamp_ms_difference <= $g_Tobjectifs["delais"] )
{
    $l_while_compteur++ ; 
    // On verifie si l'objectif n'a pas déjà été atteint
    // En comparant l'objectif demandée aux ressources existantes
    if ( $l_while_compteur == 1 )
    {
        $g_Tpossibles = $RESSOURCES->recherche_des_possibles( $RESULTAT->p_T["objectif"]["value"] ) ;
    }
    else
    {
        $g_Tpossibles = $RESSOURCES->recherche_des_possibles( $g_Tpossibles["relais"][0]["value"] ) ;
    }
    echo "<br/>";
    echo "<h2>Possibles " . $l_while_compteur . "</h2>" ;

    $BFUNC->printr( $g_Tpossibles, false ) ;
    echo "<br/>";
    echo "<h2>Solution </h2>" ;

// -------------------- MEMORISATION DU CHEMIN DEJA PARCOURU DANS OTRESULTAT  
    // on peut ajouter les matières utilisées et les données (contraintes, objectifs, outils etc) du calcul originel
    // on construit l'id
    $RESULTAT->id( $g_Tpossibles["possibles"][0]["id"] ) ;
    // on ajoute la matière si elle n'a pas été déjà notée dans la description du calcul
    $RESULTAT->mat( $g_Tpossibles["possibles"][0]["matieres"]["value"] ) ;
    // on ajoute l'outils s'il n'a pas été déjà noté dans la description du calcul
    $RESULTAT->outs( $g_Tpossibles["possibles"][0]["outils"]["value"] ) ;
    // on construit la sequence, en concaténant avec la chaine déjà existante
    $RESULTAT->seq( $g_Tpossibles["possibles"][0]["sequence"]["value"] ) ;
    // on somme le résultat au résultat précédent
    $RESULTAT->res( $g_Tpossibles["possibles"][0]["resultat"]["value"] ) ;
    // on recalcul la distance entre l'objectif et le nouveau résultat atteint
    $RESULTAT->dist( ) ;
    // on recalcul la precision(ratio) entre l'objectif et le nouveau résultat atteint
    $RESULTAT->ratio( ) ;
    // On ajoute bêtement les délais des différent tronçons de calculs /!\ les délais ne s'aditione pas forcément
    $RESULTAT->delais( $g_Tpossibles["possibles"][0]["datas"]["delais"] ) ;
    // on somme les compteurs d'opération entre eux
    $RESULTAT->compt( $g_Tpossibles["possibles"][0]["datas"]["compteur"] ) ;
//-----------------------------------------------


    if ( $g_Tpossibles["acquis"] )
    {
        echo "Je connais déjà ce résultat, je sais comment l'atteindre." ;
        echo "<br/>" ;
        // La sequence utile :
        echo "<br/>";
        echo "=> La sequence pour l'atteindre :<br/>" ;
        echo $g_Tpossibles["possibles"][0]["sequence"]["value"] . " = " . $g_Tpossibles["possibles"][0]["resultat"]["value"] ;
        // Clé du chemin de calcul intermédiaire
        echo "<br/>" ;
        echo "Clé du chemin - <i>offset (n° id)</i> : " . $g_Tpossibles["relais"][0]["key"] . " (" . $g_Tpossibles["possibles"][0]["id"] . ")" ;
        echo "<br/>" ;
        $l_while_continue = false ;
    }
    else
    {
        echo "Je n'ai jamais atteint ce résultat mais je sais comment m'en rapprocher." ;
        $BFUNC->printr( $g_Tpossibles["possibles"][0], false ) ;
        echo "<br/>" ;
        echo "Le résultat le plus proches que je connaisse est : " . $g_Tpossibles["possibles"][0]["resultat"]["value"] ;
        echo "<br/>" ;
        echo "<br/>" ;
    // La sequence utile :
        echo "=> La sequence pour l'atteindre :<br/>" ;
        echo $g_Tpossibles["possibles"][0]["sequence"]["value"] . " = " . $g_Tpossibles["possibles"][0]["resultat"]["value"] ;
        echo "<br/>" ;
        // Clé du chemin de calcul intermédiaire
        echo "Clé du chemin - <i>offset (n° id)</i> : " . $g_Tpossibles["relais"][0]["key"] . " (" . $g_Tpossibles["possibles"][0]["id"] . ")" ;
        echo "<br/>" ;
        echo "<br/>" ;
        echo "Il me reste donc <b>" . $g_Tpossibles["relais"][0]["value"] . "</b> à réaliser." ;
        echo "<br/>" ;   
    
        $l_while_continue = true ;
    }



        // timestamp en millisecondes de la fin du script
    $l_timestamp_ms_fin = microtime( true ) ; 
        // différence en millisecondes entre le début et la fin
    $l_timestamp_ms_difference = $l_timestamp_ms_fin - $l_timestamp_ms_debut ;

// Fin de la boucle while
}