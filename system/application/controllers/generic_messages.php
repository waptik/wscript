<?php

class Generic_messages extends Controller {

	function Generic_messages ()
	{
		parent::Controller ();
		$this->load->model ( 'master' );
	}

	function no_permission ()
	{
		$content = evaluate_response ( 'error|' . Lang ( 'permissions_req' ) );
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=> $content,
			'right'		=> $right,
			'header_msg'	=> Lang ( 'admin' )
		);

		$page = assign_global_variables ( $page, 'no_permission' );		
		load_template ( $page, 'template' );
	}
	
	function guests_cant_download ()
	{
		$content = evaluate_response ( 'error|' . Lang ( 'guests_cant_download_msg' ) );
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=> $content,
			'right'		=> $right,
			'header_msg'	=> Lang ( 'guests_cant_download' )
		);

		$page = assign_global_variables ( $page, 'guests_cant_download' );		
		load_template ( $page, 'template' );
	}

        function confirm_adult ()
        {
		setcookie ( "AdultConfirmed", '1', now () + 86400, '/' );
        }
}
//END