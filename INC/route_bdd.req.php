<?php
//BDD DECLARATION + APPEL BDD CLASS------------------------  BDD + CLASS
  require( $g_page_arbo . FOLD_CLASS . 'OBdd_connexion_local.req.php' ) ;
  require( $g_page_arbo . FOLD_CLASS . 'OBdd_exe.req.php' ) ;
  $BDD = new OBdd( 'iamje-1' ) ;
 
