<?php

class Users extends Controller {

	function __construct ()
	{
		parent::Controller ();
		$this->load->helper ( 'users_helper' );
		$this->load->model ( 'musers' );
		$this->load->library ( 'pagination' );
		$this->load->library ( 'form_validation' );
		$this->load->helper ( 'form' );
	}

	function manage_users ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 9 ), TRUE );
		$this->load->model ( 'msearch_queries' );

		$limit = 20;
		$username = $this->input->post ( 'user_filter', TRUE );
		$email = $this->input->post ( 'email_filter', TRUE );
		$group = $this->input->post ( 'groups', TRUE );
		$status	= $this->uri->segment ( 3, 1 );
		$start	= ( $this->uri->segment ( 4 ) ) ? $this->uri->segment ( 4 ) : '0';
		$query_id = ( $this->uri->segment ( 5 ) ) ? $this->uri->segment ( 5 ) : FALSE;

		if ( ! $query_id ) {
			$query = 'SELECT 
					SQL_CALC_FOUND_ROWS
					u.ID, u.Username, u.Active,
					u.Email,
					g.title 
				FROM ' . DBPREFIX . 'users u 
				LEFT JOIN 
				' . DBPREFIX . 'groups g 
					ON(u.Level_access=g.ID) WHERE u.Active = ' . qstr ( ( int ) $status );

			if ( $group != FALSE ) {
				$query .= ' AND u.Level_access = ' . qstr ( ( int ) $group );
			}

			if ( $username != FALSE ) {
				$query .= ' AND Username LIKE ' . qstr ( '%' . $username . '%' );
			}

			if ( $email != FALSE ) {
				$query .= ' AND Email LIKE ' . qstr ( '%' . $email . '%' );
			}

			$query .= ' ORDER BY u.ID DESC LIMIT ' . qstr ( ( int ) $start ) . ', ' . qstr ( ( int ) $limit );

			if ( ! empty ( $_POST ) ) {
				$query_id = $this->msearch_queries->save ( $query );
			}
		}
		else {
			$query = $this->msearch_queries->get ( $query_id );
		}

		$this->pagination->filePath = site_url ( 'users/manage_users/' . $status );
		$this->pagination->limit = $limit;
		$this->pagination->start = $start;
		$this->pagination->thequery = $query;
		$this->pagination->otherParams = '/' . $query_id;

		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();		

		$content = get_users_overview_table() . get_users ( $query ) . $pagination;
		
		$right = get_right_side_content ();
		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_users' )
		);
		
		$page = assign_global_variables ( $page, 'manage_users' );
		
		load_template ( $page, 'template' );
	}
        
        function user_suggest ()
	{
		$this->site_sentry->checklogin ();
		if ( ! $this->permissions->checkPermissions ( array ( 9 ) ) ) {
			exit;
		}

		$user = $this->input->post ( 'q' );
		$users = array ();
		$query = $this->db->query ( 'SELECT DISTINCT Username FROM ' . DBPREFIX . 'users WHERE LOWER(Username) LIKE ' . qstr ( '%' . ws_strtolower ( $user ) . '%' ) );
		foreach ( $query->result () as $row )
		{
			$users [] = $row->Username;
		}
		echo implode ( "\n", $users ); die ();
	}
        
        function email_suggest ()
	{
		$this->site_sentry->checklogin ();
		if ( ! $this->permissions->checkPermissions ( array ( 9 ) ) ) {
			exit;
		}

		$user = $this->input->post ( 'q' );
		$users = array ();
		$query = $this->db->query ( 'SELECT DISTINCT Email FROM ' . DBPREFIX . 'users WHERE LOWER(Email) LIKE ' . qstr ( '%' . ws_strtolower ( $user ) . '%' ) );
		foreach ( $query->result () as $row )
		{
			$users [] = $row->Email;
		}
		echo implode ( "\n", $users ); die ();
	}

	function edit_user ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 11 ), TRUE );

		$row = $this->musers->get_member_by_id ( $this->uri->segment ( 3 ) );		
		$content = '';
		$content .= get_edit_user_form ( $row );		
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	ucfirst ( ws_strtolower ( Lang ( 'update' ) . ' ' . get_username ( $row->ID ) . '\'s ' . Lang ( 'profile' ) ) )
		);

		$page = assign_global_variables ( $page, 'edit_user' );		
		load_template ( $page, 'template' );
	}

	function admin_contact_users ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 37 ), TRUE );

		$page = array
		(
				'page_title'		=>	Lang ( 'manage_users' ),
				'styles'		=>	get_page_css ( 'admin' ),
				'javascript'		=>	get_page_js ( 'admin' ),
				'contact_users_form'	=>	get_contact_users_form (),
				'message'		=>	''
		);
		
		load_template ( $page, 'contact_users' );
	}

	function add_user ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 10 ), TRUE );

		$page = array
		(
				'page_title'		=>	Lang ( 'manage_users' ),
				'styles'		=>	get_page_css ( 'manage_users' ),
				'javascript'		=>	get_page_js ( 'manage_users' ),
				'admin_add_user_form'	=>	get_admin_add_user_form (),
				'message'		=>	''
		);
		
		load_template ( $page, 'add_user' );
	}

	function user_search ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 9 ), TRUE );

		$search_results = $this->musers->get_user_search_results ( $this->input->post ( 'username' ), $this->input->post ( 'email' ) );
		$content = '';
		$content .= get_user_search_results ( $search_results );
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'user_search' )
		);
		
		$page = assign_global_variables ( $page, 'user_search' );
		
		load_template ( $page, 'template' );
	}

	function save_user_permissions ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 32 ), TRUE );

		$this->load->model ( 'mpermissions' );
		if ( ! $this->site_sentry->isadmin ( $this->uri->segment ( 3 ) ) )
		{
			$array = array ();
			$parents = $this->mpermissions->get_parent_permissions ();
			//clean the existing user permissions, we have new ones
			$this->mpermissions->delete_all_permissions ( $this->uri->segment ( 3 ), 'user' );
			
			foreach ( $_POST [ 'setting' ] as $parent => $childs )
			{
				foreach ( $childs as $key => $value )
				{
					if ( $value == 'y' && numeric ( $key ) )
					{				
						//build the array with positives
						$array [ $parent ] [] = $key;
					}
				}
			}
			
			if ( count ( $array ) > 0 )
			{
				foreach ( $array as $parent => $childs )
				{
					$posted = $array [ $parent ];
					$dbase = get_child_permissions_array ( $parent );
					
					sort ( $posted, SORT_NUMERIC );
					sort ( $dbase, SORT_NUMERIC );
		
					if ( $posted == $dbase )
					{
						$this->mpermissions->add_permission ( $this->uri->segment ( 3 ), $parent, 'user'  );
					}
					else {
						foreach ( $childs as $child )
						{
							$this->mpermissions->add_permission ( $this->uri->segment ( 3 ), $child, 'user'  );
						}
					}
				}
			}
		}
		clear_cache ();
		redirect ( 'users/manage_users', 'location' );	
	}

	// ------------------------------------------------------------------------
	
	/**
	 * do_edit_user
	 *
	 * Covers the process needed to update a member's profile / admin level required
	 *
	 * @access	public
	 * @param	array via $_POST
	 * @return
	 */ 

	function do_edit_user ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 11 ), TRUE );

		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		$row = $this->musers->get_member_by_id ( $this->uri->segment ( 3 ) );
		if ( $_submit_check != FALSE )
		{
			$username = $this->input->post ( 'username', TRUE );
			$password = $this->input->post ( 'password', TRUE );
			$password_confirmed = $this->input->post ( 'password_confirmed', TRUE );
			$email = $this->input->post ( 'email', TRUE );
			$user_group = $this->input->post ( 'user_group', TRUE );
			$auto_approve = $this->input->post ( 'auto_approve', TRUE );

			$this->form_validation->add_field ( 'username', 'required', Lang ( 'username_req' ) );
			$this->form_validation->add_field ( 'username', 'alpha_numeric', Lang ( 'username_alpha' ) );
			$this->form_validation->add_field ( 'auto_approve', 'required', Lang ( 'required' ) );
			
			if ( ! empty ( $password ) ) {
				$this->form_validation->add_field ( 'password', 'alpha_numeric', Lang ( 'password_alpha' ) );			
				$this->form_validation->add_field ( 'password_confirmed', 'matches[password]', Lang ( 'pass_must_match' ) );
			}
			else {
				$password = null;
			}
			
			$this->form_validation->add_field ( 'email', 'required', Lang ( 'email_req' ) );
			$this->form_validation->add_field ( 'email', 'valid_email', Lang ( 'valid_email' ) );
			$this->form_validation->add_field ( 'user_group', 'required', Lang ( 'required' ) );
			
			if ( $this->form_validation->execute () )
			{
				if ( $this->musers->update_member ( uri_segment ( 3 ), $username, $password, $user_group , $email, $auto_approve ) )
				{
					$msg = 'ok|' . Lang ( 'mem_profile_updated' );
					clear_cache ();
				}
				else {
					$error = 'error|' . Lang ( 'profile_n_updated' );
				}
			}
		}
		
		$content = '';
		if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}
		$content .= get_edit_user_form ( $row );

		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	ucfirst ( ws_strtolower ( Lang ( 'update' ) . ' ' . get_username ( $row->ID ) . '\'s ' . Lang ( 'profile' ) ) )
		);
		
		$page = assign_global_variables ( $page, 'edit_user' );
		
		load_template ( $page, 'template' );
	}
	
	// ------------------------------------------------------------------------

	/**
	 * mass_email_users
	 *
	 * Sends mass messages to users
	 *
	 * @access	public
	 * @param	$group - do we send to a group?
	 * @param	$status - do we send to users that have a status?
	 * @return	bol TRUE/FALSE
	 */
	 
	function do_contact_users ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 37 ), TRUE );

		$_submit_check = $this->input->post ( '_submit_check', TRUE );

		if ( $_submit_check != FALSE )
		{
			$this->form_validation->add_field ( 'groups', 'required', Lang ( 'please_select_group' ) );
			$this->form_validation->add_field ( 'status', 'required', Lang ( 'please_select_status' ) );
			$this->form_validation->add_field ( 'subject', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'message', 'required', Lang ( 'required' ) );
			
			if ( $this->form_validation->execute () )
			{
				ini_set ( "max_execution_time", 500 ); //big one?

				$results = $this->musers->select_members_for_mass_mail ( $this->input->post ( 'groups', TRUE ), $this->input->post ( 'status', TRUE ) );
					
				if ( $results != FALSE ) 
				{
					foreach ( $results as $row ) 
					{
						$details = array
						(
							'message' => nl2br ( $this->input->post ( 'message', TRUE ) )
						);

						foreach ( $row as $key => $value )
						{
							$details [ $key ] = $value;
						}

						$body = load_email_template ( $details, 'contact_users' );

						if ( send_email ( $this->input->post ( 'subject', TRUE ), $row->Email, $body ) ) {
							$status = TRUE;
						}
						else {
							$status = FALSE;
						}
					}
					( $status ) ? $msg = 'ok|' . Lang ( 'mails_sent' ) : $msg = 'error|' . Lang ( 'mails_not_sent' );
				}
				else {
					$msg = 'error|' . Lang ( 'no_user_found' );
				}
			}
		}
		
		$page = array
		(
				'page_title'		=>	Lang ( 'manage_users' ),
				'styles'		=>	get_page_css ( 'admin' ),
				'javascript'		=>	get_page_js ( 'admin' ),
				'contact_users_form'	=>	get_contact_users_form ()
		);
		
		if ( isset ( $msg ) ) {
			$page [ 'message' ] = evaluate_response ( $msg );
		}
		else {
			$page [ 'message' ] = '';
		}
		
		load_template ( $page, 'contact_users' );
	} 
	
	// ------------------------------------------------------------------------
	
	/**
	 * do_add_user
	 *
	 * Adds a new user to the database via the admin interface
	 *
	 * @param	POST
	 * @access	private
	 * @return 	string
	 */

	function do_add_user ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 10 ), TRUE );

		$_submit_check = $this->input->post ( '_submit_check', TRUE );

		if ( $_submit_check != FALSE )
		{
			$this->form_validation->add_field ( 'user_group', 'required', Lang ( 'required' ) );
			
			$this->form_validation->add_field ( 'username', 'required', Lang ( 'username_req' ) );
			$this->form_validation->add_field ( 'username', 'checkUnique[username]', Lang ( 'username_taken' ) );
			
			$this->form_validation->add_field ( 'password', 'required', Lang ( 'password_req' ) );
			
			$this->form_validation->add_field ( 'password_confirmed', 'matches[password]', Lang ( 'pass_must_match' ) );
			
			$this->form_validation->add_field ( 'email', 'required', Lang ( 'email_req' ) );
			$this->form_validation->add_field ( 'email', 'valid_email', Lang ( 'valid_email' ) );
			$this->form_validation->add_field ( 'email', 'checkUnique[email]', Lang ( 'email_taken' ) );

			$this->form_validation->add_field ( 'auto_approve', 'required', Lang ( 'required' ) );

			if ( $this->form_validation->execute () ) 
			{
				$username = $this->input->post ( 'username', TRUE );
				$password = $this->input->post ( 'password', TRUE );
				$email = $this->input->post ( 'email', TRUE );
				$level_access = $this->input->post ( 'user_group', TRUE );
				$active = $this->input->post ( 'IS_USER_ACTIVE', TRUE );
				$auto_approve = $this->input->post ( 'auto_approve', TRUE );

				if ( $this->musers->add_new_member ( $username, $password, $email, $level_access, $active, $auto_approve ) )
				{
					//Get the user we just inserted
					$row = $this->musers->get_member_by_username ( $username );
					if ( $row != FALSE )
					{
						if ( $active == 0 )
						{
							$subject = "Activation email from " . DOMAIN_NAME;

							$details = array
							(
								'confirm_url'	=> site_url ( 'register/confirm/' . $row->ID . '/' . $row->Random_key ),
								'row'		=> $row,
								'DOMAIN_NAME'	=> DOMAIN_NAME,
								'SITE_NAME'	=> SITE_NAME,
								'SITE_SLOGAN'	=> SITE_SLOGAN,
							);

							$message = load_email_template ( $details, 'register' );
							
							if ( ! send_email ( $subject, $row->Email, $message ) ) {
								$msg = 'error|' . Lang ( 'mail_not_sent' );
							}
						}
						$msg = 'ok|' . Lang ( 'admin_account_created' );
					}
					else {
						$msg = 'error|' . Lang ( 'mem_not_found' );
					}
				}
			}
		}

		$page = array
		(
				'page_title'		=>	Lang ( 'manage_users' ),
				'styles'		=>	get_page_css ( 'admin' ),
				'javascript'		=>	get_page_js ( 'admin' ),
				'admin_add_user_form'	=>	get_admin_add_user_form ()
		);
		
		if ( isset ( $msg ) ) {
			$page [ 'message' ] = evaluate_response ( $msg );
		}
		else {
			$page [ 'message' ] = '';
		}
		
		load_template ( $page, 'add_user' );
	}
	
	function manage_user_permissions ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 30 ), TRUE );

		$content = '';
		$content .= get_user_permissions_form ();
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_u_permissions' ) . ': ' . get_username ( $this->uri->segment ( 3 ) )
		);
		
		$page = assign_global_variables ( $page, 'manage_u_permissions' );
		
		load_template ( $page, 'template' );
	}	

	function options ()
	{
		$this->site_sentry->checklogin ();
		if ( numeric ( $this->uri->segment ( 4 ) ) && $this->uri->segment ( 3 ) != '' )
		{
			$ID = $this->uri->segment ( 4 );
			$action = $this->uri->segment ( 3 );
			$status = $this->uri->segment ( 5 );

			switch ( $action )
			{
				case 'suspend':
					$this->permissions->checkPermissions ( array ( 14 ), TRUE );

					$row = $this->musers->get_member_by_id ( $ID );
					if ( $row != FALSE )
					{
						$this->load->model ( 'mwallpaper' );
						$this->mwallpaper->suspend_by_username ( $ID );
						global_reset_categories ();
						$this->musers->suspend_mem ( $ID );
						clear_cache ();
					}
					redirect ( 'users/manage_users/' . $status, 'location' );
				break;

				case 'edit':
					$this->permissions->checkPermissions ( array ( 11 ), TRUE );

					$row = $this->musers->get_member_by_id ( $ID );			
					if ( $row != FALSE )
					{
						redirect ( 'users/edit_user/' . $ID, 'location' );
					}
				break;

				case 'delete':
					$this->permissions->checkPermissions ( array ( 12 ), TRUE );

					$row = $this->musers->get_member_by_id ( $ID );			
					if ( $row != FALSE )
					{
						delete_user ( $ID );
						clear_cache ();
					}
					redirect ( 'users/manage_users/' . $status, 'location' );
				break;

				case 'activate':
					$this->permissions->checkPermissions ( array ( 13 ), TRUE );

					$row = $this->musers->get_member_by_id ( $ID );			
					if ( $row != FALSE )
					{
						$this->load->model ( 'mwallpaper' );
						$this->mwallpaper->activate_by_username ( $row->ID );
						$this->musers->activate_member_by_id ( $ID );
						clear_cache ();
					}
					redirect ( 'users/manage_users/' . $status, 'location' );
				break;
			}
		}
	}
}

//END