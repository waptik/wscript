<?php
if ( ! defined ( 'BASEPATH' ) )
	exit ( 'No direct script access allowed' );

	// ------------------------------------------------------------------------


/**
 * get_page_title
 *
 * based on the given page/or not, returns the title that is defined
 *
 * @param	string/bol
 * @access	public
 * @return 	string
 */

function get_page_title ( $page = FALSE, $wallpaper = FALSE, $category = FALSE, $member = FALSE ) {
	$CI = & get_instance ();
	if ( $page != FALSE ) {
		switch ( $page ) {
			case 'register' :
				return Lang ( 'register' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'categories' :
				return @$category->title . ' ' . Lang ( 'desktop' ) . ' ' . Lang ( 'wallpapers' ) . ' ' . @$category->ID;
				break;
			
			case 'login' :
				return Lang ( 'login' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'my_account' :
				return Lang ( 'my_account' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'admin' :
				return Lang ( 'admin' );
				break;
			
			case 'update_profile' :
				return Lang ( 'update_profile' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'contact' :
				return Lang ( 'contact_us' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'admin_settings' :
				return Lang ( 'admin_settings' );
				break;
			
			case 'admin_categories' :
				return Lang ( 'edit_categories' );
				break;
			
			case 'member_wallpapers' :
				return @$member->Username . '\'s ' . Lang ( 'wallpapers' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_permissions' :
				return Lang ( 'manage_permissions' );
				break;
			
			case 'edit_permission' :
				return Lang ( 'edit_permission' );
				break;
			
			case 'add_permission' :
				return Lang ( 'manage_permissions' );
				break;
			
			case 'manage_groups' :
				return Lang ( 'manage_groups' );
				break;
			
			case 'manage_partners' :
				return Lang ( 'manage_partners' );
				break;
			
			case 'edit_group' :
				return Lang ( 'edit_group' );
				break;
			
			case 'manage_g_permissions' :
				return Lang ( 'manage_g_permissions' );
				break;
			
			case 'manage_users' :
				return Lang ( 'manage_users' );
				break;
			
			case 'user_search' :
				return Lang ( 'search_user' );
				break;
			
			case 'edit_user' :
				return Lang ( 'edit_user' );
				break;
			
			case 'manage_u_permissions' :
				return Lang ( 'manage_u_permissions' );
				break;
			
			case 'wallpaper' :
				return @$wallpaper->file_title . ' ' . Lang ( 'desktop' ) . ' ' . Lang ( 'wallpapers' ) . ' ' . @$wallpaper->ID;
				break;
			
			case 'manage_wallpapers' :
				return Lang ( 'manage_wallpapers' );
				break;
			
			case 'welcome' :
				return SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_tags' :
				return Lang ( 'manage_tags' );
				break;
			
			case 'tags' :
				return ucfirst ( urldecode ( $CI->uri->segment ( 3 ) ) ) . ' ' . Lang ( 'wallpapers' );
				break;
			
			default :
				return SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
		}
	}
	else {
		return SITE_NAME . ' | ' . SITE_SLOGAN;
	}
}

function get_page_metaD ( $page, $wallpaper = FALSE, $category = FALSE, $member = FALSE ) {
	$CI = & get_instance ();
	if ( $page != FALSE ) {
		switch ( $page ) {
			case 'register' :
				return Lang ( 'register' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'categories' :
				return @$category->meta_description;
				break;
			
			case 'login' :
				return Lang ( 'login' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'my_account' :
				return Lang ( 'my_account' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'admin' :
				return Lang ( 'admin' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'update_profile' :
				return Lang ( 'update_profile' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'contact' :
				return Lang ( 'contact_us' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'admin_settings' :
				return Lang ( 'admin_settings' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'admin_categories' :
				return Lang ( 'edit_categories' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'member_wallpapers' :
				return Lang ( 'member_wallpapers' ) . @$member->Username . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_permissions' :
				return Lang ( 'manage_permissions' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'edit_permission' :
				return Lang ( 'edit_permission' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'add_permission' :
				return Lang ( 'manage_permissions' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_groups' :
				return Lang ( 'manage_groups' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'edit_group' :
				return Lang ( 'edit_group' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_g_permissions' :
				return Lang ( 'manage_g_permissions' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_users' :
				return Lang ( 'manage_users' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'user_search' :
				return Lang ( 'search_user' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'edit_user' :
				return Lang ( 'edit_user' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_u_permissions' :
				return Lang ( 'manage_u_permissions' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'wallpaper' :
				return @$wallpaper->file_title . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_wallpapers' :
				return Lang ( 'manage_wallpapers' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'welcome' :
				return SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_tags' :
				return Lang ( 'manage_tags' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'tags' :
				return ucfirst ( urldecode ( $CI->uri->segment ( 3 ) ) ) . ' ' . Lang ( 'wallpapers' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			default :
				return SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
		}
	}
	else {
		return SITE_NAME . ' | ' . SITE_SLOGAN;
	}
}

function get_page_metaK ( $page, $wallpaper, $category = FALSE, $member = FALSE ) {
	$CI = & get_instance ();
	if ( $page != FALSE ) {
		switch ( $page ) {
			case 'register' :
				$out = Lang ( 'register' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'categories' :
				return @$category->meta_keywords;
				break;
			
			case 'login' :
				$out = Lang ( 'login' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'my_account' :
				$out = Lang ( 'my_account' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'admin' :
				$out = Lang ( 'admin' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'update_profile' :
				$out = Lang ( 'update_profile' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'contact' :
				$out = Lang ( 'contact_us' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'admin_settings' :
				$out = Lang ( 'admin_settings' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'admin_categories' :
				$out = Lang ( 'edit_categories' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'member_wallpapers' :
				$out = Lang ( 'member_wallpapers' ) . @$member->Username . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_permissions' :
				$out = Lang ( 'manage_permissions' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'edit_permission' :
				$out = Lang ( 'edit_permission' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'add_permission' :
				$out = Lang ( 'manage_permissions' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_groups' :
				$out = Lang ( 'manage_groups' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'edit_group' :
				$out = Lang ( 'edit_group' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_g_permissions' :
				$out = Lang ( 'manage_g_permissions' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_users' :
				$out = Lang ( 'manage_users' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'user_search' :
				$out = Lang ( 'search_user' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'edit_user' :
				$out = Lang ( 'edit_user' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_u_permissions' :
				$out = Lang ( 'manage_u_permissions' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'wallpaper' :
				$out = @$wallpaper->file_title . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_wallpapers' :
				$out = Lang ( 'manage_wallpapers' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'welcome' :
				$out = SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'manage_tags' :
				$out = SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			case 'tags' :
				$out = ucfirst ( urldecode ( $CI->uri->segment ( 3 ) ) ) . ' ' . Lang ( 'wallpapers' ) . ' | ' . SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
				break;
			
			default :
				$out = SITE_NAME . ' | ' . SITE_SLOGAN . '  - ' . DOMAIN_NAME;
		}
	}
	else {
		$out = SITE_NAME . ' | ' . SITE_SLOGAN;
	}
	
	$search = array ( 

		'/,+/' 
	);
	$replace = array ( 

		', ' 
	);
	
	$out = preg_replace ( $search, $replace, $out );
	return $out;
}

// ------------------------------------------------------------------------


/**
 * get_page_js
 *
 * based on the given page/or not, returns the javascript link
 *
 * @param	string/bol
 * @access	public
 * @return 	string
 */

function get_page_js ( $page = FALSE ) {
	$CI = &get_instance ();
	
	$tags = array ( 
		
		'page' => $page, 
		'segment_3' => $CI->uri->segment ( 3 ), 
		'segment_4' => $CI->uri->segment ( 4 ), 
		'adult_confirmed' => is_adult_confirmed (), 
		'sess' => isset ( $_SESSION ) ? $_SESSION : array () 
	);
	
	return load_html_template ( $tags, 'page_js' );
}

// ------------------------------------------------------------------------


/**
 * is_obj
 *
 * tries to determine if the given input is a valid object or not.
 *
 * @param	$object
 * @access	private
 * @return 	bol
 */

function is_obj ( &$object, $check = null, $strict = true ) {
	if ( is_object ( $object ) ) {
		if ( $check == null ) {
			return true;
		}
		else {
			$object_name = get_class ( $object );
			return ( $strict === true ) ? ( $object_name == $check ) : ( strtolower ( $object_name ) == strtolower ( $check ) );
		}
	}
	else {
		return false;
	}
}

// ------------------------------------------------------------------------


/**
 * get_page_css
 *
 * based on the given page/or not, returns the stylesheet link
 *
 * @param	string/bol
 * @access	public
 * @return 	string
 */

function get_page_css () {
	return load_html_template ( array (), 'page_css' );
}

// ------------------------------------------------------------------------


/**
 * setFooter
 *
 * prints out the footer
 *
 * @param	none
 * @access	public
 * @return 	string
 */

function setFooter () {
	$CI = &get_instance ();
	
	$tags = array ( 
		
		'logged_in' => $CI->site_sentry->is_logged_in (), 
		'num_users_online' => get_online_users_count () 
	);
	
	$CI->output->enable_profiler ( RUN_ON_DEVELOPMENT );
	
	return load_html_template ( $tags, 'footer' );
}

// ------------------------------------------------------------------------


/**
 * setMenu
 *
 * prints out the main menu
 *
 * @param	string/bol
 * @access	public
 * @return 	string
 */

function &get_menu_object () {
	$CI = &get_instance ();
	
	$menu = array ( 
		
		//	MAIN NAV
		1 => array ( 
			
			'text' => Lang ( 'manage' ), 
			'class' => '', 
			'link' => site_url ( 'admin' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ?
				(
					$CI->permissions->checkPermissions ( array ( 43 ) ) ||
					$CI->permissions->checkPermissions ( array ( 44 ) ) ||
					$CI->permissions->checkPermissions ( array ( 45 ) ) ||
					$CI->permissions->checkPermissions ( array ( 35 ) ) ||
					$CI->permissions->checkPermissions ( array ( 30 ) ) ||
					$CI->permissions->checkPermissions ( array ( 23 ) ) ||
					$CI->permissions->checkPermissions ( array ( 16 ) ) ||
					$CI->permissions->checkPermissions ( array ( 9 ) ) ||
					$CI->permissions->checkPermissions ( array ( 2 )
				) ) : FALSE, 
			'parent' => 0, 
			'order' => 1 
		), 
		2 => array ( 
			
			'text' => Lang ( 'nav_home' ), 
			'class' => '', 
			'link' => base_url (), 
			'show_condition' => TRUE, 
			'parent' => 0, 
			'order' => 1 
		), 
		3 => array ( 
			
			'text' => Lang ( 'nav_logout' ), 
			'class' => 'right', 
			'link' => site_url ( 'login/logout' ), 
			'show_condition' => $CI->site_sentry->is_logged_in (), 
			'parent' => 0, 
			'order' => 101 
		), 
		4 => array ( 
			
			'text' => Lang ( 'nav_login' ), 
			'class' => 'right', 
			'link' => site_url ( 'login' ), 
			'show_condition' => ! $CI->site_sentry->is_logged_in (), 
			'parent' => 0, 
			'order' => 101 
		), 
		5 => array ( 
			
			'text' => Lang ( 'nav_register' ), 
			'class' => 'right', 
			'link' => site_url ( 'register' ), 
			'show_condition' => ! $CI->site_sentry->is_logged_in (), 
			'parent' => 0, 
			'order' => 100 
		), 
		6 => array ( 
			
			'text' => Lang ( 'nav_contact' ), 
			'class' => 'right', 
			'link' => site_url ( 'contact' ), 
			'show_condition' => TRUE, 
			'parent' => 0, 
			'order' => 99 
		),  //	ADMIN MEMBERS
		7 => array ( 
			
			'text' => Lang ( 'users_options_menu' ), 
			'class' => 'users_options_menu', 
			'link' => site_url ( 'users/manage_users' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				9 
			) ) : FALSE, 
			'parent' => 1, 
			'order' => 1 
		), 
		8 => array ( 
			
			'text' => Lang ( 'q_add_user' ), 
			'class' => 'q_add_user', 
			'link' => 'javascript:dialog(800,600,\'' . Lang ( 'q_add_user' ) . '\',true,true,\'' . site_url ( 'users/add_user' ) . '\')', 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				10 
			) ) : FALSE, 
			'parent' => 7, 
			'order' => 1 
		), 
		9 => array ( 
			
			'text' => Lang ( 'mass_email_users' ), 
			'class' => 'mass_email_users', 
			'link' => 'javascript:dialog(900,700,\'' . Lang ( 'mass_email_users' ) . '\',true,true,\'' . site_url ( 'users/admin_contact_users' ) . '\')', 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				37 
			) ) : FALSE, 
			'parent' => 7, 
			'order' => 1 
		), 
		10 => array ( 
			
			'text' => Lang ( 'gen_code' ), 
			'class' => 'gen_code', 
			'link' => 'javascript:dialog(500,680,\'' . Lang ( 'gen_code' ) . '\',true,true,\'' . site_url ( 'admin/admin_get_code' ) . '\')', 
			'show_condition' => FALSE, 
			'parent' => 7, 
			'order' => 1 
		),  //	ADMIN SITE SETTINGS
		11 => array ( 
			
			'text' => Lang ( 'site_settings_menu' ), 
			'class' => 'site_settings_menu', 
			'link' => site_url ( 'admin/admin_settings' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				35 
			) ) : FALSE, 
			'parent' => 1, 
			'order' => 1 
		),  //	CATEGORIES
		12 => array ( 
			
			'text' => Lang ( 'edit_categories' ), 
			'class' => 'edit_categories', 
			'link' => site_url ( 'categories' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				23 
			) ) : FALSE, 
			'parent' => 1, 
			'order' => 1 
		),  //	ADMIN USER GROUPS
		13 => array ( 
			
			'text' => Lang ( 'user_groups_menu' ), 
			'class' => 'user_groups_menu', 
			'link' => site_url ( 'user_groups/manage_groups' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				16 
			) ) : FALSE, 
			'parent' => 1, 
			'order' => 1 
		),  //	ADMIN PERMISSIONS
		14 => array ( 
			
			'text' => Lang ( 'permissions_menu' ), 
			'class' => 'permissions_menu', 
			'link' => site_url ( 'site_permissions/manage_permissions' ), 
			'show_condition' => FALSE,  //( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 30 ) ) : FALSE,
			'parent' => 1, 
			'order' => 1 
		),  //	MY ACCOUNT
		15 => array ( 
			
			'text' => Lang ( 'my_acc_edit' ), 
			'class' => 'my_acc_edit', 
			'link' => site_url ( 'members/edit_profile' ), 
			'show_condition' => $CI->site_sentry->is_logged_in (), 
			'parent' => 18, 
			'order' => 1 
		), 
		16 => array ( 
			
			'text' => Lang ( 'search' ), 
			'class' => '', 
			'link' => site_url ( 'search' ), 
			'show_condition' => FALSE, 
			'parent' => 0, 
			'order' => 1 
		), 
		17 => array ( 
			
			'text' => Lang ( 'wallpapers' ), 
			'class' => 'wallpapers', 
			'link' => site_url ( 'wallpapers/manage' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				2 
			) ) : FALSE, 
			'parent' => 1, 
			'order' => 1 
		), 
		18 => array ( 
			
			'text' => Lang ( 'my_account' ), 
			'class' => 'right', 
			'link' => site_url ( 'members' ), 
			'show_condition' => $CI->site_sentry->is_logged_in (), 
			'parent' => 0, 
			'order' => 111 
		), 
		19 => array ( 
			
			'text' => Lang ( 'tag_cloud' ), 
			'class' => 'tag_cloud', 
			'link' => site_url ( 'tags/manage' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				40 
			) ) : FALSE, 
			'parent' => 1, 
			'order' => 1 
		), 
		20 => array ( 
			
			'text' => Lang ( 'bulk_upload' ), 
			'class' => 'bulk_upload', 
			'link' => 'javascript:dialog(800,550,\'' . Lang ( 'bulk_upload' ) . '\',true,true,\'' . site_url ( 'wallpapers/bulk' ) . '\')', 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				4 
			) ) : FALSE, 
			'parent' => 17, 
			'order' => 1 
		), 
		22 => array ( 
			
			'text' => Lang ( 'partners' ), 
			'class' => 'partners', 
			'link' => site_url ( 'partners/manage' ), 
			'show_condition' => TRUE, 
			'parent' => 1, 
			'order' => 1 
		), 
		26 => array ( 
			
			'text' => Lang ( 'submit_sitemap' ), 
			'class' => 'submit_sitemap', 
			'link' => 'javascript:dialog(500,230,\'' . Lang ( 'submit_sitemap' ) . '\',true,true,\'' . site_url ( 'sitemap/submit' ) . '\')', 
			'show_condition' => TRUE, 
			'parent' => 11, 
			'order' => 11 
		), 
		27 => array ( 
			
			'text' => Lang ( 'clear_cache' ), 
			'class' => 'clear_cache', 
			'link' => 'javascript:dialog(500,230,\'' . Lang ( 'clear_cache' ) . '\',true,true,\'' . site_url ( 'admin/clear_cache' ) . '\')', 
			'show_condition' => TRUE, 
			'parent' => 11, 
			'order' => 11 
		), 
		29 => array ( 
			
			'text' => Lang ( 'browse_by_size' ), 
			'class' => '', 
			'link' => 'javascript:void(0);', 
			'show_condition' => TRUE, 
			'parent' => 0, 
			'order' => 2 
		), 
		30 => array ( 
			
			'text' => Lang ( 'advanced_search' ), 
			'class' => '', 
			'link' => site_url ( 'search' ), 
			'show_condition' => TRUE, 
			'parent' => 0, 
			'order' => 13 
		), 
		31 => array ( 
			
			'text' => Lang ( 'visitor_searches' ), 
			'class' => 'visitor_searches', 
			'link' => site_url ( 'search/visitor_searches' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				2 
			) ) : FALSE, 
			'parent' => 17, 
			'order' => 3 
		), 
		32 => array ( 
			
			'text' => Lang ( 'watermark_settings' ), 
			'class' => 'wallpapers', 
			'link' => site_url ( 'admin/watermark_settings' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				35 
			) ) : FALSE, 
			'parent' => 17, 
			'order' => 4 
		), 
		33 => array ( 
			
			'text' => Lang ( 'bulk_edit_wallpapers' ), 
			'class' => 'bulk_edit', 
			'link' => site_url ( 'wallpapers/bulk_edit' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				2 
			) ) : FALSE, 
			'parent' => 17, 
			'order' => 4 
		), 
		34 => array ( 
			
			'text' => Lang ( 'tag_cloud_update' ), 
			'class' => 'tag_cloud_update', 
			'link' => 'javascript:dialog(500,230,\'' . Lang ( 'tag_cloud_update' ) . '\',true,true,\'' . site_url ( 'tags/update' ) . '\')', 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				40 
			) ) : FALSE, 
			'parent' => 11, 
			'order' => 14 
		), 
		35 => array ( 
			
			'text' => strtoupper ( Lang ( 'normal' ) ), 
			'class' => 'wallpapers', 
			'link' => site_url ( 'wallpapers/browse_by_size/normal' ), 
			'show_condition' => TRUE, 
			'parent' => 29, 
			'order' => 1 
		), 
		36 => array ( 
			
			'text' => strtoupper ( Lang ( 'wide' ) ), 
			'class' => 'wallpapers', 
			'link' => site_url ( 'wallpapers/browse_by_size/wide' ), 
			'show_condition' => TRUE, 
			'parent' => 29, 
			'order' => 1 
		), 
		37 => array ( 
			
			'text' => strtoupper ( Lang ( 'psp' ) ), 
			'class' => 'wallpapers', 
			'link' => site_url ( 'wallpapers/browse_by_size/psp' ), 
			'show_condition' => TRUE, 
			'parent' => 29, 
			'order' => 1 
		), 
		38 => array ( 
			
			'text' => strtoupper ( Lang ( 'iphone' ) ), 
			'class' => 'wallpapers', 
			'link' => site_url ( 'wallpapers/browse_by_size/iphone' ), 
			'show_condition' => TRUE, 
			'parent' => 29, 
			'order' => 1 
		), 
		39 => array ( 
			
			'text' => Lang ( 'comments' ), 
			'class' => 'manage_comments', 
			'link' => site_url ( 'comments/manage' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				46 
			) ) : FALSE, 
			'parent' => 1, 
			'order' => 4 
		), 
		40 => array ( 
			
			'text' => Lang ( 'latest' ), 
			'class' => 'latest_wallpapers', 
			'link' => site_url ( 'welcome/latest' ), 
			'show_condition' => TRUE, 
			'parent' => 0, 
			'order' => 1 
		), 
		41 => array ( 
			
			'text' => Lang ( 'top' ), 
			'class' => 'top_wallpapers', 
			'link' => site_url ( 'welcome/top' ), 
			'show_condition' => TRUE, 
			'parent' => 0, 
			'order' => 1 
		), 
		42 => array ( 
			
			'text' => Lang ( 'random' ), 
			'class' => 'random_wallpapers', 
			'link' => site_url ( 'welcome/random' ), 
			'show_condition' => TRUE, 
			'parent' => 0, 
			'order' => 1 
		), 
		43 => array ( 
			
			'text' => Lang ( 'syncronize_stats' ), 
			'class' => 'syncronize_stats', 
			'link' => 'javascript:dialog(500,230,\'' . Lang ( 'syncronize_stats' ) . '\',true,true,\'' . site_url ( 'admin/syncronize_stats' ) . '\')', 
			'show_condition' => TRUE, 
			'parent' => 11, 
			'order' => 13 
		), 
		44 => array ( 
			
			'text' => 'Widgets', 
			'class' => 'widgets', 
			'link' => site_url ( 'admin/widgets' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				35 
			) ) : FALSE, 
			'parent' => 11, 
			'order' => 11 
		), 
		45 => array ( 
			
			'text' => 'Modules', 
			'class' => 'modules', 
			'link' => site_url ( 'admin/modules' ), 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				35 
			) ) : FALSE, 
			'parent' => 11, 
			'order' => 11 
		), 
		46 => array ( 
			
			'text' => strtoupper ( Lang ( 'hd' ) ), 
			'class' => 'wallpapers', 
			'link' => site_url ( 'wallpapers/browse_by_size/hd' ), 
			'show_condition' => TRUE, 
			'parent' => 29, 
			'order' => 10 
		), 
		47 => array ( 
			
			'text' => strtoupper ( Lang ( 'multi' ) ), 
			'class' => 'wallpapers', 
			'link' => site_url ( 'wallpapers/browse_by_size/multi' ), 
			'show_condition' => TRUE, 
			'parent' => 29, 
			'order' => 10 
		), 
		48 => array ( 
			
			'text' => Lang ( 'colors_update' ), 
			'class' => 'colors_update', 
			'link' => 'javascript:dialog(500,230,\'' . Lang ( 'colors_update' ) . '\',true,true,\'' . site_url ( 'colors/update' ) . '\')', 
			'show_condition' => ( $CI->site_sentry->is_logged_in () ) ? $CI->permissions->checkPermissions ( array ( 
				
				40 
			) ) : FALSE, 
			'parent' => 11, 
			'order' => 14 
		) 
	);
	
	$wide = array ();
	$normal = array ();
	$psp = array ();
	$iphone = array ();
	$hd = array ();
	$multi = array ();
	
	$CI->load->library ( 'WB_array' );
	
	foreach ( get_sizes ( 'wide' ) as $height => $width ) {
		$wide [] = array ( 
			
			'height' => $height, 
			'width' => $width, 
			'sum' => ( $height + $width ) 
		);
		$wide = WB_array::sort_columns ( $wide, 'sum', TRUE );
	}
	
	foreach ( get_sizes ( 'normal' ) as $height => $width ) {
		$normal [] = array ( 
			
			'height' => $height, 
			'width' => $width, 
			'sum' => ( $height + $width ) 
		);
		$normal = WB_array::sort_columns ( $normal, 'sum', TRUE );
	}
	
	foreach ( get_sizes ( 'psp' ) as $height => $width ) {
		$psp [] = array ( 
			
			'height' => $height, 
			'width' => $width, 
			'sum' => ( $height + $width ) 
		);
		$psp = WB_array::sort_columns ( $psp, 'sum', TRUE );
	}
	
	foreach ( get_sizes ( 'iphone' ) as $height => $width ) {
		$iphone [] = array ( 
			
			'height' => $height, 
			'width' => $width, 
			'sum' => ( $height + $width ) 
		);
		$iphone = WB_array::sort_columns ( $iphone, 'sum', TRUE );
	}
	
	foreach ( get_sizes ( 'hd' ) as $height => $width ) {
		$hd [] = array ( 
			
			'height' => $height, 
			'width' => $width, 
			'sum' => ( $height + $width ) 
		);
		$hd = WB_array::sort_columns ( $hd, 'sum', TRUE );
	}
	
	foreach ( get_sizes ( 'multi' ) as $height => $width ) {
		$multi [] = array ( 
			
			'height' => $height, 
			'width' => $width, 
			'sum' => ( $height + $width ) 
		);
		$multi = WB_array::sort_columns ( $multi, 'sum', TRUE );
	}
	
	$array = create_wallpapers_array_by_measures ();
	$total = 0;
	
	foreach ( $normal as $value ) {
		$height = $value [ 'height' ];
		$width = $value [ 'width' ];
		$sum = $value [ 'sum' ];
		
		if ( isset ( $array [ $sum ] ) ) {
			$total += $array [ $sum ];
			unset ( $array [ $sum ] );
		}
		
		if ( $total ) {
			$menu_append = array ( 
				
				'text' => $width . ' X ' . $height . ' ( <b>' . $total . '</b> )', 
				'class' => '', 
				'link' => site_url ( "wallpapers/browse_by_size/$width/$height" ), 
				'show_condition' => TRUE, 
				'parent' => 35, 
				'order' => - $sum 
			);
			array_push ( $menu, $menu_append );
		}
	
	}
	unset ( $menu_append );
	
	$total = 0;
	
	foreach ( $wide as $value ) {
		$height = $value [ 'height' ];
		$width = $value [ 'width' ];
		$sum = $value [ 'sum' ];
		
		if ( isset ( $array [ $sum ] ) ) {
			$total += $array [ $sum ];
			unset ( $array [ $sum ] );
		}
		
		if ( $total ) {
			$menu_append = array ( 
				
				'text' => $width . ' X ' . $height . ' ( <b>' . $total . '</b> )', 
				'class' => '', 
				'link' => site_url ( "wallpapers/browse_by_size/$width/$height" ), 
				'show_condition' => TRUE, 
				'parent' => 36, 
				'order' => - $sum 
			);
			array_push ( $menu, $menu_append );
		}
	}
	unset ( $menu_append );
	
	$total = 0;
	
	foreach ( $psp as $value ) {
		$height = $value [ 'height' ];
		$width = $value [ 'width' ];
		$sum = $value [ 'sum' ];
		
		if ( isset ( $array [ $sum ] ) ) {
			$total += $array [ $sum ];
			unset ( $array [ $sum ] );
		}
		
		if ( $total ) {
			$menu_append = array ( 
				
				'text' => $width . ' X ' . $height . ' ( <b>' . $total . '</b> )', 
				'class' => '', 
				'link' => site_url ( "wallpapers/browse_by_size/$width/$height" ), 
				'show_condition' => TRUE, 
				'parent' => 37, 
				'order' => - $sum 
			);
			array_push ( $menu, $menu_append );
		}
	}
	unset ( $menu_append );
	
	$total = 0;
	
	foreach ( $iphone as $value ) {
		$height = $value [ 'height' ];
		$width = $value [ 'width' ];
		$sum = $value [ 'sum' ];
		
		if ( isset ( $array [ $sum ] ) ) {
			$total += $array [ $sum ];
			unset ( $array [ $sum ] );
		}
		
		if ( $total ) {
			$menu_append = array ( 
				
				'text' => $width . ' X ' . $height . ' ( <b>' . $total . '</b> )', 
				'class' => '', 
				'link' => site_url ( "wallpapers/browse_by_size/$width/$height" ), 
				'show_condition' => TRUE, 
				'parent' => 38, 
				'order' => - $sum 
			);
			array_push ( $menu, $menu_append );
		}
	}
	unset ( $menu_append );
	
	$total = 0;
	
	foreach ( $hd as $value ) {
		$height = $value [ 'height' ];
		$width = $value [ 'width' ];
		$sum = $value [ 'sum' ];
		
		if ( isset ( $array [ $sum ] ) ) {
			$total += $array [ $sum ];
			unset ( $array [ $sum ] );
		}
		
		if ( $total ) {
			$menu_append = array ( 
				
				'text' => $width . ' X ' . $height . ' ( <b>' . $total . '</b> )', 
				'class' => '', 
				'link' => site_url ( "wallpapers/browse_by_size/$width/$height" ), 
				'show_condition' => TRUE, 
				'parent' => 46, 
				'order' => - $sum 
			);
			array_push ( $menu, $menu_append );
		}
	}
	unset ( $menu_append );
	
	$total = 0;
	
	foreach ( $multi as $value ) {
		$height = $value [ 'height' ];
		$width = $value [ 'width' ];
		$sum = $value [ 'sum' ];
		
		if ( isset ( $array [ $sum ] ) ) {
			$total += $array [ $sum ];
			unset ( $array [ $sum ] );
		}
		
		if ( $total ) {
			$menu_append = array ( 
				
				'text' => $width . ' X ' . $height . ' ( <b>' . $total . '</b> )', 
				'class' => '', 
				'link' => site_url ( "wallpapers/browse_by_size/$width/$height" ), 
				'show_condition' => TRUE, 
				'parent' => 47, 
				'order' => - $sum 
			);
			array_push ( $menu, $menu_append );
		}
	}
	unset ( $menu_append );
	
	$remaining = ( ! empty ( $array ) ) ? array_sum ( $array ) : 0;
	
	if ( $remaining ) {
		$other = array ( 
			
			'text' => strtoupper ( Lang ( 'other' ) ) . ' ( <b>' . array_sum ( $array ) . '</b> )', 
			'class' => 'wallpapers', 
			'link' => site_url ( 'wallpapers/browse_by_size/other' ), 
			'show_condition' => TRUE, 
			'parent' => 29, 
			'order' => 10 
		);
		
		array_push ( $menu, $other );
		unset ( $other );
	}
	
	$CI->load->library ( 'menus' );
	
	if ( empty ( $GLOBALS [ 'menu_object' ] ) ) {
		$GLOBALS [ 'menu_object' ] = new Menus ( );
		
		foreach ( $menu as $id => $attributes ) {
			$attributes [ 'id' ] = $id;
			$GLOBALS [ 'menu_object' ]->addItem ( $attributes, $id );
		}
	}
	
	return $GLOBALS [ 'menu_object' ];
}

function setMenu () {
	$CI = &get_instance ();
	
	$session = $CI->session->userdata ( 'user_menu' );
	
	if ( RUN_ON_DEVELOPMENT || ! $session ) {
		$menu = &get_menu_object ();
		$session = $menu->getTree ();
		$CI->session->set_userdata ( array ( 
			
			'user_menu' => $session 
		) );
	}
	
	$tags = array ( 
		
		'menu' => $session, 
		'no_class' => false 
	);
	
	return load_html_template ( $tags, 'menu', TRUE, 0 );
}

function get_watermark_settings_form () {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	$tags = array ( 
		
		'CI' => $CI 
	);
	
	return load_form_template ( $tags, 'watermark_settings' );
}

function get_visitor_searches_table ( $query ) {
	$CI = &get_instance ();
	$CI->load->model ( 'msearch' );
	
	$tags = array ( 
		
		'searches' => $query->result (), 
		'CI' => $CI 
	);
	
	return load_html_template ( $tags, 'visitor_searches' );
}

function get_login_form () {
	$CI = & get_instance ();
	$CI->load->helper ( 'form' );
	
	$tags = array ( 
		
		'form_open' => form_open ( 'login/do_login', array ( 
			
			'class' => 'appnitro' 
		), array ( 
			
			'_submit_check' => 1 
		) ), 
		'value_redirect' => $CI->uri->uri_string (), 
		'fpass_btn' => __button ( Lang ( 'forgot_pass' ), 'gray', 'indicator', 'FFFFFF', '', 10, 'BUTTON', array ( 
			
			'onClick' => 'document.location=\'' . site_url ( 'login/forgot_password' ) . '\';' 
		) ) 
	);
	
	return load_form_template ( $tags, 'login_form' );
}

function get_forgot_password_form () {
	$CI = & get_instance ();
	$CI->load->helper ( 'form' );
	
	$tags = array ( 
		
		'form_open' => form_open ( 'login/forgot_password', array ( 
			
			'class' => 'appnitro' 
		), array ( 
			
			'_submit_check' => 1 
		) ) 
	);
	
	return load_form_template ( $tags, 'forgot_password' );
}

function get_register_form () {
	$CI = & get_instance ();
	$CI->load->helper ( 'form' );
	
	$tags = array ( 
		
		'form_open' => form_open ( 'register/insert_register', array ( 
			
			'class' => 'appnitro' 
		), array ( 
			
			'_submit_check' => 1 
		) ) 
	);
	
	return load_form_template ( $tags, 'register' );
}

function get_admin_breadcrumb () {
	$CI = & get_instance ();
	return '<div class="headers gray"><span>' . admin_breadcrumb ( $CI->uri->segment ( 3 ), TRUE ) . '</span></div>' . "\n";
}

function get_search_form ( $query_id = FALSE ) {
	$CI = & get_instance ();
	$CI->load->helper ( 'form' );
	
	$wide = array ();
	$normal = array ();
	$iphone = array ();
	$psp = array ();
	$hd = array ();
	$multi = array ();
	
	foreach ( get_sizes ( 'wide' ) as $height => $width ) {
		$wide [] = array ( 
			
			'height' => $height, 
			'width' => $width 
		);
	}
	
	foreach ( get_sizes ( 'iphone' ) as $height => $width ) {
		$iphone [] = array ( 
			
			'height' => $height, 
			'width' => $width 
		);
	}
	
	foreach ( get_sizes ( 'psp' ) as $height => $width ) {
		$psp [] = array ( 
			
			'height' => $height, 
			'width' => $width 
		);
	}
	
	foreach ( get_sizes ( 'normal' ) as $height => $width ) {
		$normal [] = array ( 
			
			'height' => $height, 
			'width' => $width 
		);
	}
	
	foreach ( get_sizes ( 'hd' ) as $height => $width ) {
		$hd [] = array ( 
			
			'height' => $height, 
			'width' => $width 
		);
	}
	
	foreach ( get_sizes ( 'multi' ) as $height => $width ) {
		$multi [] = array ( 
			
			'height' => $height, 
			'width' => $width 
		);
	}
	
	$tags = array ( 
		
		'form_open' => form_open ( 'search/results', array ( 
			
			'class' => 'appnitro' 
		), array ( 
			
			'_submit_check' => 1 
		) ), 
		'wide' => $wide, 
		'normal' => $normal, 
		'iphone' => $iphone, 
		'psp' => $psp, 
		'hd' => $hd, 
		'multi' => $multi, 
		'query_id' => $query_id 
	);
	
	return load_form_template ( $tags, 'search_form', TRUE, 0 );
}

function get_contact_us_form () {
	$CI = & get_instance ();
	$CI->load->helper ( 'form' );
	
	$tags = array ( 
		
		'form_open' => form_open ( 'contact/send', array ( 
			
			'class' => 'appnitro' 
		), array ( 
			
			'_submit_check' => 1 
		) ) 
	);
	
	return load_form_template ( $tags, 'contact_us' );
}

function get_admin_settings_form () {
	$CI = &get_instance ();
	$CI->load->library ( 'wb_file_manager' );
	$CI->load->helper ( 'file' );
	$backgrounds = $CI->wb_file_manager->list_files ( ROOTPATH . '/templates/' . DEFAULT_TEMPLATE . '/images/patterns', false, false, false, true, false );
	$backgrounds [ 'files' ];
	$language_files = get_filenames ( ROOTPATH . '/language' );
	$languages = array ();
	
	foreach ( $language_files as $lang_file ) {
		$languages [] = str_replace ( '.php', '', $lang_file );
	}
	
	$tags = array ( 
		
		'form_open' => form_open ( 'admin/save_admin_settings', array ( 
			
			'class' => 'appnitro', 
			'autocomplete' => 'off' 
		), array ( 
			
			'_submit_check' => 1, 
			'DBPREFIX' => DBPREFIX 
		) ), 
		'CI' => $CI, 
		'languages' => $languages, 
		'templates' => get_available_templates (), 
		'backgrounds' => $backgrounds [ 'files' ], 
		'patterns_guide' => evaluate_response ( 'info|' . Lang ( 'patterns_guide' ) ) 
	);
	
	return load_form_template ( $tags, 'admin_settings' );
}

//END