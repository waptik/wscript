<?php

class Mwallpaper extends Model {

	function add_wallpaper ( $user_id, $hash, $title_alias, $description, $title, $cat_id, $type, $height, $width, $active = 0, $parent_id = 0, $date_added = false )
	{
		if ( ! $date_added ) {
			$date_added = now ();
		}

		$data = array
		(
				'hash'		=>	$hash,
				'file_title'	=>	$title,
				'cat_id'	=>	$cat_id,
				'description'	=>	$description,
				'title_alias'	=>	$title_alias,
				'parent_id'	=>	$parent_id,
				'type'		=>	$type,
				'height'	=>	$height,
				'width'		=>	$width,
				'user_id'	=>	$user_id,
				'date_added'	=>	$date_added,
				'active'	=>	$active
		);

		return ( $this->db->insert ( DBPREFIX . 'wallpapers', escape_arr ( $data ) ) ) ? $this->db->call_function ( 'insert_id' ) : FALSE;
	}

	function add_wallpaper_bulk ( $hash, $title, $cat_id, $parent_id, $type, $height, $width, $active = 0 )
	{
		$data = array
		(
				'hash'		=>	$hash,
				'file_title'	=>	$title,
				'cat_id'	=>	$cat_id,
				'parent_id'	=>	$parent_id,
				'type'		=>	$type,
				'height'	=>	$height,
				'width'		=>	$width,
				'user_id'	=>	$this->session->userdata ( AUTH_SESSION_ID ),
				'date_added'	=>	now (),
				'active'	=>	$active
		);
		
		return ( $this->db->insert ( DBPREFIX . 'wallpapers', escape_arr ( $data ) ) ) ? $this->db->call_function ( 'insert_id' ) : FALSE;
	}

	function update_wallpaper ( $hash, $title_alias, $description, $id, $title, $cat_id, $parent_id, $type, $height, $width, $active = FALSE )
	{
		$data = array
		(
				'hash'		=>	$hash,
				'file_title'	=>	$title,
				'cat_id'	=>	$cat_id,
				'parent_id'	=>	$parent_id,
				'description'	=>	$description,
				'title_alias'	=>	$title_alias,
				'type'		=>	$type,
				'height'	=>	$height,
				'width'		=>	$width
		);
		
		if ( $active )
		{
			$data [ 'active' ] = 1;
		}
		else {
			$data [ 'active' ] = 0;
		}
		
		$this->db->where ( 'ID', $id );
		return ( $this->db->update ( DBPREFIX . 'wallpapers', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}
	
	function check_unique_hash ( $hash )
	{
		$query = $this->db->query ( "SELECT `ID` FROM `" . DBPREFIX . "wallpapers` WHERE `hash` = " . qstr ( $hash ) );
		return ( $query->num_rows () == 0 ) ? TRUE : FALSE;
	}
	
	function update_wallpaper_details ( $id, $hash )
	{
		$data = array
		(
			'hash' => $hash
		);	

		$this->db->where ( 'ID', $id );
		return ( $this->db->update ( DBPREFIX . 'wallpapers', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}
	
	function update_wallpaper_file ( $title_alias, $description, $id, $title, $cat_id, $active = 0 )
	{
		$data = array
		(
				'active'	=>	$active,
				'file_title'	=>	$title,
				'cat_id'	=>	$cat_id,
				'description'	=>	$description,
				'title_alias'	=>	$title_alias,
		);	

		$this->db->where ( 'ID', $id );
		return ( $this->db->update ( DBPREFIX . 'wallpapers', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}
	
	function delete_wallpapers_by_category ( $id )
	{
		$query = $this->db->query ( "SELECT ID FROM `" . DBPREFIX . "wallpapers` WHERE `cat_id` = " . qstr ( $id ) );
		if ( $query->num_rows () > 0 ) {
			foreach ( $query->result () as $row ) {
                                delete_wallpaper ( $row->ID );
			}
		}
		return TRUE;
	}
        
        function get_todays_wallpapers_nr ()
	{
		$query = $this->db->query ( "SELECT 
                                                        ID 
                                                FROM 
                                                        `" . DBPREFIX . "wallpapers` 
                                                WHERE 
                                                        FROM_UNIXTIME(date_added,'%e %m %Y') = FROM_UNIXTIME(UNIX_TIMESTAMP( ),'%e %m %Y')" );
		return $query->num_rows ();
	}
	
	function create_dummy_ID ()
	{
		$data = array
		(
				'file_title'	=>	'dummy',
				'cat_id'	=>	0,
				'parent_id'	=>	0,
				'user_id'	=>	$this->session->userdata ( AUTH_SESSION_ID ),
				'date_added'	=>	now ()
		);
		
		if ( $this->db->insert ( DBPREFIX . 'wallpapers', escape_arr ( $data ) ) )
		{
			return $this->db->call_function ( 'insert_id' );
		}
		return FALSE;
	}
	
	function sitemap_get_wallpapers_by_category ( $cat_id )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE Active = 1 AND parent_id = 0 AND cat_id = ' . qstr ( $cat_id ) . ' ORDER BY date_added DESC' );
		return $query->result ();
	}
	
	function rss_get_wallpapers_by_member ( $user_id )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE Active = 1 AND parent_id = 0 AND user_id = ' . qstr ( $user_id ) . ' ORDER BY date_added DESC LIMIT 10' );
		return $query->result ();
	}
	
	function rss_get_wallpapers_by_category ( $cat_id )
	{
		$terminals = get_subcats_wallpaper_childs ( $cat_id );
		$query = 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE Active = 1 AND parent_id = 0 AND (cat_id = ' . qstr ( $cat_id );
		
		if ( count ( $terminals ) > 0 ) {
			foreach ( $terminals as $value )
			{
				$query .= ' OR cat_id = ' . $value;
			}
		}
		
		$query .= ') ORDER BY date_added DESC LIMIT 10';
		
		$query = $this->db->query ( $query );
		
		return $query->result ();
	}
	
	function rss_get_wallpapers_welcome ()
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE Active = 1 AND parent_id = 0 ORDER BY ' . WALLPAPER_DISPLAY_ORDER . ' ' . WALLPAPER_ORDER_TYPE . ' LIMIT 10' );
		return $query->result ();
	}
	
	function rss_get_wallpapers_latest ()
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE Active = 1 AND parent_id = 0 ORDER BY date_added DESC LIMIT 10' );
		return $query->result ();
	}
	
	function rss_get_wallpapers_top ()
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE Active = 1 AND parent_id = 0 ORDER BY rating DESC LIMIT 10' );
		return $query->result ();
	}
	
	function rss_get_wallpapers_type ( $type )
	{
		if ( $type == 'psp' ) {
			$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE Active = 1 AND parent_id = 0 AND type = \'psp\' OR type = \'wide\' ORDER BY date_added DESC LIMIT 10' );
		}
		else {
			$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE Active = 1 AND parent_id = 0 AND type = ' . qstr ( $type ) . ' ORDER BY date_added DESC LIMIT 10' );
		}
		
		return $query->result ();
	}

	function rss_get_wallpapers_by_tag ( $tag )
	{
		$query = 'SELECT
				w.*
			FROM
				' . DBPREFIX . 'wallpapers w
			INNER JOIN
				' . DBPREFIX . 'tags_rel r
				ON
				(
					r.item_id=w.ID
				)
			LEFT JOIN
				' . DBPREFIX . 'tags t
				ON
				(
					r.tag_id=t.ID
				)
			WHERE
					w.Active = 1
				AND
					w.parent_id = 0
				AND
					t.tag = ' . qstr ( $tag ) . '
			ORDER BY
				w.date_added DESC
			LIMIT 10';

		$query = $this->db->query ( $query );
		
		return $query->result ();
	}
	
	function rss_get_wallpapers_by_color ( $color )
	{
		$query = 'SELECT
				w.*
			FROM
				' . DBPREFIX . 'wallpapers w
			INNER JOIN
				' . DBPREFIX . 'colors_rel r
				ON
				(
					r.item_id=w.ID
				)
			LEFT JOIN
				' . DBPREFIX . 'colors c
				ON
				(
					r.color_id=c.ID
				)
			WHERE
					w.Active = 1
				AND
					w.parent_id = 0
				AND
					c.color = ' . qstr ( $color ) . '
			ORDER BY
				w.date_added DESC
			LIMIT 10';

		$query = $this->db->query ( $query );
		
		return $query->result ();
	}

	function get_wallpaper ( $id )
	{
		$query = $this->db->query
		(
			'SELECT
				*
			FROM
				' . DBPREFIX . 'wallpapers
			WHERE
				ID = ' . qstr ( $id )
		);
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}
	
	function get_wallpaper_adv ( $id )
	{
		$query = $this->db->query
		(
			'SELECT
				w.*,
				COUNT(v.visitor_ip) AS votes_nr,
				u.Username
			FROM
				' . DBPREFIX . 'wallpapers w
			INNER JOIN
				' . DBPREFIX . 'users u
			ON
				(
					w.user_id = u.ID
				)
			LEFT JOIN
				' . DBPREFIX . 'votes v
			ON
				(
					v.item_id = w.ID
				)
			WHERE
				w.ID = ' . qstr ( $id ) . '
			AND
				w.parent_id = 0
			GROUP BY
				w.ID'
		);
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}
	
	function get_childs ( $parent_id )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE parent_id = ' . qstr ( ( int ) $parent_id ) );
		return ( $query->num_rows () ) ? $query->result_array () : FALSE;
	}
	
	function check_duplicate ( $title, $cat_id, $filename )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE file_title = ' . qstr ( $title ) . ' AND cat_id = ' . qstr ( $cat_id ) . ' AND wallpaper = ' . qstr ( $filename ) );
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}

	function get_most_downloaded ( $limit )
	{
		$query = $this->db->query
		(
			'SELECT
				w.*
			FROM
				' . DBPREFIX . 'wallpapers w
			WHERE
				w.active = 1
			AND
				w.downloads >= 1
			ORDER BY
				w.downloads DESC
			LIMIT
				0, ' . qstr ( ( int ) $limit )
		);

		return ( $query->num_rows () > 0 ) ? $query->result () : FALSE;
	}

	function increment_downloads ( $id )
	{
		$this->db->query
		(
			'INSERT IGNORE INTO
				' . DBPREFIX . 'downloads (item_id, ip)
			VALUES
				(' . qstr ( ( int ) $id ) . ', ' . qstr ( $this->input->ip_address () ) . ')'
		);

		if ( ( bool ) $this->db->affected_rows () ) {
			$this->db->query ( 'UPDATE ' . DBPREFIX . 'wallpapers SET downloads = downloads+1 WHERE ID = ' . qstr ( ( int ) $id ) );
		}

		return TRUE;
	}

	function increment_hits ( $id )
	{
		$q = $this->db->query
		(
			'INSERT IGNORE INTO
				' . DBPREFIX . 'hits (item_id, ip)
			VALUES
				(' . qstr ( ( int ) $id ) . ', ' . qstr ( $this->input->ip_address () ) . ')'
		);

		if ( ( bool ) $this->db->affected_rows () ) {
			$this->db->query ( 'UPDATE ' . DBPREFIX . 'wallpapers SET hits = hits+1 WHERE ID = ' . qstr ( ( int ) $id ) );
		}

		return TRUE;
	}

	function get_highest_rated ( $limit )
	{
		$query = $this->db->query
		(
			'SELECT
				w.*,
				(
					SELECT
						COUNT(v.visitor_ip) as nr_votes
					FROM
						' . DBPREFIX . 'votes v
					WHERE
						w.ID = v.item_id
				) AS nr_votes
			FROM
				' . DBPREFIX . 'wallpapers w
			WHERE
				w.active = 1
			AND
				w.parent_id = 0
			HAVING
				nr_votes >= ' . MIN_WALL_VOTES_HOMEPAGE . '
			ORDER BY
				w.rating DESC, nr_votes DESC
			LIMIT ' . qstr ( $limit )
		);
		return ( $query->num_rows () > 0 ) ? $query->result () : FALSE;
	}
	
	function get_top_rated_members ( $limit )
	{
		$query = $this->db->query
		(
			'SELECT DISTINCT
				u.ID,
				u.Username,
				SUM(v.vote_value)/COUNT(v.visitor_ip) AS score,
				COUNT(v.visitor_ip) as nr_votes
			FROM
				' . DBPREFIX . 'wallpapers w
			INNER JOIN
				' . DBPREFIX . 'votes v
			ON
				(w.ID = v.item_id)
			INNER JOIN
				' . DBPREFIX . 'users u
			ON
				(w.user_id = u.ID)
			WHERE
				(w.active = 1)
			AND
				u.Username != \'Guest\'
			GROUP BY
				u.ID
			HAVING
				nr_votes >= ' . MIN_USR_VOTES_HOMEPAGE . '
			ORDER BY
				score DESC, nr_votes DESC
			LIMIT ' . qstr ( $limit )
		);
		return ( $query->num_rows () > 0 ) ? $query->result () : FALSE;
	}
	
	function get_user_rating ( $id )
	{
		$query = $this->db->query
		(
			'SELECT
				SUM(w.rating)/COUNT(w.ID) AS score
			FROM
				' . DBPREFIX . 'wallpapers w
			WHERE
				(w.active = 1)
			AND
				w.parent_id = 0
			AND
				(w.user_id = ' . qstr ( ( int ) $id ) . ')
			ORDER BY
				score DESC'
		);

		$row = $query->row ();
		return $row->score;
	}

	function get_user_wallpapers ( $id )
	{
		$query = $this->db->query
		(
			'SELECT
				*
			FROM
				' . DBPREFIX . 'wallpapers
			WHERE
				user_id = ' . qstr ( ( int ) $id )
		);

		$query->result ();
	}

	function delete_dummies ()
	{
		return $this->db->query
		(
			'DELETE FROM ' . DBPREFIX . 'wallpapers WHERE file_title = \'dummy\''
		);
	}

	function get_user_votes ( $id )
	{
		$query = $this->db->query
		(
			'SELECT
				COUNT(' . DBPREFIX . 'votes.ID) AS votes
			FROM
				' . DBPREFIX . 'wallpapers
			INNER JOIN
				' . DBPREFIX . 'votes
			ON
				(' . DBPREFIX . 'wallpapers.ID = ' . DBPREFIX . 'votes.item_id)
			WHERE
				(' . DBPREFIX . 'wallpapers.active = 1)
			AND
				(' . DBPREFIX . 'wallpapers.user_id = ' . qstr ( $id ) . ')
			ORDER BY
				votes DESC'
		);
		$row = $query->row ();
		return $row->votes;
	}
	
	function get_downloads_nr_by_member ( $id )
	{
		$query = $this->db->query
		(
			'SELECT
				SUM(w.downloads) as dwloads
			FROM
				' . DBPREFIX . 'wallpapers w
			WHERE
				w.user_id = ' . qstr ( ( int ) $id )
		 );
		
		if ( $query->num_rows () == 1 )
		{
			$row = $query->row ();
			return $row->dwloads;
		}
		return 0;
	}
	
	function get_hits_nr_by_member ( $id )
	{
		$query = $this->db->query
		(
			'SELECT
				SUM(w.hits) as total_hits
			FROM
				' . DBPREFIX . 'wallpapers w
			WHERE
				w.user_id = ' . qstr ( ( int ) $id )
		 );
		
		if ( $query->num_rows () ) {
			$row = $query->row ();
			return $row->total_hits;
		}
		return 0;
	}
	
	function get_wallpaper_downloads ( $id )
	{
		$query = $this->db->query
		(
			'SELECT
				downloads
			FROM
				' . DBPREFIX . 'wallpapers w
			WHERE
				w.id = ' . qstr ( ( int ) $id )
		 );
		
		if ( $query->num_rows () == 1 )
		{
			$row = $query->row ();
			return $row->downloads;
		}
		return 0;
	}
	
	function get_wallpaper_for_download ( $id, $type, $width, $height )
	{
		$q = 'SELECT
				*
			FROM
				' . DBPREFIX . 'wallpapers
			WHERE
					(ID = ' . qstr ( ( int ) $id ) . '
				OR
					parent_id = ' . qstr ( ( int ) $id ) . ')
			AND
				width >= ' . qstr ( ( int ) $width ) . '
			AND
				height >= ' . qstr ( ( int ) $height ) . '
			AND
				(type = ' . qstr ( $type );

			if ( $type == 'psp' ) {
				$q .= 'OR
						type = \'wide\'';
			}

		$q .= ')';

		$query = $this->db->query ( $q );
		return ( $query->num_rows () ) ? $query->row () : FALSE;
	}
	
	function get_top_contributors ( $limit )
	{
		$query = $this->db->query
		(
			'SELECT DISTINCT
				u.ID,
				u.Username,
				COUNT(w.ID) AS nr
			FROM
				' . DBPREFIX . 'wallpapers w
			INNER JOIN
				' . DBPREFIX . 'users u
			ON
				(w.user_id = u.ID)
			WHERE
				(w.active = 1)
			AND
				u.Username != \'Guest\'
			AND
				w.parent_id = 0
			GROUP BY
				u.ID
			ORDER BY
				nr DESC
			LIMIT ' . qstr ( $limit )
		);
		return ( $query->num_rows () > 0 ) ? $query->result () : FALSE;
	}

	function get_recently_added ( $limit )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE active = 1 AND parent_id = 0 ORDER BY date_added DESC LIMIT ' . qstr ( $limit ) );
		return ( $query->num_rows () > 0 ) ? $query->result () : FALSE;
	}

	function delete_childs ( $id )
	{
		return $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'wallpapers WHERE parent_id = ' . qstr ( $id ) );
	}

	function get_wallpapers_nr_from_category ( $cat_id )
	{
		$query = $this->db->query 
		(
			'SELECT 
				COUNT(*) AS nr
			FROM 
				' . DBPREFIX . 'wallpapers 
			WHERE
				active = 1
			AND
				parent_id = 0
			AND
				cat_id = ' . qstr ( $cat_id ) 
		);
		$row = $query->row ();
		return $row->nr;
	}

	function get_wallpapers_nr_by_member ( $id )
	{
		$query = $this->db->query 
		(
			'SELECT 
				COUNT(*) AS nr
			FROM 
				' . DBPREFIX . 'wallpapers 
			WHERE
				active = 1
			AND
				parent_id = 0
			AND
				user_id = ' . qstr ( $id ) 
		);
		$row = $query->row ();
		return $row->nr;
	}

	function get_wallpaper_rating ( $id )
	{
		$query = $this->db->query
		(
			'SELECT
				SUM(vote_value)/COUNT(visitor_ip) as score
			FROM
				' . DBPREFIX . 'votes
			WHERE
				item_id = ' . qstr ( $id )
		);
		
		if ( $query->num_rows () > 0 )
		{
			$row = $query->row ();
			return $row->score;
		}
		return 0;
	}

	function get_wallpaper_hits ( $id )
	{
		$query = $this->db->query
		(
			'SELECT
				COUNT(ID) as visits
			FROM
				' . DBPREFIX . 'hits
			WHERE
				item_id = ' . qstr ( $id )
		);
		
		if ( $query->num_rows () > 0 )
		{
			$row = $query->row ();
			return $row->visits;
		}
		return 0;
	}

	function get_more_from_author ( $user_id, $exclude, $limit = 9 )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE active = 1 AND user_id = ' . qstr ( $user_id ) . ' AND parent_id = 0 AND ID != ' . qstr ( $exclude ) . ' ORDER BY ' . WALLPAPER_DISPLAY_ORDER . ' ' . WALLPAPER_ORDER_TYPE . ' LIMIT ' . qstr ( $limit ) );
		return ( $query->num_rows () > 0 ) ? $query : FALSE;
	}

	function get_more_from_category ( $cat_id, $exclude, $limit = 9 )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE active = 1 AND cat_id = ' . qstr ( $cat_id ) . ' AND parent_id = 0 AND ID != ' . qstr ( $exclude ) . ' ORDER BY ' . WALLPAPER_DISPLAY_ORDER . ' ' . WALLPAPER_ORDER_TYPE . ' LIMIT ' . qstr ( $limit ) );
		return ( $query->num_rows () > 0 ) ? $query : FALSE;
	}

	function get_wallpapers_nr ( $status, $for_member )
	{
		if ( $for_member )
		{
			$query = $this->db->query 
			(
				'SELECT 
					COUNT(*) AS nr
				FROM 
					' . DBPREFIX . 'wallpapers 
				WHERE
					active = ' . qstr ( $status ) . '
				AND
					parent_id = 0
				AND
					user_id = ' . qstr ( $this->session->userdata ( AUTH_SESSION_ID ) )
			);
		}
		else {
			$query = $this->db->query 
			(
				'SELECT 
					COUNT(*) AS nr
				FROM 
					' . DBPREFIX . 'wallpapers 
				WHERE
					parent_id = 0
				AND
					active = ' . qstr ( $status )
			);
		}

		$row = $query->row ();
		return $row->nr;
	}

	function suspend ( $id )
	{
		$this->db->query ( 'UPDATE ' . DBPREFIX . 'wallpapers SET active = 2 WHERE parent_id = ' . qstr ( $id ) );
		return ( $this->db->query ( 'UPDATE ' . DBPREFIX . 'wallpapers SET active = 2 WHERE ID = ' . qstr ( $id ) ) ) ? TRUE : FLASE;
	}

	function suspend_by_username ( $user_id )
	{
		return $this->db->query ( 'UPDATE ' . DBPREFIX . 'wallpapers SET active = 0 WHERE user_id = ' . qstr ( $user_id ) );
	}

	function activate_by_username ( $user_id )
	{
		return $this->db->query ( 'UPDATE ' . DBPREFIX . 'wallpapers SET active = 1 WHERE user_id = ' . qstr ( $user_id ) );
	}

	function get_wallpapers_measures ()
	{
		return $this->db->query
		(
			'SELECT
				width,
				height
			FROM
				' . DBPREFIX . 'wallpapers
			WHERE
				active = 1'
		);
	}

	function activate ( $id )
	{
		$this->db->query ( 'UPDATE ' . DBPREFIX . 'wallpapers SET active = 1 WHERE parent_id = ' . qstr ( $id ) );
		return ( $this->db->query ( 'UPDATE ' . DBPREFIX . 'wallpapers SET active = 1 WHERE ID = ' . qstr ( $id ) ) ) ? TRUE : FLASE;
	}

	function delete ( $id )
	{
		$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'wallpapers WHERE parent_id = ' . qstr ( ( int ) $id ) );
		$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'hits WHERE item_id = ' . qstr ( ( int ) $id ) );
		$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'votes WHERE item_id = ' . qstr ( ( int ) $id ) );
		$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'downloads WHERE item_id = ' . qstr ( ( int ) $id ) );
		$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'comments WHERE item_id = ' . qstr ( ( int ) $id ) );

		$tags = $this->db->query
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
			WHERE r.item_id = ' . qstr ( ( int ) $id )
		);

		if ( $tags->num_rows () ) {
			foreach ( $tags->result () as $tag ) {
				$this->db->query ( 'INSERT INTO ' . DBPREFIX . 'tags (`tag`) VALUES (' . qstr ( $tag->tag ) . ') ON DUPLICATE KEY UPDATE occurences=occurences-1' );
			}
		}

		$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'tags_rel WHERE item_id = ' . qstr ( ( int ) $id ) );
		$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'tags WHERE occurences < 1' );

		$colors = $this->db->query
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
			WHERE r.item_id = ' . qstr ( ( int ) $id )
		);

		if ( $colors->num_rows () ) {
			foreach ( $colors->result () as $color ) {
				$this->db->query ( 'INSERT INTO ' . DBPREFIX . 'colors (`color`) VALUES (' . qstr ( $color->color ) . ') ON DUPLICATE KEY UPDATE occurences=occurences-1' );
			}
		}

		$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'colors_rel WHERE item_id = ' . qstr ( ( int ) $id ) );
		$this->db->query ( 'DELETE FROM ' . DBPREFIX . 'colors WHERE occurences < 1' );

		return ( $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'wallpapers WHERE ID = ' . qstr ( ( int ) $id ) ) ) ? TRUE : FLASE;
	}

	function delete_by_id ( $id )
	{
		return ( $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'wallpapers WHERE ID = ' . qstr ( ( int ) $id ) ) ) ? TRUE : FLASE;
	}

	function insert_rating ( $wallpaper_id, $vote_value )
	{
		$data = array
		(
				'item_id'	=>	$wallpaper_id,
				'visitor_ip'	=>	$this->input->ip_address (),
				'vote_value'	=>	( int ) $vote_value
		);

		$this->db->insert ( DBPREFIX . 'votes', escape_arr ( $data ) );
		$rating = ( float ) get_wallpaper_rating ( $wallpaper_id );
		$this->db->query ( 'UPDATE ' . DBPREFIX . 'wallpapers SET rating = ' . qstr ( $rating ) . ' WHERE ID = ' . qstr ( ( int ) $wallpaper_id ) );

		return $rating;
	}

	function get_votes_nr ( $wallpaper_id )
	{
		$query = $this->db->query
		( 			'SELECT
						*
					FROM
						' . DBPREFIX . 'votes
					WHERE
						item_id = ' . qstr ( ( int ) $wallpaper_id )
		);
		
		return $query->num_rows ();
	}
	
	function check_if_voted ( $wall_id, $vis_ip )
	{
		$query = $this->db->query
		( 			'SELECT
						*
					FROM
						' . DBPREFIX . 'votes
					WHERE
						visitor_ip = ' . qstr ( $vis_ip ) . '
					AND
						item_id = ' . qstr ( ( int ) $wall_id )
		);
		
		return ( $query->num_rows () == 0 ) ? FALSE : TRUE;
	}
	
	function get_next_wallpaper ( $row )
	{
		$query = $this->db->query
		( 			'SELECT
						ID, hash, date_added, file_title, title_alias, type
					FROM
						' . DBPREFIX . 'wallpapers
					WHERE
						ID > ' . qstr ( ( int ) $row->ID ) . '
					AND
						active = 1
					AND
						parent_id = 0
					ORDER BY
						ID ASC
					LIMIT 1'
		);
		
		if ( $query->num_rows () == 1 )
		{
			return $query->row ();
		}
		return FALSE;
	}
	
	function get_prev_wallpaper ( $row )
	{
		$query = $this->db->query
		( 			'SELECT
						ID, hash, date_added, file_title, title_alias, type
					FROM
						' . DBPREFIX . 'wallpapers
					WHERE
						ID < ' . qstr ( ( int ) $row->ID ) . '
					AND
						active = 1
					AND
						parent_id = 0
					ORDER BY
						ID DESC
					LIMIT 1'
		);
		
		if ( $query->num_rows () == 1 )
		{
			return $query->row ();
		}
		return FALSE;
	}
	
	function get_first_wallpaper ( $row )
	{
		$query = $this->db->query
		( 			'SELECT
						ID, hash, date_added, file_title, title_alias, type
					FROM
						' . DBPREFIX . 'wallpapers
					WHERE
						active = 1
					AND
						ID != ' . qstr ( ( int ) $row->ID ) . '
					AND
						ID < ' . qstr ( ( int ) $row->ID ) . '
					AND
						parent_id = 0
					ORDER BY
						ID ASC
					LIMIT 1'
		);
		
		if ( $query->num_rows () == 1 )
		{
			return $query->row ();
		}
		return FALSE;
	}
	
	function get_last_wallpaper ( $row )
	{
		$query = $this->db->query
		( 			'SELECT
						ID, hash, date_added, file_title, title_alias, type
					FROM
						' . DBPREFIX . 'wallpapers
					WHERE
						active = 1
					AND
						ID != ' . qstr ( ( int ) $row->ID ) . '
					AND
						ID > ' . qstr ( ( int ) $row->ID ) . '
					AND
						parent_id = 0
					ORDER BY
						ID DESC
					LIMIT 1'
		);
		
		if ( $query->num_rows () == 1 )
		{
			return $query->row ();
		}
		return FALSE;
	}

	function get_lowest_child_by_size ( $id, $type = FALSE )
	{
		if ( $type != FALSE ) {
			$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE parent_id = ' . qstr ( $id ) . ' AND type=' . qstr ( $type ) . ' ORDER BY width ASC LIMIT 1' );
		}
		else {
			$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'wallpapers WHERE parent_id = ' . qstr ( $id ) . ' ORDER BY width ASC LIMIT 1' );
		}
		return ( $query->num_rows () == 1 ) ? $query->row () : FALSE;
	}
        
        function migrate_wallpapers ( $from, $to )
        {
        	if ( $from == $to ) {
        		return TRUE;
        	}

                return ( $this->db->query ( 'UPDATE ' . DBPREFIX . 'wallpapers SET cat_id = ' . qstr ( $to ) . ' WHERE cat_id = ' . qstr ( $from ) ) ) ? TRUE : FALSE;
        }
}

//END