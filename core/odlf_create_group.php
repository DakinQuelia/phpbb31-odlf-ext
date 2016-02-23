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
namespace dakinquelia\odlf\core;

class odlf_create_group
{	
	/**
	*	Variables globales
	**/
	public $u_action;
	protected $auth;
	protected $template;
	protected $user;
	protected $db;
	protected $request;
	protected $helper;
	protected $phpEx;
	protected $phpbb_root_path;
	
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
	* 	On récupère l'id du groupe
	**/
	public function get_group_id($group_name)
	{
		$sql = 'SELECT group_id FROM ' . GROUPS_TABLE . "
				WHERE group_name = '" . $this->db->sql_escape($group_name) . "'";
		$result = $this>db->sql_query($sql);
		$group_id = (int) $this->db->sql_fetchfield('group_id');
		$this->db->sql_freeresult($result); 

		return $group_id;
	}

	/**
	* 	On crée le groupe.
	**/
	public function create_groupe($group, $desc)
	{
		$group_id = 0;
		$group_type = 0;
		$group_name = $group;
		$group_desc = $desc;
		$user_id = $user->data['user_id'];

		// Attributs de groupe
		$group_attributes = array(
			'group_colour' 			=> '000000',
			'group_rank' 			=> 0,
			'group_avatar' 			=> 0,
			'group_avatar_type' 	=> 0,
			'group_avatar_width'	=> 0,
			'group_avatar_height' 	=> 0,
			'group_legend' 			=> 0,
			'group_receive_pm' 		=> 0,
		);
				
		// On inclut le fichier de fonctions si la fonction n'existe pas.
		if (!function_exists('group_create'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->phpEx);
		}
		
		// On crée le groupe.
		group_create($group_id, $group_type, $group_name, $group_desc, $group_attributes);
		
		// On récupère l'id du groupe.
		$group_id = $this->get_group_id($group_name);
		
		// On ajoute l'utilisateur en tant que chef de groupe.
		group_user_add($group_id, $user_id, false, false, false, true, false);
			
		// Le groupe est masqu//7//7é.
		$sql = 'UPDATE ' . GROUPS_TABLE . ' SET group_type = ' . GROUP_HIDDEN . '
				WHERE group_id = ' . (int) $group_id;
		$this->db->sql_query($sql);
	}
	
	/**
	*	On supprime le groupe.
	**/
	public function delete_group($group)
	{
		// On récupère l'id du groupe.
		$group_id = $this->get_group_id($group);
		
		if ($group_id)
		{
			// On inclut le fichier de fonctions si la fonction n'existe pas.
			if (!function_exists('group_delete'))
			{
				include($this->phpbb_root_path . 'includes/functions_user.' . $this->phpEx);
			}
			
			// On supprime le groupe de l'utilisateur.
			group_delete($group_id, $group);
		}
	}
	
	/**
	*	Ecran principal de notre extension.
	**/
	function main()
	{
		// On crée une clé de formulaire.
		$form_key = 'create_group';
		add_form_key($form_key);
		
		// On vérifie que l'utilisateur a la permission de créer un groupe.
		// Si ce n'est pas le cas, alors on affiche un message d'erreur.
		if (!$this->auth->acl_get('u_odlf_create_group'))
        {
           trigger_error($this->user->lang('ODLF_CANNOT_ACCESS'));
        }
		
		// Les valeurs à récupérer
		$group_name = $this->request->variable('group_name', '', true);
		$group_desc = $this->request->variable('group_desc', '', true);
		$submit = $this->request->is_set_post('submit');
			
		// On envoie le formulaire.
		if ($submit)
		{
			// Vérification du formulaire.
			if (!check_form_key($form_key))
			{
				trigger_error('FORM_INVALID');
			}

			// Si aucune erreur, alors go !
			$this->create_groupe($group_name, $group_desc);
				
			// On affiche un message de succès.
			$message = $this->user->lang['ODLF_GROUP_SUCCESS'] . '<br /><br /><a href="' . $this->helper->route('dakinquelia_odlf_controller', array('mode' => 'create_group')) . '">&laquo; ' . $this->user->lang['BACK_TO_PREV'] . '</a>';
			trigger_error($message);
				
			$this->template->assign_vars(array(
				'U_ACTION'		=> $this->u_action,
			));
		}
			
		// On assigne les valeurs des variables.
		$this->template->assign_vars(array(
			'GROUP_NAME'		=> $group_name,
			'GROUPE_DESC'		=> $group_desc
		));
		
		// On ajoute l'entrée dans la barre de navigation
		$this->template->assign_block_vars('navlinks', array(
			'U_VIEW_FORUM'		=> $this->helper->route('dakinquelia_odlf_controller', array('mode' => 'create_group')),
            'FORUM_NAME'        => $this->user->lang('ODLF_HOME'),
        ));

		// On génère HTML.
		return $this->helper->render('olf/odlf_create_group.html', $this->user->lang('ODLF_CG_TITLE'));
	}
}