<?php

/**
 * Responsible for site language loading
 * @author manilodisan
 *
 */

class WS_Languages {

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
	private $_aLanguage = array ();

	private $_aLanguages = array ();
	
	private $language;

	/**
	 * Private constructor to enforce a singleton
	 * nature
	 * @return void
	 */
	private function __construct () {
		$this->CI = &get_instance ();
		$this->loadLanguages ();
		$this->setLanguage ();
		$this->loadLanguage ();
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

	protected function setLanguage () {
		if
		(
			FALSE !== $this->CI->session->userdata ( 'selected_language' ) &&
			in_array ( $this->CI->session->userdata ( 'selected_language' ), $this->_aLanguages )
		)
		{
			$this->language = $this->CI->session->userdata ( 'selected_language' );
		}
		else {
			$this->language = LANG_TYPE;
		}

		if ( ! in_array ( $this->language, $this->_aLanguages ) ) {
			throw new WS_Exception ( "Language file __FILE__ missing", array ( 
				'__FILE__' => LANG_TYPE . ".php" 
			) );
		}
	}

	/**
	 * Loads the language array, called only
	 * in the constructor to avoid including the
	 * language file more than once per request
	 * @return array $_aLanguage
	 */
	private function loadLanguage () {
		include_once ROOTPATH . "/language/" . $this->language . ".php";

		$this->_aLanguage = $lang;
		unset ( $lang );
		return $this->_aLanguage;
	}

	/**
	 * Returns the requested language string
	 * @param string $sKey Language key
	 * @return string
	 */
	public function getLang ( $sKey ) {
		if ( isset ( $this->_aLanguage [ $sKey ] ) ) {
			return $this->_aLanguage [ $sKey ];
		}
		return "Language string failed to load: $sKey";
	}

	/**
	 * Loads the found language files from the language dir
	 */
	
	private function loadLanguages () {
		$this->CI->load->library ( 'wb_file_manager' );
		$files = $this->CI->wb_file_manager->list_files ( ROOTPATH . "/language/", TRUE, FALSE, FALSE, TRUE );
		foreach ( $files [ 'files' ] as $file ) {
			if ( $this->CI->wb_file_manager->file_extension ( $file ) == 'php' ) {
				array_push ( $this->_aLanguages, str_replace ( '.php', '', $file ) );
			}
		}
	}
	
	public function getLanguages () {
		return $this->_aLanguages;
	}
	

	
	public function getLanguage () {
		return $this->language;
	}
}
//END