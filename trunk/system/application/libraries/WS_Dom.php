<?php

/**
 * Responsible for manipulating the final output of the page
 * @author manilodisan
 *
 */

class WS_Dom {

	/**
	 * Object instance
	 * @var object $_oInstance
	 */
	private static $_oInstance = FALSE;
	private $_aAddClass = array ();
	private $_aRemoveClass = array ();
	private $_aHTML = array ();
	private $_aAppend = array ();
	private $_aPrepend = array ();
	private $_aInsertBefore = array ();
	private $_aInsertAfter = array ();
	
	private $_oDom;

	/**
	 * Private constructor to enforce a singleton
	 * nature
	 * @return void
	 */
	private function __construct () {
		$this->ci = &get_instance ();
	}

	/**
	 * Returns the object instance
	 * Also performs a check to load
	 * the class only once per page/request
	 * @return object $_oInstance
	 */
	public static function getInstance () {
		if ( FALSE === self::$_oInstance ) {
			self::$_oInstance = new self ();
		}
		
		return self::$_oInstance;
	}
	
	/**
	 * Adds css classes to given HTML elements based on identifier
	 * Usage: $dom->addClass ( 'ul.nav > li', 'newClass', ':last' );
	 *
	 * @param string $sIdentifier HTML identifier (element,class,id)
	 * @param string $sClass Class to add
	 * @param mixed $mFilter Should we select the element based on a filter (optional)?
	 */
	public function addClass ( $sIdentifier, $sClass, $mFilter = FALSE ) {
		array_push ( $this->_aAddClass, array ( 'identifier' => $sIdentifier, 'class' => $sClass, 'filter' => $mFilter ) );
	}

	/**
	 * Removes css classes from given HTML elements based on identifier
	 * Usage: $dom->removeClass ( 'ul.nav > li', 'classToRemove', ':last' );
	 *
	 * @param string $sIdentifier HTML identifier (element,class,id)
	 * @param string $sClass Class to remove
	 * @param mixed $mFilter Should we select the element based on a filter (optional)?
	 */
	public function removeClass ( $sIdentifier, $sClass, $mFilter = FALSE ) {
		array_push ( $this->_aRemoveClass, array ( 'identifier' => $sIdentifier, 'class' => $sClass, 'filter' => $mFilter ) );
	}

	/**
	 * Adds html content to given HTML elements based on identifier
	 * Usage: $dom->html ( 'ul.nav > li', 'My content', ':last' );
	 *
	 * @param string $sIdentifier HTML identifier (element,class,id)
	 * @param string $sHTML Content to add
	 * @param mixed $mFilter Should we select the element based on a filter (optional)?
	 */
	public function html ( $sIdentifier, $sHTML, $mFilter = FALSE ) {
		array_push ( $this->_aHTML, array ( 'identifier' => $sIdentifier, 'html' => $sHTML, 'filter' => $mFilter ) );
	}

	/**
	 * Appends html content to given HTML elements based on identifier
	 * Usage: $dom->append ( 'ul.nav > li', 'My content', ':last' );
	 *
	 * @param string $sIdentifier HTML identifier (element,class,id)
	 * @param string $sHTML Content to append
	 * @param mixed $mFilter Should we select the element based on a filter (optional)?
	 */
	public function append ( $sIdentifier, $sHTML, $mFilter = FALSE ) {
		array_push ( $this->_aAppend, array ( 'identifier' => $sIdentifier, 'html' => $sHTML, 'filter' => $mFilter ) );
	}

	/**
	 * Prepends html content to given HTML elements based on identifier
	 * Usage: $dom->prepend ( 'ul.nav > li', 'My content', ':last' );
	 *
	 * @param string $sIdentifier HTML identifier (element,class,id)
	 * @param string $sHTML Content to prepend
	 * @param mixed $mFilter Should we select the element based on a filter (optional)?
	 */
	public function prepend ( $sIdentifier, $sHTML, $mFilter = FALSE ) {
		array_push ( $this->_aPrepend, array ( 'identifier' => $sIdentifier, 'html' => $sHTML, 'filter' => $mFilter ) );
	}

	/**
	 * Inserts html content before a given HTML elements based on identifier
	 * Usage: $dom->insertBefore ( 'ul.nav > li', 'My content', ':last' );
	 *
	 * @param string $sIdentifier HTML identifier (element,class,id)
	 * @param string $sHTML Content to insert before
	 * @param mixed $mFilter Should we select the element based on a filter (optional)?
	 */
	public function insertBefore ( $sIdentifier, $sHTML, $mFilter = FALSE ) {
		array_push ( $this->_aInsertBefore, array ( 'identifier' => $sIdentifier, 'html' => $sHTML, 'filter' => $mFilter ) );
	}

	/**
	 * Inserts html content after a given HTML elements based on identifier
	 * Usage: $dom->insertAfter ( 'ul.nav > li', 'My content', ':last' );
	 *
	 * @param string $sIdentifier HTML identifier (element,class,id)
	 * @param string $sHTML Content to insert after
	 * @param mixed $mFilter Should we select the element based on a filter (optional)?
	 */
	public function insertAfter ( $sIdentifier, $sHTML, $mFilter = FALSE ) {
		array_push ( $this->_aInsertAfter, array ( 'identifier' => $sIdentifier, 'html' => $sHTML, 'filter' => $mFilter ) );
	}

	/**
	 * Prepares the final output
	 *
	 * @param string $sInput The input html content
	 */
	public function parse ( $sInput ) {
		require_once ROOTPATH . "/scripts/domQuery.php";
		try {
			//	@TODO, check for XHTML support in domQuery
			//	$this->_oDom = phpQuery::newDocumentXHTML ( $sInput, Lang ( 'charset' ) );
			$this->_oDom = phpQuery::newDocumentHTML ( $sInput, Lang ( 'charset' ) );
			$this->_oDom->isXHTML = true;
			$this->addClasses ();
			$this->removeClasses ();
			$this->setHTMLContent ();
			$this->setAppends ();
			$this->setPrepends ();
	
			return $this->_oDom->htmlOuter();
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
	}

	/**
	 * Modifies the output and adds the requested css classes
	 */
	private function addClasses () {
		if ( count ( $this->_aAddClass ) ) {
			foreach ( $this->_aAddClass as $aAaaClass ) {
				if ( $aAaaClass [ 'filter' ] != FALSE ) {
					$this->_oDom [ $aAaaClass [ 'identifier' ] ]->filter ( $aAaaClass [ 'filter' ] )->addClass ( $aAaaClass [ 'class' ] );
				}
				else {
					$this->_oDom [ $aAaaClass [ 'identifier' ] ]->addClass ( $aAaaClass [ 'class' ] );
				}
			}
		}
	}

	/**
	 * Modifies the output and removes the requested css classes
	 */
	private function removeClasses () {
		if ( count ( $this->_aRemoveClass ) ) {
			foreach ( $this->_aRemoveClass as $aRemoveClass ) {
				if ( $aRemoveClass [ 'filter' ] != FALSE ) {
					$this->_oDom [ $aRemoveClass [ 'identifier' ] ]->filter ( $aRemoveClass [ 'filter' ] )->removeClass ( $aRemoveClass [ 'class' ] );
				}
				else {
					$this->_oDom [ $aRemoveClass [ 'identifier' ] ]->removeClass ( $aRemoveClass [ 'class' ] );
				}
			}
		}
	}

	/**
	 * Modifies the output and adds the requested html contents
	 */
	private function setHTMLContent () {
		if ( count ( $this->_aHTML ) ) {
			foreach ( $this->_aHTML as $aHTMLContent ) {
				if ( $aHTMLContent [ 'filter' ] != FALSE ) {
					$this->_oDom [ $aHTMLContent [ 'identifier' ] ]->filter ( $aHTMLContent [ 'filter' ] )->html ( $aHTMLContent [ 'html' ] );
				}
				else {
					$this->_oDom [ $aHTMLContent [ 'identifier' ] ]->html ( $aHTMLContent [ 'html' ] );
				}
			}
		}
	}

	/**
	 * Modifies the output and appends the requested html contents
	 */
	private function setAppends () {
		if ( count ( $this->_aAppend ) ) {
			foreach ( $this->_aAppend as $aAppendContent ) {
				if ( $aAppendContent [ 'filter' ] != FALSE ) {
					$this->_oDom [ $aAppendContent [ 'identifier' ] ]->filter ( $aAppendContent [ 'filter' ] )->append ( $aHTMLContent [ 'html' ] );
				}
				else {
					$this->_oDom [ $aAppendContent [ 'identifier' ] ]->append ( $aAppendContent [ 'html' ] );
				}
			}
		}
	}

	/**
	 * Modifies the output and prepends the requested html contents
	 */
	private function setPrepends () {
		if ( count ( $this->_aPrepend ) ) {
			foreach ( $this->_aPrepend as $aPrependContent ) {
				if ( $aPrependContent [ 'filter' ] != FALSE ) {
					$this->_oDom [ $aPrependContent [ 'identifier' ] ]->filter ( $aPrependContent [ 'filter' ] )->prepend ( $aPrependContent [ 'html' ] );
				}
				else {
					$this->_oDom [ $aPrependContent [ 'identifier' ] ]->prepend ( $aPrependContent [ 'html' ] );
				}
			}
		}
	}

	/**
	 * Modifies the output and inserts the requested html contents
	 */
	private function setInsertBefore () {
		if ( count ( $this->_aInsertBefore ) ) {
			foreach ( $this->_aInsertBefore as $aInsertBeforeContent ) {
				if ( $aInsertBeforeContent [ 'filter' ] != FALSE ) {
					$this->_oDom [ $aInsertBeforeContent [ 'identifier' ] ]->filter ( $aInsertBeforeContent [ 'filter' ] )->insertBefore ( $aInsertBeforeContent [ 'html' ] );
				}
				else {
					$this->_oDom [ $aInsertBeforeContent [ 'identifier' ] ]->insertBefore ( $aInsertBeforeContent [ 'html' ] );
				}
			}
		}
	}

	/**
	 * Modifies the output and inserts the requested html contents
	 */
	private function setInsertAfter () {
		if ( count ( $this->_aInsertAfter ) ) {
			foreach ( $this->_aInsertAfter as $aInsertAfterContent ) {
				if ( $aInsertAfterContent [ 'filter' ] != FALSE ) {
					$this->_oDom [ $aInsertAfterContent [ 'identifier' ] ]->filter ( $aInsertAfterContent [ 'filter' ] )->insertAfter ( $aInsertAfterContent [ 'html' ] );
				}
				else {
					$this->_oDom [ $aInsertAfterContent [ 'identifier' ] ]->insertAfter ( $aInsertAfterContent [ 'html' ] );
				}
			}
		}
	}
}
//END