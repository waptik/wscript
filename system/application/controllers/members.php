<?php

class Members extends Controller {

	public $message = '';

	function __construct ()
	{
		parent::Controller ();
		$this->load->model ( 'musers' );
		$this->load->helper ( 'wallpapers' );
		$this->load->library ( 'form_validation' );
		$this->load->helper ( 'users' );
	}

	function show ()
	{
		$member_id = $this->uri->segment ( 3, 1 );
		$member = $this->musers->get_member_advanced ( $member_id );

		$content = get_member_details ( $member );

		$right = get_right_side_content ();

		$page = array
		(
			'content'	=> $content,
			'right'		=> $right,
			'member'	=> $member,
			'header_msg'	=> $member->Username . "'s " . Lang ( 'profile' )
		);

		$page = assign_global_variables ( $page, 'member_wallpapers' );		
		load_template ( $page, 'template' );
	}

	function fetch_wallpapers ()
	{
		$member_id = $this->uri->segment ( 3, 1 );

		$this->load->library ( 'pagination' );
		$start = $this->uri->segment ( 4, 0 );

		$this->pagination->start = $start;
		$this->pagination->limit = get_wallpapers_per_page ();
		$this->pagination->is_ajax = TRUE;
		$this->pagination->link_id = 'wallpapers_wrapper';

		$this->pagination->filePath = site_url ( 'members/fetch_wallpapers/' . $member_id );

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

		$this->pagination->add_query = ' WHERE w.user_id = ' . qstr ( ( int ) $member_id ) . ' AND w.active = 1 and w.parent_id = 0';
		$this->pagination->add_query .= ' ORDER BY w.date_added DESC';

		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();

		echo get_wallpapers ( $query ) . $pagination;die ();
	}

	function index ()
	{
		$this->site_sentry->checklogin ();

		$status	= ( numeric ( $this->uri->segment ( 3 ) ) ) ? $this->uri->segment ( 3 ) : '1';
		$start	= ( $this->uri->segment ( 4 ) ) ? $this->uri->segment ( 4 ) : '0';

		$this->load->library ( 'form_validation' );
		$this->load->library ( 'pagination' );
                $this->load->model ( 'msearch_queries' );

                $title_filter = $this->input->post ( 'title_filter' );
                $is_query_saved = ( isset ( $_SESSION [ 'saved_query' ] )  && $title_filter == FALSE ) ? $_SESSION [ 'saved_query' ] : FALSE;

                //	you need to reset the session from login controler after member login
                //	otherwise we will have an error here
                if ( $is_query_saved )
                {
                        $q_pre = $this->msearch_queries->get ( $is_query_saved );
                }
                else {
                        $q_pre = 'SELECT
                        		SQL_CALC_FOUND_ROWS 
                        		w.*
                        	FROM
                        		' . DBPREFIX . 'wallpapers w
                        	WHERE
                                        w.parent_id = 0';

                        if ( $title_filter != FALSE )
                        {
                                $q_pre .= '     AND
                                                        w.file_title
                                                LIKE
                                                        "%' . strip_punctuation ( $title_filter ) . '%"';
                        }

                        $_SESSION [ 'saved_query' ] = $this->msearch_queries->save ( $q_pre );
                }
		
		$q_pre .=  ' AND w.user_id = ' . qstr ( ( int ) get_mem_info ( 'ID' ) );

		$q_pre .=  '    AND w.active = ' . qstr ( ( int ) $status );
		$q_pre .= "     GROUP BY w.ID ORDER BY w.date_added DESC";

		$this->pagination->start = $start;
		$this->pagination->filePath = site_url ( 'members/index/' . $status );
		$this->pagination->thequery = stripslashes ( $q_pre ) . " LIMIT $start, 20";

		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();

		$content = get_member_wallpapers_overview_table ( get_mem_info ( 'ID' ) );
		$content .= '		<div style="margin-top:20px">' . "\n";
		$content .= get_member_wallpapers_table ( $query, $status );
		$content .= '		</div>' . "\n";
		$content .= ( ! empty ( $pagination ) ) ? $pagination : '';
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_wallpapers' )
		);

		$page = assign_global_variables ( $page, 'manage_wallpapers' );
		load_template ( $page, 'template' );
	}

	function edit_profile ()
	{
		$this->site_sentry->checklogin ();
		$row = $this->musers->get_member_by_id ( $this->session->userdata ( AUTH_SESSION_ID ) );		
		$content = '';
		$content .= get_edit_my_profile ( $row );		
		$right = get_right_side_content ();
		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'update_profile' )
		);
		
		$page = assign_global_variables ( $page, 'update_profile' );		
		load_template ( $page, 'template' );
	}

	function do_update_profile ()
	{
		$this->site_sentry->checklogin ();
		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		$row = $this->musers->get_member_by_id ( $this->session->userdata ( AUTH_SESSION_ID ) );
		$msg = 'error|' . Lang ( 'profile_n_updated' );

		if ( $_submit_check != FALSE )
		{
			$username = ( ALLOW_USERNAME_CHANGE ) ? $this->input->post ( 'username', TRUE ) : $row->Username;
			$password = $this->input->post ( 'password', TRUE );
			$email = $this->input->post ( 'email', TRUE );

			if ( ALLOW_USERNAME_CHANGE ) {
				$this->form_validation->add_field ( 'username', 'required', Lang ( 'username_req' ) );
			}

			if ( ! empty ( $password ) ) {			
				$this->form_validation->add_field ( 'password_confirmed', 'matches[password]', Lang ( 'pass_must_match' ) );
			}

			$this->form_validation->add_field ( 'email', 'required', Lang ( 'email_req' ) );
			$this->form_validation->add_field ( 'email', 'valid_email', Lang ( 'valid_email' ) );

			if ( $this->form_validation->execute () )
			{
				if ( $this->musers->update_member ( $row->ID, $username, $password, $row->Level_access, $email, $row->auto_approve ) )
				{
					$msg = 'ok|' . Lang ( 'profile_updated' );
				}
			}
		}

		$content = '';
		if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}

		$content .= get_edit_my_profile ( $row );

		$right = get_right_side_content ();

		$page = array
		(
			'content'	=> $content,
			'right'		=> $right,
			'header_msg'	=> Lang ( 'update_profile' )
		);

		$page = assign_global_variables ( $page, 'update_profile' );
		load_template ( $page, 'template' );
	}
}
//END