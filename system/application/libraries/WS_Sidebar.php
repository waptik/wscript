<?php

/**
 * Creates the sidebar by registering sections
 * @author manilodisan
 *
 */

class WS_Sidebar {

	/**
	 * Codeigniter instance
	 * @var obj $CI
	 */
	private $CI;

	/**
	 * Output content
	 * @var string $_sOut
	 */
	private $_sOut;

	/**
	 * Object instance
	 * @var object $_oInstance
	 */
	private static $_oInstance = FALSE;

	/**
	 * Array with registered sections
	 * @var array $_aSections
	 */
	private $_aSections = array ();

	/**
	 * Saved sections
	 * @var unknown_type
	 */
	private $_aSavedSections = array ();

	/**
	 * Private constructor to maintain
	 * the singleton type of this class
	 * @return void
	 */
	private function __construct () {
		$this->CI = &get_instance ();
		$this->loadSavedSections ();
		$this->loadDefaults ();
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
	 * Get the sections as in the database
	 * @return void
	 */
	private function loadSavedSections () {
		$loadedSections = get_setting ( 'ws_sections' );
		
		if ( FALSE != $loadedSections ) {
			$this->_aSavedSections = unserialize ( $loadedSections );
		}
	}

	/**
	 * Registers a content section
	 * @param $name Section name
	 * @param $content Section content
	 * @return void
	 */
	public function registerSection ( $id, $content, $title, $description, $show_condition = TRUE, $order = 1 ) {
		if ( ! isset ( $this->_aSections [ $id ] ) ) {
			if ( isset ( $this->_aSavedSections [ $id ] [ 'show' ] ) && ! ( bool ) $this->_aSavedSections [ $id ] [ 'show' ] ) {
				//	closed by admin
				$show_condition = 0;
			}
			
			$this->_aSections [ $id ] = array ( 
				'content' => $content, 'show' => $show_condition, 'order' => $order, 'title' => $title, 'description' => $description 
			);
		}
	}

	/**
	 * Disables a sidebar section
	 * @param string $sSection Section name
	 * @return void
	 */
	public function disableSection ( $sSection ) {
		if ( isset ( $this->_aSections [ $sSection ] ) ) {
			$this->_aSavedSections [ $sSection ] [ 'show' ] = 0;
			$this->_aSavedSections [ $sSection ] [ 'order' ] = 999;
		}
		
		set_setting ( 'ws_sections', serialize ( $this->_aSavedSections ) );
	}

	/**
	 * Enables a sidebar section
	 * @param string $sSection Section name
	 * @return void
	 */
	public function enableSection ( $sSection ) {
		if ( isset ( $this->_aSections [ $sSection ] ) ) {
			$this->_aSavedSections [ $sSection ] [ 'show' ] = 1;
			$this->_aSavedSections [ $sSection ] [ 'order' ] = $this->_aSections [ $sSection ] [ 'order' ];
		}
		
		set_setting ( 'ws_sections', serialize ( $this->_aSavedSections ) );
	}

	/**
	 * Loads the default sections provided with W-script
	 * @return void
	 */
	private function loadDefaults () {
		include_once APPPATH . "config/sections.php";
		foreach ( $sections as $id => $vars ) {
			$content = '';
			
			if ( isset ( $this->_aSavedSections [ $id ] [ 'show' ] ) && ! ( bool ) $this->_aSavedSections [ $id ] [ 'show' ] ) {
				$vars [ 'show' ] = FALSE;
			}
			
			if ( $vars [ 'show' ] ) {
				//	don't bother loading the content if the config closed this section
				switch ( $id ) {
					case 'section_categories' :
						$content = show_cats ( 0, CATEGORY_COLUMNS, 0, 21, 'fcw', 'fetchFront' );
						break;
					case 'section_tags' :
						$content = build_tag_cloud ();
						break;
					case 'section_partners' :
						$this->CI->load->model ( 'mpartners' );
						$content = load_html_template ( array ( 
							'partners' => $this->CI->mpartners->get_partners () 
						), 'show_partners', FALSE, FALSE, print_unique_id () );
						break;
					case 'section_colors' :
						$content = get_top_colors ();
						break;
					case 'section_user_stats' :
						$content = get_top_rated_members ( 10 ) . get_top_contributors ( 10 ) . '<div class="clear"></div>';
						break;
				}
			}

			$content = load_html_template ( array ( 
				'content' => $content 
			), $id, FALSE, FALSE );
			$this->registerSection ( $id, $content, $vars [ 'title' ], $vars [ 'description' ], $vars [ 'show' ], $vars [ 'order' ] );
		}
	}

	/**
	 * Any registerSection should be used before
	 * calling this function as it's useless the
	 * other way arround
	 * 
	 * @return string $out
	 */
	public function sidebarOutput () {
		$sections = $this->sortSections ();
		
		foreach ( $sections as $id => $section ) {
			if ( $section [ 'show' ] ) {
				$this->_sOut .= '<li id="' . $id . '">' . $section [ 'content' ] . '<input type="hidden" name="' . $id . '" value="1" /></li>';
			}
		}
		
		if ( is_admin () ) {
			$this->_sOut = sprintf ( '<ul id="sortable_sections">%s</ul>', $this->_sOut );
		}
		else {
			$this->_sOut = sprintf ( '<ul>%s</ul>', $this->_sOut );
		}
		
		return $this->_sOut;
	}

	/**
	 * Sorts sections based on their order
	 * @return array
	 */
	private function sortSections () {
		require_once APPPATH . "libraries/wb_array.php";
		$sections = $this->_aSections;
		
		foreach ( $sections as $key => $val ) {
			if ( isset ( $this->_aSavedSections [ $key ] [ 'order' ] ) ) {
				$sections [ $key ] [ 'order' ] = $this->_aSavedSections [ $key ] [ 'order' ];
			}
		}
		
		return WB_array::sort_columns ( $sections, 'order' );
	}

	/**
	 * Returns the registered sections
	 * Any registerSection should be used before
	 * calling this function as it's useless the
	 * other way arround
	 * 
	 * @return array $_aSections
	 */
	public function getSections ( $order = TRUE ) {
		if ( $order ) {
			return $this->sortSections ();
		}
		return $this->_aSections;
	}

}