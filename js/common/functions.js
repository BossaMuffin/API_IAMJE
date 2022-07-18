

   
$(document).ready(function(g_ALPA) 
{

    var g_ALPA = {
        sequence: [],
        objectif: "",
        outil: "",
        resultat: "",
        etape: 0,
        hcoord_next: 1,
        hcoord: 0,
        outil_utile: ""
    };

    // TRAITEMENT FORMAULAIRE
    //$("#index-zone-form").submit(function(){
    $(document).on('submit', '#index-zone-form', function(e)
    {

        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire

        var $this = $(this); // L'objet jQuery du formulaire

        var l_CerrorNotif = ''; // variable de notification d'erreur à incrémenter avec chaque message d'erreur => sera affiché en front 
        
        // remise à zéro de la représenttion graphique et de la propriété g_ALPA
        tare_chess_1D()
        g_ALPA = {
            sequence: [],
            objectif: "",
            outil: "",
            resultat: "",
            etape: 0,
            hcoord_next: 0,
            hcoord: 0,
            outil_utile: ""
        };
        
        // récupère les valeurs
        var f_Cshow = $('#index-input-show').val();

        var f_Cia = $('#index-input-ia').val();
        var f_Cmode = $('#index-input-mode').val();
        var f_Couts = $('#index-input-outils').val();
        
        var f_Cobj = $('#index-input-objectif').val();
        var f_Cmat = $('#index-input-matiere').val();
        
        var f_Ndelais = $('#index-input-delais').val();
        var f_Nratio = $('#index-input-ratio').val();
        var f_Ndist = $('#index-input-distance').val();

        var f_Berr = $('#index-input-erreurs').val();
        var f_Bdev = $('#index-input-developpement').val();

        
        /*
        // Assignation des variables Constantes
        var textDefoSelect = 'Choisir';
        var textDefoNom = 'Nom d\'usage';
        var textDefoMail = 'Votre e-mail';
        var textDefoMobile = 'N° Mobile';
        */

        // Reponses possibles de la Question 1 
        var l_Tia_possible = ['alpa'] ; 
        var l_Tmode_possible = ['learn', 'work']  ;
        var l_Touts_possible = ['addition'] ;

    // alert( 'ia : ' + f_Cia + '/ mode : ' + f_Cmode + '/ outs : ' + f_Couts + '/ obj : ' + f_Cobj + '/ mat : ' + f_Cmat + '/ delais : ' + f_Ndelais + '/ ratio : ' + f_Nratio + '/ dist : ' + f_Ndist + '/ err : ' + f_Berr + '/ dev : ' + f_Bdev ) ;
                     
        if ( l_Tia_possible.indexOf( f_Cia ) != -1 && l_Tmode_possible.indexOf( f_Cmode ) != -1 && l_Touts_possible.indexOf( f_Couts ) != -1 
                && f_Cobj != '' && f_Cmat != '' && f_Cshow == 'off'
                && ! isNaN(f_Ndelais) && ! isNaN(f_Nratio) && ! isNaN(f_Ndist)
            )  
        {
            l_html = '' ; // la representation graphique à retourner
            // on envoi les données à l'API 
            $.ajax({ 
                        //dataType: 'json', // le retirer pour afficher un resultat "brut" et decommenter la ligne $('#zone-notif-erreur').html(data);
                        url: "api/index.php", // $this.attr('action')  Le nom du fichier indiqué dans le formulaire
                        type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
                        data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
                        //data: 'show='+f_Cshow+'&ia='+f_Cia+'&mode='+f_Cmode+'&outs='+f_Couts+'&obj='+f_Cobj+'&mat='+f_Cmat+'&delais='+f_Ndelais+'&ratio='+f_Nratio+'&dist='+f_Ndist+'&err='+f_Berr+'&dev='+f_Bdev,
                        success: function(data){ // Je récupère la réponse du fichier PHP
                            var l_Csequence = "" ;
                            // Pour vous assurer de la validité d'une chaîne JSON avant de l'évaluer, vous pouvez utiliser le code suivant :
                            var l_BtestJson = !(/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/.test(
                                 data.replace(/"(\\.|[^"\\])*"/g, ''))) && eval('(' + data + ')'
                            );

                            if ( ! l_BtestJson )
                            { 
                                // data n'est pas JSON 
                                alert('not JSON') ;
                            }
                            else 
                            {
                                /*
                                 JSON.parse(data, (key, value) => {
                                        console.log(key);            // on affiche le nom de la propriété dans la console
                                        return value;                // et on renvoie la valeur inchangée.
                                    });
                                */
                                var l_Oresult = JSON.parse( data ) ; 

                                for (var i in l_Oresult) 
                                {
                                    if (l_Oresult.hasOwnProperty(i)) {
                                        var l_Cprop = i ;
                                    }
                                }

                                l_Csequence = l_Oresult[l_Cprop]["sequence"]["value"] ; 
                                g_ALPA.objectif = l_Oresult[l_Cprop]["objectif"]["value"] ;
                                g_ALPA.resultat = l_Oresult[l_Cprop]["resultat"]["value"] ;

                            }

                            var k = 0 ;
                            var l_Tseq = [];
                            var l_Tseq_temp = [] ;
                            var l_Tsequence = [] ;
                            
                            // on construit un tableau alternant outil et matiere à partir de la sequence
                            if ( l_Csequence != "" )
                            {
                                l_Tsequence = l_Csequence.split('\-\-') ;

                            }
                            //on enregistre sequence et objectif dans la propriété g_ALPA
                            g_ALPA.sequence = l_Tsequence ;
                            g_ALPA.outil = l_Tsequence ;
                            console.log(g_ALPA);

                            // on a recuperé la sequence sous la forme : ["", "1", "addition", "1", "addition", [...] "1", "addition", "1"]
                            // on va pouvoir animer l'affichage en suivant le schéma
                            l_html = representation_graphique_1d( g_ALPA.objectif ) ;

                            $('#zone-notif-erreur').show() ;
                            $('#zone-notif-erreur').html() ;
                            $('#index-zone-affichage').show() ;
                            $('#index-zone-chess').html( l_html ) ;
                            $('#zone-resultat').html( g_ALPA.resultat ) ;

                        },

                        cache: false

            }).fail(function (jqXHR, textStatus, error) {
                // Handle error here
                $('#landing-notif-erreur').show();
                $('#landing-notif-erreur').html(jqXHR.responseText);
            });
        
        } 
        else 
        {
            
            l_CerrorNotif = l_CerrorNotif + 'Respecter les valeurs de champs possibles ' ;
            
            $('#zone-notif-erreur').show() ;
            $('#zone-notif-erreur').html( '<h4 class="uppercase">Et maintenant ?</h4><div class="row jaune"><div class="col-lg-12"> Votre demande n\'a pas aboutie</div></div><div class="row jaune"><div class="col-lg-12">' + l_CerrorNotif + '</div> </div>' ) ;
        }

        return false;
    });


    /* -------------------------------------- SLEEP -------------------------------------- */
    function sleep(Nmilliseconds) 
    {
      var start = new Date().getTime() ;
      for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > Nmilliseconds){
          break;
        }
      }
    }

    /* -------------------------------------- TARE CHESS -------------------------------------- */
    function tare_chess_1D() 
    {
        $('#flatSq1'+g_ALPA.objectif).html( "" ) ;
        $('#flatSq10').html( "<span>♚</span>" ) ;
    }

    /* -------------------------------------- EXECUTION AFFICHAGE -------------------------------------- */
    $(document).on('submit', '#index-zone-exec', function(e){

        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire

        var $this = $(this); // L'objet jQuery du formulaire

        var l_CerrorNotif = ''; // variable de notification d'erreur à incrémenter avec chaque message d'erreur => sera affiché en front 

        // remise à zéro 
        tare_chess_1D()

     // on va faire avancer le pion en suivant le schéma 
        var l_hcoord_next ;
        // on parcours la sequence dan g_ALPA
        for ( var l_i = g_ALPA.etape ; l_i < g_ALPA.sequence.length ; l_i++ )
        {
            // on garde en memoire l'étape de calcul
            g_ALPA.etape = l_i ;

            // on analyse la sequence pour departager outs et matières
            l_Tseq_outs = g_ALPA.sequence[l_i].split('o\+\+') ;
            l_Tseq_mat = g_ALPA.sequence[l_i].split('m\+\+') ;
            
            if ( l_Tseq_mat.length > 1 )
            {
                // on a la matiere
                //l'etape 1 on recupere l'initiale comme coordonnée initale
                if ( l_i == 1 )
                { 
                    g_ALPA.hcoord = l_Tseq_mat[1]; 
                    // on retire le pion 
                    $('#flatSq1-0').html( "" ) ;
                }
                else
                {
                    //on garde le precedent afin de retirer le pion de son emplacement
                    l_hcoord_prev = g_ALPA.hcoord ;
                    //on incremente la nouvelle position
                    g_ALPA.hcoord = Number( g_ALPA.hcoord ) + Number( l_Tseq_mat[1] ) ;
                    //on bouge le pion
                    move_1d( l_hcoord_prev, g_ALPA.hcoord  ) ;
                }
            }
            else if (l_Tseq_outs.length > 1)
            {
                // on a l'outil
                // on garde en memoire l'outil pour l'utiliser avec lamatiere suivante
                g_ALPA.outil_utile = l_Tseq_outs[1] ;
            }
            else
            {
                console.log( l_Tseq_mat + l_Tseq_outs ) ;
            }
            
            //move_1d( l_hcoord ) ;
            sleep( 300 ) ;
        
        }

        return false;
    });


    /* -------------------------------------- AVANCE PION - MOVE 1D -------------------------------------- */
    function move_1d( g_hcoord, g_hcoord_next  ) 
    {
        // on efface le pion de sa position actuelle 
        console.log('#flatSq1-' + g_hcoord);

        $('#flatSq1-' + g_hcoord).html( "" ) ;
        // on le place sur sa nouvelle place
        $('#flatSq1-' + g_hcoord_next).html( "<span>♚</span>" ) ;

    }

    /* -------------------------------------- REPRESENTATION GRAPHIQUE 1D DU CALCUL -------------------------------------- */
    function representation_graphique_1d( g_objectif ) 
    {
        // Construction d'une grille horizontale pour la progression d'un piont graphique de 0 à Objectif 
        // oncalcul la largeur d'une colonne comme un ratio de l'objectif -> 100% / objectif = width de la case
        
        // variable retourné, la grille html à afficher
        var html = '' ;
        var l_caseWidth ;
        var l_vcoord = 1 ;
        g_objectif = Number( g_objectif ) ;
        l_caseWidth = 100 / ( g_objectif + 1 )  ;

        html += '<div id="chessDesk">' ;
        html += '        <div id="chessboardsBox" style="width: 100%; height: 200px;">' ;
        html += '           <div id="chess2DBox" style="width: 100%; height: 200px;">' ;
        html += '                <table id="flatChessboard" style="width: 98%; height: 188px; margin-bottom: 6px; margin-top: 6px;">' ;
        html += '                    <tbody>' ;
        html += '                        <tr>' ;
        html += '                            <td class="boardAngle"></td>' ;

        for ( var l_hcoord = 0 ; l_hcoord <= g_objectif ; l_hcoord++ ) 
        {
           html += '<th class="horizCoords">' + l_hcoord + '</th>' ;
        }

        html += ' <td class="boardAngle"></td>' ;
        html += '        </tr>' ;
        html += '        <tr>' ;
        html += '            <th class="vertCoords">' + l_vcoord + '</th>' ;

        for ( var l_hcoord = 0 ; l_hcoord <= g_objectif ; l_hcoord++ )
        {
            // on alterne la couleur des case : gris + blanc + gris + blanc
            if ( l_hcoord&1 )
            {
                html += '<td class="blackSquares"' ;
            }
            else
            {
                html += '<td class="whiteSquares"' ;
            }
            
            // on note l'id de la case Coord Verticale + Coord Horizontale
            html += 'style="width: ' + l_caseWidth + '% !important" id="flatSq' + l_vcoord + '-' + l_hcoord + '">' ;
            
            // on place le pion dans la première case notée 0 
            if ( l_hcoord == 0 )
            {
                html += '<span>♚</span>' ;
            }
            
            // on ferme la case
            html += '</td>' ;

        }
                                 
        html += ' <th class="vertCoords">' + l_vcoord + '</th>';
        html += '        </tr>';
        html += '        <tr>';
        html += '            <td class="boardAngle"></td>' ;
                
        for ( var l_hcoord = 0 ; l_hcoord <= g_objectif ; l_hcoord++ )
        {
            html += '<th class="horizCoords">' + l_hcoord + '</th>' ;

        }

        html += '               <td class="boardAngle"></td>';
        html += '                    </tr>';
        html += '                </tbody>';
        html += '            </table>';
        html += '        </div>';
        html += '    </div>';
        html += '</div>';


        return html ;
    }


// Fin JQUERY DOCUMENT FONCTIONS

});


                        

                           
   