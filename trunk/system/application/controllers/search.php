<?php

class Search extends Controller {

	function search ()
	{
		parent::Controller ();
		$this->load->library ( 'stemmer' );
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'stopwords' );
		$this->load->model ( 'msearch' );
		$this->load->model ( 'msearch_queries' );
	}

	function index ()
	{
		$content = '';
		$content .= get_search_form ();
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'search' )
		);		
		$page = assign_global_variables ( $page, 'search' );		
		load_template ( $page, 'template' );
	}

	function delete_searches_options ()
	{
		if ( $this->site_sentry->isadmin () ) {
			$interval = $this->input->post ( 'searches_options' );
			$this->msearch->delete_search_string_in_interval ( $interval );
		}

		exit;
	}

	function visitor_searches ()
	{
		if ( $this->site_sentry->isadmin () )
		{
			$this->load->library ( 'pagination' );
			$start = ( $this->uri->segment ( 3 ) ) ? $this->uri->segment ( 3 ) : '0';
			$this->pagination->start = $start;
			$this->pagination->limit = 20;
			$this->pagination->filePath = site_url ( 'search/visitor_searches' );
			$this->pagination->select_what = 'search_string, COUNT(*) as occurences';
			$this->pagination->the_table = DBPREFIX . 'searches';
			$this->pagination->add_query = ' GROUP BY search_string ORDER BY occurences DESC';
			
			$query = $this->pagination->getQuery ( TRUE );
			$pagination = $this->pagination->paginate ();
	
			if ( $query->num_rows () > 0 )
			{
				$content = '';
				$content .= '		<h3 class="headers gray">' . Lang ( 'visitor_searches' ) . '</h3>' . "\n";
				$content .= '		<div style="margin-top:20px">' . "\n";
				$content .= get_visitor_searches_table ( $query );
				$content .= '		</div>' . "\n";
				$content .= '		<div class="clear"></div>' . "\n";
				$content .= ( ! empty ( $pagination ) ) ? $pagination : '';
			}
			else {
				$content = evaluate_response ( 'info|' . Lang ( 'no_visitor_searches' ) );
			}
	
			$right = get_right_side_content ();
	
			$page = array
			(
				'content'	=>	$content,
				'right'		=>	$right,
				'header_msg'	=>	Lang ( 'visitor_searches' )
			);		
			$page = assign_global_variables ( $page, 'visitor_searches' );		
			load_template ( $page, 'template' );
		}
		else {
			redirect ( '' );
		}
	}

	function results ()
	{
		$_SESSION [ 'search_id' ] = 0;
		$query_id = FALSE;
		$q_pre = '';
		$search_string = $this->input->post ( 'search_for', TRUE );

		$this->form_validation->add_field ( 'search_for', 'required', Lang ( 'required' ) );
		if ( $this->form_validation->execute () )
		{
			$this->msearch->save_string ( $search_string );
			$filtered_string = ws_strtolower ( $search_string );
			$size = $this->input->post ( 'size' );

			if ( $size != 'all' && $size != FALSE )
			{
				$kt = explode ( 'X', $size );
				$height = $kt [ 1 ];
				$width = $kt [ 0 ];
			}
			else {
				$height = FALSE;
				$width = FALSE;
			}

			$category = $this->input->post ( 'category' );

			if ( $filtered_string != '' and ws_strlen ( $filtered_string ) >= 3 )
			{
				$stem = '';

				foreach ( array_unique ( ws_split ( " ", $filtered_string ) ) as $value ) {

					if ( ws_strlen ( $value ) >= 3 ) {
					       $stem .= '' . $this->stemmer->stem ( $value) . ' ';
					}
			       }

			       $stem = ws_substr ( $stem, 0, ( ws_strlen ( $stem ) - 1 ) );
			       if ( $stem != '' and ws_strlen ( $stem ) >= 3 )
			       {
				       $q_pre = $this->msearch->results ( $stem, $category, $height, $width );
				       $query_id = $this->msearch_queries->save ( $q_pre );
				       $_SESSION [ 'search_id' ] = $query_id;
			       }
			}
		}

		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	'',
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'search_results_for' ) . $search_string
		);

		$page = assign_global_variables ( $page, 'search_results' );
		load_template ( $page, 'template', TRUE );
	}

	function fetch ()
	{
		$this->load->library ( 'pagination' );
		$this->load->model ( 'msearch_queries' );
		$is_query_saved	= $this->uri->segment ( 4 );
		$start = $this->uri->segment ( 3, 0 );

		if ( $is_query_saved == FALSE ) {
			echo evaluate_response ( 'info|' . Lang ( 'no_search_results' ) );die ();
		}

		$q_pre = $this->msearch_queries->get ( $is_query_saved );

		if ( $q_pre == FALSE || $q_pre == '' ) {
			echo evaluate_response ( 'info|' . Lang ( 'no_search_results' ) );die ();
		}

		$query_id = $is_query_saved;

		$this->pagination->start = $start;
		$this->pagination->limit = get_wallpapers_per_page ();
		$this->pagination->is_ajax = TRUE;
		$this->pagination->link_id = 'content';
		$this->pagination->filePath = site_url ( 'search/fetch' );
		$this->pagination->thequery = stripslashes ( $q_pre ) . ' LIMIT ' . $start . ', ' . get_wallpapers_per_page ();
		$this->pagination->otherParams = '/' . $query_id;

		$query = $this->pagination->getQuery ( TRUE );

		if ( $query->num_rows () > 0 )
		{
			echo get_wallpapers ( $query ) . $this->pagination->paginate ();
		}
		else {
			echo evaluate_response ( 'info|' . Lang ( 'no_search_results' ) );
		}

		die ();
	}
}

//END