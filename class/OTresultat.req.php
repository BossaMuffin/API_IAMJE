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
$OTresultIncludeJeton = true ;

/* ----------------------------CLASS ALPA---------------------------------------------- */
// La classe Tresultat permet de créer un tableau de résultat préformaté et donc générique à toutes les autres class qui auraient besoin d'un résultat

class OTresultat
{

// Constantes
    const TEST = 0 ;

// Propriétés
    // Tableau préformaté pour les résultat de calcul Alpa
    public $p_T = array() ;
  
// Var de construction
    public $g_BFUNC ;




/* ---------------- CONSTRUCTEUR ----------------------------- 
* @value : $g_objectif, BFUNC
* @return : $g_BFUNC, $p_T
*/
    function __construct( $g_objectif, $BFUNC )
    {       
        
        $this->p_T["id"] = "" ;
        $this->p_T["objectif"]["value"] = $g_objectif ;
        $this->p_T["matieres"]["value"] = array() ;
        $this->p_T["outils"]["value"] = array() ;
        $this->p_T["sequence"]["value"] = "" ;
        $this->p_T["resultat"]["value"] = 0 ;
        $this->p_T["datas"]["distance"] = $g_objectif ;
        $this->p_T["datas"]["precision"] = 0 ;
        $this->p_T["datas"]["delais"] = 0 ;
        $this->p_T["datas"]["compteur"] = 0 ;
        
        // pour utiliser la classe de fonctions basiques
        $this->g_BFUNC = $BFUNC ;

    // fin construct 
    } 
    // ------------------------------------

/* ------------------- CLONE ----------------------- 
* Empêche le clonage
* @value : none
* @return : none
*/
    private function  __clone()
    {
    // fin clone
    }
    // ------------------------------------



/* ------------------- ADD ID ----------------------- 
* Ajoute l'id
* @value : none
* @return : false si il y a déjà un id, true sinon
*/  
    function id( $Cparam )
    {
        // on concatène l'id au tableau de résultat
        $this->p_T["id"] .= $Cparam ;

        if ( $this->p_T["id"] == "" )
        {
            return true ;
        }
        else
        {
            return false ;
        }

    // fin id
    }
    // ------------------------------------


/* ------------------- ADD MATIERES ----------------------- 
* Ajoute la matiere
* @value : none
* @return : false si il y a déjà, true sinon
*/  
    function mat( $Tparam )
    {
        // on verifie que la matiere n'a pas été déjà noté dans la description du calcul
        $l_Btest = in_array( $Tparam , $this->p_T["matieres"]["value"] ) ;

        if ( ! $l_Btest )
        {
            array_push( $this->p_T["matieres"]["value"], $Tparam ) ;
            return true ;
        }
        else
        {
            return false ;
        }

    // fin matieres
    }
    // ------------------------------------


/* ------------------- ADD OUTILS ----------------------- 
* Ajoute les outils
* @value : none
* @return : false si il y a déjà, true sinon
*/  
    function outs( $Tparam )
    {
        // on verifie que l'outil n'a pas été déjà noté dans la description du calcul
        $l_Btest = in_array( $Tparam , $this->p_T["outils"]["value"] ) ;

        if ( ! $l_Btest )
        {
            array_push( $this->p_T["outils"]["value"], $Tparam ) ;
            return true ;
        }
        else
        {
            return false ;
        }

    // fin outils
    }
    // ------------------------------------


/* ------------------- ADD SEQUENCE ----------------------- 
* Ajoute la sequence de calcul (le chemin de reflexion)
* @value : none
* @return : false si il y a déjà, true sinon
*/  
    function seq( $Cparam )
    {
        $this->p_T["sequence"]["value"] .= $Cparam ;

        if ( $this->p_T["sequence"]["value"] == "" )
        {
            return true ;
        }
        else
        {
            return false ;
        }

    // fin sequence
    }
    // ------------------------------------


/* ------------------- ADD RESULTAT ----------------------- 
* Ajoute le resultat (même type que l'objectif)
* @value : none
* @return : false si il y a déjà, true sinon
*/  
    function res( $Rparam )
    {
        $this->p_T["resultat"]["value"] += $Rparam ;

        if ( $this->p_T["resultat"]["value"] == 0 )
        {
            return true ;
        }
        else
        {
            return false ;
        }

    // fin resultat
    }
    // ------------------------------------



/* ------------------- ADD DISTANCE ----------------------- 
* Ajoute la distance entre le rsultat et l'objectif
* @value : none
* @return : false si il y a déjà, true sinon
*/  
    function dist( )
    {
        $this->p_T["datas"]["distance"] = $this->p_T["objectif"]["value"] - $this->p_T["resultat"]["value"] ;

        if ( $this->p_T["datas"]["distance"] == 0 )
        {
            return true ;
        }
        else
        {
            return false ;
        }

    // fin distance
    }
    // ------------------------------------


/* ------------------- ADD PRECISION ----------------------- 
* Ajoute le ratio entre le rsultat et l'objectif
* @value : none
* @return : false si il y a déjà, true sinon
*/  
    function ratio( )
    {
        $this->p_T["datas"]["precision"] = $this->p_T["objectif"]["value"] / $this->p_T["resultat"]["value"] ;

        if ( $this->p_T["datas"]["precision"] == 0 )
        {
            return true ;
        }
        else
        {
            return false ;
        }

    // fin precision
    }
    // ------------------------------------

/* ------------------- ADD DELAIS ----------------------- 
* Ajoute le temps de calcul
* @value : none
* @return : false si il y a déjà, true sinon
*/  
    function delais( $Rparam )
    {
        // On ajoute bêtement les délais des différent tronçons de calculs /!\ les délais ne s'aditione pas forcément
        $this->p_T["datas"]["delais"] += $Rparam ;

        if ( $this->p_T["datas"]["delais"] == 0 )
        {
            return true ;
        }
        else
        {
            return false ;
        }

    // fin delais
    }
    // ------------------------------------


/* ------------------- ADD COMPTEUR ----------------------- 
* Ajoute le nombre de calcul (opérateurs dans la sequence)
* @value : none
* @return : false si il y a déjà, true sinon
*/  
    function compt( $Nparam )
    {
        // on somme les compteurs d'opération entre eux
        $this->p_T["datas"]["compteur"] += $Nparam ;

        if ( $this->p_T["datas"]["compteur"] == 0 )
        {
            $this->p_T["datas"]["compteur"] = $Nparam ;
            return true ;
        }
        else
        {
            return false ;
        }

    // fin compteur
    }
    // ------------------------------------

/* ----------------------------------FIN------------------------------------- */
/* Fin Class OTresultat */
}








