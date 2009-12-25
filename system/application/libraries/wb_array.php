<?php

class WB_array {

	protected static $out = array ();
	public static $search_nodes = TRUE;

	public static function get_values ( array $array ) {
		foreach ( $array as $value ) {
			if ( is_array ( $value ) ) {
				if ( self::$search_nodes ) {
					self::get_values ( $value );
				}
			}
			else {
				array_push ( self::$out, $value );
			}
		}
		return self::$out;
	}
	
	function array_sort ( $array, $type = 'asc' ) {
		$result = array ();
		foreach ( $array as $var => $val ) {
			$set = false;
			foreach ( $result as $var2 => $val2 ) {
				if ( $set == false ) {
					if ( $val > $val2 && $type == 'desc' || $val < $val2 && $type == 'asc' ) {
						$temp = array ();
						foreach ( $result as $var3 => $val3 ) {
							if ( $var3 == $var2 )
								$set = true;
							if ( $set ) {
								$temp [ $var3 ] = $val3;
								unset ( $result [ $var3 ] );
							}
						}
						$result [ $var ] = $val;
						foreach ( $temp as $var3 => $val3 ) {
							$result [ $var3 ] = $val3;
						}
					}
				}
			}

			if ( ! $set ) {
				$result [ $var ] = $val;
			}
		}
		return $result;
	}

	public static function get_keys ( array $array ) {
		foreach ( $array as $key => $value ) {
			if ( is_array ( $value ) ) {
				if ( self::$search_nodes ) {
					self::get_keys ( $value );
				}
			}
			else {
				array_push ( self::$out, $key );
			}
		}
		return self::$out;
	}

	public static function get_values_by_key ( array $array, $needle_key ) {
		foreach ( $array as $key => $value ) {
			if ( $key == $needle_key ) {
				array_push ( self::$out, $value );
			}

			if ( is_array ( $value ) ) {
				if  ( self::$search_nodes ) {
					self::get_values_by_key ( $value, $needle_key );
				}
			}
		}
		return self::$out;
	}

	public static function not_empty ( array $array, &$ret = FALSE ) {
		foreach ( $array as $arr ) {
			if ( ! is_array ( $arr ) ) {
				if ( strlen ( trim ( $arr ) ) ) {
					return TRUE;
					break;
				}
			}
			else {
				$ret = self::not_empty ( $arr, $ret );
			}
		}
		return $ret;
	}

	public static function max ( array $array, $only_numeric = true )
	{
		return
		( $only_numeric ) ? 
			max ( self::extract_integers ( $array ) ) : 
			max ( self::get_values ( $array ) );
	}

	public static function min ( array $array, $only_numeric = true )
	{
		return
		( $only_numeric ) ? 
			min ( self::extract_integers ( $array ) ) : 
			min ( self::get_values ( $array ) );
	}

	public static function extract_integers ( array $array ) {
		$array = self::get_values ( $array );
		$out = array ();

		foreach ( $array as $value ) {
			if ( is_int ( $value ) ) {
				array_push ( $out, $value );
			}
		}
		return $out;
	}

	public static function get_first_key ( array $array ) {
		return key ( $array );
	}

	public static function get_last_key ( array $array ) {
		$keys = self::get_keys ( $array );
		foreach ( $keys as $key ) {
			$out = $key;
		}
		return $out;
	}

	public static function get_last_value ( array $array, $search_nodes = true, &$out = array () ) {
		$values = self::get_values ( $array );
		foreach ( $values as $value ) {
			$out = $value;
		}
		return $out;
	}
	
	public static function sort_columns ( array $array, $field, $descending = FALSE )
	{
		$is_numeric = self::is_numeric ( $array );
		$keys = array_keys ( $array );
		$size = sizeof ( $array );

		for ( $i = 0; $i < $size - 1; $i++ )
		{
			$min_index = $i;
			$min_value = $array [ $keys [ $i ] ] [ $field ];
			$sKey = $keys [ $i ];

			for ( $n = $i + 1; $n < $size; $n++ )
			{
				if ( $array [ $keys [ $n ] ] [ $field ] < $min_value )
				{
					$min_index = $n;
					$sKey = $keys [ $n ];
					$min_value = $array [ $keys [ $n ] ] [ $field ];
				}
			}

			$keys [ $min_index ] = $keys [ $i ];
			$keys [ $i ] = $sKey;
		}

		$ret = array ();
		for ( $n = 0; $n < $size; $n++ )
		{
			$i = $descending ? $size - $n - 1: $n;
			$ret [ $keys [ $i ] ] = $array [ $keys [ $i ] ];
		}

		return $is_numeric ? array_values ( $ret ) : $ret;
	}

	function is_numeric ( array $array )
	{
		$keys = self::get_keys ( $array );
		for ( $i = 0; $i < sizeof ( $keys ); $i++ )
		{
			if ( ! is_int ( $keys [ $i ] ) || ( $keys [ $i ] != $i ) )
			{
				return FALSE;
			}
		}
		return TRUE;
	}
}