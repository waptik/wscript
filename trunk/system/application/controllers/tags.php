<?php

class Tags extends Controller {

	function tags ()
	{
		parent::Controller ();
		$this->load->model ( 'mtags' );
		$this->load->helper ( 'tags' );
	}

	function show ()
	{
		$right = get_right_side_content ();
		$tag = urldecode ( $this->uri->segment ( 3 ) );

		$page = array
		(
			'content'	=>	'',
			'right'		=>	$right,
			'header_msg'	=>	$tag . ' ' . Lang ( 'wallpapers' )
		);

		$page = assign_global_variables ( $page, 'tags' );

		load_template ( $page, 'template' );
	}
	
	function fetch ()
	{
		$this->load->library ( 'pagination' );
		$start = ( $this->uri->segment ( 4 ) ) ? $this->uri->segment ( 4 ) : '0';

		$tag = urldecode ( $this->uri->segment ( 3 ) );

		$this->pagination->start = $start;
		$this->pagination->limit = get_wallpapers_per_page ();
		$this->pagination->is_ajax = TRUE;
		$this->pagination->link_id = 'content';
		$this->pagination->filePath = site_url ( 'tags/fetch/' . $this->uri->segment ( 3 ) );

		$display_type = isset ( $_COOKIE [ 'wallpaper_display_type' ] ) ? $_COOKIE [ 'wallpaper_display_type' ] : 'box';
		if ( $display_type == 'list' ) {
			$this->pagination->select_what = 'w.*,u.Username';
		}
		else {
			$this->pagination->select_what = 'w.*';
		}

		if ( $display_type == 'list' ) {
			$this->pagination->the_table = DBPREFIX . 'wallpapers w INNER JOIN ' . DBPREFIX . 'tags_rel r ON(w.ID = r.item_id) INNER JOIN ' . DBPREFIX . 'tags t ON(t.ID=r.tag_id) LEFT JOIN ' . DBPREFIX . 'users u ON(u.ID=w.user_id)';
		}
		else {
			$this->pagination->the_table = DBPREFIX . 'wallpapers w INNER JOIN ' . DBPREFIX . 'tags_rel r ON(w.ID = r.item_id) INNER JOIN ' . DBPREFIX . 'tags t ON(t.ID=r.tag_id)';
		}
		
		$this->pagination->add_query = ' WHERE w.active = 1 AND w.parent_id = 0 AND (t.tag = ' . qstr ( $tag ) . ')';

		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();

		echo get_wallpapers ( $query ) . $pagination;die ();
	}

	function manage ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 40 ), TRUE );

		$this->load->helper ( 'form' );

		$this->load->library ( 'form_validation' );
		$this->load->library ( 'pagination' );
		$start			=	$this->uri->segment ( 3, 0 );	

		$tags = $this->input->post ( 'tags' );
		if ( $tags != FALSE ) {
			for ( $i = 0; $i < count ( $tags ); $i++ ) {
				if ( $tags [ $i ] != '' )
				{
					$this->mtags->add_exclusion ( $tags [ $i ] );
				}
			}
			update_tags ();
		}

		$this->pagination->start = $start;
		$this->pagination->limit = 15;
		$this->pagination->filePath = site_url ( 'tags/manage' );
		$this->pagination->select_what = '*';
		$this->pagination->the_table = '`' . DBPREFIX . 'tags`';
		$this->pagination->add_query = ' WHERE exclude=1 ORDER BY `ID` DESC';
		
		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();
		
		$content = '';
		$content .= evaluate_response ( 'info|' . Lang ( 'tags_exclusion_info' ) );
		$content .= get_tags_table ( $query );
		$content .= ( ! empty ( $pagination ) ) ? $pagination : '';
		$content .= '		<div style="margin-top:20px">' . "\n";
		$content .= get_add_tags_form ();
		$content .= '		</div>' . "\n";
		$right = get_right_side_content ();
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_tags' )
		);		
		$page = assign_global_variables ( $page, 'manage_tags' );		
		load_template ( $page, 'template' );
	}

	function options ()
	{
		$this->site_sentry->checklogin ();
		if ( numeric ( $this->uri->segment ( 4 ) ) && $this->uri->segment ( 3 ) != '' )
		{
			$ID = $this->uri->segment ( 4 );
			$action = $this->uri->segment ( 3 );

			switch ( $action )
			{			
				case 'delete':
					$row = $this->mtags->get_tag ( $ID );
					if ( $row != FALSE && $this->permissions->checkPermissions ( array ( 41 ) ) )
					{
						$this->mtags->delete_exclusion ( $ID );
					}
					redirect ( 'tags/manage', 'location' );
				break;
			}
		}
	}

	function update ()
	{
		if ( update_tags () ) {
			redirect ( 'tags/update', 'refresh' );
		}
		$page = array
		(
			'page_title'	=> Lang ( 'tag_cloud_update' ),
			'styles'	=> get_page_css ( 'tag_cloud_update' ),
			'javascript'	=> get_page_js ( 'tag_cloud_update' ),
			'message'	=> evaluate_response ( 'ok|<h3>Tags updated successfully</h3>' )
		);

		load_template ( $page, 'generic' );
	}
}

//END