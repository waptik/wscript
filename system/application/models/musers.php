<?php

class Musers extends Model {
	
	/**================================================================================================================
	 *	USERS START
	 *=================================================================================================================*/

	/**
	 * Returns the user's details based on ID
	 *
	 * @param int $id
	 * @return obj
	 */

	function get_member_by_id ( $id )
	{
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'users 
			WHERE 
				ID = ' . qstr ( $id ) 
		);		
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}

	function get_member_advanced ( $id)
	{
		$query = $this->db->query 
		(
			'SELECT
				u.ID,
				u.date_registered,
				u.Username,
				COUNT(w.ID) as walls_nr,
				SUM(w.rating)/COUNT(v.visitor_ip) as user_rating,
				SUM(w.downloads) as user_downloads,
				SUM(w.hits) as user_hits,
				COUNT(v.visitor_ip) AS user_votes
			FROM
				' . DBPREFIX . 'users u
			LEFT JOIN
				' . DBPREFIX . 'wallpapers w
			ON
				(w.user_id = u.ID)
			LEFT JOIN
				' . DBPREFIX . 'votes v
			ON
				(v.item_id = w.ID)
			WHERE
				w.parent_id = 0
			AND
				w.active = 1 
			AND
				u.ID = ' . qstr ( $id ) . '
			GROUP BY u.ID' 
		);
	
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}

	/**
	 * Performs the search based on admin's input
	 *
	 * @return obj/bol
	 */

	function get_user_search_results ( $user = FALSE, $email = FALSE )
	{
		$query = FALSE;
		if ( $user != FALSE && $email == FALSE ) {
			if ( ! empty ( $user ) ) {
				$query = $this->db->getwhere ( DBPREFIX . '`users`', array ( 'Username' => $user ) );
			}
		}
		
		if ( $email != FALSE && $user == FALSE ) {
			if ( ! empty ( $email ) ) {
				$query = $this->db->getwhere ( DBPREFIX . '`users`', array ( 'Email' => $email ) );
			}
		}
		
		if ( $email != FALSE && $user != FALSE ) {
			if ( ! empty ( $email ) ) {
				$query = $this->db->getwhere ( DBPREFIX . '`users`', array ( 'Username' => $user, 'Email' => $email ) );
			}
		}
		return ( ( $query != FALSE ) ? $query->num_rows () > 0 : FALSE ) ? $query->result () : FALSE;
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
	
	function get_user_group ( $id = FALSE )
	{
		if ( ! $id ) {
			$level_access = get_mem_info ( 'Level_access' );
		}
		else {
			$level_access = $this->get_level_access ( $id );
		}

		$query = $this->db->query 
		( 	
				"SELECT 
					`title` 
				FROM 
					" . DBPREFIX . "groups 
				WHERE 
					`ID` = " . qstr ( ( int ) $level_access )
		);
					
		if ( $query->num_rows () == 1 )
		{
			$row = $query->row ();
			return $row->title;
		}
		else {
			return Lang ( 'undefined' );
		}
	}
	
	function get_users_by_group ( $group_id )
	{
		$query = $this->db->query 
		( 	
				"SELECT 
					*
				FROM 
					" . DBPREFIX . "users 
				WHERE 
					Level_access = " . qstr ( ( int ) $group_id )
		);

		return $query->result ();
	}
	
	/**
	 * Returns the user's details based on Email
	 *
	 * @param int $id
	 * @return obj
	 */
	
	function get_member_by_email ( $email )
	{
		$this->db->cache_on ();
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'users 
			WHERE 
				Email = ' . qstr ( $email ) 
		);		
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}
	
	/**
	 * Updates a member based on ID
	 *
	 * @param int $id
	 * @return bol
	 */
	
	function activate_member_by_id ( $id )
	{
		$query = 'UPDATE ' . DBPREFIX . 'users SET Active = 1 WHERE ID = ' . qstr ( $id );
		return ( $this->db->query ( $query ) ) ? TRUE : FALSE;
	}
	
	
	/**
	 * Suspends a member
	 *
	 * @param int $ID
	 * @return bol
	 */
	
	function suspend_mem ( $ID )
	{
		return ( $this->db->query 
		( 
			"UPDATE 
				" . DBPREFIX . "users 
			SET 
				`Active` = 2
			WHERE 
				`ID` = " . qstr ( $ID ) 
		) ) ? TRUE : FALSE;
	}
	
	/**
	 * Deletes a member
	 *
	 * @param int $ID
	 * @return bol
	 */
	
	function delete_mem ( $ID )
	{
		return ( $this->db->query 
		( 
			"DELETE FROM 
				" . DBPREFIX . "users 
			WHERE 
				`ID` = " . qstr ( $ID ) 
		) ) ? TRUE : FALSE;
	}
	
	/**
	 * Returns the user's details based on Username
	 *
	 * @param int $id
	 * @return obj
	 */
	
	function get_member_by_username ( $username )
	{
		$this->db->cache_off ();
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'users 
			WHERE 
				Username = ' . qstr ( $username ) 
		);		
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}
	
	/**
	 * Returns the user's details based on Username
	 *
	 * @param int $id
	 * @return obj
	 */
	
	function get_member_by_user_pass ( $username, $password )
	{
		$query = $this->db->query 
		( 	'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'users 
			WHERE 
				Username = ' . qstr ( $username ) . ' 
			AND 
				Password = ' . qstr ( md5 ( $password ) ) 
		);
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}
	
	/**
	 * Returns the level access of a given member
	 *
	 * @param int $id
	 * @return int/bol
	 */
	
	function get_level_access ( $id )
	{
		$query = $this->db->query 
		( 	'SELECT 
				Level_access 
			FROM 
				' . DBPREFIX . 'users 
			WHERE 
				ID = ' . qstr ( $id )
		);
		if ( $query->num_rows () == 1 ) {
			$row = $query->row ();
			return $row->Level_access;
		}
		return FALSE;		
	}
	
	/**
	 * Inserts the new member into the database, used for registration
	 *
	 * @return bol
	 */
	
	function add_new_member ( $username, $password, $email, $level_access, $active_or_not, $auto_approve = 0 )
	{
		$this->load->helper ( 'string' );
		$data = array
		(
			'`Username`'		=> $username,
			'`Password`'		=> md5 ( $password ),
			'`date_registered`'	=> now (),
			'`Email`'		=> $email,
			'`Random_key`'		=> random_string ( 'alnum', 32 ),
			'`Level_access`'	=> $level_access,
			'`Active`'		=> $active_or_not,
			'`auto_approve`'	=> $auto_approve
		);

		return ( $this->db->insert ( DBPREFIX . 'users', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}
	
	/**
	 * Updates a member
	 *
	 * @return bol
	 */
	
	function update_member ( $id, $username, $password, $user_group , $email, $auto_approve )
	{
		$data = array
		(
			'`Username`'		=> $username,
			'`Email`'		=> $email,
			'`Level_access`'	=> $user_group,
			'`auto_approve`'	=> $auto_approve
		);

		if ( $password != null ) {
			$data [ 'Password' ] = md5 ( $password );
		}

		$this->db->where ( 'ID', $id );
		return ( $this->db->update ( DBPREFIX . 'users', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}
	
	/**
	 * Select the members for mass mailing purposes
	 *
	 * @returnobj/bol
	 */
	
	function select_members_for_mass_mail ( $group_array, $status_array )
	{
		$q = 'SELECT DISTINCT * FROM `' . DBPREFIX . 'users` WHERE (';

		foreach ( $group_array as $group ) {
			$q .= '(Level_access = ' . qstr ( $group ) . ') OR';
		}
		
		$q = substr ( $q, 0, ( strLen ( $q ) -3 ) );
		
		$q .= ') AND (';

		foreach ( $status_array as $stat ) {
			$q .= '(Active = ' . qstr ( $stat ) . ') OR';
		}
		
		$q = substr ( $q, 0, ( strLen ( $q ) - 3 ) );

		$q .= ')';
		
		$query = $this->db->query ( $q );
		
		return ( $query->num_rows () > 0 ) ? $query->result () : FALSE;
	}
	
	/**
	 * Returns the number of active users in db
	 * @return obj/bol
	 */
	
	function get_active_users_nr ()
	{
		$query = $this->db->query ( "SELECT ID FROM `" . DBPREFIX . "users` WHERE `Active` = 1" );
		return $query->num_rows ();
	}
	
	/**
	 * Returns the number of inactive users in db
	 * @return obj/bol
	 */
	
	function get_inactive_users_nr ()
	{
		$query = $this->db->query ( "SELECT ID FROM `" . DBPREFIX . "users` WHERE `Active` = 0" );
		return $query->num_rows ();
	}
	
	/**
	 * Returns the number of suspended users in db
	 * @return obj/bol
	 */
	
	function get_suspended_users_nr ()
	{
		$query = $this->db->query ( "SELECT ID FROM `" . DBPREFIX . "users` WHERE `Active` = 2" );
		return $query->num_rows ();
	}

	function password_reset ( $email, $random_passkey )
	{
		$this->db->where ( 'Email', $email );
		$this->db->set ( 'Temp_pass', $random_passkey );
		$this->db->set ( 'Temp_pass_active', 1 );
		if ( $this->db->update ( DBPREFIX . 'users' ) ) {
			return TRUE;
		}
		return FALSE;
	}
	
	function confirmpassword ( $id, $t_pass )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'users WHERE ID = ' . qstr ( $id ) );
		$row = $query->row ();

		if ( $query->num_rows () == 1 && $row->Temp_pass == $t_pass && $row->Temp_pass_active == 1 )
		{
			$this->db->where ( 'ID', $id );
			$this->db->set ( 'Password', md5 ( $t_pass ) );
			$this->db->set ( 'Temp_pass_active', 0 );
			$this->db->update ( DBPREFIX . 'users' );
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	
	/**================================================================================================================
	 *	USERS END
	 *=================================================================================================================*/
	
}

//END