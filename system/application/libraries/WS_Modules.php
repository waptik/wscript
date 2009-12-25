<?php

/**
 * Responsible for site language loading
 * @author manilodisan
 *
 */

class WS_Modules {

	private $modules = array ();

	/**
	 * Object instance
	 * @var object $_oInstance
	 */
	private static $_oInstance = FALSE;

	/**
	 * Private constructor to enforce a singleton
	 * nature
	 * @return void
	 */
	private function __construct () {
	}

	/**
	 * Returns the object instance
	 * Also performs a check to load
	 * the class only once per page/request
	 * @return object $_oInstance
	 */
	public static function getInstance () {
		if ( FALSE === self::$_oInstance ) {
			self::$_oInstance = new self ( );
		}
		
		return self::$_oInstance;
	}

	/**
	 * Enables a given module
	 * @param $sModule
	 * @return unknown_type
	 */
	public function enableModule ( $sModule ) {
		if ( ! file_exists ( MODULES_DIR . $sModule . DIRECTORY_SEPARATOR . $sModule . '.php' ) ) {
			throw new WS_Exception ( "Module main configuration file __FILE__ not found. The module can't be enabled", array ( 
				'__FILE__' => $sModule . '.php' 
			) );
		}

		$loaded_modules = get_setting ( 'ws_modules' );

		if ( $loaded_modules !== FALSE ) {
			$loaded_modules = unserialize ( $loaded_modules );
		}

		$loaded_modules [ $sModule ] [ 'loaded' ] = 1;

		return set_setting ( 'ws_modules', serialize ( $loaded_modules ) );
	}

	/**
	 * Disables a given module
	 * @param string $sModule Module identifier
	 * @return void
	 */
	public function disableModule ( $sModule ) {
		$loaded_modules = get_setting ( 'ws_modules' );
		
		if ( $loaded_modules !== FALSE ) {
			$loaded_modules = unserialize ( $loaded_modules );
		}

		$loaded_modules [ $sModule ] [ 'loaded' ] = 0;

		set_setting ( 'ws_modules', serialize ( $loaded_modules ) );
	}

	private function loadModule ( $sModule ) {
		if ( ! file_exists ( MODULES_DIR . $sModule . DIRECTORY_SEPARATOR . $sModule . '.php' ) ) {
			throw new WS_Exception ( "Module main file __FILE__ not found", array ( 
				'__FILE__' => $sModule . '.php' 
			) );
		}
		
		if ( ! include_once ( MODULES_DIR . $sModule . DIRECTORY_SEPARATOR . $sModule . '.php' ) ) {
			throw new WS_Exception ( "Module main file __FILE__ can't be included", array ( 
				'__FILE__' => $sModule . '.php' 
			) );
		}
		
		return TRUE;
	}

	private function loadModuleConfig ( $sModule ) {
		if ( ! file_exists ( MODULES_DIR . $sModule . DIRECTORY_SEPARATOR . $sModule . '.ini' ) ) {
			throw new WS_Exception ( "Module main configuration file __FILE__ not found", array ( 
				'__FILE__' => $sModule . '.ini' 
			) );
		}
		
		return @parse_ini_file ( MODULES_DIR . $sModule . DIRECTORY_SEPARATOR . $sModule . '.ini' );
	}

	public function loadModules () {
		$source_dir = MODULES_DIR;

		$loadedModules = get_setting ( 'ws_modules' );

		if ( $loadedModules !== FALSE ) {
			$loadedModules = unserialize ( $loadedModules );
		}

		if ( $fp = @opendir ( $source_dir ) ) {
			$source_dir = rtrim ( realpath ( $source_dir ), DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
			while ( FALSE !== ( $file = readdir ( $fp ) ) ) {
				//	only level 1, no recursion please, in and out
				if ( @is_dir ( $source_dir . $file ) && strncmp ( $file, '.', 1 ) !== 0 ) {
					$this->modules [ $file ] [ 'loaded' ] = 0;
					//	load it here to make the settings available in the module main file
					$this->modules [ $file ] = array_merge ( $this->loadModuleConfig ( $file ), $this->modules [ $file ] );

					if ( isset ( $loadedModules [ $file ] [ 'loaded' ] ) && ( ( bool ) $loadedModules [ $file ] [ 'loaded' ] ) ) {
						$this->modules [ $file ] [ 'loaded' ] = 1;
						if ( ! $this->loadModule ( $file ) ) {
							$this->disableModule ( $file );
							unset ( $this->modules [ $file ] );
							$this->modules [ $file ] [ 'loaded' ] = 0;
						}	
					}
				}
			}
		}

		return $this->modules;
	}
	
	public function getModulesData () {
		return $this->modules;
	}
	
	public function getModuleData ( $sModule ) {
		if ( isset ( $this->modules [ $sModule ] ) ) {
			return $this->modules [ $sModule ];
		}
		return FALSE;
	}
}
//END