<?php

class Login extends Controller {

	public $message = '';
	/**
	 * Contructor function
	 *
	 */
	
	function login ()
	{
		parent::Controller ();
		$this->load->model ( 'musers' );
		$this->load->library ( 'form_validation' );
		$this->load->helper ( 'form' );
	}
	
	function index ()
	{
		$content = '';
		$content .= get_login_form ();

		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'login' )
		);
		
		$page = assign_global_variables ( $page, 'login' );
		
		load_template ( $page, 'template' );
	}
	// ------------------------------------------------------------------------
	
	/**
	 * do_login - responsible for logging our members in
	 *
	 * @access	public
	 * @param	string
	 * @return	none
	 */
	
	function do_login ()
	{
		$msg = 'info|' . Lang ( 'login_msg' );

		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		
		if ( $_submit_check != FALSE )
		{
			$url = $this->input->post ( 'url', TRUE );
			$username = $this->input->post ( 'username', TRUE );
			$password = $this->input->post ( 'password', TRUE );
			$redirect = $this->input->post ( 'redirect', TRUE );
			$remember = $this->input->post ( 'remember', TRUE );
			$attempt = $this->session->userdata ( ATEMPT );
			
			if ( ! empty ( $url ) ) 
			{
				$msg = 'error|' . Lang ( 'spam_bot' );
			}
			else {			
				$this->form_validation->add_field ( 'username', 'required', Lang ( 'username_req' ) );
				$this->form_validation->add_field ( 'password', 'required', Lang ( 'password_req' ) );
				
				if ( $this->form_validation->execute () ) 
				{
					$row = $this->musers->get_member_by_user_pass ( $username, $password );

					if ( $row != FALSE )
					{
						if ( $row->Active == 1 )
						{
							@session_destroy ();
							$this->session->unset_userdata ( 'user_menu' );
							$this->site_sentry->__set_login_sessions ( $row->ID, $row->Password, ( isset ( $remember ) ) ? TRUE : FALSE );
							if ( $attempt ) {
								redirect ( get_redirect_after_login ( $redirect ), 'location' );
							}
							else {
								redirect ( $this->site_sentry->get_user_level_redirect_on_login (), 'location' );
							}
						}
						elseif ( $row->Active == 0 ) {
							$msg = 'error|' . Lang ( 'user_not_active' );
						}
						elseif ( $row->Active == 2 ) {
							$msg = 'error|' . Lang ( 'suspended' );
						}
					}
					else {		
						$msg = 'error|' . Lang ( 'login_failed' );
					}
				}
			}
		}

		$content = '';
		
		if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}
		else {
			$content .= evaluate_response ( 'info|' . Lang ( 'login_msg' ) );
		}
		$content .= get_login_form ();
		$content .= '			<div style="margin-top:20px">' . "\n";
		$content .= get_forgot_password_form ();
		$content .= '			</div>' . "\n";
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'login' )
		);
		
		$page = assign_global_variables ( $page, 'login' );
		
		load_template ( $page, 'template' );
	}

	function forgot_password ()
	{
		$this->load->helper ( 'string');
		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		$content = '';

		if ( $_submit_check != FALSE )
		{
			$url = $this->input->post ( 'url', TRUE );
			if ( ! empty ( $url ) ) 
			{
				$msg = 'error|' . Lang ( 'spam_bot' );
			}
			else {
				$this->form_validation->add_field ( 'f_email', 'required', Lang ( 'required' ) );
				$this->form_validation->add_field ( 'f_email', 'valid_email', Lang ( 'valid_email' ) );
				if ( $this->form_validation->execute () ) 
				{
					$email = $this->input->post ( 'f_email');
					$random_passkey =  random_string ( 'alnum', 12 );
					$row = $this->musers->get_member_by_email ( $email );
		
					if ( $row == FALSE || ! $this->musers->password_reset ( $email, $random_passkey ) ) {
						//play dead
					}
					else {
						$subject = Lang ( 'pass_reset_subj' ) . DOMAIN_NAME;

						$details = array
						(
							'confirm_url'	=> site_url ( 'login/confirmpassword/' . $row->ID . '/' . $random_passkey ),
							'row'		=> $row,
							'temp_pass'	=> $random_passkey,
						);

						$message = load_email_template ( $details, 'forgot_password' );

						if ( send_email ( $subject, $row->Email, $message ) ) {
							$msg = 'ok|' . Lang ( 'new_pass_sent' );
						}
						else {
							$msg = 'error|' . Lang ( 'fpass_email_fail' );
						}
					}
				}
			}
		}

		if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}

		$content .= get_forgot_password_form ();

		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'forgot_pass' )
		);
		
		$page = assign_global_variables ( $page, 'forgot_pass' );
		
		load_template ( $page, 'template' );
	}

	function confirmpassword ()
	{
		$user_id    =   $this->uri->segment ( 3 );
		$t_password =   $this->uri->segment ( 4 );

		if ( $this->musers->confirmpassword ( $user_id, $t_password ) == TRUE )
		{
			$msg = 'ok|' . Lang ( 'new_pass_ok' );
		}
		else {
			$msg = 'error|' . Lang ( 'incorrect_pass' );
		}
		
		$content = '';
		
		if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}
		$content .= get_login_form ();

		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'login' )
		);
		
		$page = assign_global_variables ( $page, 'login' );
		
		load_template ( $page, 'template' );
	}

	function logout ()
	{
		$this->site_sentry->__logout ();
	}
}

//END