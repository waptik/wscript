<?php

class Admin extends Controller {

	public $message = '';

	function __construct () {
		parent::Controller ();
		$this->site_sentry->checklogin ();
		$this->load->model ( 'master' );
		$this->load->library ( 'pagination' );
		$this->load->library ( 'form_validation' );
		$this->load->helper ( 'form' );
		$this->load->helper ( 'wallpapers' );
		$this->load->helper ( 'users' );
	}

	function modules () {
		if ( ! is_admin () ) {
			redirect ();
			exit ();
		}
		
		$content = '';

		require_once APPPATH . 'libraries/WS_Modules.php';
		$modules = WS_Modules::getInstance ();
		$outModules = $modules->getModulesData ();

		if ( ! count ( $outModules ) ) {
			$content = evaluate_response ( 'info|'. Lang ( 'no_modules' ) );
		}
		else {
			$content .= load_html_template ( array ( 
				'modules' => $outModules
			), 'admin_modules' );
		}

		$page = array ( 
			'content' => $content, 'right' => get_right_side_content (), 'header_msg' => Lang ( 'module_settings' ) 
		);
		
		$page = assign_global_variables ( $page, 'module_settings' );
		load_template ( $page, 'template' );
	}

	function disable_module () {
		if ( ! is_admin () || empty ( $_POST ) ) {
			exit ();
		}

		require_once APPPATH . "libraries/WS_Modules.php";
		$modules = WS_Modules::getInstance ();
		$modulesData = $modules->getModulesData ();

		if ( isset ( $modulesData [ $_POST [ 'module_name' ] ] ) ) {
			$modules->disableModule ( $_POST [ 'module_name' ] );
		}
		clear_cache ();

		exit;
	}

	function enable_module () {
		if ( ! is_admin () || empty ( $_POST ) ) {
			exit ();
		}

		require_once APPPATH . "libraries/WS_Modules.php";
		$modules = WS_Modules::getInstance ();
		$modulesData = $modules->getModulesData ();

		if ( isset ( $modulesData [ $_POST [ 'module_name' ] ] ) ) {
			$modules->enableModule ( $_POST [ 'module_name' ] );
		}
		clear_cache ();

		exit;
	}

	function save_watermark_settings () {
		$this->permissions->checkPermissions ( array ( 
			35 
		), TRUE );
		$this->load->library ( 'ws_settings_writer' );
		
		$settings = array ();
		
		$settings [ 'ENABLE_WATERMARK' ] = prepare_constant ( $this->input->post ( 'ENABLE_WATERMARK' ), TRUE );
		$settings [ 'WATERMARK_POSITION' ] = prepare_constant ( $this->input->post ( 'WATERMARK_POSITION' ) );
		
		$target_path = dirname ( BASEPATH ) . "/uploads/watermark/";
		include ( dirname ( BASEPATH ) . '/scripts/class.upload.php' );
		$file = ROOTPATH . "/uploads/watermark/watermark_bg.jpg";
		$handle = new Upload ( $file );
		
		if ( $handle->uploaded ) {
			$handle->jpeg_quality = 100;
			$handle->file_new_name_body = 'watermark_bg_compiled';
			$handle->file_overwrite = TRUE;
			$handle->file_auto_rename = FALSE;
			$handle->image_watermark = ROOTPATH . '/uploads/watermark/watermark.png';
			$handle->image_watermark_position = $this->input->post ( 'WATERMARK_POSITION' );
			$handle->process ( $target_path );

			if ( $handle->processed ) {
				//low
			}
		}

		unset ( $handle );

		$this->ws_settings_writer->Set ( 'Settings', $settings );
		$this->ws_settings_writer->ConfigFile = 'watermark.php';
		$this->ws_settings_writer->Save ();
		
		redirect ( 'admin/watermark_settings' );
	}

	function watermark_settings () {
		$this->permissions->checkPermissions ( array ( 
			34 
		), TRUE );
		
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header ( 'Cache-Control: post-check=0, pre-check=0', FALSE );
		header ( "Pragma: no-cache" );
		
		$content = '';
		$content .= get_watermark_settings_form ();
		$right = get_right_side_content ();
		$page = array ( 
			'content' => $content, 'right' => $right, 'header_msg' => Lang ( 'watermark_settings' ) 
		);
		$page = assign_global_variables ( $page, 'watermark_settings' );
		load_template ( $page, 'template' );
	}

	function widgets () {
		if ( ! is_admin () ) {
			redirect ();
			exit ();
		}

		require_once APPPATH . 'libraries/WS_Sidebar.php';
		$sections = WS_Sidebar::getInstance ();

		$content = '';
		$content .= evaluate_response ( 'info|' . Lang ( 'widgets_guide' ) );
		$content .= load_html_template ( array ( 
			'sections' => $sections->getSections ()
		), 'admin_widgets' );
		
		$page = array ( 
			'content' => $content, 'right' => get_right_side_content (), 'header_msg' => Lang ( 'widgets_settings' ) 
		);
		
		$page = assign_global_variables ( $page, 'widgets_settings' );
		load_template ( $page, 'template' );
	}

	function change_widgets () {
		if ( ! is_admin () || empty ( $_POST ) ) {
			exit ();
		}

		require_once APPPATH . "libraries/WS_Sidebar.php";
		$loadedSections = WS_Sidebar::getInstance ();
		$sections = $loadedSections->getSections ();

		$i = 1;
		foreach ( $_POST as $key => $val ) {
			if ( isset ( $sections [ $key ] ) ) {
				if ( ( bool ) $sections [ $key ] [ 'show' ] ) {
					$sections [ $key ] [ 'order' ] = $i;
					$i++;
				}
			}
			unset ( $sections [ $key ] [ 'title' ], $sections [ $key ] [ 'description' ], $sections [ $key ] [ 'content' ] );
		}

		set_setting ( 'ws_sections', serialize ( $sections ) );
		clear_cache ();
		exit;
	}

	function enable_widget () {
		if ( ! is_admin () || empty ( $_POST ) ) {
			exit ();
		}

		require_once APPPATH . "libraries/WS_Sidebar.php";
		$sidebar = WS_Sidebar::getInstance ();
		$sidebar->enableSection ( $_POST [ 'widget_name' ] );
		clear_cache ();

		exit;
	}

	function disable_widget () {
		if ( ! is_admin () || empty ( $_POST ) ) {
			exit ();
		}

		require_once APPPATH . "libraries/WS_Sidebar.php";
		$sidebar = WS_Sidebar::getInstance ();
		$sidebar->disableSection ( $_POST [ 'widget_name' ] );
		clear_cache ();

		exit;
	}

	function index () {
		$this->permissions->checkPermissions ( array ( 
			35 
		), TRUE );
		$content = '';
		$content .= '		<h2>' . Lang ( 'wallpapers' ) . '</h2>' . "\n";
		$content .= get_wallpapers_overview_table ();
		$content .= '		<div style="margin-top:20px">' . "\n";
		$content .= '		<h2>' . ucfirst ( Lang ( 'members' ) ) . '</h2>' . "\n";
		$content .= get_users_overview_table ();
		$content .= '		</div>' . "\n";
		$right = get_right_side_content ();
		$page = array ( 
			'content' => $content, 'right' => $right, 'header_msg' => Lang ( 'admin' ) 
		);
		$page = assign_global_variables ( $page, 'admin' );
		load_template ( $page, 'template' );
	}

	function admin_settings () {
		$this->permissions->checkPermissions ( array ( 
			35 
		), TRUE );
		$content = '';
		$content .= get_admin_settings_form ();
		
		$right = get_right_side_content ();
		
		$page = array ( 
			'content' => $content, 'right' => $right, 'header_msg' => Lang ( 'admin_settings' ) 
		);
		
		$page = assign_global_variables ( $page, 'admin_settings' );
		
		load_template ( $page, 'template' );
	}

	function changebg () {
		$new_bg = $this->uri->segment ( 3, TRUE );
		set_setting ( 'bg_pattern', $new_bg );
		$this->session->unset_userdata ( 'ws_settings' );
		clear_cache ();
		die ( 'SUCCESS' );
	}

	function upload_watermark () {
		$this->permissions->checkPermissions ( array ( 
			34 
		), TRUE );
		
		$target_path = dirname ( BASEPATH ) . "/uploads/watermark/";
		include ( dirname ( BASEPATH ) . '/scripts/class.upload.php' );
		$handle = new Upload ( $_FILES [ 'watermark_picture' ] );
		
		if ( $handle->uploaded ) {
			$handle->file_new_name_body = 'watermark';
			$handle->file_overwrite = TRUE;
			$handle->file_auto_rename = FALSE;
			$handle->allowed = array ( 
				'image/*' 
			);
			$handle->image_convert = 'png';
			$handle->process ( $target_path );

			if ( ! $handle->processed ) {
				die ( $handle->error );
			}
			else {
				$file = ROOTPATH . "/uploads/watermark/watermark_bg.jpg";
				$handle = new Upload ( $file );

				if ( $handle->uploaded ) {
					$handle->jpeg_quality = 100;
					$handle->file_new_name_body = 'watermark_bg_compiled';
					$handle->file_overwrite = TRUE;
					$handle->file_auto_rename = FALSE;
					$handle->image_watermark = dirname ( BASEPATH ) . '/uploads/watermark/watermark.png';
					$handle->image_watermark_position = WATERMARK_POSITION;
					$handle->process ( $target_path );
					
					if ( $handle->processed ) {
						//low
					}
				}

				unset ( $handle );
			}
		}
		
		unset ( $handle );
		redirect ( 'admin/watermark_settings' );
	}

	function save_admin_settings () {
		$this->load->library ( 'ws_settings_writer' );
		$saved = FALSE;
		$this->permissions->checkPermissions ( array ( 
			34 
		), TRUE );
		$_submit_check = $this->input->post ( '_submit_check', TRUE );
		
		if ( $_submit_check != FALSE ) {
			$this->form_validation->add_field ( 'HOSTNAME', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'DATABASE', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'DBUSER', 'required', Lang ( 'required' ) );
			
			if ( $this->input->post ( 'LICENSE_KEY', TRUE ) != FALSE ) {
				$this->form_validation->add_field ( 'LICENSE_KEY', 'exact_length[32]', Lang ( 'invalid_license' ) );
			}
			
			$this->form_validation->add_field ( 'ADMIN_EMAIL', 'valid_email', Lang ( 'valid_email' ) );
			$this->form_validation->add_field ( 'DOMAIN_NAME', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'SITE_NAME', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'SITE_SLOGAN', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'LANG_TYPE', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'RUN_ON_DEVELOPMENT', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'WALLPAPER_QUALITY', 'required', Lang ( 'required' ) );
			$this->form_validation->add_field ( 'WALLPAPER_QUALITY', 'numeric', Lang ( 'numeric' ) );
			
			$this->form_validation->add_field ( 'USE_SMTP', 'required', Lang ( 'required' ) );
			if ( $this->input->post ( 'USE_SMTP', TRUE ) != FALSE && $this->input->post ( 'USE_SMTP', TRUE ) == 1 ) {
				$this->form_validation->add_field ( 'SMTP_PORT', 'required', Lang ( 'required' ) );
				$this->form_validation->add_field ( 'SMTP_HOST', 'required', Lang ( 'required' ) );
				$this->form_validation->add_field ( 'SMTP_USER', 'required', Lang ( 'required' ) );
				$this->form_validation->add_field ( 'SMTP_PASS', 'required', Lang ( 'required' ) );
			}
			
			$this->form_validation->add_field ( 'MAIL_IS_HTML', 'required', Lang ( 'required' ) );
			
			if ( $this->form_validation->execute () ) {
				$settings = array ();
				
				$logo = $_FILES [ 'logo' ];
				
				if ( $logo [ 'size' ] ) {
					move_uploaded_file ( $logo [ 'tmp_name' ], ROOTPATH . "/various/logo.gif" );
				}
				
				$settings [ 'HOSTNAME' ] = prepare_constant ( $this->input->post ( 'HOSTNAME' ) );
				$settings [ 'DATABASE' ] = prepare_constant ( $this->input->post ( 'DATABASE' ) );
				$settings [ 'DBUSER' ] = prepare_constant ( $this->input->post ( 'DBUSER' ) );
				$settings [ 'DBPASS' ] = prepare_constant ( $this->input->post ( 'DBPASS' ) );
				$settings [ 'DBPREFIX' ] = prepare_constant ( DBPREFIX );
				$settings [ 'DEFAULT_USERNAME' ] = prepare_constant ( $this->input->post ( 'DEFAULT_USERNAME' ) );
				$settings [ 'DEFAULT_PASSWORD' ] = prepare_constant ( $this->input->post ( 'DEFAULT_PASSWORD' ) );
				$settings [ 'SECURITY_KEY' ] = prepare_constant ( SECURITY_KEY );
				$settings [ 'APPLICATION_URL' ] = prepare_constant ( $this->input->post ( 'APPLICATION_URL' ) );
				$settings [ 'ADMIN_EMAIL' ] = prepare_constant ( $this->input->post ( 'ADMIN_EMAIL' ) );
				$settings [ 'DOMAIN_NAME' ] = prepare_constant ( $this->input->post ( 'DOMAIN_NAME' ) );
				$settings [ 'SITE_NAME' ] = prepare_constant ( $this->input->post ( 'SITE_NAME' ) );
				$settings [ 'SITE_SLOGAN' ] = prepare_constant ( $this->input->post ( 'SITE_SLOGAN' ) );
				$settings [ 'USE_SMTP' ] = prepare_constant ( $this->input->post ( 'USE_SMTP' ), TRUE );
				
				if ( $this->input->post ( 'USE_SMTP' ) ) {
					$settings [ 'SMTP_PORT' ] = prepare_constant ( $this->input->post ( 'SMTP_PORT' ) );
					$settings [ 'SMTP_HOST' ] = prepare_constant ( $this->input->post ( 'SMTP_HOST' ) );
					$settings [ 'SMTP_USER' ] = prepare_constant ( $this->input->post ( 'SMTP_USER' ) );
					$settings [ 'SMTP_PASS' ] = prepare_constant ( $this->input->post ( 'SMTP_PASS' ) );
				}
				else {
					$settings [ 'SMTP_PORT' ] = prepare_constant ( '' );
					$settings [ 'SMTP_HOST' ] = prepare_constant ( '' );
					$settings [ 'SMTP_USER' ] = prepare_constant ( '' );
					$settings [ 'SMTP_PASS' ] = prepare_constant ( '' );
				}
				
				$settings [ 'LANG_TYPE' ] = prepare_constant ( $this->input->post ( 'LANG_TYPE' ) );
				$settings [ 'MAIL_IS_HTML' ] = prepare_constant ( $this->input->post ( 'MAIL_IS_HTML' ), TRUE );
				$settings [ 'KEEP_LOGGED_IN_FOR' ] = prepare_constant ( KEEP_LOGGED_IN_FOR );
				$settings [ 'APPLICATION_INDEX_PAGE' ] = prepare_constant ( APPLICATION_INDEX_PAGE );
				
				$settings [ 'REDIRECT_TO_LOGIN' ] = prepare_constant ( REDIRECT_TO_LOGIN );
				$settings [ 'REDIRECT_AFTER_LOGIN' ] = prepare_constant ( REDIRECT_AFTER_LOGIN );
				$settings [ 'REDIRECT_ON_LOGOUT' ] = prepare_constant ( REDIRECT_ON_LOGOUT );
				$settings [ 'RUN_ON_DEVELOPMENT' ] = prepare_constant ( $this->input->post ( 'RUN_ON_DEVELOPMENT' ), TRUE );
				$settings [ 'TOOLTIPS_ENABLED' ] = prepare_constant ( TOOLTIPS_ENABLED, TRUE );
				$settings [ 'REDIRECT_AFTER_CONFIRMATION' ] = prepare_constant ( REDIRECT_AFTER_CONFIRMATION, TRUE );
				$settings [ 'ALLOW_USERNAME_CHANGE' ] = prepare_constant ( ALLOW_USERNAME_CHANGE, TRUE );
				$settings [ 'DEFAULT_TEMPLATE' ] = prepare_constant ( $this->input->post ( 'DEFAULT_TEMPLATE' ) );
				$settings [ 'ALLOW_REMEMBER_ME' ] = prepare_constant ( ALLOW_REMEMBER_ME, TRUE );
				$settings [ 'AUTOLOAD_LIBRARIES' ] = prepare_constant ( "database,session,site_sentry,permissions,tasks,site_language" );
				
				$settings [ 'MIN_USR_VOTES_HOMEPAGE' ] = prepare_constant ( $this->input->post ( 'MIN_USR_VOTES_HOMEPAGE' ) );
				$settings [ 'MIN_WALL_VOTES_HOMEPAGE' ] = prepare_constant ( $this->input->post ( 'MIN_WALL_VOTES_HOMEPAGE' ) );
				$settings [ 'WALLPAPER_DISPLAY_ORDER' ] = prepare_constant ( $this->input->post ( 'WALLPAPER_DISPLAY_ORDER' ) );
				$settings [ 'WALLPAPER_ORDER_TYPE' ] = prepare_constant ( $this->input->post ( 'WALLPAPER_ORDER_TYPE' ) );
				
				$settings [ 'MAX_TAGS' ] = prepare_constant ( $this->input->post ( 'MAX_TAGS' ) );
				$settings [ 'TAGS_ORDER_BY' ] = prepare_constant ( $this->input->post ( 'TAGS_ORDER_BY' ) );
				$settings [ 'TAGS_ORDER_BY_METHOD' ] = prepare_constant ( $this->input->post ( 'TAGS_ORDER_BY_METHOD' ) );
				$settings [ 'TAGS_MIN_CHARACTERS' ] = prepare_constant ( $this->input->post ( 'TAGS_MIN_CHARACTERS' ) );
				$settings [ 'APPLICATION_FOLDER' ] = prepare_constant ( add_start_end_slashes ( $this->input->post ( 'APPLICATION_FOLDER' ) ) );

				$settings [ 'CATEGORY_COLUMNS' ] = prepare_constant ( $this->input->post ( 'CATEGORY_COLUMNS' ) );
				$settings [ 'SHOW_CATEGORY_COUNTERS' ] = prepare_constant ( $this->input->post ( 'SHOW_CATEGORY_COUNTERS' ), TRUE );

				$settings [ 'TRACKING_CODE' ] = prepare_constant ( base64_encode ( $this->input->post ( 'TRACKING_CODE' ) ) );
				$settings [ 'AD_CODE' ] = prepare_constant ( base64_encode ( $this->input->post ( 'AD_CODE' ) ) );
				$settings [ 'WALLPAPER_AD_CODE' ] = prepare_constant ( base64_encode ( $this->input->post ( 'WALLPAPER_AD_CODE' ) ) );
				$settings [ 'TOP_DOWNLOAD_AD_CODE' ] = prepare_constant ( base64_encode ( $this->input->post ( 'TOP_DOWNLOAD_AD_CODE' ) ) );
				$settings [ 'WALLPAPER_IPHONE_AD_CODE' ] = prepare_constant ( base64_encode ( $this->input->post ( 'WALLPAPER_IPHONE_AD_CODE' ) ) );
				$settings [ 'WALLPAPER_DOWNLOAD_AD_CODE' ] = prepare_constant ( base64_encode ( $this->input->post ( 'WALLPAPER_DOWNLOAD_AD_CODE' ) ) );

				$settings [ 'ENABLE_MOD_REWRITE' ] = prepare_constant ( $this->input->post ( 'ENABLE_MOD_REWRITE' ), TRUE );
				$settings [ 'WALLPAPER_QUALITY' ] = prepare_constant ( ( $this->input->post ( 'WALLPAPER_QUALITY' ) <= 100 ) ? $this->input->post ( 'WALLPAPER_QUALITY' ) : 100 );
				
				$settings [ 'WALLPAPERS_PER_COLUMN' ] = prepare_constant ( $this->input->post ( 'WALLPAPERS_PER_COLUMN' ) );
				
				$settings [ 'SITE_HAS_ADULT_MATERIALS' ] = prepare_constant ( $this->input->post ( 'SITE_HAS_ADULT_MATERIALS' ), TRUE );
				$settings [ 'GUESTS_CAN_DOWNLOAD' ] = prepare_constant ( $this->input->post ( 'GUESTS_CAN_DOWNLOAD' ), TRUE );
				$settings [ 'GUESTS_CAN_UPLOAD' ] = prepare_constant ( $this->input->post ( 'GUESTS_CAN_UPLOAD' ), TRUE );
				$settings [ 'AUTO_APROVE_COMMENTS' ] = prepare_constant ( $this->input->post ( 'AUTO_APROVE_COMMENTS' ), TRUE );
				$settings [ 'OPEN_WALLPAPERS_IN_NEW_WINDOW' ] = prepare_constant ( $this->input->post ( 'OPEN_WALLPAPERS_IN_NEW_WINDOW' ), TRUE );
				
				$settings [ 'MAX_COLORS' ] = prepare_constant ( $this->input->post ( 'MAX_COLORS' ) );
				
				$this->ws_settings_writer->Set ( 'Settings', $settings );
				if ( $this->ws_settings_writer->Save () ) {
					$saved = TRUE;
				}
				else {
					$msg = 'error|' . Lang ( 'settings_not_saved' );
				}
			}
			else {
				$msg = 'error|' . Lang ( 'validation_n_passed' );
			}
		}
		
		if ( ! $saved ) {
			$content = '';
			if ( isset ( $msg ) ) {
				$content .= evaluate_response ( $msg );
			}
			$content .= get_admin_settings_form ();
			
			$right = get_right_side_content ();
			
			$page = array ( 
				'content' => $content, 'right' => $right, 'header_msg' => Lang ( 'admin_settings' ) 
			);
			
			$page = assign_global_variables ( $page, 'admin_settings' );
			
			load_template ( $page, 'template' );
		}
		else {
			clear_cache ();
			global_reset_categories ();
			header ( "Cache-Control: no-cache, must-revalidate" ); // HTTP/1.1
			header ( "Expires: Sat, 26 Jul 1997 05:00:00 GMT" ); // Date in the past
			header ( "Location: " . site_url ( 'admin' ) );
		}
	}

	function clear_cache () {
		if ( clear_cache () && global_reset_categories () ) {
			$msg = 'ok|' . Lang ( 'clear_cache_ok' );
		}
		else {
			$msg = 'error|' . Lang ( 'clear_cache_error' );
		}
		
		$page = array ( 
			'page_title' => Lang ( 'clear_cache' ), 'styles' => get_page_css ( 'clear_cache' ), 'javascript' => get_page_js ( 'clear_cache' ), 'message' => evaluate_response ( $msg ) 
		);
		
		load_template ( $page, 'generic' );
	}

	function syncronize_stats () {
		$msg = 'info|' . Lang ( 'syncronizing_stats' );
		$js = '<script type="text/javascript">
			$.get("' . site_url ( 'admin/syncronize_stats_start' ) . '", function(data){
				if(data==0){
					parent.refresh();
				}
				else {
					refresh ();
				}
			});
		</script>';
		
		$page = array ( 
			'page_title' => Lang ( 'clear_cache' ), 'styles' => get_page_css ( 'clear_cache' ), 'javascript' => get_page_js ( 'clear_cache' ), 'message' => evaluate_response ( $msg ) . $js 
		);
		
		load_template ( $page, 'generic' );
	}

	function syncronize_stats_start () {
		$this->load->library ( 'WS_Cron' );
		echo ( int ) $this->ws_cron->start ();
		die ();
	}
}

//END