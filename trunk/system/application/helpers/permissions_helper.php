<?php

	function build_permissions_html_list ( $type, $id )
	{
		$CI = &get_instance ();
		$tags = array
		(
			'perm_type'		=>	$type,
			'perm_id'		=>	$id,
			'permission_types'	=>	$CI->permissions->permissions,
		);

		return load_html_template ( $tags, 'permissions_html_list' );
	}
	
	function get_list_permissions ( $parents )
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );

		$tags = array
		(
			'form_open'	=> form_open ( 'site_permissions/manage_permissions', array ( 'class' => 'appnitro', 'onsubmit' => "return false;", 'id' => 'permissions_form' ) ),
			'parents_count'	=> $parents->num_rows (),
			'result'	=> $parents->result ()			
		);
		return load_html_template ( $tags, 'get_list_permissions' );
	}
	
	function get_add_permission_form ()
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );
		
		$tags = array
		(
			'form_open'			=>	form_open ( 'site_permissions/do_add_permission', array ( 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) ),		
		);
		
		return load_form_template ( $tags, 'add_permission' );
	}

	function get_edit_permission_form ( $permissions_list, $permission )
	{
		$CI =& get_instance ();
		$CI->load->helper ( 'form' );
		
		$tags = array
		(
			'form_open'		=> form_open ( 'site_permissions/do_edit_permission/' . $CI->uri->segment ( 3 ), array ( 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) ),
			'permission'		=> $permission,
			'permissions_list'	=> $permissions_list	
		);
		
		return load_form_template ( $tags, 'edit_permission' );
	}

//END