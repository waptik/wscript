<?php
class Musergroups extends Model {

	/**================================================================================================================
	 *	GROUPS START
	 *=================================================================================================================*/

	/**
	 * Returns the group details
	 *
	 * @param int $id
	 * @return obj
	 */

	function get_group ( $id )
	{
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'groups 
			WHERE 
				ID = ' . qstr ( $id ) 
		);
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}

	/**
	 * Returns the available groups
	 *
	 * @param int $id
	 * @return obj
	 */

	function get_groups ()
	{
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'groups 
			ORDER BY ID DESC'
		);
		return ( $query->num_rows () > 0 ) ? $query->result () : FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * get_user_group
	 *
	 * Returns the title of the group that a user belongs to
	 *
	 * @param	user id
	 * @access	private
	 * @return 	string
	 */

	function get_group_members ( $id )
	{
		$query = $this->db->query ( 'SELECT COUNT(ID) AS nr FROM ' . DBPREFIX . 'users WHERE Level_access = ' . qstr ( $id ) );
			
		if ( $query->num_rows () == 1 )
		{
			$row = $query->row ();
			return $row->nr;
		}
		return 0;
	}

	// ------------------------------------------------------------------------
	
	/**
	 * insert_group
	 *
	 * Adds a new group to the database
	 *
	 * @param	$title - group title
	 * @param	$description - group description
	 * @param	$login_redirect - default group redirect on login
	 * @param	$logout_redirect - default group redirect on logout
	 * @access	private
	 * @return 	bol
	 */

	function insert_group ( $title, $description, $login_redirect, $logout_redirect )
	{
		$data = array
		(
			'`title`'		=> $title,
			'`description`'		=> $description,
			'`login_redirect`'	=> $login_redirect,
			'`logout_redirect`'	=> $logout_redirect
		);

		return ( $this->db->insert ( DBPREFIX . 'groups', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * edit_group
	 *
	 * Adds a new group to the database
	 *
	 * @param	$title - group title
	 * @param	$description - group description
	 * @param	$login_redirect - default group redirect on login
	 * @param	$logout_redirect - default group redirect on logout
	 * @access	private
	 * @return 	bol
	 */

	function edit_group ( $id, $title, $description, $login_redirect, $logout_redirect )
	{
		$data = array
		(
			'`title`'		=> $title,
			'`description`'		=> $description,
			'`login_redirect`'	=> $login_redirect,
			'`logout_redirect`'	=> $logout_redirect
		);
		$this->db->where ( 'ID', $id );
		return ( $this->db->update ( DBPREFIX . 'groups', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}

	/**
	 * Deletes a group
	 *
	 * @param int $ID
	 * @return bol
	 */

	function delete_group ( $ID )
	{
		return ( $this->db->query 
		( 
			"DELETE FROM 
				" . DBPREFIX . "groups 
			WHERE 
				`ID` = " . qstr ( $ID ) 
		) ) ? TRUE : FALSE;
	}

	/**
	 * Deletes the members that belong to a given group
	 *
	 * @param int $gr_id - group id
	 * @return bol
	 */

	function delete_members_from_group ( $gr_id )
	{
		return ( $this->db->query 
		( 
			"DELETE FROM 
				" . DBPREFIX . "users 
			WHERE 
				`Level_access` = " . qstr ( $gr_id ) 
		) ) ? TRUE : FALSE;
	}

	/**
	 * Returns the login page that belongs to the group in which a member
	 * is part, if any
	 *
	 * @return string/bol
	 */

	function get_login_group_redirect ()
	{
		$query = $this->db->query 
		(
			'SELECT 
				`login_redirect` 
			FROM 
				' . DBPREFIX . 'groups 
			WHERE 
				`ID` = ' . qstr ( get_mem_info ( 'Level_access' ) )
		);
		if ( $query->num_rows () == 1 ) {
			$row = $query->row ();
			return $row->login_redirect;
		}
		return FALSE;	
	}

	/**
	 * Returns the logout page that belongs to the group in which a member
	 * is part, if any
	 *
	 * @return string/bol
	 */

	function get_logout_group_redirect ()
	{
		$query = $this->db->query 
		(
			'SELECT 
				`logout_redirect` 
			FROM 
				' . DBPREFIX . 'groups 
			WHERE 
				`ID` = ' . qstr ( get_mem_info ( 'Level_access' ) )
		);
		if ( $query->num_rows () == 1 ) {
			$row = $query->row ();
			return $row->logout_redirect;
		}
		return FALSE;	
	}

	/**================================================================================================================
	 *	GROUPS END
	 *=================================================================================================================*/

}
//END