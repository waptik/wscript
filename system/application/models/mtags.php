<?php

class Mtags extends Model {

	function add_exclusion ( $tag )
	{
		$this->db->query ( 'INSERT INTO ' . DBPREFIX . 'tags (`tag`,`exclude`) VALUES (' . qstr ( $tag ) . ',1) ON DUPLICATE KEY UPDATE ID=LAST_INSERT_ID(ID), exclude=1' );
		return $this->db->call_function ( 'insert_id' );
	}

	function add ( $tag, $occurences = 1 )
	{
		$this->db->query ( 'INSERT INTO ' . DBPREFIX . 'tags (`tag`,`occurences`) VALUES (' . qstr ( $tag ) . ',' . qstr ( ( int ) $occurences ) . ') ON DUPLICATE KEY UPDATE ID=LAST_INSERT_ID(ID), occurences=occurences+1' );
		return $this->db->call_function ( 'insert_id' );
	}

	function add_rel ( $tag_id, $wall_id )
	{
		$this->db->query ( 'INSERT IGNORE INTO ' . DBPREFIX . 'tags_rel (`tag_id`,`item_id`) VALUES (' . qstr ( ( int ) $tag_id ) . ',' . qstr ( ( int ) $wall_id ) . ')' );
		return $this->db->call_function ( 'insert_id' );
	}

	function get_tag ( $id )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'tags WHERE ID = ' . qstr ( $id ) );
		if ( $query->num_rows () == 1 )
		{
			return $query->row ();
		}
		return FALSE;
	}

	function delete_exclusion ( $id )
	{
		return ( $this->db->query ( 'UPDATE ' . DBPREFIX . 'tags SET exclude=0 WHERE ID = ' . qstr ( $id ) ) ) ? TRUE : FALSE;
	}

	function get_tags_data ()
	{
		$query = $this->db->query
		(
			'SELECT
				DISTINCT tag,
				occurences
			FROM
				' . DBPREFIX . 'tags
			WHERE ID >= (SELECT FLOOR(MAX(ID) * RAND()) FROM ' . DBPREFIX . 'tags) AND exclude=0 ORDER BY ID LIMIT ' . MAX_TAGS
		);

		return ( $query->num_rows () ) ? $query->result () : FALSE;
	}

	function get_exclude_tags ()
	{
		$query = $this->db->query ( 'SELECT tag FROM ' . DBPREFIX . 'tags WHERE exclude=1' );
		return $query->result ();
	}

	function delete_by_wallpaper ( $wallpaper_id )
	{
		return ( $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'tags_rel WHERE item_id = ' . qstr ( $wallpaper_id ) ) ) ? TRUE : FALSE;
	}

	function get_wallpaper_tags ( $wall_id )
	{
		$query = $this->db->query
		(
			'SELECT
				t.tag,
				t.ID
			FROM
				' . DBPREFIX . 'tags_rel r
			INNER JOIN
				' . DBPREFIX . 'tags t
				ON
				(
					t.ID = r.tag_id
				)
			WHERE t.exclude = 0 AND r.item_id = ' . qstr ( ( int ) $wall_id )
		);

		return ( $query->num_rows () ) ? $query->result () : FALSE;
	}

	function update_tags ()
	{
		$query = $this->db->query
		(
			'SELECT
				t.ID,
				t.occurences,
				COUNT(r.tag_id) AS r_occurences,
				r.item_id as item_id,
				w.ID as wall_id
			FROM
				' . DBPREFIX . 'tags t
			LEFT JOIN
				' . DBPREFIX . 'tags_rel r
				ON
				(
					r.tag_id = t.ID
				)
			LEFT JOIN
				' . DBPREFIX . 'wallpapers w
				ON
				(
					w.id = r.item_id
				)
			WHERE
				t.exclude=0
			GROUP BY
				t.ID
			HAVING
				r_occurences <> occurences OR wall_id IS NULL LIMIT 0, 200'
		);

		if ( $query->num_rows () ) {
			foreach ( $query->result () as $row ) {
				if ( $row->wall_id == NULL ) {
					$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'tags_rel WHERE item_id = ' . qstr ( ( int ) $row->item_id ) );
					if ( $row->r_occurences ) {
						$row->r_occurences -= 1;
					}
				}

				if ( ! ( bool ) $row->r_occurences ) {
					$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'tags WHERE ID = ' . qstr ( ( int ) $row->ID ) );
				}
				else {
					$this->db->query ( 'UPDATE ' . DBPREFIX . 'tags SET occurences = ' . qstr ( ( int ) $row->r_occurences ) . ' WHERE ID = ' . qstr ( ( int ) $row->ID ) );
				}
			}
		}

		return $query->num_rows ();
	}
}

//END