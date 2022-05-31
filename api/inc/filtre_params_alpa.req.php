<?php

/* --------------------------------------  --------------------- -------------------------------------- */
/* --------------------------------------  FILTRE DES PARAMETRES -------------------------------------- */
/* --------------------------------------  --------------------- -------------------------------------- */
$filtresJeton = true ;
$filtresListe = "" ;

if ( ! $tFiltre["mode"] ) 
{
    $filtresJeton = false ;
    $filtresListe .= "mode / " ;
}

if ( ! $tFiltre["matieres"] ) 
{
    $filtresJeton = false ;
    $filtresListe .= "matieres / " ;
}

if ( ! $tFiltre["outils"] ) 
{
    $filtresJeton = false ;
    $filtresListe .= "outils / " ;
}

if ( ! $tFiltre["objectifs"] ) 
{
    $filtresJeton = false ;
    $filtresListe .= "objectifs / " ;
}

if ( ! $tFiltre["distance"] ) 
{
    $filtresJeton = false ;
    $filtresListe .= "distance / " ;
}

if ( ! $tFiltre["delais"] ) 
{
    $filtresJeton = false ;
    $filtresListe .= "delais / " ;
}

if ( ! $tFiltre["precision"] ) 
{
    $filtresJeton = false ;
    $filtresListe .= "precision / " ;
}

if ( ! $filtresJeton ) 
{
    echo '<h1>IAMJE ERROR</h1><u>' . $g_page . '</u>' ;
    echo '<br/>' . $tErrMess["api"] ;
    echo '<br/>---' ; 
    echo '<br/>' ;
    echo '<br/><i><u>Aide</u> : vérifier les paramètres GET ' . $filtresListe . '</i>' ;
    exit ;
}

/* --------------------------------------  --------------------- -------------------------------------- */
/* --------------------------------------  --------------------- -------------------------------------- */

