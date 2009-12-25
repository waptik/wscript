<?php

class Mcomments extends Model {

	function add ( $item_id, $c_comment, $c_name, $c_url, $c_email, $active )
	{
		$data = array
		(
			'item_id'	=> $item_id,
			'c_name'	=> $c_name,
			'c_email'	=> $c_email,
			'c_url'		=> $c_url,
			'c_comment'	=> $c_comment,
			'date_added'	=> now (),
			'active'	=> $active
		);

		return ( $this->db->insert ( DBPREFIX . 'comments', escape_arr ( $data ) ) ) ? $this->db->call_function ( 'insert_id' ) : FALSE;
	}

	function delete ( $item_id )
	{
		return ( $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'comments WHERE ID = ' . qstr ( $item_id ) ) ) ? TRUE : FALSE;
	}

	function edit ( $item_id, $data = array () )
	{
		$this->db->where ( 'ID', $item_id );
		return $this->db->update ( DBPREFIX . 'comments', $data ); 
	}

}