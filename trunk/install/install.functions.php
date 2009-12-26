<?php
$phpver = ( float ) PHP_VERSION;
if ( $phpver >= 5.0 ) {
	define ( 'PHPVER', 0x5000 );
}
elseif ( $phpver > 4.299999 ) { # 4.3
	define ( 'PHPVER', 0x4300 );
}
elseif ( $phpver > 4.199999 ) { # 4.2
	define ( 'PHPVER', 0x4200 );
}
elseif ( strnatcmp ( PHP_VERSION, '4.0.5' ) >= 0 ) {
	define ( 'PHPVER', 0x4050 );
}
else {
	define ( 'PHPVER', 0x4000 );
}

function site_url () {
}

function qstr ( $str, $magic_quotes = false ) {
	switch ( gettype ( $str ) ) {
		case 'string' :
			$replaceQuote = "\\'"; /// string to use to replace quotes
			if ( ! $magic_quotes ) {
				
				if ( $replaceQuote [ 0 ] == '\\' ) {
					// only since php 4.0.5
					$str = _str_replace ( array ( 
						'\\', "\0" 
					), array ( 
						'\\\\', "\\\0" 
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

function prepare_constant ( $input, $bolean = FALSE ) {
	return ( $bolean ) ? ( ( $input == 1 ) ? 'TRUE' : 'FALSE' ) : "'" . addslashes ( $input ) . "'";
}

function add_ending_slash ( $string ) {
	return ( preg_match ( '/\/$/', $string ) ) ? $string : $string . '/';
}

function Lang ( $key ) {
	$lang = LoadLanguage ( 'en' );
	if ( isset ( $lang [ $key ] ) ) {
		return $lang [ $key ];
	}
	else {
		return "Language string failed to load: " . $key;
	}
}

function LoadLanguage ( $lang_type ) {
	include ( "../language/english.php" );
	return $lang;
}

function load_permission_files () {
	include ( '../system/application/config/file_permissions.php' );
	return $permArray;
}

function Verify_files_before_install () {
	$permError = '';
	$serverError = '';
	$isOK = true;
	$permArray = load_permission_files ();
	
	$linux_message = 'Please CHMOD it to 777.';
	$windows_message = 'Please set anonymous write permissions in IIS. If you don\'t have access to do this, you will need to contact your hosting provider.';
	
	$error_message = $linux_message;
	if ( strtolower ( substr ( PHP_OS, 0, 3 ) ) == 'win' ) {
		$error_message = $windows_message;
	}
	
	foreach ( $permArray as $a ) {
		if ( ! _is_really_writable ( '../' . $a ) ) {
			$permError .= sprintf ( "<div class=\"error_messages\"><p>The file or folder <b>%s</b> isn't writable. " . $error_message . "</p></div>", $a );
			$isOK = false;
		}
	}
	
	if ( ! $isOK ) {
		return $permError;
	}
	return TRUE;
}

function add_start_end_slashes ( $input ) {
	if ( $input != '' ) {
		if ( substr ( $input, - 1 ) != '/' ) {
			$input = $input . '/';
		}
		
		if ( substr ( $input, 0, 1 ) != '/' ) {
			$input = '/' . $input;
		}
		return $input;
	}
}

function print_unique_id () {
	$prefix = 'W';
	$my_random_id = $prefix;
	$my_random_id .= chr ( rand ( 65, 90 ) );
	$my_random_id .= time ();
	$my_random_id .= uniqid ( $prefix );
	return $my_random_id;
}

function get_domain_of_url ( $url ) {
	$parse = @parse_url ( $url );
	return $parse [ 'host' ];
}

function _is_really_writable ( $file ) {
	if ( is_dir ( $file ) ) {
		$file = rtrim ( $file, '/' ) . '/' . md5 ( rand ( 1, 100 ) );
		
		if ( ( $fp = @fopen ( $file, 'ab' ) ) === FALSE ) {
			return FALSE;
		}
		
		fclose ( $fp );
		@chmod ( $file, 0777 );
		@unlink ( $file );
		return TRUE;
	}
	elseif ( ( $fp = @fopen ( $file, 'ab' ) ) === FALSE ) {
		return FALSE;
	}
	
	fclose ( $fp );
	return TRUE;
}

function application_url ( $path = null ) {
	$s = empty ( $_SERVER [ 'HTTPS' ] ) ? '' : ( $_SERVER [ 'HTTPS' ] == 'on' ) ? 's' : '';
	$protocol = application_url_strleft ( strtolower ( $_SERVER [ 'SERVER_PROTOCOL' ] ), '/' ) . $s;
	$port = ( $_SERVER [ 'SERVER_PORT' ] == '80' ) ? '' : ( ':' . $_SERVER [ 'SERVER_PORT' ] );
	return $protocol . '://' . $_SERVER [ 'SERVER_NAME' ] . $port . dirname ( $_SERVER [ 'SCRIPT_NAME' ] ) . '/' . $path;
}

function application_url_strleft ( $s1, $s2 ) {
	return substr ( $s1, 0, strpos ( $s1, $s2 ) );
}

function get_application_index_page () {
	$index = 'index.php';
	$url = application_url ( 'application_index.php/pass' );
	
	if ( ! preg_match ( '/\.[a-zA-Z]*$/', $_SERVER [ 'HTTP_HOST' ] ) ) {
		$url = str_replace ( '://' . $_SERVER [ 'HTTP_HOST' ] . '/', '://' . @gethostbyname ( $_SERVER [ 'HTTP_HOST' ] ) . '/', $url );
	}
	
	$status = http_get ( $url );
	if ( $status != 'true' ) {
		return $index . '?';
	}
	else {
		return $index;
	}
}

function get_domain_name () {
	$kt = parse_url ( application_url () );
	return $kt [ 'host' ];
}

function http_get ( $url, $_get = array () ) {
	$_query = NULL;
	$contents = NULL;
	
	if ( ! empty ( $_get ) ) {
		$_query = '?';
		
		foreach ( $_get as $key => $value ) {
			$_query .= $key . '=' . $value . '&';
		}
		
		$_query = rtrim ( $_query, '&' );
	}
	
	if ( ini_get ( 'allow_url_fopen' ) == "1" ) {
		
		$handle = @fopen ( $url . $_query, "r" );
		if ( $handle === FALSE ) {
			return $handle;
		}
		
		while ( ! feof ( $handle ) ) {
			$contents .= fread ( $handle, 8192 );
		}
		fclose ( $handle );
	}
	else {
		include_once ( 'HttpClient.php' );
		$contents = HttpClient::quickGet ( $url . $_query );
	}
	
	return trim ( $contents );
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
							<p>' . $text . '<em style="color:#' . $text_color . '!important">' . $text . '</em></p>
						</span>
						<b></b>
					</button>';
}

function __img_text ( $text, $text_color = 'FFFFFF', $font = 'AlteHaasGroteskBold', $size = 10 ) {
	return '<img src="' . @preg_replace ( '@install/@', '', application_url () ) . 'scripts/buttons.php?text=' . base64_encode ( $text ) . '&size=' . $size . '&font=' . $font . '&color=' . $text_color . '" />';
}

function get_application_folder () {
	$http_host = $_SERVER [ 'HTTP_HOST' ];
	$url = application_url ();
	
	if ( $http_host == '' ) {
		$http_host = $_SERVER [ 'SERVER_NAME' ];
	}
	
	$kt = explode ( $http_host, $url );
	
	if ( isset ( $kt [ 1 ] ) ) {
		if ( str_replace ( 'install/', '', $kt [ 1 ] ) != '' ) {
			return add_start_end_slashes ( str_replace ( 'install/', '', $kt [ 1 ] ) );
		}
	}
	return '';
}

function ws_read_file ( $file ) {
	if ( ! file_exists ( $file ) ) {
		return FALSE;
	}
	
	if ( function_exists ( 'file_get_contents' ) ) {
		return file_get_contents ( $file );
	}
	
	if ( ! $fp = @fopen ( $file, FOPEN_READ ) ) {
		return FALSE;
	}
	
	flock ( $fp, LOCK_SH );
	
	$data = '';
	if ( filesize ( $file ) > 0 ) {
		$data = & fread ( $fp, filesize ( $file ) );
	}
	
	flock ( $fp, LOCK_UN );
	fclose ( $fp );
	
	return $data;
}

function create_config_file () {
	global $form_validation;
	if ( array_key_exists ( 'HOSTNAME', $_POST ) ) {
		extract ( $_POST );

		$form_validation->add_field ( 'HOSTNAME', 'required', Lang ( 'required' ) );
		$form_validation->add_field ( 'DATABASE', 'required', Lang ( 'required' ) );
		$form_validation->add_field ( 'DBUSER', 'required', Lang ( 'required' ) );

		$form_validation->add_field ( 'ADMIN_EMAIL', 'valid_email', Lang ( 'valid_email' ) );
		$form_validation->add_field ( 'SITE_NAME', 'required', Lang ( 'required' ) );
		$form_validation->add_field ( 'SITE_SLOGAN', 'required', Lang ( 'required' ) );

		if ( $form_validation->execute () ) {
			@$db_link = @mysql_connect ( $HOSTNAME, $DBUSER, $DBPASS );

			if ( $db_link ) {
				@mysql_select_db ( $DATABASE );
			}
			else {
				return sprintf ( "Invalid database parameters. Details (if any):<br />%s", @mysql_error () );
			}

			$schema = ws_read_file ( "schema.txt" );
			$schema = str_replace ( '{|DBPREFIX|}', $DBPREFIX, $schema );
			$schema = str_replace ( '{|ADMIN_USERNAME|}', $DEFAULT_USERNAME, $schema );
			$schema = str_replace ( '{|ADMIN_PASSWORD|}', md5 ( $DEFAULT_PASSWORD ), $schema );
			$schema = str_replace ( '{|NOW|}', time (), $schema );
			$schema = str_replace ( '{|ADMIN_EMAIL|}', $ADMIN_EMAIL, $schema );
			
			$schema_kt = preg_split ( '/;[\n\r]+/', $schema );
			
			foreach ( $schema_kt as $schema_query ) {
				if ( ! @mysql_query ( $schema_query ) ) {
					return sprintf ( "I failed to setup your database. Please make sure you provided the right mysql credentials!<br /><br />Details (if any):<br />%s", @mysql_error () );
				}
			}
			
			if ( $db_link ) {
				@mysql_close ( $db_link );
			}
			
			$settings = array ();
			$settings [ 'HOSTNAME' ] = prepare_constant ( $_POST [ 'HOSTNAME' ] );
			$settings [ 'DATABASE' ] = prepare_constant ( $_POST [ 'DATABASE' ] );
			$settings [ 'DBUSER' ] = prepare_constant ( $_POST [ 'DBUSER' ] );
			$settings [ 'DBPASS' ] = prepare_constant ( $_POST [ 'DBPASS' ] );
			$settings [ 'DBPREFIX' ] = prepare_constant ( $_POST [ 'DBPREFIX' ] );
			$settings [ 'SECURITY_KEY' ] = prepare_constant ( md5 ( print_unique_id () ) );
			$settings [ 'APPLICATION_URL' ] = prepare_constant ( add_ending_slash ( str_replace ( 'install/', '', application_url () ) ) );
			$settings [ 'APPLICATION_INDEX_PAGE' ] = prepare_constant ( get_application_index_page () );
			$settings [ 'ADMIN_EMAIL' ] = prepare_constant ( $_POST [ 'ADMIN_EMAIL' ] );
			$settings [ 'DOMAIN_NAME' ] = prepare_constant ( get_domain_name () );
			$settings [ 'SITE_NAME' ] = prepare_constant ( $_POST [ 'SITE_NAME' ] );
			$settings [ 'SITE_SLOGAN' ] = prepare_constant ( $_POST [ 'SITE_SLOGAN' ] );
			$settings [ 'USE_SMTP' ] = prepare_constant ( "0", TRUE );
			
			$settings [ 'SMTP_PORT' ] = prepare_constant ( "" );
			$settings [ 'SMTP_HOST' ] = prepare_constant ( "" );
			$settings [ 'SMTP_USER' ] = prepare_constant ( "" );
			$settings [ 'SMTP_PASS' ] = prepare_constant ( "" );
			
			$settings [ 'MAIL_IS_HTML' ] = prepare_constant ( "1", TRUE );
			$settings [ 'APPLICATION_FOLDER' ] = prepare_constant ( get_application_folder () );
			$settings [ 'REDIRECT_TO_LOGIN' ] = prepare_constant ( "login" );
			$settings [ 'REDIRECT_AFTER_LOGIN' ] = prepare_constant ( "members" );
			$settings [ 'REDIRECT_ON_LOGOUT' ] = prepare_constant ( "login" );
			$settings [ 'RUN_ON_DEVELOPMENT' ] = prepare_constant ( "0", TRUE );
			$settings [ 'TOOLTIPS_ENABLED' ] = prepare_constant ( "1", TRUE );
			$settings [ 'REDIRECT_AFTER_CONFIRMATION' ] = prepare_constant ( "1", TRUE );
			$settings [ 'ALLOW_USERNAME_CHANGE' ] = prepare_constant ( "0", TRUE );
			$settings [ 'DEFAULT_EMAIL_TEMPLATE' ] = prepare_constant ( "default" );
			$settings [ 'KEEP_LOGGED_IN_FOR' ] = 60 * 60 * 24 * 100;
			$settings [ 'ALLOW_REMEMBER_ME' ] = prepare_constant ( "1", TRUE );
			$settings [ 'LANG_TYPE' ] = prepare_constant ( "english" );
			$settings [ 'ENABLE_MOD_REWRITE' ] = prepare_constant ( "1", TRUE );
			$settings [ 'DEFAULT_TEMPLATE' ] = prepare_constant ( "default" );
			$settings [ 'MIN_USR_VOTES_HOMEPAGE' ] = 5;
			$settings [ 'MIN_WALL_VOTES_HOMEPAGE' ] = 3;
			$settings [ 'WALLPAPER_DISPLAY_ORDER' ] = prepare_constant ( "date_added" );
			$settings [ 'WALLPAPER_ORDER_TYPE' ] = prepare_constant ( "DESC" );
			$settings [ 'MAX_TAGS' ] = prepare_constant ( 20 );
			$settings [ 'TAGS_ORDER_BY' ] = prepare_constant ( "" );
			$settings [ 'TAGS_ORDER_BY_METHOD' ] = prepare_constant ( "" );
			$settings [ 'TAGS_MIN_CHARACTERS' ] = prepare_constant ( 3 );
			$settings [ 'CATEGORY_COLUMNS' ] = prepare_constant ( 3 );
			$settings [ 'SHOW_CATEGORY_COUNTERS' ] = prepare_constant ( "0", TRUE );
			$settings [ 'TRACKING_CODE' ] = prepare_constant ( "" );
			$settings [ 'AD_CODE' ] = prepare_constant ( "" );
			$settings [ 'TOP_DOWNLOAD_AD_CODE' ] = prepare_constant ( "" );
			$settings [ 'WALLPAPER_AD_CODE' ] = prepare_constant ( "" );
			$settings [ 'WALLPAPER_IPHONE_AD_CODE' ] = prepare_constant ( "" );
			$settings [ 'WALLPAPER_DOWNLOAD_AD_CODE' ] = prepare_constant ( "" );
			$settings [ 'ENABLE_MOD_REWRITE' ] = prepare_constant ( "0", TRUE );
			$settings [ 'WALLPAPER_QUALITY' ] = prepare_constant ( 100 );
			$settings [ 'WALLPAPERS_PER_COLUMN' ] = prepare_constant ( 6 );
			$settings [ 'SITE_HAS_ADULT_MATERIALS' ] = prepare_constant ( "0", TRUE );
			$settings [ 'GUESTS_CAN_DOWNLOAD' ] = prepare_constant ( "1", TRUE );
			$settings [ 'GUESTS_CAN_UPLOAD' ] = prepare_constant ( "1", TRUE );
			$settings [ 'AUTO_APROVE_COMMENTS' ] = prepare_constant ( "0", TRUE );
			$settings [ 'OPEN_WALLPAPERS_IN_NEW_WINDOW' ] = prepare_constant ( "1", TRUE );
			$settings [ 'MAX_COLORS' ] = prepare_constant ( 162 );

			$files_settings = new WS_Settings_writer ( );
			$files_settings->Set ( 'Settings', $settings );
			$files_settings->ConfigFile = '../settings.php';

			if ( $files_settings->Save () ) {
				header ( "Refresh:0;url=../index.php" );
			}
			else {
				return 'Settings not saved. Please check your fields for errors and also that you applied the right permissions to all required files and folders.';
			}
		}
	}
}
//END