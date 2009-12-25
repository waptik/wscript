<?php

class Contact extends Controller
{
        function contact()
        {
                parent::Controller();
                $this->load->library ( 'form_validation' );
        }
        
        function index()
        {
                $content = '';
                $content .= evaluate_response ( 'info|' . Lang ( 'contact' ) );
		$content .= get_contact_us_form ();
		$right = get_right_side_content ();
		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'contact_us' )
		);
		
		$page = assign_global_variables ( $page, 'contact' );		
		load_template ( $page, 'template' );
        }
    
        function send ()
        {        
                $_submit_check = $this->input->post ( '_submit_check', TRUE );

		if ( $_submit_check != FALSE )
		{
			$this->form_validation->add_field ( 'email', 'required', Lang ( 'required' ) );
                        $this->form_validation->add_field ( 'email', 'valid_email', Lang ( 'valid_email' ) );
			$this->form_validation->add_field ( 'message', 'required', Lang ( 'required' ) );
                        $this->form_validation->add_field ( 'message', 'max_length[800]', Lang ( 'max_length' ) );
                        $this->form_validation->add_field ( 'message', 'min_length[30]', Lang ( 'min_length' ) );
                        $this->form_validation->add_field ( 'url', 'empty', Lang ( 'spam_bot' ) );
                
                        if ( $this->form_validation->execute () )
			{
                                if ( send_email ( Lang ( 'contactus_subject' ) . SITE_NAME, ADMIN_EMAIL, $this->input->post ( 'message', TRUE ), $this->input->post ( 'email', TRUE ) ) ) {
                                        $msg = 'ok|' . Lang ( 'contact_sent' );
                                }
                                else {
                                        $msg = 'error|' . Lang ( 'contact_n_sent' );
                                }
                        }
                }
                
                $content = '';
                if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}
		$content .= get_contact_us_form ();
		$right = get_right_side_content ();
		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'contact' )
		);
		
		$page = assign_global_variables ( $page, 'contact' );		
		$data [ 'content' ] = load_template ( $page, 'template' );
        }
    
}
//END