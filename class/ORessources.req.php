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
$ORessourcesIncludeJeton = true ;

/* ----------------------------CLASS RESSOURCES---------------------------------------------- */
// La classe RESSOURCES garde en mémoire les ressources acauise au cours des différentes séries d'apprentissage ALPA
//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste ...

class ORessources
{

// Propriétés
    public $p_Tressources = array() ;
    public $p_Tarchives = array() ;
    public $p_Tpossibles = array() ; // XXXX
    public $p_Tarchives_BDD = array() ; // XXX
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
                and $l_Tarchive["matieres"] == $Tresultat["matieres"]
                and $l_Tarchive["outils"]["value"] == $Tresultat["outils"]["value"]
                and $l_Tarchive["sequence"]["value"] == $Tresultat["sequence"]["value"]
                and $l_Tarchive["resultat"] == $Tresultat["resultat"] ) 
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
    function push_archive_A( $Tresultat  )
    {
        global $BDD ;
        
        $l_archive_exist = $this->check_archive_A( $Tresultat ) ;
        $l_archive_exist_BDD = $BDD->check_archive_A( $Tresultat ) ;

        // controle de l'existence similaire, sinon on enregistre
        if ( ! $l_archive_exist and ! $l_archive_exist_BDD[0] )
        {
            array_push( $this->p_Tarchives, $Tresultat );
            $BDD->push_archive_A($Tresultat, $l_archive_exist_BDD["val"]["tab"] ) ;
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
        global $BDD ;

        // on ne pourrait que checker dans la BDD !! ??  XXXXXXXXXXXXXXXXXXXXXXXXx
        $l_ressource_exist = $this->check_ressource( $resultat ) ;
        $l_ressource_exist_BDD = $BDD->check_ressource( $resultat ) ;

        // controle de l'existence similaire, sinon on enregistre
        if ( ! $l_ressource_exist and  ! $l_ressource_exist_BDD[0]  )
        {
            array_push( $this->p_Tressources, $resultat ) ;
            $BDD->push_ressource( $resultat, $l_ressource_exist_BDD["val"]["tab"] ) ;
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
        global $BDD ;

        //ce jeton permet de savoir si l'objectif est déjà connu en tant que ressource
        $l_jeton = false ;

        //ce jeton permet de savoir si les ressources sont en live (FALSE) ou en BDD (TRUE)
        $l_jeton_BDD = false ;

        // nous allons retourner un tableau contenant :
        // 1) le resultat de l'aiguillage (boolleen)
        // Si vrai 
        // 2) le Tableau des possibles $l_Tpossibles 
        // Si faux on rajoute 
        // 3) le Tableau des différences $l_Tdifferences
        // 4) le Tableau des différences minimales $l_Tmin_diff
        $l_Treponse = array() ;

        
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxx
// 999999999999999999999 a verifier pour mode WORK (check ressource in BDD) 
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxx
       // on check d'abord le calcul courant, si pas la ressource, on solicite les archives BDD
        if ( ! $this->check_ressource( $objectifs ) )
        {

            // On sollicite la BDD
            $l_TcheckBdd = $BDD->check_ressource( $objectifs ) ;
            if ( $l_TcheckBdd[0] )
            {
                // On a trouvé la ressource
                $l_jeton = true ;
                $l_jeton_BDD = true ;
            }

        }
        else 
        { 
            // sinon, la ressource est dans le calcul courant
             $l_jeton = true ;
        }
       

        if ( $l_jeton )
        {
            // la connaissance pour repondre à l'objectif demandé est déjà acquise
            $l_Treponse["acquis"] = true ;

            // on récupère dans les archives toutes les données de calcul ALPA qui ont mené à cet objectif 
            // on les range dans $l_Tpossibles 
            $l_Tpossibles = array() ;
            $l_i = 0 ;


            if ( $l_jeton_BDD )
            {
    // l'archive est en BDD-----------------------------------------
                // on recupere toutes les archives (avec le CLIENT ID KEY) qui ont l'objectif pour resultat dans la BDD
                $l_Tarchives_BDD = $BDD->get_archives_A_good( $objectifs ) ;

                $l_Tarchives = array() ;

                foreach ( $l_Tarchives_BDD["val"] as $l_key => $l_Tarchive_BDD ) 
                {
// TROUVER UN MOYEN DE MODELISER/GENERALISER LA CONVERSION
                    $l_Tarchive_BDD = (array)$l_Tarchive_BDD;
                    $l_Tarchives[$l_key]["id"]                   = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["id"]] ; 
                    $l_Tarchives[$l_key]["objectif"]["value"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["obj"]] ; 
                    $l_Tarchives[$l_key]["matieres"]["value"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["mat"]] ; 
                    $l_Tarchives[$l_key]["outils"]["value"]      = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["outs"]] ;
                    $l_Tarchives[$l_key]["sequence"]["value"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["seq"]] ;
                    $l_Tarchives[$l_key]["resultat"]["value"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["result"]] ;
                    $l_Tarchives[$l_key]["datas"]["distance"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["dist"]] ;
                    $l_Tarchives[$l_key]["datas"]["precision"]   = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["ratio"]] ;
                    $l_Tarchives[$l_key]["datas"]["delais"]      = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["delais"]] ;
                    $l_Tarchives[$l_key]["datas"]["compteur"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["compt"]] ;

                    $l_Tmin_diff[$l_i]["value"] = $objectifs ;
                    $l_Tmin_diff[$l_i]["key"] = $l_key ;

                    array_push($l_Tpossibles, $l_Tarchives[$l_key]) ;
                    $l_i++ ;
                    
                }
            
            }
            else
            {
    // l'archive est en live -----------------------------------------------

                foreach ( $this->p_Tarchives as $l_key => $l_Tarchive ) 
                {
                    // Pour mémoire
                    if ( $l_Tarchive["resultat"]["value"] == $objectifs ) 
                    {
                        $l_Tmin_diff[$l_i]["value"] = $objectifs ;
                        $l_Tmin_diff[$l_i]["key"] = $l_key ;
                        array_push($l_Tpossibles, $l_Tarchive) ;
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

 // l'archive est en BDD-----------------------------------------
            // on recupere toutes les archives (avec le CLIENT ID KEY) dans la BDD
            $l_Tarchives_BDD = $BDD->get_archives_A_all( $objectifs ) ;
            $l_Tressource = array() ;


            $l_i = 0 ;
            $jeton_init = false ;
            
            foreach ( $l_Tarchives_BDD["val"] as $l_key => $l_Tarchive_BDD ) 
            {
// TROUVER UN MOYEN DE MODELISER/GENERALISER LA CONVERSION

                $l_Tarchive_BDD = (array)$l_Tarchive_BDD;
                $l_Tarchives[$l_key]["id"]                   = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["id"]] ; 
                $l_Tarchives[$l_key]["objectif"]["value"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["obj"]] ; 
                $l_Tarchives[$l_key]["matieres"]["value"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["mat"]] ; 
                $l_Tarchives[$l_key]["outils"]["value"]      = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["outs"]] ;
                $l_Tarchives[$l_key]["sequence"]["value"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["seq"]] ;
                $l_Tarchives[$l_key]["resultat"]["value"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["result"]] ;
                $l_Tarchives[$l_key]["datas"]["distance"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["dist"]] ;
                $l_Tarchives[$l_key]["datas"]["precision"]   = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["ratio"]] ;
                $l_Tarchives[$l_key]["datas"]["delais"]      = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["delais"]] ;
                $l_Tarchives[$l_key]["datas"]["compteur"]    = $l_Tarchive_BDD[$BDD->p_Tcol["arch"]["compt"]] ;
                

                // on enregistre toutes la table des archives de la BDD (avec le CLIENT ID KEY) dans $this->p_Tarchives
                // evite de faire cette grosse requete à plusieurs reprise dans le même ALPA WORK 
                // (car testé au début de la fonction recherche des possibles) 
                array_push( $this->p_Tarchives, $l_Tarchives[$l_key] ) ;

    // Pour mémoire
        // on compare tous les objectifs déjà atteints avec l'objectif demandé et on prend le plus proche
        // à adapter a chaque forme de ressource ...
                // ADAPTER LE TYPE DE COMPARAISON AVEC LE TYPE DE DONNÉES COMPARÉES XXXXXXXXXXX
                // CHOISIR ENTRE LA COMPARAISON AUX OBJECTIFS DEMANDÉS OU AUX RÉSULTATS ATEINTS
                //$l_Tdifferences[$l_key] =  $objectifs - $l_Trarchive["objectif"]["value"];
                $l_Tdifferences[$l_key] =  $objectifs - $l_Tarchives[$l_key]["resultat"]["value"] ;
                // ------------------------------------------------------------------------    
                if ( ! $jeton_init )
                {
                // initialisation pour 0 
                    $l_Tmin_diff[$l_i]["value"] = $l_Tdifferences[$l_key] ;
                    $l_Tmin_diff[$l_i]["key"] = $l_key ;
                    $jeton_init = true ;
                }
                else 
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
            // xxxxx
            //    $l_Tmin_diff[$l_i]["value"] = abs( $l_Tarchives[0]["objectif"]["value"] - $objectifs ) ;
            //   $l_Tmin_diff[$l_i]["key"] = 0 ;    
            

            // Ensuite on récupère dans les archives toutes les données de calcul ALPA correspondant aux plus petites différences
            // on les range dans $l_Tpossibles 
            foreach ( $l_Tmin_diff as $l_i => $l_min_diff ) 
            {
                // Pour mémoire
                    array_push( $l_Tpossibles, $l_Tarchives[$l_min_diff["key"]] ) ;

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