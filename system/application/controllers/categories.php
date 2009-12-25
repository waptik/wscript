<?php

class Categories extends Controller {

	function categories ()
	{
		parent::Controller ();
		$this->load->model ( 'master' );
		$this->load->model ( 'mcategories' );
		$this->load->helper ( 'categories' );
		$this->load->library ( 'pagination' );
		$this->load->library ( 'form_validation' );
		$this->load->helper ( 'form' );
	}

	function fetch ()
	{
		echo show_cats ( $this->uri->segment ( 3, 0 ), CATEGORY_COLUMNS, $this->uri->segment ( 4, 0 ) );die ();
	}

	function fetchFront ()
	{
		echo show_cats ( $this->uri->segment ( 3, 0 ), CATEGORY_COLUMNS, $this->uri->segment ( 4, 0 ), 21, 'fcw', 'fetchFront' );die ();
	}

	function show ()
	{
		$right = get_right_side_content ();
		$category = get_category ( $this->uri->segment ( 3 ) );

		$page = array
		(
			'content'	=>	load_html_template
						(
							array
							(
								'breadcrumb'	=> '',
								'category'	=> $category
							), 'category_details'
						),
			'right'		=>	$right,
			'category'	=>	$category,
			'header_msg'	=>	breadcrumb ( $this->uri->segment ( 3 ), TRUE )
		);

		$page = assign_global_variables ( $page, 'categories' );

		load_template ( $page, 'template', TRUE );
	}

	function add_more_inputs ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 23, 25 ), TRUE );

		$fields_nr = $this->input->post ( 'fields_nr' );
		$out = '';
		for ( $i = 0; $i < $fields_nr; $i++ )
		{
			$out .= '	<li>' . "\n";
			$out .= '		<div align="left">' . "\n";
			$out .= '			<input name="newtitle[]" type="text" id="newtitle[]"  class="element text large" />' . "\n";
			$out .= '		</div>' . "\n";
			$out .= '	</li>' . "\n";
		}
		
		die ( $out );
	}
	
	function index ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 23 ), TRUE );
		
		$id_parent = $this->uri->segment ( 3, 0 );

		if ( ! empty ( $_POST ) )
		{
			$order1 = $this->mcategories->get_categories_number () + 1;
			$title = $this->input->post ( 'newtitle' );
			$name = $this->input->post ( 'name' );
			$id = $this->input->post ( 'id' );

			for ( $i = 0; $i < count ( $name ); $i++ )
			{
				$this->mcategories->updateCatName ( $id [ $i ], $name  [ $i ] );
				$this->mcategories->update_category_order ( $i, $id [ $i ] );
			}

			for ( $i = 0; $i < count ( $title ); $i++)
			{
				if ( $title [ $i ] != '' )
				{
					$this->mcategories->insertCat ( $id_parent, $title [ $i ], $order1 );
					$order1++;
				}
			}

			global_reset_categories ();
		}

		$categories = $this->mcategories->get_categories_by_parent ( $id_parent );	

		$content = '';
		if ( $categories->num_rows () ) {
			$content .= get_admin_breadcrumb ( $id_parent, TRUE );
		}

		$content .= get_categories_list ( $categories );

		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'edit_categories' )
		);

		$page = assign_global_variables ( $page, 'admin_categories' );

		load_template ( $page, 'template' );
	}

	function update_cats_order ()
	{
		if ( $this->input->post ( 'sort_order' ) != FALSE && $this->permissions->checkPermissions ( array ( 25 ) ) ) {
			$item_order = 1;
			$order_array = explode ( ",", urldecode ( $this->input->post ( 'sort_order' ) ) );
			foreach ( $order_array as $order ) {
				$this->mcategories->update_category_order ( $item_order, $order );
				$item_order++;
			}
		}
	}

        function migrate ()
        {
                $this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 25 ), TRUE );
		
		$category = $this->mcategories->get_category_by_id ( $this->uri->segment ( 3 ) );
		$page = array
		(
				'page_title'			=>	Lang ( 'migrate_wallpapers' ),
				'styles'			=>	get_page_css ( 'admin' ),
				'javascript'			=>	get_page_js ( 'migrate_wallpapers' ),
				'get_categ_more_options_form'	=>	migrate_wallpapers_form ( $category ),
				'message'			=>	''
		);
		
		load_template ( $page, 'manage_categories_options' );
        }

        function do_migrate ()
        {
                $this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 25 ), TRUE );
                
                $this->load->model ( 'mwallpaper' );
                $this->load->library ( 'form_validation' );
		
                $from = $this->input->post ( 'from_cat' );
                $to = $this->input->post ( 'to_cat' );
                
                $this->form_validation->add_field ( 'from_cat', 'required', Lang ( 'required' ) );
		$this->form_validation->add_field ( 'from_cat', 'numeric', Lang ( 'numeric' ) );
                        
                $this->form_validation->add_field ( 'to_cat', 'required', Lang ( 'required' ) );
		$this->form_validation->add_field ( 'to_cat', 'numeric', Lang ( 'numeric' ) );
                
                if ( $this->form_validation->execute () )
		{
                        if ( $this->mwallpaper->migrate_wallpapers ( $from, $to ) )
                        {
                                $msg = evaluate_response ( 'ok|' . Lang ( 'migration_success' ) );
                                global_reset_categories ();
                        }
                        else {
                                $msg = evaluate_response ( 'error|' . Lang ( 'migration_failed' ) );
                        }
                }

		$category = $this->mcategories->get_category_by_id ( $this->uri->segment ( 3 ) );
		$page = array
		(
				'page_title'			=>	Lang ( 'migrate_wallpapers' ),
				'styles'			=>	get_page_css ( 'admin' ),
				'javascript'			=>	get_page_js ( 'migrate_wallpapers' ),
				'get_categ_more_options_form'	=>	migrate_wallpapers_form ( $category ),
				'message'			=>	isset ( $msg ) ? $msg : ''
		);

		load_template ( $page, 'manage_categories_options' );
        }

	function edit_cat_meta ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 25 ), TRUE );

		$category = $this->mcategories->get_category_by_id ( $this->uri->segment ( 3 ) );
		$page = array
		(
				'page_title'			=>	Lang ( 'edit_categories' ),
				'styles'			=>	get_page_css ( 'admin' ),
				'javascript'			=>	get_page_js ( 'edit_cat_meta' ),
				'get_categ_more_options_form'	=>	get_categ_more_options_form ( $category ),
				'message'			=>	''
		);

		load_template ( $page, 'manage_categories_options' );
	}
	
	function do_edit_cat_meta ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 25 ), TRUE );
		
		$category = $this->mcategories->get_category_by_id ( $this->uri->segment ( 3 ) );
		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		if ( $_submit_check != FALSE )
		{
			$description = $this->input->post ( 'description', TRUE );
			$meta_description = $this->input->post ( 'meta_description', TRUE );
			$meta_keywords = $this->input->post ( 'meta_keywords', TRUE );
			
			if ( $this->mcategories->save_cat_meta ( $category->ID, $description, $meta_description, $meta_keywords ) )
			{
				$msg = 'ok|' . Lang ( 'cat_meta_updated' );
			}
			else {
				$msg = 'error|' . Lang ( 'cat_meta_n_updated' );
			}
		}

		$page = array
		(
				'page_title'			=>	Lang ( 'edit_categories' ),
				'styles'			=>	get_page_css ( 'admin' ),
				'javascript'			=>	get_page_js ( 'edit_cat_meta' ),
				'get_categ_more_options_form'	=>	get_categ_more_options_form ( $category )
		);
		
		if ( isset ( $msg ) ) {
			$page [ 'message' ] = evaluate_response ( $msg );
		}
		else {
			$page [ 'message' ] = '';
		}
		
		load_template ( $page, 'manage_categories_options' );
	}
	
	function options ()
	{
		$this->site_sentry->checklogin ();
		$action = $this->uri->segment ( 4 );
		$id = $this->uri->segment ( 3 );
		$referer = @$_SERVER [ 'HTTP_REFERER' ];
		if ( $referer == '' ) {
			$referer = site_url ( 'categories/index' );
		}

		switch ( $action )
		{
			case 'delete'	:
				$this->permissions->checkPermissions ( array ( 26 ), TRUE );
				ini_set ( "max_execution_time", 500 );//we might have a lot of wallpapers added here
				$this->mcategories->delCategory ( $id );
				global_reset_categories ();
			break;

			case 'lock'	:
				$this->permissions->checkPermissions ( array ( 27 ), TRUE );

				$this->mcategories->lock_category ( $id );
				global_reset_categories ();
			break;
			
			case 'unlock'	:
				$this->permissions->checkPermissions ( array ( 28 ), TRUE );
				
				$this->mcategories->unlock_category ( $id );
				global_reset_categories ();
			break;
		}
		
		header ( "Location: $referer" );
	}
	
}
//END