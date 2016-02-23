<?php
/*======================================================================*\
|| #################################################################### ||
|| # ODLF Create Forum Core par Dakin Quelia						  # ||
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2016 by Dakin Quelia (Daniel Chalsèche).			  # ||
|| # Ce fichier ne peut être redistribué sans ma permission.	      # ||
|| # --------------- CODE LIBRE MAIS A TELECHARGER ICI -------------- # ||
|| # http://www.danielchalseche.fr.cr/  							  # ||
|| #################################################################### ||
\*======================================================================*/
namespace dakinquelia\odlf\core;

class odlf_create_forum
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
	
	/**
	*	Le constructeur
	**/
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
	* 	On crée un forum.
	* 	Source : http://www.phpbb.com/community/viewtopic.php?f=71&t=758985
	**/
	public function create_forum($forum_name, $forum_perm_from = false)
	{
	   $forum_data = array(
		  'parent_id'              		=> 0,
		  'left_id'               		=> 0,
		  'right_id'               		=> 0,
		  'forum_parents'           	=> '',
		  'forum_name'            		=> $forum_name,
		  'forum_desc'           		=> $forum_desc,
		  'forum_desc_bitfield'     	=> '',
		  'forum_desc_options'      	=> 7,
		  'forum_desc_uid'         		=> '',
		  'forum_link'            		=> '',
		  'forum_password'         		=> '',
		  'forum_style'            		=> 0,
		  'forum_image'            		=> '',
		  'forum_rules'            		=> '',
		  'forum_rules_link'        	=> '',
		  'forum_rules_bitfield'    	=> '',
		  'forum_rules_options'    	 	=> 7,
		  'forum_rules_uid'         	=> '',
		  'forum_topics_per_page'  		=> 0,
		  'forum_type'            		=> 1,
		  'forum_status'            	=> 0,
		  'forum_posts'            		=> 0,
		  'forum_topics'           	 	=> 0,
		  'forum_topics_real'     		=> 0,
		  'forum_last_post_id'      	=> 0,
		  'forum_last_poster_id'   		=> 0,
		  'forum_last_post_subject'   	=> '',
		  'forum_last_post_time'      	=> 0,
		  'forum_last_poster_name'   	=> '',
		  'forum_last_poster_colour'   	=> '',
		  'forum_flags'            		=> 32,
		  'display_on_index'        	=> false,
		  'enable_indexing'         	=> true,
		  'enable_icons'            	=> false,
		  'enable_prune'            	=> false,
		  'prune_next'            		=> 0,
		  'prune_days'            		=> 7,
		  'prune_viewed'            	=> 7,
		  'prune_freq'           		=> 1,
	   );

	   if (!class_exists('acp_forums'))
	   {
		  include($this->phpbb_root_path . 'includes/acp/acp_forums.' . $this->phpEx);
	   }

	   $forums_admin = new acp_forums();
	   $forums_admin->update_forum_data($forum_data);

	   if ($forum_perm_from)
	   {
		  global $auth;

		  $auth->acl_clear_prefetch();

		  $users_sql_ary = $groups_sql_ary = array();

		  // Copy permisisons from/to the acl users table (only forum_id gets changed)
		  $sql = 'SELECT user_id, auth_option_id, auth_role_id, auth_setting
				FROM ' . ACL_USERS_TABLE . '
				WHERE forum_id = ' . $forum_perm_from;
		  $result = $db->sql_query($sql);
		  while($row = $db->sql_fetchrow($result))
		  {
			 $users_sql_ary[] = array(
				'user_id'        	=> (int) $row['user_id'],
				'forum_id'         	=> (int) $forum_data['forum_id'],
				'auth_option_id'   	=> (int) $row['auth_option_id'],
				'auth_role_id'      => (int) $row['auth_role_id'],
				'auth_setting'      => (int) $row['auth_setting'],
			 );
		  }
		  $db->sql_freeresult($result);

		  // Copy permisisons from/to the acl groups table (only forum_id gets changed)
		  $sql = 'SELECT group_id, auth_option_id, auth_role_id, auth_setting
				FROM ' . ACL_GROUPS_TABLE . '
				WHERE forum_id = ' . $forum_perm_from;
		  $result = $db->sql_query($sql);
		  while($row = $db->sql_fetchrow($result))
		  {
			 $groups_sql_ary[] = array(
				'group_id'         => (int) $row['group_id'],
				'forum_id'         => (int) $forum_data['forum_id'],
				'auth_option_id'   => (int) $row['auth_option_id'],
				'auth_role_id'     => (int) $row['auth_role_id'],
				'auth_setting'     => (int) $row['auth_setting']
			 );
		  }
		  $db->sql_freeresult($result);

		  // Now insert the data
		  $db->sql_multi_insert(ACL_USERS_TABLE, $users_sql_ary);
		  $db->sql_multi_insert(ACL_GROUPS_TABLE, $groups_sql_ary);
		  cache_moderators();
	   }
	}
}