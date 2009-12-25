<?php

class Master extends Model {

	/**================================================================================================================
	 *	GLOBALS START
	 *=================================================================================================================*/
	
	function check_bookmark ( $id ) {
		$query = $this->db->query ( 'SELECT ID FROM ' . DBPREFIX . 'member_bookmarks WHERE item_id=' . qstr ( $id ) . ' AND user_id=' . qstr ( $this->session->userdata ( AUTH_SESSION_ID ) ) );
		
		if ( $query->num_rows () > 0 ) {
			return TRUE;
		}
		
		return FALSE;
	}

	function get_setting ( $setting ) {
		$query = $this->db->query ( 'SELECT value FROM ' . DBPREFIX . 'site_settings WHERE label=' . qstr ( $setting ) );
		
		if ( $query->num_rows () ) {
			$row = $query->row ();
			return $row->value;
		}
		return FALSE;
	}

	function get_settings () {
		$query = $this->db->query ( 'SELECT label, value FROM ' . DBPREFIX . 'site_settings' );
		return $query->result_array ();
	}

	function set_setting ( $label, $value ) {
		if ( ! $this->get_setting ( $label ) ) {
			return $this->db->query ( 'INSERT INTO ' . DBPREFIX . 'site_settings SET label = ' . qstr ( $label ) . ', value = ' . qstr ( $value ) );
		}
		return $this->db->query ( 'UPDATE ' . DBPREFIX . 'site_settings SET value = ' . qstr ( $value ) . ' WHERE label = ' . qstr ( $label ) );
	}

	function insertRating ( $id, $vote_value, $return = TRUE ) {
		if ( ! $this->check_if_voted ( $id ) ) :
			
			$data = array ( 
				'item_id' => $id, 'visitor_ip' => $this->input->ip_address (), 'date_added' => now (), 'vote_value' => $vote_value, 'ID_user' => $this->session->userdata ( AUTH_SESSION_ID ) 
			);
			
			$this->db->insert ( DBPREFIX . 'votes', escape_arr ( $data ) );
		
			
		endif;
		
		//do we need a return?
		if ( $return ) :
			$out = '		<ul>' . "\n";
			$out .= '			<li class="voteTotal">';
			$out .= '<a class="voted" title="Voted" href="javascript:void(0)">';
			$out .= '<span>' . get_article_score ( $id ) . '</span></a></li>' . "\n";
			$out .= '		</ul>' . "\n";
			
			return $out;
		
		endif;
	}

	function check_if_voted ( $item_id ) {
		$query = $this->db->query ( "SELECT ID FROM " . DBPREFIX . "votes WHERE item_id = " . qstr ( $item_id ) . " AND visitor_ip =" . qstr ( $this->input->ip_address () ) );
		if ( $query->num_rows () > 0 ) {
			return TRUE;
		}
		return FALSE;
	}

/**================================================================================================================
 *	GLOBALS END
 *=================================================================================================================*/
}

//END