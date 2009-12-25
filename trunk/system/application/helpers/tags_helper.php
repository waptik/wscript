<?php

	function get_tags_table ( $query )
	{
		$CI =& get_instance();
		
		$tags = array
		(
			'query'	=> $query->result (),
			'CI'	=> $CI
		);
		
		return load_html_template ( $tags, 'tags_table' );
	}
	
	function get_add_tags_form ()
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );
		
		$tags = array
		(
			'form_open'	=> form_open ( 'tags/manage', array ( 'class' => 'appnitro', 'id' => 'add_tags_exclusion' ), array ( '_submit_check' => 1 ) )
		);
		
		return load_form_template ( $tags, 'add_tags' );
	}

//END