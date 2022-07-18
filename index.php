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
$BFUNC = new OBaseFunctions( $B_DEVMODESHOW, $B_DEVMODERUN ) ;
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

        <!-- JS FILES  --> 
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="' . $g_page_arbo . FOLD_JS . FOLD_COMMON . 'functions.js"></script>

        <!-- CSS FILES  --> 
        <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="http://pingendo.github.io/pingendo-bootstrap/themes/default/bootstrap.css" rel="stylesheet" type="text/css">
        <link type="text/css" rel="stylesheet" href="' . $g_page_arbo . FOLD_CSS . 'chess.css" />
        <link type="text/css" rel="stylesheet" href="' . $g_page_arbo . FOLD_CSS . FOLD_SKINS . 'gnome-chess.css" /> 
    
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
            echo "Vous pouvez m'aider en me donnant des exercices. <br/>" ;
            echo "<br/>" ;
            echo "Pour cela,<br/>" ;
            echo "Il vous suffit de me donner les instructions via le formulaire ci-dessous : " ;
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
                
                <!-- JETON -->
                <input  type="hidden" id="index-input-show" name="show"  value="off">

                <!-- MODE -->
                <input  type="hidden" id="index-input-ia" name="ia"  value="alpa">

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
                            value="12"
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
                            value="1"
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
                            value="1" min="0" max="10" step="0.5"
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
                            value="1000" min="500" max="10000" step="100"
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
                            value="0.8" min="0" max="2" step="0.01"
                            placeholder="chiffre autour de 1">
                  </div>
                </div>


                <div class="form-group">

                <!-- ERREURS -->
                  <div class="col-sm-offset-2 col-sm-4">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="err" id="index-input-erreurs" checked>Système d'erreurs</label>
                    </div>
                  </div>

                <!-- DEVELOPPEMENT -->
                  <div class="col-sm-4">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="dev" id="index-input-developpement">Affichage des erreurs</label>
                    </div>
                  </div>

                </div>

                <!-- SUBMIT -->
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Envoyer</button>
                  </div>
                </div>

              </form>
              
              <!-- AFFICHAGE ERREURS -->
              <div id="zone-notif-erreur">
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
             <!-- AFFICHAGE ERREURS -->
              <div id="zone-memo-sequence" style="display: none;">
              </div>
              <div id="zone-memo-etat" style="display: none;">
              </div>
            <form id="index-zone-exec" class="form-horizontal" role="form" method="post" action="#">
              <!-- SUBMIT EXECUTION -->
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-5">
                  <button type="submit" class="btn btn-default">Afficher</button>
                </div>
                <div id="zone-resultat" class="col-sm-5">
                </div>
              </div>
            </form>

           <div class="section" id="index-zone-chess">
            <?php
            $l_objectif = 10 ;
            // Construction d'une grille horizontale pour la progression d'un piont graphique de 0 à Objectif 
            // Construction d'une grille horizontale pour la progression d'un piont graphique de 0 à Objectif 
            //include($g_page_arbo . FOLD_INC . 'body-representation-graphique-1d.inc.php') ;
            ?>
           </div>



          </div>
        </div>
      </div>
    </div>
<!-- Fin REPRESENTATION GRAPHIQUE 1D DU CALCUL -->
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - -->


<!-- FIN -->
  <body>
<html>
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - -->