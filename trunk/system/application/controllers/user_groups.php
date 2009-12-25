<?php

class User_groups extends Controller {
	
	function user_groups ()
	{
		parent::Controller ();
		$this->site_sentry->checklogin ();
		$this->load->helper ( 'usergroup_helper' );
		$this->load->model ( 'musergroups' );
		$this->load->library ( 'pagination' );
		$this->load->library ( 'form_validation' );
		$this->load->helper ( 'form' );
	}
	
	function manage_groups ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 16 ), TRUE );

		$this->pagination->start = ( $this->uri->segment ( 3 ) ) ? $this->uri->segment ( 3 ) : '0';
		$this->pagination->limit = ( $this->uri->segment ( 4 ) ) ? $this->uri->segment ( 4 ) : '10';
		$this->pagination->filePath = site_url ( 'user_groups/manage_groups' );
		$this->pagination->select_what = '*';
		$this->pagination->the_table = '`' . DBPREFIX . 'groups`';
		$this->pagination->add_query = ' ORDER BY `ID` ASC';
		
		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();
		
		$content = '';

		$content .= get_groups_list ( $query );
		$content .= ( ! empty ( $pagination ) ) ? $pagination : '';
		$content .= '			<div style="margin-top:20px">' . "\n";
		$content .= add_group_form ();
		$content .= '			</div>' . "\n";
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_groups' )
		);
		
		$page = assign_global_variables ( $page, 'manage_groups' );
		
		$data [ 'content' ] = load_template ( $page, 'template' );
	}
	
	function edit_group ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 18 ), TRUE );

		$row = $this->musergroups->get_group ( $this->uri->segment ( 3 ) );
		
		$content = '';
		$content .= edit_group_form ( $row );
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'edit_group' )
		);
		
		$page = assign_global_variables ( $page, 'edit_group' );
		
		$data [ 'content' ] = load_template ( $page, 'template' );
	}
	
	function manage_group_permissions ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 30 ), TRUE );

		if ( ! empty ( $_POST ) ) {
			if ( ! $this->site_sentry->isadmingroup ( $this->uri->segment ( 3 ) ) )
			{
				$array = array ();
				$parents = $this->mpermissions->get_parent_permissions ();
				//clean the existing group permissions, we have new ones
				$this->mpermissions->delete_all_permissions ( $this->uri->segment ( 3 ), 'group' );

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
							$this->mpermissions->add_permission ( $this->uri->segment ( 3 ), $parent, 'group'  );
						}
						else {
							foreach ( $childs as $child )
							{
								$this->mpermissions->add_permission ( $this->uri->segment ( 3 ), $child, 'group'  );
							}
						}
					}
				}
				$this->session->unset_userdata ( 'all_permissions' );
			}
			redirect ( 'user_groups/manage_groups' );
		}

		$content = '';
		$content .= get_group_permissions_form ();

		$right = get_right_side_content ();

		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_g_permissions' ) . ': ' . get_group_title ( $this->uri->segment ( 3 ) )
		);

		$page = assign_global_variables ( $page, 'manage_g_permissions' );
		
		load_template ( $page, 'template' );
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * do_edit_user_group
	 *
	 * Responsible for updating a group of users
	 *
	 * @param	GET
	 * @access	private
	 * @return 	string
	 */
	
	function do_edit_user_group ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 18 ), TRUE );
		
		$get_group = $this->musergroups->get_group ( $this->uri->segment ( 3 ) );
					
		if ( $get_group != FALSE )
		{
			$data [ 'row' ] = $get_group;

			$_submit_check = $this->input->post ( '_submit_check', TRUE );
		
			if ( $_submit_check != FALSE )
			{
				$this->form_validation->add_field ( 'title', 'required', Lang ( 'required' ) );
				
				if ( $this->form_validation->execute () )
				{
					$title = $this->input->post ( 'title', TRUE );
					$desc = $this->input->post ( 'description', TRUE );
					$login_redirect = $this->input->post ( 'login_redirect', TRUE );
					$logout_redirect = $this->input->post ( 'logout_redirect', TRUE );
					
					if ( $this->musergroups->edit_group ( $get_group->ID, $title, $desc, $login_redirect, $logout_redirect ) )
					{
						clear_cache ();
						$msg = 'ok|' . Lang ( 'group_edited' );
					}
					else {
						$msg = 'error|' . Lang ( 'group_n_edited' );
					}
				}
			}
		}
		else {
			die ( Lang ( 'invalid_request' ) );
		}
		
		$row = $this->musergroups->get_group ( $this->uri->segment ( 3 ) );
		
		
		$content = '';
		if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}
		$content .= edit_group_form ( $row );
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'edit_group' )
		);
		
		$page = assign_global_variables ( $page, 'edit_group' );
		
		$data [ 'content' ] = load_template ( $page, 'template' );
	}
	
	// ------------------------------------------------------------------------

	/**
	 * add_user_groups
	 *
	 * handles new group additions
	 *
	 * @access	public
	 * @param	$_POST
	 * @return	string
	 */
	
	function add_user_groups ()
	{
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 17 ), TRUE );
		
		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		
		if ( $_submit_check != FALSE )
		{			
			$title = $this->input->post ( 'title', TRUE );
			$desc = $this->input->post ( 'description', TRUE );
			$login_redirect = $this->input->post ( 'login_redirect', TRUE );
			$logout_redirect = $this->input->post ( 'logout_redirect', TRUE );
			
			$this->form_validation->add_field ( 'title', 'required', Lang ( 'required' ) );
			
			if ( $this->form_validation->execute () )
			{
				$insert = $this->musergroups->insert_group ( $title, $desc, $login_redirect, $logout_redirect );

				if ( $insert )
				{
					$msg = 'ok|' . Lang ( 'group_added' );
				}
				else {
					$msg = 'error|' . Lang ( 'group_n_added' );
				}
			}
		}
		
		$this->pagination->start = ( $this->uri->segment ( 3 ) ) ? $this->uri->segment ( 3 ) : '0';
		$this->pagination->limit = ( $this->uri->segment ( 4 ) ) ? $this->uri->segment ( 4 ) : '10';
		$this->pagination->filePath = site_url ( 'admin/manage_groups' );
		$this->pagination->select_what = '*';
		$this->pagination->the_table = '`' . DBPREFIX . 'groups`';
		$this->pagination->add_query = ' ORDER BY `ID` ASC';
		
		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();
		
		$content = '';
		if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}
		$content .= get_groups_list ( $query );
		$content .= ( ! empty ( $pagination ) ) ? $pagination : '';
		$content .= '			<div style="margin-top:20px">' . "\n";
		$content .= add_group_form ();
		$content .= '			</div>' . "\n";
		
		$right = get_right_side_content ();

		
		$page = array
		(
			'content'	=>	$content,
			'right'		=>	$right,
			'header_msg'	=>	Lang ( 'manage_groups' )
		);
		
		$page = assign_global_variables ( $page, 'manage_groups' );
		
		$data [ 'content' ] = load_template ( $page, 'template' );
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
				case 'delete':
					$this->permissions->checkPermissions ( array ( 19 ), TRUE );

					$group = $this->musergroups->get_group ( $ID );
					if ( $group != FALSE )
					{
						$this->load->model ( 'musers' );
						$users = $this->musers->get_users_by_group ( $ID );
						
						if ( $users != FALSE ) {
							foreach ( $users as $user ) {
								delete_user ( $user->ID );
							}
						}

						$this->musergroups->delete_group ( $ID );
						clear_cache ();
					}
					redirect ( 'user_groups/manage_groups', 'location' );
				break;
				
				case 'edit':
					$this->permissions->checkPermissions ( array ( 18 ), TRUE );

					$group = $this->musergroups->get_group ( $ID );
					if ( $group != FALSE )
					{
						redirect ( 'user_groups/edit_group/' . $ID, 'location' );
					}
				break;
			}
		}
	}
	
}

//END