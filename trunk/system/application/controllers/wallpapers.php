<?php

class Wallpapers extends Controller {

	function wallpapers () {
		parent::Controller ();
		$this->load->model ( 'mwallpaper' );
		$this->load->helper ( 'wallpapers' );
		$this->load->library ( 'wb_file_manager' );
		
		//	jupload vars
		$this->upload_sess = '_juvar';
		$this->tmp_prefix = 'jutmp.';
		$this->session_id = session_id ();
		$this->dest_dir = TEMP_DIR;

		$this->allowed_upload_extensions = array ( 

			'gif', 
			'bmp', 
			'jpg', 
			'jpeg', 
			'jpe' 
		);
	}

	function upload () {
		if ( ! GUESTS_CAN_UPLOAD && ! is_logged_in () ) {
			die ( "<script type=\"text/javascript\">parent.close_dialog();</script>" );
		}
		
		$page = array ( 
			
			'page_title' => Lang ( 'manage_users' ), 
			'styles' => get_page_css ( 'manage_users' ), 
			'javascript' => get_page_js ( 'manage_users' ), 
			'admin_add_user_form' => '', 
			'message' => '' 
		);
		
		if ( ! is_logged_in () ) {
			$page [ 'message' ] = evaluate_response ( 'info|' . Lang ( 'guest_uploading' ) );
		}
		
		if ( get_todays_wallpapers_nr () >= 32000 ) {
			$page [ 'message' ] = evaluate_response ( 'error|' . Lang ( 'too_many_wallpapers_4_today' ) );
		}
		else {
			$this->load->library ( 'form_validation' );
			$page [ 'admin_add_user_form' ] = get_add_wallpapers_form ();
		}
		
		echo load_template ( $page, 'add_user' );
		die ();
	}

	function fetch_by_category () {
		$this->load->library ( 'pagination' );
		$display_type = isset ( $_COOKIE [ 'wallpaper_display_type' ] ) ? $_COOKIE [ 'wallpaper_display_type' ] : 'box';
		$start = ( $this->uri->segment ( 4 ) ) ? $this->uri->segment ( 4 ) : '0';
		
		$childs = get_subcats_wallpaper_childs ( $this->uri->segment ( 3 ) );
		
		$this->pagination->start = $start;
		$this->pagination->limit = get_wallpapers_per_page ();
		$this->pagination->is_ajax = TRUE;
		$this->pagination->link_id = 'wallpapers_wrp';
		$this->pagination->filePath = site_url ( 'wallpapers/fetch_by_category/' . $this->uri->segment ( 3 ) );
		
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
		
		$this->pagination->add_query = ' WHERE w.active = 1 AND w.parent_id = 0 AND (w.cat_id = ' . $this->uri->segment ( 3 );
		
		if ( count ( $childs ) > 0 ) {
			foreach ( $childs as $value ) {
				$this->pagination->add_query .= ' OR cat_id = ' . $value;
			}
		}
		
		$this->pagination->add_query .= ') ORDER BY date_added ' . WALLPAPER_ORDER_TYPE;
		
		$query = $this->pagination->getQuery ( TRUE );
		
		echo get_wallpapers ( $query ) . $this->pagination->paginate ();
		die ();
	}

	function show () {
		$wallpaper = get_wallpaper_adv ( $this->uri->segment ( 3 ) );
		$this->load->helper ( 'text' );

		if ( $wallpaper != FALSE ) {
			$type = detect_wallpaper_type ( get_sizes (), $wallpaper->height, $wallpaper->width );
			$childs = $this->mwallpaper->get_childs ( $wallpaper->ID );

			if ( ! $childs ) {
				$childs = array ();
			}
			
			array_push ( $childs, array ( 
				
				'type' => $type, 
				'height' => $wallpaper->height, 
				'width' => $wallpaper->width 
			) );
			
			$possible_resizes = array ();
			
			$this->load->library ( 'WB_array' );

			foreach ( $childs as $child ) {
				$possible_resizes [ $child [ 'type' ] ] [ $child [ 'width' ] ] = $child [ 'height' ];
				$lower_sizes = get_lower_sizes ( $child [ 'width' ], $child [ 'type' ] );

				if ( ! count ( $lower_sizes ) ) {
					continue;
				}

				foreach ( $lower_sizes as $height => $width ) {
					if ( ! is_psp ( $height, $width ) && ! is_iphone ( $height, $width ) ) {
						$possible_resizes [ $child [ 'type' ] ] [ $width ] = $height;
					}
					elseif ( is_psp ( $height, $width ) ) {
						if ( $child [ 'type' ] == 'wide' ) {
							if ( ! isset ( $possible_resizes [ 'psp' ] ) ) {
								$possible_resizes [ 'psp' ] = array ();
							}
							$possible_resizes [ 'psp' ] [ $width ] = $height;
						}
					}
				}
				
				$possible_resizes [ $child [ 'type' ] ] = WB_array::array_sort ( $possible_resizes [ $child [ 'type' ] ], 'desc' );
				
				if ( isset ( $possible_resizes [ 'psp' ] ) ) {
					$possible_resizes [ 'psp' ] = WB_array::array_sort ( $possible_resizes [ 'psp' ], 'desc' );
				}
			}

			$tags [ 'id' ] = $wallpaper->ID;
			$tags [ 'row' ] = $wallpaper;
			$tags [ 'uri_segment' ] = $this->uri->segment ( 3 );
			$tags [ 'download_table' ] = load_html_template ( array ( 'childs' => $possible_resizes, 'row' => $wallpaper ), 'show_download' );
			$tags [ 'edit' ] = '';
			$tags [ 'current_rating' ] = $wallpaper->rating;
			$tags [ 'wallpaper_url' ] = get_wallpaper_url ( $wallpaper );
			$tags [ 'thumb' ] = get_wallpaper_url_location ( $wallpaper ) . generate_big_thumb ( $wallpaper );
			
			$pending_review = '';
			
			if ( ! $wallpaper->active ) {
				$pending_review = evaluate_response ( 'info|' . Lang ( 'pending_review' ) );
			}
			
			if ( is_logged_in () && ( $wallpaper->user_id == get_mem_info ( 'ID' ) || get_mem_info ( 'ID' ) == 1 ) ) {
				$this->load->library ( 'form_validation' );
				$tags [ 'edit' ] = write_header ( Lang ( 'edit_wallpaper' ) ) . get_edit_wallpapers_form ( $wallpaper ) . '<br />';
			}
			
			$content = $pending_review . load_html_template ( $tags, 'wallpaper_details', FALSE, 0, md5 ( selfUrl () ) );
			
			$right = get_right_side_content ();
			
			$this->mwallpaper->increment_hits ( $this->uri->segment ( 3 ) );
			
			$page = array ( 
				
				'content' => $content, 
				'wallpaper' => $wallpaper, 
				'right' => $right, 
				'header_msg' => $wallpaper->file_title . ' ' . Lang ( 'wallpaper' ) 
			);
			
			$page = assign_global_variables ( $page, 'wallpaper' );
			load_template ( $page, 'template', TRUE );
		}
		else {
			redirect ( '', 'location' );
		}
	}

	function show_download () {
		$wallpaper = get_wallpaper_adv ( $this->uri->segment ( 3 ) );
		$this->load->helper ( 'text' );
		
		if ( $wallpaper != FALSE ) {
			$type = detect_wallpaper_type ( get_sizes (), $wallpaper->height, $wallpaper->width );
			$class = 'picture_wrapper_details_' . $type;
			$childs = $this->mwallpaper->get_childs ( $wallpaper->ID );
			
			if ( ! $childs ) {
				$childs = array ();
			}
			
			array_push ( $childs, array ( 
				
				'type' => $type, 
				'height' => $wallpaper->height, 
				'width' => $wallpaper->width 
			) );
			
			$possible_resizes = array ();
			
			$this->load->library ( 'WB_array' );
			
		foreach ( $childs as $child ) {
				$possible_resizes [ $child [ 'type' ] ] [ $child [ 'width' ] ] = $child [ 'height' ];
				$lower_sizes = get_lower_sizes ( $child [ 'width' ], $child [ 'type' ] );

				if ( ! count ( $lower_sizes ) ) {
					continue;
				}

				foreach ( $lower_sizes as $height => $width ) {
					if ( ! is_psp ( $height, $width ) && ! is_iphone ( $height, $width ) ) {
						$possible_resizes [ $child [ 'type' ] ] [ $width ] = $height;
					}
					elseif ( is_psp ( $height, $width ) ) {
						if ( $child [ 'type' ] == 'wide' ) {
							if ( ! isset ( $possible_resizes [ 'psp' ] ) ) {
								$possible_resizes [ 'psp' ] = array ();
							}
							$possible_resizes [ 'psp' ] [ $width ] = $height;
						}
					}
				}
				
				$possible_resizes [ $child [ 'type' ] ] = WB_array::array_sort ( $possible_resizes [ $child [ 'type' ] ], 'desc' );
				
				if ( isset ( $possible_resizes [ 'psp' ] ) ) {
					$possible_resizes [ 'psp' ] = WB_array::array_sort ( $possible_resizes [ 'psp' ], 'desc' );
				}
			}
			
			$tags [ 'row' ] = $wallpaper;
			$tags [ 'uri_segment' ] = $this->uri->segment ( 3 );
			$tags [ 'childs' ] = $possible_resizes;
			
			$page = array ( 
				
				'page_title' => Lang ( 'manage_users' ), 
				'styles' => get_page_css ( 'manage_users' ), 
				'javascript' => get_page_js ( 'manage_users' ), 
				'admin_add_user_form' => '', 
				'message' => load_html_template ( $tags, 'show_download' ) 
			);
			
			if ( ! is_logged_in () && ! GUESTS_CAN_DOWNLOAD ) {
				$page [ 'message' ] = evaluate_response ( 'error|' . Lang ( 'guests_cant_download_msg' ) );
			}
			
			echo load_template ( $page, 'add_user' );
			die ();
		}
		else {
			die ( "<script type=\"text/javascript\">parent.close_dialog();</script>" );
		}
	}

	function get_wallpaper_colors () {
		$wallpaper = get_wallpaper ( $this->uri->segment ( 3 ) );
		
		$location = get_wallpaper_location ( $wallpaper );
		$thumb_name = 'thumb_big_' . $wallpaper->type . '_' . $wallpaper->hash . '.jpg';
		$thumb = $location . $thumb_name;
		
		echo get_wallpaper_colors ( $wallpaper->ID );
		die ();
	}

	function get_wallpaper_breadcrumb () {
		$wallpaper = get_wallpaper ( $this->uri->segment ( 3 ) );
		echo breadcrumb ( $wallpaper->cat_id, TRUE );
		die ();
	}

	function more_from_category () {
		$wallpaper = get_wallpaper ( $this->uri->segment ( 3 ) );
		echo load_html_template ( array ( 
			
			'more_from_category' => get_more_from_category ( $wallpaper->cat_id, $wallpaper->ID, $this->uri->segment ( 4, 0 ) ) 
		), 'more_from_category' );
		die ();
	}

	function get_more_from_author () {
		$wallpaper = get_wallpaper ( $this->uri->segment ( 3 ) );
		echo load_html_template ( array ( 
			
			'more_from_author' => get_more_from_author ( $wallpaper->user_id, $wallpaper->ID ) 
		), 'more_from_author' );
		die ();
	}

	function get_next_prev_wallpapers () {
		$wallpaper = get_wallpaper ( $this->uri->segment ( 3 ) );
		echo get_next_prev_wallpapers ( $wallpaper );
		die ();
	}

	function get_wallpaper_tags () {
		$wallpaper = get_wallpaper ( $this->uri->segment ( 3 ) );
		echo get_wallpaper_tags ( $wallpaper->ID );
		die ();
	}

	function ajax_more_from_author () {
		echo get_more_from_author ( $this->uri->segment ( 3 ), $this->uri->segment ( 3 ), $this->uri->segment ( 4 ) );
		die ();
	}

	function ajax_more_from_category () {
		echo get_more_from_category ( $this->uri->segment ( 3 ), $this->uri->segment ( 3 ), $this->uri->segment ( 4 ) );
		die ();
	}

	function bulk_edit () {
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 
			
			4 
		), TRUE );
		
		$content = '';
		$content .= get_bulk_edit_form ();
		$right = get_right_side_content ();
		
		$page = array ( 
			
			'content' => $content, 
			'right' => $right, 
			'header_msg' => Lang ( 'bulk_edit_wallpapers' ) 
		);
		$page = assign_global_variables ( $page, 'bulk_edit' );
		load_template ( $page, 'template', TRUE );
	}

	function title_suggest () {
		$title = $this->input->post ( 'q' );
		$titles = array ();
		$query = $this->db->query ( 'SELECT DISTINCT file_title FROM ' . DBPREFIX . 'wallpapers WHERE file_title LIKE ' . qstr ( '%' . ws_strtolower ( $title ) . '%' ) );
		foreach ( $query->result () as $row ) {
			$titles [] = $row->file_title;
		}
		echo implode ( "\n", $titles );
		die ();
	}

	function filter_bulk_edit () {
		$this->load->library ( 'form_validation' );
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 
			
			4 
		), TRUE );
		$limit = $this->input->post ( 'output_limit' );
		$title_is_duplicate = $this->input->post ( 'title_is_duplicate' );
		$description_is_empty = $this->input->post ( 'description_is_empty' );
		$keywords_is_empty = $this->input->post ( 'keywords_is_empty' );
		$title_contains = $this->input->post ( 'title_contains' );
		
		$q_pre = 'SELECT
                                        w.*,
                                        GROUP_CONCAT(t.tag SEPARATOR \',\') AS tags,
                                        (
                                                SELECT
                                                        COUNT(dup.ID)
                                                FROM
                                                        ' . DBPREFIX . 'wallpapers as dup
                                                WHERE
                                                        dup.file_title = w.file_title
                                                AND
                                                        dup.ID != w.ID
                                                AND
                                                        dup.parent_id = 0
                                        ) as total_duplicates,
                                        u.Username
                                FROM
                                        ' . DBPREFIX . 'wallpapers w
                                LEFT JOIN
                                	' . DBPREFIX . 'users u
                                	ON(u.ID=w.user_id)
                                LEFT JOIN
                                	' . DBPREFIX . 'tags_rel tr
                                	ON(tr.item_id=w.ID)
                                LEFT JOIN
                                	' . DBPREFIX . 'tags t
                                	ON(t.ID=tr.tag_id)
                                WHERE
                                        w.parent_id = 0';
		
		if ( $title_contains != FALSE ) {
			$q_pre .= ' AND w.file_title LIKE "%' . strip_punctuation ( $title_contains, FALSE ) . '%"';
		}
		
		if ( $description_is_empty ) {
			$q_pre .= ' AND (w.description = \'\' OR w.description IS NULL)';
		}
		
		if ( $keywords_is_empty ) {
			$q_pre .= ' AND (t.tag = \'\' OR t.tag IS NULL)';
		}
		
		$q_pre .= ' GROUP BY w.ID';
		
		if ( $title_is_duplicate ) {
			$q_pre .= ' HAVING total_duplicates > 0';
		}
		
		$q_pre .= ' LIMIT ' . ( int ) $limit;
		
		$result = $this->db->query ( $q_pre );
		
		echo get_bulk_edit_wallpapers_table ( $result );
		die ();
	}

	function browse_by_size () {
		$detect = $this->uri->segment ( 3 );
		if ( $detect == 'normal' || $detect == 'wide' || $detect == 'iphone' || $detect == 'psp' || $detect == 'hd' || $detect == 'multi' || $detect == 'other' ) {
			$title = 'browse_by_type';
			$page_title = Lang ( $detect . "_wallpapers" );
		}
		else {
			$title = 'browse_by_size';
			$page_title = Lang ( 'browse_by_size' ) . ' ' . $this->uri->segment ( 3 ) . ' X ' . $this->uri->segment ( 4 );
		}
		
		$right = get_right_side_content ();
		
		$page = array ( 
			
			'content' => '', 
			'right' => $right, 
			'header_msg' => $page_title 
		);
		
		$page = assign_global_variables ( $page, $title );
		load_template ( $page, 'template', TRUE );
	}

	function fetch_by_size () {
		$this->load->library ( 'pagination' );
		$detect = $this->uri->segment ( 3 );
		
		if ( $detect == 'normal' || $detect == 'wide' || $detect == 'iphone' || $detect == 'psp' || $detect == 'hd' || $detect == 'multi' || $detect == 'other' ) {
			$start = ( $this->uri->segment ( 4 ) ) ? $this->uri->segment ( 4 ) : '0';
			
			$this->pagination->start = $start;
			$this->pagination->is_ajax = TRUE;
			$this->pagination->link_id = 'content';
			$this->pagination->limit = get_wallpapers_per_page ();
			$this->pagination->filePath = site_url ( 'wallpapers/fetch_by_size/' . $detect );
			$this->pagination->select_what = 'DISTINCT w.*, u.Username';
			$this->pagination->the_table = DBPREFIX . 'wallpapers w LEFT JOIN ' . DBPREFIX . 'users u ON(u.ID=w.user_id) LEFT JOIN ' . DBPREFIX . 'wallpapers wc ON(wc.parent_id=w.ID)';
			$this->pagination->add_query = ' WHERE w.active = 1 and w.parent_id = 0';
			$this->pagination->add_query .= ' AND (w.type = ' . qstr ( $detect ) . ' OR wc.type = ' . qstr ( $detect );
			
			if ( $detect == 'psp' ) {
				$this->pagination->add_query .= ' OR w.type = \'wide\' OR wc.type = \'wide\'';
			}
			
			$this->pagination->add_query .= ')';
		}
		else {
			$width = $this->uri->segment ( 3 );
			$height = $this->uri->segment ( 4 );
			$start = ( $this->uri->segment ( 5 ) ) ? $this->uri->segment ( 5 ) : '0';
			
			$this->pagination->start = $start;
			$this->pagination->is_ajax = TRUE;
			$this->pagination->link_id = 'content';
			$this->pagination->limit = get_wallpapers_per_page ();
			$this->pagination->filePath = site_url ( 'wallpapers/fetch_by_size/' . $width . '/' . $height );
			$this->pagination->select_what = 'DISTINCT w.*, u.Username';
			$this->pagination->the_table = DBPREFIX . 'wallpapers w LEFT JOIN ' . DBPREFIX . 'users u ON(u.ID=w.user_id) LEFT JOIN ' . DBPREFIX . 'wallpapers wc ON(wc.parent_id=w.ID)';
			$this->pagination->add_query = ' WHERE w.active = 1 and w.parent_id = 0';
			
			if ( is_psp ( $height, $width ) ) {
				$this->pagination->add_query .= ' AND (w.type = \'wide\' OR wc.type = \'wide\')';
			}
			else {
				$type = detect_wallpaper_type ( get_sizes (), $height, $width );
				
				$this->pagination->add_query .= ' AND (w.type = ' . qstr ( $type ) . ' OR wc.type = ' . qstr ( $type ) . ')';
				
				if ( $width != FALSE && numeric ( $width ) ) {
					$this->pagination->add_query .= ' AND (w.width >= ' . qstr ( $width ) . ' OR wc.width >= ' . qstr ( $width ) . ')';
				}
				
				if ( $height != FALSE && numeric ( $height ) ) {
					$this->pagination->add_query .= ' AND (w.height >= ' . qstr ( $height ) . ' OR wc.height >= ' . qstr ( $height ) . ')';
				}
			}
		}
		
		$this->pagination->add_query .= ' ORDER BY date_added ' . WALLPAPER_ORDER_TYPE;
		
		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();
		
		if ( $query->num_rows () > 0 ) {
			echo get_wallpapers ( $query ) . $pagination;
		}
		else {
			echo evaluate_response ( 'info|' . Lang ( 'no_search_results' ) );
		}
		
		die ();
	}

	function bulk () {
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 
			
			4 
		), TRUE );
		
		$this->load->library ( 'form_validation' );
		
		$page = array ( 
			
			'page_title' => Lang ( 'bulk_upload' ), 
			'styles' => get_page_css (), 
			'javascript' => get_page_js (), 
			'bulk_form' => get_bulk_form (), 
			'message' => '' 
		);
		
		if ( get_todays_wallpapers_nr () >= 30000 ) {
			$page [ 'message' ] = evaluate_response ( 'error|' . Lang ( 'too_many_wallpapers_4_today' ) );
			$page [ 'bulk_form' ] = '';
		}
		
		load_template ( $page, 'bulk_upload' );
	}

	function do_bulk () {
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 
			
			4 
		), TRUE );

		$method = $this->input->post ( 'bulk_method' );
		$use_schedule = $this->input->post ( 'use_schedule' );
		
		$this->load->library ( 'form_validation' );
		
		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		
		if ( $_submit_check != FALSE ) {
			$this->form_validation->add_field ( 'bulk_method', 'required', Lang ( 'required' ) );
			if ( $method == 'cat_direct' ) {
				$this->form_validation->add_field ( 'cat_id', 'required', Lang ( 'required' ) );
				$this->form_validation->add_field ( 'ignore_folders', 'required', Lang ( 'required' ) );
			}
			
			if ( $use_schedule == 'yes' ) {
				$this->form_validation->add_field ( 'schedule_amount', 'required', Lang ( 'required' ) );
				$this->form_validation->add_field ( 'schedule_amount', 'numeric', Lang ( 'numeric' ) );
				$this->form_validation->add_field ( 'schedule_amount', 'greater_than[0]', Lang ( 'numeric' ) );
				$this->form_validation->add_field ( 'schedule_interval', 'required', Lang ( 'required' ) );
				$this->form_validation->add_field ( 'schedule_interval', 'numeric', Lang ( 'numeric' ) );
			}
			
			if ( $this->form_validation->execute () ) {
				$cat_id = - 1;
				$maxChunkSize = $this->wb_file_manager->tobytes ( @ini_get ( 'upload_max_filesize' ) );
				
				$data = array ( 
					
					'maxChunkSize' => ( $maxChunkSize ) ? $maxChunkSize - 100 : $this->wb_file_manager->tobytes ( '1.5M' ) 
				);
				
				switch ( $method ) {
					case 'cat_direct' :
						$cat_id = $this->input->post ( 'cat_id' );
						break;
				}
				
				$schedule_id = FALSE;
				
				if ( $use_schedule == 'yes' ) {
					$this->load->model ( 'mschedule' );
					$schedule_id = $this->mschedule->add_schedule ( $this->input->post ( 'schedule_amount' ), $this->input->post ( 'schedule_interval' ) );
				}

				$this->set_bulk_sessions ( $this->input->post ( 'naming_method' ), $method, $this->input->post ( 'ignore_folders' ), $cat_id, $this->input->post ( 'add_watermarks' ), $schedule_id, $this->input->post ( 'schedule_interval' ) );

				echo load_html_template ( $data, 'jupload' );
				die ();
			}
		}
		
		$page = array ( 
			
			'page_title' => Lang ( 'bulk_upload' ), 
			'styles' => get_page_css (), 
			'javascript' => get_page_js (), 
			'bulk_form' => get_bulk_form (), 
			'message' => '' 
		);
		
		if ( get_todays_wallpapers_nr () >= 30000 ) {
			$page [ 'message' ] = evaluate_response ( 'error|' . Lang ( 'too_many_wallpapers_4_today' ) );
			$page [ 'bulk_form' ] = '';
		}
		
		load_template ( $page, 'bulk_upload' );
	}

	function java_do_upload () {
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 
			
			4 
		), TRUE );
		$this->load->library ( 'form_validation' );
		
		if ( ! isset ( $_SESSION [ $this->upload_sess . 'time_start' ] ) ) {
			$_SESSION [ $this->upload_sess . 'time_start' ] = now ();
		}
		
		$cnt = 0;
		
		if ( ! isset ( $_SESSION [ $this->upload_sess . 'size' ] ) ) {
			$this->abort_file ();
		}
		
		if ( ! isset ( $_SESSION [ $this->upload_sess . 'session_id' ] ) ) {
			$this->abort_file ();
		}
		
		$this->session_id = $_SESSION [ $this->upload_sess . 'session_id' ];
		
		foreach ( $_FILES as $value ) {
			$extension = $this->wb_file_manager->file_extension ( $value [ 'name' ] );
			if ( ! in_array ( $extension, $this->allowed_upload_extensions ) ) {
				//	skip this file and move to next one
				$this->abort_file ();
			}
			
			$jupart = ( isset ( $_POST [ 'jupart' ] ) ) ? ( int ) $_POST [ 'jupart' ] : 0;
			$jufinal = ( isset ( $_POST [ 'jufinal' ] ) ) ? ( int ) $_POST [ 'jufinal' ] : 1;
			$relpaths = ( isset ( $_POST [ 'relpathinfo' ] ) ) ? $_POST [ 'relpathinfo' ] : NULL;
			$md5sums = ( isset ( $_POST [ 'md5sum' ] ) ) ? $_POST [ 'md5sum' ] : NULL;
			
			if ( @gettype ( $relpaths ) == 'string' ) {
				$relpaths = array ( 
					
					$relpaths 
				);
			}
			
			if ( @gettype ( $md5sums ) == 'string' ) {
				$md5sums = array ( 
					
					$md5sums 
				);
			}
			
			if ( ! is_array ( $md5sums ) ) {
				$this->abort_file ();
			}
			
			if ( ! is_array ( $relpaths ) ) {
				$this->abort_file ();
			}
			
			$subdir = $this->wb_file_manager->clean_path ( $relpaths [ $cnt ] );
			$parts = explode ( '/', $subdir );
			$nparts = '';
			
			foreach ( $parts as $part ) {
				$nparts .= $this->wb_file_manager->safe ( $part ) . '/';
			}
			
			$subdir = $nparts;
			
			$dstname = $this->dest_dir . $this->tmp_prefix . $this->session_id;
			$tmpname = $this->dest_dir . $this->tmp_prefix . 'tmp' . $this->session_id;
			
			if ( ! @move_uploaded_file ( $value [ 'tmp_name' ], $tmpname ) ) {
				$this->abort_file ();
			}
			
			if ( $jupart ) {
				//	get the file size
				$len = @filesize ( $tmpname );
				
				$_SESSION [ $this->upload_sess . 'size' ] += $len;
				
				if ( $len > 0 ) {
					$src = @fopen ( $tmpname, 'rb' );
					$dst = @fopen ( $dstname, ( $jupart == 1 ) ? 'wb' : 'ab' );
					while ( $len > 0 ) {
						$rlen = ( $len > 8192 ) ? 8192 : $len;
						$buf = fread ( $src, $rlen );
						if ( ! $buf ) {
							@fclose ( $src );
							@fclose ( $dst );
							@unlink ( $dstname );
							$this->abort_file ();
						}
						
						if ( ! fwrite ( $dst, $buf, $rlen ) ) {
							@fclose ( $src );
							@fclose ( $dst );
							@unlink ( $dstname );
							$this->abort_file ();
						}
						$len -= $rlen;
					}
					
					@fclose ( $src );
					@fclose ( $dst );
					@unlink ( $tmpname );
				}
				
				if ( $jufinal ) {
					// This is the last chunk. Check total length and
					// rename it to it's final name.
					$dlen = @filesize ( $dstname );
					
					if ( $dlen != $_SESSION [ $this->upload_sess . 'size' ] ) {
						$this->abort_file ();
					}
					
					if ( $md5sums [ $cnt ] != @md5_file ( $dstname ) ) {
						$this->abort_file ();
					}
					
					$dstfinal = $this->dstfinal ( $dstname, $subdir, $value );
					
					if ( $dstfinal != FALSE ) {
						$_SESSION [ $this->upload_sess . 'files' ] ++;
						$_SESSION [ $this->upload_sess . 'total_size' ] += @filesize ( $dstfinal );
					}
					else {
						$_SESSION [ $this->upload_sess . 'skipped' ] ++;
					}
					
					//	reset file size
					$_SESSION [ $this->upload_sess . 'size' ] = 0;
				}
			}
			else {
				// Got a single file upload. Trivial.
				if ( $md5sums [ $cnt ] != @md5_file ( $tmpname ) ) {
					$this->abort_file ();
				}
				
				$dstfinal = $this->dstfinal ( $tmpname, $subdir, $value );
				
				if ( $dstfinal != FALSE ) {
					$_SESSION [ $this->upload_sess . 'files' ] ++;
					$_SESSION [ $this->upload_sess . 'total_size' ] += @filesize ( $dstfinal );
				}
				else {
					$_SESSION [ $this->upload_sess . 'skipped' ] ++;
				}
				
				//	reset file size
				$_SESSION [ $this->upload_sess . 'size' ] = 0;
			}
			$cnt ++;
		}
		
		$this->success ();
	}

	private function success () {
		die ( "SUCCESS" );
	}

	private function dstfinal ( $file, $subdir, $file_array ) {
		if ( ! @file_exists ( $file ) ) {
			return FALSE;
		}
		
		$size = @getimagesize ( $file );
		$type = detect_wallpaper_type ( get_sizes (), $size [ 1 ], $size [ 0 ] );

		$ret = $file;
		$this->load->model ( 'mcategories' );
		
		$subcat_empty = TRUE;
		$sb = explode ( '/', $subdir );
		
		foreach ( $sb as $v ) {
			if ( $v != '' ) {
				$subcat_empty = FALSE;
			}
		}

		if ( check_unique_hash ( $file ) ) {
			$wallpaper_id = $this->mwallpaper->create_dummy_ID ();

			$location = $this->wb_file_manager->clean_path ( get_wallpaper_location ( FALSE, now (), $wallpaper_id ) );
			$hash = md5_file ( $file );

			if ( ! $this->wb_file_manager->is_dir ( $location ) && ! $this->wb_file_manager->mkdir ( $location ) ) {
				$this->abort ( "Unable to create folders in order to save the wallpapers. Please make sure you have the right permissions on all files and folders" );
			}

			$ret = $this->wb_file_manager->clean_path ( $location . DS . $hash . '.jpg' );

			$cat_title = '';
			if ( ! @file_exists ( $ret ) ) {
				if ( ! @rename ( $file, $ret ) ) {
					return FALSE;
				}
				
				if ( $_SESSION [ $this->upload_sess . 'add_watermarks' ] ) {
					$ret_temp = $this->wb_file_manager->clean_path ( $location . DS . 'temp.jpg' );
					if ( ! @rename ( $ret, $ret_temp ) ) {
						return FALSE;
					}
					
					@ini_set ( "memory_limit", '256M' );
					@ini_set ( "max_execution_time", 300 );
					
					include ( ROOTPATH . '/scripts/class.upload.php' );
					$handle = new Upload ( $ret_temp );
					
					if ( $handle->uploaded ) {
						$handle->jpeg_quality = WALLPAPER_QUALITY;
						$handle->image_watermark = ROOTPATH . '/uploads/watermark/watermark.png';
						$handle->image_watermark_position = WATERMARK_POSITION;
						$handle->file_new_name_body = $hash;
						
						$handle->process ( $location );
						
						if ( ! $handle->processed ) {
							return FALSE;
						}
						@unlink ( $ret_temp );
					}
				}
			}
			
			switch ( $_SESSION [ $this->upload_sess . 'method' ] ) {
				case 'cat_direct' :
					$cat_id = $_SESSION [ $this->upload_sess . 'cat_id' ];
					$direct_cat = $this->mcategories->get_category_by_id ( $cat_id );
					
					if ( ! $direct_cat ) {
						$this->abort ( "Category not found!" );
					}
					
					$cat_id = $direct_cat->ID;
					$cat_title = $direct_cat->title;
					
					if ( $_SESSION [ $this->upload_sess . 'ignore_folders' ] == 'keep' ) {
						if ( ! $subcat_empty ) {
							$categories = array_map ( "trim", explode ( '/', $subdir ) );
							
							foreach ( $categories as $key => $cat ) {
								if ( $cat != '' ) {
									if ( $key == 0 ) {
										$seek_cat = $this->mcategories->get_category_by_parent_and_title ( $cat_id, $cat );
										if ( $seek_cat == FALSE ) {
											$cat_id = $this->mcategories->insertCat ( $cat_id, $cat, 1 );
											$cat_title = $cat;
										}
										else {
											$cat_id = $seek_cat->ID;
											$cat_title = $seek_cat->title;
										}
									}
									else {
										$seek_cat = $this->mcategories->get_category_by_parent_and_title ( $this->mcategories->get_id_by_title ( $categories [ $key - 1 ] ), $cat );
										if ( $seek_cat == FALSE ) {
											$cat_id = $this->mcategories->insertCat ( $this->mcategories->get_id_by_title ( $categories [ $key - 1 ] ), $cat, 1 );
											$cat_title = $cat;
										}
										else {
											$cat_id = $seek_cat->ID;
											$cat_title = $seek_cat->title;
										}
									}
								}
							}
						}
					}
					break;
				
				case 'structure' :
					if ( ! $subcat_empty ) {
						$categories = array_map ( "trim", explode ( '/', $subdir ) );
						
						foreach ( $categories as $key => $cat ) {
							if ( $cat != '' ) {
								if ( $key == 0 ) { //	this is the first level
									$seek_cat = $this->mcategories->get_category_by_parent_and_title ( 0, $cat );
									if ( $seek_cat == FALSE ) {
										$cat_id = $this->mcategories->insertCat ( 0, $cat, $this->mcategories->get_categories_number () + 1 );
										$cat_title = $cat;
									}
									else {
										$cat_id = $seek_cat->ID;
										$cat_title = $seek_cat->title;
									}
								}
								else {
									$seek_cat = $this->mcategories->get_category_by_parent_and_title ( $this->mcategories->get_id_by_title ( $categories [ $key - 1 ] ), $cat );
									if ( $seek_cat == FALSE ) {
										$cat_id = $this->mcategories->insertCat ( $this->mcategories->get_id_by_title ( $categories [ $key - 1 ] ), $cat, $this->mcategories->get_categories_number () + 1 );
										$cat_title = $cat;
									}
									else {
										$cat_id = $seek_cat->ID;
										$cat_title = $seek_cat->title;
									}
								}
							}
						}
					}
					else { //	get the first category available. If this one fails too, insert a dummy category
						$query = $this->db->query ( 'SELECT ID FROM ' . DBPREFIX . 'categories WHERE title = \'Bulk\'' );
						$row = ( $query->num_rows () > 0 ) ? $query->row () : FALSE;
						$cat_id = ( $row != FALSE ) ? $row->ID : $this->mcategories->insertCat ( 0, 'Bulk', 1 );
					}
					break;
			}

			if ( $_SESSION [ $this->upload_sess . 'naming_method' ] != 'file_based' ) {
				if ( $cat_title != '' ) {
					$title = $cat_title . " " . $_SESSION [ $this->upload_sess . 'files' ];
					$tags = $cat_title;
				}
				else {
					$category = $this->mcategories->get_cat_title ( $cat_id );
					$title = $category->title . " " . $_SESSION [ $this->upload_sess . 'files' ];
					$tags = $category->title;
				}
			}
			else {
				$kt = str_replace ( '.' . strtolower ( $this->wb_file_manager->file_extension ( $file_array [ 'name' ] ) ), '', strtolower ( $file_array [ 'name' ] ) );
				$title = $kt;
				$tags = $title;
			}

			$title = prepare_title ( $title );

			if ( $title == '' ) {
				$title = 'no name';
			}

			add_tags ( $wallpaper_id, $tags );
			
			if ( $_SESSION [ $this->upload_sess . 'schedule_id' ] ) {
				$active = 0;
				$this->load->model ( 'mschedule' );
				$this->mschedule->add_scheduled_wallpaper ( $_SESSION [ $this->upload_sess . 'schedule_id' ], $wallpaper_id );
			}
			else {
				$active = 1;
			}

			$this->mwallpaper->update_wallpaper ( $hash, '', '', $wallpaper_id, $title, $cat_id, 0, $type, $size [ 1 ], $size [ 0 ], $active );
		}
		else {
			return FALSE;
		}
		
		return $ret;
	}

	private function abort_file () {
		$_SESSION [ $this->upload_sess . 'skipped' ] ++;
		$this->mwallpaper->delete_dummies ();
		// remove all uploaded files of *this* request
		if ( isset ( $_FILES ) ) {
			foreach ( $_FILES as $val ) {
				$this->wb_file_manager->delete ( $val [ 'tmp_name' ] );
			}
		}
		
		// remove all temp files
		$this->wb_file_manager->delete ( $this->dest_dir . DS );
		$this->wb_file_manager->mkdir ( $this->dest_dir . DS );
		$this->success ();
	}

	private function abort ( $msg = '' ) {
		$this->abort_file ();
		// reset session vars
		$this->reset_bulk_sessions ();
		die ( 'ERROR: ' . $msg . "\n" );
	}

	function upload_show_summary () {
		global_reset_categories ();
		clear_cache ();
		
		$elapsed = nicetime ( now () - $_SESSION [ $this->upload_sess . 'time_start' ] );
		$size = ByteSize ( $_SESSION [ $this->upload_sess . 'total_size' ] );
		$files_nr = $_SESSION [ $this->upload_sess . 'files' ];
		$skipped = $_SESSION [ $this->upload_sess . 'skipped' ];
		
		$msg = sprintf ( Lang ( 'files_uploaded' ), $files_nr, $skipped, $elapsed, $size );
		
		// reset session vars
		$this->reset_bulk_sessions ();
		
		$page = array ( 
			
			'page_title' => Lang ( 'bulk_upload' ), 
			'styles' => get_page_css (), 
			'javascript' => get_page_js (), 
			'message' => evaluate_response ( 'ok|' . $msg ) 
		);
		
		load_template ( $page, 'generic' );
	}

	private function set_bulk_sessions ( $naming_method, $method, $ignore_folders, $cat_id, $enable_watermarks, $schedule_id, $schedule_interval ) {
		$_SESSION [ $this->upload_sess . 'naming_method' ] = $naming_method;
		$_SESSION [ $this->upload_sess . 'method' ] = $method;
		$_SESSION [ $this->upload_sess . 'ignore_folders' ] = $ignore_folders;
		$_SESSION [ $this->upload_sess . 'add_watermarks' ] = $enable_watermarks;
		
		$_SESSION [ $this->upload_sess . 'schedule_id' ] = $schedule_id;
		$_SESSION [ $this->upload_sess . 'schedule_interval' ] = $schedule_interval;
		
		$_SESSION [ $this->upload_sess . 'files' ] = 0;
		$_SESSION [ $this->upload_sess . 'skipped' ] = 0;
		$_SESSION [ $this->upload_sess . 'total_size' ] = 0;
		$_SESSION [ $this->upload_sess . 'time_start' ] = now ();
		
		$this->session_id = print_unique_id ();
		
		$_SESSION [ $this->upload_sess . 'size' ] = 0;
		$_SESSION [ $this->upload_sess . 'session_id' ] = $this->session_id;
		$_SESSION [ $this->upload_sess . 'cat_id' ] = $cat_id;
	}

	private function reset_bulk_sessions () {
		$_SESSION [ $this->upload_sess . 'naming_method' ] = 0;
		$_SESSION [ $this->upload_sess . 'method' ] = 0;
		$_SESSION [ $this->upload_sess . 'ignore_folders' ] = 0;
		$_SESSION [ $this->upload_sess . 'add_watermarks' ] = 0;
		
		$_SESSION [ $this->upload_sess . 'schedule_id' ] = 0;
		$_SESSION [ $this->upload_sess . 'schedule_interval' ] = 0;
		
		$_SESSION [ $this->upload_sess . 'files' ] = 0;
		$_SESSION [ $this->upload_sess . 'total_size' ] = 0;
		$_SESSION [ $this->upload_sess . 'time_start' ] = 0;
		
		$this->session_id = print_unique_id ();
		
		$_SESSION [ $this->upload_sess . 'size' ] = 0;
		$_SESSION [ $this->upload_sess . 'session_id' ] = 0;
		$_SESSION [ $this->upload_sess . 'cat_id' ] = 0;
	}

	function edit () {
		$wallpaper_id = $this->uri->segment ( 3 );
		$wallpaper = get_wallpaper ( $wallpaper_id );
		
		if ( ! $wallpaper ) {
			redirect ();
		}
		
		$this->site_sentry->checklogin ();
		if ( $wallpaper->user_id != get_mem_info ( 'ID' ) ) {
			$this->permissions->checkPermissions ( array ( 
				
				4 
			), TRUE );
		}
		
		$this->load->library ( 'form_validation' );
		
		$content = '';
		$content .= get_edit_wallpapers_form ( $wallpaper );
		$right = get_right_side_content ();
		
		$page = array ( 
			
			'content' => $content, 
			'right' => $right, 
			'header_msg' => Lang ( 'edit_wallpaper' ) . ': ' . $wallpaper->file_title 
		);
		$page = assign_global_variables ( $page, 'edit_wallpaper' );
		load_template ( $page, 'template' );
	}

	function insert_rating () {
		$id = $this->uri->segment ( 3 );
		$rating = $this->input->post ( 'rating' );
		
		if ( ! check_if_voted ( $id, $this->input->ip_address () ) && $rating != FALSE ) {
			$this->mwallpaper->insert_rating ( $id, $rating );
			$this->smarty->clear_cache ( null, $this->uri->segment ( 4 ) );
		}
		
		echo Lang ( 'current_rating' ) . '<b>' . get_wallpaper_rating ( $id ) . '</b> ' . Lang ( 'from' ) . ' <b>' . get_votes_nr ( $id ) . '</b> votes';
		die ();
	}

	function download () {
		if ( ! is_logged_in () && ! GUESTS_CAN_DOWNLOAD ) {
			redirect ( 'generic_messages/guests_cant_download' );
		}

		$this->load->library ( 'wb_file_manager' );
		
		$id = $this->uri->segment ( 3 );
		$width = $this->uri->segment ( 4 );
		$height = $this->uri->segment ( 5 );
		$type = detect_wallpaper_type ( get_sizes (), $height, $width );
		
		if ( $type == 'other' ) {
			$row = get_wallpaper ( $id );
			$new_file = get_wallpaper_url_location ( $row ) . $row->hash . '.jpg';
			$new_file_location = get_wallpaper_location ( $row ) . $row->hash . '.jpg';
		}
		else {
			$row = $this->mwallpaper->get_wallpaper_for_download ( $id, $type, $width, $height );

			if ( ! $row ) {
				die ( "Wallpaper not found" );
			}

			$location = get_wallpaper_location ( $row );
			$url_location = get_wallpaper_url_location ( $row );
			$original_file = $location . $row->hash . '.jpg';

			if ( $row->width != $width ) {
				$new_file = $url_location . $width . $height . $row->hash . '.jpg';
				$new_file_location = $location . $width . $height . $row->hash . '.jpg';

				if ( ! file_exists ( $location . $width . $height . $row->hash . '.jpg' ) ) {
					include_once ( ROOTPATH . '/scripts/class.upload.php' );
					$handle = new Upload ( $original_file );
					
					if ( $handle->uploaded ) {
						$handle->jpeg_quality = WALLPAPER_QUALITY;
						$handle->image_resize = true;
						$handle->image_x = $width;
						$handle->image_y = $height;
						$handle->file_new_name_body = $width . $height . $row->hash;
						$handle->process ( $location );
						
						if ( $handle->processed ) {
							unset ( $handle );
						}
					}
				}
			}
			else {
				$new_file = $url_location . $row->hash . '.jpg';
				$new_file_location = $original_file;
			}
		}

		$this->mwallpaper->increment_downloads ( $id );

		if ( OPEN_WALLPAPERS_IN_NEW_WINDOW ) {
			$page = array ( 
				
				'page_title' => Lang ( 'download' ) . ' ' . $row->file_title, 
				'styles' => get_page_css (), 
				'javascript' => get_page_js (), 
				'message' => load_html_template ( array ( 
					
					'new_file' => $new_file 
				), 'download' ) 
			);
	
			load_template ( $page, 'generic' );
		}
		else {
			//	force download
			if ( $row->title_alias != '' ) {
	                        $title = preg_replace ( '/\W/', '_', $row->title_alias );
	                }
	                else {
	                        $title = preg_replace ( '/\W/', '_', $row->file_title );
	                }
			$this->load->library ( 'wb_file_manager' );
			$filename = "{$title}_{$width}-{$height}.jpg";
			$this->wb_file_manager->download ( $new_file_location,  TRUE, 200, $filename );
			exit;
		}
	}

	function manage () {
		$this->site_sentry->checklogin ();
		$this->permissions->checkPermissions ( array ( 
			
			4 
		), TRUE );
		
		$status = $this->uri->segment ( 3, 1 );
		$start = $this->uri->segment ( 4, 0 );
		
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'pagination' );
		$this->load->model ( 'msearch_queries' );
		
		$title_filter = $this->input->post ( 'title_filter' );
		$user_filter = $this->input->post ( 'user_filter' );
		$is_query_saved = $this->uri->segment ( 5 );
		$limit = 20;
		
		if ( $is_query_saved ) {
			$q_pre = $this->msearch_queries->get ( $is_query_saved );
			$query_id = $is_query_saved;
		}
		else {
			$q_pre = 'SELECT
                        		SQL_CALC_FOUND_ROWS 
                        		w.*,
                        		u.Username
                        	FROM
                        		' . DBPREFIX . 'wallpapers w
                        	LEFT JOIN
                        		' . DBPREFIX . 'users u 
                        		ON(u.ID=w.user_id)
                        	WHERE
                                        w.parent_id = 0';
			
			if ( $title_filter != FALSE ) {
				$q_pre .= '     AND
                                                        w.file_title
                                                LIKE
                                                        "%' . strip_punctuation ( $title_filter ) . '%"';
			}
			
			if ( $user_filter != FALSE ) {
				$q_pre .= '     AND
							u.Username
						LIKE "%' . strip_punctuation ( $user_filter ) . '%"';
			}
			
			$q_pre .= '    AND w.active = ' . qstr ( $status );
			$q_pre .= "     GROUP BY w.ID ORDER BY w.date_added DESC";
			
			$query_id = $this->msearch_queries->save ( $q_pre );
		}
		
		$this->pagination->start = $start;
		$this->pagination->limit = $limit;
		$this->pagination->filePath = site_url ( 'wallpapers/manage/' . $status );
		$this->pagination->thequery = stripslashes ( $q_pre ) . " LIMIT $start, " . qstr ( ( int ) $limit );
		$this->pagination->otherParams = "/$query_id";
		
		$query = $this->pagination->getQuery ( TRUE );
		$pagination = $this->pagination->paginate ();
		
		$content = get_wallpapers_overview_table () . get_wallpapers_table ( $query, $status );
		$content .= ( ! empty ( $pagination ) ) ? $pagination : '';
		$right = get_right_side_content ();
		
		$page = array ( 
			
			'content' => $content, 
			'right' => $right, 
			'header_msg' => Lang ( 'manage_wallpapers' ) 
		);
		
		$page = assign_global_variables ( $page, 'manage_wallpapers' );
		load_template ( $page, 'template' );
	}

	function do_edit () {
		$this->site_sentry->checklogin ();
		$wallpaper_id = $this->uri->segment ( 3 );
		
		$row = get_wallpaper ( $wallpaper_id );
		if ( $row->user_id != $this->session->userdata ( AUTH_SESSION_ID ) ) {
			$this->permissions->checkPermissions ( array ( 
				
				4 
			), TRUE );
		}

		if ( ! SAFE_MODE ) {
			@ini_set ( "memory_limit", '512M' );
			@ini_set ( "max_execution_time", 300 );
		}

		$row_user = $this->musers->get_member_by_id ( $this->session->userdata ( AUTH_SESSION_ID ) );
		$wallpaper_state = ( $this->site_sentry->isadmin () ) ? TRUE : ( $row_user->auto_approve == 1 ) ? TRUE : FALSE;

		$this->load->library ( 'form_validation' );

		if ( ! empty ( $_POST ) ) {
			$title_alias = $this->input->post ( 'title_alias' );

			if ( $title_alias != FALSE ) {
				$this->form_validation->add_field ( 'title_alias', 'title_alias', Lang ( 'invalid_alias' ) );
			}

			$this->form_validation->add_field ( 'cat_id', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'file_title', 'required', Lang ( 'required' ) );

			if ( $this->form_validation->execute () ) {
				$state = ( $this->site_sentry->isadmin () ) ? 1 : ( $row_user->auto_approve == 1 ) ? 1 : 0;
				$title = $this->input->post ( 'file_title' );
				$cat_id = $this->input->post ( 'cat_id' );
				$keywords = $this->input->post ( 'keywords' );
				$description = $this->input->post ( 'description' );
				$msg = 'error|' . Lang ( 'wallpaper_n_edited' );
				$updated = FALSE;
				$files = $_FILES [ 'wallpapers' ];
				$added_types = array ();
				$parent_id = 0;

				$this->load->model ( 'mtags' );
				$this->load->model ( 'mcolors' );

				$this->mcolors->delete_by_wallpaper ( $row->ID );
				$this->mtags->delete_by_wallpaper ( $row->ID );

				add_tags ( $row->ID, $keywords );

				if ( ! $files [ "size" ] [ 0 ] ) {
					$this->mwallpaper->update_wallpaper ( $row->hash, $title_alias, $description, $row->ID, $title, $cat_id, 0, $row->type, $row->height, $row->width, $state );
				}
				else {
					rmdir_r ( get_wallpaper_location ( $row ), FALSE );
					$this->mwallpaper->delete_childs ( $row->ID );

					foreach ( $files [ "error" ] as $i => $error ) {
						$img_size = @getimagesize ( $files [ 'tmp_name' ] [ $i ] );
						$type = detect_wallpaper_type ( get_sizes (), $img_size [ 1 ], $img_size [ 0 ] );

						if ( $error != UPLOAD_ERR_OK || in_array ( $type, $added_types ) ) {
							continue;
						}

						if ( $files [ 'size' ] [ $i ] ) {
							$hash = @md5_file ( $files [ 'tmp_name' ] [ $i ] );

							if ( ! $parent_id ) {
								$this->mwallpaper->update_wallpaper ( $row->hash, $title_alias, $description, $row->ID, $title, $cat_id, 0, $type, $img_size [ 1 ], $img_size [ 0 ], $state );
								$parent_id = $row->ID;
								$insert_id = $row->ID;
							}
							else {
								$title = '';
								$description = '';
								$title_alias = '';
								$insert_id = $this->mwallpaper->add_wallpaper ( $row_user->ID, $hash, $title_alias, $description, $title, $cat_id, $type, $img_size [ 1 ], $img_size [ 0 ], $state, $parent_id, $row->date_added );
							}

							$single_data = array (
								'name' => $files [ 'name' ] [ $i ], 
								'type' => $files [ 'type' ] [ $i ], 
								'tmp_name' => $files [ 'tmp_name' ] [ $i ], 
								'error' => $files [ 'error' ] [ $i ], 
								'size' => $files [ 'size' ] [ $i ] 
							);

							$this->__add_wallpaper ( $insert_id, $single_data );
							array_push ( $added_types, $type );
						}
					}
					
					clear_cache ();
					global_reset_categories ();
				}
				redirect ( 'wallpapers/show/' . $row->ID );
			}
		}

		$content = '';
		if ( isset ( $msg ) ) {
			$content .= evaluate_response ( $msg );
		}
		$content .= get_edit_wallpapers_form ( $row );
		$right = get_right_side_content ();
		
		$page = array ( 
			
			'content' => $content, 
			'right' => $right, 
			'header_msg' => Lang ( 'edit_wallpaper' ) . ': ' . $row->file_title 
		);
		$page = assign_global_variables ( $page, 'edit_wallpaper' );
		load_template ( $page, 'template' );
	}

	function __add_wallpaper ( $id, $file ) {
		include_once ( ROOTPATH . '/scripts/class.upload.php' );
		$handle = new Upload ( $file );
		$row = get_wallpaper ( $id );

		if ( $handle->uploaded ) {
			$handle->jpeg_quality = WALLPAPER_QUALITY;

			if ( ENABLE_WATERMARK ) {
				$handle->image_watermark = ROOTPATH . '/uploads/watermark/watermark.png';
				$handle->image_watermark_position = WATERMARK_POSITION;
			}

			$handle->process ( get_wallpaper_location ( $row ) );

			if ( $handle->processed ) {
				$hash = md5_file ( get_wallpaper_location ( $row ) . $handle->file_dst_name );
				if ( @rename ( get_wallpaper_location ( $row ) . $handle->file_dst_name, get_wallpaper_location ( $row ) . $hash . '.jpg' ) ) {
					$this->mwallpaper->update_wallpaper_details ( $row->ID, $hash );
				}

				unset ( $handle );
				return get_wallpaper_location ( $row ) . $hash . '.jpg';
			}
		}
		return FALSE;
	}

	function add_wallpaper () {
		if ( ! GUESTS_CAN_UPLOAD && ! is_logged_in () ) {
			die ( "<script type=\"text/javascript\">parent.close_dialog();</script>" );
		}

		$msg = '';

		if ( ! SAFE_MODE ) {
			@ini_set ( "memory_limit", '512M' );
			@ini_set ( "max_execution_time", 300 );
		}

		$this->load->library ( 'form_validation' );

		$row_user = $this->musers->get_member_by_id ( $this->session->userdata ( AUTH_SESSION_ID ) );
		if ( $row_user == FALSE ) {
			$row_user = $this->musers->get_member_by_username ( 'Guest' );

			if ( $row_user == FALSE ) {
				$this->musers->add_new_member ( 'Guest', print_unique_id (), 'g.' . ADMIN_EMAIL, 2, 1 );
				$row_user = $this->musers->get_member_by_username ( 'Guest' );
			}
		}
		
		$_submit_check = $this->input->post ( '_submit_check', TRUE );

		if ( $_submit_check != FALSE ) {
			$title_alias = do_xhtml ( $this->input->post ( 'title_alias', TRUE ) );

			if ( $title_alias != FALSE ) {
				$this->form_validation->add_field ( 'title_alias', 'title_alias', Lang ( 'invalid_alias' ) );
			}

			$this->form_validation->add_field ( 'cat_id', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'file_title', 'required', Lang ( 'required' ) );

			$files = $_FILES [ 'wallpapers' ];
			if ( ! $files [ "size" ] [ 0 ] ) {
				$this->form_validation->_errors [ 'wallpapers' ] [] = Lang ( 'required' );
			}
			else {
				if ( $this->form_validation->execute () ) {
					$state = ( $this->site_sentry->isadmin () ) ? 1 : ( $row_user->auto_approve == 1 ) ? 1 : 0;
					$title = do_xhtml ( $this->input->post ( 'file_title', TRUE ) );
					$cat_id = do_xhtml ( $this->input->post ( 'cat_id', TRUE ) );
					$keywords = do_xhtml ( $this->input->post ( 'keywords', TRUE ) );
					$description = do_xhtml ( $this->input->post ( 'description', TRUE ) );
					$msg = evaluate_response ( 'error|' . Lang ( 'wallpaper_n_added' ) );
					$parent_id = 0;
					$date_added = now ();
					$added_types = array ();

					foreach ( $files [ "error" ] as $i => $error ) {
						$img_size = @getimagesize ( $files [ 'tmp_name' ] [ $i ] );
						$type = detect_wallpaper_type ( get_sizes (), $img_size [ 1 ], $img_size [ 0 ] );
	
						if ( $error != UPLOAD_ERR_OK || in_array ( $type, $added_types ) ) {
							continue;
						}
	
						if ( $files [ 'size' ] [ $i ] ) {
							$hash = @md5_file ( $files [ 'tmp_name' ] [ $i ] );

							if ( $parent_id ) {
								// we don't need those on childs
								$title_alias = '';
								$description = '';
								$title = '';
							}
							
							$insert_id = $this->mwallpaper->add_wallpaper ( $row_user->ID, $hash, $title_alias, $description, $title, $cat_id, $type, $img_size [ 1 ], $img_size [ 0 ], $state, $parent_id, $date_added );
							
							if ( ! $parent_id ) {
								$parent_id = $insert_id;
							}
							
							$single_data = array ( 
								
								'name' => $files [ 'name' ] [ $i ], 
								'type' => $files [ 'type' ] [ $i ], 
								'tmp_name' => $files [ 'tmp_name' ] [ $i ], 
								'error' => $files [ 'error' ] [ $i ], 
								'size' => $files [ 'size' ] [ $i ] 
							);
							
							$this->__add_wallpaper ( $insert_id, $single_data );
							array_push ( $added_types, $type );
						}
					}
					
					if ( $parent_id ) {
						add_tags ( $parent_id, $keywords );
						clear_cache ();
						global_reset_categories ();
						$msg = '<script type="text/javascript">parent.document.location=\'' . get_wallpaper_url ( get_wallpaper ( $parent_id ) ) . '\';parent.showUpdate();parent.close_dialog();</script>';
					}
				}
			}
		}
		
		$page = array ( 
			
			'page_title' => Lang ( 'manage_users' ), 
			'styles' => get_page_css ( 'manage_users' ), 
			'javascript' => get_page_js ( 'manage_users' ), 
			'admin_add_user_form' => get_add_wallpapers_form (), 
			'message' => $msg 
		);
		
		echo load_template ( $page, 'add_user' );
		die ();
	}

	function options () {
		$this->site_sentry->checklogin ();
		if ( numeric ( $this->uri->segment ( 4 ) ) && $this->uri->segment ( 3 ) != '' ) {
			$ID = $this->uri->segment ( 4 );
			$action = $this->uri->segment ( 3 );
			$status = $this->uri->segment ( 5 );
			
			switch ( $action ) {
				case 'activate' :
					$this->permissions->checkPermissions ( array ( 
						
						6 
					), TRUE );
					
					$row = get_wallpaper ( $ID );
					if ( $row != FALSE ) {
						$this->mwallpaper->activate ( $ID );
						global_reset_categories ();
					}
					redirect ( 'wallpapers/manage/' . $status, 'location' );
					break;
				
				case 'edit' :
					$row = get_wallpaper ( $ID );
					if ( $row != FALSE ) {
						if ( $row->user_id != $this->session->userdata ( AUTH_SESSION_ID ) ) {
							$this->permissions->checkPermissions ( array ( 
								
								4 
							), TRUE );
						}
						redirect ( 'wallpapers/edit/' . $ID, 'location' );
					}
					break;
				
				case 'suspend' :
					$this->permissions->checkPermissions ( array ( 
						
						7 
					), TRUE );
					
					$row = get_wallpaper ( $ID );
					if ( $row != FALSE ) {
						$this->mwallpaper->suspend ( $ID );
						global_reset_categories ();
					}
					redirect ( 'wallpapers/manage/' . $status, 'location' );
					break;
				
				case 'delete' :
					$row = get_wallpaper ( $ID );
					if ( $row != FALSE ) {
						if ( $row->user_id != $this->session->userdata ( AUTH_SESSION_ID ) ) {
							$this->permissions->checkPermissions ( array ( 
								
								5 
							), TRUE );
						}
						delete_wallpaper ( $row->ID );
						global_reset_categories ();
						
						if ( $row->user_id == $this->session->userdata ( AUTH_SESSION_ID ) ) {
							redirect ( 'members/index/' . $status, 'location' );
						}
						else {
							redirect ( 'wallpapers/manage/' . $status, 'location' );
						}
					}
					else {
						redirect ( '', 'location' );
					}
					break;
			}
		}
	}

	function mass_options () {
		$array = $this->input->post ( 'tablechoice' );
		$action = $this->input->post ( 'mass_action' );
		
		if ( $array == FALSE ) {
			exit ();
		}
		
		switch ( $action ) {
			case 'mass_delete' :
				foreach ( $array as $value ) {
					$row = get_wallpaper ( $value );
					if ( $row != FALSE ) {
						if ( $row->user_id != $this->session->userdata ( AUTH_SESSION_ID ) ) {
							if ( ! $this->permissions->checkPermissions ( array ( 
								
								5 
							) ) ) {
								exit ();
							}
						}
						delete_wallpaper ( $value );
					}
				}
				break;
			
			case 'mass_suspend' :
				if ( ! $this->permissions->checkPermissions ( array ( 
					
					7 
				) ) ) {
					exit ();
				}
				
				foreach ( $array as $value ) {
					$row = get_wallpaper ( $value );
					if ( $row != FALSE ) {
						$this->mwallpaper->suspend ( $value );
					}
				}
				break;
			
			case 'mass_activate' :
				if ( ! $this->permissions->checkPermissions ( array ( 
					
					6 
				) ) ) {
					exit ();
				}
				
				foreach ( $array as $value ) {
					$row = get_wallpaper ( $value );
					if ( $row != FALSE ) {
						$this->mwallpaper->activate ( $value );
					}
				}
				break;
		}
		
		global_reset_categories ();
		exit ();
	}

	function get_frontpage_footer_walls () {
		$most_downloaded = $this->mwallpaper->get_most_downloaded ( 10 );
		$tags_most_downloaded = array ( 
			
			'wallpapers' => $most_downloaded 
		);
		
		$highest_rated = $this->mwallpaper->get_highest_rated ( 10 );
		$tags_highest_rated = array ( 
			
			'wallpapers' => $highest_rated 
		);
		
		$recently_added = $this->mwallpaper->get_recently_added ( 10 );
		$tags_recently_added = array ( 
			
			'wallpapers' => $recently_added 
		);
		echo load_html_template ( $tags_most_downloaded, 'most_downloaded', TRUE, 0 ) . load_html_template ( $tags_highest_rated, 'highest_rated', TRUE, 0 ) . load_html_template ( $tags_recently_added, 'recently_added', TRUE, 0 );
		die ();
	}

}

//END