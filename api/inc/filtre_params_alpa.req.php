<?php

/* --------------------------------------  --------------------- -------------------------------------- */
/* --------------------------------------  FILTRE DES PARAMETRES -------------------------------------- */
/* --------------------------------------  --------------------- -------------------------------------- */
$filtresJeton = true ;
$filtresListe = "" ;

// Filtre Faux : pas d'impact // l'absence de cette variable n'est pas blocante
if ( ! $tFiltre["show"] )
{
    $filtresListe .= "show / " ;
}


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

// Filtre Faux : pas d'impact // l'absence de cette variable n'est pas blocante
if ( ! $tFiltre["dev"] )
{
    $filtresListe .= "dev / " ;
}

// Filtre Faux : pas d'impact // l'absence de cette variable n'est pas blocante
if ( ! $tFiltre["err"] )
{
    $filtresListe .= "err / " ;
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

