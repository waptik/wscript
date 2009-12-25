<?php

/**
 * Site settings manager
 * @author manilodisan
 *
 */

class WS_Settings {

	/**
	 * CodeIgniter instance
	 * @var unknown_type
	 */
	private $CI;

	/**
	 * Object instance
	 * @var object $_oInstance
	 */
	private static $_oInstance = FALSE;

	/**
	 * Array with registered sections
	 * @var array $_aSections
	 */
	private $_aSettings = array ();

	/**
	 * Private constructor to maintain
	 * the singleton type of this class
	 * @return void
	 */
	private function __construct () {
		$this->CI = &get_instance ();
		$this->CI->load->model ( 'master' );
		$this->_aSettings = $this->CI->master->get_settings ();
	}

	/**
	 * Returns the value of the requested setting
	 * @param string $sLabel
	 * @return mixed
	 */
	public function getSetting ( $sLabel ) {
		foreach ( $this->_aSettings as $sett ) {
			if ( $sLabel == $sett [ 'label' ] ) {
				return $sett [ 'value' ];
			}
		}
		return FALSE;
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
	 * Saves a new setting in the database
	 * @param string $sLabel
	 * @param string $sValue
	 * @return bool
	 */
	public function setSetting ( $sLabel, $sValue ) {
		return $this->CI->master->set_setting ( $sLabel, $sValue );
	}

	/**
	 * Returns the site settings
	 * 
	 * @return array $_aSettings
	 */
	public function getSettings () {
		return $this->_aSettings;
	}

}