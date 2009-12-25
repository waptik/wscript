<?php

class Form_validation {

	var $_fields = array ();
	var $_errors = array ();
	var $success_page;
	var $default_error_class = 'error';

	function checkUnique ( $compared, $field )
	{
		$CI = &get_instance ();
		$query = $CI->db->query ( "SELECT ID FROM `" . DBPREFIX . "users` WHERE LOWER(" . $field . ") = LOWER(" . qstr ( $compared ) . ")" );
		return ( $query->num_rows () === 0 ) ? TRUE : FALSE;	
	}

	function partner_not_exists ( $link )
	{
		$CI = &get_instance ();
		$query = $CI->db->query ( 'SELECT ID FROM `' . DBPREFIX . 'partners` WHERE link = ' . qstr ( $link ) );
		return ( $query->num_rows () === 0 ) ? TRUE : FALSE;
	}

	function title_alias ( $input )
	{
		return ( preg_match ( '/[^a-zA-Z0-9\s+]/', $input ) ) ? FALSE : TRUE;
	}

	function add_field ( $fieldName, $rule, $error, $isfile = FALSE )
	{
		$field = array();
		$field [ 'name' ] = $fieldName;
		$field [ 'rule' ] = $rule;
		$field [ 'error' ] = $error;
		$field [ 'isfile' ] = $isfile;
		
		$this->_fields [ $fieldName ] [] = $field;
	}

	function getField_value ( $field )
	{
		if ( array_key_exists ( $field, $_POST ) ) {
			return $_POST [ $field ];
		}
		else {
			return '';
		}
	}

	function execute ()
	{
		if ( ! $this->validate () )
		{
			foreach ( $_POST as $key => $value )
			{
				$this->$key = $value;
			}
		}
		else {
			return TRUE;
		}
	}

	function validate ()
	{
		foreach ( $this->_fields as $field => $set ) 
		{			
			foreach ( $set as $key ) 
			{
				$param = FALSE;
				$rule = $key [ 'rule' ];
				$field = $key [ 'name' ];
				$error = $key [ 'error' ];
				$isfile = $key [ 'isfile' ];

				if ( $isfile )
				{
					$file = $_FILES [ $field ];
					// Is the field required and it was sent blank, don't evaluate the rest of the validations until we have some data
					if ( $rule == 'required' && ( ! isset ( $file ) ) OR $file [ 'size' ] == 0 )
					{
						$this->_errors [ $field ] [] = $error;
						continue 2;
					}
					
					if ( preg_match ( "/(.*?)\[(.*?)\]/", $rule, $match ) )
					{
						$rule	= $match [ 1 ];
						$param	= $match [ 2 ];
					}
					
					if ( ! method_exists ( $this, $rule ) )
					{
						if ( function_exists ( $rule ) )
						{						
							$result = $rule ( $file, $param );
						}
	
						continue;
					}
					else {					
						$result = $this->$rule ( $file, $param );
					}
					
					if ( $result === FALSE )
					{
						$this->_errors [ $field ] [] = $error;
					}
				}
				else {
					// Is the field required and it was sent blank, don't evaluate the rest of the validations until we have some data
					if ( $rule == 'required' && ( ! isset ( $_POST [ $field ] ) OR $_POST [ $field ] == '' ) )
					{
						$this->_errors [ $field ] [] = $error;
						continue 2;
					}
					
					if ( preg_match ( "/(.*?)\[(.*?)\]/", $rule, $match ) )
					{
						$rule	= $match [ 1 ];
						$param	= $match [ 2 ];
					}
					
					if ( ! method_exists ( $this, $rule ) )
					{
						if ( function_exists ( $rule ) )
						{						
							$result = $rule ( $_POST [ $field ], $param );
						}
	
						continue;
					}
					else {					
						$result = $this->$rule ( $_POST [ $field ], $param );
					}
					
					if ( $result === FALSE )
					{
						$this->_errors [ $field ] [] = $error;
					}
				}
			}
		}
		
		return ( empty ( $this->_errors ) ) ? TRUE : FALSE;
	}

	function set_checkbox ( $field = '', $value = '', $callback = '' )
	{
		if ( isset ( $_POST [ $field ] ) && $_POST [ $field ] == $value )
		{
			return ' checked="checked"';
		}
		elseif ( ! isset ( $_POST [ $field ] ) )
		{
			if ( isset ( $callback ) && $callback == $value )
			{
				return ' checked="checked"';
			}
		}
		else {
			return '';
		}
	}
	
	function set_select ( $field = '', $value = '', $callback = '' )
	{
		if ( isset ( $_POST [ $field ] ) && $_POST [ $field ] == $value )
		{
			return ' selected="selected"';
		}
		elseif ( ! isset ( $_POST [ $field ] ) )
		{
			if ( isset ( $callback ) && $callback == $value )
			{
				return ' selected="selected"';
			}
		}
		else {
			return '';
		}
	}
	
	function set_radio ( $field = '', $value = '', $callback = '' )
	{
		if ( isset ( $_POST [ $field ] ) && $_POST [ $field ] == $value )
		{
			return ' checked="checked"';
		}
		elseif ( ! isset ( $_POST [ $field ] ) )
		{
			if ( isset ( $callback ) && $callback == $value )
			{
				return ' checked="checked"';
			}
		}
		else {
			return '';
		}
	}
	
	function valid_url($str)
	{
		return ( ! preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $str)) ? FALSE : TRUE;
	}

	function printField_error ( $field, $class = FALSE )
	{
		if ( count ( $this->_errors ) == 0 ) {
			return FALSE;
		}
		else {		
			if ( isset ( $this->_errors [ $field ] ) AND is_array ( $this->_errors [ $field ] ) ) {
				$out = '';
				foreach ( $this->_errors [ $field ] as $error ) {
					$error_class = ( $class ) ? $class : $this->default_error_class;
					$out .= '<p class="' . $error_class . '">' . $error . '</p>' . "\n";
				}
			}
			return ( isset ( $out ) ) ? $out : FALSE;
		}
	}

	function valid_email ( $str )
	{
		return ( ! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str ) ) ? FALSE : TRUE;
	}

	function check_normal ( $file )
	{
		if ( ! file_exists ( $file [ 'tmp_name' ] ) ) {
			return FALSE;
		}

		$details = @getimagesize ( $file [ 'tmp_name' ] );

		foreach ( get_sizes () as $size )
		{
			foreach ( $size as $height => $width )
			{
				if ( isset ( $details [ 0 ] ) && isset ( $details [ 1 ] ) && $details [ 0 ] == $width && $details [ 1 ] == $height && round ( $width / $height ) == 1 )
				{
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	function check_wide ( $file )
	{
		if ( ! file_exists ( $file [ 'tmp_name' ] ) ) {
			return FALSE;
		}

		$details = @getimagesize ( $file [ 'tmp_name' ] );

		foreach ( get_sizes () as $size )
		{
			foreach ( $size as $height => $width )
			{
				if ( isset ( $details [ 0 ] ) && isset ( $details [ 1 ] ) && $details [ 0 ] == $width && $details [ 1 ] == $height && round ( $width / $height ) == 2 )
				{
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	function check_iphone ( $file )
	{
		if ( ! file_exists ( $file [ 'tmp_name' ] ) ) {
			return FALSE;
		}

		$details = @getimagesize ( $file [ 'tmp_name' ] );

		foreach ( get_sizes () as $size )
		{
			foreach ( $size as $height => $width )
			{
				if ( isset ( $details [ 0 ] ) && isset ( $details [ 1 ] ) && $details [ 0 ] == $width && $details [ 1 ] == $height && round ( $width / $height ) == 1 )
				{
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	function check_unique_hash ( $file )
	{
		if ( ! file_exists ( $file [ 'tmp_name' ] ) ) {
			return FALSE;
		}
		return check_unique_hash ( $file [ 'tmp_name' ] );
	}

	function check_empty_zip ( $zip )
	{
		return ( strlen ( $zip ) > 0 ) ? TRUE : FALSE;
	}

	function numeric ( $str )
	{
		return ( ! preg_match ( "/^[0-9\.]+$/i", $str ) ) ? FALSE : TRUE;
	}

	function alpha_numeric ( $str )
	{
		return ( ! preg_match ( "/^([-a-z0-9])+$/i", $str ) ) ? FALSE : TRUE;
	}

	function exact_length($str, $val)
	{
		if ( ! is_numeric($val))
		{
			return FALSE;
		}

		return (strlen($str) != $val) ? FALSE : TRUE;
	}

	function required($str)
	{
		if ( ! is_array($str))
		{
			return (trim($str) == '') ? FALSE : TRUE;
		}
		else
		{
			return ( ! empty($str));
		}
	}

	function matches($str, $field)
	{
		if ( ! isset($_POST[$field]))
		{
			return FALSE;
		}

		return ($str !== $_POST[$field]) ? FALSE : TRUE;
	}

	function min_length($str, $val)
	{
		if ( ! is_numeric($val))
		{
			return FALSE;
		}

		return (strlen($str) < $val) ? FALSE : TRUE;
	}

	function max_length($str, $val)
	{
		if ( ! is_numeric($val))
		{
			return FALSE;
		}

		return (strlen($str) > $val) ? FALSE : TRUE;
	}
	
	function greater_than($str, $val)
	{
		if ( ! is_numeric($val))
		{
			return FALSE;
		}

		return ($str <= $val) ? FALSE : TRUE;
	}
}
//END