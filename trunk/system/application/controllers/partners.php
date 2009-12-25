<?php

class Partners extends Controller {

	function partners ()
	{
		parent::Controller ();
		$this->load->helper ( 'partners' );
		$this->load->model ( 'mpartners' );
		$this->load->library ( 'pagination' );
		$this->load->library ( 'form_validation' );
	}

	function manage ()
	{
		$this->site_sentry->checklogin ();
		if (
			! $this->permissions->checkPermissions ( array ( 43 ) ) ||
			! $this->permissions->checkPermissions ( array ( 44 ) ) ||
			! $this->permissions->checkPermissions ( array ( 45 ) )
		) {
			redirect ();
		}

		$this->pagination->start = ( $this->uri->segment ( 3 ) ) ? $this->uri->segment ( 3 ) : 0;
		$this->pagination->limit = ( $this->uri->segment ( 4 ) ) ? $this->uri->segment ( 4 ) : 10;
		$this->pagination->filePath = site_url ( 'partners/manage' );
		$this->pagination->select_what = '*';
		$this->pagination->the_table = '`' . DBPREFIX . 'partners`';
		$this->pagination->add_query = ' ORDER BY `ID` ASC';
		
		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();
		
		$content = '';

		$content .= get_partners_list ( $query );
		$content .= ( ! empty ( $pagination ) ) ? $pagination : '';
		$content .= '			<div style="margin-top:20px">' . "\n";
		$content .= add_partner_form ();
		$content .= '			</div>' . "\n";
		
		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_partners' )
		);
		
		$page = assign_global_variables ( $page, 'manage_partners' );
		load_template ( $page, 'template' );
	}

	function add_partner ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 43 ), TRUE );
		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		
		if ( $_submit_check != FALSE )
		{			
			$title = $this->input->post ( 'title', TRUE );
			$desc = $this->input->post ( 'description', TRUE );
			$link = $this->input->post ( 'link' );
			
			$this->form_validation->add_field ( 'title', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'link', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'link', 'valid_url', Lang ( 'valid_url' ) );
			$this->form_validation->add_field ( 'link', 'partner_not_exists', Lang ( 'partner_exists' ) );
			
			if ( $this->form_validation->execute () )
			{
				$insert = $this->mpartners->insert_partner ( $title, $desc, $link );

				if ( $insert )
				{
					clear_cache ();
					$msg = 'ok|' . Lang ( 'partner_added' );
				}
				else {
					$msg = 'error|' . Lang ( 'partner_n_added' );
				}
			}
		}
		
		$this->pagination->start = ( $this->uri->segment ( 3 ) ) ? $this->uri->segment ( 3 ) : 0;
		$this->pagination->limit = ( $this->uri->segment ( 4 ) ) ? $this->uri->segment ( 4 ) : 10;
		$this->pagination->filePath = site_url ( 'partners/manage' );
		$this->pagination->select_what = '*';
		$this->pagination->the_table = '`' . DBPREFIX . 'partners`';
		$this->pagination->add_query = ' ORDER BY `ID` ASC';
		
		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();
		
		$content = '';
		if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}
		$content .= get_partners_list ( $query );
		$content .= ( ! empty ( $pagination ) ) ? $pagination : '';
		$content .= '			<div style="margin-top:20px">' . "\n";
		$content .= add_partner_form ();
		$content .= '			</div>' . "\n";
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_partners' )
		);
		
		$page = assign_global_variables ( $page, 'manage_partners' );
		
		load_template ( $page, 'template' );
	}
	
	function edit ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 44 ), TRUE );
		$content = edit_partner_form ( $this->uri->segment ( 3 ) );
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_partners' )
		);
		
		$page = assign_global_variables ( $page, 'manage_partners' );
		
		load_template ( $page, 'template' );
	}
	
	function do_edit_partner ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 44 ), TRUE );
		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		
		if ( $_submit_check != FALSE )
		{			
			$title = $this->input->post ( 'title', TRUE );
			$desc = $this->input->post ( 'description', TRUE );
			$link = $this->input->post ( 'link' );
			
			$this->form_validation->add_field ( 'title', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'link', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'link', 'valid_url', Lang ( 'valid_url' ) );
			
			if ( $this->form_validation->execute () )
			{
				$insert = $this->mpartners->update_partner ( $this->uri->segment ( 3 ), $title, $desc, $link );

				if ( $insert )
				{
					clear_cache ();
					$msg = 'ok|' . Lang ( 'partner_edited' );
				}
				else {
					$msg = 'error|' . Lang ( 'partner_n_edited' );
				}
				clear_cache ();
			}
		}
		
		$content = '';
		if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}
		$content .= edit_partner_form ( $this->uri->segment ( 3 ) );
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_partners' )
		);
		
		$page = assign_global_variables ( $page, 'manage_partners' );
		
		load_template ( $page, 'template' );
	}
	
	function options ()
	{
		$this->site_sentry->checklogin ();
		if ( numeric ( $this->uri->segment ( 4 ) ) && $this->uri->segment ( 3 ) != '' )
		{
			$ID = $this->uri->segment ( 4 );
			$action = $this->uri->segment ( 3 );

			switch ( $action )
			{
				case 'edit':
					$this->permissions->checkPermissions ( array ( 44 ), TRUE );
					$row = get_partner ( $ID );
					if ( $row != FALSE )
					{
						redirect ( 'partners/edit/' . $ID, 'location' );
					}
				break;
			
				case 'delete':
					$this->permissions->checkPermissions ( array ( 45 ), TRUE );
					$row = get_partner ( $ID );
					if ( $row != FALSE )
					{
						$this->mpartners->delete ( $ID );
					}
					clear_cache ();
					redirect ( 'partners/manage', 'location' );
				break;
			}
		}
	}
	
}

//END