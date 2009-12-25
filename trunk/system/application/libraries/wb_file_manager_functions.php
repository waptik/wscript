<?php

define ( 'DS', '/' );
define ( 'FILE_MANAGER_MAX_EXEC_TIME', 500 );
define ( 'FILE_MANAGER_MEMORY_LIMIT', MEMORY_LIMIT );


//	allowing you to run this lib from outside Webber
if ( ! defined ( 'SAFE_MODE' ) ) {
	define ( 'SAFE_MODE', ( ( boolean ) @ini_get ( "safe_mode" ) === FALSE ) ? FALSE : TRUE );
}

class WB_file_manager_functions {
	
	//	error types
	var $_errors	= array
	(
		0 	=> "Unknown error",
		1 	=> "Directory does not exist '<b>%s</b>'",
		2 	=> "Invalid directory '<b>%s</b>'",
		3 	=> "Not writable directory '<b>%s</b>'",
		4 	=> "File does not exist '<b>%s</b>'",
		5 	=> "File or directory does not exist '<b>%s</b>'",
		6 	=> "Element is not an array '<b>%s</b>'",
		7 	=> "Operation failed! Chmod 0777 directory '<b>%s</b>'",
		8 	=> "Directory does not exist or directory is not writable '<b>%s</b>'",
		9 	=> "Specify a new directory '<b>%s</b>'",
		10	=> "Can't delete resource '<b>%s</b>'!",
		11	=> "Failed to get statistics for '<b>%s</b>'. This is not a folder",
		12	=> "Callback function/method '<b>%s</b>' was not found",
		13	=> "'<b>%s</b>' NOT changed to '<b>%s</b>'",
		14	=> "File '<b>%s</b>' could not be copied!",
		15	=> "File '<b>%s</b>' could not be copied because I failed to create a new name!",
		16	=> "'<b>%s</b>' process was ended before time due to ini settings",
		17	=> "Since no location was specified please define your root folder after instanciating this class in order to make the search possible: \$this->wb_file_manager->root = '/path/to/your/root/folder'",
		18	=> "%s failed. The destination folder '<b>%s</b>' is a subfolder of the source folder '<b>%s</b>'",
		19	=> "The uploaded file '<b>%s</b>' exceeds the upload_max_filesize directive in php.ini.",
		20	=> "The uploaded file '<b>%s</b>' exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
		21	=> "The file '<b>%s</b>' was only partially uploaded",
		22	=> "No file was selected for upload",
		23	=> "The file '<b>%s</b>' was not uploaded because no temp directory was found",
		24	=> "The file '<b>%s</b>' can't be uploaded because the destination folder ('<b>%s</b>') is not writable",
		25	=> "The file '<b>%s</b>' can't be uploaded. Error in extension",
		26	=> "The file '<b>%s</b>' can't be uploaded. File is bigger than the max upload limit",
		27	=> "The file '<b>%s</b>' has 0 bytes. Does it exist?",
		28	=> "Cannot create temp file",
		29	=> "Image type '<b>%s</b>' not supported",
		30	=>  "The file '<b>%s</b>' can't be uploaded. Extension not allowed",
	);
	
	//	log messages
	var $_logs	= array
	(
		0 	=> "'<b>%s</b>' was ignored due to our filters",
		1	=> "'<b>%s</b>' changed to '<b>%s</b>'",
		2	=> "running using '<b>%s</b>' commands",
		3	=> "'<b>%s</b>' was skipped from the copy operation as it was found in another 'to copy' folder",
		4	=> "'<b>%s</b>' was copied successfully from %s to %s",
		5	=> "'<b>%s</b>' was moved successfully from %s to %s",
		6	=> "'<b>%s</b>' was deleted successfully",
	);

	//	array of allowed extensions to upload
	var $allowed_upload = array ( 'csv', 'psd', 'pdf', 'eps', 'ps', 'swf', 'tar', 'tgz', 'xhtml', 'zip', 'mid', 'midi', 'mpga', 'mp2', 'mp3', 'aif', 'aiff', 'aifc', 'ram', 'rm', 'rpm', 'ra', 'rv', 'wav', 'bmp', 'gif', 'jpeg', 'jpg', 'png', 'tiff', 'tif', 'html', 'htm', 'txt', 'text', 'log', 'xml', 'mov', 'avi', 'movie', 'doc', 'word', 'xl', 'eml' );
	
	//	what we can see
	var $allowed_view = array ( 'csv', 'psd', 'pdf', 'eps', 'ps', 'swf', 'tar', 'tgz', 'xhtml', 'zip', 'mid', 'midi', 'mpga', 'mp2', 'mp3', 'aif', 'aiff', 'aifc', 'ram', 'rm', 'rpm', 'ra', 'rv', 'wav', 'bmp', 'gif', 'jpeg', 'jpg', 'png', 'tiff', 'tif', 'html', 'htm', 'txt', 'text', 'log', 'xml', 'mov', 'avi', 'movie', 'doc', 'word', 'xl', 'eml' );

	//	what we can read
	var $allowed_read = array ( 'csv', 'xhtml', 'html', 'htm', 'txt', 'text', 'log', 'xml' );

	//	what we can edit
	var $allow_edit = array ( 'csv', 'xhtml', 'html', 'htm', 'txt', 'text', 'log', 'xml' );

	//	what we can create
	var $allow_create = array ( 'csv', 'xhtml', 'html', 'htm', 'txt', 'text', 'log', 'xml' );

	//	global ignore files
	var $ignore	= array ( '.', '..', 'Thumb.db' );
	
	var $logs	= array ();

	//	errors occured during file/folder manipulation
	var $errors	= array ();
	
	//	path to current folder (optional, used only for file details functions @get_file_details)
	var $path = null;

	var $max_upload_size = 0;
	
	//	default folder permission
	var $folder_perm = 0755;
	//	default folder permission
	var $file_perms = 0666;
	//	needed file details ( depth etc )
	var $root = '';
	
	//	IMAGE SETTINGS
	
	//	save thumbs to new files
	var $image_save_to_file = TRUE;
	//	image type
	var $image_type = -1;
	//	image quality
	var $image_quality = 100;
	//	image max height
	var $image_max_x = 100;
	//	image max width
	var $image_max_y = 100;
	//	thumbs folder
	var $image_thumbs_dir = NULL;
	//	thumbs prefix string
	var $image_thumb_prefix = 'thb_';
	
	function __construct ()
	{
		if ( SAFE_MODE ) {
			$this->allowed_execution_time = ( int ) @ini_get ( "max_execution_time" );
		}
		else {
			//	otherwise set the execution time to a bigger value
			@ini_set ( "max_execution_time", FILE_MANAGER_MAX_EXEC_TIME );
			//	we will probably need more memory as well...
			@ini_set ( "memory_limit", FILE_MANAGER_MEMORY_LIMIT );
			$this->allowed_execution_time = FILE_MANAGER_MAX_EXEC_TIME;
		}
	}
	
	function build_path ( $root, $item, $is_win = FALSE )
	{
		return WB_file_manager_functions::clean_path ( $root . DS . $item, $is_win );
	}
	
	/**
	 * Returns a unique file name if there's an existing one in the list
	 * @param string $orig Original filename
	 * @param array $list The list of existing files
	 * @param integer $max Max size of new filename
	 * @return string
	 */
	
	function get_unique_name ( $orig, $list_md5 = array () )
	{
		$orig = WB_file_manager_functions::clean_path ( $orig );
		$path = WB_file_manager_functions::get_file_location ( $orig );
		$original_file = WB_file_manager_functions::get_file_from_path ( $orig );
		
		if ( ! WB_file_manager_functions::is_file ( $orig ) ) {
			return $original_file;
		}

		$cnt = 0;

		if ( @preg_match ( '/(.+)\.([^.]{1,5})$/', $original_file, $parts ) )
		{
			// split to name & extension
			list ( $name, $ext ) = array ( $parts [ 1 ], $parts [ 2 ] );
			
			$new_name = $name . '[' . $cnt . '].' . $ext;
			$rtry = WB_file_manager_functions::clean_path ( $path . DS . $name . '[' . $cnt . '].' . $ext );

			while ( file_exists ( $rtry ) )
			{
				$cnt++;
				$rtry = WB_file_manager_functions::clean_path ( $path . DS . $name . '[' . $cnt . '].' . $ext );
				$new_name = $name . '[' . $cnt . '].' . $ext;
			}

			return $new_name;
		}

		return $original_file;
	}
	
	/**
	 * Returns the size of a given directory
	 * @param string $dir Path to directory
	 * @return integer Size in bytes
	 */
	
	function dirsize ( $dir, $recursive = TRUE )
	{
		$dir = WB_file_manager_functions::add_end_slash ( $dir );

		if ( ! $recursive ) {
			return array_sum ( array_map ( 'filesize', glob ( "$dir*" ) ) );
		}

		$size = 0;
		
		
		$files = glob ( "$dir*" );

		foreach ( $files as $file )
		{
			$size += ( WB_file_manager_functions::is_dir ( $file ) ) ? WB_file_manager_functions::dirsize ( $file, $recursive ) : @filesize ( $file );
		}
		
		return $size;
	}
	
	/**
	 * Applies a callback to an array of files and directories
	 * 
	 * @todo To allow adding other params when using object->method
	 * @param array $array The array
	 * @param mixed $callback Callback function, method etc.
	 * @return array The new array
	 */

	function apply_callback ( $array, $callback )
	{
		if ( $callback != FALSE )
		{//	apply callback if method/function exists

			$out = array ();

			foreach ( $array as $value )
			{
				if ( is_array ( $callback ) )
				{//	it's an object
					if
						(
							isset ( $callback [ 0 ] ) &&
							is_object ( $callback [ 0 ] ) &&
							isset ( $callback [ 1 ] ) &&
							method_exists ( $callback [ 0 ], $callback [ 1 ] )
						)
					{
						$_dir = $callback [ 0 ]->$callback [ 1 ] ( $value );
					}
					else {//	bad callback, log the error and continue as without any callback
						WB_file_manager_functions::_add_error ( 12, @implode ( '->', $callback ) );
						$_dir = $directory . DS . $file;
					}
				}
				elseif ( function_exists ( $callback ) )
				{//	a function
					$_dir = $callback ( $value );
				}
				else {//	bad callback, log the error and continue as without any callback
					WB_file_manager_functions::_add_error ( 12, $callback );
					$_dir = $value;
				}

				array_push ( $out, $_dir );
			}

			return $out;
		}

		return $array;
	}
	
	/**
	 * Adds an end slash to a string if not present
	 * 
	 * @param string $str The string
	 * @param boolean $force Should we force and add a slash even if present?
	 * @return string
	 */
	
	function add_end_slash ( $str, $force = FALSE )
	{
		if ( substr ( $str, -1 ) != '/' || $force ) {
			$str = $str . '/';
		}

		return $str;
	}
	
	/**
	 * Adds a start slash to a string if not present
	 * 
	 * @param string $str The string
	 * @param boolean $force Should we force and add a slash even if present?
	 * @return string
	 */
	
	function add_start_slash ( $str, $force = FALSE )
	{
		if ( $str [ 0 ] != '/' || $force ) {
			$str = '/' . $str;
		}

		return $str;
	}

	function _p ( $var )
	{
		echo '<pre>' . print_r ( $var, TRUE ) . '</pre>';
	}
	
	/**
	 * Converts an associative array to a normal one. Only values are considered
	 * 
	 * @param @array The array
	 * @return array
	 */

	function remove_association ( $array )
	{
		$out = array ();
		foreach ( $array as $key => $value )
		{
			if ( is_array ( $value ) )
			{
				$out = array_merge ( $out, $this->remove_association (  $value ) );
			}
			else {
				if ( ! isset ( $array [ $key ] [ 'name' ] ) )
				{
					array_push ( $out, $array );
				}
			}
		}

		return $out;
	}
	
	function is_empty_dir ( $dir, $check = FALSE )
	{
		if ( $check ) {
			if ( ! WB_file_manager_functions::is_dir ( $dir ) ) {
				return FALSE;
			}
		}

		if ( $dh = @opendir ( $dir ) )
		{
			while ( $file = readdir ( $dh ) )
			{
				if ( $file != '.' && $file != '..') {
					closedir ( $dh );
					return FALSE;
				}
			}
			closedir ( $dh );
			return TRUE;
		}
		
		return FALSE; // whatever the reason is : no such dir, not a dir, not readable
	}

	function get_file_details ( $filename, $include = array ( 'size', 'type', 'extension', 'kind', 'modified', 'location', 'permissions', 'depth', 'full_path_md5', 'is_empty', 'full_path', 'rel_path', 'group', 'owner', 'last_access', 'readable', 'writable' ) )
        {
        	$file_prepare = WB_file_manager_functions::clean_path ( $filename );

        	$kt = explode ( DS, $file_prepare );

        	$file = $kt [ count ( $kt ) - 1 ];
        	$location = WB_file_manager_functions::get_file_location ( $file_prepare );
		
		$out = array ( 'file' => $file );
		
		if ( in_array ( 'size', $include ) ) {		$out [ 'size' ] = @filesize ( $filename );}
		if ( in_array ( 'type', $include ) ) {		$out [ 'type' ] = @filetype ( $filename );}
		if ( in_array ( 'extension', $include ) ) {	$out [ 'extension' ] = $this->file_extension ( $filename );}
		if ( in_array ( 'kind', $include ) ) 	{	$out [ 'kind' ] =  $this->file_extension ( $filename, TRUE );}
		if ( in_array ( 'modified', $include ) ) {	$out [ 'modified' ] =  mdate ( "%d-%m-%Y %h:%i:%s", WB_file_manager_functions::last_change ( $filename ) );}
		if ( in_array ( 'last_access', $include ) ) {	$out [ 'last_access' ] =  mdate ( "%d-%m-%Y %h:%i:%s", WB_file_manager_functions::last_access ( $filename ) );}
		if ( in_array ( 'location', $include ) ) {	$out [ 'location' ] =  WB_file_manager_functions::clean_path ( $location );}
		if ( in_array ( 'full_path_md5', $include ) ) {	$out [ 'full_path_md5' ] =  md5 ( $location . $file );}
		if ( in_array ( 'permissions', $include ) ) {	$out [ 'permissions' ] =  WB_file_manager_functions::get_human_readable_string ( $filename );}
		if ( in_array ( 'full_path', $include ) ) {	$out [ 'full_path' ] =  WB_file_manager_functions::clean_path ( $location . DS . $file );}
		if ( in_array ( 'depth', $include ) ) {		$out [ 'depth' ] =  $this->get_depth ( $this->root, $location );}
		if ( in_array ( 'group', $include ) ) {		$out [ 'group' ] =  WB_file_manager_functions::group ( $filename );}
		if ( in_array ( 'owner', $include ) ) {		$out [ 'owner' ] =  WB_file_manager_functions::owner ( $filename );}
		if ( in_array ( 'is_empty', $include ) ) {	$out [ 'is_empty' ] =  WB_file_manager_functions::is_empty_dir ( $filename );}
		if ( in_array ( 'rel_path', $include ) ) {	$out [ 'rel_path' ] =  WB_file_manager_functions::get_relative_path ( $this->root, $filename );}
		if ( in_array ( 'readable', $include ) ) {	$out [ 'readable' ] =  WB_file_manager_functions::readable ( $filename );}
		if ( in_array ( 'writable', $include ) ) {	$out [ 'writable' ] =  WB_file_manager_functions::writable ( $filename );}

		return $out;
        }

	function get_depth ( $root, $loc )
	{
		if ( @strpos ( $loc, $root ) !== 0 ) {
			return FALSE;
		}
		
		$segments = str_replace ( $root, '', $loc );
		$kt = explode ( DS, $segments );
		$depth = 0;
		
		foreach ( $kt as $fold )
		{
			if ( $fold != '' )
			{
				$depth++;
			}
		}
	
		return $depth;
	}
	
	function get_relative_path ( $root, $abs )
	{
		if ( @strpos ( $abs, $root ) !== 0 ) {
			return FALSE;
		}

		return str_replace ( $root, '', $abs );
	}
	
	/**
	 * Returns the name of a file
	 * 
	 * @param string $file
	 * @return string
	 */
	
	function name ( $file, $check = FALSE )
	{
		if ( $check ) {
			if ( ! WB_file_manager_functions::is_file ( $file ) ) {
				return FALSE;
			}
		}

		$ext = WB_file_manager_functions::file_extension ( $file );
		$filename = WB_file_manager_functions::get_file_from_path ( $file );

		return str_replace ( $ext, '', $filename );
	}
	
	/**
	 * Cleans a list of folders. If one path is located within another path that is present in the array
	 * it will be removed. This is good when performing operations such as copy or move.
	 * 
	 * @param array $folders Array with folder paths
	 * @return array
	 */
	
	function clean_child_folders ( $folders )
	{
		if ( is_array ( $folders ) && ! empty ( $folders ) )
		{
			$mirror = $folders;
	
			foreach ( $mirror as $folder )
			{
				foreach ( $folders as $key => $fld )
				{
					if ( strpos ( $fld, $folder ) === 0 && $fld != $folder ) {
						unset ( $folders [ $key ] );
					}
				}
			}
			
			return $folders;	
		}
		return array ();	
	}
	
	function clean_child_files ( $files, $folders )
	{
		if ( is_array ( $folders ) && ! empty ( $folders ) )
		{
			if ( is_array ( $files ) && ! empty ( $files ) )
			{
				foreach ( $files as $key => $file )
				{
					$location = WB_file_manager_functions::clean_path ( WB_file_manager_functions::get_file_location ( $file ) );
		
					foreach ( $folders as $folder )
					{
						if ( strpos ( $location, $folder ) === 0 ) {
							unset ( $files [ $key ] );
						}
					}
				}
			}
		}
		return $files;
	}
	
	function size ( $file, $check = FALSE )
	{
		if ( $check ) {
			if ( WB_file_manager_functions::is_file ( $file ) ) {
				return filesize ( $file );
			}
			return FALSE;
		}

		return filesize ( $file );
	}
        
        function get_numeric_permission ( $path ) 
	{		
		// Initialisation
		$val	= 0;
		$perms	= @fileperms ( $path );
		
		// Owner; User
		$val += ( ( $perms & 0x0100 ) ? 0x0100 : 0x0000 );		// Read
		$val += ( ( $perms & 0x0080 ) ? 0x0080 : 0x0000 );		// Write
		$val += ( ( $perms & 0x0040 ) ? 0x0040 : 0x0000 );		// Execute

		// Group
		$val += ( ( $perms & 0x0020 ) ? 0x0020 : 0x0000 );		// Read
		$val += ( ( $perms & 0x0010 ) ? 0x0010 : 0x0000 );		// Write
		$val += ( ( $perms & 0x0008 ) ? 0x0008 : 0x0000 );		// Execute

		// Global; World
		$val += ( ( $perms & 0x0004 ) ? 0x0004 : 0x0000 );		// Read
		$val += ( ( $perms & 0x0002 ) ? 0x0002 : 0x0000 );		// Write
		$val += ( ( $perms & 0x0001 ) ? 0x0001 : 0x0000 );		//	Execute

		// Misc
		$val += ( ( $perms & 0x40000 ) ? 0x40000 : 0x0000 );	// temporary file (01000000)
		$val += ( ( $perms & 0x80000 ) ? 0x80000 : 0x0000 ); 	// compressed file (02000000)
		$val += ( ( $perms & 0x100000 ) ? 0x100000 : 0x0000 );	// sparse file (04000000)
		$val += ( ( $perms & 0x0800 ) ? 0x0800 : 0x0000 );		// Hidden file (setuid bit) (04000)
		$val += ( ( $perms & 0x0400 ) ? 0x0400 : 0x0000 );		// System file (setgid bit) (02000)
		$val += ( ( $perms & 0x0200 ) ? 0x0200 : 0x0000 );		// Archive bit (sticky bit) (01000)

		return decoct ( $val );
	}
	
	function get_human_readable_string ( $path )
	{
		$mode = @fileperms ( $path );

		if ( $mode & 0x1000 ) $type='p'; 	// FIFO pipe
		else if ( $mode & 0x2000 ) $type='c'; 	// Character special
		else if ( $mode & 0x4000 ) $type='d'; 	// Directory
		else if ( $mode & 0x6000 ) $type='b'; 	// Block special
		else if ( $mode & 0x8000 ) $type='-'; 	// Regular
		else if ( $mode & 0xA000 ) $type='l'; 	// Symbolic Link
		else if ( $mode & 0xC000 ) $type='s'; 	// Socket
		else $type='u'; // UNKNOWN
		
		// Determine les permissions par groupe
		
		$owner [ "read" ]	= ( $mode & 00400 ) ? 'r' : '&minus;';
		$owner [ "write" ]	= ( $mode & 00200 ) ? 'w' : '&minus;';
		$owner [ "execute" ]	= ( $mode & 00100 ) ? 'x' : '&minus;';
		$group [ "read" ]	= ( $mode & 00040 ) ? 'r' : '&minus;';
		$group [ "write" ]	= ( $mode & 00020 ) ? 'w' : '&minus;';
		$group [ "execute" ]	= ( $mode & 00010 ) ? 'x' : '&minus;';
		$others [ "read" ]	= ( $mode & 00004 ) ? 'r' : '&minus;';
		$others [ "write" ]	= ( $mode & 00002 ) ? 'w' : '&minus;';
		$others [ "execute" ]	= ( $mode & 00001 ) ? 'x' : '&minus;';
		
		// Adjuste pour SUID, SGID et sticky bit
		
		if ( $mode & 0x800 ) $owner [ "execute" ] 	= ( $owner [ 'execute' ] == 'x' ) ? 's' : 'S';
		if ( $mode & 0x400 ) $group [ "execute" ] 	= ( $group [ 'execute' ] == 'x' ) ? 's' : 'S';
		if ( $mode & 0x200 ) $others [ "execute" ] 	= ( $others [ 'execute' ] == 'x' ) ? 't' : 'T';
		
		return
			//	owner
			"$type $owner[read]$owner[write]$owner[execute]" .
			//	group
			" $group[read]$group[write]$group[execute]" .
			//	others
			" $others[read]$others[write]$others[execute]";
	}
	
	/**
	 * Returns the location of a given file
	 * 
	 * @param string $file Path to file
	 * @param boolean $check Whether to check or not the resource type before anything else
	 * @return mixed
	 */
	
	function get_file_location ( $file, $check = FALSE )
	{
		if ( $check ) {
			if ( WB_file_manager_functions::file_exists ( $file ) ) {
				$pathinfo = @pathinfo ( $file );
				return WB_file_manager_functions::clean_path ( $pathinfo [ 'dirname' ] );
			}
			return NULL;
		}

		$pathinfo = @pathinfo ( $file );
		return WB_file_manager_functions::clean_path ( $pathinfo [ 'dirname' ] );
	}

	/**
	 * Returns the extension of a file
	 * 
	 * @param string Path to file
	 * @param boolean $explained If true, will return the detailed result e.g.
	 * pdf = application/pdf ...
	 * @return string
	 */

        function file_extension ( $filename, $explained = FALSE )
	{
		$extension = 'directory';

		$kt = explode ( '.', $filename );
		$extension = $kt [ count ( $kt ) - 1 ];
		
		if ( $explained )
		{
			include ( ROOTPATH . '/system/application/config/mimes.php' );
			if ( isset ( $mimes [ $extension ] ) )
			{
				$return = $mimes [ $extension ];
				if ( is_array ( $mimes [ $extension ] ) )
				{
					$return = $mimes [ $extension ] [ 0 ];
				}
				return $return;
			}
		}
		return strtolower ( $extension );
	}

	function get_file_permissions ( $file )
	{
		return substr ( sprintf ( '%o', fileperms ( $file ) ), -4 );
	}

	/**
	 * Transforms a "friendly size" of file into it's exact
	 * size in bytes
	 * 
	 * @param string $val The string type size e.g. 10G
	 * @return integer
	 */

	function tobytes ( $val )
	{
		$val = trim ( $val );
		$out = 1;
		$last = strtolower ( $val { strlen ( $val ) - 1 } );
		switch ( $last )
		{
			case 'g':
				//	gigabytes
				$out *= ( ( 1024 * 1000 ) * 1000 );
				break;
			case 'm':
				//	megabytes
				$out *= ( 1024 * 1000 );
				break;
			case 'k':
				//	kilobytes
				$out *= 1024;
				break;
		}

		return $out;
	}
	
	function _add_error ( $num, $items = FALSE )
	{
		if ( array_key_exists ( $num, $this->_errors ) )
		{
			if ( $items != FALSE )
			{
				if ( is_array ( $items ) ) {
					$this->errors [ mdate ( "%d-%m-%Y %h:%i:%s" ) ] [] = WB_file_manager_functions::vprint ( $this->_errors [ $num ], $items );
				}
				else {
					$this->errors [ mdate ( "%d-%m-%Y %h:%i:%s" ) ] [] = @sprintf ( $this->_errors [ $num ], $items );
				}
			}
			else {
				$this->errors [ mdate ( "%d-%m-%Y %h:%i:%s" ) ] [] = $this->_errors [ $num ];
			}	
		}
		return FALSE;
	}

	function _add_log ( $num, $items = FALSE )
	{
		if ( array_key_exists ( $num, $this->_logs ) )
		{
			if ( $items != FALSE )
			{
				if ( is_array ( $items ) ) {
					$this->logs [ mdate ( "%d-%m-%Y %h:%i:%s" ) ] [] = WB_file_manager_functions::vprint ( $this->_logs [ $num ], $items );
				}
				else {
					$this->logs [ mdate ( "%d-%m-%Y %h:%i:%s" ) ] [] = @sprintf ( $this->_logs [ $num ], $items );
				}
			}
			else {
				$this->logs [ mdate ( "%d-%m-%Y %h:%i:%s" ) ] [] = $this->_logs [ $num ];
			}
		}
		return FALSE;
	}
	
	/**
	 * [string or int] vprint ( string $format [, mixed $ary [, bool $return]] )
	 *
	 * Closely mimics the functionality of sprintf(), printf(), vprintf(), and vsprintf().
	 *
	 * Replaces %[bcdeufFosxX] with each element of $ary
	 *     See http://us3.php.net/manual/en/function.sprintf.php for details on replacement types.
	 *
	 * If there are not enough elements in $ary (or it is left out) to satisfy $format,
	 *     it will be padded to the correct length.
	 *
	 * Since v*printf() doesn't mind having too many elements in the array, $format will be left alone.
	 *
	 * If $ary is a string, it will be recast into an array.
	 *
	 * It's buggy when using the argument swapping functionality, unless you do it propperly.
	 *
	 * May break when using modifiers (%.4e, %02s, etc), unless you do it propperly.
	 * http://www.php.net/manual/en/function.sprintf.php#65181
	 * 
	 * @param string $format The string to be modified
	 * @param array $ary The array with replacements
	 * @return string Formatted string
	 **/
	
	function vprint ( $format, $ary = array () )
	{
		// Sanity?!
		if ( ! is_array ( $ary ) ) {
			$ary = array ( $ary );
		}

		// Find %n$n.
		@preg_match_all ( '#\\%[\\d]*\\$[bcdeufFosxX]#', $format, $matches );

		// Weed out the dupes and count how many there are.
		$counts = count ( array_unique ( $matches [ 0 ] ) );
		
		// Count the number of %n's and add it to the number of %n$n's.
		$countf = @preg_match_all ( '#\\%[bcdeufFosxX]#', $format, $matches ) + $counts;
		
		// Count the number of replacements.
		$counta = count ( $ary );
		
		if ( $countf > $counta ) {
			// Pad $ary if there's not enough elements.
			$ary = array_pad ( $ary, $countf, "&nbsp;" );
		}
		
		return vsprintf ( $format, $ary );
	}
	
	function clean_path ( $path, $win = FALSE )
	{
		if ( $win ) {//	windows path
			return rtrim ( trim ( preg_replace ( array ( "@/+@", "@(\\\)+@" ), "\\\\\\", $path ) ), "\\" );
		}
		return rtrim ( trim ( preg_replace ( array ( "/\\\\/", "/\/{2,}/" ), DS, $path ) ), DS );
	}
	
	/**
	 * Performs a check to determine if we're still on time to continue the
	 * process started at $time start
	 *
	 * @param integer $time_start The time when the process started
	 * @return boolean
	 */
	
	function _check_timing ( $time_start )
	{
		if ( time () - $time_start >= $this->allowed_execution_time - 2 ) {
			//	sound the alarm
			return FALSE;
		}
		
		return TRUE;
	}
	
	function is_dir ( $dir )
	{
		@clearstatcache ();
		$isdir = FALSE;

		if ( @is_dir ( $dir ) ) {
			$isdir = TRUE;
		}

		return $isdir;
	}
	
	function is_file ( $file )
	{
		clearstatcache ();
		$isfile = FALSE;
		if ( @file_exists ( $file ) && is_file ( $file ) ) {
			$isfile = TRUE;
		}

		return $isfile;
	}
	
	/**
	 * Provides a safe for saving name of file
	 * 
	 * @param string $name File name
	 * @param string $ext File extension
	 * @return string The safe file name
	 */

	function safe ( $name )
	{
		if ( function_exists ( 'iconv' ) ) {
			$name = @iconv ( "UTF-8", "UTF-8//IGNORE", $name );
		}
		elseif ( function_exists ( 'mb_convert_encoding' ) ) {
			$name = mb_convert_encoding ( $name, "UTF-8", "UTF-8" );
		}

		return preg_replace ( "/(?:[^\s\w\.-]+)/u", "_", $name );
	}

	function get_file_from_path ( $path )
	{
		$pathinfo = @pathinfo ( $path );
		return $pathinfo [ 'basename' ];
	}

	function get_file_name ( $file )
	{
		$filename = WB_file_manager_functions::get_file_from_path ( $file );
		$ext = WB_file_manager_functions::file_extension ( $file, FALSE );
		
		return str_replace ( '.' . $ext, '', $filename );
	}
	
	/**
	 * Returns the File's last modified time
	 *
	 * @param string $file Path to file
	 * @param boolean $check Whether to check or not the resource type before anything else
	 * @return integer
	 */
        
        function last_change ( $file, $check = FALSE )
        {
		if ( $check ) {
			if ( WB_file_manager_functions::is_file ( $file ) ) {
				return filemtime ( $file );
			}
			return FALSE;
		}

		return filemtime ( $file );
        }
	
	/**
	 * Returns the File's last access time
	 *
	 * @param string $file Path to file
	 * @param boolean $check Whether to check or not the resource type before anything else
	 * @return integer
	 */
        
        function last_access ( $file, $check = FALSE )
        {
		if ( $check ) {
			if ( WB_file_manager_functions::is_file ( $file ) ) {
				return fileatime ( $file );
			}
			return FALSE;
		}

		return fileatime ( $file );
        }
        
        /**
	 * Returns the File's owner.
	 *
	 * @param string $file Path to file
	 * @param boolean $check Whether to check or not the resource type before anything else
	 * @return integer the Fileowner
	 */

	function owner ( $file, $check = FALSE )
	{
		if ( $check ) {
			if ( WB_file_manager_functions::is_file ( $file ) ) {
				return fileowner ( $file );
			}
			return FALSE;
		}

		return filegroup ( $file );
	}

	/**
	 * Returns the File group.
	 *
	 * @param string $file Path to file
	 * @param boolean $check Whether to check or not the resource type before anything else
	 * @return integer the Filegroup
	 */

	function group ( $file, $check = FALSE )
	{
		if ( $check ) {
			if ( WB_file_manager_functions::is_file ( $file ) ) {
				return filegroup ( $file );
			}
			return FALSE;
		}
		
		return filegroup ( $file );
	}
	
	/**
	 * Returns true if the File is writable.
	 *
	 * @param string $file Path to file
	 * @return boolean true if its writable, false otherwise
	 * @access public
	 */

	function writable ( $file )
	{
		return is_writable ( $file );
	}

	/**
	 * Returns true if the File is executable.
	 *
	 * @param string $file Path to file
	 * @return boolean true if its executable, false otherwise
	 * @access public
	 */

	function executable ( $file )
	{
		return is_executable ( $file );
	}

	/**
	 * Returns true if the File is readable.
	 *
	 * @param string $file Path to file
	 * @return boolean true if file is readable, false otherwise
	 * @access public
	 */

	function readable ( $file )
	{
		return is_readable ( $file );
	}
	
	function is_text ( $file )
	{
		$kind = WB_file_manager_functions::file_extension ( $file, TRUE );
		
		$kt = explode ( DS, $kind );
		
		if ( is_array ( $kt ) && isset ( $kt [ 0 ] ) && strtolower ( $kt [ 0 ] ) == 'text' ) {
			return TRUE;
		}
		
		return FALSE;
	}
	
	function is_image ( $file )
	{
		$kind = WB_file_manager_functions::file_extension ( $file, TRUE );
		
		$kt = explode ( DS, $kind );
		
		if ( is_array ( $kt ) && isset ( $kt [ 0 ] ) && strtolower ( $kt [ 0 ] ) == 'image' ) {
			return TRUE;
		}
		
		return FALSE;
	}
	
	function is_audio ( $file )
	{
		$kind = WB_file_manager_functions::file_extension ( $file, TRUE );
		
		$kt = explode ( DS, $kind );
		
		if ( is_array ( $kt ) && isset ( $kt [ 0 ] ) && strtolower ( $kt [ 0 ] ) == 'audio' ) {
			return TRUE;
		}
		
		return FALSE;
	}
	
	function is_video ( $file )
	{
		$kind = WB_file_manager_functions::file_extension ( $file, TRUE );
		
		$kt = explode ( DS, $kind );
		
		if ( is_array ( $kt ) && isset ( $kt [ 0 ] ) && strtolower ( $kt [ 0 ] ) == 'video' ) {
			return TRUE;
		}
		
		return FALSE;
	}
	
	function is_application ( $file )
	{
		$kind = WB_file_manager_functions::file_extension ( $file, TRUE );
		
		$kt = explode ( DS, $kind );
		
		if ( is_array ( $kt ) && isset ( $kt [ 0 ] ) && strtolower ( $kt [ 0 ] ) == 'application' ) {
			return TRUE;
		}
		
		return FALSE;
	}
	
	function save_image ( $im, $dest_file )
	{
		$res = NULL;
		// ImageGIF is not included into some GD2 releases, so it might not work
		// output png if gifs are not supported
		if ( ( $this->image_type == 1 )  && ! function_exists ( 'imagegif' ) ) {
			$this->image_type = 3;
		}

		switch ( $this->image_type )
		{
			case 1:
				//	gif files
				if ( $this->image_save_to_file ) {
					$res = ImageGIF ( $im, $dest_file );
				}
				else {
					header ( "Content-type: image/gif" );
					$res = ImageGIF ( $im );
				}
			break;
			case 2:
				//	jpg files
				if ( $this->image_save_to_file ) {
					$res = ImageJPEG ( $im, $dest_file, $this->image_quality );
				}
				else {
					header ( "Content-type: image/jpeg" );
					$res = ImageJPEG ( $im, NULL, $this->image_quality );
				}
			break;
			case 3:
				if ( PHP_VERSION >= '5.1.2' )
				{
					// Convert to PNG quality.
					// PNG quality: 0 (best quality, bigger file) to 9 (worst quality, smaller file)
					$quality = 9 - min ( round ( $this->image_quality / 10 ), 9 );
					if ( $this->image_save_to_file ) {
						$res = ImagePNG ( $im, $dest_file, $quality );
					}
					else {
						header ( "Content-type: image/png" );
						$res = ImagePNG ( $im, NULL, $quality );
					}
				}
				else {
					if ( $this->image_save_to_file ) {
						$res = ImagePNG ( $im, $dest_file );
					}
					else {
						header ( "Content-type: image/png" );
						$res = ImagePNG ( $im );
					}
				}
			break;
		}
		
		return $res;
	}
	
	function img_create_from_type ( $type, $filename )
	{
		$im = NULL;
		switch ( $type )
		{
			case 1:
				$im = ImageCreateFromGif ( $filename );
			break;
			case 2:
				$im = ImageCreateFromJpeg ( $filename );
			break;
			case 3:
				$im = ImageCreateFromPNG ( $filename );
			break;
		}

		return $im;
	}
	
}