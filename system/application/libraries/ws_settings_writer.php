<?php

class WS_Settings_writer {

	var $Settings = array ();
	var $ConfigFile = 'settings.php';

	function Set ( $varname = '', $value = '' ) {
		if ( $varname == '' ) {
			return false;
		}
		
		$my_vars = array_keys ( get_object_vars ( $this ) );
		if ( ! in_array ( $varname, $my_vars ) ) {
			return false;
		}
		
		$this->$varname = $value;
		return true;
	}

	function Load () {
		if ( ! $fp = fopen ( $this->ConfigFile, 'r' ) ) {
			return false;
		}
		fread ( $fp, filesize ( $this->ConfigFile ) );
		fclose ( $fp );
		return true;
	}

	function Save () {
		if ( ! is_writable ( $this->ConfigFile ) ) {
			return false;
		}
		
		$tmpfname = tempnam ( $_SERVER [ 'DOCUMENT_ROOT' ] . '/temp', 'rS_' );


		if ( ! $handle = fopen ( $tmpfname, 'w' ) ) {
			return false;
		}
		
		$contents = '';
		$contents .= '<?' . 'php' . "\n\n";
		
		foreach ( $this->Settings as $key => $value ) {
			$string = "\t" . 'define ( \'' . $key . '\', ' . $value . ' );' . "\n";
			$contents .= $string;
		}
		
		$contents .= "\n//\tEND SETTINGS";
		
		fputs ( $handle, $contents, strlen ( $contents ) );
		fclose ( $handle );
		chmod ( $tmpfname, 0644 );
		
		if ( ! copy ( $tmpfname, $this->ConfigFile ) ) {
			return false;
		}
		unlink ( $tmpfname );
		
		return true;
	}
}

//END
