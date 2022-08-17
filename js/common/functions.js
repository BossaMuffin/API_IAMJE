$(document).ready(function(g_ALPA) 
{
    
    var g_CANVAS = {
        ctx: [],
        ctxPion: [],
        w: 1000, // longueur du canvas
        h: 300, //hauteur d'une case et du canvas
        caseW: 0,
        caseH: 0,
        pion_pos: 0, // position du pion sur la representation
        progression: 1 // progression de lecture de sequence pour position du pion
    }

    var g_ALPA = {
        sequence: [],
        sequencePropre: "",
        objectif: "",
        outil: "",
        resultat: "",
        etape: 0,
        hcoord_next: 1,
        hcoord: 0,
        outil_utile: ""
    };

    // Reponses possibles de la Question 1 
    var l_Tia_possible = ['alpa'] ; 
    var l_Tmode_possible = ['learn', 'work']  ;
    var l_Touts_possible = [
                            ['addition', '+']
                            ] ;

    var mesFonctions = {
      addition : function (x, y){ return Number(x) + Number(y) ; }
    };


   // console.log(addition(2,10));

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
            sequencePropre: [],
            objectif: "",
            outil: "",
            resultat: "",
            etape: 0,
            hcoord_next: 0,
            hcoord: 0,
            outil_utile: ""
        };
        
        // récupère les valeurs
        var f_Cshow = $('#index-input-show').val() ;

        var f_Cia = $('#index-input-ia').val() ; 
        var f_Cmode = $('#index-input-mode').val() ;
        var f_Couts = $('#index-input-outils').val() ;
        
        var f_Cobj = $('#index-input-objectif').val() ;
        var f_Cmat = $('#index-input-matiere').val() ;
        
        var f_Ndelais = $('#index-input-delais').val() ;
        var f_Nratio = $('#index-input-ratio').val() ;
        var f_Ndist = $('#index-input-distance').val() ;

        var f_Berr = Boolean( $('#index-input-erreurs').prop("checked") ) ;
        var f_Bdev = Boolean( $('#index-input-developpement').prop("checked") ) ;
        
        var f_Caff = "canvas" ;

        if ( $('#index-input-canvas').prop("checked") ){ f_Caff = $('#index-input-canvas').val() ; }
        else if ($('#index-input-html').prop("checked")){ f_Caff = $('#index-input-html').val() ; }

        console.log(f_Berr);
        console.log(f_Bdev);
        console.log(f_Caff);
        /*
        // Assignation des variables Constantes
        var textDefoSelect = 'Choisir';
        var textDefoNom = 'Nom d\'usage';
        var textDefoMail = 'Votre e-mail';
        var textDefoMobile = 'N° Mobile';
        */
        
        // verifie l'existence de l'outil utilisé
        var l_Bouts = false ;
        var l_Iouts = 0 ;
        l_Touts_possible.forEach( function( l_Cpart ) 
        {                     
            l_Cpart.indexOf( f_Couts ) != -1 ;
            l_Bouts = true ;
            if ( !l_Bouts ){ l_Iouts++ } ;
        });

        // alert( 'ia : ' + f_Cia + '/ mode : ' + f_Cmode + '/ outs : ' + f_Couts + '/ obj : ' + f_Cobj + '/ mat : ' + f_Cmat + '/ delais : ' + f_Ndelais + '/ ratio : ' + f_Nratio + '/ dist : ' + f_Ndist + '/ err : ' + f_Berr + '/ dev : ' + f_Bdev ) ;
      
        
        if ( l_Tia_possible.indexOf( f_Cia ) != -1 && l_Tmode_possible.indexOf( f_Cmode ) != -1 && l_Bouts 
                && f_Cobj != '' && f_Cmat != '' && f_Cshow == 'off'
                && ! isNaN(f_Ndelais) && ! isNaN(f_Nratio) && ! isNaN(f_Ndist)
            )  
        {
            l_html = '' ; // la representation graphique à retourner
            
            // deplacement de la fenetre vers la zone d'affichage
            $('#index-marge-bottom').show( ) ;
            $('html, body').stop().animate( {scrollTop: 800}, "swing" ) ;
            //window.scrollTo(0,800);
            //on laisse le temps ) la fenetre de descendre
            stop();
            sleep( 1000 ) ;

/* ------------------- AJAX ----------------------------------------------------------------------------------*/
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

                            // Definition des CANVAS d'affichage
                            buildCanvas() ;
                            var l_Ocanvas = document.getElementById('index-canvas-bg') ;
                            var l_OcanvasPion = document.getElementById('index-canvas-pion') ;


                            if ( ! l_BtestJson )
                            { 
                                // D'abord on efface les zones HTML
                                $('#index-zone-exec').hide( ) ;
                                $('#index-zone-chess-html').html( "" ) ;

                                // on efface la zone CANVAS
    
                                $('#index-zone-chess-canvas').html( "" ) ;
                                

                                // data n'est pas JSON 
                                $('#zone-err-php').show().html( "<h3>Not JSON</h3>" ) ;
                                // on affiche le resultat brut
                                l_html = data ;

                            }
                            else 
                            {
                                $('#zone-err-php').hide() ;
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



                                //on enregistre objectif, resultat et outils dans la propriété g_ALPA
                                g_ALPA.objectif = l_Oresult[l_Cprop]["objectif"]["value"] ;
                                g_ALPA.resultat = l_Oresult[l_Cprop]["resultat"]["value"] ;
                                g_ALPA.outil = l_Oresult[l_Cprop]["outils"]["value"] ;

                                //on recupere la sequence sous forme de chaine de caractère
                                l_Csequence = l_Oresult[l_Cprop]["sequence"]["value"] ; 
                                var l_Tsequence = [] ;
                                
                                // on construit un tableau alternant outil et matiere à partir de la sequence
                                if ( l_Csequence != "" )
                                {
                                    l_Tsequence = l_Csequence.split('\-\-') ;
                                }

                                // on a recuperé la sequence sous la forme : ["", "1", "addition", "1", "addition", [...] "1", "addition", "1"]
                                //on enregistre sequence et outils dans la propriété g_ALPA
                                g_ALPA.sequence = l_Tsequence ;
                                
         // ------------------------------ AFFICHAGE ------------------------
                        // NETOYAGE DE LA SEQUENCE
                                // on cree une sequence "propre" pour l'affichage
                                g_ALPA.sequence.forEach(function(l_Cpart) 
                                {
                                  
                                  var l_CpartPropre = l_Cpart.replace( 'o++', '' ).replace( 'm++', '' ).replace( f_Couts, l_Touts_possible[l_Iouts][1] ) ;
                                  //l_CpartPropre = l_CpartPropre.replace( 'm++', '' ) ;
                                  g_ALPA.sequencePropre += l_CpartPropre ;

                                }); 
                
                        // AFFICHAGE DE LA SEQUENCE PROPRE
                                $('#zone-sequence-propre').show() ;
                                $('#zone-sequence-propre').html( g_ALPA.sequencePropre + '  =  ' + g_ALPA.resultat + '  ~  ' + g_ALPA.objectif ) ;

                                // Affichage de la data brute
                                // $('#zone-data-brute').show() ;
                                // $('#zone-data-brute').html( data ) ;
                                

                    // AFFICHAGE CANVAS ----------------------------------------------------------------
                    // ----------------------------------------------------------------------------------

                                if ( l_Ocanvas.getContext && f_Caff=="canvas")
                                {
                                    // D'abord on efface les zones HTML
                                    $('#index-zone-exec').hide( ) ;
                                    $('#index-zone-chess-html').html( "" ) ;
                                    


                                    g_objectif = Number( g_ALPA.objectif ) ;

                                // RECUPERE LA TAILLE DE LA FENETRE
                                var g_winR = window.devicePixelRatio ; //XXXXXXXXXXXXXXXXXXXXXXxx unless

                                // LANCEMENT DU CONTEXTE D'ANIMATION : HTML ou CANVAS
                                   g_CANVAS.ctx = l_Ocanvas.getContext('2d') ;
                                   g_CANVAS.ctxPion = l_OcanvasPion.getContext('2d') ;

                                // REGLAGE TAILLE CANVAS
                                    g_CANVAS.caseW = g_CANVAS.w / g_objectif ;
                                    g_CANVAS.caseH = g_CANVAS.h ;

                                // TAILLE DU CANVAS BG
                                    l_Ocanvas.width = g_CANVAS.w ;
                                    l_Ocanvas.height = g_CANVAS.h ;
                                    
                                    l_Ocanvas.style.width = g_CANVAS.w + "px" ;
                                    l_Ocanvas.style.height = g_CANVAS.h + "px" ;

                                    //  g_CANVAS.ctx.scale( g_winR, g_winR ) ;
                                    
                                // TAILLE DU CANVAS
                                    l_OcanvasPion.width = g_CANVAS.w ;
                                    l_OcanvasPion.height = g_CANVAS.h ;
                                    
                                    l_OcanvasPion.style.width = g_CANVAS.w + "px" ;
                                    l_OcanvasPion.style.height = g_CANVAS.h + "px" ;

                                    //on laisse le temps ) la fenetre de descendre
                                    //sleep( 1000 ) ;

                                // code de dessin dans le canvas
                                    representation_graphique_canvas_1d( g_CANVAS.caseH ) ;
                                

                                } 
                                else if (f_Caff == "html")
                                {

                        // AFFICHAGE HTML --------------------------------------------------------------
                        // ----------------------------------------------------------------------------

                                    // on efface la zone CANVAS
                                    $('#index-zone-chess-canvas').html( "" ) ;
                                    buildCanvas() ;

                                    //on laisse le temps ) la fenetre de descendre
                                    //sleep( 1000 ) ;

                                    // code pour le cas où canvas ne serait pas supporté
                                    // on va pouvoir animer l'affichage en suivant le schéma
                                    l_html = representation_graphique_html_1d( g_ALPA.objectif ) ;
                                    $('#index-zone-exec').show( ) ;
                                }
                                else
                                {
                        // AFFICHAGE ERREUR -----------------------------------------------------------
                                    // on efface la zone HTML
                                    $('#index-zone-chess-html').html( "" ) ;
                                    // on efface la zone CANVAS
                                    $('#index-canvas-bg').html( "" ) ;
                                    $('#index-canvas-pion').html( "" ) ;
                                    // code pour le cas où canvas ne serait pas supporté
                                    // on va pouvoir animer l'affichage en suivant le schéma
                                    l_html = "Le type d'affichage demandé n'est pas reconnu. Veuillez choisir parmi les options proposées ou demander le développement/débuggage de cette méthode d'affichage." ;
                                    $('#index-zone-exec').show( ) ;
                                }
                                




                                //Affichage du bouton "animation"
                                
                                $('#zone-resultat').html( '<b>' + g_ALPA.resultat + '<b>' ) ;
                            }


 

                            //Affichage des erreurs 99999999999 A TRAITER
                            $('#zone-notif-erreur').html() ;

                            // Affichage de la zone d'affichage
                            $('#index-zone-affichage').show() ;
                            $('#index-zone-chess-html').html( l_html ) ;
                        


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
    function buildCanvas() 
    {
        var canvasHTML = '<canvas id="index-canvas-bg" style="width:1000px; height:300px; position:absolute; top:0px; left:0px; margin-top: 45px;"></canvas><canvas id="index-canvas-pion" style="width:1000px; height:300px; position:absolute; top:0px; left:0px; margin-top: 45px;">' ;
        $('#index-zone-chess-canvas').html( canvasHTML ) ;
    }
                           
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




/* ------------------------------------------------------------------------------------ */
/* -------------------------------------- CANVAS -------------------------------------- */
/* ------------------------------------------------------------------------------------ */

                                
/* -------------------------------------- REPRESENTATION GRAPHIQUE CANVAS 1D DU CALCUL -------------------------------------- */
    function representation_graphique_canvas_1d() 
    {
        
        //g_CANVAS.ctxPion.clearRect( 20, 20, g_CANVAS.w, g_CANVAS.caseH ) ;
        canvas_draw_damier() ;
        sleep( 400 ) ;
        canvas_draw_pion() ;
 
        // si la sequence est encore exploitable on met à jour la position du pion et on relance le dessin
        if ( g_ALPA.sequence[ g_CANVAS.progression ] != undefined )
        {   
            // animation du pion
            canvas_update_pion_position() ;
            // on repete le mouvement
            requestAnimationFrame( representation_graphique_canvas_1d ) ;
        }
        else 
        {
            g_CANVAS.progression = 1 ;
            g_CANVAS.pion_pos = 0 ;
        }
    }

/* -------------------------------------- UPADTE POSITION PION -------------------------------------- */
    function canvas_update_pion_position()
    {
        if (g_CANVAS.progression == 1 )
        {
            console.log(g_CANVAS.progression + ":" + g_CANVAS.pion_pos + "->") ;
            g_CANVAS.pion_pos = Number( g_ALPA.sequence[ 1 ].substr(3) ) ;
            console.log(g_CANVAS.pion_pos) ;
            g_CANVAS.progression++ ;
        }
        else
        {
            // code le dessin dans le canvas
            if ( g_ALPA.sequence[ g_CANVAS.progression ].substr(0, 3) == 'o++')
            {
                var l_outs = g_ALPA.sequence[ g_CANVAS.progression ].substr(3) ;
            }
            else 
            {
                console.log("Pb lecture sequence, outil ?") ;
            }


            if ( g_ALPA.sequence[ g_CANVAS.progression + 1 ].substr(0, 3) == 'm++' )
            {
                g_ALPA.sequence[ g_CANVAS.progression + 1 ].substr(3) ;
            }
            else 
            {
                console.log("Pb lecture sequence, matiere ?") ;
            }
           
            console.log(g_CANVAS.progression + ":" + g_CANVAS.pion_pos + "->") ;
            // on va chercher la fonction, dans l'objet mesFonctions, correspondant à l'outil dans la sequence de calcul
            g_CANVAS.pion_pos =  mesFonctions[l_outs]( g_CANVAS.pion_pos, g_ALPA.sequence[ g_CANVAS.progression + 1 ].substr(3) ) ;
            console.log(g_CANVAS.pion_pos) ;
            g_CANVAS.progression = g_CANVAS.progression + 2 ; 

        }

       




        //console.log();
    }


/* -------------------------------------- DRAW BACKGROUND DAMIER -------------------------------------- */
    function canvas_draw_damier() 
    {

        g_CANVAS.ctx.font = '20px Times New Roman';
        
        
        // dessin du damier 1D
        for ( var l_hcoord = 0 ; l_hcoord < g_ALPA.objectif ; l_hcoord++ )
        {
            if ( l_hcoord&1 )
            {
                l_color = '211,116,116' ;
            }
            else
            {
                l_color = '255,214,214' ;
            }
                g_CANVAS.ctx.fillStyle = 'rgb(' + l_color + ')' ;
            // on dessine un rectangle damier
                g_CANVAS.ctx.fillRect( l_hcoord * g_CANVAS.caseW, 0, g_CANVAS.caseW, g_CANVAS.caseH ) ;
            // on numérote les case 
                g_CANVAS.ctx.fillStyle = 'Black';
                g_CANVAS.ctx.fillText(l_hcoord , g_CANVAS.caseW * (l_hcoord + 0.3), 30);

        }
        g_CANVAS.ctx.fillStyle = 'Black';
        g_CANVAS.ctx.strokeRect( 0, 0, g_CANVAS.w, g_CANVAS.w ) ;
    }

/* -------------------------------------- DRAW PION -------------------------------------- */
    function canvas_draw_pion() 
    {
        if ( g_CANVAS.caseW < g_CANVAS.caseH )
        {
            l_rayon = g_CANVAS.caseW  / 2 ;
        }
        else
        {
            l_rayon = g_CANVAS.caseH  / 2 ;
        }
        
        // dessin du pion
            g_CANVAS.ctxPion.fillStyle = 'rgb(0,0,0,' + (0.5 + g_CANVAS.pion_pos * ( 1 / (2 *g_ALPA.objectif) )) + ')';
            g_CANVAS.ctxPion.beginPath() ;
            g_CANVAS.ctxPion.arc( (g_CANVAS.caseW / 2) + (g_CANVAS.pion_pos * g_CANVAS.caseW), g_CANVAS.caseH / 2, l_rayon, 0, Math.PI * 2, false ) ;
            g_CANVAS.ctxPion.fill() ;
    }




/* ---------------------------------------------------------------------------------- */
/* -------------------------------------- HTML -------------------------------------- */
/* ---------------------------------------------------------------------------------- */




/* -------------------------------------- REPRESENTATION GRAPHIQUE HTML 1D DU CALCUL -------------------------------------- */
    function representation_graphique_html_1d( g_objectif ) 
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
            //sleep( 300 ) ;
        
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


// Fin JQUERY DOCUMENT FONCTIONS

});


                        

                           
   