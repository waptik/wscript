<?php
	function get_user_search_results ( $search_results )
	{
		$CI =& get_instance ();
		
		$tags = array
		( 
			'results' 	=> $search_results,
			'CI' 		=> $CI 
		);
		
		return load_form_template( $tags, 'users' );
	}
	
	function get_admin_add_user_form ()
	{
		$CI =& get_instance();
		
		$tags = array
		(			
			'groups' => get_groups (),
			'form_open' => form_open ( 'users/do_add_user', array ( 'id' => 'admin_add_user', 'class' => 'appnitro' ) ),
			'error_REDIRECT_AFTER_CONFIRMATION' => $CI->form_validation->printField_error ( 'REDIRECT_AFTER_CONFIRMATION' ),
			'value_user_group' => $CI->form_validation->getField_value ( 'user_group' ),
		);
		
		return load_form_template ( $tags, 'admin_add_user' );
	}

	function get_users_overview_table ()
	{
		return load_html_template ( array() , 'users_overview_table' );
	}
	
	function get_user_permissions_form ()
	{
		$CI =& get_instance();
		
		$tags = array
		(
			'form_open' => form_open ( 'users/save_user_permissions/' . $CI->uri->segment ( 3 ), array ( 'class' => 'appnitro' ) ),
			'site_sentry_isadmin' => $CI->site_sentry->isadmin ( $CI->uri->segment ( 3 ) ),
			'build_permissions_html_list' => build_permissions_html_list ( 'user', $CI->uri->segment ( 3 ), TRUE )
		);
		
		return load_form_template ( $tags, 'user_permissions' );
	}

	function get_users ( $query )
	{
		$CI =& get_instance();

		$tags = array
		(			
			'results'	=> $query->result (),
			'CI'		=> $CI
		);

		return load_form_template ( $tags, 'users' );
	}

	function get_contact_users_form ()
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );

		$tags = array
		(			
			'groups' => get_groups (),
			'form_open' => form_open ( 'users/do_contact_users', array ( 'id' => 'mass_email_form', 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) )
		);

		return load_form_template ( $tags, 'contact_users' );
	}

	function get_edit_user_form ( $row )
	{
		$CI =& get_instance ();
		$CI->load->helper ( 'form' );
		
		$tags = array
		(			
			'row' => $row,
			'groups' => get_groups(),
			'form_open' => form_open ( 'users/do_edit_user/' . $row->ID, array ( 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) ),
			'user_group' => $CI->form_validation->getField_value ( 'user_group' ),
		);
	
		return load_form_template ( $tags, 'edit_user_form' );
	}
	
	function get_edit_my_profile ( $row )
	{
		$CI =& get_instance ();
		$CI->load->helper ( 'form' );
		
		$tags = array
		(
			'row'		=> $row,
			'CI'		=> $CI,
			'form_open'	=> form_open ( 'members/do_update_profile', array ( 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) ),
		);
		
		return load_form_template( $tags, 'edit_my_profile' );
	}

//END