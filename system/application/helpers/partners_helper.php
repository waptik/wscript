<?php

	function add_partner_form ()
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );

		$tags = array
		(
			'form_open'	=>	form_open ( 'partners/add_partner', array ( 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) ),
		);
		
		return load_form_template ( $tags, 'add_partner' );
	}
	
	function edit_partner_form ( $id )
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );
		
		$tags = array
		(
			'form_open'	=>	form_open ( 'partners/do_edit_partner/' . $id, array ( 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) ),
			'row'		=>	get_partner ( $id )
		);
		
		return load_form_template ( $tags, 'edit_partner' );
	}
	
	function get_partners_list ( $query )
	{
		$CI =& get_instance();
		
		$tags = array
		(
			'query'		=>	$query->result ()
		);
		
		return load_html_template ( $tags, 'partners_list' );
	}

//END