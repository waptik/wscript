<?php

class Mpermissions extends Model {
	
	/**================================================================================================================
	 *	PERMISSIONS START
	 *=================================================================================================================*/
	
	/**
	 * Deletes a permission
	 *
	 * @param int $ID
	 * @return bol
	 */
	
	function delete_permission ( $ID )
	{
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'permissions 
			WHERE 
				parent_id = ' . qstr ( $ID ) 
		);
		
		if ( $query->num_rows () > 0 ) {
			foreach ( $query->result () as $row ) {
				$this->delete_permission ( $row->ID );
			}
		}

		return ( $this->db->query 
		( 
			"DELETE FROM 
				" . DBPREFIX . "permissions 
			WHERE 
				`ID` = " . qstr ( $ID ) 
		) ) ? TRUE : FALSE;
	}
	
	/**
	 * get_child_permissions_array
	 *
	 * Used to compare what the admin submits for a group
	 * or user. If he selected all the labels there's no need
	 * to create an entry for each and every one of them but
	 * instead, we enter the parrent. To determine that, we will
	 * compared the _POST array with what's in the permissions's
	 * table.
	 *
	 * @param int $parent - the parent id
	 * @return array
	 */
	
	function get_child_permissions_array ( $parent )
	{
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'permissions
			WHERE parent_id = ' . qstr ( $parent )
		);

		$array = array ();

		if ( $query->num_rows () > 0 )
		{
			foreach ( $query->result () as $row )
			{
				$array [] = $row->ID; 
			}
		}

		return ( count ( $array ) > 0 ) ? $array : FALSE;
	}
	
	function _get_child_permissions_array ( $parent )
	{
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'permissions
			WHERE parent_id = ' . qstr ( $parent )
		);

		$array = array ();

		if ( $query->num_rows () > 0 )
		{
			foreach (  $query->result () as $row  )
			{
				$array [] = $row;
			}
		}

		return ( count ( $array ) > 0 ) ? $array : FALSE;
	}
	
	/**
	 * Returns the permission details
	 *
	 * @param int $id
	 * @return obj
	 */
	
	function get_permission ( $id )
	{
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'permissions 
			WHERE 
				ID = ' . qstr ( $id ) 
		);
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}
	
	/**
	 * Deletes all permissions assigned to a specific group
	 * or use
	 *
	 * @param int $id - group id
	 * @return bol
	 */
	
	function delete_all_permissions ( $id, $type )
	{
		return (  $this->db->query 
						(
							'DELETE  
							FROM 
								' . DBPREFIX . 'added_permissions 
							WHERE 
								item_id = ' . qstr ( $id ) . '
							AND item_type = ' . qstr ( $type )
						)
			) ? TRUE : FALSE;
	}
	
	/**
	 * Inserts a permission
	 *
	 * @param int $group - user id
	 * @param int $area - the area
	 * @param int $item_type - group or user
	 * @return bol
	 */
	
	function add_permission ( $item_id, $area, $item_type )
	{
		$data = array
		(
			'item_id'	=>	$item_id,
			'item_type'	=>	$item_type,
			'area'		=>	$area
		);
		
		return ( $this->db->insert ( DBPREFIX . 'added_permissions', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * get_permission_parent_id
	 *
	 * Returns the parent id of a given permission
	 *
	 * @param	perm id
	 * @access	private
	 * @return 	int
	 */
	
	function get_permission_parent_id ( $id )
	{
		$query = $this->db->query ( 'SELECT parent_id from ' . DBPREFIX . 'permissions WHERE ID = ' . qstr ( $id ) );
		if ( $query->num_rows () == 1 ) {
			$row = $query->row ();
			return $row->parent_id;
		}
		return FALSE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * check_permission_record
	 *
	 * Returns true or false if a permission was set for the
	 * following group or user
	 *
	 * @param	$area - area id
	 * @param	$type - group/user
	 * @param	$id - id of the user or group
	 * @access	private
	 * @return 	bol
	 */
	
	function check_permission_record ( $area, $type, $id )
	{
		switch ( $type )
		{
			case 'user':
				$query = $this->db->query ( 'SELECT ID FROM ' . DBPREFIX . 'added_permissions WHERE item_id = ' . qstr ( $id ) . ' AND area = ' . qstr ( $area ) . ' AND item_type = ' . qstr ( $type ) );
				
				return ( $query->num_rows () == 1 ) ? TRUE : FALSE;
			break;
		
			case 'group':
				$query = $this->db->query ( 'SELECT ID FROM ' . DBPREFIX . 'added_permissions WHERE item_id = ' . qstr ( $id ) . ' AND area = ' . qstr ( $area ) . ' AND item_type = ' . qstr ( $type ) );
				
				return ( $query->num_rows () == 1  ) ? TRUE : FALSE;
			break;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * check_parent_permission_record
	 *
	 * Returns true or false if a parent permission was set for the
	 * following group or user
	 *
	 * @param	$area - area id
	 * @param	$type - group/user
	 * @param	$id - id of the user or group
	 * @access	private
	 * @return 	bol
	 */
	
	function check_parent_permission_record ( $area, $type, $id )
	{
		switch ( $type )
		{
			case 'user':
				$query = $this->db->query ( 'SELECT ID FROM ' . DBPREFIX . 'added_permissions WHERE item_id = ' . qstr ( $id ) . ' AND area = ' . qstr ( $this->get_permission_parent_id ( $area ) ) . ' AND item_type = ' . qstr ( $type ) );
				
				return ( $query->num_rows () == 1 ) ? TRUE : FALSE;
			break;
		
			case 'group':
				$query = $this->db->query ( 'SELECT ID FROM ' . DBPREFIX . 'added_permissions WHERE item_id = ' . qstr ( $id ) . ' AND area = ' . qstr ( $this->get_permission_parent_id ( $area ) ) . ' AND item_type = ' . qstr ( $type ) );
				
				return ( $query->num_rows () == 1  ) ? TRUE : FALSE;
			break;
		}
	}
	
	/**
	 * Tryes to determine if a given permission is parent or not
	 *
	 * @param int $id
	 * @return bol
	 */
	
	function is_parent_permission ( $id )
	{
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'permissions 
			WHERE 
				ID = ' . qstr ( $id ) 
		);
		if ( $query->num_rows () == 1 ) {
			$row = $query->row ();
			if ( $row->parent_id == 0 ) {
				return TRUE;
			}
			return FALSE;
		}
		return FALSE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * get_parent_permissions
	 *
	 * Returns the current parent permissions
	 *
	 * @param	none
	 * @access	private
	 * @return 	obj
	 */
	
	function get_parent_permissions ( $cache = TRUE )
	{
		return $this->db->query
		( 	'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'permissions
			WHERE
				parent_id = 0 
			ORDER BY 
				ID DESC'
		);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * get_childs_permissions
	 *
	 * Returns the current permissions
	 *
	 * @param	$parent_id - the parent ID
	 * @access	private
	 * @return 	obj
	 */
	
	function get_childs_permissions ( $parent_id )
	{
		return $this->db->query
		( 	'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'permissions
			WHERE
				parent_id = ' . qstr ( $parent_id ) . ' 
			ORDER BY 
				ID ASC'
		);
	}
	
	/**
	 * Adds new permissions or permission rules to the database
	 *
	 * @param $parent - 	if the parent is 0 we have a new permission
	 * 			otherwise add a child to an existing permission
	 * @return int/bol
	 */
	
	function add_new_permission ( $label, $parent = 0 )
	{
		$data = array
		(
				'label'		=>	$label,
				'parent_id'	=>	$parent,
				'editable'	=>	1
		);
		return ( $this->db->insert ( DBPREFIX . 'permissions', escape_arr ( $data ) ) ) ? $this->db->call_function ( 'insert_id' ) : FALSE;
	}

	/**
	 * Updates a permission
	 *
	 * @param $label - permission label
	 * @param $parent - 	if the parent is 0 we have a new permission
	 * 			otherwise add a child to an existing permission
	 * @return int/bol
	 */
	
	function update_permission ( $id, $label )
	{
		$data = array
		(
				'label'		=>	$label
		);
		$this->db->where ( 'ID', $id );
		return ( $this->db->update ( DBPREFIX . 'permissions', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}
	
	function get_all_permissions ()
	{
		$query = $this->db->query 
		(
			'SELECT 
				ID, parent_id 
			FROM 
				' . DBPREFIX . 'permissions' 
		);
		return ( $query->num_rows () > 0 ) ? $query->result () : FALSE;
	}

	/**================================================================================================================
	 *	PERMISSIONS END
	 *=================================================================================================================*/
	
}
//END