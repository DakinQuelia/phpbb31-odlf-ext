<?php
/*======================================================================*\
|| #################################################################### ||
|| # Classe Site par Dakin Quelia							 	      # ||
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2016 by Dakin Quelia (Daniel Chalsèche).			  # ||
|| # Ce fichier ne peut être redistribué sans ma permission.	      # ||
|| # --------------- CODE LIBRE MAIS A TELECHARGER ICI -------------- # ||
|| # http://www.danielchalseche.fr.cr/  							  # ||
|| #################################################################### ||
\*======================================================================*/
namespace dakinquelia\odlf\controller;

class main
{
	/**
	*	Variables globales
	**/
	protected $odlf_create_group;
	protected $odlf_create_forum;
	protected $template;
	protected $user;
	protected $auth;
	protected $db;
	protected $request;
	protected $helper;
	protected $phpbb_root_path;
	protected $phpEx;
	
	/**
	* 	Le constructeur
	**/
	public function __construct(\dakinquelia\odlf\core\odlf_create_group $odlf_create_group, \dakinquelia\odlf\core\odlf_create_forum $odlf_create_forum, \phpbb\template\template $template, \phpbb\user $user, \phpbb\auth\auth $auth, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, \phpbb\controller\helper $helper, $phpbb_root_path, $phpEx)
	{
		$this->odlf_create_group	= $odlf_create_group;
		$this->odlf_create_forum	= $odlf_create_forum;
		$this->template 			= $template;
		$this->user 				= $user;
		$this->auth 				= $auth;
		$this->db 					= $db;
		$this->request 				= $request;
		$this->config 				= $config;
		$this->helper 				= $helper;
		$this->phpbb_root_path 		= $phpbb_root_path;
		$this->phpEx 				= $phpEx;
	}
	
	public function handle_odlf()
	{
		include($this->phpbb_root_path . 'includes/functions_module.' . $this->phpEx);
		
		// On récupère le mode.
		$mode = $this->request->variable('mode', '');
		
		// On ajoute l'entrée dans la barre de navigation
		$this->template->assign_block_vars('navlinks', array(
			'U_VIEW_FORUM'	=> $this->helper->route('dakinquelia_odlf_controller', array('mode' => 'main')),
			'FORUM_NAME'	=> $this->user->lang['ODLF_HOME']),
		));
		
		switch($mode)
		{
			case 'create_group':
				$this->odlf_create_group->main();
			break;
			
			case 'create_forum':
				$this->odlf_create_forum->main();
			break;
			
			default:
				$this->odlf_main->main();
			break;
		}
	}
}