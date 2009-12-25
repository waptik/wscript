<?php

class Comments extends Controller {

	function __construct () {
		parent::Controller ();
		$this->load->model ( 'mcomments' );
	}

	function manage () {
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 46 ) );

		$this->load->library ( 'pagination' );
		$start = $this->uri->segment ( 3, 0 );

		$this->pagination->start = $start;
		$this->pagination->limit = 20;
		$this->pagination->filePath = site_url ( 'comments/manage' );
		$this->pagination->select_what = 'c.*,w.date_added as w_date_added,w.file_title as w_title,w.ID as w_ID,w.hash as w_hash';
		$this->pagination->the_table = DBPREFIX . 'comments c LEFT JOIN ' . DBPREFIX . 'wallpapers w on w.ID = c.item_id';
		$this->pagination->add_query .= ' ORDER BY c.active ASC, c.date_added DESC';

		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();

		$content = '';
                $content .= load_form_template ( array ( 'rows' => $query, 'pagination' => $pagination ), 'manage_comments' );

		$right = get_right_side_content ();

		$page = array
		(
			'content'	=> $content,
			'right'		=> $right,
			'header_msg'	=> Lang ( 'manage_comments' )
		);

		$page = assign_global_variables ( $page, 'manage_comments' );		
		load_template ( $page, 'template' );
	}

	function approve () {
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 46 ) );

		$id = $this->uri->segment ( 3 );
		$this->mcomments->edit ( $id, array ( 'active' => 1 ) );die ();
	}

	function delete () {
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 46 ) );

		$id = $this->uri->segment ( 3 );
		$this->mcomments->delete ( $id );die ();
	}

	function add_comment () {
		$this->load->library ( 'form_validation' );
		$spam_url = $this->input->post ( 'url' );

		if ( ! empty ( $_POST ) && $spam_url == FALSE ) {
			$name = $this->input->post ( 'c_name' );
			$email = $this->input->post ( 'c_email' );
			$comment = $this->input->post ( 'c_comment' );
			$url = $this->input->post ( 'c_url' );

			$this->form_validation->add_field ( 'c_name', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'c_email', 'required', Lang ( 'required' ) );
			if ( $email != FALSE ) {
				$this->form_validation->add_field ( 'c_email', 'valid_email', Lang ( 'valid_email' ) );
			}

			if ( $url != FALSE ) {
				$this->form_validation->add_field ( 'c_url', 'valid_url', Lang ( 'valid_url' ) );
			}

			$this->form_validation->add_field ( 'c_comment', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'c_comment', 'max_length[1000]', Lang ( 'max_length' ) );
			$this->form_validation->add_field ( 'c_comment', 'min_length[10]', Lang ( 'min_length' ) );

			if ( $this->form_validation->execute () ) {
				$this->mcomments->add ( $this->uri->segment ( 3 ), strip_tags ( $comment, '<b><i>' ), $name, $url, $email, ( int ) AUTO_APROVE_COMMENTS );
				$page [ 'message' ] = "<script type=\"text/javascript\">parent.refresh();</script>";
			}
		}

		if ( ! isset ( $page [ 'message' ] ) ) {
			$prefix_msg = '';
			if ( ! AUTO_APROVE_COMMENTS ) {
				$prefix_msg = evaluate_response ( 'info|' . Lang ( 'comments_are_moderated' ) );
			}
			$page [ 'message' ] = $prefix_msg . load_form_template ( array ( 'id' => $this->uri->segment ( 3 ) ), 'add_comment' );
		}

		$page [ 'page_title' ] = Lang ( 'add_comment' );
		$page [ 'styles' ] = get_page_css ();
		$page [ 'javascript' ] = get_page_js ( 'admin' );

		echo load_template ( $page, 'generic' );die ();
	}

	function get_wallpaper_comments ()
	{
		echo get_wallpaper_comments ( $this->uri->segment ( 3 ), $this->uri->segment ( 4 ) );die ();
	}
}

//	END COMMENTS CONTROLLER