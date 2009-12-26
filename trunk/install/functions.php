<?php

class Functions {

	var $language;
	
	// ------------------------------------------------------------------------
	
	/**
	 * Check unique
	 *
	 * Performs a check to determine if one parameter is unique in the database
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
 
 
	function checkUnique ( $compared, $field )
	{
		global $login;
		if ( $login->db->RecordCount ( "SELECT ID FROM `" . DBPREFIX . "users` WHERE " . $field . " = " . $login->db->qstr ( $compared ) ) === 0 ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
		
	}

	// ------------------------------------------------------------------------
	
	/**
	 * send_email - Handles all emailing from one place
	 *
	 * @access	public
	 * @param	string
	 * @return	bool TRUE/FALSE
	 */
	 
	function send_email ( $subject, $to, $body, $from_email = FALSE )
	{
		global $login;
		$login->_load_class ( 'phpmailer' );
		//do we use SMTP?
		if ( USE_SMTP ) {
			$login->phpmailer->IsSMTP();
			$login->phpmailer->SMTPAuth = true;
			$login->phpmailer->Host = SMTP_HOST;
			$login->phpmailer->Port = SMTP_PORT;
			$login->phpmailer->Password = base64_decode ( SMTP_PASS );
			$login->phpmailer->Username = SMTP_USER;
		}

		$login->phpmailer->From = ( $from_email ) ? $from_email : ADMIN_EMAIL;
		$login->phpmailer->FromName = DOMAIN_NAME;
		$login->phpmailer->AddAddress( $to );
		$login->phpmailer->AddReplyTo ( ADMIN_EMAIL, DOMAIN_NAME );
		$login->phpmailer->Subject = $subject;
		$login->phpmailer->Body = $body;
		$login->phpmailer->WordWrap = 100;
		$login->phpmailer->IsHTML ( MAIL_IS_HTML );
		$login->phpmailer->AltBody  =  $this->html2txt ( $body );

		if ( ! $login->phpmailer->Send() ) {
			return FALSE;
		}
		else {
			$login->phpmailer->ClearAllRecipients ();
			$login->phpmailer->ClearReplyTos ();
			return TRUE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * ip_first - let's get a clean ip
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */

	function ip_first ( $ips ) 
	{
		if ( ( $pos = strpos ( $ips, ',' ) ) != false ) {
			return substr ( $ips, 0, $pos );
		} 
		else {
			return $ips;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * ip_valid - will try to determine if a given ip is valid or not
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */

	function ip_valid ( $ips )
	{
		if ( isset( $ips ) ) {
			$ip    = $this->ip_first ( $ips );
			$ipnum = ip2long ( $ip );
			if ( $ipnum !== -1 && $ipnum !== false && ( long2ip ( $ipnum ) === $ip ) ) {
				if ( ( $ipnum < 167772160   || $ipnum > 184549375 ) && // Not in 10.0.0.0/8
				( $ipnum < - 1408237568 || $ipnum > - 1407188993 ) && // Not in 172.16.0.0/12
				( $ipnum < - 1062731776 || $ipnum > - 1062666241 ) )   // Not in 192.168.0.0/16
				return true;
			}
		}
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * getIP - returns the IP of the visitor
	 *
	 * @access	public
	 * @param	none
	 * @return	string
	 */

	function getIP () 
	{
		$check = array(
				'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR',
				'HTTP_FORWARDED', 'HTTP_VIA', 'HTTP_X_COMING_FROM', 'HTTP_COMING_FROM',
				'HTTP_CLIENT_IP'
				);

		foreach ( $check as $c ) {
			if ( $this->ip_valid ( &$_SERVER [ $c ] ) ) {
				return $this->ip_first ( $_SERVER [ $c ] );
			}
		}

		return $_SERVER['REMOTE_ADDR'];
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * html2txt - converts html to text
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	 
	function html2txt ( $document )
	{
		$search = array("'<script[^>]*?>.*?</script>'si",	// strip out javascript
				"'<[\/\!]*?[^<>]*?>'si",		// strip out html tags
				"'([\r\n])[\s]+'",			// strip out white space
				"'@<![\s\S]*?–[ \t\n\r]*>@'",
				"'&(quot|#34|#034|#x22);'i",		// replace html entities
				"'&(amp|#38|#038|#x26);'i",		// added hexadecimal values
				"'&(lt|#60|#060|#x3c);'i",
				"'&(gt|#62|#062|#x3e);'i",
				"'&(nbsp|#160|#xa0);'i",
				"'&(iexcl|#161);'i",
				"'&(cent|#162);'i",
				"'&(pound|#163);'i",
				"'&(copy|#169);'i",
				"'&(reg|#174);'i",
				"'&(deg|#176);'i",
				"'&(#39|#039|#x27);'",
				"'&(euro|#8364);'i",			// europe
				"'&a(uml|UML);'",			// german
				"'&o(uml|UML);'",
				"'&u(uml|UML);'",
				"'&A(uml|UML);'",
				"'&O(uml|UML);'",
				"'&U(uml|UML);'",
				"'&szlig;'i",
				);
		$replace = array(	"",
					"",
					" ",
					"\"",
					"&",
					"<",
					">",
					" ",
					chr(161),
					chr(162),
					chr(163),
					chr(169),
					chr(174),
					chr(176),
					chr(39),
					chr(128),
					"ä",
					"ö",
					"ü",
					"Ä",
					"Ö",
					"Ü",
					"ß",
				);

		$text = preg_replace($search,$replace,$document);

		return trim ( $text );
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * XSS Clean
	 *
	 * Sanitizes data so that Cross Site Scripting Hacks can be
	 * prevented.  This function does a fair amount of work but
	 * it is extremely thorough, designed to prevent even the
	 * most obscure XSS attempts.  Nothing is ever 100% foolproof,
	 * of course, but I haven't been able to get anything passed
	 * the filter.
	 *
	 * Note: This function should only be used to deal with data
	 * upon submission.  It's not something that should
	 * be used for general runtime processing.
	 *
	 * This function was based in part on some code and ideas I
	 * got from Bitflux: http://blog.bitflux.ch/wiki/XSS_Prevention
	 *
	 * To help develop this script I used this great list of
	 * vulnerabilities along with a few other hacks I've
	 * harvested from examining vulnerabilities in other programs:
	 * http://ha.ckers.org/xss.html
	 * Taken from Codeigniter - http://www.codeigniter.com
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	 
	function xss_clean ( $str, $charset = 'ISO-8859-1' )
	{
		/*
		 * Remove Null Characters
		 *
		 * This prevents sandwiching null characters
		 * between ascii characters, like Java\0script.
		 *
		 */
		$str = preg_replace('/\0+/', '', $str);
		$str = preg_replace('/(\\\\0)+/', '', $str);

		/*
		 * Validate standard character entities
		 *
		 * Add a semicolon if missing.  We do this to enable
		 * the conversion of entities to ASCII later.
		 *
		 */
		$str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"\\1;",$str);
		
		/*
		 * Validate UTF16 two byte encoding (x00)
		 *
		 * Just as above, adds a semicolon if missing.
		 *
		 */
		$str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"\\1\\2;",$str);

		/*
		 * URL Decode
		 *
		 * Just in case stuff like this is submitted:
		 *
		 * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
		 *
		 * Note: Normally urldecode() would be easier but it removes plus signs
		 *
		 */	
		$str = preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $str);
		$str = preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $str);		
				
		/*
		 * Convert character entities to ASCII
		 *
		 * This permits our tests below to work reliably.
		 * We only convert entities that are within tags since
		 * these are the ones that will pose security problems.
		 *
		 */
		if ( preg_match_all("/<(.+?)>/si", $str, $matches))
		{		
			for ($i = 0; $i < count($matches['0']); $i++)
			{
				$str = str_replace($matches['1'][$i],
									$this->_html_entity_decode ( $matches['1'][$i], $charset ),
									$str);
			}
		}
		
		/*
		 * Not Allowed Under Any Conditions
		 */	
		$bad = array(
						'document.cookie'	=> '[removed]',
						'document.write'	=> '[removed]',
						'window.location'	=> '[removed]',
						"javascript\s*:"	=> '[removed]',
						"Redirect\s+302"	=> '[removed]',
						'<!--'				=> '&lt;!--',
						'-->'				=> '--&gt;'
					);
	
		foreach ($bad as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);   
		}
	
		/*
		 * Convert all tabs to spaces
		 *
		 * This prevents strings like this: ja	vascript
		 * Note: we deal with spaces between characters later.
		 *
		 */		
		$str = preg_replace("#\t+#", " ", $str);
	
		/*
		 * Makes PHP tags safe
		 *
		 *  Note: XML tags are inadvertently replaced too:
		 *
		 *	<?xml
		 *
		 * But it doesn't seem to pose a problem.
		 *
		 */		
		$str = str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
	
		/*
		 * Compact any exploded words
		 *
		 * This corrects words like:  j a v a s c r i p t
		 * These words are compacted back to their correct state.
		 *
		 */		
		$words = array('javascript', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
		foreach ($words as $word)
		{
			$temp = '';
			for ($i = 0; $i < strlen($word); $i++)
			{
				$temp .= substr($word, $i, 1)."\s*";
			}
			
			$temp = substr($temp, 0, -3);
			$str = preg_replace('#'.$temp.'#s', $word, $str);
			$str = preg_replace('#'.ucfirst($temp).'#s', ucfirst($word), $str);
		}
	
		/*
		 * Remove disallowed Javascript in links or img tags
		 */		
		 $str = preg_replace("#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $str);
		 $str = preg_replace("#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si", "", $str);
		 $str = preg_replace("#<(script|xss).*?\>#si", "", $str);

		/*
		 * Remove JavaScript Event Handlers
		 *
		 * Note: This code is a little blunt.  It removes
		 * the event handler and anything up to the closing >,
		 * but it's unlikely to be a problem.
		 *
		 */		
		 $str = preg_replace('#(<[^>]+.*?)(onblur|onchange|onclick|onfocus|onload|onmouseover|onmouseup|onmousedown|onselect|onsubmit|onunload|onkeypress|onkeydown|onkeyup|onresize)[^>]*>#iU',"\\1>",$str);
	
		/*
		 * Sanitize naughty HTML elements
		 *
		 * If a tag containing any of the words in the list
		 * below is found, the tag gets converted to entities.
		 *
		 * So this: <blink>
		 * Becomes: &lt;blink&gt;
		 *
		 */		
		$str = preg_replace('#<(/*\s*)(alert|applet|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|xml|xss)([^>]*)>#is', "&lt;\\1\\2\\3&gt;", $str);
		
		/*
		 * Sanitize naughty scripting elements
		 *
		 * Similar to above, only instead of looking for
		 * tags it looks for PHP and JavaScript commands
		 * that are disallowed.  Rather than removing the
		 * code, it simply converts the parenthesis to entities
		 * rendering the code un-executable.
		 *
		 * For example:	eval('some code')
		 * Becomes:		eval&#40;'some code'&#41;
		 *
		 */
		$str = preg_replace ( '#(alert|cmd|passthru|eval|exec|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str );
						
		/*
		 * Final clean up
		 *
		 * This adds a bit of extra precaution in case
		 * something got through the above filters
		 *
		 */	
		$bad = array
		(
			'document.cookie'	=> '[removed]',
			'document.write'	=> '[removed]',
			'window.location'	=> '[removed]',
			"javascript\s*:"	=> '[removed]',
			"Redirect\s+302"	=> '[removed]',
			'<!--'			=> '&lt;!--',
			'-->'			=> '--&gt;'
		);
	
		foreach ( $bad as $key => $val )
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		return $str;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * get_redirect_if_restricted
	 *
	 * Used by checkLogin to store the url that was restricted
	 * if the user is presented with the login, after entering the right
	 * credentials, he's being redirected to the previous page that applied
	 * restrictions
	 *
	 * @access private
	 * @return string
	 */
	
	function get_redirect_if_restricted ()
	{
		return urlencode ( $this->selfURL () );
	}
	
	// --------------------------------------------------------------------

	/**
	 * HTML Entities Decode
	 *
	 * This function is a replacement for html_entity_decode()
	 *
	 * In some versions of PHP the native function does not work
	 * when UTF-8 is the specified character set, so this gives us
	 * a work-around.  More info here:
	 * http://bugs.php.net/bug.php?id=25670
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	/* -------------------------------------------------
	/*  Replacement for html_entity_decode()
	/* -------------------------------------------------*/
	
	/*
	NOTE: html_entity_decode() has a bug in some PHP versions when UTF-8 is the
	character set, and the PHP developers said they were not back porting the
	fix to versions other than PHP 5.x.
	* This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	*/
	
	function _html_entity_decode($str, $charset='ISO-8859-1')
	{
		if (stristr($str, '&') === FALSE) return $str;
	
		// The reason we are not using html_entity_decode() by itself is because
		// while it is not technically correct to leave out the semicolon
		// at the end of an entity most browsers will still interpret the entity
		// correctly.  html_entity_decode() does not convert entities without
		// semicolons, so we are left with our own little solution here. Bummer.
	
		if (function_exists('html_entity_decode') && (strtolower($charset) != 'utf-8' OR version_compare(phpversion(), '5.0.0', '>=')))
		{
			$str = html_entity_decode($str, ENT_COMPAT, $charset);
			$str = preg_replace('~&#x([0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
			return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
		}
		
		// Numeric Entities
		$str = preg_replace('~&#x([0-9a-f]{2,5});{0,1}~ei', 'chr(hexdec("\\1"))', $str);
		$str = preg_replace('~&#([0-9]{2,4});{0,1}~e', 'chr(\\1)', $str);
	
		// Literal Entities - Slightly slow so we do another check
		if (stristr($str, '&') === FALSE)
		{
			$str = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES)));
		}
		
		return $str;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Returns a message in the appropriate language.
	 * @access private
	 * @return string
	 */
	function Lang ( $key ) 
	{
		if ( count( $this->language ) < 1 ) {
			$this->LoadLanguage ( "en" );
		}
		if ( isset ( $this->language [ $key ] ) ) {
			return $this->language [ $key ];
		}
		else {
			return "Language string failed to load: " . $key;//no need to die here
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * cleaner
	 *
	 * Tries to clean everything that is transmited vie GET, POST, REQUEST
	 *
	 * @access	public
	 * @param
	 * @return
	 */

	function cleaner () 
	{
		foreach ( $_POST as $k => $v ) {
			if ( is_array ( $_POST [ $k ] ) )
			{
				for ( $i = 0; $i < count ( $_POST [ $k ] ); $i++ ) {
					if ( ! get_magic_quotes_gpc () ) {
						$_POST [ $k ] [ $i ] = $this->xss_clean ( addslashes ( htmlspecialchars ( $_POST [ $k ] [ $i ] ) ) );
					}
					else {
						$_POST [ $k ] [ $i ] = $this->xss_clean ( htmlspecialchars ( $_POST [ $k ] [ $i ] ) );
					}
				}
			} 
			else {
				if ( ! get_magic_quotes_gpc () ) {
					$_POST [ $k ] = $this->xss_clean ( addslashes ( htmlspecialchars ( $_POST [ $k ] ) ) );
				}
				else {
					$_POST [ $k ] = $this->xss_clean ( htmlspecialchars ( $_POST [ $k ] ) );
				}
			}
		}
		
		foreach ( $_GET as $k => $v ) {
			if ( is_array ( $_GET [ $k ] ) )
			{
				for ( $i = 0; $i < count ( $_GET [ $k ] ); $i++ ) {
					if ( ! get_magic_quotes_gpc () ) {
						$_GET [ $k ] [ $i ] = $this->xss_clean ( addslashes ( htmlspecialchars ( $_GET [ $k ] [ $i ] ) ) );
					}
					else {
						$_GET [ $k ] [ $i ] = $this->xss_clean ( htmlspecialchars ( $_GET [ $k ] [ $i ] ) );
					}
				}
			} 
			else {
				if ( ! get_magic_quotes_gpc () ) {
					$_GET [ $k ] = $this->xss_clean ( addslashes ( htmlspecialchars ( $_GET [ $k ] ) ) );
				}
				else {
					$_GET [ $k ] = $this->xss_clean ( htmlspecialchars ( $_GET [ $k ] ) );
				}
			}
		}
		
		foreach ( $_REQUEST as $k => $v ) {
			if ( is_array ( $_REQUEST [ $k ] ) )
			{
				for ( $i = 0; $i < count ( $_REQUEST [ $k ] ); $i++ ) {
					if ( ! get_magic_quotes_gpc () ) {
						$_REQUEST [ $k ] [ $i ] = $this->xss_clean ( addslashes ( htmlspecialchars ( $_REQUEST [ $k ] [ $i ] ) ) );
					}
					else {
						$_REQUEST [ $k ] [ $i ] = $this->xss_clean ( htmlspecialchars ( $_REQUEST [ $k ] [ $i ] ) );
					}
				}
			} 
			else {
				if ( ! get_magic_quotes_gpc () ) {
					$_REQUEST [ $k ] = $this->xss_clean ( addslashes ( htmlspecialchars ( $_REQUEST [ $k ] ) ) );
				}
				else {
					$_REQUEST [ $k ] = $this->xss_clean ( htmlspecialchars ( $_REQUEST [ $k ] ) );
				}
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Validate if email
	 *
	 * Determines if the passed param is a valid email
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	
	function valid_email ( $str )
	{
		return ( ! preg_match ( "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str ) ) ? FALSE : TRUE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Sets the language for all class error messages.  Returns false 
	 * if it cannot load the language file.  The default language type
	 * is English.
	 * @param string $lang_type Type of language (e.g. Portuguese: "br")
	 * @param string $lang_path Path to the language file directory
	 * @access public
	 * @return bool
	 */
	 
	function LoadLanguage ( $lang_type, $lang_path = "lang/" )
	{
		if ( file_exists ( BASE_PATH . $lang_path . 'lang.' . LANG_TYPE . '.php' ) ) {
			include ( BASE_PATH . $lang_path . 'lang.' . LANG_TYPE . '.php' );
		}
		elseif ( file_exists ( BASE_PATH . $lang_path . 'lang.en.php' ) ) {
			include ( BASE_PATH . $lang_path . 'lang.en.php' );
		}
		else {
			die ( "Could not load language file" );
			return false;
		}
		$this->language = $PHPLOGIN_LANG;
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * evaluate_response - responsible for the way we show messages, by type
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	
	function evaluate_response ( $reponse )
	{
		$arr = explode ( '|', $reponse );
		
		switch ( $arr [ 0 ] ) {
			case 'error'	:
				return '<div class="error_messages"><p>' . $arr [ 1 ] . '</p></div>' . "\n";
				break;
				
			case 'ok'	:
				return '<div class="success_messages"><p>' . $arr [ 1 ] . '</p></div>' . "\n";
				break;
				
			case 'notice'	:
				return '<div class="notice_messages"><p>' . $arr [ 1 ] . '</p></div>' . "\n";
				break;
				
			default:
				return '<div class="info_messages"><p>' . $arr [ 1 ] . '</p></div>' . "\n";
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Validate if numeric
	 *
	 * Validates string against numeric characters
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
 
 
	function numeric ( $str )
	{
		return ( ! ereg ( "^[0-9\.]+$", $str ) ) ? FALSE : TRUE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Validate if alfa numeric
	 *
	 * Validates string against alpha numeric characters
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
 
	function alpha_numeric ( $str )
	{
		return ( ! preg_match ( "/^([-a-z0-9])+$/i", $str ) ) ? FALSE : TRUE;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Exact Length
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	function exact_length($str, $val)
	{
		if ( ! is_numeric($val))
		{
			return FALSE;
		}

		return (strlen($str) != $val) ? FALSE : TRUE;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Create a Random String
	 *
	 * Useful for generating passwords or hashes.
	 *
	 * @access	public
	 * @param	string 	type of random string.  Options: alunum, numeric, nozero, unique
	 * @param	none
	 * @return	string
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	 
	 
	function random_string ( $type = 'alnum', $len = 8 )
	{					
		switch ( $type )
		{
			case 'alnum'	:
			case 'numeric'	:
			case 'nozero'	:
			
					switch ($type)
					{
						case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
							break;
						case 'numeric'	:	$pool = '0123456789';
							break;
						case 'nozero'	:	$pool = '123456789';
							break;
					}
	
					$str = '';
					for ( $i=0; $i < $len; $i++ )
					{
						$str .= substr ( $pool, mt_rand ( 0, strlen ( $pool ) -1 ), 1 );
					}
					return $str;
			break;
			case 'unique' : return md5 ( uniqid ( mt_rand () ) );
			break;
		}
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Convert MySQL Style Datecodes
	 *
	 * This function is identical to PHPs date() function,
	 * except that it allows date codes to be formatted using
	 * the MySQL style, where each code letter is preceded
	 * with a percent sign:  %Y %m %d etc...
	 *
	 * The benefit of doing dates this way is that you don't
	 * have to worry about escaping your text letters that
	 * match the date codes.
	 *
	 * @access	public
	 * @param	string
	 * @param	integer
	 * @return	integer
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	 
	function mdate ( $datestr = '', $time = '' )
	{
		if ( $datestr == '' )
			return '';
		
		if ( $time == '' )
			$time = time ();
			
		$datestr = str_replace ( '%\\', '', preg_replace ( "/([a-z]+?){1}/i", "\\\\\\1", $datestr ) );
		return date( $datestr, $time );
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * selfURL
	 *
	 * returns the current url
	 *
	 * @param	string
	 * @access	private
	 * @return 	string
	 */
	
	function selfURL ()
	{
		$s = empty ( $_SERVER["HTTPS"] ) ? '' : ( $_SERVER["HTTPS"] == "on" ) ? "s" : "";
		$protocol = $this->strleft ( strtolower ( $_SERVER["SERVER_PROTOCOL"] ), "/" ).$s;
		$port = ( $_SERVER["SERVER_PORT"] == "80" ) ? "" : ( ":".$_SERVER["SERVER_PORT"] );
		return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * strleft
	 *
	 * performs substractions
	 *
	 * @param	string
	 * @access	private
	 * @return 	string
	 */

	function strleft ( $s1, $s2 )
	{
		return substr ( $s1, 0, strpos  ( $s1, $s2 ) );
	}
	
	// --------------------------------------------------------------------

	/**
	 * Required
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
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
	
	

	// --------------------------------------------------------------------

	/**
	 * Match one field to another
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	function matches($str, $field)
	{
		if ( ! isset($_POST[$field]))
		{
			return FALSE;
		}

		return ($str !== $_POST[$field]) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Minimum Length
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	function min_length($str, $val)
	{
		if ( ! is_numeric($val))
		{
			return FALSE;
		}

		return (strlen($str) < $val) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Max Length
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	function max_length($str, $val)
	{
		if ( ! is_numeric($val))
		{
			return FALSE;
		}

		return (strlen($str) > $val) ? FALSE : TRUE;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Header Redirect
	 *
	 * Header redirect in two flavors
	 *
	 * @access	public
	 * @param	string	the URL
	 * @param	string	the method: location or redirect
	 * @return	string
	 */
	function redirect ( $uri = '', $method = 'location', $refresh = FALSE )
	{
		( $refresh ) ? $ref = $refresh : $ref = 0;
		switch($method)
		{
			case 'refresh'		: header("Refresh:$ref;url=".$uri);
				break;
			case 'meta_refresh'	: header("Refresh:$ref;url=".$uri);
				break;
			default			: header("Location: ".$uri);
				break;
		}
		exit;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * add_ending_slash
	 *
	 * self explanatory in it's own declaration - adds a trailing slash where 
	 * it should be one
	 *
	 * @param	string
	 * @access	private
	 * @return 	string
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	
	function add_ending_slash ( $string )
	{
		return ( preg_match ( '/\/$/', $string ) ) ? $string : $string . '/';
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Prep URL
	 *
	 * Simply adds the http:// part if missing
	 *
	 * @access	public
	 * @param	string	the URL
	 * @return	string
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	 
	function prep_url($str = '')
	{
		if ($str == 'http://' OR $str == '')
		{
			return '';
		}
		
		if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://')
		{
			$str = 'http://'.$str;
		}
		
		return $str;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * _array_combine
	 *
	 * Substitute for array_combine from PHP5
	 *
	 * @access	public
	 * @param	array $keys - indexes
	 * @param	array $values - values
	 * @return	array
	 * This function was copied and modified (or not) from Codeigniter
	 * http://www.codeigniter.com
	 */
	 
	function _array_combine ( $keys, $values )
	{
		$result = array();
		foreach ( array_map ( null, $keys, $values ) as $pair ) {
			$result [ $pair[ 0 ] ] = $pair [ 1 ];
		}
		return $result;
	}
	
	// ------------------------------------------------------------------------

	/**
	 * CheckWritePermission
	 *
	 * checks a given file for write permissions
	 *
	 * @access	public
	 * @param	$file
	 * @return	bol
	 */
	
	function CheckWritePermission ( $file='' )
	{
		if ( ! file_exists ( $file ) ) {
			return false;
		}

		$unlink = false;

		if ( ! is_file ( $file ) ) {
			$unlink = true;
			if ( is_dir ( $file ) ) {
				$file = $file . '/' . date('U') . '.php';
			} 
			else {
				return false;
			}
		}

		if ( ! $fp = @fopen ( $file, 'w+' ) ) {
			return false;
		}

		$contents = "\n";

		if ( ! @fputs ( $fp, $contents, strlen ( $contents ) ) ) {
			return false;
		}

		if ( ! @fclose ( $fp ) ) {
			return false;
		}

		if ( $unlink ) {
			if ( ! @unlink ( $file ) ) {
				return false;
			}
		}
		return true;
	}
}
?>