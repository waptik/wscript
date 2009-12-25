<?php

class Mcategories extends Model {
	
	/**================================================================================================================
	 *	CATEGORIES START
	 *=================================================================================================================*/
	
	/**
	 * Tryes to determine if a given category is parent or not
	 *
	 * @param int $id
	 * @return bol
	 */
	
	function is_parent_category ( $id )
	{
		$query = $this->db->query 
		(
			'SELECT 
				* 
			FROM 
				' . DBPREFIX . 'categories 
			WHERE 
				id_parent = ' . qstr ( $id ) 
		);
		return ( $query->num_rows () > 0 ) ? TRUE : FALSE;
	}
	
	/**
	 * Returns the childs of a category
	 *
	 * @param int $cat_id - the categ. ID
	 * @return obj
	 */
	
	function get_childs_category ( $cat_id )
	{
		$query = $this->db->query 
		(
			'SELECT 
				*
			FROM 
				' . DBPREFIX . 'categories 
			WHERE 
				id_parent = ' . qstr ( $cat_id ) 
		);
		return $query->result ();
	}/**
	 * Updates a certain category
	 * @return bol
	 */
	
	function updateCatName ( $id, $title )
	{
		$query = "UPDATE " . DBPREFIX . "categories SET title = " . qstr ( $title ) . " WHERE ID = " . qstr ( $id );
		return ( $this->db->query ( $query ) ) ? TRUE : FALSE;
	}
	
	/**
	 * deletes a category
	 * @param $id
	 * return bol
	 */
	
	function delCategory ( $id )
	{
		$this->load->model ( 'mwallpaper' );
		//do we have childs?
                $category = $this->get_category_by_id ( $id );
                if ( $category != FALSE )
                {
                        $query = $this->get_subcategories ( $category->lft, $category->rgt );
        
                        if ( $query->num_rows () > 0 )
                        {
                                foreach ( $query->result () as $row ) 
                                {
                                        delete_wallpapers_by_category ( $row->ID );
                                        $this->delCategory ( $row->ID );
                                }
                        }

                        delete_wallpapers_by_category ( $id );
                        return ( $this->db->query ( "DELETE FROM " . DBPREFIX . "categories WHERE ID = " . qstr ( $id ) ) ) ? TRUE : FALSE; 
                }
                return FALSE;
	}
	
	/**
	 * returns all the categories
	 * @param none
	 * return obj/bol
	 */

	function getCats ()
	{
		$query = $this->db->query ( "SELECT * FROM " . DBPREFIX . "categories ORDER BY id_parent ASC" );
		return ( $query->num_rows () > 0 ) ? $query : FALSE;
	}
	
	function getCatsForSitemap ()
	{
		$query = $this->db->query ( "SELECT c.* FROM " . DBPREFIX . "categories c INNER JOIN " . DBPREFIX . "wallpapers w ON(w.cat_id=c.ID) GROUP BY c.ID ORDER BY c.id_parent ASC" );
		return ( $query->num_rows () > 0 ) ? $query : FALSE;
	}

	function get_cats4select ()
	{
		$query = $this->db->query ( "SELECT ID, id_parent, is_locked, title FROM " . DBPREFIX . "categories ORDER BY id_parent ASC" );
		return ( $query->num_rows () > 0 ) ? $query : FALSE;
	}
	
	function getCats_adv ()
	{
		$query = $this->db->query
		(
			"SELECT
                                " . DBPREFIX . "categories.ID,
				" . DBPREFIX . "categories.id_parent,
				COUNT(" . DBPREFIX . "wallpapers.ID) AS wallpapers,
                                (
                                        SELECT
                                                COUNT(subcats.ID)
                                        FROM
                                                " . DBPREFIX . "categories AS subcats
                                        WHERE
                                                (
                                                                subcats.lft > " . DBPREFIX . "categories.lft
                                                        AND
                                                                subcats.rgt < " . DBPREFIX . "categories.rgt
                                                )
                                ) As subcategories
                                
                        FROM
                                " . DBPREFIX . "categories
			LEFT JOIN
				" . DBPREFIX . "wallpapers
				ON
					(
							" . DBPREFIX . "wallpapers.cat_id = " . DBPREFIX . "categories.ID
						AND
							" . DBPREFIX . "wallpapers.parent_id = 0
					)
                        
			GROUP BY
				" . DBPREFIX . "categories.ID
			ORDER BY
				" . DBPREFIX . "categories.ID ASC"
		);
		return ( $query->num_rows () > 0 ) ? $query->result_array () : FALSE;
	}
	
	/**
	 * returns the number of subcategories in one category
	 * @param $id - parent id
	 * return obj/bol
	 */
	
	function get_cat_subcats_r ( $id )
	{
		$query = $this->db->query ( "SELECT ID FROM " . DBPREFIX . "categories WHERE id_parent = " . qstr ( $id ) );
		return $query->num_rows();
	}
	
	/**
	 * updates the counters of the categories
	 * @param $id - categ id
	 * @param $counter - the new counter
	 * return bol
	 */
	
	function update_counters ( $id, $wallpapers_counter, $subcats_counter, $issubcat )
	{
		return ( $this->db->query ( "UPDATE " . DBPREFIX . "categories SET subcats_counter = " . qstr ( $subcats_counter ) . ", items_counter = " . qstr ( $wallpapers_counter ) . ", issubcat = " . qstr ( $issubcat ) . " WHERE ID = " . qstr ( $id ) ) ) ? TRUE : FALSE;
	}
	
	/**
	 * updates categories and subcategories
	 * @param $id - cat id
	 * @param $issubcat - if is a subcategory than we put a sign '>' on it
	 * return bol
	 */
	
	function updateCats ( $id, $issubcat = FALSE )
	{
		if ( $issubcat )
		{
			$query = "UPDATE " . DBPREFIX . "categories SET issubcat = '>' WHERE ID = " . qstr ( $id );
		}
		else {
			$query = "UPDATE " . DBPREFIX . "categories SET issubcat = '' WHERE ID = " . qstr ( $id );
		}
		return ( $this->db->query ( $query ) ) ? TRUE : FALSE;
	}
	
	/**
	 * returns the categories by title to create the lang file
	 * @param none
	 * return obj
	 */
	
	function getUpdateCats ()
	{
		$query = $this->db->query ( "SELECT * FROM " . DBPREFIX . "categories ORDER BY title ASC" );
		return $query;
	}
	
	/**
	 * returns the total number of categories
	 * @param none
	 * return int
	 */
	
	function get_categories_number ()
	{
		$query = $this->db->query ( "SELECT ID FROM " . DBPREFIX . "categories" );
		return $query->num_rows();
	}
	
	/**
	 * inserts a new category
	 * @param $id_parent - parent ID/if any
	 * @param $newtitle - category title
	 * @param $order - it's order in the list
	 * return bol
	 */
	
	function insertCat ( $id_parent, $newtitle, $order )
	{
		$data = array
		(
				'id_parent'	=>	$id_parent,
				'title'		=>	$newtitle,
				'order1'	=>	$order
		);

		return ( $this->db->insert ( DBPREFIX . "categories", escape_arr ( $data ) ) ) ? $this->db->call_function ( 'insert_id' ) : FALSE;
	}
	
	/**
	 * returns the title of a given category
	 * @param $cat_id - category id
	 * @access private
	 * return string
	 */
	
	function get_cat_title ( $cat_id )
	{
		$query = $this->db->query ( "SELECT * FROM " . DBPREFIX . "categories WHERE ID = " . qstr ( $cat_id ) );
		if ( $query->num_rows () == 1 ) {
			$row = $query->row ();
			return $row->title;
		}
		return FALSE;
	}
	
	/**
	 * updates the additional options of a category (meta's and description)
	 * @access private
	 * return bol
	 */
	
	function save_cat_meta ( $id, $description, $meta_description, $meta_keywords )
	{
		return ( $this->db->query ( "UPDATE " . DBPREFIX . "categories SET description = " . qstr ( $description ) . ", meta_description = " . qstr ( $meta_description ) . ", meta_keywords = " . qstr ( $meta_keywords ) . " WHERE ID = " . qstr ( $id ) ) ) ? TRUE : FALSE;
	}
	
	/**
	 * returns the object array of a given category
	 * @param $cat_id - category id
	 * @access private
	 * return obj
	 */
	
	function get_category_by_id ( $cat_id )
	{
		$query = $this->db->query ( "SELECT * FROM " . DBPREFIX . "categories WHERE ID = " . qstr ( $cat_id ) );
		if ( $query->num_rows () == 1 ) {
			return $query->row ();
		}
		return FALSE;
	}

	function get_category_by_parent_and_title ( $parent_id, $title )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'categories WHERE id_parent = ' . qstr ( ( int ) $parent_id ) . ' AND `title` = ' . qstr ( $title ) );
		if ( $query->num_rows () == 1 ) {
			return $query->row ();
		}
		return FALSE;
	}

	function check_bulk_cat_duplicate ( $title, $parent, $cache_query = FALSE )
	{
		evaluate_cache ( $cache_query );
		$query = $this->db->query ( "SELECT * FROM " . DBPREFIX . "categories WHERE title = " . qstr ( $title ) . " AND id_parent = " . qstr ( $this->get_id_by_title  ( $parent ) ) );
		if ( $query->num_rows () == 1 ) {
			return $query->row ();
		}
		return FALSE;
	}
	
	function get_category_by_title  ( $title )
	{
		$query = $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'categories WHERE title = ' . qstr ( $title ) );
		if ( $query->num_rows () == 1 ) {
			return $query->result ();
		}
		return FALSE;
	}
	
	function get_id_by_title  ( $title )
	{
		$query = $this->db->query ( 'SELECT ID FROM ' . DBPREFIX . 'categories WHERE title = ' . qstr ( $title ) );
		if ( $query->num_rows () == 1 ) {
			$row = $query->row ();
			return $row->ID;
		}
		return FALSE;
	}
	
	/**
	 * selects all categories that are not parent
	 * @param
	 * return obj
	 */

	function selectSubcats ()
	{
		return $this->db->query ( "SELECT * FROM " . DBPREFIX . "categories WHERE id_parent != 0" );
	}

	/**
	 * selects categories by parent ID
	 * @param $parent_id - id of the parent
	 * return obj
	 */

	function get_categories_by_parent ( $parent_id )
	{
		return $this->db->query ( "SELECT * FROM " . DBPREFIX . "categories WHERE id_parent= " . qstr ( $parent_id ) . " ORDER BY order1 ASC" );
	}
	
	function get_subcategories ( $left, $right )
	{
		return $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'categories WHERE lft > ' . qstr ( $left ) . ' AND rgt < ' . qstr ( $right ) . ' ORDER BY order1 ASC' );
	}
	
	function get_parents ( $left, $right )
	{
		return $this->db->query ( 'SELECT * FROM ' . DBPREFIX . 'categories WHERE lft < ' . qstr ( $left ) . ' AND rgt > ' . qstr ( $right ) . ' ORDER BY lft ASC' );
	}
	
	/**
	 * selects top level categories
	 * return obj
	 */
	
	function get_top_level_categories ()
	{
		$query = $this->db->query ( "SELECT * FROM " . DBPREFIX . "categories WHERE id_parent= 0 ORDER BY order1 ASC" );
		return $query->result ();
	}
	
	/**
	 * updates the order of a category
	 * @param $order - the new order
	 * @param $cat_id - the cat id to be updated
	 * return obj
	 */
	
	function update_category_order ( $order, $cat_id )
	{
		return ( $this->db->query ( "UPDATE " . DBPREFIX . "categories SET order1 = " . qstr ( $order ) . " WHERE ID = " . qstr ( $cat_id ) ) ) ? TRUE : FALSE;
	}
	
	/**
	 * checks if a categ. exists or not
	 * @param
	 * return bol
	 */
	
	function cat_exists ( $id )
	{
		$query = $this->db->query ( "SELECT * FROM " . DBPREFIX . "categories WHERE ID = " . qstr ( $id ) );
		return ( $query->num_rows () > 0 ) ? TRUE : FALSE; 
	}

	/**
	 * locks a certain category
	 * @param cat $id
	 */

	function unlock_category ( $id )
	{
		$row = $this->get_category_by_id ( $id );
		$this->db->query ( "UPDATE " . DBPREFIX . "categories SET is_locked = 0 WHERE lft > " . qstr ( ( int ) $row->lft ) . " AND rgt < " . qstr ( ( int ) $row->rgt ) );
		return ( $this->db->query ( "UPDATE " . DBPREFIX . "categories SET is_locked = 0 WHERE ID = " . qstr ( $id ) ) ) ? TRUE : FALSE;
	}

	/**
	 * unlocks a certain category
	 * @param cat $id
	 */

	function lock_category ( $id )
	{
		$row = $this->get_category_by_id ( $id );
		$this->db->query ( "UPDATE " . DBPREFIX . "categories SET is_locked = 1 WHERE lft > " . qstr ( ( int ) $row->lft ) . " AND rgt < " . qstr ( ( int ) $row->rgt ) );
		return ( $this->db->query ( "UPDATE " . DBPREFIX . "categories SET is_locked = 1 WHERE ID = " . qstr ( $id ) ) ) ? TRUE : FALSE;
	}

	/**================================================================================================================
	 *	CATEGORIES END
	 *=================================================================================================================*/

}

//END