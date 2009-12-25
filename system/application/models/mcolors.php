<?php

class Mcolors extends Model {

	function add ( $color, $occurences = 1 )
	{
		$this->db->query ( 'INSERT INTO ' . DBPREFIX . 'colors (`color`,`occurences`) VALUES (' . qstr ( $color ) . ',' . qstr ( ( int ) $occurences ) . ') ON DUPLICATE KEY UPDATE ID=LAST_INSERT_ID(ID), occurences=occurences+1' );
		return $this->db->call_function ( 'insert_id' );
	}

	function add_rel ( $color_id, $wall_id )
	{
		$this->db->query ( 'INSERT IGNORE INTO ' . DBPREFIX . 'colors_rel (`color_id`,`item_id`) VALUES (' . qstr ( ( int ) $color_id ) . ',' . qstr ( ( int ) $wall_id ) . ')' );
		return $this->db->call_function ( 'insert_id' );
	}

	function get_color_data ()
	{
		$query = $this->db->query
		(
			'SELECT
				DISTINCT color
			FROM
				' . DBPREFIX . 'colors
			ORDER BY
				RAND()
			LIMIT 0, ' . MAX_COLORS
		);

		return ( $query->num_rows () ) ? $query->result () : FALSE;
	}

	function delete_by_wallpaper ( $wallpaper_id )
	{
		return ( $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'colors_rel WHERE item_id = ' . qstr ( $wallpaper_id ) ) ) ? TRUE : FALSE;
	}

	function get_wallpapers_by_color ( $color )
	{
		$query = $this->db->query
		(
			'SELECT
				w.*
			FROM
				' . DBPREFIX . 'colors_rel r
			INNER JOIN
				' . DBPREFIX . 'wallpapers w
				ON
				(
					r.item_id = w.ID
				)
			INNER JOIN
				' . DBPREFIX . 'colors c
				ON
				(
					r.color_id = c.ID
				)
			WHERE r.item_id = ' . qstr ( ( int ) $color )
		);

		return ( $query->num_rows () ) ? $query->result () : FALSE;
	}

	function get_wallpaper_colors ( $wall_id )
	{
		$query = $this->db->query
		(
			'SELECT
				c.color,
				c.ID
			FROM
				' . DBPREFIX . 'colors_rel r
			INNER JOIN
				' . DBPREFIX . 'colors c
				ON
				(
					c.ID = r.color_id
				)
			WHERE r.item_id = ' . qstr ( ( int ) $wall_id ) . ' LIMIT 0, 18'
		);

		return ( $query->num_rows () ) ? $query->result () : FALSE;
	}

	function update_colors ()
	{
		$query = $this->db->query
		(
			'SELECT
				c.ID,
				c.occurences,
				COUNT(r.color_id) AS r_occurences,
				r.item_id as item_id,
				w.ID as wall_id
			FROM
				' . DBPREFIX . 'colors c
			LEFT JOIN
				' . DBPREFIX . 'colors_rel r
				ON
				(
					r.color_id = c.ID
				)
			LEFT JOIN
				' . DBPREFIX . 'wallpapers w
				ON
				(
					w.id = r.item_id
				)
			GROUP BY
				c.ID
			HAVING
				r_occurences <> occurences OR wall_id IS NULL LIMIT 0, 200'
		);

		if ( $query->num_rows () ) {
			foreach ( $query->result () AS $row ) {
				if ( $row->wall_id == NULL ) {
					$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'colors_rel WHERE item_id = ' . qstr ( ( int ) $row->item_id ) );
					if ( $row->r_occurences ) {
						$row->r_occurences -= 1;
					}
				}

				if ( ! ( bool ) $row->r_occurences ) {
					$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'colors WHERE ID = ' . qstr ( ( int ) $row->ID ) );
				}
				else {
					$this->db->query ( 'UPDATE ' . DBPREFIX . 'colors SET occurences = ' . qstr ( ( int ) $row->r_occurences ) . ' WHERE ID = ' . qstr ( ( int ) $row->ID ) );
				}
			}
		}

		return $query->num_rows ();
	}
}

//END