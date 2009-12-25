<?php

class Mpartners extends Model {
	
	function insert_partner ( $title, $desc, $link )
	{
		$data = array
		(
			'title'		=> $title,
			'description'	=> $desc,
			'link'		=> $link	
		);
		
		return ( $this->db->insert ( DBPREFIX . 'partners', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}
	
	function update_partner ( $id, $title, $desc, $link )
	{
		$data = array
		(
			'title'		=> $title,
			'description'	=> $desc,
			'link'		=> $link	
		);
		$this->db->where ( 'ID', $id );
		return ( $this->db->update ( DBPREFIX . 'partners', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}
	
	function get_partner ( $id, $cache = TRUE )
	{
		evaluate_cache ( $cache );
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'partners WHERE ID = ' . qstr ( $id ) );
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}
	
	function delete ( $id )
	{
		return ( $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'partners WHERE ID = ' . qstr ( $id ) ) ) ? TRUE : FALSE;
	}
	
	function get_partners ()
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'partners ORDER BY ID DESC' );
		return ( $query->num_rows () > 0 ) ? $query->result () : FALSE;
	}
	
}

//END