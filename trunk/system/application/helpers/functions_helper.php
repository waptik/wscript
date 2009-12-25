<?php
if ( ! defined ( 'BASEPATH' ) )
	exit ( 'No direct script access allowed' );

define ( 'TEMPLATES_DIR', ROOTPATH . '/templates/' );

include_once APPPATH . "libraries/UTF8.php";

if ( ! function_exists ( "mb_strtolower" ) ) {

	function mb_strtolower ( $str ) {
		return UTF8::strtolower ( $str );
	}
}
if ( ! function_exists ( "mb_strlen" ) ) {

	function mb_strlen ( $str ) {
		return UTF8::strlen ( $str );
	}
}
if ( ! function_exists ( "mb_strrpos" ) ) {

	function mb_strrpos ( $haystack, $needle ) {
		return UTF8::strrpos ( $haystack, $needle );
	}
}
if ( ! function_exists ( "mb_strpos" ) ) {

	function mb_strpos ( $haystack, $needle, $offset = 0 ) {
		return UTF8::strpos ( $haystack, $needle, $offset );
	}
}
if ( ! function_exists ( "mb_substr" ) ) {

	function mb_substr ( $str, $start, $length = NULL ) {
		return UTF8::substr ( $str, $start, $length );
	}
}

function get_child_permissions_array ( $parent ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mpermissions' );
	return $CI->mpermissions->get_child_permissions_array ( $parent );
}

function get_setting ( $setting ) {
	require_once APPPATH . "libraries/WS_Settings.php";
	$settings = WS_Settings::getInstance ();
	return $settings->getSetting ( $setting );
}

function set_setting ( $label, $value ) {
	require_once APPPATH . "libraries/WS_Settings.php";
	$settings = WS_Settings::getInstance ();
	return $settings->setSetting ( $label, $value );
}

function write_header ( $message, $tag = 'h1' ) {
	$CI = &get_instance ();
	$s1 = $CI->uri->segment ( 1 );
	$s2 = $CI->uri->segment ( 2 );
	$s3 = $CI->uri->segment ( 3 );
	
	$display_type = isset ( $_COOKIE [ 'wallpaper_display_type' ] ) ? $_COOKIE [ 'wallpaper_display_type' ] : 'box';
	
	if ( $display_type == 'box' ) {
		$box_class = 'box-off';
		$box_url = 'javascript:void(0);';
		$list_class = 'list';
		$list_url = 'javascript:switch_display_type(\'list\');';
	}
	elseif ( $display_type == 'list' ) {
		$box_class = 'box';
		$list_class = 'list-off';
		$box_url = 'javascript:switch_display_type(\'box\');';
		$list_url = 'javascript:void(0);';
	}
	
	$box = '<a href="' . $box_url . '" class="' . $box_class . '" title="Box">Box</a>';
	$list = '<a href="' . $list_url . '" class="' . $list_class . '" title="List">List</a>';
	
	$rss = '<a href="%s" class="rssb" title="RSS">RSS</a>';
	
	$out = '<div class="headerBg">';
	$out .= '<' . $tag . '>' . $message . '</' . $tag . '>';
	$out .= '<div class="headerButtons">';
	
	//	RSS button
	if ( $s1 == 'categories' && $s2 == 'show' ) {
		$out .= sprintf ( $rss, site_url ( 'rss/cat/' . $s3 ) );
	}
	elseif ( $s1 == 'tags' && $s2 == 'show' ) {
		$out .= sprintf ( $rss, site_url ( 'rss/tag/' . $s3 ) );
	}
	elseif ( $s1 == 'colors' && $s2 == 'browse' ) {
		$out .= sprintf ( $rss, site_url ( 'rss/color/' . $s3 ) );
	}
	elseif ( $s1 == 'members' && $s2 == 'show' ) {
		$out .= sprintf ( $rss, site_url ( 'rss/member/' . $s3 ) );
	}
	elseif ( $s1 == '' && $s2 == '' ) {
		$out .= sprintf ( $rss, site_url ( 'rss/welcome' ) );
	}
	elseif ( $s1 == 'welcome' && $s2 == 'latest' ) {
		$out .= sprintf ( $rss, site_url ( 'rss/latest' ) );
	}
	elseif ( $s1 == 'welcome' && $s2 == 'top' ) {
		$out .= sprintf ( $rss, site_url ( 'rss/top' ) );
	}
	elseif ( $s1 == 'wallpapers' && $s2 == 'browse_by_size' ) {
		$out .= sprintf ( $rss, site_url ( 'rss/type/' . $s3 ) );
	}
	
	$wallpaper_display_pages = array ( 
		
		'/', 
		'tags/show', 
		'categories/show', 
		'welcome/index', 
		'welcome/latest', 
		'welcome/top', 
		'welcome/random', 
		'search/results', 
		'colors/browse', 
		'members/show', 
		'wallpapers/browse_by_size' 
	);
	
	//	Wall choice buttons
	if ( in_array ( uri_segment ( 1 ) . "/" . uri_segment ( 2 ), $wallpaper_display_pages ) ) {
		$out .= $box . $list;
	}
	else {
		$out .= '<span class="end">&nbsp;</span>';
	}
	
	$out .= '</div></div>';
	
	return $out;
}

function is_logged_in () {
	$CI = &get_instance ();
	return ( $CI->session->userdata ( LOGGEDIN ) ) ? TRUE : FALSE;
}

function write_file ( $filename, $content ) {
	if ( $handle = fopen ( $filename, 'w' ) ) {
		if ( is_really_writable ( $filename ) ) {
			if ( fwrite ( $handle, $content ) === FALSE ) {
				return FALSE;
			}
			fclose ( $handle );
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	else {
		return FALSE;
	}
}

/**
 * A function for making time periods readable
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     2.0.0
 * @link        http://aidanlister.com/2004/04/making-time-periods-readable/
 * @param       int     number of seconds elapsed
 * @param       string  which time periods to display
 * @param       bool    whether to show zero time periods
 */
function nicetime ( $seconds, $use = null, $zeros = false ) {
	// Define time periods
	$periods = array ( 
		
		'years' => 31556926, 
		'Months' => 2629743, 
		'weeks' => 604800, 
		'days' => 86400, 
		'hours' => 3600, 
		'minutes' => 60, 
		'seconds' => 1 
	);
	
	// Break into periods
	$seconds = ( float ) $seconds;
	foreach ( $periods as $period => $value ) {
		if ( $use && strpos ( $use, $period [ 0 ] ) === false ) {
			continue;
		}
		$count = floor ( $seconds / $value );
		if ( $count == 0 && ! $zeros ) {
			continue;
		}
		$segments [ strtolower ( $period ) ] = $count;
		$seconds = $seconds % $value;
	}
	
	// Build the string
	foreach ( $segments as $key => $value ) {
		$segment_name = substr ( $key, 0, - 1 );
		$segment = $value . ' ' . $segment_name;
		if ( $value != 1 ) {
			$segment .= 's';
		}
		$array [] = $segment;
	}
	
	$str = implode ( ', ', $array );
	return $str;
}

function get_image_colors ( $image ) {
	if ( is_file ( $image ) ) {
		$PREVIEW_WIDTH = 150; //WE HAVE TO RESIZE THE IMAGE, BECAUSE WE ONLY NEED THE MOST SIGNIFICANT COLORS.
		$PREVIEW_HEIGHT = 150;
		$size = GetImageSize ( $image );
		$scale = 1;
		if ( $size [ 0 ] > 0 )
			$scale = min ( $PREVIEW_WIDTH / $size [ 0 ], $PREVIEW_HEIGHT / $size [ 1 ] );
		if ( $scale < 1 ) {
			$width = floor ( $scale * $size [ 0 ] );
			$height = floor ( $scale * $size [ 1 ] );
		}
		else {
			$width = $size [ 0 ];
			$height = $size [ 1 ];
		}
		$image_resized = @imagecreatetruecolor ( $width, $height );
		if ( $size [ 2 ] == 1 )
			$image_orig = @imagecreatefromgif ( $image );
		if ( $size [ 2 ] == 2 )
			$image_orig = @imagecreatefromjpeg ( $image );
		if ( $size [ 2 ] == 3 )
			$image_orig = @imagecreatefrompng ( $image );
		@imagecopyresampled ( $image_resized, $image_orig, 0, 0, 0, 0, $width, $height, $size [ 0 ], $size [ 1 ] ); //WE NEED NEAREST NEIGHBOR RESIZING, BECAUSE IT DOESN'T ALTER THE COLORS
		$im = $image_resized;
		$imgWidth = imagesx ( $im );
		$imgHeight = imagesy ( $im );
		for ( $y = 0; $y < $imgHeight; $y ++ ) {
			for ( $x = 0; $x < $imgWidth; $x ++ ) {
				$index = @imagecolorat ( $im, $x, $y );
				$Colors = @imagecolorsforindex ( $im, $index );
				$Colors [ 'red' ] = intval ( ( ( $Colors [ 'red' ] ) + 15 ) / 32 ) * 32; //ROUND THE COLORS, TO REDUCE THE NUMBER OF COLORS, SO THE WON'T BE ANY NEARLY DUPLICATE COLORS!
				$Colors [ 'green' ] = intval ( ( ( $Colors [ 'green' ] ) + 15 ) / 32 ) * 32;
				$Colors [ 'blue' ] = intval ( ( ( $Colors [ 'blue' ] ) + 15 ) / 32 ) * 32;
				if ( $Colors [ 'red' ] >= 256 )
					$Colors [ 'red' ] = 240;
				if ( $Colors [ 'green' ] >= 256 )
					$Colors [ 'green' ] = 240;
				if ( $Colors [ 'blue' ] >= 256 )
					$Colors [ 'blue' ] = 240;
				$hexarray [] = substr ( "0" . dechex ( $Colors [ 'red' ] ), - 2 ) . substr ( "0" . dechex ( $Colors [ 'green' ] ), - 2 ) . substr ( "0" . dechex ( $Colors [ 'blue' ] ), - 2 );
			}
		}
		$hexarray = array_count_values ( $hexarray );
		natsort ( $hexarray );
		$hexarray = array_reverse ( $hexarray, true );
		
		$out = array ();
		$hex = array_keys ( $hexarray );
		
		for ( $i = 0; $i < 5; $i ++ ) {
			if ( isset ( $hex [ $i ] ) ) {
				array_push ( $out, $hex [ $i ] );
			}
		}
		
		return $out;
	
	}
	return FALSE;
}

function read_file ( $filename ) {
	$handle = @fopen ( $filename, "r" );
	if ( $handle ) {
		while ( ! feof ( $handle ) ) {
			$lines [] = fgets ( $handle, 4096 );
		}
		fclose ( $handle );
		return $lines;
	}
	return FALSE;
}

function __v ( $var ) {
	echo '<pre>' . var_dump ( $var ) . '</pre>';
	die ();
}

function __p ( $var ) {
	echo '<pre>' . print_r ( $var, true ) . '</pre>';
	die ();
}

function create_wallpapers_array_by_measures () {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	$wallpapers = $CI->mwallpaper->get_wallpapers_measures ();
	
	$db_array = $wallpapers->result_array ();
	$array = array ();
	$psp_size = 480 + 272;
	
	$array [ $psp_size ] = 0;
	
	foreach ( $db_array as $value ) {
		$width = $value [ 'width' ];
		$height = $value [ 'height' ];
		$size = $width + $height;
		
		if ( $width == FALSE || $height == FALSE ) {
			continue;
		}
		
		if ( isset ( $array [ $size ] ) ) {
			$array [ $size ] += 1;
		}
		else {
			$array [ $size ] = 1;
		}
		
		if ( is_wide ( $height, $width ) ) {
			//	if it's wide, it could be psp as well so add it
			$array [ $psp_size ] ++;
		}
	}
	
	if ( $array [ $psp_size ] === 0 ) {
		unset ( $array [ $psp_size ] );
	}
	
	return $array;
}

function delete_user ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'musers' );
	
	$row = $CI->musers->get_member_by_id ( $id );
	if ( $row != FALSE ) {
		$CI->load->model ( 'mwallpaper' );
		$user_wallpapers = $CI->mwallpaper->get_user_wallpapers ( $id );
		
		if ( $user_wallpapers != FALSE ) {
			foreach ( $user_wallpapers as $wallpaper ) {
				delete_wallpaper ( $wallpaper->ID );
			}
		}
		
		$CI->musers->delete_mem ( $row->ID );
	}
}

function _get_child_permissions_array ( $parent ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mpermissions' );
	return $CI->mpermissions->_get_child_permissions_array ( $parent );
}

function get_permission_parent_id ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mpermissions' );
	return $CI->mpermissions->get_permission_parent_id ( $id );
}

function get_n_current_label_state ( $area, $type, $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mpermissions' );
	if ( ! $CI->mpermissions->check_permission_record ( $area, $type, $id ) && ! $CI->mpermissions->check_parent_permission_record ( $area, $type, $id ) ) {
		return "checked";
	}
}

function get_y_current_label_state ( $area, $type, $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mpermissions' );
	if ( $CI->mpermissions->check_permission_record ( $area, $type, $id ) || $CI->mpermissions->check_parent_permission_record ( $area, $type, $id ) ) {
		return "checked";
	}
}

function is_parent_permission ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mpermissions' );
	return $CI->mpermissions->is_parent_permission ( $id );
}

function get_permission_title ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mpermissions' );
	$row = $CI->mpermissions->get_permission ( $id );
	return $row->label;
}

function form_validation_get_value ( $field, $default_value = '' ) {
	$CI = &get_instance ();
	return ( $CI->form_validation->getField_value ( $field ) ) ? $CI->form_validation->getField_value ( $field ) : $default_value;
}

function form_validation_print_error ( $field, $css_class = null ) {
	$CI = &get_instance ();
	return $CI->form_validation->printField_error ( $field, $css_class );
}

function form_validation_set_select ( $field, $value, $callback = '' ) {
	$CI = &get_instance ();
	return $CI->form_validation->set_select ( $field, $value, $callback );
}

function form_validation_set_checkbox ( $field, $value, $callback = '' ) {
	$CI = &get_instance ();
	return $CI->form_validation->set_checkbox ( $field, $value, $callback );
}

function form_validation_set_radio ( $field, $value, $callback = '' ) {
	$CI = &get_instance ();
	return $CI->form_validation->set_radio ( $field, $value, $callback );
}

function get_groups_select ( $select_name, $selected_value = FALSE ) {
	$CI = & get_instance ();
	$CI->load->model ( 'musergroups' );
	
	$groups = $CI->musergroups->get_groups ();
	
	$out = "\t\t\t\t\t\t\t" . '<option value="">' . Lang ( 'all' ) . '</option>' . "\n";
	
	foreach ( $groups as $group ) {
		$out .= "\t\t\t\t\t\t\t" . '<option ' . form_validation_set_select ( $select_name, $group->ID, $selected_value ) . ' value="' . $group->ID . '">' . $group->title . '</option>' . "\n";
	}
	
	$out = "\t\t\t\t\t\t" . '<select name="' . $select_name . '" id="' . $select_name . '" class="element select large">' . $out . '</select>' . "\n";
	return $out;
}

function get_grant_categs_select ( $select_name, $please_select = TRUE, $selected_value = FALSE, $unlocked = FALSE ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mcategories' );
	$items = $CI->mcategories->get_cats4select ();
	
	if ( $please_select ) {
		$out = "\t\t\t\t\t\t\t" . '<option value="">' . Lang ( 'all' ) . '</option>' . "\n";
	}
	else {
		$out = "\t\t\t\t\t\t\t" . '<option value="all">' . Lang ( 'all' ) . '</option>' . "\n";
	}
	
	if ( $items != FALSE ) {
		$lookup = array ();
		foreach ( $items->result_array () as $item ) {
			$item [ 'children' ] = array ();
			$lookup [ $item [ 'ID' ] ] = $item;
		}
		
		$tree = array ();
		foreach ( $lookup as $id => $foo ) {
			$item = &$lookup [ $id ];
			if ( $item [ 'id_parent' ] == 0 ) {
				$tree [ $id ] = &$item;
			}
			else {
				if ( isset ( $lookup [ $item [ 'id_parent' ] ] ) ) {
					$lookup [ $item [ 'id_parent' ] ] [ 'children' ] [ $id ] = &$item;
				}
			}
		}
		
		build_options ( $tree, $out, 0, $select_name, $selected_value, $unlocked );
	}
	
	$out = "\t\t\t\t\t\t" . '<select name="' . $select_name . '" id="' . $select_name . '" class="element select large">' . $out . '</select>' . "\n";
	
	return $out;
}

function build_options ( $categs, &$out, $level = 0, $select_name, $selected_value, $unlocked = FALSE ) {
	$mylevel = 0;
	foreach ( $categs as $data ) {
		if ( ( $unlocked && ! $data [ 'is_locked' ] ) || ! $unlocked ) {
			$out .= '<option ' . form_validation_set_select ( $select_name, $data [ 'ID' ], $selected_value ) . ' style="padding-left:' . ( $level * 20 ) . 'px" value="' . $data [ 'ID' ] . '">' . str_repeat ( '&nbsp;&nbsp;', $level ) . '' . $data [ 'title' ] . '</option>' . "\n";
			if ( ! empty ( $data [ 'children' ] ) ) {
				$mylevel = $level;
				$mylevel ++;
				build_options ( $data [ 'children' ], $out, $mylevel, $select_name, $selected_value, $unlocked );
			}
		}	
	}

	return $out;
}

function Lang ( $key ) {
	try {
		require_once APPPATH . 'libraries/WS_Languages.php';
		$language = WS_Languages::getInstance ();
		return $language->getLang ( $key );
	} catch ( WS_Exception $e ) {
		//
	}
}

function selfUrlClean () {
	$CI = &get_instance ();
	return $CI->uri->segment ( 1 ) . $CI->uri->segment ( 2 );
}

function check_unique_hash ( $file ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return ( $CI->mwallpaper->check_unique_hash ( @md5_file ( $file ) ) ) ? TRUE : FALSE;
}

function delete_wallpapers_by_category ( $cat_id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	if ( $CI->mwallpaper->delete_wallpapers_by_category ( $cat_id ) ) {
		return TRUE;
	}
	return FALSE;
}

function delete_wallpaper ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	$CI->load->model ( 'mtags' );
	$row = get_wallpaper ( $id );
	$CI->mwallpaper->delete ( $id );
	delete_wallpaper_folder ( get_wallpaper_location ( $row ) );
}

function delete_wallpaper_folder ( $path ) {
	$base = realpath ( dirname ( $path ) );
	rmdir_r ( $path, TRUE );
	
	if ( check_empty_folder ( $base ) ) {
		delete_wallpaper_folder ( $base );
	}
}

function get_my_session_id () {
	$CI = &get_instance ();
	$session = $CI->session->userdata ( AUTH_SESSION_ID );
	return ( numeric ( $session ) ) ? $session : FALSE;
}

function is_admin () {
	$CI = &get_instance ();
	$session = $CI->session->userdata ( AUTH_SESSION_ID );
	return ( numeric ( $session ) && ( int ) $session === 1 ) ? TRUE : FALSE;
}

function breadcrumb ( $id, $link = FALSE ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mcategories' );
	$cat = get_category ( $id );
	$nav = '';
	$query = $CI->db->query ( 'SELECT ID, title, id_parent, lft, rgt FROM ' . DBPREFIX . 'categories WHERE lft < ' . qstr ( $cat->lft ) . ' AND rgt > ' . qstr ( $cat->rgt ) . ' ORDER BY lft ASC' );
	foreach ( $query->result () as $row ) {
		if ( $link == TRUE ) {
			$nav .= '<a href="' . get_category_url ( $row ) . '" title="' . $row->title . '">' . __character_limiter ( $row->title, 20 ) . '</a> &rarr; ';
		
		}
		else {
			$nav .= __character_limiter ( $row->title, 40 ) . ' &rarr; ';
		}
	}
	
	$nav_final = ( $link ) ? '<a href="' . get_category_url ( $cat ) . '" title="' . $cat->title . '">' . __character_limiter ( $cat->title, 20 ) . '</a>' : $cat->title;
	return $nav . $nav_final;
}

function get_subcats_wallpaper_childs ( $parent ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mcategories' );
	$CI->load->model ( 'mwallpaper' );
	
	$terminals = array ();
	$category = get_category ( $parent );
	
	$subcats = $CI->mcategories->get_subcategories ( $category->lft, $category->rgt );
	if ( $subcats->num_rows () > 0 ) {
		foreach ( $subcats->result () as $row ) {
			$terminals [] = $row->ID;
		}
	}
	return array_flatten ( $terminals );
}

function prepare_title ( $name ) {
	return trim ( ucfirst ( prepare_tag ( $name ) ) );
}

function prepare_tags ( $string ) {
	$tags = preg_split ( '/[,\s]+/', $string );
	$out = array ();
	
	foreach ( $tags as $tag ) {
		$tag = trim ( $tag );
		if ( ws_strlen ( $tag ) >= TAGS_MIN_CHARACTERS ) {
			array_push ( $out, prepare_tag ( $tag ) );
		}
	}
	
	return $out;
}

function array_flatten ( $a ) { //	flattens multi-dim arrays (distroys keys)
	$ab = array ();
	if ( ! is_array ( $a ) ) {
		return $ab;
	}
	
	foreach ( $a as $value ) {
		if ( is_array ( $value ) ) {
			$ab = array_merge ( $ab, array_flatten ( $value ) );
		}
		else {
			array_push ( $ab, $value );
		}
	}
	return $ab;
}

function get_partner ( $id ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mpartners' );
	$row = $CI->mpartners->get_partner ( $id );
	return ( $row != FALSE ) ? $row : FALSE;
}

function get_wallpaper_title ( $id ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mwallpaper' );
	$row = $CI->mwallpaper->get_wallpaper ( $id );
	return ( $row != FALSE ) ? $row->file_title : FALSE;
}

function get_wallpaper ( $id, $cache = FALSE ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_wallpaper ( $id, $cache );
}

function get_wallpaper_adv ( $id ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_wallpaper_adv ( $id );
}

function delete_cache ( $controller, $view ) {
	$CI = & get_instance ();
	delete_pages_cache ();
	return ( $CI->db->cache_delete ( $controller, $view ) ) ? TRUE : FALSE;
}

function delete_pages_cache () {
	rmdir_r ( BASEPATH . 'cache', FALSE );
}

function get_wallpaper_url_for_cache_del ( $id ) {
	$CI = & get_instance ();
	return base_url () . $CI->config->item ( 'index_page' ) . '/wallpapers/show/' . $id;
}

function delete_page_from_cache ( $file ) {
	if ( is_file ( BASEPATH . 'cache/' . $file ) ) {
		unlink ( BASEPATH . 'cache/' . $file );
	}
}

function evaluate_cache ( $parameter ) {
	$CI = & get_instance ();
	return ( $parameter ) ? $CI->db->cache_on () : $CI->db->cache_off ();
}

function get_wallpaper_url ( $row ) {
	if ( ENABLE_MOD_REWRITE ) {
		$title = ( trim ( $row->title_alias != '' ) ) ? trim ( $row->title_alias ) : trim ( $row->file_title );
		$title = prepare_tag ( preg_replace ( '/\s+/', '_', $title ), array ( 
			
			'_' 
		) );
		return site_url ( $title . '-' . $row->ID . '.html' );
	}
	else {
		return site_url ( 'wallpapers/show/' . $row->ID );
	}
}

function get_category_url ( $row = FALSE, $id = FALSE ) {
	if ( ENABLE_MOD_REWRITE ) {
		if ( $id != FALSE ) {
			$row = get_category ( $id );
		}
		
		$title = urlencode ( strip_punctuation ( trim ( $row->title ) ) );
		return site_url ( get_parent_segments ( $row->lft, $row->rgt ) . '/' . $title . '-' . $row->ID ) . '/';
	}
	else {
		return site_url ( 'categories/show/' . $row->ID . '/' );
	}
}

function get_parent_segments ( $left, $right ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mcategories' );
	$array = array ();
	$query = $CI->mcategories->get_parents ( $left, $right, FALSE );
	foreach ( $query->result () as $row ) {
		$array [] = urlencode ( strip_punctuation ( $row->title ) );
	}
	
	rsort ( $array );
	return implode ( '/', $array );
}

function admin_breadcrumb ( $parent, $link = FALSE ) {
	$obj = & get_instance ();
	$nav = "";
	if ( $parent > 0 ) {
		$croot = $parent;
		$cntr = 0;
		while ( $croot > 0 ) {
			$sbcts = $obj->db->query ( "SELECT * FROM " . DBPREFIX . "categories WHERE ID = " . qstr ( $croot ) );
			$crw = $sbcts->row ();
			if ( $cntr == 0 ) {
				if ( $link ) {
					if ( $parent == $crw->ID ) {
						$nav = get_cat_title ( $crw->ID );
					}
					else {
						$nav = '<a href="' . site_url ( 'categories/index/' . $crw->ID ) . '" title="' . get_cat_title ( $crw->ID ) . '">' . get_cat_title ( $crw->ID ) . '</a>';
					}
				}
				else {
					$nav = get_cat_title ( $crw->ID );
				}
			}
			else {
				if ( $parent != $croot ) {
					if ( $link ) {
						if ( $parent == $crw->ID ) {
							$nav = get_cat_title ( $crw->ID ) . ' &rarr; ' . $nav;
						}
						else {
							$nav = '<a href="' . site_url ( 'categories/index/' . $crw->ID ) . '" title="' . get_cat_title ( $crw->ID ) . '">' . get_cat_title ( $crw->ID ) . '</a> &rarr; ' . $nav;
						}
					}
					else {
						$nav = get_cat_title ( $crw->ID ) . ' &rarr; ' . $nav;
					}
				}
			}
			$cntr ++;
			$croot = $crw->id_parent;
		}
	}
	
	if ( $link == TRUE ) {
		$_root = '<a href="' . site_url ( 'categories' ) . '" title="categories">Root</a> ';
	}
	else {
		$_root = 'Root ';
	}
	
	$nav = '&rarr; ' . $nav;
	
	return $_root . $nav;
}

function assign_global_variables ( $array, $page_title ) {
	if ( isset ( $array [ 'wallpaper' ] ) ) {
		$wallpaper = $array [ 'wallpaper' ];
	}
	else {
		$wallpaper = null;
	}
	
	if ( isset ( $array [ 'category' ] ) ) {
		$category = $array [ 'category' ];
	}
	else {
		$category = null;
	}
	
	if ( isset ( $array [ 'member' ] ) ) {
		$member = $array [ 'member' ];
	}
	else {
		$member = null;
	}
	
	$logo_size = @getimagesize ( ROOTPATH . '/various/logo.gif' );
	
	$data = array ( 
		
		'page_title' => get_page_title ( $page_title, $wallpaper, $category, $member ), 
		'meta_description' => get_page_metaD ( $page_title, $wallpaper, $category, $member ), 
		'meta_keywords' => get_page_metaK ( $page_title, $wallpaper, $category, $member ), 
		'styles' => get_page_css ( $page_title ), 
		'javascript' => get_page_js ( $page_title ), 
		'top_menu' => setMenu (), 
		'footer' => setFooter (), 
		'slogan' => SITE_SLOGAN, 
		'home' => base_url (), 
		'logo_size_x' => $logo_size [ 0 ], 
		'logo_size_y' => $logo_size [ 1 ], 
		'header_height' => $logo_size [ 1 ] + 12, 
		'adult_confirmed' => is_adult_confirmed (), 
		'site_url' => ( substr ( site_url (), - 1 ) == '/' ) ? substr ( site_url (), 0, - 1 ) : site_url () 
	);
	
	return associative_push ( $array, $data );
}

function uri_segment ( $seg ) {
	$CI = &get_instance ();
	return $CI->uri->segment ( $seg );
}

function is_adult_confirmed () {
	return ( isset ( $_COOKIE [ "AdultConfirmed" ] ) ) ? TRUE : FALSE;
}

function get_top_colors () {
	$CI = &get_instance ();
	$CI->load->model ( 'mcolors' );
	$out = '';
	
	$top_colors = $CI->mcolors->get_color_data ();
	if ( $top_colors != FALSE ) {
		$i = 0;
		$out = '<div class="cocw"><table cellspacing="0" ><tr>';
		
		foreach ( $top_colors as $color ) {
			$out .= '<td><a href="' . site_url ( "colors/browse/" . $color->color ) . '" style="background:#' . $color->color . ';" title="#' . $color->color . '"><!-- --></a></td>';
			if ( $i == 26 ) {
				$out .= '</tr><tr>';
				$i = 0;
			}
			else {
				$i ++;
			}
		}
		$out = ( substr ( $out, 0, - 4 ) == '<tr>' ) ? substr ( $out, 0, - 4 ) . '</table></div>' : $out . '</table></div>';
	}
	return $out;
}

function get_wallpaper_colors ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mcolors' );
	
	$out = '';
	
	$top_colors = $CI->mcolors->get_wallpaper_colors ( $id );
	if ( $top_colors != FALSE ) {
		$i = 0;
		$out = '<div class="cocw"><table cellspacing="0" ><tr>' . "\n";
		
		foreach ( $top_colors as $color ) {
			$out .= '<td><a href="' . site_url ( "colors/browse/" . $color->color ) . '" style="background:#' . $color->color . ';" title="#' . $color->color . '"><!-- -- ></a></td>' . "\n";
			if ( $i == 26 ) {
				$out .= '</tr><tr>' . "\n";
				$i = 0;
			}
			else {
				$i ++;
			}
		}
		$out .= '</tr></table></div>';
	}
	return $out;
}

function get_wallpaper_tags ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mtags' );
	$otags = '';
	
	$tags = $CI->mtags->get_wallpaper_tags ( $id );
	if ( $tags != FALSE ) {
		foreach ( $tags as $tag ) {
			$otags .= "<a href=\"" . site_url ( "tags/show/" . urlencode ( $tag->tag ) ) . "\" title=\"{$tag->tag}\">{$tag->tag}</a>, ";
		}
	}
	
	$otags = substr ( $otags, 0, - 2 );
	
	return $otags;
}

function get_online_users_count () {
	$CI = &get_instance ();
	$w = $CI->db->query ( 'SELECT DISTINCT ip_address FROM  `' . DBPREFIX . 'sessions` WHERE last_activity > ' . ( now () - 180 ) . '' );
	return $w->num_rows ();
}

function get_mem_info ( $key ) {
	$CI = &get_instance ();
	$CI->load->library ( 'WB_meminfo' );
	return $CI->wb_meminfo->get ( $key );
}

function assign_smarty_global_variables ( $array ) {
	$CI = &get_instance ();
	
	$data = array ( 
		
		'images_path' => create_images_path (), 
		'base_url' => base_url (), 
		'ip_address' => $CI->input->ip_address (), 
		'CI' => $CI 
	);
	
	return associative_push ( $array, $data );
}

function load_smarty_template ( $tags, $page, $type, $clearAssign, $caching, $unique_id, $rel_dir = FALSE ) {
	$CI = & get_instance ();

	$CI->smarty->caching = ( ! RUN_ON_DEVELOPMENT ) ? $caching : FALSE;

	if ( ( bool ) $caching && ! RUN_ON_DEVELOPMENT ) {
		$CI->smarty->cache_lifetime = 3600 * 3;
	}

	$CI->smarty->template_dir = ( FALSE != $rel_dir ) ? $rel_dir : SMARTY_TEMPLATE_DIR;
	$CI->smarty->compile_dir = SMARTY_COMPILE_DIR;
	if ( ! RUN_ON_DEVELOPMENT && ( bool ) $caching ) {
		$CI->smarty->cache_dir = SMARTY_CACHE_DIR;
	}
	else {
		$CI->smarty->compile_check = true;
	}
	
	if ( $clearAssign || RUN_ON_DEVELOPMENT ) {
		$CI->smarty->clear_all_assign ();
	}
	
	$tags = assign_smarty_global_variables ( $tags );
	
	if ( FALSE != $rel_dir ) {
		$template = $rel_dir . DIRECTORY_SEPARATOR . $page . '.tpl';
	}
	else {
		$template = DEFAULT_TEMPLATE . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $page . '.tpl';
	}
	
	if ( ( bool ) $caching && ! RUN_ON_DEVELOPMENT ) {
		$CI->smarty->cache_lifetime = 3600 * 3;
		if ( ! $CI->smarty->is_cached ( $template, $unique_id ) ) {
			foreach ( $tags as $assign => $value ) {
				$CI->smarty->assign ( $assign, $value );
			}
		}
	}
	else {
		foreach ( $tags as $assign => $value ) {
			$CI->smarty->assign ( $assign, $value );
		}
	}

	$smarty_output = $CI->smarty->fetch ( $template, $unique_id );

	include_once APPPATH . 'libraries/WS_Dom.php';
	$oDom = WS_Dom::getInstance ();
	
	$CI->output->set_output ( $oDom->parse ( $smarty_output ) );
	return $smarty_output;
}

function clear_smarty_template ( $tpl_file, $cache_id = null ) {
	$CI = & get_instance ();
	return $CI->smarty->clear_cache ( $tpl_file . '.tpl', $cache_id );
}

function load_template ( $tags, $page, $clearAssign = TRUE, $caching = 0, $unique_id = '' ) {
	return load_smarty_template ( $tags, $page, 'pages', $clearAssign, $caching, $unique_id );
}

function load_form_template ( $tags, $page, $clearAssign = TRUE, $caching = 0, $unique_id = '' ) {
	return load_smarty_template ( $tags, $page, 'forms', $clearAssign, $caching, $unique_id );
}

function load_email_template ( $tags, $page, $clearAssign = TRUE, $caching = 0, $unique_id = '' ) {
	return load_smarty_template ( $tags, $page, 'email', $clearAssign, $caching, $unique_id );
}

function load_html_template ( $tags, $page, $clearAssign = TRUE, $caching = 0, $unique_id = '' ) {
	return load_smarty_template ( $tags, $page, 'html', $clearAssign, $caching, $unique_id );
}

function load_module_template ( $module, $tags, $page, $clearAssign = TRUE, $caching = 0, $unique_id = '' ) {
	return load_smarty_template ( $tags, $page, 'html', $clearAssign, $caching, $unique_id, MODULES_DIR . $module . DIRECTORY_SEPARATOR . 'templates' );
}

function get_right_side_content () {
	require_once APPPATH . "libraries/WS_Sidebar.php";
	$sidebar = WS_Sidebar::getInstance ();
	return load_html_template ( array ( 
		
		'sidebarContents' => $sidebar->sidebarOutput () 
	), 'right_side', FALSE, FALSE );
}

function get_category ( $id, $cache = FALSE ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mcategories' );
	return $CI->mcategories->get_category_by_id ( $id, $cache );
}

function associative_push ( $arr, $tmp ) {
	if ( is_array ( $tmp ) ) {
		foreach ( $tmp as $key => $value ) {
			$arr [ $key ] = $value;
		}
		return $arr;
	}
	return FALSE;
}

function get_redirect_after_login ( $url ) {
	$kt = explode ( 'index/', $url );
	return @$kt [ 1 ];
}

function get_next_prev_wallpapers ( $id ) {
	$CI = & get_instance ();
	$next_wallpaper = $CI->mwallpaper->get_next_wallpaper ( $id );
	$prev_wallpaper = $CI->mwallpaper->get_prev_wallpaper ( $id );
	$last_wallpaper = $CI->mwallpaper->get_last_wallpaper ( $id );
	$first_wallpaper = $CI->mwallpaper->get_first_wallpaper ( $id );
	
	make_thumb_if_not_exists ( $next_wallpaper );
	make_thumb_if_not_exists ( $prev_wallpaper );
	make_thumb_if_not_exists ( $last_wallpaper );
	make_thumb_if_not_exists ( $first_wallpaper );
	
	$tags = array ( 
		
		'next_wallpaper' => $next_wallpaper, 
		'prev_wallpaper' => $prev_wallpaper, 
		'last_wallpaper' => $last_wallpaper, 
		'first_wallpaper' => $first_wallpaper 
	);
	
	return load_html_template ( $tags, 'next_prev_wallpapers', FALSE, 1, md5 ( selfUrl () ) );
}

function __character_limiter ( $text, $limit ) {
	$CI = & get_instance ();
	$CI->load->helper ( 'text' );
	return character_limiter ( $text, $limit );
}

function show_cats ( $id_parent, $numcols, $start = 0, $limit = 21, $link_id = 'categories_wrp', $fetch_url = 'fetch' ) {
	$CI = & get_instance ();
	
	$CI->load->library ( 'Pagination' );
	$pagination = new Pagination ( );
	
	$pagination->start = $start;
	$pagination->limit = $limit;
	$pagination->filePath = site_url ( 'categories/' . $fetch_url . '/' . $id_parent );
	$pagination->is_ajax = TRUE;
	$pagination->link_id = $link_id;
	$pagination->select_what = '*';
	$pagination->the_table = '`' . DBPREFIX . 'categories`';
	$pagination->add_query = ' WHERE id_parent = ' . qstr ( ( int ) $id_parent ) . ' ORDER BY order1 ASC, title DESC';
	
	$query_cat = $pagination->getQuery ( TRUE );
	$pagination = $pagination->paginate ();
	
	if ( $query_cat->num_rows () > 0 ) {
		foreach ( $query_cat->result () as $row_cat ) {
			$items [] = array ( 
				
				0 => $row_cat->title, 
				1 => $row_cat->ID, 
				2 => $row_cat->items_counter, 
				3 => $row_cat->description, 
				4 => $row_cat->issubcat, 
				5 => $row_cat->id_parent, 
				6 => get_category_url ( $row_cat ) 
			);
		}
		
		// Number of Items
		$numitems = count ( $items );
		
		// Number of Rows
		$numrows = ceil ( $numitems / $numcols );
		
		$cat = '<div class="categories_wrp"><table width="100%"><!-- IT\'S TABULAR DATA MKAY ?! :) -->' . "\n";
		for ( $row = 1; $row <= $numrows; $row ++ ) {
			$cell = 0;
			$cat .= '		<tr>' . "\n";
			for ( $col = 1; $col <= $numcols; $col ++ ) {
				
				$cat .= '			<td width="' . round ( 100 / $numcols ) . '%">' . "\n";
				
				if ( $col === 1 ) {
					$cell += $row;
					
					if ( ! empty ( $items [ $cell - 1 ] ) ) {
						$link = $items [ $cell - 1 ] [ 6 ];
						if ( SHOW_CATEGORY_COUNTERS ) {
							$counter = ' [' . $items [ $cell - 1 ] [ 2 ] . ']';
						}
						else {
							$counter = '';
						}
						$cat .= '				<a href="' . $link . '" title="' . $items [ $cell - 1 ] [ 0 ] . '">' . __character_limiter ( $items [ $cell - 1 ] [ 0 ], 15 ) . $counter . '</a>' . "\n";
					}
				}
				else {
					$cell += $numrows;
					
					if ( ! empty ( $items [ $cell - 1 ] ) ) {
						$link = $items [ $cell - 1 ] [ 6 ];
						if ( SHOW_CATEGORY_COUNTERS ) {
							$counter = ' [' . $items [ $cell - 1 ] [ 2 ] . ']';
						}
						else {
							$counter = '';
						}
						$cat .= '				<a href="' . $link . '" title="' . $items [ $cell - 1 ] [ 0 ] . '">' . __character_limiter ( $items [ $cell - 1 ] [ 0 ], 15 ) . $counter . '</a>' . "\n";
					}
					else {
						$cat .= '&nbsp;' . "\n";
					}
				}
				$cat .= '			</td>' . "\n";
			}
			$cat .= '		</tr>' . "\n";
		}
		$cat .= '	</table></div>' . "\n" . $pagination;
		
		return $cat;
	}
}

function file_extension ( $filename ) {
	$path_info = pathinfo ( $filename );
	return $path_info [ 'extension' ];
}

function clear_cache () {
	reset_menu_data ();
	rmdir_r ( TEMP_DIR, FALSE );
	return TRUE;
}

function reset_menu_data () {
	$CI = &get_instance ();
	$CI->session->unset_userdata ( 'user_menu' );
}

function rmdir_r ( $dir, $DeleteMe = TRUE ) {
	if ( ! SAFE_MODE ) {
		ini_set ( "max_execution_time", "300" );
	}
	
	if ( is_file ( $dir ) ) {
		@unlink ( $dir );
	}
	else {
		if ( ! $dh = @opendir ( $dir ) )
			return;
		while ( false !== ( $obj = readdir ( $dh ) ) ) {
			if ( $obj == '.' || $obj == '..' ) {
				continue;
			}
			
			if ( ! @unlink ( $dir . '/' . $obj ) ) {
				rmdir_r ( $dir . '/' . $obj, true );
			}
		}
		
		closedir ( $dh );
		if ( $DeleteMe ) {
			@rmdir ( $dir );
		}
	}
	
	return TRUE;
}

function strip_punctuation ( $text, $alsoStopWords = TRUE ) {
	$CI = &get_instance ();
	$CI->load->library ( 'stopwords' );
	if ( $alsoStopWords ) {
		return $CI->stopwords->parseString ( $text );
	}
	else {
		return $CI->stopwords->removeSymbols ( $text );
	}
}

function get_friendly_time_elapsed ( $value ) {
	$hours = floor ( $value / 3600 );
	$value = $value % 3600;
	
	$minutes = floor ( $value / 60 );
	$value = $value % 60;
	
	$seconds = $value;
	return str_pad ( $hours, 2, '0', STR_PAD_LEFT ) . 'h:' . str_pad ( $minutes, 2, '0', STR_PAD_LEFT ) . 'm:' . str_pad ( $seconds, 2, '0', STR_PAD_LEFT ) . 's';
}

function prepare_tag ( $utf8_text, $replace = array ( ' ' ) ) {
	/* Strip HTML tags */
	$utf8_text = strip_tags ( $utf8_text );
	
	/* Decode HTML entities */
	$utf8_text = html_entity_decode ( $utf8_text, ENT_NOQUOTES );
	
	/* Remove punctuation */
	$utf8_text = strip_punctuation ( $utf8_text );
	
	$search = array ( 
		
		'/\s+/' 
	);
	
	$utf8_text = preg_replace ( $search, $replace, $utf8_text );
	
	return $utf8_text;
}

function ws_strtolower ( $string ) {
	$CI = &get_instance ();
	$CI->load->library ( 'WS_text_processor' );
	WS_text_processor::$str = $string;
	return WS_text_processor::strtolower ();
}

function ws_strlen ( $string ) {
	$CI = &get_instance ();
	$CI->load->library ( 'WS_text_processor' );
	WS_text_processor::$str = $string;
	return WS_text_processor::strlen ();
}

function ws_substr ( $string, $start, $length = FALSE ) {
	$CI = &get_instance ();
	$CI->load->library ( 'WS_text_processor' );
	WS_text_processor::$str = $string;
	return WS_text_processor::substr ( $start, $length );
}

function ws_split ( $split_del, $string ) {
	$CI = &get_instance ();
	$CI->load->library ( 'WS_text_processor' );
	WS_text_processor::$str = $string;
	return WS_text_processor::split ( $split_del );
}

function ws_strpos ( $string, $needle, $offset = 0 ) {
	$CI = &get_instance ();
	$CI->load->library ( 'WS_text_processor' );
	WS_text_processor::$str = $string;
	return WS_text_processor::strpos ( $needle, $offset );
}

function make_thumb_if_not_exists ( $row ) {
	$CI = & get_instance ();
	$CI->load->library ( 'wb_file_manager' );
	
	if ( ! $row ) {
		return FALSE;
	}
	
	$location = get_wallpaper_location ( $row );
	$wallpaper = $location . $row->hash . '.jpg';
	$thumb = $location . 'thumb_' . $row->hash . '.jpg';
	
	if ( ! is_file ( $thumb ) ) {
		include_once ( ROOTPATH . '/scripts/class.upload.php' );
		$handle = new Upload ( $wallpaper );
		
		if ( $handle->uploaded ) {
			$handle->jpeg_quality = WALLPAPER_QUALITY;
			$handle->image_resize = true;
			
			if ( $row->type == 'iphone' ) {
				$handle->image_ratio_x = true;
			}
			
			$handle->image_y = 109;
			$handle->file_new_name_body = 'thumb_' . $row->hash;
			$handle->process ( get_wallpaper_location ( $row ) );
			
			if ( $handle->processed ) {
				unset ( $handle );
			}
		}
	}
}

function generate_big_thumb ( $row ) {
	$CI = & get_instance ();
	$CI->load->library ( 'wb_file_manager' );
	$type = $row->type;
	
	$location = get_wallpaper_location ( $row );
	$wallpaper = $location . $row->hash . '.jpg';
	$thumb_name = 'thumb_big_' . $type . '_' . $row->hash . '.jpg';
	$thumb = $location . $thumb_name;
	
	if ( ! file_exists ( ( $thumb ) ) ) {
		if ( file_exists ( $wallpaper ) ) {
			if ( $row->type == 'iphone' || $row->width < 516 ) {
				$thumb_name = $row->hash . '.jpg';
			}
			else {
				include_once ( ROOTPATH . '/scripts/class.upload.php' );
				$handle = new Upload ( get_wallpaper_location ( $row ) . $row->hash . '.jpg' );
				
				if ( $handle->uploaded ) {
					$handle->jpeg_quality = WALLPAPER_QUALITY;
					$handle->image_resize = true;
					$handle->image_x = 516;
					
					$handle->image_ratio_y = true;
					$handle->file_new_name_body = 'thumb_big_' . $type . '_' . $row->hash;
					$handle->process ( get_wallpaper_location ( $row ) );
					
					if ( $handle->processed ) {
						add_colors ( $row->ID, $thumb );
						unset ( $handle );
					}
				}
			}
		}
	}
	
	return $thumb_name;
}

function add_tags ( $wallpaper_id, $tags ) {
	$tags = prepare_tags ( $tags );
	$CI = & get_instance ();
	$CI->load->model ( 'mtags' );
	$exclude = array ();
	
	if ( is_array ( $tags ) && ! empty ( $tags ) ) {
		$exclude_tags_obj = $CI->mtags->get_exclude_tags ();
		
		foreach ( $exclude_tags_obj as $exclude_tag ) {
			array_push ( $exclude, ws_strtolower ( $exclude_tag->tag ) );
		}
		
		foreach ( $tags as $tag ) {
			if ( ! in_array ( $tag, $exclude ) && $tag != '' && ws_strlen ( $tag ) >= TAGS_MIN_CHARACTERS ) {
				$tag_id = $CI->mtags->add ( $tag );
				$CI->mtags->add_rel ( $tag_id, $wallpaper_id );
			}
		}
	}
}

function add_colors ( $wallpaper_id, $image ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mcolors' );
	
	$colors = get_image_colors ( $image );
	
	if ( is_array ( $colors ) && ! empty ( $colors ) ) {
		foreach ( $colors as $color ) {
			if ( validate_hex ( $color ) ) {
				$color_id = $CI->mcolors->add ( $color );
				$CI->mcolors->add_rel ( $color_id, $wallpaper_id );
			}
		}
	}
}

function validate_hex ( $hex ) {
	return ( @preg_match ( '/[a-f0-9]{6}$/i', $hex ) ) ? TRUE : FALSE;
}

function build_tag_cloud () {
	$CI = & get_instance ();
	$CI->load->model ( 'mtags' );
	$CI->load->library ( 'wordcloud' );

	$shown = '';

	$tags = $CI->mtags->get_tags_data ();
	
	if ( $tags != FALSE ) {
		foreach ( $tags as $tag ) {
			if ( ws_strlen ( $tag->tag ) >= TAGS_MIN_CHARACTERS ) {
				$CI->wordcloud->addWord ( array (
					'word' => $tag->tag,
					'url' => site_url ( 'tags/show/' . urlencode ( $tag->tag ) ),
					'size' => $tag->occurences 
				) );
			}
		}

		unset ( $tags );

		if ( TAGS_ORDER_BY != '' ) {
			$CI->wordcloud->orderBy ( TAGS_ORDER_BY, TAGS_ORDER_BY_METHOD );
		}

		$cloud = $CI->wordcloud->showCloud ( 'array' );

		if ( count ( $cloud ) ) {
			$shown = '<div class="cloud">';
			foreach ( $cloud as $ctag ) {
				$shown .= '<a href="' . $ctag [ 'url' ] . '" class="word size' . $ctag [ 'range' ] . '">' . $ctag [ 'word' ] . '</a>' . "\n";
			}
			$shown .= '	</div>';
		}
	}
	
	return $shown;
}

/**
 * Taken from CI, converted to support multibite
 */

function character_limiter ( $str, $n = 500, $end_char = '&#8230;' ) {
	if ( ws_strlen ( $str ) < $n ) {
		return $str;
	}
	
	$str = preg_replace ( "/\s+/", ' ', str_replace ( array ( 
		
		"\r\n", 
		"\r", 
		"\n" 
	), ' ', $str ) );
	
	if ( ws_strlen ( $str ) <= $n ) {
		return $str;
	}
	
	$out = "";
	foreach ( explode ( ' ', trim ( $str ) ) as $val ) {
		$out .= $val . ' ';
		if ( strlen ( $out ) >= $n ) {
			return trim ( $out ) . $end_char;
		}
	}
}

function update_tags () {
	$CI = & get_instance ();
	$CI->load->model ( 'mtags' );
	return $CI->mtags->update_tags ();
}

function update_colors () {
	$CI = & get_instance ();
	$CI->load->model ( 'mcolors' );
	return $CI->mcolors->update_colors ();
}

function get_member_details ( $row ) {
	$tags = array ( 
		
		'row' => $row 
	);
	
	return load_html_template ( $tags, 'member_details' );
}

function add_start_end_slashes ( $input ) {
	if ( $input != '' && ( $input != '/' ) ) {
		if ( substr ( $input, - 1 ) != '/' ) {
			$input = $input . '/';
		}
		
		if ( substr ( $input, 0, - ( strlen ( $input ) - 1 ) ) != '/' ) {
			$input = '/' . $input;
		}
		return $input;
	}
}

function get_hits_nr_by_member ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_hits_nr_by_member ( $id );
}

function get_wallpapers_nr_by_member ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_wallpapers_nr_by_member ( $id );
}

function get_downloads_nr_by_member ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_downloads_nr_by_member ( $id );
}

function get_user_rating ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_user_rating ( $id );
}

function print_unique_id () {
	$prefix = 'W';
	$my_random_id = $prefix;
	$my_random_id .= chr ( rand ( 65, 90 ) );
	$my_random_id .= time ();
	$my_random_id .= uniqid ( $prefix );
	return $my_random_id;
}

function get_user_votes ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_user_votes ( $id );
}

function searchdir ( $path, $maxdepth = -1, $mode = "FULL", $d = 0 ) {
	if ( substr ( $path, strlen ( $path ) - 1 ) != '/' ) {
		$path .= '/';
	}
	$dirlist = array ();
	if ( $mode != "FILES" ) {
		$dirlist [] = $path;
	}
	
	if ( $handle = opendir ( $path ) ) {
		while ( false !== ( $file = readdir ( $handle ) ) ) {
			if ( $file != '.' && $file != '..' ) {
				$file = $path . $file;
				if ( ! is_dir ( $file ) ) {
					if ( $mode != "DIRS" ) {
						$dirlist [] = $file;
					}
				}
				elseif ( $d >= 0 && ( $d < $maxdepth || $maxdepth < 0 ) ) {
					$result = searchdir ( $file . '/', $maxdepth, $mode, $d + 1 );
					$dirlist = array_merge ( $dirlist, $result );
				}
			}
		}
		closedir ( $handle );
	}
	if ( $d == 0 ) {
		natcasesort ( $dirlist );
	}
	return ( $dirlist );
}

function get_available_templates () {
	$dirlist = searchdir ( TEMPLATES_DIR, 1 );
	$array = array ();
	foreach ( $dirlist as $dir ) {
		$kt = explode ( '/', $dir );
		if ( $kt [ count ( $kt ) - 2 ] != 'templates' ) {
			array_push ( $array, $kt [ count ( $kt ) - 2 ] );
		}
	}
	return $array;
}

function get_wallpaper_location ( $row = FALSE, $date_added = FALSE, $id = FALSE ) {
	if ( $row != FALSE ) {
		$date_added = $row->date_added;
		$wid = $row->ID;
	}
	elseif ( $date_added != FALSE && $id != FALSE ) {
		$wid = $id;
	}
	elseif ( $row == FALSE && $id != FALSE && $date_added == FALSE ) {
		if ( $date_added == FALSE ) {
			$row = get_wallpaper ( $id );
			$date_added = $row->date_added;
		}
		
		$wid = $id;
	}
	else {
		log_message ( "error", __FILE__ . ":" . __LINE__ . " - Unable to find wallpaper location using the specified parameters" );
		return FALSE;
	}
	
	$y = mdate ( "%Y", $date_added );
	$m = mdate ( "%m", $date_added );
	$d = mdate ( "%d", $date_added );
	
	return ROOTPATH . '/uploads/wallpapers/' . $y . '/' . $m . '/' . $d . '/' . $wid . '/';
}

/**
 * Use the next one, this function will be retired
 */
function get_wallpaper_url_location ( $row, $date_added = FALSE ) {
	if ( ! $date_added ) {
		$y = mdate ( "%Y", $row->date_added );
		$m = mdate ( "%m", $row->date_added );
		$d = mdate ( "%d", $row->date_added );
	}
	else {
		$y = mdate ( "%Y", $date_added );
		$m = mdate ( "%m", $date_added );
		$d = mdate ( "%d", $date_added );
	}
	
	return base_url () . 'uploads/wallpapers/' . $y . '/' . $m . '/' . $d . '/' . $row->ID . '/';
}

function get_wallpaper_url_location_fixed ( $id, $date_added ) {
	$out = mdate ( "%Y/%m/%d", $date_added ) . "/$id/";
	return base_url () . 'uploads/wallpapers/' . $out;
}

function create_file_name ( $path_to_file ) {
	$CI = &get_instance ();
	$CI->load->library ( 'wb_file_manager' );
	return @md5_file ( $path_to_file ) . '.' . $CI->wb_file_manager->file_extension ( $path_to_file );
}

function check_empty_folder ( $folder ) {
	$files = array ();
	if ( $handle = opendir ( $folder ) ) {
		while ( false !== ( $file = readdir ( $handle ) ) ) {
			if ( $file != "." && $file != ".." ) {
				return FALSE;
			}
		}
		closedir ( $handle );
	}
	return TRUE;
}

function get_votes_nr ( $wall_id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_votes_nr ( $wall_id );
}

function get_wallpaper_hits ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_wallpaper_hits ( $id );
}

function check_if_voted ( $wall_id, $ip ) {
	$CI = &get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->check_if_voted ( $wall_id, $ip );
}

function get_more_from_author ( $id, $exclude, $start = 0, $limit = 6 ) {
	$CI = & get_instance ();
	$display_type = isset ( $_COOKIE [ 'wallpaper_display_type' ] ) ? $_COOKIE [ 'wallpaper_display_type' ] : 'box';
	
	$CI->load->library ( 'pagination' );
	$CI->pagination->is_ajax = TRUE;
	$CI->pagination->link_id = 'get_more_from_author';
	$CI->pagination->start = $start;
	$CI->pagination->limit = $limit;
	$CI->pagination->filePath = site_url ( 'wallpapers/ajax_more_from_author/' . $id );
	
	if ( $display_type == 'list' ) {
		$CI->pagination->select_what = 'w.*,u.Username';
	}
	else {
		$CI->pagination->select_what = 'w.*';
	}
	
	if ( $display_type == 'list' ) {
		$CI->pagination->the_table = '' . DBPREFIX . 'wallpapers w INNER JOIN ' . DBPREFIX . 'users u ON(u.ID=w.user_id)';
	}
	else {
		$CI->pagination->the_table = DBPREFIX . 'wallpapers w';
	}
	
	$CI->pagination->add_query = ' WHERE w.active = 1 AND w.parent_id = 0 AND w.ID != ' . qstr ( ( int ) $exclude ) . '  AND w.user_id = ' . qstr ( ( int ) $id );
	
	$CI->pagination->add_query .= ' ORDER BY w.date_added DESC';
	
	$query = $CI->pagination->getQuery ( TRUE );
	
	if ( ! $query->num_rows () ) {
		return FALSE;
	}
	
	$content = '<div id="get_more_from_author">' . "\n";
	$content .= get_wallpapers ( $query );
	
	$content .= $CI->pagination->paginate ();
	$content .= '</div>' . "\n";
	return $content;
}

function get_more_from_category ( $cat_id, $exclude, $start = 0, $limit = 6 ) {
	$CI = & get_instance ();
	$display_type = isset ( $_COOKIE [ 'wallpaper_display_type' ] ) ? $_COOKIE [ 'wallpaper_display_type' ] : 'box';
	
	$CI->load->library ( 'pagination' );
	$CI->pagination->is_ajax = TRUE;
	$CI->pagination->link_id = 'get_more_from_category';
	$CI->pagination->start = $start;
	$CI->pagination->limit = $limit;
	$CI->pagination->filePath = site_url ( 'wallpapers/ajax_more_from_category/' . $cat_id );
	
	if ( $display_type == 'list' ) {
		$CI->pagination->select_what = 'w.*,u.Username';
	}
	else {
		$CI->pagination->select_what = 'w.*';
	}
	
	if ( $display_type == 'list' ) {
		$CI->pagination->the_table = '' . DBPREFIX . 'wallpapers w INNER JOIN ' . DBPREFIX . 'users u ON(u.ID=w.user_id)';
	}
	else {
		$CI->pagination->the_table = DBPREFIX . 'wallpapers w';
	}
	
	$CI->pagination->add_query = ' WHERE w.active = 1 AND w.parent_id = 0 AND w.ID != ' . qstr ( ( int ) $exclude ) . '  AND w.cat_id = ' . qstr ( ( int ) $cat_id );
	$CI->pagination->add_query .= ' ORDER BY w.date_added DESC';
	
	$query = $CI->pagination->getQuery ( TRUE );
	
	if ( ! $query->num_rows () ) {
		return FALSE;
	}
	$content = '<div id="get_more_from_category">' . "\n";
	$content .= get_wallpapers ( $query );
	
	$content .= $CI->pagination->paginate ();
	$content .= '</div>' . "\n";
	return $content;
}

function get_online_users_table () {
	$CI = & get_instance ();
	$CI->load->model ( 'master' );
	$start = $CI->uri->segment ( 3, 0 );
	$CI->load->library ( 'pagination' );
	$CI->pagination->start = $start;
	$CI->pagination->limit = 50;
	$CI->pagination->filePath = site_url ( 'welcome/users_online' );
	$CI->pagination->select_what = 'DISTINCT ip_address, user_data, uri_string, last_activity';
	$CI->pagination->the_table = '`' . DBPREFIX . 'sessions`';
	$CI->pagination->add_query = ' WHERE last_activity > ' . ( now () - 180 ) . ' ORDER BY last_activity DESC';
	
	$query = $CI->pagination->getQuery ( TRUE );
	$online_users = array ();
	$i = 0;
	
	foreach ( $query->result () as $row ) {
		$user_data = unserialize ( $row->user_data );
		
		if ( ! isset ( $row->uri_string ) ) {
			continue;
		}
		
		unset ( $user_data [ 'KB_user_id' ] );
		unset ( $user_data [ 'KB_logged_in' ] );
		unset ( $user_data [ 'all_permissions' ] );
		unset ( $user_data [ 'user_permissions' ] );
		unset ( $user_data [ 'group_permissions' ] );
		unset ( $user_data [ 'user_menu' ] );
		
		$online_users [ $i ] [ $i ] [ 'current_page' ] = $row->uri_string;
		$online_users [ $i ] [ $i ] [ 'username' ] = 'Guest';
		$online_users [ $i ] [ $i ] [ 'last_activity' ] = mdate ( '%d-%m-%Y %h:%i:%j', $row->last_activity );
		$online_users [ $i ] [ $i ] [ 'user_id' ] = '0';
		
		$meminfo = ( isset ( $user_data [ 'meminfo_loaded' ] ) && ! empty ( $user_data [ 'meminfo_loaded' ] ) ) ? unserialize ( $user_data [ 'meminfo_loaded' ] ) : FALSE;
		
		if ( $meminfo != FALSE ) {
			foreach ( $meminfo as $k => $v ) {
				foreach ( array_keys ( $v ) as $key ) {
					if ( $key != 'Username' && $key != 'ID' ) {
						unset ( $meminfo [ $k ] [ $key ] );
					}
					else {
						if ( $key == 'Username' ) {
							$online_users [ $i ] [ $i ] [ 'username' ] = $meminfo [ $k ] [ $key ];
						}
						elseif ( $key == 'ID' ) {
							$online_users [ $i ] [ $i ] [ 'user_id' ] = $meminfo [ $k ] [ $key ];
						}
					
					}
				}
			}
		}
		$i ++;
	}
	
	$tags = array ( 
		
		'online_users' => $online_users, 
		'pagination' => $CI->pagination->paginate () 
	);
	
	return load_html_template ( $tags, 'online_users', TRUE, 0 );
}

function get_wallpaper_comments ( $id, $start = 0 ) {
	$CI = & get_instance ();
	$CI->load->library ( 'pagination' );
	$CI->pagination->is_ajax = TRUE;
	$CI->pagination->link_id = 'wallpaper_comments';
	$CI->pagination->start = $start;
	$CI->pagination->limit = 10;
	$CI->pagination->filePath = site_url ( 'comments/get_wallpaper_comments/' . $id );
	$CI->pagination->select_what = '*';
	$CI->pagination->the_table = '`' . DBPREFIX . 'comments`';
	$CI->pagination->add_query = ' WHERE `active` = 1 AND item_id = ' . qstr ( ( int ) $id );
	$CI->pagination->add_query .= ' ORDER BY date_added DESC';
	
	$query = $CI->pagination->getQuery ( TRUE );
	
	$data = array ( 
		
		'pagination' => $CI->pagination->paginate (), 
		'rows' => $query, 
		'button' => __button ( Lang ( 'add_comment' ), '', '', 'FFFFFF', 'AlteHaasGroteskBold', 10, 'submit', array ( 
			
			'onclick' => 'dialog(650,450,\'' . Lang ( 'add_comment' ) . '\',true,true,\'' . site_url ( 'comments/add_comment/' . $id ) . '\');' 
		) ) 
	);
	
	return load_html_template ( $data, 'comments' );
}

function get_gravatar ( $email ) {
	$size = 40;
	$grav_url = "http://www.gravatar.com/avatar.php?gravatar_id=" . md5 ( ws_strtolower ( $email ) ) . "&size=" . $size;
	return $grav_url;
}

function get_wallpaper_downloads ( $id ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_wallpaper_downloads ( $id );
}

function get_top_rated_members ( $limit = 10 ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mwallpaper' );
	
	$out = "\n";
	
	$members = $CI->mwallpaper->get_top_rated_members ( $limit );
	if ( $members != FALSE ) {
		$out .= '<ul class="right">';
		foreach ( $members as $row ) {
			if ( $row->score != 0 ) {
				$out .= '<li><a href="' . site_url ( 'members/show/' . $row->ID ) . '" title="' . $row->Username . '"><span class="ui-icon ui-icon-signal" style="-moz-user-select: none;">&nbsp;</span>' . $row->Username . ' [ ' . round ( $row->score, 2 ) . ' ]</a></li>';
			}
		}
		$out .= '</ul>';
	}
	
	return $out;
}

function get_top_contributors ( $limit = 10 ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mwallpaper' );
	
	$out = "\n";
	
	$members = $CI->mwallpaper->get_top_contributors ( $limit );
	if ( $members != FALSE ) {
		$out .= '<ul class="left">';
		foreach ( $members as $row ) {
			$out .= '<li><a href="' . site_url ( 'members/show/' . $row->ID ) . '" title="' . $row->Username . '"><span class="ui-icon ui-icon-carat-2-e-w" style="-moz-user-select: none;">&nbsp;</span>' . $row->Username . ' [ ' . $row->nr . ' ]</a></li>';
		}
		$out .= '</ul>';
	}
	
	return $out;
}

function get_wallpaper_display ( &$row, $margin = FALSE ) {
	make_thumb_if_not_exists ( $row );
	$margin_append = ( $margin ) ? ' margin' : '';
	$row->rating = round ( $row->rating );
	
	$display_type = isset ( $_COOKIE [ 'wallpaper_display_type' ] ) ? $_COOKIE [ 'wallpaper_display_type' ] : FALSE;
	
	if ( ! $display_type ) {
		$display_type = 'box';
	}
	
	$tags = array ( 
		
		'margin_append' => $margin_append, 
		'row' => $row, 
		'display' => $display_type 
	);
	
	return load_html_template ( $tags, 'wallpaper_preview', TRUE, 0 );
}

function get_wallpapers ( $query ) {
	$content = '';
	$display_type = isset ( $_COOKIE [ 'wallpaper_display_type' ] ) ? $_COOKIE [ 'wallpaper_display_type' ] : 'box';
	
	if ( $query != FALSE && $query->num_rows () > 0 ) {
		$row_count = 1;
		$content .= '	<ul class="' . $display_type . '">' . "\n";
		
		foreach ( $query->result () as $row ) {
			$margin_append = ( $row_count % 3 ) ? TRUE : FALSE;
			$content .= get_wallpaper_display ( $row, $margin_append );
			( ! $margin_append && $display_type != 'list' ) ? $content .= '		<li class="clear">&nbsp;</li>' . "\n" : '';
			$row_count ++;
		}
		
		$content .= '	</ul>' . "\n";
		$content .= '	<div class="clear"><!-- --></div>' . "\n";
		
		if ( mb_strlen ( $content ) ) {
			$content .= '<script type="text/javascript">$(document).ready(function(){$(\'.picture_wrapper\').hover(function(){$(this).children(\'.title\').slideDown(\'fast\');},function(){$(this).children(\'.title\').slideUp(\'fast\');});
	});$(\'.picture_wrapper .list_data ul.left_c li.data:first-child, .picture_wrapper .list_data ul.right_c li:first-child\').css({"border-top":0});$(\'.picture_wrapper .list_data ul.left_c li.data:last-child, .picture_wrapper .list_data ul.right_c li:last-child\').css({"border-bottom":0});;</script>';
		}
	}
	else {
		$content = evaluate_response ( 'info|' . Lang ( 'no_wallpapers_added' ) );
	}
	
	return $content;
}

function do_xhtml ( $string ) {
	$string = stripslashes ( $string );
	$string = str_replace ( "'", "&#039;", $string );
	$string = str_replace ( '"', '&quot;', $string );
	$string = str_replace ( '`', '', $string );
	$string = str_replace ( '>', '&gt;', $string );
	$string = str_replace ( '<', '&lt;', $string );
	
	return $string;
}

function get_category_wallpapers ( $cat_id ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mcategories' );
	$CI->load->model ( 'mwallpaper' );
	$nr = 0;
	
	if ( $CI->mcategories->is_parent_category ( $cat_id ) ) {
		$nr += $CI->mwallpaper->get_wallpapers_nr_from_category ( $cat_id );
		foreach ( $CI->mcategories->get_childs_category ( $cat_id ) as $child ) {
			$nr += get_category_wallpapers ( $child->ID );
		}
	}
	else {
		return $CI->mwallpaper->get_wallpapers_nr_from_category ( $cat_id );
	}
	
	return $nr;
}

function get_wallpapers_nr ( $status, $for_member = FALSE ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return ( int ) $CI->mwallpaper->get_wallpapers_nr ( $status, $for_member );
}

function get_wallpaper_rating ( $id ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return round ( $CI->mwallpaper->get_wallpaper_rating ( $id ), 2 );
}

function get_level_access ( $id ) {
	$CI = & get_instance ();
	$CI->load->model ( 'musers' );
	return $CI->musers->get_level_access ( $id );
}

function check_allowed_size ( $allowed_sizes, $file, $type = 'w' ) {
	$details = getimagesize ( $file );
	$heights = array ();
	$widths = array ();
	
	foreach ( $allowed_sizes as $key => $value ) {
		foreach ( $value as $height => $width ) {
			$heights [] = $height;
			$widths [] = $width;
		}
	}
	return ( $type == 'w' ) ? ( ( isset ( $details [ 0 ] ) && in_array ( $details [ 0 ], array_values ( $widths ) ) ) ? TRUE : FALSE ) : ( ( isset ( $details [ 1 ] ) && in_array ( $details [ 1 ], array_values ( $heights ) ) ) ? TRUE : FALSE );
}

function check_wide ( $file ) {
	$details = getimagesize ( $file );
	$heights = array ();
	$widths = array ();
	
	foreach ( get_sizes () as $height => $width ) {
		if ( isset ( $details [ 0 ] ) && isset ( $details [ 1 ] ) && $details [ 0 ] == $width && $details [ 1 ] == $height && round ( $width / $height ) == 2 ) {
			return TRUE;
		}
	}
	return FALSE;
}

function get_sizes ( $type = FALSE ) {
	$normal = array ( 
		
		1920 => 2560, 
		1440 => 1920, 
		1260 => 1680, 
		1200 => 1600, 
		1024 => 1280, 
		960 => 1280, 
		864 => 1152, 
		768 => 1024, 
		600 => 800 
	);
	$wide = array ( 
		
		1600 => 2560, 
		1200 => 1920, 
		1050 => 1680, 
		900 => 1440, 
		800 => 1280 
	);
	$psp = array ( 
		
		272 => 480 
	);
	$iphone = array ( 
		
		480 => 320 
	);
	$hd = array ( 
		
		1080 => 1920, 
		768 => 1366, 
		720 => 1280, 
		480 => 852 
	);
	$multi = array ( 
		
		1536 => 4096, 
		1200 => 3840, 
		1050 => 3360, 
		1200 => 3200, 
		900 => 2880, 
		1050 => 2800, 
		960 => 2560, 
		864 => 2304, 
		768 => 2048, 
		625 => 2000 
	);
	
	if ( $type != FALSE ) {
		switch ( $type ) {
			case 'normal' :
				return $normal;
				break;
			case 'wide' :
				return $wide;
				break;
			case 'psp' :
				return $psp;
				break;
			case 'iphone' :
				return $iphone;
				break;
			case 'hd' :
				return $hd;
				break;
			case 'multi' :
				return $multi;
				break;
		}
	}
	elseif ( ! $type ) {
		return array ( 
			'normal' => $normal, 
			'wide' => $wide, 
			'psp' => $psp, 
			'iphone' => $iphone, 
			'hd' => $hd, 
			'multi' => $multi 
		);
	}
	return array ();
}

function is_normal ( $h, $w ) {
	$sizes = get_sizes ( 'normal' );
	return ( isset ( $sizes [ $h ] ) && $sizes [ $h ] == $w ) ? TRUE : FALSE;
}

function is_wide ( $h, $w ) {
	$sizes = get_sizes ( 'wide' );
	return ( isset ( $sizes [ $h ] ) && $sizes [ $h ] == $w ) ? TRUE : FALSE;
}

function is_psp ( $h, $w ) {
	return ( $h == 272 && $w == 480 ) ? TRUE : FALSE;
}

function is_iphone ( $h, $w ) {
	return ( $h == 480 && $w == 320 ) ? TRUE : FALSE;
}

function is_hd ( $h, $w ) {
	$sizes = get_sizes ( 'hd' );
	return ( isset ( $sizes [ $h ] ) && $sizes [ $h ] == $w ) ? TRUE : FALSE;
}

function is_multi ( $h, $w ) {
	$sizes = $sizes = get_sizes ( 'multi' );
	return ( isset ( $sizes [ $h ] ) && $sizes [ $h ] == $w ) ? TRUE : FALSE;
}

function detect_wallpaper_type ( $sizes, $h, $w ) {
	if ( is_iphone ( $h, $w ) ) {
		return 'iphone';
	}
	if ( is_psp ( $h, $w ) ) {
		return 'psp';
	}
	if ( is_hd ( $h, $w ) ) {
		return 'hd';
	}
	if ( is_multi ( $h, $w ) ) {
		return 'multi';
	}
	if ( is_wide ( $h, $w ) ) {
		return 'wide';
	}
	if ( is_normal ( $h, $w ) ) {
		return 'normal';
	}
	return 'other';
}

function mkdir_recursive ( $pathname, $mode = 0777 ) {
	is_dir ( dirname ( $pathname ) ) || mkdir_recursive ( dirname ( $pathname ), $mode );
	return is_dir ( $pathname ) || @mkdir ( $pathname, $mode );
}

function get_lower_sizes ( $original_width, $type ) {
	$out = array ();
	
	foreach ( get_sizes ( $type ) as $height => $width ) {
		if ( $width < $original_width ) {
			$out [ $height ] = $width;
		}
	}
	
	ksort ( $out );
	return $out;
}

function get_lowest_sizes ( $sizes, $type ) {
	$wide = array ();
	$normal = array ();
	
	$new_width = 100000;
	
	foreach ( $sizes as $size ) {
		foreach ( $size as $height => $width ) {
			if ( round ( $width / $height ) == 2 ) { //wide
				if ( $width < $new_width ) {
					$wide = array ( 
						
						$height, 
						$width 
					);
				}
			}
			elseif ( round ( $width / $height ) == 1 ) { //normal
				if ( $width < $new_width ) {
					$normal = array ( 
						
						$height, 
						$width 
					);
				}
			}
		}
	}
	
	switch ( $type ) {
		case 'normal' :
			return $normal;
			break;
		
		case 'wide' :
			return $wide;
			break;
	}
}

function ByteSize ( $file_size ) {
	$file_size = $file_size - 1;
	if ( $file_size >= 1099511627776 )
		$show_filesize = number_format ( ( $file_size / 1099511627776 ), 2 ) . " TB";
	elseif ( $file_size >= 1073741824 )
		$show_filesize = number_format ( ( $file_size / 1073741824 ), 2 ) . " GB";
	elseif ( $file_size >= 1048576 )
		$show_filesize = number_format ( ( $file_size / 1048576 ), 2 ) . " MB";
	elseif ( $file_size >= 1024 )
		$show_filesize = number_format ( ( $file_size / 1024 ), 2 ) . " KB";
	elseif ( $file_size > 0 )
		$show_filesize = $file_size . " b";
	elseif ( $file_size == 0 || $file_size == - 1 )
		$show_filesize = "0 b";
	return $show_filesize;
}

function get_wallpapers_per_page () {
	return WALLPAPERS_PER_COLUMN * 3;
}

function global_reset_categories () {
	reset_counters (); //reset counters
	update_lft_rgt_values ();
	clear_cache ();
	return TRUE;
}

function get_categories_counters ( $cats ) {
	foreach ( $cats as $value ) {
		$array [ $value [ 'ID' ] ] = $value;
	}
	
	foreach ( $array as $id => $category ) {
		$categories [ $id ] [ 'total_wallpapers' ] = $category [ 'wallpapers' ];
		$categories [ $id ] [ 'subcats' ] = $category [ 'subcategories' ];
		if ( $category [ 'id_parent' ] ) {
			if ( isset ( $categories [ $category [ 'id_parent' ] ] ) ) {
				$categories [ $category [ 'id_parent' ] ] [ 'total_wallpapers' ] += $category [ 'wallpapers' ];
				$categories [ $category [ 'id_parent' ] ] [ 'subcats' ] += $category [ 'subcategories' ];
			}
		}
	}
	
	return $categories;
}

function get_todays_wallpapers_nr () {
	$CI = & get_instance ();
	$CI->load->model ( 'mwallpaper' );
	return $CI->mwallpaper->get_todays_wallpapers_nr ();
}

function reset_counters () {
	$CI = & get_instance ();
	$CI->load->model ( 'mcategories' );
	$categories = $CI->mcategories->getCats_adv ();
	if ( $categories != FALSE ) {
		$cdata = get_categories_counters ( $categories );
		foreach ( $cdata as $id => $value ) {
			$issubcat = ( $value [ 'subcats' ] > 0 ) ? '>' : '';
			$CI->mcategories->update_counters ( $id, $value [ 'total_wallpapers' ], $value [ 'subcats' ], $issubcat );
		}
	}
}

function update_lft_rgt_values () {
	$CI = & get_instance ();
	$CI->load->model ( 'mcategories' );
	rebuild_tree ( 0, 0 );
}

/*
	 http://www.sitepoint.com/article/hierarchical-data-database/2
	*/

function rebuild_tree ( $parent, $left ) {
	$CI = & get_instance ();
	$right = $left + 1;
	
	$result = $CI->db->query ( 'SELECT ID FROM ' . DBPREFIX . 'categories WHERE id_parent = ' . qstr ( $parent ) );
	if ( $result->num_rows () > 0 ) {
		foreach ( $result->result () as $row ) {
			$right = rebuild_tree ( $row->ID, $right );
		}
	}
	
	$CI->db->query ( 'UPDATE ' . DBPREFIX . 'categories SET lft = ' . qstr ( $left ) . ', rgt=' . qstr ( $right ) . ' WHERE ID = ' . qstr ( $parent ) );
	
	return $right + 1;
}

function get_category_subcategories ( $cat_id ) {
	$CI = & get_instance ();
	$CI->load->model ( 'mcategories' );
	$nr = 0;
	
	if ( $CI->mcategories->is_parent_category ( $cat_id ) ) {
		$nr += $CI->mcategories->get_cat_subcats_r ( $cat_id );
		foreach ( $CI->mcategories->get_childs_category ( $cat_id ) as $child ) {
			$nr += get_category_subcategories ( $child->ID );
		}
	}
	else {
		return $CI->mcategories->get_cat_subcats_r ( $cat_id );
	}
	
	return $nr;
}

function rearrange_cat_subcats () {
	$CI = & get_instance ();
	$CI->load->model ( 'mcategories' );
	$getAllCats = $CI->mcategories->getCats ();
	if ( $getAllCats != FALSE ) {
		foreach ( $getAllCats->result () as $gencat ) {
			( $CI->mcategories->get_cat_subcats_r ( $gencat->ID ) > 0 ) ? $CI->mcategories->updateCats ( $gencat->ID, TRUE ) : $CI->mcategories->updateCats ( $gencat->ID );
		}
	}
}

function create_images_path () {
	return base_url () . 'templates/' . DEFAULT_TEMPLATE . '/images/';
}

function get_cat_title ( $cat_id ) {
	$obj = & get_instance ();
	$obj->load->model ( 'mcategories' );
	return $obj->mcategories->get_cat_title ( $cat_id );
}

function get_username ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'musers' );
	$row = $CI->musers->get_member_by_id ( $id );
	return ( $row != FALSE ) ? $row->Username : FALSE;
}

function get_suspended_users_nr () {
	$CI = &get_instance ();
	$CI->load->model ( 'musers' );
	return $CI->musers->get_suspended_users_nr ();
}

function get_active_users_nr () {
	$CI = &get_instance ();
	$CI->load->model ( 'musers' );
	return $CI->musers->get_active_users_nr ();
}

function get_user_group ( $id = FALSE ) {
	$CI = &get_instance ();
	$CI->load->model ( 'musers' );
	return ( $CI->musers->get_user_group ( $id ) != FALSE ) ? $CI->musers->get_user_group ( $id ) : FALSE;
}

function get_user_group_id ( $id = FALSE ) {
	$CI = &get_instance ();
	$CI->load->model ( 'musers' );
	return ( $id != FALSE ) ? $CI->musers->get_level_access ( $id ) : $CI->musers->get_level_access ( $CI->session->userdata ( AUTH_SESSION_ID ) );
}

function get_inactive_users_nr () {
	$CI = &get_instance ();
	$CI->load->model ( 'musers' );
	return $CI->musers->get_inactive_users_nr ();
}

function get_group_members ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'musergroups' );
	return ( $CI->musergroups->get_group_members ( $id ) != FALSE ) ? $CI->musergroups->get_group_members ( $id ) : 0;
}

function get_group_title ( $id ) {
	$CI = &get_instance ();
	$CI->load->model ( 'musergroups' );
	$row = $CI->musergroups->get_group ( $id );
	return $row->title;
}

function get_groups () {
	$CI = &get_instance ();
	$CI->load->model ( 'musergroups' );
	return ( $CI->musergroups->get_groups () != FALSE ) ? $CI->musergroups->get_groups () : FALSE;
}

function selfURL () {
	$s = empty ( $_SERVER [ "HTTPS" ] ) ? '' : ( $_SERVER [ "HTTPS" ] == "on" ) ? "s" : "";
	$protocol = strleft ( strtolower ( $_SERVER [ "SERVER_PROTOCOL" ] ), "/" ) . $s;
	$port = ( $_SERVER [ "SERVER_PORT" ] == "80" ) ? "" : ( ":" . $_SERVER [ "SERVER_PORT" ] );
	return $protocol . "://" . $_SERVER [ 'HTTP_HOST' ] . $port . $_SERVER [ 'REQUEST_URI' ];
}

function strleft ( $s1, $s2 ) {
	return substr ( $s1, 0, strpos ( $s1, $s2 ) );
}

function evaluate_response ( $reponse ) {
	$arr = explode ( '|', $reponse );
	
	switch ( $arr [ 0 ] ) {
		case 'error' :
			return '<div class="info_messages error"><div class="inner">' . $arr [ 1 ] . '</div></div>' . "\n";
			break;
		
		case 'ok' :
			return '<div class="info_messages success"><div class="inner">' . $arr [ 1 ] . '</div></div>' . "\n";
			break;
		
		case 'notice' :
			return '<div class="info_messages notice"><div class="inner">' . $arr [ 1 ] . '</div></div>' . "\n";
			break;
		
		default :
			return '<div class="info_messages info"><div class="inner">' . $arr [ 1 ] . '</div></div>' . "\n";
	}
}

function qstr ( $str, $magic_quotes = false ) {
	switch ( gettype ( $str ) ) {
		case 'string' :
			$replaceQuote = "\\'"; /// string to use to replace quotes
			if ( ! $magic_quotes ) {
				
				if ( $replaceQuote [ 0 ] == '\\' ) {
					// only since php 4.0.5
					$str = _str_replace ( array ( 
						
						'\\', 
						"\0" 
					), array ( 
						
						'\\\\', 
						"\\\0" 
					), $str );
					//$s = str_replace("\0","\\\0", str_replace('\\','\\\\',$s));
				}
				return "'" . str_replace ( "'", $replaceQuote, $str ) . "'";
			}
			
			// undo magic quotes for "
			$str = str_replace ( '\\"', '"', $str );
			
			if ( $replaceQuote == "\\'" ) { // ' already quoted, no need to change anything
				return "'$str'";
			}
			else { // change \' to '' for sybase/mssql
				$str = str_replace ( '\\\\', '\\', $str );
				return "'" . str_replace ( "\\'", $treplaceQuote, $str ) . "'";
			}
			break;
		case 'boolean' :
			$str = ( $str === FALSE ) ? 0 : 1;
			return $str;
			break;
		case 'integer' :
			$str = ( $str === NULL ) ? 'NULL' : $str;
			return $str;
			break;
		default :
			$str = ( $str === NULL ) ? 'NULL' : $str;
			return $str;
			break;
	}
}

function escape_arr ( $array ) {
	if ( is_array ( $array ) ) {
		foreach ( $array as $key => $value ) {
			$new_arr [ $key ] = addslashes ( $value );
		}
		return $new_arr;
	}
}

function _str_replace ( $src, $dest, $data ) {
	if ( PHPVER >= 0x4050 )
		return str_replace ( $src, $dest, $data );
	
	$s = reset ( $src );
	$d = reset ( $dest );
	while ( $s !== false ) {
		$data = str_replace ( $s, $d, $data );
		$s = next ( $src );
		$d = next ( $dest );
	}
	return $data;
}

function send_email ( $subject, $to, $body, $replyto = FALSE ) {
	$CI = &get_instance ();
	$CI->load->library ( 'phpmailer' );
	//do we use SMTP?
	if ( USE_SMTP ) {
		$CI->phpmailer->IsSMTP ();
		$CI->phpmailer->SMTPAuth = true;
		$CI->phpmailer->Host = SMTP_HOST;
		$CI->phpmailer->Port = SMTP_PORT;
		$CI->phpmailer->Password = SMTP_PASS;
		$CI->phpmailer->Username = SMTP_USER;
	}
	
	$CI->phpmailer->From = ADMIN_EMAIL;
	$CI->phpmailer->FromName = DOMAIN_NAME;
	$CI->phpmailer->AddAddress ( $to );
	if ( $replyto != FALSE ) {
		$CI->phpmailer->AddReplyTo ( $replyto );
	}
	else {
		$CI->phpmailer->AddReplyTo ( ADMIN_EMAIL, DOMAIN_NAME );
	}
	$CI->phpmailer->Subject = $subject;
	$CI->phpmailer->Body = $body;
	$CI->phpmailer->WordWrap = 100;
	$CI->phpmailer->IsHTML ( MAIL_IS_HTML );
	$CI->phpmailer->AltBody = html2txt ( $body );
	$CI->phpmailer->CharSet = "UTF-8";
	
	if ( ! $CI->phpmailer->Send () ) {
		return FALSE;
	}
	else {
		$CI->phpmailer->ClearAllRecipients ();
		$CI->phpmailer->ClearReplyTos ();
		return TRUE;
	}
}

function get_my_username () {
	$CI = &get_instance ();
	$row = $CI->master->get_member_by_id ( $CI->session->userdata ( AUTH_SESSION_ID ) );
	return ( $row != FALSE ) ? $row->Username : FALSE;
}

function html2txt ( $text ) {
	return strip_tags ( $text );
}

function valid_email ( $str ) {
	return ( ! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str ) ) ? FALSE : TRUE;
}

function valid_ip ( $ip ) {
	return $this->CI->valid_ip ( $ip );
}

function alpha ( $str ) {
	return ( ! preg_match ( "/^([a-z])+$/i", $str ) ) ? FALSE : TRUE;
}

function alpha_numeric ( $str ) {
	return ( ! preg_match ( "/^([a-z0-9])+$/i", $str ) ) ? FALSE : TRUE;
}

function alpha_dash ( $str ) {
	return ( ! preg_match ( "/^([-a-z0-9_-])+$/i", $str ) ) ? FALSE : TRUE;
}

function numeric ( $str ) {
	return ( ! preg_match ( "/^[0-9\.]+$/i", $str ) ) ? FALSE : TRUE;
}

function get_uri_segments ( $uri ) {
	$url = parse_url ( $uri );
	$kt = explode ( $url [ 'host' ] . '/', $uri );
	
	$prepare_segments = explode ( '/', $kt [ 1 ] );
	
	$clean_uri = '';
	
	foreach ( $prepare_segments as $value ) {
		if ( $value != '' ) :
			$clean_uri .= $value . '/';
		
		
		
		
		
		
		
		
		
		
			endif;
	}
	
	return ( ! empty ( $clean_uri ) ) ? substr ( $clean_uri, 0, - 1 ) : FALSE;
}

function prepare_constant ( $input, $bolean = FALSE ) {
	return ( $bolean ) ? ( ( $input == 1 ) ? 'TRUE' : 'FALSE' ) : "'" . addslashes ( $input ) . "'";
}

function get_tooltip ( $index, $what = FALSE ) {
	$tooltips = array ( 
		
		'cat_details' => array ( 
			
			'title' => Lang ( 'cat_details_title' ), 
			'content' => Lang ( 'cat_details' ) 
		), 
		'manage_subcats' => array ( 
			
			'title' => Lang ( 'manage_subcats_title' ), 
			'content' => Lang ( 'manage_subcats' ) 
		), 
		'cat_group_perm' => array ( 
			
			'title' => Lang ( 'cat_group_perm_title' ), 
			'content' => Lang ( 'cat_group_perm' ) 
		), 
		'lock_cat' => array ( 
			
			'title' => Lang ( 'lock_cat_title' ), 
			'content' => Lang ( 'lock_cat' ) 
		), 
		'delete_cat' => array ( 
			
			'title' => Lang ( 'delete_cat_title' ), 
			'content' => Lang ( 'delete_cat' ) 
		), 
		'unlock_cat' => array ( 
			
			'title' => Lang ( 'unlock_cat_title' ), 
			'content' => Lang ( 'unlock_cat' ) 
		) 
	);
	
	if ( isset ( $tooltips [ $index ] [ $what ] ) && $tooltips [ $index ] [ $what ] != '' && TOOLTIPS_ENABLED ) {
		return $tooltips [ $index ] [ $what ];
	}
	return FALSE;
}

function __button ( $text, $color = '', $action_type = '', $text_color = 'FFFFFF', $font = 'AlteHaasGroteskBold', $size = 10, $type = 'submit', $params = array () ) {
	$add_params = '';
	if ( ! empty ( $params ) ) {
		foreach ( $params as $k => $v ) {
			$add_params .= "$k=\"$v\" ";
		}
	}
	
	return '<button class="buttons ' . $color . ' ' . $action_type . '" type="' . $type . '" ' . trim ( $add_params ) . '>
					<span>
						<i></i>
						<p>' . do_xhtml ( $text ) . '<em style="color:#FFF!important">' . do_xhtml ( $text ) . '</em></p>
					</span>
					<b></b>
				</button>';
}

function __img_text ( $text, $text_color = 'FFFFFF', $size = 10, $font = 'AlteHaasGroteskBold' ) {
	return '<img src="' . APPLICATION_URL . 'scripts/buttons.php?text=' . base64_encode ( $text ) . '&amp;size=' . $size . '&amp;font=' . $font . '&amp;color=' . $text_color . '" alt="' . str_replace ( '"', '', $text ) . '" />';
}
//END
