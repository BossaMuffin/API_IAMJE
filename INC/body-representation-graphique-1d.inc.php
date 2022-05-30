<?php
/* -------------------------------------- REPRESENTATION GRAPHIQUE 1D DU CALCUL -------------------------------------- */

// Construction d'une grille horizontale pour la progression d'un piont graphique de 0 à Objectif 
// oncalcul la largeur d'une colonne comme un ratio de l'objectif -> 100% / objectif = width de la case
$l_caseWidth = 100 / ( $$l_ALPA->g_Tobjectifs["objectif"][0] +1 )  ;

echo '
        <div id="chessDesk">
            <div id="chessboardsBox" style="width: 100%; height: 200px;">
                <div id="chess2DBox" style="width: 100%; height: 200px;">
                    <table id="flatChessboard" style="width: 98%; height: 188px; margin-bottom: 6px; margin-top: 6px;">
                        <tbody>
                            <tr>
                                <td class="boardAngle"></td>
    ' ;
                            for ( $l_hcoord = 0 ; $l_hcoord <= $$l_ALPA->g_Tobjectifs["objectif"][0] ; $l_hcoord++ )
                            {
                                echo '<th class="horizCoords">' . $l_hcoord . '</th>' ;

                            }
echo '                               
                            
                                <td class="boardAngle"></td>
                            </tr>
    ' ;
                            $l_vcoord = 1 ;
echo '
                            <tr>
                                <th class="vertCoords">' . $l_vcoord . '</th>
    ' ;

                            for ( $l_hcoord = 0 ; $l_hcoord <= $$l_ALPA->g_Tobjectifs["objectif"][0] ; $l_hcoord++ )
                            {
                                // on alterne la couleur des case : gris + blanc + gris + blanc
                                if ( $l_hcoord&1 )
                                {
                                    echo '<td class="blackSquares"' ;
                                }
                                else
                                {
                                    echo '<td class="whiteSquares"' ;
                                }
                                
                                // on note l'id de la case Coord Verticale + Coord Horizontale
                                echo 'style="width: ' . $l_caseWidth . '% !important" id="flatSq' . $l_vcoord . $l_hcoord. '">' ;
                                
                                // on place le pion dans la première case notée 0 
                                if ( $l_hcoord == 0 )
                                {
                                    echo '<span>♚</span>' ;
                                }
                                
                                // on ferme la case
                                echo '</td>' ;

                            }
                                
echo '
                                <th class="vertCoords">' . $l_vcoord . '</th>
                            </tr>
                            <tr>
                                <td class="boardAngle"></td>
    ' ;
            
                            for ( $l_hcoord = 0 ; $l_hcoord <= $$l_ALPA->g_Tobjectifs["objectif"][0] ; $l_hcoord++ )
                            {
                                echo '<th class="horizCoords">' . $l_hcoord . '</th>' ;

                            }
echo '                             
                                <td class="boardAngle"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
';