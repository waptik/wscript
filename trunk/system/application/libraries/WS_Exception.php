<?php

class WS_Exception extends Exception {

	private $aArrayVars;

	public function __construct ( $sMessage, $aArrayVars = array () ) {
		$this->aArrayVars = $aArrayVars;
		$sMessage = str_replace ( array_keys ( $this->aArrayVars ), array_values ( $this->aArrayVars ), $sMessage );
		log_message ( 'error', $sMessage );
		parent::__construct ( $sMessage, 1 );
	}

	public function getError () {
		return '<div class="ui-state-error ui-corner-all" style="padding: 10px;">
				<p>
					<span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>
					<b>Alert:</b>' . $this->message . '
				</p>
			</div>';
	}

}