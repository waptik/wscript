<?php
@session_start ();

/*
|---------------------------------------------------------------
| FIRST CHECK
|---------------------------------------------------------------
|
| Try to determine if we need an install otherwise continue
|
*/
	if ( file_exists ( 'settings.php' ) && filesize ( 'settings.php' ) > 100 )
	{
		if ( is_dir ( 'install' ) )
		{
			die ( "Please delete the folder \"install\" from your server before continuing!" );
		}

		require_once ( 'settings.php' );
		require_once ( 'watermark.php' );
	}
	else {
		header ( "Location: install/install.php" );
		die ();//prevent anything else
	}

/*
|---------------------------------------------------------------
| PHP ERROR REPORTING LEVEL
|---------------------------------------------------------------
|
| By default CI runs with error reporting set to ALL.  For security
| reasons you are encouraged to change this when your site goes live.
| For more info visit:  http://www.php.net/error_reporting
|
*/
	error_reporting ( ( RUN_ON_DEVELOPMENT ) ? E_ALL : E_WARNING );
	@ini_set ( "display_errors",RUN_ON_DEVELOPMENT );

/*
|---------------------------------------------------------------
| SYSTEM FOLDER NAME
|---------------------------------------------------------------
|
| This variable must contain the name of your "system" folder.
| Include the path if the folder is not in the same  directory
| as this file.
|
| NO TRAILING SLASH!
|
*/
	$system_folder = "system";

/*
|---------------------------------------------------------------
| APPLICATION FOLDER NAME
|---------------------------------------------------------------
|
| If you want this front controller to use a different "application"
| folder then the default one you can set its name here. The folder 
| can also be renamed or relocated anywhere on your server.
| For more info please see the user guide:
| http://www.codeigniter.com/user_guide/general/managing_apps.html
|
|
| NO TRAILING SLASH!
|
*/
	$application_folder = "application";


/*
|===============================================================
| END OF USER CONFIGURABLE SETTINGS
|===============================================================
*/


/*
|---------------------------------------------------------------
| SET THE SERVER PATH
|---------------------------------------------------------------
|
| Let's attempt to determine the full-server path to the "system"
| folder in order to reduce the possibility of path problems.
| Note: We only attempt this if the user hasn't specified a 
| full server path.
|
*/
	if (strpos($system_folder, '/') === FALSE)
	{
		if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
		{
			$system_folder = realpath(dirname(__FILE__)).'/'.$system_folder;
		}
	}
	else
	{
		// Swap directory separators to Unix style for consistency
		$system_folder = str_replace("\\", "/", $system_folder); 
	}

/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT		- The file extension.  Typically ".php"
| FCPATH	- The full server path to THIS file
| SELF		- The name of THIS file (typically "index.php)
| BASEPATH	- The full server path to the "system" folder
| APPPATH	- The full server path to the "application" folder
|
*/
	define ( 'EXT', '.'.pathinfo ( __FILE__, PATHINFO_EXTENSION ) );
	define ( 'FCPATH', __FILE__ );
	define ( 'SELF', pathinfo( __FILE__, PATHINFO_BASENAME ) );
	define ( 'BASEPATH', $system_folder.'/');
	define ( 'ROOTPATH', realpath ( dirname ( BASEPATH ) ) );
	
	$phpver = (float) PHP_VERSION;
	if ( $phpver >= 5.0 ) {
		define( 'PHPVER',0x5000);
	}
	elseif ( $phpver > 4.299999 ) { # 4.3
		define( 'PHPVER',0x4300);
	}
	elseif ( $phpver > 4.199999 ) { # 4.2
		define( 'PHPVER',0x4200);
	}
	elseif ( strnatcmp ( PHP_VERSION,'4.0.5' ) >=0 ) {
		define( 'PHPVER',0x4050);
	}
	else {
		define( 'PHPVER',0x4000);
	}
	
	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ($application_folder == '')
		{
			$application_folder = 'application';
		}
	
		define('APPPATH', BASEPATH.$application_folder.'/');
	}

/*
|---------------------------------------------------------------
| DEFINE E_STRICT
|---------------------------------------------------------------
|
| Some older versions of PHP don't support the E_STRICT constant
| so we need to explicitly define it otherwise the Exception class 
| will generate errors.
|
*/
	if ( ! defined('E_STRICT'))
	{
		define('E_STRICT', 2048);
	}

	define ( 'SMARTY_TEMPLATE_DIR', ROOTPATH . '/templates/' );
	//	when you chnage this remember to also change in scripts/combine.php
	define ( 'TEMP_DIR', ROOTPATH . '/various/cache/' );
	define ( 'LOGS_DIR', ROOTPATH . '/various/logs/' );
	define ( 'MODULES_DIR', APPPATH . 'modules/' );
	define ( 'SMARTY_COMPILE_DIR', TEMP_DIR );
	define ( 'SMARTY_CACHE_DIR', TEMP_DIR );
	define ( 'DB_CACHE_DIR', TEMP_DIR );
	define ( 'WS_ENCODING', 'UTF-8' );

	if ( extension_loaded ( 'mbstring' ) ) {
		define ( 'MB_LOADED', TRUE );
		@mb_regex_set_options ( 'pz' );
		@mb_internal_encoding ( WS_ENCODING );
		@mb_regex_encoding ( WS_ENCODING );
	}
	else {
		define ( 'MB_LOADED', FALSE );
	}

/*
|---------------------------------------------------------------
| INI COMMANDS
|---------------------------------------------------------------
|
| The script requires a little more memory to run without
| errors so we'll try to make sure we're not running out
| of it.
|
*/

	define ( 'SAFE_MODE', ( ( bool ) @ini_get ( "safe_mode" ) === FALSE ) ? FALSE : TRUE );
        define ( 'MEMORY_LIMIT', '128M' );

	if ( ! SAFE_MODE ) {
		@ini_set ( 'memory_limit', MEMORY_LIMIT );
	}

	define ( "GLOBAL_PREFIX", "WS_" );
	define ( "AUTH_COOKIE", GLOBAL_PREFIX . "authenticate" );
	define ( "AUTH_COOKIE_ID", GLOBAL_PREFIX . "cookie_id" );
	define ( "AUTH_SESSION_ID", GLOBAL_PREFIX . "user_id" );
	define ( "LOGGEDIN", GLOBAL_PREFIX . "logged_in" );
	define ( "BASE_PATH", "" );
	define ( "COOKIE_PATH", "/" );
	define ( "ATEMPT", GLOBAL_PREFIX . "attempt" );

/*
|---------------------------------------------------------------
| LOAD THE FRONT CONTROLLER
|---------------------------------------------------------------
|
| And away we go...
|
*/
	require_once BASEPATH . 'codeigniter/CodeIgniter'.EXT;
//END