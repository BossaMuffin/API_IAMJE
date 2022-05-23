<?php
/* ----------------------------CLASS RESSOURCES---------------------------------------------- */
// La classe RESSOURCES garde en mémoire les ressources acauise au cours des différentes séries d'apprentissage ALPA
//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste ...

class ORessources
{


    public $p_Tressources = array();
    public $p_Tarchives = array();


    // var de construction

    function __CONSTRUCT( )
    {         
        
    } // fin construct 

/* ---------------------------------------------------------------------------- */
// MISE EN MEMOIRE DES RESULTATS
    function push_archive_A( $Tresultat )
    {

        $l_archive_exist = false ;
        foreach($this->p_Tarchives as $l_i_id => $l_Tarchive ) 
        {
        // Pour mémoire
        // $p_Tarchives[$l_i_id]["objectif"]["value"] = $Tresultats["objectif"]["value"]
        // $p_Tarchives[$l_i_id]["matieres"]["value"] = $Tresultats["matieres"]["value"] 
        // $p_Tarchives[$l_i_id]["outils"]["value"] = $Tresultats["outils"]["value"]
        // $p_Tarchives[$l_i_id]["sequence"]["value"] = $Tresultats["sequence"]["value"]
        // $p_Tarchives[$l_i_id]["resultat"]["value"] = $Tresultats["resultat"]["value"]
        // $p_Tarchives[$l_i_id]["datas"]["distance"] = $Tresultats["datas"]["distance"]
        // $p_Tarchives[$l_i_id]["datas"]["precision"] = $Tresultats["datas"]["precision"]
        // $p_Tarchives[$l_i_id]["datas"]["delais"] = $Tresultats["datas"]["delais"]
        // $p_Tarchives[$l_i_id]["datas"]["compteur"] = $Tresultats["datas"]["compteur"]

            if( $l_Tarchive["objectif"]["value"] == $Tresultat["objectif"]["value"] 
                and $l_Tarchive["matieres"]["value"] == $Tresultat["matieres"]["value"]
                and $l_Tarchive["outils"]["value"] == $Tresultat["outils"]["value"]
                and $l_Tarchive["sequence"]["value"] == $Tresultat["sequence"]["value"]
                and $l_Tarchive["resultat"]["value"] == $Tresultat["resultat"]["value"] ) 
            {
                $l_archive_exist = true ;
            }
        }
        // controle de l'existence similaire, sinon on enregistre
        if( !$l_archive_exist )
        {
            array_push( $this->p_Tarchives, $Tresultat );
        }
    
    }
    // ------------------------------------

/* ---------------------------------------------------------------------------- */
// MISE EN MEMOIRE DES RESSOURCES 
// PRIMORDIALE : coeur de l'apprentissage
// L'IA à atteint un nouveau résultat
// Comme elle sait comment l'atteindre, elle le connait
// Elle peut le compter parmi ses ressources comme une nouvelle ressource à exploiter
    function push_ressource( $Tresultat )
    {

        $l_ressource_exist = false ;
        foreach($this->p_Tressources as $l_i => $l_ressource ) 
        {
            if( $l_ressource == $Tresultat ) 
            {
                $l_ressource_exist = true ;
            }
        }
        // controle de l'existence similaire, sinon on enregistre
        if( !$l_ressource_exist )
        {
            array_push( $this->p_Tressources, $Tresultat );
        }
    
    }
    // ------------------------------------


/* ----------------------------------FIN------------------------------------- */
/* Fin Class ORessources */
}