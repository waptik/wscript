<?php

class Welcome extends Controller {
	
	function __construct ()
	{
		parent::Controller ();
	}

	function index ()
	{
		$right = get_right_side_content ();
		
		switch ( WALLPAPER_DISPLAY_ORDER ) {
			case 'RAND()'	:
				$page_title = Lang ( 'random_wallpapers' );
				$title = 'random_wallpapers';
				break;
			case 'date_added'	:
				if ( WALLPAPER_ORDER_TYPE == 'DESC' ) {
					$page_title = Lang ( 'latest_wallpapers' );
					$title = 'latest_wallpapers';
				}
				else {
					$page_title = Lang ( 'oldest_wallpapers' );
					$title = 'oldest_wallpapers';
				}
				break;
			case 'hits'	:
				if ( WALLPAPER_ORDER_TYPE == 'DESC' ) {
					$page_title = Lang ( 'popular_wallpapers' );
					$title = 'popular_wallpapers';
				}
				else {
					$page_title = Lang ( 'less_popular_wallpapers' );
					$title = 'less_popular_wallpapers';
				}
				break;
			case 'rating'	:
				if ( WALLPAPER_ORDER_TYPE == 'DESC' ) {
					$page_title = Lang ( 'top_wallpapers' );
					$title = 'top_wallpapers';
				}
				else {
					$page_title = Lang ( 'worst_rated_wallpapers' );
					$title = 'worst_rated_wallpapers';
				}
				break;
		}

		$page = array
		(
			'content'	=> '',
			'right'		=> $right,
			'header_msg'	=> $page_title
		);

		$page = assign_global_variables ( $page, $title );		
		load_template ( $page, 'template', TRUE );
	}

	function change_display_type ()
	{
		$display_type = $this->uri->segment ( 3, TRUE );

		if ( ! in_array ( $display_type, array ( 'list', 'box' ) ) ) {
			$display_type = 'box';
		}

		setcookie ( "wallpaper_display_type", $display_type, now () + 86400, '/' );
		die ( print_unique_id () );
	}

	function get_wallpapers ()
	{
		$start = ( $this->uri->segment ( 5 ) ) ? $this->uri->segment ( 5 ) : '0';
		$type = ( $this->uri->segment ( 3 ) ) ? $this->uri->segment ( 3 ) : WALLPAPER_ORDER_TYPE;
		$order = ( $this->uri->segment ( 4 ) ) ? $this->uri->segment ( 4 ) : WALLPAPER_DISPLAY_ORDER;
		$query_type = $type;

		if ( ! in_array ( $type, array ( 'RAND()', 'date_added', 'hits', 'rating' ) ) ) {
			$type = 'date_added';
		}

		if ( ! in_array ( $order, array ( 'DESC', 'ASC' ) ) ) {
			$order = 'DESC';
		}
		
		if ( $query_type != 'RAND()' ) {
			$query_type = "w.$type";
		}

		$CI = &get_instance ();
		$CI->load->library ( 'pagination' );
		$CI->pagination->start = $start;
		$CI->pagination->is_ajax = TRUE;
		$CI->pagination->link_id = 'content';
		$CI->pagination->limit = get_wallpapers_per_page ();
		$CI->pagination->filePath = site_url ( "welcome/get_wallpapers/$type/$order" );

		$display_type = isset ( $_COOKIE [ 'wallpaper_display_type' ] ) ? $_COOKIE [ 'wallpaper_display_type' ] : 'box';
		if ( $display_type == 'list' ) {
			$this->pagination->select_what = 'w.*,u.Username';
		}
		else {
			$this->pagination->select_what = 'w.*';
		}

		if ( $display_type == 'list' ) {
			$this->pagination->the_table = DBPREFIX . 'wallpapers w LEFT JOIN ' . DBPREFIX . 'users u ON(u.ID=w.user_id)';
		}
		else {
			$this->pagination->the_table = DBPREFIX . 'wallpapers w';
		}

		$CI->pagination->add_query = ' WHERE w.active = 1 and w.parent_id = 0';
		$CI->pagination->add_query .= " ORDER BY $query_type $order";
		$query = $CI->pagination->getQuery ( TRUE );
		
		if ( $type != 'RAND()' ) {
			echo get_wallpapers ( $query ) . $CI->pagination->paginate ();die();
		}
		//	we don't need a pagination when showing random wallpapers
		echo get_wallpapers ( $query );die();
	}

	function latest ()
	{
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	'',
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'latest_wallpapers' )
		);

		$page = assign_global_variables ( $page, 'latest_wallpapers' );		
		load_template ( $page, 'template', TRUE );
	}

	function top ()
	{
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	'',
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'top_wallpapers' )
		);

		$page = assign_global_variables ( $page, 'top_wallpapers' );		
		load_template ( $page, 'template', TRUE );
	}

	function random ()
	{
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	'',
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'random_wallpapers' )
		);

		$page = assign_global_variables ( $page, 'random_wallpapers' );		
		load_template ( $page, 'template', TRUE );
	}

	function users_online ()
	{
		$content = get_online_users_table ();
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'welcome' )
		);

		$page = assign_global_variables ( $page, 'users_online' );		
		load_template ( $page, 'template', TRUE );
	}

	function load_frontpage_adverts ()
	{
		echo load_html_template ( array (), 'frontpage_adverts' );
	}

	function change_sidebar_sections () {
		setcookie ( "ws_right_sections", @serialize ( $_POST ), now () + 86400, '/' );
		die ( print_unique_id () );
	}
}

//END