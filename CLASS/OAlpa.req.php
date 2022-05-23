<?php
/* ----------------------------CLASS ALPA---------------------------------------------- */
// La classe ALPA est l'unité élémentaire du coeur de l'intelligence artificielle IAMJE - ALPA est le MOTEUR !!! 
//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste ...

class OAlpa
{

    const   MODE = "L" ;
    public $p_Tresultats = array() ;
    public $p_Caffichage_serie_A_ordres = "" ;
    public $p_Caffichage_serie_A = "" ;

  // var de construction
    public $g_Tobjectifs ;
    public $g_Tressources ;
    public $g_mode ;
    public $g_BFUNC ;
    public $g_LFUNC ;
    public $g_RESSOURCES ;

    function __CONSTRUCT( $T_objectifs, $T_ressources, $mode, $LFUNC, $BFUNC, $RESSOURCES )
    {         
        $this->g_Tobjectifs = $T_objectifs ;
        $this->g_Tressources = $T_ressources ;
        $this->g_mode = $mode ;
        $this->g_LFUNC = $LFUNC ;
        $this->g_BFUNC = $BFUNC ;
        $this->g_RESSOURCES = $RESSOURCES ;
    } // fin construct 

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



/* ---------------------------------------------------------------------------------------------- */
/* FONCTION ELEMENTAIRE EN MODE D'APPRENTISSAGE "ALPA":"A" */
// retourne : ...
// reste à intégrer : 1) le mode 2) selection de l'outil 3) l'approche/distance de l'objectif
    function A( $T_objectifs, $Coutils )
    {
        
        // Identification de cette phase d'apprentissage
        //$test = $this->g_BFUNC->genereCharKey();

    	// Initialisation du depart/base de calcul
    	$l_resultat = $this->g_Tressources["matieres"] ;

        // Initialisation des capteurs de performance
            // timestamp en millisecondes du début du script
        $l_timestamp_ms_debut = microtime( true ) ;
            // compteur de calculs
        $l_compteur = 0 ;
            // Initialisation de l'approche au delais
        $l_timestamp_ms_difference = 0 ;
            // Initialisation de l'approche/distance à l'objectif
        $l_distance = $T_objectifs["objectif"] ;
        // Initialisation du taux de precision minimum
        $l_precision = 0 ;
            // sequence de calculs (chemin utilisé)
        $l_sequence = '[matiere]' . $this->g_Tressources["matieres"] ;

        if ($T_objectifs["objectif"] != $this->g_Tressources["matieres"])
        {
            // Boucle de calcul pour approche/distance de l'obejctif demandé
        	while( $l_distance  > $T_objectifs["distance"] and $l_timestamp_ms_difference < $T_objectifs["delais"] )
            //while( $l_precision  > $T_objectifs["precision"] and $l_timestamp_ms_difference < $T_objectifs["delais"] )
        	{

                // incrementation du compteur à chaque passage dans le calcul
                $l_compteur++ ;
                // incrémentation de la sequence
                $l_sequence .= '[outil]' . $this->g_Tressources["outils"] . '[matiere]' . $this->g_Tressources["matieres"] ;

                // lancement du calcul itératif 
                $l_resultat = $this->g_LFUNC->$Coutils($l_resultat, $this->g_Tressources["matieres"]) ;
        		//$l_resultat = $this->g_LFUNC->addition($l_resultat, $this->g_Tressources["matieres"]) ;
                //$l_resultat = addition( $l_resultat, $this->g_Tressources["matieres"] ) ;

                // Controle de précision de calcul et de respect des contraintes
                    // Estimation de l'approche/distance à l'objectif 
                $l_distance = $T_objectifs["objectif"] - $l_resultat ;
                    // Estimation de la precision à l'objectif 
                $l_precision = $l_resultat / $T_objectifs["objectif"] ;
                    // timestamp en millisecondes de la fin du script
                $l_timestamp_ms_fin = microtime( true ) ; 
                    // différence en millisecondes entre le début et la fin
                $l_timestamp_ms_difference = $l_timestamp_ms_fin - $l_timestamp_ms_debut ;


        	}
        }
        else 
        {

            // incrémentation de la sequence
            $l_sequence .= '[outil]' . $this->g_Tressources["outils"] . '[matiere]' . $this->g_Tressources["matieres"] ;

            // Controle de précision de calcul et de respect des contraintes
                // Estimation de l'approche/distance à l'objectif 
            $l_distance = 0 ;
                // Estimation de la precision à l'objectif 
            $l_precision = 1 ;
                // timestamp en millisecondes de la fin du script
            $l_timestamp_ms_fin = microtime( true ) ; 
                // différence en millisecondes entre le début et la fin
            $l_timestamp_ms_difference = $l_timestamp_ms_fin - $l_timestamp_ms_debut ;
        }
		
        // chargement des données à retourner
        //$l_Treponse["id"] = $l_id["value"] ;
        $l_Treponse["objectif"] = $T_objectifs["objectif"] ;
        $l_Treponse["resultat"] = $l_resultat ;
        $l_Treponse["distance"] = $l_distance ;
        $l_Treponse["precision"] = $l_precision ;
		$l_Treponse["delais"] = $l_timestamp_ms_difference ;
        $l_Treponse["compteur"] = $l_compteur ;
        $l_Treponse["sequence"] = $l_sequence ;

        // PRIMORDIALE : coeur de l'apprentissage
        // L'IA à atteint un nouveau résultat
        // Comme elle sait comment l'atteindre, elle le connait
        // Elle peut le compter parmi ses ressources comme une nouvelle ressource à exploiter
        $this->g_RESSOURCES->push_ressource( $l_resultat ) ;

        // retour du résultat 
    	return  $l_Treponse;
        	

    // Fin fonctionélémentaire ALPA
    } 
// ------------------------------------



/* ---------------------------------------------------------------------------------------------- */
/* ENREGISTREMENT DE L'APPRENTISSAGE -> MISE EN MEMOIRE DU CALCUL ELEMENTAIRE */
// ????? BDD ?????

/* -------------------------------------FORMATAGE DE LA SEIRE D'APPRENTISSAGE ----------------------------------------------- */
// On formate le resultat du calcul ALPA dans un tableau global 
// hierarchisation de l'information OBJECTIF > MATIERE UTILISEE > OUTILS > DONNEES DE CALCUL
    function formate_A( $T_result, $Cid )
    {
        $l_Tmemoire["id"] = $Cid ;
        $l_Tmemoire["objectif"]["value"] = $T_result["objectif"] ;
        $l_Tmemoire["matieres"]["value"] = $this->g_Tressources["matieres"] ;
        $l_Tmemoire["outils"]["value"] = $this->g_Tressources["outils"] ;
        $l_Tmemoire["sequence"]["value"] = $T_result["sequence"] ;
        $l_Tmemoire["resultat"]["value"] = $T_result["resultat"] ;
        $l_Tmemoire["datas"]["distance"] = $T_result["distance"] ;
        $l_Tmemoire["datas"]["precision"] = $T_result["precision"] ;
        $l_Tmemoire["datas"]["delais"] = $T_result["delais"] ;
        $l_Tmemoire["datas"]["compteur"] = $T_result["compteur"] ;

        return $l_Tmemoire ;
    }
// ------------------------------------



/* --------------------------------SERIE A-------------------------------------------- */
// Travail en serie de fonctions élémentaire en mode apprentissage "ALPA":"A" */
    function serie_A( $objectif_max, $Coutils )
    {
        // Afffichage des ordres d'apprentissage 
        $this->p_Caffichage_serie_A_ordres = $this->affichage_serie_A_ordres( $objectif_max ) ;

        // On boucle la fonction élémentaire A sur la serie de l'objectif initial à l'objectif max 
        $l_i = 0 ;
        $l_Tobjectifs= $this->g_Tobjectifs ;
        while( $l_Tobjectifs["objectif"] <= $objectif_max )
        {

            $l_i++ ;
            $g_i_id = "v1-" . $this->g_Tressources["outils"] . "-" . $l_i ;

            // -------------------- TRAVAIL  
            // TRAVAIL D'APPRENTISAGE ELEMENTAIRE 
            $l_Tresult = $this->A( $l_Tobjectifs, $Coutils ) ;

            // Pour mémoire
            // $g_Tresultat["resultat"]
            // $g_Tresultat["distance"]
            // $g_Tresultat["precision"]
            // $g_Tresultat["delais"]
            // $g_Tresultat["compteur"]
            // $g_Tresultat["sequence"]

            $l_Tresultats[$l_i] = $l_Tresult ;
            $l_Tresultats[$l_i]["id"] = $g_i_id ;

            // ------------------------------ FORMATAGE PHASE A
            // Formatage du resultat de calcul elementaire
            // pour memorisation de l'apprentissage
            $l_Treponse[$g_i_id] = $this->formate_A( $l_Tresultats[$l_i], $g_i_id ) ;

            // ------------------------------ ARCHIVAGE PHASE A
            // Archivage du resultat de calcul elementaire
            // pour réutilisation future de l'apprentissage
            $this->g_RESSOURCES->push_archive_A( $l_Treponse[$g_i_id], $g_i_id ) ;

            // --------------------------- AFFICHAGE PHASE A 
            // Implémente l'attribut d'affichage de la phase d'apprentissage */
            $this->p_Caffichage_serie_A .= $this->affichage_A( $l_Tresultats[$l_i] ) ;

            // Incrémente l'objectif d'apprentissage
            $l_Tobjectifs["objectif"] = $l_Tobjectifs["objectif"] + $this->g_Tressources["matieres"];

            // Fin boucle while pour créer la serie d'apprentissage
        }

        $this->p_Tresultats = $l_Treponse ;
        
        //return $l_Treponse ;

    // Fin de la fonction d'apprentissage en série ALPA
    }
// ------------------------------------



/* --------------------------------AFFICHAGE POUR CONTROLE D'APPRENTISSAGE ------------------------------------------------- */
/* AFFICHAGE  D'APPRENTISSAGE "ALPA":"A" */
// Utilse pour le DEV surtout
    function affichage_serie_A_ordres( $g_objectif_max )
    {

            // ---------------------------------------------------------------------------------------------- 
            // AFFICHAGE DES ORDRES D'APRENTISSAGE (ENSEIGNEMENT) 
            $l_part[1] = " <br/>
            ORDRES D'ENSEIGNEMENT<br/>
            <br/>

            <dd>

                <u>Objectif initial</u> (objectif) :<br/>
                <b>" . $this->g_Tobjectifs["objectif"] . "</b><br/>

                <u>Objectif final</u> (g_objectif_max) :<br/>
                <b>" . $g_objectif_max . "</b><br/>

                <u>Matière utilisée</u> (matieres) :<br/>
                <b>" . $this->g_Tressources["matieres"] . "</b><br/>

                <u>Outils étudié</u> (outils) :<br/> 
                <b>" . $this->g_Tressources["outils"] . "</b><br/>

                <u>Distance à l'objectif minimum demandée</u> (distance) :<br/>
                <b>" . $this->g_Tobjectifs["distance"] . "</b><br/>

                <u>Taux de precision minimum demandé</u> (precision) :<br/>
                <b>" . $this->g_Tobjectifs["precision"] . "</b><br/>

                <u>Délais maximum</u> (delais) :<br/>
                <b>" . $this->g_Tobjectifs["delais"] . "ms</b><br/>

            </dd>

            <br/>" ;

            return $l_part[1] ;

    }
// ------------------------------------


/* --------------------------------AFFICHAGE POUR CONTROLE D'APPRENTISSAGE ------------------------------------------------- */
/* AFFICHAGE  D'APPRENTISSAGE "ALPA":"A" */
// Utilse pour le DEV surtout
    function affichage_A( $T_result )
    {

            // AFFICHAGE DE L'ID DE LA PHASE ELEMENTAIRE D'APPRENTISSAGE  
            $l_part[1] = "<br/><br/>
            <-------------------------------------->
            <br/>PHASE n°<b>" . $T_result["id"] . "</b>
            <br/>" ;

            // ---------------------------------------------------------------------------------------------- 
            // AFFICHAGE DES ORDRES D'APRENTISSAGE (ENSEIGNEMENT) 
            $l_part[2] = " <br/>
            ORDRES D'ENSEIGNEMENT<br/>
            <br/>

            <dd>

                <!--<u>Outils étudié</u> (outils) :<br/> 
                <b>" . $this->g_Tressources["outils"] . "</b><br/>-->

                <u>Objectif demandé</u> (objectif) :<br/>
                <b>" . $T_result["objectif"] . "</b><br/>

                <!--<u>Distance à l'objectif minimum demandée</u> (distance) :<br/>
                <b>" . $this->g_Tobjectifs["distance"] . "</b><br/>

                <u>Taux de precision minimum demandé</u> (precision) :<br/>
                <b>" . $this->g_Tobjectifs["precision"] . "</b><br/>

                <u>Délais maximum</u> (delais) :<br/>
                <b>" . $this->g_Tobjectifs["delais"] . "ms</b><br/>

                <u>Matière utilisée</u> (matieres) :<br/>
                <b>" . $this->g_Tressources["matieres"] . "</b><br/>-->

            </dd>

            <br/>" ;

            // ---------------------------------------------------------------------------------------------- 
            // AFFICHAGE DU RÉSULTAT */
            $l_part[3] = "<br/>
            RÉSULTAT D'APPRENTISSAGE<br/>
            <br/>

            <dd>

                <u>Résultat atteint</u> (resultat) :<br/>
                <b>" . $T_result["resultat"] . "</b><br/>

                <u>Distance de l'approche</u> (distance) :<br/>
                <b>" . $T_result["distance"] . "</b><br/>

                <u>Précision de l'approche</u> (precision) :<br/>
                <b>" . $T_result["precision"] . "</b><br/>

                <u>Temps de calcul</u> (delais) :<br/>
                <b>" . $T_result["delais"] . " ms</b><br/>

                <u>Nombre de calcul</u> (compteur) :<br/>
                <b>" . $T_result["compteur"] . "</b><br/>

                <u>Séquence de calcul</u> (sequence) :<br/>
                <b>" . $T_result["sequence"] . "</b><br/>

            </dd>" ;

            return $l_part[1].$l_part[2] . $l_part[3] ;

    }
// ------------------------------------




/* ----------------------------------FIN------------------------------------- */
/* Fin Class OAlpa */
}








