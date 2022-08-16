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
// on utilise cette variable pour valider l'include du fichier
$OLfuncIncludeJeton = true ;

/* ------------------------------- LISTE DES OUTILS DÉCLARÉS ---------------------------- */
// Ressource de fonction à apprendre/travailler
// Elle vont contextualisé les paramètres de la CAPSULE deans la fonction de travail A

//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste à répartir les fonctions dans plusieurs classes et renomer correctement les instances

class OListeFunctions 
{

	function __CONSTRUCT()
    {   
    } // fin construct 


	// ADDITION
	function addition( $s1, $s2 ) 
	{
		return $s1+$s2;
	}

	// SOUTRACTION
	function soustraction( $s1, $s2 ) 
	{
		return $s1-$s2;
	}





/* ----------------------------------FIN------------------------------------- */
/* Fin Class OListeFunctions */
}