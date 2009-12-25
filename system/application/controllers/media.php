<?php
include_once APPPATH . 'libraries/WS_Media.php';

class Media extends Controller {

	public function __construct () {
		parent::__construct ();
		$this->oMedia = WS_Media::getInstance ();
	}

	public function loadCss () {
		echo $this->oMedia->getOutput ( 'css' );die ();
	}

	public function loadJs () {
		echo $this->oMedia->getOutput ( 'javascript' );die ();
	}

}