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

        <!-- JS SCRIPTS FILES -->  
        <script type="text/ecmascript" src="<?php echo $g_page_arbo . FOLD_JS . 'COMMON/xhr.js' ; ?>"></script>
        <script type="text/ecmascript" src="<?php echo $g_page_arbo . FOLD_JS . 'chess.js' ; ?>"></script>
        
        <!-- CSS FILES  --> 
        <link type="text/css" rel="stylesheet" href="<?php echo $g_page_arbo . FOLD_CSS . 'chess.css' ; ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo $g_page_arbo . FOLD_CSS . 'SKINS/gnome-chess.css' ; ?>" /> 

        <script type="text/javascript">
            var nVwPressed = false;
            function pressVwBtn(nBtnId) {
                if (nVwPressed) { document.getElementById("viewBtn" + nVwPressed).className = ""; }
                document.getElementById("viewBtn" + nBtnId).className = "pressedBtn";
                nVwPressed = nBtnId;
            }

            // Firefox only
            function onPGNLoaded(frEvnt) {
                var sFBody = frEvnt.target.result;
                chess.readPGN(sFBody, document.chessCtrl3.plyerClr2[1].checked);
            }

            // Firefox only
            function loadPGNFile() {
                var oFile = document.getElementById("PGNFileData").files[0];
                if (oFile) {
                    var oFReader = new FileReader();
                    oFReader.onload = onPGNLoaded;
                    oFReader.readAsText(oFile);
                }
            }

            function initChess() {
                chess.useAI(document.chessCtrl1.useAI.checked);
                chess.setPromotion(document.chessCtrl1.selPromo.selectedIndex);
                chess.setFrameRate(Math.abs(Number(document.chessCtrl2.frameRateCtrl.value)) || 1000);
                chess.setSide(document.chessCtrl2.selSide.selectedIndex);
                chess.useKeyboard(document.chessCtrl2.KeybCtrl.checked);
                chess.placeById("chessDesk");
                document.chessCtrl1.plyDepthCtrl.value = "0";
                chess.setView(1);
                pressVwBtn(1);
            }
        </script>
        <style type="text/css">
            hr {
                width: 30%;
                margin-top: 32px;
                margin-bottom: 24px;
            }

            img.tbBtn {
                cursor: pointer;
                margin: 1px 3px 1px 3px;
            }

            #pgnTable {
                width: auto;
                height: auto;
                margin-left: auto;
                margin-right: auto;
                border-collapse: collapse;
                border: 0;
            }

            #pgnTable tr td { padding: 2px; }

            #chessDesk {
                clear: both;
                width: auto;
                height: auto;
                margin-top: 32px;
                margin-bottom: 32px;
            }

            #chessToolBar {
                width: 550px;
                height: auto;
                margin: 12px auto;
                background-color: #969696;
                -moz-box-shadow: inset 0 25px 27px -10px #BDBDBD;
                -webkit-box-shadow: inset 0 25px 27px -10px #BDBDBD;
                box-shadow: inset 0 25px 27px -10px #BDBDBD;
                border-bottom: 1px solid #424242;
                text-align:center;
                padding: 6px 3px 2px 3px;
            }

            #setViewBtns {
                width: auto;
                float: left;
            }

            #setViewBtns span {
                margin: 0 3px;
                display: inline-block;
                font: 12px / 13px "Lucida Grande", sans-serif;
                text-shadow: rgba(255, 255, 255, 0.4) 0 1px;
                padding: 3px 6px;
                border: 1px solid rgba(0, 0, 0, 0.6);
                background-color: #969696;
                cursor: default;
                -moz-border-radius: 3px;
                -webkit-border-radius: 3px;
                border-radius: 3px;
                -moz-box-shadow: rgba(255, 255, 255, 0.4) 0 1px, inset 0 20px 20px -10px white;
                -webkit-box-shadow: rgba(255, 255, 255, 0.4) 0 1px, inset 0 20px 20px -10px white;
                box-shadow: rgba(255, 255, 255, 0.4) 0 1px, inset 0 20px 20px -10px white;
            }
            #setViewBtns span.pressedBtn {
                background: #B5B5B5;
                -moz-box-shadow: inset rgba(0, 0, 0, 0.4) 0 -5px 12px, inset rgba(0, 0, 0, 1) 0 1px 3px, rgba(255, 255, 255, 0.4) 0 1px;
                -webkit-box-shadow: inset rgba(0, 0, 0, 0.4) 0 -5px 12px, inset rgba(0, 0, 0, 1) 0 1px 3px, rgba(255, 255, 255, 0.4) 0 1px;
                box-shadow: inset rgba(0, 0, 0, 0.4) 0 -5px 12px, inset rgba(0, 0, 0, 1) 0 1px 3px, rgba(255, 255, 255, 0.4) 0 1px;
            }
        </style>


    </head>
    <body onload="initChess();">
        <h1>IAMJE-ChessTest</h1>

        <div id="chessDesk">
            <div id="chessboardsBox" style="width: 512px; height: 512px;">
                <div id="chess2DBox" style="width: 512px; height: 512px;">
                    <table id="flatChessboard" style="width: 500px; height: 500px; margin-bottom: 6px; margin-top: 6px;">
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
                                <th class="vertCoords">8</th>
                                <td class="whiteSquares" id="flatSq91"><span>♜</span></td>
                                <td class="blackSquares" id="flatSq92"><span>♞</span></td>
                                <td class="whiteSquares" id="flatSq93"><span>♝</span></td>
                                <td class="blackSquares" id="flatSq94"><span>♛</span></td>
                                <td class="whiteSquares" id="flatSq95"><span>♚</span></td>
                                <td class="blackSquares" id="flatSq96"><span>♝</span></td>
                                <td class="whiteSquares" id="flatSq97"><span>♞</span></td>
                                <td class="blackSquares" id="flatSq98"><span>♜</span></td>
                                <th class="vertCoords">8</th>
                            </tr>
                            <tr>
                                <th class="vertCoords">7</th>
                                <td class="blackSquares" id="flatSq81"><span>♟</span></td>
                                <td class="whiteSquares" id="flatSq82"><span>♟</span></td>
                                <td class="blackSquares" id="flatSq83"><span>♟</span></td>
                                <td class="whiteSquares" id="flatSq84"><span>♟</span></td>
                                <td class="blackSquares" id="flatSq85"><span>♟</span></td>
                                <td class="whiteSquares" id="flatSq86"><span>♟</span></td>
                                <td class="blackSquares" id="flatSq87"><span>♟</span></td>
                                <td class="whiteSquares" id="flatSq88"><span>♟</span></td>
                                <th class="vertCoords">7</th>
                            </tr>
                            <tr>
                                <th class="vertCoords">6</th>
                                <td class="whiteSquares" id="flatSq71"></td>
                                <td class="blackSquares" id="flatSq72"></td>
                                <td class="whiteSquares" id="flatSq73"></td>
                                <td class="blackSquares" id="flatSq74"></td>
                                <td class="whiteSquares" id="flatSq75"></td>
                                <td class="blackSquares" id="flatSq76"></td>
                                <td class="whiteSquares" id="flatSq77"></td>
                                <td class="blackSquares" id="flatSq78"></td>
                                <th class="vertCoords">6</th>
                            </tr>
                            <tr>
                                <th class="vertCoords">5</th>
                                <td class="blackSquares" id="flatSq61"></td>
                                <td class="whiteSquares" id="flatSq62"></td>
                                <td class="blackSquares" id="flatSq63"></td>
                                <td class="whiteSquares" id="flatSq64"></td>
                                <td class="blackSquares" id="flatSq65"></td>
                                <td class="whiteSquares" id="flatSq66"></td>
                                <td class="blackSquares" id="flatSq67"></td>
                                <td class="whiteSquares" id="flatSq68"></td>
                                <th class="vertCoords">5</th>
                            </tr>
                            <tr>
                                <th class="vertCoords">4</th>
                                <td class="whiteSquares" id="flatSq51"></td>
                                <td class="blackSquares" id="flatSq52"></td>
                                <td class="whiteSquares" id="flatSq53"></td>
                                <td class="blackSquares" id="flatSq54"></td>
                                <td class="whiteSquares" id="flatSq55"></td>
                                <td class="blackSquares" id="flatSq56"></td>
                                <td class="whiteSquares" id="flatSq57"></td>
                                <td class="blackSquares" id="flatSq58"></td>
                                <th class="vertCoords">4</th>
                            </tr>
                            <tr>
                                <th class="vertCoords">3</th>
                                <td class="blackSquares" id="flatSq41"></td>
                                <td class="whiteSquares" id="flatSq42"></td>
                                <td class="blackSquares" id="flatSq43"></td>
                                <td class="whiteSquares" id="flatSq44"></td>
                                <td class="blackSquares" id="flatSq45"></td>
                                <td class="whiteSquares" id="flatSq46"></td>
                                <td class="blackSquares" id="flatSq47"></td>
                                <td class="whiteSquares" id="flatSq48"></td>
                                <th class="vertCoords">3</th>
                            </tr>
                            <tr>
                                <th class="vertCoords">2</th>
                                <td class="whiteSquares" id="flatSq31"><span>♙</span></td>
                                <td class="blackSquares" id="flatSq32"><span>♙</span></td>
                                <td class="whiteSquares" id="flatSq33"><span>♙</span></td>
                                <td class="blackSquares" id="flatSq34"><span>♙</span></td>
                                <td class="whiteSquares" id="flatSq35"><span>♙</span></td>
                                <td class="blackSquares" id="flatSq36"><span>♙</span></td>
                                <td class="whiteSquares" id="flatSq37"><span>♙</span></td>
                                <td class="blackSquares" id="flatSq38"><span>♙</span></td>
                                <th class="vertCoords">2</th>
                            </tr>
                            <tr>
                                <th class="vertCoords">1</th>
                                <td class="blackSquares" id="flatSq21"><span>♖</span></td>
                                <td class="whiteSquares" id="flatSq22"><span>♘</span></td>
                                <td class="blackSquares" id="flatSq23"><span>♗</span></td>
                                <td class="whiteSquares" id="flatSq24"><span>♕</span></td>
                                <td class="blackSquares" id="flatSq25"><span>♔</span></td>
                                <td class="whiteSquares" id="flatSq26"><span>♗</span></td>
                                <td class="blackSquares" id="flatSq27"><span>♘</span></td>
                                <td class="whiteSquares" id="flatSq28"><span>♖</span></td>
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
                <div id="chessSizeHandle">◢</div>
            </div>
        </div>


    </body>
</html>