<?php

	function get_categories_list ( $categories )
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );

		$tags = array
		(
			'form_open'	=>	form_open ( 'categories/index/' . $CI->uri->segment ( 3, 0 ), array ( 'class' => 'appnitro', 'id' => 'add_categories' ) ),
			'categories'	=>	$categories,
			'no_categories'	=>	evaluate_response ( 'notice|' . Lang ( 'no_categories' ) ),
			'CI'		=> 	$CI,
			'add_fields_button'	=> __button ( Lang ( 'add_more_fields' ), 'gray', 'indicator', '', '', '10', '', array ( 'onclick' => 'ajax_add_inputs(\'add_categories\',\'' . site_url ( 'categories/add_more_inputs' ) . '\',\'inputs_receiver\' );return false;' ) )
		);
		
		return load_form_template ( $tags, 'show_categories' );
	}
	
	function get_categ_more_options_form ( $category )
	{
		$CI =& get_instance();
		$CI->load->helper ( 'form' );
		
		$tags = array
		(
			'form_open'	=>	form_open ( 'categories/do_edit_cat_meta/' . $category->ID, array ( 'id' => 'do_edit_cat_meta', 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) ),
			'category'	=>	$category,
			'CI'		=> 	$CI
		);
		
		return load_form_template ( $tags, 'edit_cat_meta' );
	}
        
        function migrate_wallpapers_form ( $category )
	{
		$CI =& get_instance ();
		$CI->load->helper ( 'form' );
		
		$tags = array
		(
			'form_open'	=>	form_open ( 'categories/do_migrate/' . $category->ID, array ( 'id' => 'do_migrate', 'class' => 'appnitro' ), array ( '_submit_check' => 1 ) ),
			'category'	=>	$category,
			'CI'		=> 	$CI,
                        'segment3'      =>      $CI->uri->segment(3)
		);
		
		return load_form_template ( $tags, 'migrate_wallpapers' );
	}

//END