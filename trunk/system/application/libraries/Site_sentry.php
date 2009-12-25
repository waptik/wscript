<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_sentry
{
	function __construct ()
	{
		$CI =& get_instance();

		if ( ! preg_match ( '#' . preg_quote ( APPLICATION_URL ) . '#', selfUrl () ) )
		{
			header ( 'HTTP/1.1 301 Moved Permanently'); 
			header ( 'Location: ' . substr ( APPLICATION_URL, 0, -1 ) . $CI->uri->uri_string () );
			exit;
		}

		$this->obj =& get_instance();
		$this->obj->load->model ( 'musers' );
		$this->obj->load->model ( 'musergroups' );
	}

	// ------------------------------------------------------------------------
	
	/**
	 * checkLogin
	 *
	 * Applies restrictions to visitors based on membership and level access
	 * Also handles cookie based "remember me" feature
	 *
	 * @access	public
	 * @param	string
	 * @return	bool TRUE/FALSE
	 */ 

	function checkLogin ()
	{
		if ( ! $this->is_logged_in () )
		{
			$access = FALSE;

			if ( ( bool ) $this->obj->input->cookie ( AUTH_COOKIE_ID ) )
			{
				$row = $this->obj->musers->get_member_by_id ( $_COOKIE [ AUTH_COOKIE_ID ] );
				if ( $row != FALSE ) {
					if ( $this->obj->input->cookie ( AUTH_COOKIE ) == md5 ( SECURITY_KEY . $this->obj->input->ip_address () . $row->Password . $this->obj->input->user_agent () ) ) {

						$sessions = array
						(
							AUTH_SESSION_ID	=> $row->ID,
							LOGGEDIN	=> TRUE
						);

						$this->obj->session->set_userdata ( $sessions );
						$access = TRUE;
					}
				}
				else {
					$access = FALSE;
				}
			}
		}
		else {
			$access = TRUE;
		}

		if ( $access == FALSE ) {
			$this->obj->session->set_userdata ( array ( ATEMPT => TRUE ) );
			$redirect_segments = $this->obj->uri->segment_array ();
			$redirect = '';
			
			foreach ( $redirect_segments as $segment )
			{
				$redirect .= $segment . '/';
			}

			redirect ( REDIRECT_TO_LOGIN . '/index/' . $redirect, 'location' );
		}
	}	
	
	// ------------------------------------------------------------------------
	
	/**
	 * set_login_sessions - sets the login sessions
	 *
	 * @access	private
	 * @param	$user_id - the user's id
	 * @param	$password - the user's password
	 * @param	$remember - did our user checked the remember me checkbox?
	 * @return	none
	 */
	
	function __set_login_sessions ( $user_id, $password, $remember )
	{		
		//set the sessions
		$sessions = array
		(
			AUTH_SESSION_ID	=>	$user_id,
			LOGGEDIN	=>	TRUE
		);
		
		if ( $this->obj->session->userdata ( ATEMPT ) )
		{
			@$this->obj->session->unset_userdata ( ATEMPT );
		}
		
		$this->obj->session->set_userdata ( $sessions );
		//do we have "remember me"?
		if ( $remember ) {
			setcookie ( AUTH_COOKIE_ID, $user_id, time () + KEEP_LOGGED_IN_FOR, COOKIE_PATH );
			setcookie ( AUTH_COOKIE, md5 ( SECURITY_KEY . $this->obj->input->ip_address() . $password . $this->obj->input->user_agent () ), now () + KEEP_LOGGED_IN_FOR, COOKIE_PATH );
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * is_logged_in - returns true/false if a user is logged in or not
	 *
	 * @access	public
	 * @param	$_SESSION
	 * @return	bol TRUE/FALSE
	 */
	
	function is_logged_in ()
	{
		return ( $this->obj->session->userdata ( AUTH_SESSION_ID ) ) ? TRUE : FALSE;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * redirect_if_loggedIn
	 *
	 * where used, if the user is logged in, this function
	 * redirects him to the default ( REDIRECT_AFTER_LOGIN ) page.
	 *
	 * good for pages such as register, login, forgot password etc.
	 *
	 * @access	public
	 * @param	$_SESSION
	 * @return	bol TRUE/FALSE
	 */

	function redirect_if_loggedIn ()
	{
		return ( $this->is_logged_in () ) ? redirect ( REDIRECT_AFTER_LOGIN, 'location' ) : '';
	}

	// ------------------------------------------------------------------------
	
	/**
	 * logout
	 *
	 * Handles logouts
	 *
	 * @param	none
	 * @access	public
	 */
	
	function __logout ()
	{
		//if we have a valid session
		if ( $this->obj->session->userdata ( LOGGEDIN ) == TRUE )
		{
			//let's store the redirect page before we kill the sessions
			$redir = $this->get_user_level_redirect_on_logout ();
			$this->obj->session->sess_destroy ();
		}
                else {
                        $redir = '';
                }

		if ( ( bool ) ( $this->obj->input->cookie ( AUTH_COOKIE_ID ) ) && ( bool ) ( $this->obj->input->cookie ( AUTH_COOKIE ) ) ) {
			setcookie ( AUTH_COOKIE_ID, '', time() - KEEP_LOGGED_IN_FOR, COOKIE_PATH );
			setcookie ( AUTH_COOKIE, '', time() - KEEP_LOGGED_IN_FOR, COOKIE_PATH );
		}

		session_destroy ();

		//redirect the user to the default "logout" page
		redirect ( $redir, 'location' );
	}

	// ------------------------------------------------------------------------

	/**
	 * get_user_level_redirect_on_login
	 *
	 * tries to determine where to redirect a members on login
	 * based on group settings
	 *
	 * @access	private
	 * @param	$_SESSION ID
	 * @return	bol TRUE/FALSE
	 */
	
	function get_user_level_redirect_on_login ()
	{
		$group = $this->obj->musergroups->get_login_group_redirect ();
		return ( $group != FALSE ) ? $group : REDIRECT_AFTER_LOGIN;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * get_user_level_redirect_on_logout
	 *
	 * tries to determine where to redirect a members on logout
	 * based on group settings
	 *
	 * @access	private
	 * @param	$_SESSION ID
	 * @return	bol TRUE/FALSE
	 */
	
	function get_user_level_redirect_on_logout ()
	{
		$group = $this->obj->musergroups->get_logout_group_redirect ();
		return ( $group != FALSE ) ? $group : REDIRECT_ON_LOGOUT;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Is admin - Determines if the logged in member is an admin
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */

	function isadmin ( $user_id = FALSE )
	{
		return ( $user_id != FALSE ) ? ( $this->obj->musers->get_level_access ( $user_id ) == 1 ) ? TRUE : FALSE : ( get_mem_info ( 'Level_access' ) == 1 ) ? TRUE : FALSE;
	}

	function isadmingroup ( $group_id = FALSE )
	{
		return ( $group_id != FALSE ) ? ( ( $group_id == 1 ) ? TRUE : FALSE ) : FALSE;
	}
}
//END
