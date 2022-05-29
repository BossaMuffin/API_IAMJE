<?php
/* ----------------------------CLASS RESSOURCES---------------------------------------------- */
// La classe RESSOURCES garde en mémoire les ressources acauise au cours des différentes séries d'apprentissage ALPA
//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste ...

class ORessources
{

// Propriétés
    public $p_Tressources = array();
    public $p_Tarchives = array();

// Var de construction
    //public $g_BFUNC ;
/* ---------------- CONSTRUCTEUR ----------------------------- 
* @value : none
* @return : none
*/
    function  __construct( )
    {
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

/* ----------------------- CHECK ARCHIVE A ----------------------------------- 
* VERIFIE UN CALCUL
* Cherche le calcul demandé parmis les archives existantes 
* Si le calcul est en ressource, c'est que l'IA l'a déjà effectué
* L'IA connait différente séquence de calcul permettant d'atteindre l'objectif avec ses ressources
* @param :
* @value : none
* @return : booléen
*/
    function check_archive_A( $Tresultat )
    {   
        $l_archive_exist = false ;

        foreach ( $this->p_Tarchives as $l_i_id => $l_Tarchive ) 
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

            if ( $l_Tarchive["objectif"]["value"] == $Tresultat["objectif"]["value"] 
                and $l_Tarchive["matieres"]["value"] == $Tresultat["matieres"]["value"]
                and $l_Tarchive["outils"]["value"] == $Tresultat["outils"]["value"]
                and $l_Tarchive["sequence"]["value"] == $Tresultat["sequence"]["value"]
                and $l_Tarchive["resultat"]["value"] == $Tresultat["resultat"]["value"] ) 
            {
                $l_archive_exist = true ;
            }
        }
        
        return $l_archive_exist ;

    // fin check ressources
    }
    // ------------------------------------


/* ---------------------------- MISE EN MEMOIRE DES RESULTATS ALPA----------------------------------- 
* @param :
* @value : $this->p_Tarchives
* @return : none
*/
    function push_archive_A( $Tresultat )
    {
        $l_archive_exist = $this->check_archive_A($Tresultat) ;

        // controle de l'existence similaire, sinon on enregistre
        if ( !$l_archive_exist )
        {
            array_push( $this->p_Tarchives, $Tresultat );
        }

    // fin push archive
    }
    // ------------------------------------

/* ----------------------- CHECK RESSOURCE ----------------------------------- 
* VERIFIE UNE CONNAISSANCE
* Cherche l'objectif demandé parmis les ressources existantes 
* Si l'objectif est en ressource, c'est que l'IA l'a déjà atteint
* L'IA connait différente séquence de calcul permettant d'atteindre l'objectif avec ses ressources
* @param :
* @value : none
* @return : booléen
*/
    function check_ressource( $objectif )
    {

        $l_ressource_exist = false ;
        foreach ( $this->p_Tressources as $l_i => $l_ressource ) 
        {
            if ( $l_ressource == $objectif ) 
            {
                $l_ressource_exist = true ;
            }
        }

        return $l_ressource_exist ;

    // fin check ressources
    }
    // ------------------------------------


/* ------------------------ MISE EN MEMOIRE DES RESSOURCES ---------------------------------- 
* PRIMORDIALE : coeur de l'apprentissage
* L'IA à atteint un nouveau résultat
* Comme elle sait comment l'atteindre, elle le connait
* Elle peut le compter parmi ses ressources comme une nouvelle ressource à exploiter
* @param :
* @value : $this->p_Tressources
* @return : none
*/
    function push_ressource( $resultat )
    {

        $l_ressource_exist = $this->check_ressource($resultat) ;

        // controle de l'existence similaire, sinon on enregistre
        if ( !$l_ressource_exist )
        {
            array_push( $this->p_Tressources, $resultat );
        }

    // fin push ressources
    }
    // ------------------------------------


/* ------------------------ RECHERCHE DE POSSIBLES ---------------------------------- 
* PRIMORDIALE : coeur de l'intelligence = la restitution
* En comparant l'objectif demandée aux ressources existantes
* On va vérifié si la différence entre l'objectif demandé et les connaissances fait partie des connaissance
* @param :
* @value : 
* @return : 
*/
    function recherche_des_possibles( $objectifs )
    {
        // nous allonsretourner un tableau contenant :
        // 1) le resultat de l'aiguillage (boolleen)
        // Si vrai 
        // 2) le Tableau des possibles $l_Tpossibles 
        // Si faux on rajoute 
        // 3) le Tableau des différences $l_Tdifferences
        // 4) le Tableau des différences minimales $l_Tmin_diff
     
       $l_Treponse = array() ;

        if ( $this->check_ressource( $objectifs ) )
        {
            // la connaissance pour repondre à l'objectif demandé est déjà acquise
            $l_Treponse["acquis"] = true ;

            // on récupère dans les archives toutes les données de calcul ALPA qui ont mené à cet objectif 
            // on les range dans $l_Tpossibles 
            $l_Tpossibles = array() ;
            $l_i = 0 ;
            foreach ( $this->p_Tarchives as $l_key => $l_Tressource ) 
            {
                // Pour mémoire
                if ( $l_Tressource["resultat"]["value"] == $objectifs ) 
                {
                    $l_Tmin_diff[$l_i]["value"] = $objectifs ;
                    $l_Tmin_diff[$l_i]["key"] = $l_key ;
                    array_push($l_Tpossibles, $l_Tressource) ;
                    $l_i++ ;
                }
                // $p_Tarchives[$l_i_id]["matieres"]["value"] = $Tresultats["matieres"]["value"] 
                // $p_Tarchives[$l_i_id]["outils"]["value"] = $Tresultats["outils"]["value"]
                // $p_Tarchives[$l_i_id]["sequence"]["value"] = $Tresultats["sequence"]["value"]
                // $p_Tarchives[$l_i_id]["resultat"]["value"] = $Tresultats["resultat"]["value"]
                // $p_Tarchives[$l_i_id]["datas"]["distance"] = $Tresultats["datas"]["distance"]
                // $p_Tarchives[$l_i_id]["datas"]["precision"] = $Tresultats["datas"]["precision"]
                // $p_Tarchives[$l_i_id]["datas"]["delais"] = $Tresultats["datas"]["delais"]
                // $p_Tarchives[$l_i_id]["datas"]["compteur"] = $Tresultats["datas"]["compteur"]
            }

            $l_Treponse["differences"] = array() ;
            $l_Treponse["relais"] = $l_Tmin_diff ;
            $l_Treponse["possibles"] = $l_Tpossibles ;
        }
        else
        {
            // la connaissance pour repondre à l'objectif demandé n'est pas encore acquise
            $l_Treponse["acquis"] = false ;

            // on récupère dans les archives toutes les données de calcul ALPA qui ont mené à cet objectif 
            // on les range dans $l_Tpossibles 
            $l_Tpossibles = array() ;
            $l_Tdifferences = array() ;

            $l_i = 0 ;
            $l_Tmin_diff[$l_i]["value"] = abs( $this->p_Tarchives[0]["objectif"]["value"] - $objectifs ) ;
            $l_Tmin_diff[$l_i]["key"] = 0 ;
            
            // Pour mémoire
                // on compare tous les objectifs déjà atteints avec l'objectif demandé et on prend le plus proche
                // à adapter a chaque forme de ressource ...

            foreach ( $this->p_Tarchives as $l_key => $l_Tressource ) 
            {
                // ADAPTER LE TYPE DE COMPARAISON AVEC LE TYPE DE DONNÉES COMPARÉES XXXXXXXXXXX
                // CHOISIR ENTRE LA COMPARAISON AUX OBJECTIFS DEMANDÉS OU AUX RÉSULTATS ATEINTS
                //$l_Tdifferences[$l_key] =  $objectifs - $l_Tressource["objectif"]["value"];
                $l_Tdifferences[$l_key] =  $objectifs - $l_Tressource["resultat"]["value"];
                // ------------------------------------------------------------------------    

                if ( $l_key == 0 )
                {
                // initialisation pour 0 
                    $l_Tmin_diff[$l_i]["value"] = $l_Tdifferences[$l_key] ;
                    $l_Tmin_diff[$l_i]["key"] = $l_key ;
                }
                else if ( $l_key > 0 )
                {
                // on range les différences les plus petites avec la clé de ressource dans un tableau
                    // Si la valeur suivante est plus petite que la valeur la plus petite enregistré (par defaut, key=0)
                    if ( abs( $l_Tmin_diff[$l_i]["value"] ) >= abs( $l_Tdifferences[$l_key] ) )
                    {
                        if ( abs( $l_Tmin_diff[$l_i]["value"] ) > abs( $l_Tdifferences[$l_key] ) )
                        {
                        // si on trouve une valeur plus petite on efface la pile des valeurs les plus petites et on recommence
                            $l_i = 0 ;
                            $l_Tmin_diff = array() ;
                            $l_Tmin_diff[$l_i]["value"] = $l_Tdifferences[$l_key] ;
                            $l_Tmin_diff[$l_i]["key"] = $l_key ;
                        }
                        else if ( abs( $l_Tmin_diff[$l_i]["value"] ) == abs( $l_Tdifferences[$l_key] ) )
                        {
                        // si la valeur suivante est aussi petite que la plus petite trouvée on l'ajoute à la pile des plus petites valeurs
                            $l_i++ ;
                            $l_Tmin_diff[$l_i]["value"] = $l_Tdifferences[$l_key] ;
                            $l_Tmin_diff[$l_i]["key"] = $l_key ;

                        }
                        
                    }
                }


            }
            // Ensuite on récupère dans les archives toutes les données de calcul ALPA correspondant aux plus petites différences
            // on les range dans $l_Tpossibles 
            foreach ( $l_Tmin_diff as $l_i => $l_min_diff ) 
            {
                // Pour mémoire
                    array_push( $l_Tpossibles, $this->p_Tarchives[$l_min_diff["key"]] ) ;

            }
            
            $l_Treponse["differences"] = $l_Tdifferences ;
            $l_Treponse["relais"] = $l_Tmin_diff ;
            $l_Treponse["possibles"] = $l_Tpossibles ;
            
         // fin if/else   
        }

        return $l_Treponse ;

    // fin recherche des possibles
    }
    // ------------------------------------


/* ----------------------------------FIN------------------------------------- */
/* Fin Class ORessources */
}