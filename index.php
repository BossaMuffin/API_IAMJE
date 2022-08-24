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

/* arborescence dans le site */
if ( ! isset( $g_page_arbo ) )
{
     $g_page_arbo = "" ;
}

if ( ! isset( $g_page ) )
{
     $g_page = "index.php" ;
}
/* VARIABLES META GLOBALES : var meta et directory /!\ FOLD_INC nest pas encore défini */
// regroupe l'ensemble des variables globales (PATH et META DATAS)
require_once( $g_page_arbo . 'inc/global_var_meta.req.php') ;
//regroupe les fonctions liées au fonctionnement global de l'IA
require_once( $g_page_arbo . FOLD_CLASS . 'OBaseFunctions.req.php' ) ;
$BFUNC = new OBaseFunctions( $B_DEVMODESHOW, $B_DEVMODERUN, $B_RESULTMODESHOW ) ;
//permet d'aiguiller l'IA entre les différents serveurs
require_once( $g_page_arbo . FOLD_INC . 'route_serv.req.php' ) ;

// permet d'aiguiller l'IA sur les différentes BDD
require_once( $g_page_arbo . FOLD_INC . 'route_bdd.req.php' ) ;

/* ------------------------------------------------------------------------------------------- */
/* HEADER */
echo '
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>IAM-JE</title>
        <base href="' . $BFUNC->g_url . $g_page . '">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="language" content="fr-FR" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
      <!-- Core Meta Data -->
        <meta name="author" content="Jean-Eudes Méhus" >
        <meta name="publisher" content="Agence Comozone" >
        <meta name="email" content="coz.web@comozone.com">
        <meta name="description" content="Première Intelligence Artificielle Par Enseigenment Renforcé." />
        <meta name="keywords" content="Intelligence Artificielle, IA, AI, artificial intelligence" />
        
      <!-- Humans -->
        <link rel="author" href="https://www.comozone.com/page-ourteam.txt"/>
        
      <!-- Page Canonique -->
        <link rel="canonical" href="' . $BFUNC->g_url . $g_page . '" />

      
      <!-- CSS FILES  --> 
        <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
          <!-- <link rel="stylesheet" href="' . $g_page_arbo . FOLD_CSS . FOLD_COMMON . 'fontawesome-5.8.1.min.css" > -->
          <!-- <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"> -->
       
        <link rel="stylesheet" type="text/css" href="' . $g_page_arbo . FOLD_CSS . FOLD_COMMON . 'buttonLoader.css" >

        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
          <!-- <link href="http://pingendo.github.io/pingendo-bootstrap/themes/default/bootstrap.css" rel="stylesheet" type="text/css" > -->
          <!-- <link type="text/css" rel="stylesheet" href="' . $g_page_arbo . FOLD_CSS . FOLD_COMMON . 'bootstrap-4.3.1.min.css" > -->
 
    </head>
    
    <body>' ;

/* Fin header */
?>


<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - -->
      <!-- INTRODUCTION -->
      <div class="section" id="index-zone-intro">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h1>IAMJE</h1>
<?php
            /* INTRODUCTION */
            // Affichage d'un mot d'accueil
            echo "Je veux apprendre.<br/>" ;
            /* Fin Introdution */
?>
            </div>
            <div class="col-md-5">
<?php
            echo "Vous pouvez m'aider en me donnant des exercices. <br/>" ;
            echo "<br/>" ;
            echo "Pour cela,<br/>" ;
            echo "Il vous suffit de me donner les instructions via le formulaire ci-dessous : " ;
?>
            </div>
            <div class="col-md-7">
<?php
            /* VERS LA PAGE API */
            // Paramètres par défaut
            $l_Tparam = [
              "ia"    => "alpa",
              "mode"  => "learn",
              "outs"  => "addition",
              "obj"   => "12,5",
              "mat"   => "1",
              "dist"  => "1",
              "delais"=> "1000",
              "ratio" => "0.8",
              "dev"   => "on"] ;

            
            echo "<br/>" ;
            echo "Ou en mode API en suivant le lien suivant : " ;
            echo '<a target="blank" href="' . $g_page_arbo . FOLD_API . 'index.php?ia=' . $l_Tparam["ia"] . '&mode=' . $l_Tparam["mode"] . '&outs=' . $l_Tparam["outs"] . '&obj=' . $l_Tparam["obj"] . '&mat=' . $l_Tparam["mat"] . '&dist=' . $l_Tparam["dist"] . '&delais=' . $l_Tparam["delais"] . '&ratio=' . $l_Tparam["ratio"] . '&dev=' . $l_Tparam["dev"] . '">ia=alpa&mode=learn&outs=addition&obj=12&mat=1&dist=1&delais=1000&ratio=0.8</a>' ;
            echo "<br/>" ;
            echo "Ou en mode API DEV en suivant le lien suivant : " ;
            echo '<a target="blank" href="' . $g_page_arbo . FOLD_API . 'index.php?ia=dev&mode=' . $l_Tparam["mode"] . '&outs=' . $l_Tparam["outs"] . '&obj=' . $l_Tparam["obj"] . '&mat=' . $l_Tparam["mat"] . '&dist=' . $l_Tparam["dist"] . '&delais=' . $l_Tparam["delais"] . '&ratio=' . $l_Tparam["ratio"] . '&dev=' . $l_Tparam["dev"] . '">ia=dev&mode=learn...</a>' ;



           
            /* Fin Introdution */
?>
            </div>
          </div>
        </div>
      </div>
      <!-- Fin INTRODUCTION -->
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - -->


<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - -->
      <!-- FORMULAIRE DES ORDRES ALPA -->
      <div class="section" >
        <div class="container">
          <div class="row">
            <div class="col-sm-offset-2 col-md-10">

              <h2> Ordres </h2>
              <form id="index-zone-form" class="form-horizontal" role="form" method="get" action="<?php echo $BFUNC->g_url . FOLD_API . 'index.php' ; ?>">
                
                <!-- JETON 99999 -->
                <!-- <input  type="hidden" id="index-input-show" name="show"  value="off"> -->

                <!-- MODE -->
                <input  type="hidden" id="index-input-ia" name="ia"  value="<?php echo $l_Tparam['ia'] ; ?>">

                <!-- MODE -->
                <div class="form-group has-success has-feedback">
                  <div class="col-sm-2">
                    <label for="inputMode" class="control-label">Mode</label>
                  </div>
                  <div class="col-sm-5">
                    <select name="mode" id="index-input-mode" class="form-control">
                         <option value="learn" selected>Learn</option>
                         <option value="work">Work</option>
                    </select>

                  </div>
                </div>

                <!-- OUTIL -->
                <div class="form-group has-success">
                  <div class="col-sm-2">
                    <label for="inputOutils" class="control-label">Outil</label>
                  </div>
                  <div class="col-sm-5">
                     <select name="outs" id="index-input-outils" class="form-control">
                      <optgroup label="Fonctionnel">
                         <option value="addition" selected>Addition</option>
                      </optgroup>
                      <optgroup label="Soon ...">
                         <option value="soustraction">Soustraction</option>
                      </optgroup>
                     </select>
                  </div>
                </div>

                <!-- OBJECTIF -->
                <div class="form-group has-success">
                  <div class="col-sm-2">
                    <label for="inputObjectif" class="control-label">Objectif</label>
                  </div>
                  <div class="col-sm-5">
                    <input  type="texte" name="obj" id="index-input-objectif" class="form-control" 
                            value="<?php echo $l_Tparam['obj'] ; ?>"
                            placeholder="Pour l'instant, que des chiffres, plusieurs séparés de virgules si mode &quot;learn&quot;">
                    <span class="fa fa-check form-control-feedback"></span>
                  </div>
                </div>

                <!-- MATIERE -->
                <div class="form-group has-success">
                  <div class="col-sm-2">
                    <label for="inputMatiere" class="control-label">Matière utile</label>
                  </div>
                  <div class="col-sm-5">
                    <input  type="number" name="mat" id="index-input-matiere" class="form-control" 
                            value="<?php echo $l_Tparam['mat'] ; ?>"
                            placeholder="Pour l'instant, que des chiffres...">
                  </div>
                </div>

                <h4> Contraintes </h4>

                <!-- DISTANCE -->
                <div class="form-group has-success">
                  <div class="col-sm-2">
                    <label for="inputDistance" class="control-label">Distance min</label>
                  </div>
                  <div class="col-sm-5">
                    <input  type="number" name="dist" id="index-input-distance" class="form-control" 
                            value="<?php echo $l_Tparam['dist'] ; ?>" min="0" max="10" step="0.5"
                            placeholder="Distance en chiffre">
                  </div>
                </div>

                <!-- DELAIS -->
                <div class="form-group has-success">
                  <div class="col-sm-2">
                    <label for="inputDelais" class="control-label">Delais max</label>
                  </div>
                  <div class="col-sm-5">
                    <input  type="number" name="delais" id="index-input-delais" class="form-control" 
                            value="<?php echo $l_Tparam['delais'] ; ?>" min="500" max="10000" step="100"
                            placeholder="Temps de calcul en miliseconde">
                  </div>
                </div>

                <!-- RATIO -->
                <div class="form-group has-success">
                  <div class="col-sm-2">
                    <label for="inputRatio" class="control-label">Ratio min</label>
                  </div>
                  <div class="col-sm-5">
                    <input  type="number" name="ratio" id="index-input-ratio" class="form-control" 
                            value="<?php echo $l_Tparam['ratio'] ; ?>" min="0" max="2" step="0.01"
                            placeholder="chiffre autour de 1">
                  </div>
                </div>
                
                <br/><br/>

                <h4> Options </h4>

                <div class="form-group">

                <!-- ERREURS -->
                  <div class="col-sm-offset-2 col-sm-4">
                        <input type="hidden" name="err" id="index-input-erreurs" value="true" />
                  </div>

                <!-- DEVELOPPEMENT -->
                  <div id="index-input-devmode" class="col-sm-offset-2 col-sm-10">
                    <br/>
                    <legend>Mode développement</legend>
                    <div> 
                        <input type="radio" name="dev" id="index-input-dev" value="yes" >
                          <label for="yes" style="margin: 0 45px 0 5px ; ">OUI</label>
                        <input type="radio" name="dev" id="index-input-normal" value="no" checked>
                          <label for="no" style="margin: 0 45px 0 5px ; ">NON</label>
                    </div>
                  </div>

                <!-- CHOIX AFFICHAGE HTML OU CANVAS -->
                  <div id="index-form-aff" class="col-sm-offset-2 col-sm-10" style="display: none;">
                    <br/>
                    <legend>Type d'affichage</legend>
                    <div> 
                        <input type="radio" name="aff" id="index-input-canvas" value="canvas" checked>
                          <label for="canvas" style="margin: 0 45px 0 5px ; ">CANVAS</label>
                        <!-- <input type="radio" name="aff" id="index-input-html" value="html">
                          <label for="html" style="margin: 0 45px 0 5px ; ">HTML</label> -->
                    </div>
                  </div>
                  <!-- CHOIX AFFICHAGE HTML OU CANVAS -->
                  <div id="index-form-show" class="col-sm-offset-2 col-sm-10" style="display: none;">
                    <br/>
                    <legend>Type d'affichage</legend>
                    <div> 
                        <input type="radio" name="show" id="index-input-show-table" value="on">
                          <label for="on" style="margin: 0 45px 0 5px ; ">TABLE</label>
                        <input type="radio" name="show" id="index-input-show-json" value="off" checked>
                          <label for="off" style="margin: 0 45px 0 5px ; ">JSON</label>
                    </div>
                  </div>



                </div>

                <!-- SUBMIT -->
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success has-spinner">Envoyer</button>
                  </div>
                </div>

              </form>
              
              <!-- AFFICHAGE ERREURS -->
              <div id="zone-notif-erreur" class="text-danger">
              </div>

            </div>
          </div>
        </div>
      </div>
      <!-- Fin FORMULAIRE -->
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - -->



<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- REPRESENTATION GRAPHIQUE 1D DU CALCUL -->
    <div class="section" id="index-zone-affichage" style="display: none;">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2> REPRÉSENTATION GRAPHIQUE </h2>
             <!-- AFFICHAGE ERREURS-->
            <div id="zone-err-php" style="display: none; margin-top:30px">
            </div>

            <!-- AFFICHAGE DE LA DATA -->
            <div id="zone-data-brute" style="display: none; margin-top:30px">
            </div>
            
            
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <!-- LOADER -->
              <div id="index-ajaxBox"></div>
            <!-- ANIMATION DU CALCUL DANS UN DESSIN CANVAS width="1000" height="300" modifié après dans function.js pour coller à la taille de la fenetre -->
              <div class="row" id="index-zone-chess-canvas">
                <div class="col-md-12">
                  
                  <!-- DATAS RECUES SEQUENCE PROPRE-->
                  <div id="zone-sequence-propre-1" style="display: none; margin-top:30px">
                  </div>
                  
                </div>
                <div class="col-md-12">
                  
                  <!-- PLATEAU -->
                  <canvas id="index-canvas-bg-1" style="width:1000px; height:300px; position:absolute; top:0px; left:0px; margin-top: 45px;">
                  </canvas>
                  <!-- ZONE D'ANIMATION -->
                  <canvas id="index-canvas-pion-1" style="width:1000px; height:300px; position:absolute; top:0px; left:0px; margin-top: 45px;">
                  </canvas>

                </div>
              </div>
          </div>
        </div>



      </div>
    </div>
<!-- Fin REPRESENTATION GRAPHIQUE 1D DU CALCUL -->
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - -->

<div id="index-marge-bottom" style="display: none; height:1000px"></div>
<!-- FIN -->

<?php
/* -------------------------------   SCRIPT  ----------------------------------------------------  */
echo '
      <!-- JS FILES  --> 
        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
          <!-- <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script> -->
          <!-- <script type="text/javascript" src="' . $g_page_arbo . FOLD_JS . FOLD_COMMON . 'jquery-4.3.0.min.js"></script> -->
          <!-- <script type="text/javascript" src="' . $g_page_arbo . FOLD_JS . FOLD_COMMON . 'jquery-2.0.2.min.js"></script> -->

        <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
          <!-- <script type="text/javascript" src="' . $g_page_arbo . FOLD_JS . FOLD_COMMON . 'bootstrap-4.3.1.bundle.min.js"></script> -->
          <!-- <script type="text/javascript" src="' . $g_page_arbo . FOLD_JS . FOLD_COMMON . 'bootstrap-4.3.1.min.js"></script> -->

        <script src="' . $g_page_arbo . FOLD_JS . FOLD_COMMON . 'jquery.buttonLoader.min.js"></script>

        <script type="text/javascript" src="' . $g_page_arbo . FOLD_JS . 'functions.js"></script>
' ;
/* Fin script */
?>



  <body>
<html>
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - -->