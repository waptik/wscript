<?php

class Colors extends Controller {
	
	function __construct () {
		parent::Controller ();
		$this->load->model ( 'mcolors' );
		$this->load->library ( 'Pagination' );
	}

	function browse () {
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	'',
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'browse_by_color' ) . ' #' . $this->uri->segment ( 3 )
		);

		$page = assign_global_variables ( $page, 'browse_by_color' );
		load_template ( $page, 'template', TRUE );
	}

	function fetch ()
	{
		$hex = $this->uri->segment ( 3 );
		$start = $this->uri->segment ( 4, 0 );

		$this->pagination->start = $start;
		$this->pagination->limit = get_wallpapers_per_page ();
		$this->pagination->is_ajax = TRUE;
		$this->pagination->link_id = 'wallpapers_wrapper';
		$this->pagination->filePath = site_url ( 'colors/fetch/' . $hex );
		$this->pagination->thequery = 'SELECT
							SQL_CALC_FOUND_ROWS DISTINCT w.*, u.Username
						FROM
							' . DBPREFIX . 'wallpapers w
						INNER JOIN
							' . DBPREFIX . 'colors_rel r
							ON
							(
								w.ID = r.item_id
							)
						INNER JOIN
							' . DBPREFIX . 'colors c
							ON
							(
									r.color_id = c.ID
								AND
									c.color = ' . qstr ( $hex ) . '
							)
						LEFT JOIN
							' . DBPREFIX . 'users u
							ON
							(
								u.ID = w.user_id
							)
						LIMIT
							' . qstr ( ( int ) $start ) . ', ' . get_wallpapers_per_page ();

		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();
		
		if ( $query->num_rows () > 0 )
		{
			echo get_wallpapers ( $query ) . $pagination;
		}
		else {
			echo evaluate_response ( 'info|' . Lang ( 'no_search_results' ) );
		}

		die ();
	}

	function update ()
	{
		$page = array
		(
			'page_title'	=> Lang ( 'colors_update' ),
			'styles'	=> get_page_css ( 'colors_update' ),
			'javascript'	=> get_page_js ( 'colors_update' ),
			'message'	=> evaluate_response ( 'ok|<h3>Colors updated successfully</h3>' )
		);

		if ( update_colors () ) {
			$page [ 'message' ] = evaluate_response ( 'info|<h3>Please stand by while we update the records</h3>' );
			load_template ( $page, 'generic' );
			redirect ( 'colors/update', 'refresh' );
		}

		load_template ( $page, 'generic' );
	}
	
}