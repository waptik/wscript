<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['base_url']		= APPLICATION_URL;
$config['index_page'] 		= ( ENABLE_MOD_REWRITE ) ? "" : APPLICATION_INDEX_PAGE;
$config['uri_protocol']		= ( @preg_match ( '/\?/', APPLICATION_INDEX_PAGE ) ) ? "QUERY_STRING" : "AUTO";
$config['url_suffix'] 		= "";
$config['language']		= "english";
$config['charset'] 		= "UTF-8";
$config['enable_hooks'] 	= TRUE;
$config['subclass_prefix'] 	= 'WS_';
$config['permitted_uri_chars'] 	= '';
$config['enable_query_strings'] = FALSE;
$config['directory_trigger'] 	= 'd';
$config['controller_trigger'] 	= 'c';
$config['function_trigger'] 	= 'm';
$config['log_threshold'] 	= RUN_ON_DEVELOPMENT;
$config['log_path'] 		= LOGS_DIR;
$config['log_date_format'] 	= 'Y-m-d H:i:s';
$config['cache_path'] 		= LOGS_DIR;
$config['encryption_key']	= "";
$config['sess_cookie_name']	= 'ci_session';
$config['sess_expiration']	= 7200;
$config['sess_encrypt_cookie']	= FALSE;
$config['sess_use_database']	= TRUE;
$config['sess_table_name']	= DBPREFIX . 'sessions';
$config['sess_match_ip']	= FALSE;
$config['sess_match_useragent']	= FALSE;
$config['sess_time_to_update'] 	= 10;
$config['cookie_prefix']	= "";
$config['cookie_domain']	= "";
$config['cookie_path']		= "/";
$config['global_xss_filtering'] = FALSE;
$config['compress_output'] 	= FALSE;
$config['time_reference'] 	= 'local';
$config['rewrite_short_tags'] 	= FALSE;

//	END