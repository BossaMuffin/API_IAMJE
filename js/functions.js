$(document).ready(function (ele) 
{
    var g_scrollY = window.scrollY ;
    var g_scrollYpas = 400 ;

    // Durée total d'une animation CANVAS en ms
    var g_NdureeAnimation = 2500 ;
    
    // Paramétrage de la zone d'affichage CANVAS
    var g_CANVAS = {
        w: 1000, // longueur du canvas
        h: 200, //hauteur d'une case et du canvas
        caseW: 0,
        caseH: 0
    }
    // donnée de formualaire : CANVAS ou HTML
    var f_Caff = "canvas" ;
    var f_Cshow = "off" ;
    // jeton temoin du n° de CANVAS en cours de traitement 
    //lorsque plusieur tableau de resultats sont reçus
    var g_Ncanvas = 0 ;
    // liste des n° des tableaux de resultats reçus
    var g_Tprop = [] ;
    // l'animation d'un tableau a t-il été entièrement traité avant de passer au tableau suivant ?
    var g_BcanvasTemoin = 1 ;

    // Objet qui recevra les datas formatées PARSE.JSON
    var l_Oresult = [] ;

    // Table qui recevra les datas formatées pour exploitation graphique
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
    
    // Donnée de formulaire : outil choisi
    var f_Couts = "" ;
    // Numéro indentifiant l'outil
    var l_Iouts = 0 ;
    var mesFonctions = {
      addition : function (x, y){ return Number(x) + Number(y) ; },
    };
    
    // non fonctionnel pour le moment
    mesFonctions.multiplication = function (x, y){ return Number(x) * Number(y) ; }
    mesFonctions.soustraction = function (x, y){ return Number(x) - Number(y) ; }

   // console.log(addition(2,10));

   // Affiche le controleur de type d'affichage HTML ou CANVAS
    switchAff() ;

    $(document).on('click', '#index-input-devmode ', function (e)
    {
        // Affiche le controleur de type d'affichage HTML ou CANVAS si pas en mode DEV
        switchAff() ;

        
    });





    // --------------------------- TRAITEMENT FORMAULAIRE
    $(document).on('submit', '#index-zone-form', function (e)
    {

        e.preventDefault() ; // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire

        var $this = $(this) ; // L'objet jQuery du formulaire

        // -------------------------- LOADER SPINNER ------------------------------
        $('.has-spinner').buttonLoader('start');

        //   ------------------------ REMISE à 0 -------------------------------


        // si mode Normal on passe le mode show en false : renvoi json
        if ( $('#index-input-normal').is(':checked'))
        {
            $('#index-input-show-json').prop('checked', true) ;
            $('#index-input-show-table').prop('checked', false) ;
        }



        // variable de notification d'erreur à incrémenter avec chaque message d'erreur => sera affiché en front 
        var l_CerrorNotif = ''; 
        // remise à zéro de la représenttion graphique et de la propriété g_ALPA
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

        // numéro tableau lorsque plusieurs tableaux de resultats sont reçus
        g_Ncanvas = 0 ;
        // liste des n° des tableaux de resultats reçus
        g_Tprop = [] ;
        // l'animation d'un tableau a t-il été entièrement traité avant de passer au tableau suivant ?
        g_BcanvasTemoin = 1 ;

        // récupère les valeurs

        var f_Cia = $('#index-input-ia').val() ; 
        var f_Cmode = $('#index-input-mode').val() ;
        var f_Couts = $('#index-input-outils').val() ;
        
        var f_Cobj = $('#index-input-objectif').val() ;
        var f_Cmat = $('#index-input-matiere').val() ;
        
        var f_Ndelais = $('#index-input-delais').val() ;
        var f_Nratio = $('#index-input-ratio').val() ;
        var f_Ndist = $('#index-input-distance').val() ;
    
        // Affiche le controleur de type d'affichage HTML ou CANVAS si pas en mode DEV
        switchAff() ;

        if ( $('#index-input-canvas').prop("checked") ){ f_Caff = $('#index-input-canvas').val() ; }
        else if ($('#index-input-html').prop("checked")){ f_Caff = $('#index-input-html').val() ; }

        
        // verifie l'existence de l'outil utilisé
        var l_Bouts = false ;
        
        l_Touts_possible.forEach(function(l_Touts) {
            if ( l_Touts.indexOf( f_Couts ) != -1 )
            {
                l_Bouts = true ;
            }  
        });
                       
        if ( !l_Bouts ){ l_CerrorNotif += "L'outils demandé n'est pas configuré ; " ; }


        // D'abord on cache les affichages précédents
        $('#index-zone-affichage').hide( ) ;
        $('#index-zone-exec').hide( ) ;
        $('#index-zone-html').html( "" ) ;
        // on efface la zone CANVAS
        $('#index-zone-chess-canvas').html( "" ) ;
        // on efface la zone erreurs 
        $('#zone-notif-erreur').html("") ;

            // alert( 'ia : ' + f_Cia + '/ mode : ' + f_Cmode + '/ outs : ' + f_Couts + '/ obj : ' + f_Cobj + '/ mat : ' + f_Cmat + '/ delais : ' + f_Ndelais + '/ ratio : ' + f_Nratio + '/ dist : ' + f_Ndist + '/ dev : ' + f_Bdev ) ;
        if ( l_Tia_possible.indexOf( f_Cia ) != -1 && l_Tmode_possible.indexOf( f_Cmode ) != -1 && l_Bouts 
                && f_Cobj != '' && f_Cmat != '' && ( f_Cshow == 'off' || f_Cshow == 'on' )
                && ! isNaN(f_Ndelais) && ! isNaN(f_Nratio) && ! isNaN(f_Ndist)
            )  
        {
            l_html = '' ; // la representation graphique à retourner
            
            // deplacement de la fenetre vers la zone d'affichage
            $('#index-marge-bottom').show( ) ;
            $('html, body').animate( {scrollTop: 1100}, "swing" ) ;
            //window.scrollTo(0,800);


/* ------------------- AJAX ----------------------------------------------------------------------------------*/
            // on envoi les données à l'API 
            $.ajax({ 
                        //dataType: 'json', // le retirer pour afficher un resultat "brut" et decommenter la ligne $('#zone-notif-erreur').html(data);
                        url: "api/index.php", // $this.attr('action')  Le nom du fichier indiqué dans le formulaire
                        type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
                        data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
                        //data: 'show='+f_Cshow+'&ia='+f_Cia+'&mode='+f_Cmode+'&outs='+f_Couts+'&obj='+f_Cobj+'&mat='+f_Cmat+'&delais='+f_Ndelais+'&ratio='+f_Nratio+'&dist='+f_Ndist+'&err='+f_Berr+'&dev='+f_Bdev,
                        success: function(data){ // Je récupère la réponse du fichier PHP
                            
                            // Pour vous assurer de la validité d'une chaîne JSON avant de l'évaluer, vous pouvez utiliser le code suivant :
                            var l_BtestJson = !(/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/.test(
                                 data.replace(/"(\\.|[^"\\])*"/g, ''))) && eval('(' + data + ')'
                            );


                            if ( ! l_BtestJson )
                            { 
                                // D'abord on efface les zones HTML
                                $('#index-zone-exec').hide( ) ;
                                $('#index-zone-html').html( "" ) ;
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
                                // on recupere le resultat, PARSE.JSON puis on separe les resultats
                                l_Oresult = [] ;
                                l_Oresult = JSON.parse( data ) ; 
                                //console.log(l_Oresult) ;
                                for (var i in l_Oresult) 
                                {
                                    if (l_Oresult.hasOwnProperty(i)) {
                                        g_Tprop.push(i) ;
                                        //console.log(i);
                                    }
                                }

                                // on construit le nobres de canvas nécessaire à chaque resultat
                                buildCanvas( g_Tprop ) ;
                                
                                // Definition des CANVAS d'affichages
            /* ************************************************************************ */

                                parcourtResultToAffichage() ;

            /* ************************************************************************ */                             
                                                                       

                                // Affichage du bresultat graphique
                                $('#zone-resultat').html( '<b>' + g_ALPA.resultat + '<b>' ) ;
                            }



                            //Affichage des erreurs 99999999999 A TRAITER
                            $('#zone-notif-erreur').html() ;

                            // Affichage de la zone d'affichage
                            $('#index-zone-affichage').show() ;
                            $('#index-zone-html').html( l_html ) ;

                        
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
            // il y a erreur dans les valeurs envoyées via le formulaire

            // on stoppe le spinner
            $('.has-spinner').buttonLoader('stop') ;
            l_CerrorNotif = l_CerrorNotif + 'Respecter les valeurs de champs possibles. ' ;
            
            $('#zone-notif-erreur').show() ;
            $('#zone-notif-erreur').html( '<h4 class="uppercase">Et maintenant ?</h4><div class="row"><div class="col-lg-12"> Votre demande n\'a pas aboutie.</div></div><div class="row"><div class="col-lg-12">' + l_CerrorNotif + '</div> </div>' ) ;
        }

        // return false;
    });


/* -------------------------------------- SLEEP -------------------------------------- */
// Affiche le controleur de type d'affichage HTML ou CANVAS si pas en mode DEV
    function parcourtResultToAffichage() 
    {

            g_BcanvasTemoin = 0 ;
            g_ALPA.sequence = [] ;
            g_ALPA.sequencePropre = "" ;

            var l_Tsequence = [] ;
            var l_Csequence = "" ;
            var l_CpartPropre = "" ;
            var l_Cpart = "" ;

            //console.log(g_Ncanvas) ;
            var l_Ocanvas = document.getElementById('index-canvas-bg-' + g_Ncanvas ) ;
            var l_OcanvasPion = document.getElementById('index-canvas-pion-' + g_Ncanvas) ;

            //console.log( l_Oresult[g_Ncanvas].objectif ) ;
           
            //on enregistre objectif, resultat et outils dans la propriété g_ALPA
            g_ALPA.objectif = l_Oresult[g_Ncanvas].objectif["value"] ;
            g_ALPA.resultat = l_Oresult[g_Ncanvas].resultat["value"] ;
            g_ALPA.outil = l_Oresult[g_Ncanvas].outils["value"] ;
            
            //on recupere la sequence sous forme de chaine de caractère
            l_Csequence = l_Oresult[g_Ncanvas].sequence["value"] ; 



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

            // console.log("seq 1 : " + g_ALPA.sequence) ;
            g_ALPA.sequence.forEach(function(l_Cpart) 
            {
              // console.log("Part : " + l_Cpart) ;
              l_CpartPropre = l_Cpart.replace( "o++", "" ).replace( "m++", "" ).replace( l_Touts_possible[l_Iouts][0], l_Touts_possible[l_Iouts][1] ) ;

            // console.log("PartPropre : " + l_CpartPropre );
              g_ALPA.sequencePropre += l_CpartPropre ;
             

            }); 

    // AFFICHAGE DE LA SEQUENCE PROPRE
            l_NcanvasAff = g_Ncanvas + 1 ;
            
            $('#zone-sequence-propre-' + g_Ncanvas ).html( '<b>' + l_NcanvasAff + '</b><p>' + g_ALPA.sequencePropre + '  =  ' + g_ALPA.resultat + '  ~  ' + g_ALPA.objectif + '</p>' ) ;
            $('#zone-sequence-propre-' + g_Ncanvas ).show() ;
            // Affichage de la data brute
            // $('#zone-data-brute').show() ;
            // $('#zone-data-brute').html( data ) ;
            

// AFFICHAGE CANVAS ----------------------------------------------------------------
// ----------------------------------------------------------------------------------

            if ( l_Ocanvas.getContext && f_Caff=="canvas")
            {
                // D'abord on efface les zones HTML
                $('#index-zone-exec').hide() ;
                $('#index-zone-html').html( "" ) ;
                // console.log("OBJ : " + g_ALPA.objectif ) ;
                g_objectif = Number( g_ALPA.objectif ) ;

            // RECUPERE LA TAILLE DE LA FENETRE unless
            // var g_winR = window.devicePixelRatio ;

            // console.log("NCANVAS : " + g_Ncanvas ) ;
                g_CANVAS[g_Ncanvas] = 
                {
                    ctx: [],
                    ctxPion: [],
                    pion_pos: 0, // position du pion sur la representation
                    progression: 1 // progression de lecture de sequence pour position du pion
                }

            // LANCEMENT DU CONTEXTE D'ANIMATION : HTML ou CANVAS
                g_CANVAS[g_Ncanvas].ctx = l_Ocanvas.getContext('2d') ;
                g_CANVAS[g_Ncanvas].ctxPion = l_OcanvasPion.getContext('2d') ;

            
            // REGLAGE TAILLE CANVAS
                g_CANVAS.caseW = g_CANVAS.w / (g_objectif + 1) ;
                g_CANVAS.caseH = g_CANVAS.h ;

            // TAILLE DU CANVAS BG
                l_Ocanvas.width = g_CANVAS.w ;
                l_Ocanvas.height = g_CANVAS.h ;
                
                l_Ocanvas.style.width = g_CANVAS.w + "px" ;
                l_Ocanvas.style.height = g_CANVAS.h + "px" ;

                // g_CANVAS[g_Ncanvas].ctx.scale( g_winR, g_winR ) ;
                
            // TAILLE DU CANVAS
                l_OcanvasPion.width = g_CANVAS.w ;
                l_OcanvasPion.height = g_CANVAS.h ;
                
                l_OcanvasPion.style.width = g_CANVAS.w + "px" ;
                l_OcanvasPion.style.height = g_CANVAS.h + "px" ;



            //on laisse le temps la fenetre de descendre
            // avant de lancer l'animation graphique canvas n° g_Ncanvas
                waitAnimate( 'html, body' ) ;

            }
            else if (f_Caff == "html")
            {

    // AFFICHAGE HTML --------------------------------------------------------------
    // ------------------------ SUPPRIME POUR FACILITE LA MAINTENANCE ----------------------------------------------------

                // on efface la zone CANVAS
                $('#index-zone-chess-canvas').html( "" ) ;
                buildCanvas([1]) ;
                // code pour le cas où canvas ne serait pas supporté
                // on va pouvoir animer l'affichage en suivant le schéma
                //Affichage du bouton "animation"
                $('#index-zone-exec').show( ) ;
            }
            else
            {
    // AFFICHAGE ERREUR -----------------------------------------------------------
                // on efface la zone HTML
                $('#index-zone-html').html( "" ) ;
                // on efface la zone CANVAS
                $('#index-canvas-bg').html( "" ) ;
                $('#index-canvas-pion').html( "" ) ;
                // code pour le cas où canvas ne serait pas supporté
                // on va pouvoir animer l'affichage en suivant le schéma
                l_html = "Le type d'affichage demandé n'est pas reconnu. Veuillez choisir parmi les options proposées ou demander le développement/débuggage de cette méthode d'affichage." ;
                $('#index-zone-exec').show( ) ;
            }

        //});
    }





/* -------------------------------------- SWITCHAFF -------------------------------------- */
// Affiche le controleur de type d'affichage HTML ou CANVAS si pas en mode DEV
    function switchAff() 
    {
        
        var f_Bdev = "yes" ;
        var l_Bdevelo = $('#index-input-dev').val() ;
        var l_Bnormal = $('#index-input-normal').val() ;

        var l_BshowTabl = $('#index-input-show-table').val() ;
        var l_BshowJson = $('#index-input-show-json').val() ;
 

        if ( $('#index-input-dev').prop("checked") )
        { 
            f_Bdev = $('#index-input-dev').val() ;
        }
        else if ($('#index-input-normal').prop("checked"))
        { 
            f_Bdev = $('#index-input-normal').val() ; 
        }


        if ( $('#index-input-show-html').prop("checked") )
        { 
            f_Cshow = $('#index-input-show-html').val() ;
        }
        else if ($('#index-input-show-table').prop("checked"))
        { 
            f_Cshow = $('#index-input-show-table').val() ;
        }

        //console.log( $('#index-input-show').val() ) ;
        //console.log( $('#index-input-dev').val() ) ;

        if ( f_Bdev == l_Bdevelo )
        { 
            $('#index-form-aff').hide() ; 
            $('#index-form-show').show() ;
        }
        else if ( f_Bdev == l_Bnormal ) 
        {
            $('#index-form-aff').show() ; 
            $('#index-form-show').hide() ;
        }

    }

/* -------------------------------------- BUILDCANVAS -------------------------------------- */
    function buildCanvas(g_Tprop) 
    {
        var canvasHTML = '';
        // on parcourt la liste des resultats pour créer une zone de canvas par calcul
        g_Tprop.forEach(function(l_Ncanvas) {
            canvasHTML += '<div class="row">' ;
            canvasHTML +=   '<div class="col-md-12">' ;
            canvasHTML +=       '<div id="zone-sequence-propre-' + l_Ncanvas + '" style="display: none; margin-top:30px;"></div>';
            canvasHTML +=   '</div>' ;
            l_divHeight = g_CANVAS.h + 100 ;
            canvasHTML +=   '<div class="col-md-12" style="height:' + l_divHeight + 'px;">' ;
            canvasHTML +=       '<canvas id="index-canvas-bg-' + l_Ncanvas + '" style="width:1000px; height:200px; position:absolute; top:0px; left:0px; margin-top: 45px;"></canvas>' ;
            canvasHTML +=       '<canvas id="index-canvas-pion-' + l_Ncanvas + '" style="width:1000px; height:200px; position:absolute; top:0px; left:0px; margin-top: 45px;"></canvas>' ;
            canvasHTML +=   '</div>';
            canvasHTML += '</div>';
        });


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
/* -------------------------------------- WAITANIMATED -------------------------------------- */
function waitAnimate( Csection ) {
            if( $( Csection ).is( ':animated' ) ) {
                setTimeout(function() {
                    waitAnimate(Csection);
                }, 0 );
               // console.log('waitAnimate');
            }else {// code de dessin dans le canvas
                canvas_draw_damier() ;
                representation_graphique_canvas_1d( ) ;
            } 
    }

function waitFunction() {
            if( g_BcanvasTemoin == 0 ) {
                setTimeout(function() {
                    waitFunction( );
                }, 0 );
                // console.log('waitFunction');
            }else{
                g_Ncanvas++ ;
                if ( typeof(g_Tprop[g_Ncanvas]) !== 'undefined' )
                {
                    g_scrollY = window.scrollY ;
                    var l_newScroll = g_scrollY + g_scrollYpas ;
                    $('html, body').animate( {scrollTop: l_newScroll}, "swing" ) ;
                    parcourtResultToAffichage() ;
                }
                else {
                    // L'animation est terminée
                    // on stoppe le spinner
                    $('.has-spinner').buttonLoader('stop');
                }
            }
    }

/* ------------------------------------------------------------------------------------ */
/* -------------------------------------- CANVAS -------------------------------------- */
/* ------------------------------------------------------------------------------------ */

                                
/* -------------------------------------- REPRESENTATION GRAPHIQUE CANVAS 1D DU CALCUL -------------------------------------- */
    function representation_graphique_canvas_1d() 
    {
        // console.log("in representation : " + g_Ncanvas);
        canvas_draw_damier() ;
        sleep( g_NdureeAnimation / g_ALPA.resultat ) ;
        canvas_draw_pion() ;
 
        // si la sequence est encore exploitable on met à jour la position du pion et on relance le dessin
        if ( g_ALPA.sequence[ g_CANVAS[g_Ncanvas].progression ] != undefined )
        {   
            // animation du pion
            canvas_update_pion_position() ;
            requestAnimationFrame( representation_graphique_canvas_1d ) ;
            //representation_graphique_canvas_1d();
        }
        else 
        {
            //console.log("fin de l'animation");
            g_CANVAS[g_Ncanvas].progression = 1 ;
            g_CANVAS[g_Ncanvas].pion_pos = 0 ;
            // on laisse le temps à l'execution graphique de se terminer avant de relancer la fonction
            //

            waitFunction() ;
            
        }
        
    }

/* -------------------------------------- UPADTE POSITION PION -------------------------------------- */
    function canvas_update_pion_position()
    {
        if (g_CANVAS[g_Ncanvas].progression == 1 )
        {
            // console.log(g_CANVAS[g_Ncanvas].progression + ":" + g_CANVAS[g_Ncanvas].pion_pos + "->") ;
            g_CANVAS[g_Ncanvas].pion_pos = Number( g_ALPA.sequence[ 1 ].substr(3) ) ;
            // console.log(g_CANVAS[g_Ncanvas].pion_pos) ;
            g_CANVAS[g_Ncanvas].progression++ ;
        }
        else
        {
            // code le dessin dans le canvas
            if ( g_ALPA.sequence[ g_CANVAS[g_Ncanvas].progression ].substr(0, 3) == 'o++')
            {
                var l_outs = g_ALPA.sequence[ g_CANVAS[g_Ncanvas].progression ].substr(3) ;
            }
            else 
            {
                console.log("Pb lecture sequence, outil ?") ;
            }


            if ( g_ALPA.sequence[ g_CANVAS[g_Ncanvas].progression + 1 ].substr(0, 3) == 'm++' )
            {
                g_ALPA.sequence[ g_CANVAS[g_Ncanvas].progression + 1 ].substr(3) ;
            }
            else 
            {
                console.log("Pb lecture sequence, matiere ?") ;
            }
           
            // console.log(g_CANVAS[g_Ncanvas].progression + ":" + g_CANVAS[g_Ncanvas].pion_pos + "->") ;
            // on va chercher la fonction, dans l'objet mesFonctions, correspondant à l'outil dans la sequence de calcul
            g_CANVAS[g_Ncanvas].pion_pos =  mesFonctions[l_outs]( g_CANVAS[g_Ncanvas].pion_pos, g_ALPA.sequence[ g_CANVAS[g_Ncanvas].progression + 1 ].substr(3) ) ;
            // console.log(g_CANVAS[g_Ncanvas].pion_pos) ;
            g_CANVAS[g_Ncanvas].progression = g_CANVAS[g_Ncanvas].progression + 2 ; 

        }

    }


/* -------------------------------------- DRAW BACKGROUND DAMIER -------------------------------------- */
    function canvas_draw_damier() 
    {

        g_CANVAS[g_Ncanvas].ctx.font = '20px Times New Roman';
        
        
        // dessin du damier 1D
        for ( var l_hcoord = 0 ; l_hcoord <= g_ALPA.objectif + 2 ; l_hcoord++ )
        {
            if ( l_hcoord&1 )
            {
                l_color = '211,116,116' ;
            }
            else
            {
                l_color = '255,214,214' ;
            }

            g_CANVAS[g_Ncanvas].ctx.fillStyle = 'rgb(' + l_color + ')' ;
        // on dessine un rectangle damier
            g_CANVAS[g_Ncanvas].ctx.fillRect( l_hcoord * g_CANVAS.caseW, 0, g_CANVAS.caseW, g_CANVAS.caseH ) ;
        // on numérote les case 
            g_CANVAS[g_Ncanvas].ctx.fillStyle = 'Black';
            g_CANVAS[g_Ncanvas].ctx.fillText(l_hcoord , g_CANVAS.caseW * (l_hcoord + 0.3), 30);

        }
        g_CANVAS[g_Ncanvas].ctx.fillStyle = 'Black';
        g_CANVAS[g_Ncanvas].ctx.strokeRect( 0, 0, g_CANVAS.w, g_CANVAS.w ) ;
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
        var l_pionColor = 'rgb(0,0,0,' + (0.5 + g_CANVAS[g_Ncanvas].pion_pos * ( 1 / (2 *g_ALPA.objectif) )) + ')' ;
        if ( g_CANVAS[g_Ncanvas].pion_pos == 0 )
        { 
            l_pionColor = 'red' ; 
        }

        g_CANVAS[g_Ncanvas].ctxPion.fillStyle = l_pionColor ;
        g_CANVAS[g_Ncanvas].ctxPion.beginPath() ;
        g_CANVAS[g_Ncanvas].ctxPion.arc( (g_CANVAS.caseW / 2) + (g_CANVAS[g_Ncanvas].pion_pos * g_CANVAS.caseW), g_CANVAS.caseH / 2, l_rayon, 0, Math.PI * 2, false ) ;
        g_CANVAS[g_Ncanvas].ctxPion.fill() ;

        if ( g_CANVAS[g_Ncanvas].pion_pos == g_ALPA.resultat )
        {
            // on passe le temmoin de fin d'animation à 1 pour libérer la suite du traitement
            g_BcanvasTemoin = 1 ;
            //console.log("fin de l'animation du pion");
        }
    }




// Fin JQUERY DOCUMENT FONCTIONS

});


                        

                           
   