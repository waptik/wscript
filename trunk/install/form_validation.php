<?php

class Form_validation {
	
	var $_fields = array ();
	var $_errors = array ();
	var $success_page;
	var $default_error_class = 'error';
	
	// --------------------------------------------------------------------

	/**
	 * add_field
	 * Inserts a new field into the validation queue
	 *
	 * @access	private
	 * @param	$fieldName - the name of the field we're validating	
	 * @param	$rule = validation rule (needs to be a native function or one that is present in this class)	
	 * @param	$error = the error that we output on fail
	 * @return	array
	 *
	 */
	
	function add_field ( $fieldName, $rule, $error )
	{
		$field = array();
		$field [ 'name' ] = $fieldName;
		$field [ 'rule' ] = $rule;
		$field [ 'error' ] = $error;
		
		$this->_fields [ $fieldName ] [] = $field;
	}
	
	// --------------------------------------------------------------------

	/**
	 * getField_value
	 * If the validation passed, this function returns the value that
	 * was posted back into the input. We don't wanna anoy our visitors
	 * by forcing them to start over with the form each time they fail
	 * the validation
	 *
	 * @access	public
	 * @param	$field - the name of the field we're validating
	 * @return	string
	 *
	 */
	
	function getField_value ( $field, $default = FALSE )
	{
		if ( array_key_exists ( $field, $_POST ) ) {
			return $_POST [ $field ];
		}
		else {
                        if ( $default != FALSE )
                        {
                                return $default;
                        }
			return '';
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * execute
	 * Triggers the validation
	 *
	 * @access	private
	 * @param	array/$_POST
	 * @return	string/bol
	 *
	 */
	
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
	
	// --------------------------------------------------------------------

	/**
	 * validate
	 * Does the hard job of validating each input following it's rules
	 *
	 * @access	private
	 * @param	array
	 * @return	bol
	 * 
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	
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
		
		return ( empty ( $this->_errors ) ) ? TRUE : FALSE;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set Checkbox
	 *
	 * Enables checkboxes to be set to the value the user
	 * selected in the event of an error
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	string
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	function set_checkbox ( $field = '', $value = '' )
	{
		if ( $field == '' OR $value == '' OR  ! isset ( $_POST [ $field ] ) )
		{
			return '';
		}

		if ( $_POST [ $field ] == $value )
		{
			return ' checked="checked"';
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * printField_error
	 * Prints out the nice little red error messages to guide our visitor
	 *
	 * @access	public
	 * @param	array
	 * @return	string/bol
	 *
	 */
	
	function printField_error ( $field, $class = FALSE )
	{
		if ( count ( $this->_errors ) == 0 ) {
			return FALSE;
		}
		else {		
			if ( is_array ( @$this->_errors [ $field ] ) ) {
				$out = '';
				foreach ( $this->_errors [ $field ] as $error ) {
					$error_class = ( $class ) ? $class : $this->default_error_class;
					$out .= '<p class="' . $error_class . '">' . $error . '</p>' . "\n";
				}
			}
			return ( isset ( $out ) ) ? $out : FALSE;
		}
	}
}
?>