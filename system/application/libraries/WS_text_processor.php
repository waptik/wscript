<?php

class WS_text_processor {

	public static $str;

	public static function strtolower () {
		return ( function_exists ( 'mb_strtolower' ) ) ? mb_strtolower ( self::$str, WS_ENCODING ) : strtolower ( self::$str );
	}

	public static function strlen () {
		return ( function_exists ( 'mb_strlen' ) ) ? mb_strlen ( self::$str, WS_ENCODING ) : strlen ( self::$str );
	}

	public static function substr ( $start, $length ) {
		if ( ! $length ) {
			return ( function_exists ( 'mb_substr' ) ) ? mb_substr ( self::$str, $start, mb_strlen ( self::$str ), WS_ENCODING ) : substr ( self::$str, $start, mb_strlen ( self::$str ) );
		}
		return ( function_exists ( 'mb_substr' ) ) ? mb_substr ( self::$str, $start, $length, WS_ENCODING ) : substr ( self::$str, $start, $length );
	}

	public static function split ( $split_del ) {
		return ( function_exists ( 'mb_split' ) ) ? mb_split ( $split_del, self::$str ) : split ( $split_del, self::$str );
	}

	public static function strpos ( $needle, $offset ) {
		return ( function_exists ( 'mb_strpos' ) ) ? mb_strpos ( self::$str, $needle, $offset, WS_ENCODING ) : strpos ( self::$str, $needle, $offset );
	}

	public static function strip_punctuation () {
		return self::$str;
	}
	
	public static function utf8_to_unicode ( $str ) {
		$mState = 0;
		$mUcs4  = 0;
		$mBytes = 1;
    
		$out = array ();
    
		$len = strlen ( $str );
    
		for ( $i = 0; $i < $len; $i++ ) {
			$in = ord ( $str{$i} );
        
			if ( $mState == 0 ) {
				if ( 0 == ( 0x80 & ( $in ) ) ) {
					$out [] = $in;
					$mBytes = 1;
                
				}
				elseif ( 0xC0 == ( 0xE0 & ( $in ) ) ) {
					$mUcs4 = ( $in );
					$mUcs4 = ( $mUcs4 & 0x1F ) << 6;
					$mState = 1;
					$mBytes = 2;
				}
				elseif ( 0xE0 == ( 0xF0 & ( $in ) ) ) {
					$mUcs4 = ( $in );
					$mUcs4 = ( $mUcs4 & 0x0F ) << 12;
					$mState = 2;
					$mBytes = 3;
				}
				elseif ( 0xF0 == ( 0xF8 & ( $in ) ) ) {
					$mUcs4 = ( $in );
					$mUcs4 = ( $mUcs4 & 0x07 ) << 18;
					$mState = 3;
					$mBytes = 4;
				}
				elseif ( 0xF8 == ( 0xFC & ( $in ) ) ) {
					$mUcs4 = ( $in );
					$mUcs4 = ( $mUcs4 & 0x03 ) << 24;
					$mState = 4;
					$mBytes = 5;
				}
				elseif ( 0xFC == ( 0xFE & ( $in ) ) ) {
					$mUcs4 = ( $in );
					$mUcs4 = ( $mUcs4 & 1 ) << 30;
					$mState = 5;
					$mBytes = 6;
				}
				else {
					trigger_error (
						'utf8_to_unicode: Illegal sequence identifier in UTF-8 at byte ' . $i, E_USER_WARNING
					);
	
					return FALSE;
				}
			}
			else {
				if ( 0x80 == ( 0xC0 & ( $in ) ) ) {
					$shift = ( $mState - 1 ) * 6;
					$tmp = $in;
					$tmp = ( $tmp & 0x0000003F ) << $shift;
					$mUcs4 |= $tmp;

					if ( 0 == --$mState ) {
						if
						(
							( ( 2 == $mBytes ) && ( $mUcs4 < 0x0080 ) ) ||
							( ( 3 == $mBytes ) && ( $mUcs4 < 0x0800 ) ) ||
							( ( 4 == $mBytes ) && ( $mUcs4 < 0x10000 ) ) ||
							( 4 < $mBytes ) ||
							( ( $mUcs4 & 0xFFFFF800 ) == 0xD800) ||
							( $mUcs4 > 0x10FFFF )
						) {
                        
							trigger_error (
								'utf8_to_unicode: Illegal sequence or codepoint in UTF-8 at byte ' . $i, E_USER_WARNING
							);
                        
							return FALSE;
						}
                    
						if ( 0xFEFF != $mUcs4 ) {
							$out [] = $mUcs4;
						}
                    
						//initialize UTF8 cache
						$mState = 0;
						$mUcs4  = 0;
						$mBytes = 1;
					}
				}
				else {
					trigger_error (
						'utf8_to_unicode: Incomplete multi-octet sequence in UTF-8 at byte ' . $i, E_USER_WARNING
					);

					return FALSE;
				}
			}
		}
		return $out;
	}

	public static function utf8_from_unicode ( $arr ) {
		ob_start ();
    
		foreach ( array_keys ( $arr ) as $k ) {
			if ( ( $arr [ $k ] >= 0 ) && ( $arr [ $k ] <= 0x007f ) ) {
				echo chr ( $arr [ $k ] );
			}
			elseif ( $arr [ $k ] <= 0x07ff ) {
				echo chr ( 0xc0 | ( $arr [ $k ] >> 6 ) );
				echo chr ( 0x80 | ( $arr [ $k ] & 0x003f ) );
			}
			elseif ( $arr [ $k ] == 0xFEFF ) {

			}
			elseif ( $arr [$k ] >= 0xD800 && $arr [ $k ] <= 0xDFFF ) {
				trigger_error (
					'utf8_from_unicode: Illegal surrogate at index: ' . $k . ', value: ' . $arr [ $k ], E_USER_WARNING
				);
            
				return FALSE;
			}
			elseif ( $arr [ $k ] <= 0xffff ) {
				echo chr ( 0xe0 | ( $arr [ $k ] >> 12 ) );
				echo chr ( 0x80 | ( ( $arr [ $k] >> 6) & 0x003f ) );
				echo chr ( 0x80 | ( $arr [ $k ] & 0x003f ) );
			}
			elseif ( $arr [ $k ] <= 0x10ffff ) {
				echo chr ( 0xf0 | ( $arr [ $k ] >> 18 ) );
				echo chr ( 0x80 | ( ( $arr [ $k ] >> 12 ) & 0x3f ) );
				echo chr ( 0x80 | ( ( $arr [ $k ] >> 6 ) & 0x3f ) );
				echo chr ( 0x80 | ( $arr [ $k ] & 0x3f ) );
			}
			else {
				trigger_error (
					'utf8_from_unicode: Codepoint out of Unicode range at index: ' . $k . ', value: ' . $arr [ $k ], E_USER_WARNING
				);

				return FALSE;
			}
		}
    
		$result = ob_get_contents ();
		ob_end_clean ();
		return $result;
	}
}