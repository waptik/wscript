<?php

class Register extends Controller {

	function __construct ()
	{
		parent::Controller ();
		$this->load->model ( 'musers' );
		$this->load->library ( 'form_validation' );
		$this->load->helper ( 'form' );
	}

	function index ()
	{
		$content = evaluate_response ( 'info|' . Lang ( 'inf_register' ) ) . get_register_form ();
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=> $content,
			'right'		=> $right,
			'header_msg'	=> Lang ( 'register' )
		);
		
		$page = assign_global_variables ( $page, 'register' );
		load_template ( $page, 'template' );
	}

	// ------------------------------------------------------------------------

	/**
	 * register
	 *
	 * Covers the process needed to register a new member
	 *
	 * @access	public
	 * @param	array via $_GET
	 * @return
	 */


	function insert_register ()
	{
		$this->site_sentry->redirect_if_loggedIn ();
		$msg = 'info|' . Lang ( 'inf_register' );
		
		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		
		if ( $_submit_check != FALSE )
		{
			$url = $this->input->post ( 'url', TRUE );

			if ( $url != FALSE ) {
				$msg = 'error|' . Lang ( 'spam_bot' );
			}
			else {
				$this->form_validation->add_field ( 'r_username', 'required', Lang ( 'username_req' ) );
				$this->form_validation->add_field ( 'r_username', 'alpha_numeric', Lang ( 'username_alpha' ) );
				$this->form_validation->add_field ( 'r_username', 'checkUnique[username]', Lang ( 'username_taken' ) );

				$this->form_validation->add_field ( 'r_password', 'required', Lang ( 'password_req' ) );
				$this->form_validation->add_field ( 'r_password', 'alpha_numeric', Lang ( 'password_alpha' ) );

				$this->form_validation->add_field ( 'r_password_confirmed', 'matches[r_password]', Lang ( 'pass_must_match' ) );

				$this->form_validation->add_field ( 'r_email', 'required', Lang ( 'email_req' ) );
				$this->form_validation->add_field ( 'r_email', 'valid_email', Lang ( 'valid_email' ) );
				$this->form_validation->add_field ( 'r_email', 'checkUnique[email]', Lang ( 'email_taken' ) );

				if ( $this->form_validation->execute () )
				{
					$username = $this->input->post ( 'r_username', TRUE );
					$password = $this->input->post ( 'r_password', TRUE );
					$email = $this->input->post ( 'r_email', TRUE );
					
					if ( $this->musers->add_new_member ( $username, $password, $email, 2, FALSE ) )
					{
						$row = $this->musers->get_member_by_username ( $username );
						if ( $row != FALSE )
						{
							$subject = "Activation email from " . DOMAIN_NAME;

							$details = array
							(
								'confirm_url'		=> site_url ( 'register/confirm/' . $row->ID . '/' . $row->Random_key ),
								'Username'		=> $row->Username,
								'DOMAIN_NAME'		=> DOMAIN_NAME,
								'SITE_NAME'		=> SITE_NAME,
								'SITE_SLOGAN'		=> SITE_SLOGAN,
							);
							$message = load_email_template ( $details, 'register' );

							if ( send_email ( $subject, $row->Email, $message ) ) {
								$msg = 'ok|' . Lang ( 'account_created' );
							}
							else {
								$msg = 'error|' . Lang ( 'mail_not_sent' );
							}
						}
						else {
							$msg = 'error|' . Lang ( 'mem_not_found' );
						}
					}
				}
			}
		}

		$content = evaluate_response ( 'info|' . Lang ( 'inf_register' ) );
		if ( isset ( $msg ) ) {
			$content = evaluate_response ( $msg );
		}

		$content .= get_register_form ();
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'register' )
		);
		
		$page = assign_global_variables ( $page, 'register' );
		load_template ( $page, 'template' );
	}
	
	// ------------------------------------------------------------------------

	/**
	 * confirm
	 *
	 * Covers the process needed to confirm a certain member
	 *
	 * @access	public
	 * @param	array via $_GET
	 * @return	string
	 */ 

	function confirm ()
	{
		$ID = $this->uri->segment ( 3 );
		$key = $this->uri->segment ( 4 );

		if ( $ID != FALSE || $key != FALSE ) {
			if ( numeric ( $ID ) && strlen ( $key ) == 32 && alpha_numeric ( $key ) ) {
				$row = 	$this->musers->get_member_by_id ( $ID );
				if ( $row != FALSE ) {

					if ( $row->Active == 1 ) {
						$msg = 'error|' . Lang ( 'already_active' );
					}
					elseif ( $row->Active == 2 ) {
						$msg = 'error|' . Lang ( 'suspended' );
					}
					elseif ( $row->Random_key != $key ) {
						$msg = 'error|' . Lang ( 'invalid_key' );
					}
					else {
						if ( $this->musers->activate_member_by_id ( $row->ID ) ) 
						{
							$msg = 'ok|' . Lang ( 'mem_confirmed' );
						}
					}
				}
				else {
					$msg = 'error|' . Lang ( 'mem_not_found' );	
				}
			}
			else {
				$msg = 'error|' . Lang ( 'invalid_data' );
			}
		}
		else {
			$msg = 'error|' . Lang ( 'no_get_data' );
		}

		$content = evaluate_response ( 'info|' . Lang ( 'inf_register' ) );
		if ( isset ( $msg ) ) {
			$content = evaluate_response ( $msg );
		}

		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'confirm_registration' )
		);

		$page = assign_global_variables ( $page, 'register' );

		load_template ( $page, 'template' );
	}	
}

//END