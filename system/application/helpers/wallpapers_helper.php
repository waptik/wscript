<?php

	function get_wallpapers_overview_table ()
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );		
		$tags = array ();		
		return load_html_template ( $tags, 'wallpapers_overview_table' );
	}
	
	function get_member_wallpapers_overview_table ( $member_id )
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );		
		$tags = array ( 'member_id' => $member_id );		
		return load_html_template ( $tags, 'member_wallpapers_overview_table' );
	}

	function get_bulk_form ()
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );

		$options = array ();

		$tags = array
		(
			'form_open'	=>	form_open ( 'wallpapers/do_bulk', array ( 'id' => 'bulk_insert_form', 'class' => 'appnitro', 'enctype' => "multipart/form-data" ), array ( '_submit_check' => 1 ) ),
			'CI'		=>	$CI,
			'post_value'	=>	( $CI->form_validation->getField_value ( 'cat_id' ) ) ? $CI->form_validation->getField_value ( 'cat_id' ) : '',
			'options'	=>	$options
		);
		
		return load_form_template ( $tags, 'bulk_form' );
	}
        
        function get_bulk_edit_form ()
        {
		$tags = array ( 'button' => __button ( Lang ( 'search' ), '', '', 'FFFFFF', 'AlteHaasGroteskBold', 10, 'submit', array ( 'onclick' => 'ajax_form(\'filter_bulk_edit\',\'' . site_url ( 'wallpapers/filter_bulk_edit') . '\',\'response\')' ) ));
		return load_form_template ( $tags, 'bulk_edit_form', TRUE, 0 );
        }

	function get_wallpapers_table ( $query, $status )
	{
		$CI =& get_instance();
		
		foreach ( $query->result () as $row )
		{
			make_thumb_if_not_exists ( $row );
		}		
		
		$tags = array
		(
			'CI'		=> $CI,
			'query'		=> $query->result (),
			'status'	=> $status
			
		);

		return load_html_template ( $tags, 'wallpapers_table' );
	}
	
	function get_bulk_edit_wallpapers_table ( $query )
	{
		$CI =& get_instance();
		
		foreach ( $query->result () as $row )
		{
			make_thumb_if_not_exists ( $row );
		}		

		$tags = array
		(
			'CI'		=> $CI,
			'query'		=> $query->result ()
			
		);

		return load_html_template ( $tags, 'bulk_edit_wallpapers_table' );
	}

	function get_member_wallpapers_table ( $query, $status )
	{
		$CI =& get_instance();
		
		foreach ( $query->result () as $row )
		{
			make_thumb_if_not_exists ( $row );
		}		

		$tags = array
		(
			'CI'		=> $CI,
			'query'		=> $query->result (),
			'status'	=> $status
			
		);

		return load_html_template ( $tags, 'member_wallpapers_table' );
	}

	function get_add_wallpapers_form ()
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );		
		$tags = array
		(
			'form_open'	=> form_open ( 'wallpapers/add_wallpaper', array ( 'class' => 'appnitro', 'enctype' => "multipart/form-data" ), array ( '_submit_check' => 1 ) ),
			'post_value'	=> ( $CI->form_validation->getField_value ( 'cat_id' ) ) ? $CI->form_validation->getField_value ( 'cat_id' ) : ''
		);		
		return load_form_template ( $tags, 'add_wallpaper' );
	}

	function get_edit_wallpapers_form ( $row )
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );
		$CI->load->model ( 'mtags' );
		$row->tags = '';
		
		$tags = $CI->mtags->get_wallpaper_tags ( $row->ID );
		if ( $tags != FALSE ) {
			foreach ( $tags as $tag ) {
				$row->tags .= "$tag->tag, ";
			}
		}
		
		$row->tags = substr ( $row->tags, 0, -2 );

		$tags = array
		(
			'form_open'	=> form_open ( 'wallpapers/do_edit/' . $row->ID, array ( 'class' => 'appnitro', 'enctype' => "multipart/form-data" ), array ( '_submit_check' => 1 ) ),
			'post_value'	=> ( $CI->form_validation->getField_value ( 'cat_id' ) ) ? $CI->form_validation->getField_value ( 'cat_id' ) : $row->cat_id,
			'row'		=> $row,
			
		);		
		return load_form_template ( $tags, 'edit_wallpaper' );
	}

        function get_quick_edit_wallpapers_form ( $row )
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );
		$post_value = ( $CI->form_validation->getField_value ( 'cat_id' ) ) ? $CI->form_validation->getField_value ( 'cat_id' ) : $row->cat_id;

		$tags = array
		(
			'form_open'	=> form_open ( 'wallpapers/do_quick_edit/' . $row->ID, array ( 'class' => 'appnitro', 'onsubmit' => 'return false', 'id' => 'quick_edit_wallpaper' ), array ( '_submit_check' => 1 ) ),
			'row'		=> $row,
			'categs_select'	=> get_grant_categs_select ( 'cat_id' )
		);		
		return load_form_template ( $tags, 'quick_edit_wallpaper', TRUE, 0 );
	}

//END