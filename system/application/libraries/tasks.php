<?php
require_once APPPATH . "libraries/WS_Exception.php";
require_once APPPATH . "libraries/WS_Modules.php";

class Tasks {

	protected static $settings;

	private static $CI;

	public function __construct () {
		$this->CI = &get_instance ();
		$this->loadSmarty ();
		$this->loadModules ();

		define ( 'WS_VERSION', get_setting ( 'WS_VERSION' ) );
	}

	private function loadModules () {
		try {
			$modules = WS_Modules::getInstance ();
			$modules->loadModules ();
		}
		catch ( WS_Exception $e ) {
			//
		}
	}

	private function loadSmarty () {
		$this->CI->load->library ( 'smarty' );
		$this->CI->smarty->template_dir = SMARTY_TEMPLATE_DIR;
		$this->CI->smarty->compile_dir = SMARTY_COMPILE_DIR;
		$this->CI->smarty->cache_dir = SMARTY_CACHE_DIR;
		$this->CI->smarty->error_reporting = ( RUN_ON_DEVELOPMENT ) ? E_ALL : E_WARNING;
		$this->CI->smarty->compile_check = ( RUN_ON_DEVELOPMENT ) ? TRUE : FALSE;
	}
}

//END