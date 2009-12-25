<?php

class Permissions {

	private $user_permissions = array ();
	private $group_permissions = array ();
	private $isadmin = FALSE;

	function __construct ()
	{
		$obj = &get_instance ();

		if ( $obj->site_sentry->is_logged_in () ) {
			$obj->load->model ( 'mpermissions' );
			$obj->load->helper ( 'permissions' );

			$this->permissions = $this->build_permissions ();
			$this->load_user_permissions ();
			$this->load_group_permissions ( get_user_group_id ( $obj->session->userdata ( AUTH_SESSION_ID ) ) );
			$this->isadmin = $obj->site_sentry->isadmin ();
		}
	}

	/**
	* Creates an array with the defined permissions
	*
	* @return array/bol
	*/

	function build_permissions ()
	{
		$obj = &get_instance ();
		$array = array ();

		$session = $obj->session->userdata ( 'all_permissions' );

		if ( $session !== FALSE ) {
			return $session;
		}

		$permissions = $obj->mpermissions->get_all_permissions ();
		foreach ( $permissions as $permission )
		{
			if ( $permission->parent_id != 0 ) {
				$array [ $permission->parent_id ] [] = $permission->ID;
			}
		}

		$userdata = array ( 'all_permissions' => $array );
		$obj->session->set_userdata ( $userdata );
		return $array;
	}

	/**
	* load_user_permissions
	* Loads up user permissions for
	* the userid passed in.
	*
	* @param Int $userid Userid to load up permissions for.
	* @return bol TRUE/FALSE
	*/
	
	function load_user_permissions ()
	{
		$obj = &get_instance ();
		$session = $obj->session->userdata ( 'user_permissions' );

		if ( $session !== FALSE ) {
			$this->user_permissions = $session;
			return;
		}

		$query = $obj->db->query ( "SELECT * FROM " . DBPREFIX . "added_permissions WHERE item_type = 'user' AND item_id = " . qstr ( $obj->session->userdata ( AUTH_SESSION_ID ) ) );

		$permissions = array ();
		foreach ( $query->result () as $row )
		{
			if ( ! in_array ( $row->area, $permissions ) ) {
				$permissions [] = $row->area;
			}
		}
		
		$userdata = array ( 'user_permissions' => $permissions );
		$obj->session->set_userdata ( $userdata );

		$this->user_permissions = $permissions;
	}
	
	/**
	* load_group_permissions
	* Loads up group permissions for
	* the groupid passed in.
	*
	* @param Int $userid Userid to load up permissions for.
	* @return bol TRUE/FALSE
	*/
	
	function load_group_permissions ( $groupid = 0 )
	{
		$obj = &get_instance ();

		if ( $groupid <= 0 || ! numeric ( $groupid ) ) {
			return FALSE;
		}
		
		$session = $obj->session->userdata ( 'group_permissions' );

		if ( $session !== FALSE ) {
			$this->group_permissions = $session;
			return;
		}

		$query = $obj->db->query ( "SELECT * FROM " . DBPREFIX . "added_permissions WHERE item_type = 'group' AND item_id = " . qstr ( $groupid ) );

		$permissions = array ();
		foreach ( $query->result () as $row )
		{
			if ( ! in_array ( $row->area, $permissions ) ) {
				$permissions [] = $row->area;
			}
		}
		
		$userdata = array ( 'group_permissions' => $permissions );
		$obj->session->set_userdata ( $userdata );

		$this->group_permissions = $permissions;
	}
	
	/**
	* checkPermissions
	*
	* @param int $user_id - the id of the user we're checking
	* @param array $areas array with the areas that needs to be
	* defined in the user or permissions, if it's not defined,
	* deny access
	* @param $redirect, should we redirect id the user has no
	* permission over this area?
	*
	* @return bol True/FALSE
	*/
	
	function checkPermissions ( $areas = array (), $redirect = FALSE )
	{
		$obj = &get_instance ();
		$obj->load->helper ( 'users' );

		foreach ( $areas as $area )
		{
			//there's enough for only one to come back FALSE and we're not allowed			
			if ( numeric ( $area ) )
			{//does the area exists?..is it also numeric?
				if ( $this->isadmin ) {
					//ups, hide everything, the boss is here
					return TRUE;
				}
				else {
					if ( ! $this->checkAreas ( $area ) ) {
						if ( $redirect ) {
							redirect ( 'generic_messages/no_permission', 'location' );
						}
						return FALSE;
					}
				}
			}
			else {
				if ( $redirect ) {
					redirect ( 'generic_messages/no_permission', 'location' );
				}
				return FALSE;
			}
		}
		//we made it, let's play
		return TRUE;
	}
	
	/**
	* checkAreas
	*
	* @param int $area the id of the restricted area
	* @param int $user_id - the id of the user we're checking
	*
	* @return bol True/FALSE
	*/
	
	function checkAreas ( $area )
	{
		$obj = &get_instance ();
		
		//no area? this should not run but...just make sure
		if ( is_null ( $area ) ) {
			return FALSE;
		}

		//if no group permissions or user permissions
		//are defined return FALSE
		if ( empty ( $this->user_permissions ) && empty ( $this->group_permissions ) ) {
			return FALSE;
		}

		//if the user or the group has no permissions
		//defined regarding this area return FALSE
		if ( is_parent_permission ( $area ) ) {
			if ( in_array ( $area, array_values ( $this->user_permissions ) ) || in_array ( $area, array_values ( $this->group_permissions ) ) ) {
				return TRUE;
			}
		}
		else {
			if 	(
					in_array ( $area, array_values ( $this->user_permissions ) ) ||
					in_array ( $area, array_values ( $this->group_permissions ) ) ||
					in_array ( get_permission_parent_id ( $area ), array_values ( $this->user_permissions ) ) ||
					in_array ( get_permission_parent_id ( $area ), array_values ( $this->group_permissions ) )
				) {
				return TRUE;
			}
		}

		return FALSE;
	}
}

//END