<?php	
	function get_group_permissions_form ()
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );
		$tags = array
		(
			'CI' => $CI
		);
		
		return load_form_template ( $tags, 'group_permissions' );
	}
	
	function get_groups_list ( $query )
	{
		$CI =& get_instance();
		$tags = array
		(
			'result'=>	$query->result (),
			'CI'	=>	$CI
		);		
		return load_form_template ( $tags, 'get_groups_list' );
	}
	
	function add_group_form ()
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );
		$tags = array
		(
			'form_open'	=>	form_open ( 'user_groups/add_user_groups', array ( 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) ),
		);		
		return load_form_template ( $tags, 'add_group' );
	}
	
	function edit_group_form ( $row )
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );
		$tags = array
		(
			'form_open'	=>	form_open ( 'user_groups/do_edit_user_group/' . $CI->uri->segment ( 3 ), array ( 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) ),
			'row'		=>	$row
		);		
		return load_form_template ( $tags, 'edit_group' );
	}
//END