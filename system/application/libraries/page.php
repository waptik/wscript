<?php
class Page
{
	var $page;

	// ------------------------------------------------------------------------
	
	/**
	 * Page
	 *
	 * used mostly for emailing purposes
	 * replaces keywords with their defined values
	 *
	 * @param	array
	 * @access	public
	 * @return 	string
	 */
	
	function process ( $params )
	{
		if ( ! is_array ( $params ) ) {
			die ( "Invalid parameters given" );
		}
		else {
			extract ( $params );
			if ( file_exists ( $template ) )
			{
				$this->page = join ( "", file ( $template ) );
			}
			else {
				die ( "Template file $template not found." );
			}
		}
	}
	
	/**
	 * parse
	 *
	 * parses the template
	 *
	 * @param	string
	 * @access	public
	 * @return 	string
	 */

	function parse ( $file )
	{
		ob_start ();
		include ( $file );
		$buffer = ob_get_contents ();
		ob_end_clean ();
		return $buffer;
	}
	
	/**
	 * replace_tags
	 *
	 * responsible for replacing the keywords with their respective values
	 *
	 * @param	string
	 * @access	public
	 * @return 	string
	 */

	function replace_tags ( $tags = array () )
	{
		if ( sizeof ( $tags ) > 0 )
		{
			foreach ( $tags as $tag => $data )
			{
				$this->page = preg_replace ( "/{" . $tag . "}/", $data, $this->page );
			}
		}
		else {
			die ( "No tags designated for replacement." );
		}
	}
	
	/**
	 * output
	 *
	 * simply outputs the generated file
	 *
	 * @param	string
	 * @access	public
	 * @return 	string
	 */

	function output ()
	{
		return $this->page;
	}
}
//END