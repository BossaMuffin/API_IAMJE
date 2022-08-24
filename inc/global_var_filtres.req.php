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
$filtresIncludeJeton = true ;

// à mettre dans un objet dédié XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxx

// on defini notre set de filtres

$tFiltre = array() ;

$tFiltre["ia"] = isset( $_GET["ia"] ) && ! empty( $_GET["ia"] ) && in_array( $_GET["ia"], $tIA, true ) ;

$tFiltre["mode"] = isset( $_GET["mode"] ) && ! empty( $_GET["mode"] ) && in_array( $_GET["mode"], $tMode, true ) ;

$tFiltre["matieres"] = isset( $_GET["mat"] ) && ! empty( $_GET["mat"] )  && strlen( $_GET["mat"] ) <= 100 ;

$class_methods = get_class_methods('OListeFunctions') ;
$tFiltre["outils"] = isset( $_GET["outs"] ) && ! empty( $_GET["outs"] ) && in_array( $_GET["outs"], $class_methods, true ) ;

$tFiltre["objectifs"] = isset( $_GET["obj"] ) && ! empty( $_GET["obj"] ) && strlen( $_GET["obj"] ) <= 100 ;

$tFiltre["distance"] = isset( $_GET["dist"] ) && ! empty( $_GET["dist"] ) && is_numeric( $_GET["delais"] ) ;

$tFiltre["delais"] = isset( $_GET["delais"] ) && ! empty( $_GET["delais"] ) && is_numeric( $_GET["delais"] ) ;

$tFiltre["precision"] = isset( $_GET["ratio"] ) && ! empty( $_GET["ratio"] ) && is_numeric( $_GET["ratio"] ) ;

