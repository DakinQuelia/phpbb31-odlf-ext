<?php
/*======================================================================*\
|| #################################################################### ||
|| # ODLF Main Core par Dakin Quelia							 	  # ||
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2016 by Dakin Quelia (Daniel Chalsèche).			  # ||
|| # Ce fichier ne peut être redistribué sans ma permission.	      # ||
|| # --------------- CODE LIBRE MAIS A TELECHARGER ICI -------------- # ||
|| # http://www.danielchalseche.fr.cr/  							  # ||
|| #################################################################### ||
\*======================================================================*/
namespace dakinquelia\odlf\core;

class odlf_main
{	
	/**
	*	Variables globales
	**/
	protected $auth;
	protected $template;
	protected $user;
	protected $db;
	protected $request;
	protected $helper;
	protected $phpEx;
	protected $phpbb_root_path;
	
	var $u_action;
	
	public function __construct(\phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, \phpbb\controller\helper $helper, $phpEx, $phpbb_root_path)
	{
		$this->auth					= $auth;
		$this->template 			= $template;
		$this->user 				= $user;
		$this->db 					= $db;
		$this->request 				= $request;
		$this->helper 				= $helper;
		$this->phpEx 				= $phpEx;
		$this->phpbb_root_path 		= $phpbb_root_path;
	}
	
	/**
	*	Ecran principal de notre extension
	**/
	function main()
	{
		// On génère HTML.
		return $this->helper->render('olf/odlf_main.html', $this->user->lang('ODLF_MAIN_TITLE'));
	}
}