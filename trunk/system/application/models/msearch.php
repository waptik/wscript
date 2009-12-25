<?php

class Msearch extends Model {

	function save_string ( $string ) {
		$data = array ( 
			'search_string' => $string, 
			'date_added' => now () 
		);
		
		return ( $this->db->insert ( DBPREFIX . 'searches', escape_arr ( $data ) ) ) ? TRUE : FALSE;
	}

	function delete_search_string_in_interval ( $interval ) {
		switch ( $interval ) {
			case 'all' :
				return $this->db->query ( 'TRUNCATE TABLE ' . DBPREFIX . 'searches' );
				break;
			
			case 'one_hour' :
				return $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'searches WHERE date_added < ' . ( now () - 3600 ) );
				break;
			
			case 'one_day' :
				return $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'searches WHERE date_added < ' . ( now () - 86400 ) );
				break;
			
			case 'one_week' :
				return $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'searches WHERE date_added < ' . now () - ( 86400 * 7 ) );
				break;
			
			case 'one_month' :
				return $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'searches WHERE date_added < ' . now () - ( 86400 * 30 ) );
				break;
			
			case 'one_year' :
				return $this->db->query ( 'DELETE FROM ' . DBPREFIX . 'searches WHERE date_added < ' . now () - ( 86400 * 365 ) );
				break;
		}
	}

	function get_search_strings () {
		return $this->db->query ( 'SELECT search_string, COUNT(*) as occurences FROM ' . DBPREFIX . 'searches GROUP BY search_string ORDER BY occurences DESC' );
	}

	function results ( $string, $category, $height, $width ) {
		$query = '
			(SELECT 
				SQL_CALC_FOUND_ROWS 
				DISTINCT w.*, u.Username
			FROM 
				' . DBPREFIX . 'wallpapers w 
			LEFT JOIN 
				' . DBPREFIX . 'wallpapers AS child 
				ON
				(
					w.ID = child.parent_id
				)
			LEFT JOIN 
				' . DBPREFIX . 'users u
				ON
				(
					u.ID = w.user_id
				) 
			LEFT JOIN 
				' . DBPREFIX . 'tags_rel r 
				ON
				(
					w.ID = r.item_id
				) 
			LEFT JOIN ' . DBPREFIX . 'tags t 
				ON
				(
					t.ID=r.tag_id
				)
			WHERE 
					w.active = 1 
				AND 
					w.parent_id = 0 
				AND
					(';
		$kt = explode ( " ", $string );

		foreach ( $kt as $val ) {
			if ( $val != " " and strlen ( $val ) > 0 ) {
				$query .= ' w.file_title LIKE "%' . strip_punctuation ( $val ) . '%" OR';
			}
		}

		$query = substr ( $query, 0, ( strLen ( $query ) - 3 ) );
		$query .= ')';

		if ( $category != FALSE && numeric ( $category ) ) {
			$terminals = get_subcats_wallpaper_childs ( $category );
			$query .= ' AND (w.cat_id = ' . qstr ( $category );
			if ( count ( $terminals ) > 0 ) {
				foreach ( $terminals as $value ) {
					$query .= ' OR w.cat_id = ' . qstr ( $value );
				}
			}
			$query .= ')';
		}

		if ( $height != FALSE && numeric ( $height ) && $width != FALSE && numeric ( $width ) ) {
			$type = detect_wallpaper_type ( get_sizes (), $height, $width );
			$query .= ' 
				AND
				(
					(
							w.type = ' . qstr ( $type ) . ' 
						OR
							child.type = ' . qstr ( $type ) . '
					)
				AND 
				(
					(
							child.width >= ' . qstr ( $width ) . ' 
						OR 
							w.width >= ' . qstr ( $width ) . '
					) 
					AND 
					(
							child.height >= ' . qstr ( $height ) . ' 
						OR 
							w.height >= ' . qstr ( $height ) . '
					)
				)';
			
			if ( $type == 'psp' ) {
				$query .= ' 
				OR
					(
							w.type = ' . qstr ( 'wide' ) . ' 
						OR 
							child.type = ' . qstr ( 'wide' ) . '
					)
				)';
			}
			else {
				$query .= ')';
			}
		}

		$query .= ')';

		$query .= ' UNION DISTINCT';

		$query .= '
			(SELECT  
				DISTINCT w.*, u.Username
			FROM 
				' . DBPREFIX . 'wallpapers w 
			LEFT JOIN 
				' . DBPREFIX . 'wallpapers AS child 
				ON
				(
					w.ID = child.parent_id
				)
			LEFT JOIN 
				' . DBPREFIX . 'users u
				ON
				(
					u.ID = w.user_id
				) 
			LEFT JOIN 
				' . DBPREFIX . 'tags_rel r 
				ON
				(
					w.ID = r.item_id
				) 
			LEFT JOIN ' . DBPREFIX . 'tags t 
				ON
				(
					t.ID=r.tag_id
				)
			WHERE 
					w.active = 1 
				AND 
					w.parent_id = 0 
				AND
					(';
		$kt = explode ( " ", $string );

		foreach ( $kt as $val ) {
			if ( $val != " " and strlen ( $val ) > 0 ) {
				$query .= ' t.tag LIKE "%' . strip_punctuation ( $val ) . '%" OR';
			}
		}

		$query = substr ( $query, 0, ( strLen ( $query ) - 3 ) );
		$query .= ')';

		if ( $category != FALSE && numeric ( $category ) ) {
			$terminals = get_subcats_wallpaper_childs ( $category );
			$query .= ' AND (w.cat_id = ' . qstr ( $category );
			if ( count ( $terminals ) > 0 ) {
				foreach ( $terminals as $value ) {
					$query .= ' OR w.cat_id = ' . qstr ( $value );
				}
			}
			$query .= ')';
		}

		if ( $height != FALSE && numeric ( $height ) && $width != FALSE && numeric ( $width ) ) {
			$type = detect_wallpaper_type ( get_sizes (), $height, $width );
			$query .= ' 
				AND
				(
					(
							w.type = ' . qstr ( $type ) . ' 
						OR
							child.type = ' . qstr ( $type ) . '
					)
				AND 
				(
					(
							child.width >= ' . qstr ( $width ) . ' 
						OR 
							w.width >= ' . qstr ( $width ) . '
					) 
					AND 
					(
							child.height >= ' . qstr ( $height ) . ' 
						OR 
							w.height >= ' . qstr ( $height ) . '
					)
				)';
			
			if ( $type == 'psp' ) {
				$query .= ' 
				OR
					(
							w.type = ' . qstr ( 'wide' ) . ' 
						OR 
							child.type = ' . qstr ( 'wide' ) . '
					)
				)';
			}
			else {
				$query .= ')';
			}
		}

		$query .= ' ) ORDER BY ID ASC';

		return $query;
	}
}

//END