<?php
/*======================================================================*\
|| #################################################################### ||
|| # ODLF Create Group Core par Dakin Quelia						  # ||
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2016 by Dakin Quelia (Daniel Chalsèche).			  # ||
|| # Ce fichier ne peut être redistribué sans ma permission.	      # ||
|| # --------------- CODE LIBRE MAIS A TELECHARGER ICI -------------- # ||
|| # http://www.danielchalseche.fr.cr/  							  # ||
|| #################################################################### ||
\*======================================================================*/
namespace dakinquelia\odlf\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Evenement d'écoute
*/
class listener implements EventSubscriberInterface
{
	/**
	*	Variables globales
	**/
	protected $user;
	protected $template;
	protected $db;
	protected $auth;
	protected $helper;
	protected $request;
	protected $phpbb_root_path;
	protected $phpEx;
	
	/**
	*	Le constructeur
	**/
	public function __construct(\phpbb\user $user, \phpbb\template\template $template, \phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, \phpbb\controller\helper $helper, \phpbb\request\request $request, $phpbb_root_path, $phpEx)
	{
		$this->user					= $user;
		$this->template				= $template;
		$this->db					= $db;
		$this->auth 				= $auth;
		$this->helper 				= $helper;
		$this->request 				= $request;
		$this->phpbb_root_path 		= $phpbb_root_path;
		$this->phpEx 				= $phpEx;
	}
	
	/**
	*	On appelle les évènements
	**/
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'					=> 'load_language_on_setup',
			'core.permissions'					=> 'permissions'
		);
	}
	
	/**
	*	On charge le fichier de langue.
	**/
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'dakinquelia/odlf',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}
	
	/**
	*	On définit les permissions.
	**/
	public function permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions += array(
			'u_odlf_create_group'		=> array(
				'lang'		=> 'ACL_U_ODLF_CREATE_GROUP',
				'cat'		=> 'odlf'
			),
			'u_odlf_create_forum'		=> array(
				'lang'		=> 'ACL_U_ODLF_CREATE_FORUM',
				'cat'		=> 'odlf'
			),
		);
		$event['permissions'] = $permissions;
		$categories['odlf'] = 'ACL_CAT_ODLF';
		$event['categories'] = array_merge($event['categories'], $categories);
	}
}
