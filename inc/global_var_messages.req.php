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
$messagesIncludeJeton = true ;
                               
// à mettre dans un objet dédié XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXxxx
// on defini la phrase a afficher en cas de mauvaise utilisation
$tErrMess = array();
$tErrMess["api"] = '<br>
					<br>---
					<p> Salut toi !<br/><br/> Moi (API IAMJE) ? 
					<br/> ...
					<br/> Je suis juste du code dédié à l\'IA ... je ne peux  pas faire grand chose quand on m\'appelle directement :)
					<br/> Une erreur s\'est produite,<br/>Essayez de corriger vos paramètres GET et recommencez, svp ;) 
					<br/>
					<br/>---
					<br/><br/>
					<br/><a href="' . $BFUNC->g_url . '" title="Retour au bercail"> Retour </a>
					</p>';
$tErrMess["form"] = '<br>
					<br>---
					<p>Bonjour cher visiteur, <br/><br/>Cette application est en développement permanent...
					<br/> ...
					<br/> Une erreur innatendue s\'est produite,<br/>Essayez de corriger vos paramètres et recommencez, svp :)
					<br/>
					<br/>---
					<br/><br/>
					<br/><a href="' . $BFUNC->g_url . '" title="Retour au bercail"> Retour </a>
					</p>';



