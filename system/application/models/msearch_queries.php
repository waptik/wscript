<?php

class Msearch_queries extends Model {
	
	function save ( $query )
	{
		$data = array
		(
			'query'		=> base64_encode ( preg_replace ( '/\s+/', ' ', preg_replace ( '/[\n\r\t]/', ' ', $query ) ) ),
			'date_added'	=> now ()
		);
		
		return ( $this->db->insert ( DBPREFIX . 'search_queries', escape_arr ( $data ) ) ) ? $this->db->call_function ( 'insert_id' ) : FALSE;
	}
	
	function get ( $id )
	{
		$query = $this->db->query ( 'SELECT query FROM ' . DBPREFIX . 'search_queries WHERE ID = ' . qstr ( $id ) );
		if ( $query->num_rows () == 1 ){
			$row = $query->row ();
			return base64_decode ( $row->query );
		}
		return FALSE;
	}
	
	function perform_maintenance ()
	{
		return ( $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'search_queries WHERE date_added < ' . qstr ( now () - 86400 ) ) ) ? TRUE : FALSE;
	}
	
}

//END