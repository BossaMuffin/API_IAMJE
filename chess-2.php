<?php
/* arborescence dans le site */
    $g_page_arbo = "" ;
    $g_page = "chess.php" ;

/* VARIABLES META GLOBALES : var meta et directory /!\ FOLD_INC nest pas encore défini */
// regroupe l'ensemble des variables globales
require($g_page_arbo . 'INC/meta_global_var.req.php') ;
//regroupe les fonctions liées au fonctionnement global de l'IA
require( $g_page_arbo . FOLD_CLASS . 'OBaseFunctions.req.php' ) ;
$BFUNC = new OBaseFunctions() ;
//permet d'aiguiller l'IA entre les différents serveurs
require($g_page_arbo . FOLD_INC . 'route_serv.req.php') ;
/* ------------------------------------------------------------------------------------------- */
/* HEADER */
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>IAM-JE CHESS</title>
        <base href="<?php echo $g_url ; ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="language" content="fr-FR" />
        <!-- Core Meta Data -->
        <meta name="author" content="Jean-Eudes Méhus" >
        <meta name="publisher" content="Agence Comozone" >
        <meta name="email" content="coz.web@comozone.com">
        <meta name="description" content="Prmière Intelligence Artificielle Par Enseigenment Renforcé." />
        <meta name="keywords" content="Intelligence Artificielle, IA, AI, artificial intelligence" />
        <!-- Humans -->
        <link rel="author" href="https://www.comozone.com/page-ourteam.txt"/>
        <!-- Page Canonique -->
        <link rel="canonical" href="<?php echo $g_url . $g_page ; ?>" />

        <!-- JS SCRIPTS FILES 
        <script type="text/ecmascript" src="<?php echo $g_page_arbo . FOLD_JS . 'chess.js' ; ?>"></script>-->  
        
        <!-- CSS FILES  --> 
        <link type="text/css" rel="stylesheet" href="<?php echo $g_page_arbo . FOLD_CSS . 'chess-2.css' ; ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo $g_page_arbo . FOLD_CSS . 'SKINS/gnome-chess.css' ; ?>" /> 
       


    </head>
    <body>
        <h1>IAMJE-ChessTest</h1>

        <div id="chessDesk">
            <div id="chessboardsBox" style="width: 100%; height: 200px;">
                <div id="chess2DBox" style="width: 100%; height: 200px;">
                    <table id="flatChessboard" style="width: 98%; height: 188px; margin-bottom: 6px; margin-top: 6px;">
                        <tbody>
                            <tr>
                                <td class="boardAngle"></td>
                                <th class="horizCoords">a</th>
                                <th class="horizCoords">b</th>
                                <th class="horizCoords">c</th>
                                <th class="horizCoords">d</th>
                                <th class="horizCoords">e</th>
                                <th class="horizCoords">f</th>
                                <th class="horizCoords">g</th>
                                <th class="horizCoords">h</th>
                                <td class="boardAngle"></td>
                            </tr>
                            <tr>
                                <th class="vertCoords">1</th>
                                <td class="whiteSquares" id="flatSq91"><span>♚</span></td>
                                <td class="blackSquares" id="flatSq92"></td>
                                <td class="whiteSquares" id="flatSq93"></td>
                                <td class="blackSquares" id="flatSq94"></td>
                                <td class="whiteSquares" id="flatSq95"></td>
                                <td class="blackSquares" id="flatSq96"></td>
                                <td class="whiteSquares" id="flatSq97"></td>
                                <td class="blackSquares" id="flatSq98"></td>
                                <th class="vertCoords">1</th>
                            </tr>
                            <tr>
                                <td class="boardAngle"></td>
                                <th class="horizCoords">a</th>
                                <th class="horizCoords">b</th>
                                <th class="horizCoords">c</th>
                                <th class="horizCoords">d</th>
                                <th class="horizCoords">e</th>
                                <th class="horizCoords">f</th>
                                <th class="horizCoords">g</th>
                                <th class="horizCoords">h</th>
                                <td class="boardAngle"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </body>
</html>