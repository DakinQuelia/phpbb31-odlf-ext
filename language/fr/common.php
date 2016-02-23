<?php
/*======================================================================*\
|| #################################################################### ||
|| # ODLF par Dakin Quelia						  					  # ||
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2016 by Dakin Quelia (Daniel Chalsèche).			  # ||
|| # Ce fichier ne peut être redistribué sans ma permission.	      # ||
|| # --------------- CODE LIBRE MAIS A TELECHARGER ICI -------------- # ||
|| # http://www.danielchalseche.fr.cr/  							  # ||
|| #################################################################### ||
\*======================================================================*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	// Global
	'ODLF_GROUP_SUCCESS'					=> 'Le groupe a été créé avec succès.',
	'ODLF_CANNOT_ACCESS'					=> 'Vous ne pouvez pas accéder à cette page.',
	
	// Page principale
	'ODLF_MAIN_TITLE'						=> 'Informations',
	'ODLF_MAIN_TEXT'						=> 'Ceci est l’écran d’accueil de notre panneau de contrôle de gestion.',
	'ODLF_CG_TITLE'							=> 'Créer mon groupe',
	'ODLF_CG_TEXT'							=> '',
	'ODLF_CF_TITLE'							=> 'Créer mon forum',
	'ODLF_CF_TEXT'							=> '',
	
	// Panneau de contrôle
	'UCP_ODLF_TITLE'						=> 'Panneau de contrôle',
	
	
	// Permissions
	'ACL_U_ODLF_CREATE_FORUM'				=> 'Peut créer un forum',
	'ACL_U_ODLF_CREATE_GROUP'				=> 'Peut créer un groupe',
));
