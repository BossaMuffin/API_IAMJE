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
$OAlpaIncludeJeton = true ;

/* ----------------------------CLASS ALPA---------------------------------------------- */
// La classe ALPA est l'unité élémentaire du coeur de l'intelligence artificielle IAMJE - ALPA est le MOTEUR !!! 
//                           /!\ Reste à mieux rendre les erreurs (sur test d'une fonction avec la valeur de lerreur de cette fonction)
//                           /!\ Reste à gérer les droits d'acès aux fonction public, private, protected
//                           /!\ Reste ...

class OAlpa
{

// Constantes
    // const   MODE = "L" ;

// Propriétés
    // tableau contenant les resultat
    public $p_Tresultats = array() ;
    // public $p_Caffichage_serie_A_ordres = "" ;
    public $p_Caffichage_serie_A = "" ;


// Var de construction
    private $g_CinstanceName = "nom de l'instance ALPA" ;
    public $g_Tobjectifs ;
    public $g_Tressources ;
    public $g_mode ;

/* ---------------- CONSTRUCTEUR ----------------------------- 
* @value : $g_CinstanceName, $T_objectifs, $T_ressources, $mode
* @Instance utilisée : $LFUNC, $BFUNC, $RESSOURCES
* @Class utilisée : new OTresultat
* @return : idem
*/

    function __construct( $g_CinstanceName, $T_objectifs, $T_ressources, $mode )
    {    
        global $BDD ;   

        $this->g_CinstanceName = $g_CinstanceName ;
        $this->g_Tobjectifs = $T_objectifs ;
        $this->g_Tressources = $T_ressources ;
        $this->g_mode = $mode ;

        // BDD -> on trace les ordres envoyés et on recupere dans $BDD->p_NtraceId l'id de l'insert dans la BDD

        $l_Ttrace = $this->trace_ordres() ;

        // ---------------------------------------------------------------------------------------------- 
        // APPEL DE LA FONCTION ELEMENTAIRE EN MODE D'APPRENTISSAGE "ALPA":"A" 
        // $g_Tressources["outils"] se trouve dans $LFUNC
        if ( $l_Ttrace[0] and $this->serie_A() ) 
        {

            // on UPDATE la trace avec le dernier insert ID $l_Ttrace["val"] si ALPA aboutit 
            $BDD->push_trace_A_running( $BDD->p_NtraceId, $BDD->p_CtabTracesName ) ;
        }

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

/* -------------------------------------- TRACE ORDRES  --------------------------- 
* memorise les ordres donnés à ALPA
* @param : 
* @value : none
* @return : bool de resussite (mail ou url)
*/
  function trace_ordres()
  {
    global $BDD ;
    global $BFUNC ;

    $l_jeton = true ;

    $l_Treponse["err"] = array( "id" => "0", "com" => "" ) ;
    $l_Treponse["val"] = "void" ;
    $l_Treponse[0] = false ;

    // propriété $BDD qui garde en memoire le check + create table colonne
    if ( ! $BDD->p_TnewTtraces )
    {
        // une table T_ordres et les colonnes de trace sont crées si besoin
        $BDD->tab_trace_A_create( $BDD->p_CtabTracesName ) ; 
    }
    // on enregistre les ordres issues de GET     
    $l_TnewTrace = $BDD->push_trace_A( $BDD->p_CtabTracesName ) ;
    
    if ( $l_TnewTrace[0] )
    {
        $l_Treponse[0] = true ;
        // on recupere l'ID de cette INSERT
        $BDD->p_NtraceId = $BDD->lastInsertID() ;

    }
    else
    {
        $l_Treponse["err"]["id"] = "1" ;
    }

    // on charge les erreurs dans la propriété qui permettra de les restituer en mode DEV
    $BFUNC->dev_mode( __METHOD__, $l_Treponse["err"] ) ;

    // on charge la reponse à retourner
    return $l_Treponse ;

  // fin trace ordres
  }
  // ------------------------------------



/* ------------------------------------- EXPLICATIONS ------------------------------------------ 
* FONCTION ELEMENTAIRE "ALPA":"A" */

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



/* ------------------------------------ ALPA ----------------------------------------------- 
* FONCTION ELEMENTAIRE EN MODE D'APPRENTISSAGE "ALPA":"A" 
* @param :
* @value : $RESSOURCES->push_ressource( $l_resultat )
* @return : table $l_Treponse
*/
// reste à intégrer : 1) le mode 2) selection de l'outil 3) l'approche/distance de l'objectif
// 999999999 XXXXXXXXx la sequence d'opération ecrite [matiere]a[outil]b[matiere] ... puis [m]a[o]b[m]... 
// peux pôrter à confusion avec des "a" matiere ou "b" outil complexe pouvant présenter la suite de caracteres [m] et [o] XXXXXXXXXXXXXx  999999999999
    function A( $l_i_objectif, $g_i_id = "defo_id" )
    {

        global $BDD ;
        global $LFUNC ;
        global $BFUNC ;
        global $RESSOURCES ;

        // Initialisation des capteurs de performance
            // timestamp en millisecondes du début du script
        $l_timestamp_ms_debut = microtime( true ) ;
            // Initialisation de l'approche au delais
        $l_timestamp_ms_difference = 0 ;

// --------------- ------------------------------------- LEARN : APPRENTISSAGE ALPA ----------------------------------
        if ( $this->g_mode == "learn" )
        {
            $Coutils = $this->g_Tressources["outils"] ;

            // Identification de cette phase d'apprentissage
            //$test = $BFUNC->genereCharKey();

            // Initialisation du depart/base de calcul
            $l_resultat = $this->g_Tressources["matieres"] ;

            // Initialisation des capteurs de performance spécifique à l'apprentissage
            // compteur de calculs
            $l_compteur = 0 ;
            // Initialisation de l'approche/distance à l'objectif
            $l_distance = $this->g_Tobjectifs["objectif"][$l_i_objectif] ;
            // Initialisation du taux de precision minimum
            $l_precision = 0 ;
                // sequence de calculs (chemin utilisé)
            $l_sequence = '--m++' . $this->g_Tressources["matieres"] ;
            if ( $this->g_Tobjectifs["objectif"][$l_i_objectif] != $this->g_Tressources["matieres"] )
            {
                // Boucle de calcul pour approche/distance de l'obejctif demandé
                while ( $l_distance  > $this->g_Tobjectifs["distance"] and $l_timestamp_ms_difference < $this->g_Tobjectifs["delais"] )
                //while( $l_precision  > $T_objectifs["precision"] and $l_timestamp_ms_difference < $T_objectifs["delais"] )
                {

                    // incrementation du compteur à chaque passage dans le calcul
                    $l_compteur++ ;
                    // incrémentation de la sequence
                    $l_sequence .= '--o++' . $this->g_Tressources["outils"] . '--m++' . $this->g_Tressources["matieres"] ;

                    // lancement du calcul itératif 
                    $l_resultat = $LFUNC->$Coutils($l_resultat, $this->g_Tressources["matieres"]) ;
                    //$l_resultat = $LFUNC->addition($l_resultat, $this->g_Tressources["matieres"]) ;
                    //$l_resultat = addition( $l_resultat, $this->g_Tressources["matieres"] ) ;

                    // Controle de précision de calcul et de respect des contraintes
                        // Estimation de l'approche/distance à l'objectif 
                    $l_distance = $this->g_Tobjectifs["objectif"][$l_i_objectif] - $l_resultat ;
                        // Estimation de la precision à l'objectif 
                    $l_precision = $l_resultat / $this->g_Tobjectifs["objectif"][$l_i_objectif] ;
                        // timestamp en millisecondes de la fin du script
                    $l_timestamp_ms_fin = microtime( true ) ; 
                        // différence en millisecondes entre le début et la fin
                    $l_timestamp_ms_difference = $l_timestamp_ms_fin - $l_timestamp_ms_debut ;


                }
            }
            else 
            {

                // incrémentation de la sequence
                $l_sequence .= '--o++' . $this->g_Tressources["outils"] . '--m++' . $this->g_Tressources["matieres"] ;

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
            $l_Treponse["objectif"]["value"] = $this->g_Tobjectifs["objectif"][$l_i_objectif] ;
            $l_Treponse["resultat"]["value"] = $l_resultat ;
            $l_Treponse["sequence"]["value"] = $l_sequence ;
            $l_Treponse["datas"]["distance"] = $l_distance ;
            $l_Treponse["datas"]["precision"] = $l_precision ;
            $l_Treponse["datas"]["delais"] = $l_timestamp_ms_difference ;
            $l_Treponse["datas"]["compteur"] = $l_compteur ;
            
            // la table qui contiendra la chaine de calcul trouvé pour atteindre l'objectif demandé 
            // on implémente l'objectif au tableau de résultat
            $l_RESULTAT = new OTresultat( $this->g_Tobjectifs["objectif"][$l_i_objectif] ) ;
            // XXXXXXX 99999999999 créer des propriétés Tresultat par Valeur importante réutilisée partout ailleurs
            $l_Treponse = $this->formate_A( $l_RESULTAT, $l_Treponse, $g_i_id, $this->g_Tressources["matieres"], $this->g_Tressources["outils"] ) ;
              
            // PRIMORDIALE : coeur de l'apprentissage
            // L'IA à atteint un nouveau résultat
            // Comme elle sait comment l'atteindre, elle le connait
            // Elle peut le compter parmi ses ressources comme une nouvelle ressource à exploiter

            // enregistrement dans la base de donnée et en live
            // controle de l'existence similaire, sinon on enregistre
            $RESSOURCES->push_ressource( $l_resultat ) ;
            $RESSOURCES->push_archive_A( $l_Treponse->p_T ) ;

            // retour du résultat       
            return $l_Treponse ;
           
        // Fin  LEARN : APPRENTISSAGE ALPA ----------------------------------  
        }
// ----------- OU ------------------------------------- WORK : TRAVAIL ALPA ----------------------------------
        else if ( $this->g_mode == "work" )
        {
            // ------------------------------------- RECHERCHE DE POSSIBLES ----------------------------------------- 
            // ------- utilise un  un objet "resultat ALPA" pour formater facilement des tableau de ce type en créant des instances du formatage unique
            // ----------- fonction bouclée 
            // ------------- créer des fonctions secondaires communes (de nommage, de calcul de précision, de timming, et compteur, ou de mise à jour du tableau de resultat "solution")

               
            // la table qui contiendra la chaine de calcul trouvé pour atteindre l'objectif demandé 
            // on implémente l'objectif au tableau de résultat
            $l_RESULTAT = new OTresultat( $this->g_Tobjectifs["objectif"][$l_i_objectif] ) ;

            // jeton de boucle while 
            $l_while_continue = true ;
            $l_while_compteur = 0 ;
// XXXXXXXXXXXXXXXXXXXXXXXXX 9999999999999999 ATTENTION A LA DIVERGENCE !!!!!!!!!!!!!!!!!!!!!!!!
            while ( $l_while_continue 
                    and  ( ( abs( 1 - abs( $l_RESULTAT->p_T["datas"]["precision"] ) ) > ( 1 - $this->g_Tobjectifs["precision"] ) ) 
                        or $l_RESULTAT->p_T["datas"]["distance"] <= $g_Tobjectifs["distance"] )
                    and $l_timestamp_ms_difference <= $this->g_Tobjectifs["delais"] )
            {
                $l_while_compteur++ ; 
                // On verifie si l'objectif n'a pas déjà été atteint
                // En comparant l'objectif demandée aux ressources existantes
                if ( $l_while_compteur == 1 )
                {
                    $g_Tpossibles = $RESSOURCES->recherche_des_possibles( $l_RESULTAT->p_T["objectif"]["value"] ) ;
                }
                else
                {
                    $g_Tpossibles = $RESSOURCES->recherche_des_possibles( $g_Tpossibles["relais"][0]["value"] ) ;
                    // on incremente le compteur d'utilisation "memo" memoire 
                }

            // -------------------- MEMORISATION DU CHEMIN DEJA PARCOURU DANS OTRESULTAT  XXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxxx
                //$this->g_Tressources["matieres"] = $g_Tpossibles["possibles"][0]["matieres"]["value"] ;

                $l_Treponse = $this->formate_A( $l_RESULTAT, $g_Tpossibles["possibles"][0], $g_Tpossibles["possibles"][0]["id"], $g_Tpossibles["possibles"][0]["matieres"]["value"], $g_Tpossibles["possibles"][0]["outils"]["value"] ) ;
              
                if ( $g_Tpossibles["acquis"] )
                {
                    $l_while_continue = false ;
                }
                else
                {
                    $l_while_continue = true ;
                }
                    // timestamp en millisecondes de la fin du script
                $l_timestamp_ms_fin = microtime( true ) ; 
                    // différence en millisecondes entre le début et la fin
                $l_timestamp_ms_difference = $l_timestamp_ms_fin - $l_timestamp_ms_debut ;


// ATTENTIOn PROBLEME DE COMPTEUR -> MEMO n'enregistre pas les sequence intermédiaires !!!!!!!!! XXXXXXXXXXXXXXXXXXXXXxxx  999999999999999999999999ç
                
            /* -------------------------------------- ENREGISTREMENT DE L'APPRENTISSAGE ISSU DU TRAVAIL EFFECTUÉ --------------------------------- */
            // ENREGISTREMENT DES RESULTATS ET DES RESSOURCES DÉCOUVERTES  
            // on rend réutilisable
            // on enregistre le resultat atteint 

            // enregistrement dans la base de donnée et en live
            // controle de l'existence similaire, sinon on enregistre
            $RESSOURCES->push_ressource( $l_Treponse->p_T["resultat"]["value"] ) ;
            $RESSOURCES->push_archive_A( $l_Treponse->p_T ) ;           


            }

           // Fin de la boucle while
            $RESSOURCES->push_ressource( $l_Treponse->p_T["resultat"]["value"] ) ;
            $RESSOURCES->push_archive_A( $l_Treponse->p_T ) ;


            // retour du résultat       
            return $l_Treponse ;
        // Fin  WORK : TRAVAIL ALPA ----------------------------------   
        }
        
        

    // Fin fonction élémentaire ALPA
    } 
    // ------------------------------------



/* ------------------------------- ENREGISTREMENT RESULAT EN BDD ------------------------------------------------------ 
* ENREGISTREMENT DE L'APPRENTISSAGE -> MISE EN MEMOIRE DU CALCUL ELEMENTAIRE 
* @param :
* @value : 
* @return : 
*/
// ????? BDD ????? -> Oressources



/* -------------------------------------FORMATAGE DE LA SEIRE D'APPRENTISSAGE ----------------------------------------------- 
* On formate le resultat du calcul ALPA dans un tableau global 
* hierarchisation de l'information OBJECTIF > MATIERE UTILISEE > OUTILS > DONNEES DE CALCUL
* @param :
* @value : none
* @return : table $l_Tmemoire
*/
    function formate_A( $l_RESULTAT, $T_result, $Cid, $Nmatieres, $Coutils )
    {

        // -------------------- MEMORISATION DU CHEMIN DEJA PARCOURU DANS OTRESULTAT  
        // on peut ajouter les matières utilisées et les données (contraintes, objectifs, outils etc) du calcul originel
        // on construit l'id
        $l_RESULTAT->id( $Cid ) ;
        // on injecte la matière si elle n'a pas été déjà notée dans la description du calcul
        $l_RESULTAT->mat( $Nmatieres ) ;
        // on injecte l'outils s'il n'a pas été déjà noté dans la description du calcul
        $l_RESULTAT->outs( $Coutils ) ;
        // on construit la sequence
        $l_RESULTAT->seq( $T_result["sequence"]["value"] ) ;
        // on injecte le resultat
        $l_RESULTAT->res( $T_result["resultat"]["value"] ) ;
        // on calcul la distance entre l'objectif et le résultat atteint
        $l_RESULTAT->dist( $T_result["datas"]["distance"] ) ;
        // on calcul la precision(ratio) entre l'objectif et le ésultat atteint
        $l_RESULTAT->ratio( $T_result["datas"]["precision"] ) ;
        // on injecte le délai de calcul
        $l_RESULTAT->delais( $T_result["datas"]["delais"] ) ;
        // on injecte le compteur d'opération
        $l_RESULTAT->compt( $T_result["datas"]["compteur"] ) ;
   
        return $l_RESULTAT ;

    // fin formate_A
    }
    // ------------------------------------

/* --------------------------------SERIE A-------------------------------------------- 
* Travail en serie de fonctions élémentaire en mode apprentissage "ALPA":"A" 
* @param :
* @value : $RESSOURCES->push_archive_A( $l_Treponse[$g_i_id], $g_i_id ) et $this->p_Tresultats
* @return : none
*/
    function serie_A( )
    {
        global $BFUNC ;
        global $BDD ;
        // Afffichage des ordres d'apprentissage 
        //$this->p_Caffichage_serie_A_ordres = $this->affichage_serie_A_ordres( $objectif_max ) ;

        // On boucle la fonction élémentaire A sur la serie de l'objectif initial à l'objectif max 
        $l_i = 0 ;
        $l_Tobjectifs = $this->g_Tobjectifs ;
        $l_Tressources = $this->g_Tressources ;

        foreach ( $l_Tobjectifs["objectif"] as $l_i_objectif => $l_objectif )
        {

            $l_i++ ;
            // 9999999999999 XXXXXXXXXXXXXXXXxx créer un objet de nommage des calculs
            $l_TobjType = $BFUNC->get_type( $l_objectif ) ;

            $l_CtabName = $BDD->p_Tprefixes["arch"] . $l_TobjType["subval"] ;

            $g_i_id = "v1o" . $BDD->p_NtraceId . "-" . $l_Tressources["outils"] . "-" . $BDD->tab_max_id( $l_CtabName, "id" )["val"] ;

            // -------------------- TRAVAIL  
            // TRAVAIL D'APPRENTISAGE ELEMENTAIRE 
            // $l_i_objectif = intval( $l_i_objectif ) ;
            $l_Tresult = $this->A( $l_i_objectif, $g_i_id ) ;
            // Résultat formaté, ressourcé et archivé dans Alpa
            //v0 : $l_Treponse[$g_i_id] = $l_Tresult->p_T ;
            //v1 : $l_Treponse[$l_i] = $l_Tresult->p_T ;
            $l_Treponse = $l_Tresult->p_T ;
            // --------------------------- AFFICHAGE PHASE A 
            // Implémente l'attribut d'affichage de la phase d'apprentissage */
            $this->p_Caffichage_serie_A .= $this->affichage_A( $l_Tresult->p_T ) ;

            // Fin boucle foreach pour créer la serie d'apprentissage
            array_push( $this->p_Tresultats, $l_Treponse ) ;
        }

        // $this->p_Tresultats = $l_Treponse ;
        //array_push( $this->p_Tresultats, $l_Treponse ) ;
        //print_r($this->p_Tresultats) ;

        return true ;

    // Fin de la fonction d'apprentissage en série ALPA
    }
    // ------------------------------------



/* --------------------------------AFFICHAGE POUR CONTROLE D'APPRENTISSAGE ------------------------------------------------- 
* AFFICHAGE  D'APPRENTISSAGE "ALPA":"A" 
* Utilsée surtout pour le DEV 
* @param :
* @value : none
* @return : html char
*/
    function affichage_serie_A_ordres( )
    {

            // ---------------------------------------------------------------------------------------------- 
            // AFFICHAGE DES ORDRES 
            if ( $this->g_mode == "work" )
            {
                $l_mode = "RESTITUTION" ;
            }
            else if ( $this->g_mode == "learn" )
            {
                $l_mode = "ENSEIGNEMENT" ;
            }
            
            // on recupere la liste des objectifs qu'on formate
            foreach ( $this->g_Tobjectifs["objectif"] as $l_i_obj => $objectif )
            {
                    if ( $l_i_obj == 0 )
                    {
                        $l_Cobjectifs = $objectif ;
                    }
                    else
                    {
                        $l_Cobjectifs .= ", ".$objectif ;
                    }
                    
            } 

            $l_part[1] = "
            <h2>" . $l_mode . " " . $this->g_CinstanceName . "</h2>
            <h3>ORDRES</h3>

            <dd>

                <u>Serie d'objectifs traités</u> (Tobjectifs) :<br/>
                <b>" . $l_Cobjectifs . "</b><br/>

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

    // fin affichage ordres de serie A
    }
    // ------------------------------------


/* --------------------------------AFFICHAGE POUR CONTROLE D'APPRENTISSAGE ------------------------------------------------- */
/* AFFICHAGE  D'APPRENTISSAGE "ALPA":"A" 
* Utilsée surtout pour le DEV 
* @param :
* @value : none
* @return : html char
*/
    function affichage_A( $T_result )
    {

            // AFFICHAGE DE L'ID DE LA PHASE ELEMENTAIRE D'APPRENTISSAGE  
            $l_part[1] = "
            <-------------------------------------->
            <br/>PHASE n°<b>" . $T_result["id"] . "</b>
            <br/>" ;

            // ---------------------------------------------------------------------------------------------- 
            // AFFICHAGE DES ORDRES D'APRENTISSAGE (ENSEIGNEMENT) 
            $l_part[2] = " <br/>
            <h3>ORDRES D'ENSEIGNEMENT<h3/>
            <br/>

            <dd>

                <!--<u>Outils étudié</u> (outils) :<br/> 
                <b>" . $this->g_Tressources["outils"] . "</b><br/>-->

                <u>Objectif demandé</u> (objectif) :<br/>
                <b>" . $T_result["objectif"]["value"][0] . "</b><br/>

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
            <h3>RÉSULTAT D'APPRENTISSAGE</h3>
            <br/>

            <dd>

                <u>Résultat atteint</u> (resultat) :<br/>
                <b>" . $T_result["resultat"]["value"] . "</b><br/>

                <u>Distance de l'approche</u> (distance) :<br/>
                <b>" . $T_result["datas"]["distance"] . "</b><br/>

                <u>Précision de l'approche</u> (precision) :<br/>
                <b>" . $T_result["datas"]["precision"] . "</b><br/>

                <u>Temps de calcul</u> (delais) :<br/>
                <b>" . $T_result["datas"]["delais"] . " ms</b><br/>

                <u>Nombre de calcul</u> (compteur) :<br/>
                <b>" . $T_result["datas"]["compteur"] . "</b><br/>

                <u>Séquence de calcul</u> (sequence) :<br/>
                <b>" . $T_result["sequence"]["value"] . "</b><br/>

            </dd>" ;

            return $l_part[1].$l_part[2] . $l_part[3] ;

    // fin affichage A
    }
    // ------------------------------------




/* ----------------------------------FIN------------------------------------- */
/* Fin Class OAlpa */
}








