<?php

/**
 * Responsible for site language loading
 * @author manilodisan
 *
 */

class WS_Media {

	/**
	 * Object instance
	 * @var object $_oInstance
	 */
	private static $_oInstance = FALSE;
	private $_aJavascript = array ();
	private $_aStylesheet = array ();
	private $_sCacheDir;
	public $bUseCache = TRUE;

	/**
	 * Private constructor to enforce a singleton
	 * nature
	 * @return void
	 */
	private function __construct () {
		$this->_sCacheDir = ROOTPATH . '/various/cache/';
		$this->loadDefaultStyleshhet ();
		$this->loadDefaultJs ();
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

	public function registerJs ( $sJsPath ) {
		if ( file_exists ( $sJsPath ) ) {
			array_push ( $this->_aJavascript, $sJsPath );
		}
	}

	public function registerCss ( $sCssPath ) {
		if ( file_exists ( $sCssPath ) ) {
			array_push ( $this->_aStylesheet, $sCssPath );
		}
	}

	private function loadDefaultJs () {
		$aDefaultJs = array
		(
			'jquery132.js',
			'jquery-ui-171.js',
			'menu/superfish.js',
			'menu/hoverintent.js',
			'menu/supersubs.js',
			'jquery/tooltip.js',
			'jquery/cookie.js'
		);
		
		foreach ( $aDefaultJs as $sDefaultJs ) {
			$this->registerJs ( ROOTPATH . '/templates/' . DEFAULT_TEMPLATE . '/js/' . $sDefaultJs );
		}
	}

	private function loadDefaultStyleshhet () {
		$aDefaultStylesheet = array
		(
			'reset.css',
			'default.css',
			'menus.css',
			'forms.css',
			'tables.css',
			'jquery/ui.css',
			'buttons.css'
		);
		
		foreach ( $aDefaultStylesheet as $sDefaultStylesheet ) {
			$this->registerCss ( ROOTPATH . '/templates/' . DEFAULT_TEMPLATE . '/css/' . $sDefaultStylesheet );
		}
	}
	
	public function getOutput ( $sOutputType = 'javascript' ) {
		require_once ROOTPATH . '/scripts/jsmin.php';
		switch ( $sOutputType ) {
			case 'css'		:
				$elements = $this->_aStylesheet;
				break;
			case 'javascript'	:
				$elements = $this->_aJavascript;
				break;
			default			:
				die ();
				break;
		}

		$lastmodified = 0;
		while ( list ( , $element ) = each ( $elements ) ) {
			if ( ( $sOutputType == 'javascript' && substr ( $element, - 3 ) != '.js' ) || ( $sOutputType == 'css' && substr ( $element, - 4 ) != '.css' ) ) {
				continue;
			}

			$lastmodified = max ( $lastmodified, filemtime ( $element ) );
		}
		
		// Send Etag hash
		$hash = $lastmodified . '-' . md5 ( implode ( ',', $elements ) );
		header ( "Etag: \"" . $hash . "\"" );
		
		if ( isset ( $_SERVER [ 'HTTP_IF_NONE_MATCH' ] ) && stripslashes ( $_SERVER [ 'HTTP_IF_NONE_MATCH' ] ) == '"' . $hash . '"' ) {
			// Return visit and no modifications, so do not send anything
			header ( "HTTP/1.0 304 Not Modified" );
			header ( 'Content-Length: 0' );
		}
		else {
			// First time visit or files were modified
			if ( $this->bUseCache ) {
				// Determine supported compression method
				$gzip = strstr ( $_SERVER ['HTTP_ACCEPT_ENCODING'], 'gzip' );
				$deflate = strstr ( $_SERVER ['HTTP_ACCEPT_ENCODING'], 'deflate' );

				// Determine used compression method
				$encoding = $gzip ? 'gzip' : ( $deflate ? 'deflate' : 'none' );

				// Check for buggy versions of Internet Explorer
				if ( ! strstr ( $_SERVER ['HTTP_USER_AGENT'], 'Opera' ) && preg_match ( '/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER ['HTTP_USER_AGENT'], $matches )) {
					$version = floatval ( $matches [ 1 ] );
					
					if ( $version < 6 ) {
						$encoding = 'none';
					}
					
					if ( $version == 6 && ! strstr ( $_SERVER [ 'HTTP_USER_AGENT' ], 'EV1' ) ) {
						$encoding = 'none';
					}
				}

				// Try the cache first to see if the combined files were already generated
				$cachefile = 'cache-' . $hash . '.' . $sOutputType . ( $encoding != 'none' ? '.' . $encoding : '' );

				if ( file_exists ( $this->_sCacheDir . '/' . $cachefile ) ) {
					if ( $fp = fopen ( $this->_sCacheDir . $cachefile, 'rb' ) ) {
						if ( $encoding != 'none' ) {
							header ( "Content-Encoding: " . $encoding );
						}

						header ( "Content-Type: text/" . $sOutputType );
						header ( "Content-Length: " . filesize ( $this->_sCacheDir . $cachefile ) );

						fpassthru ( $fp );
						fclose ( $fp );
						exit ();
					}
				}
			}

			// Get contents of the files
			$contents = '';
			reset ( $elements );
			while ( list ( , $element ) = each ( $elements ) ) {
				if ( $sOutputType == 'javascript' ) {
					$contents .= JSMin::minify ( file_get_contents ($element ) );
				}
				else {
					$contents .= str_replace ( '{template_path}', base_url () . 'templates/' . DEFAULT_TEMPLATE . '/', file_get_contents (  $element ) );
				}
			}

			// Send Content-Type
			header ( "Content-Type: text/" . $sOutputType );

			if ( isset ( $encoding ) && $encoding != 'none' ) {
				// Send compressed contents
				$contents = gzencode ( $contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE );
				header ( "Content-Encoding: " . $encoding );
				header ( 'Content-Length: ' . strlen ( $contents ) );
			}
			else {
				// Send regular contents
				header ( 'Content-Length: ' . strlen ( $contents ) );
			}
			
			// Store cache
			if ( $this->bUseCache ) {
				if ( $fp = fopen ( $this->_sCacheDir . $cachefile, 'wb' ) ) {
					fwrite ( $fp, $contents );
					fclose ( $fp );
				}
			}

			return $contents;
		}
	}
}
//END