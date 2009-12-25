<?php

/**
 * Requirements:
 * 	- PHP > 4.3.0
 */

include 'wb_file_manager_functions.php';

class WB_file_manager extends WB_file_manager_functions {

	/**
	 * Returns an array with all the files and folders inside
	 * a given directory
	 *
	 * @param string $directory Path to directory
	 * @param boolean $recursive Should we go recursive or just list 1st level?
	 * @param boolean $hierarchical Should we output an associative array representing
	 * the hierarchical data as it was found or list a normal array with all files and
	 * folders?
	 * @param mixed $callback Should we apply a callback function to each file or folder found?
	 * @param boolean $only_folders Should we return only folders
	 * @param boolean $only_files Should we return only files
	 * @param array $ignore Array of files or folders to ignore when listing contents
	 * @return array
	 */

	function list_files ( $directory, $recursive = TRUE, $callback = FALSE, $only_folders = FALSE, $only_files = FALSE, $keep_paths = false )
	{
		if ( ! WB_file_manager_functions::is_dir ( $directory ) )
		{//	we can't list files if this is not a directory
			//	store the error and return false, we're done here
			WB_file_manager_functions::_add_error ( 5, array ( $directory ) );
			return FALSE;
		}
		
		//	the directories present in this dir
		$directories = array ();
		//	the files present in this dir
		$files = array ();

		$dir = @opendir ( $directory );
		// try to open the directory
		if ( $dir )
		{
			// Create an array for all files found
			$tmp = array ();

			// Add the files
			while ( false !== ( $file = @readdir ( $dir ) ) )
			{
				if ( $keep_paths ) {
					$current_location = WB_file_manager_functions::clean_path ( $directory . '/' . $file );
				}
				else {
					$current_location = $file;
				}

				// Make sure the file exists
				if ( in_array ( $file, $this->ignore ) )
				{
					WB_file_manager_functions::_add_log ( 0, array ( $current_location ) );
					continue;
				}

				// If it's a directory, list all files within it
				if ( WB_file_manager_functions::is_dir ( $current_location ) )
				{//	this is a directory, we may have to go recursive
					if ( $recursive )
					{
						$tmp2 = WB_file_manager::list_files ( $current_location, TRUE, FALSE );

						if ( is_array ( $tmp2 ) )
						{//	we found something, add it to the final array
							//	add folders
							if ( ! $only_files )
							{
								array_push ( $directories, $current_location );
								$directories = array_merge ( $directories, $tmp2 [ 'directories' ] );
							}

							if ( ! $only_folders )
							{
								$files = array_merge ( $files, $tmp2 [ 'files' ] );
							}

							unset ( $tmp2 );
						}
					}
					else {
						if ( ! $only_files )
						{
							array_push ( $directories, $current_location );
						}
					}
				}
				else {//	it's a file, let's store it and move on
					if ( ! $only_folders )
					{
						array_push ( $files, $current_location );
					}
				}
			}

			//	close the starting directory
			closedir ( $dir );
		}

		$directories = WB_file_manager_functions::apply_callback ( $directories, $callback );
		$files = WB_file_manager_functions::apply_callback ( $files, $callback );

		return array ( 'files' => $files, 'directories' => $directories );
	}

	function search ( $term, $location = array (), $callback = FALSE, $extensions = array (), $size = array (), $date_modified = array (), $mode = array ( 'contents', 'filenames' ) )
	{
		if ( ! is_array ( $location ) || empty ( $location ) )
		{
			$location = array ();
			
			//	no locations added, we'll try to search on the root folder.
			if ( $this->root == '' ) {
				die ( $this->_errors [ 17 ] );
			}
			
			array_push ( $location, $this->root );
		}

		$files = array ();
		$files [ 'files' ] = array ();

		if ( ! is_array ( $location ) || empty ( $location ) ) {//	no location was specified
			$files = WB_file_manager::list_files ( $location, TRUE );
		}
		else {//	we're searching in specific directories
			foreach ( $location as $loc )
			{
				if ( WB_file_manager_functions::is_dir ( $loc ) )
				{
					$_files = WB_file_manager::list_files ( $loc, FALSE );
					$files [ 'files' ] = array_merge ( $files [ 'files' ], $_files [ 'files' ] );
				}
			}
		}

		$found = array ();
		$directories = array ();

		foreach ( $files [ 'files' ] as $file )
		{
			if ( WB_file_manager_functions::is_file ( $file ) )
			{
				$name = WB_file_manager_functions::name ( $file );
				$ext = WB_file_manager_functions::file_extension ( $file );
				$modified = WB_file_manager_functions::last_change ( $file );
				$filesize = WB_file_manager_functions::size ( $file );
	
				//	search extensions if needed
				if ( is_array ( $extensions ) && ! empty ( $extensions ) )
				{
					if ( ! in_array ( $ext, $extensions ) ) {
						continue;
					}
				}

				//	search size if needed
				if ( is_array ( $size ) && ! empty ( $size ) )
				{
					$min = ( isset ( $size [ 0 ] ) ) ? $size [ 0 ] : FALSE;
					$max = ( isset ( $size [ 1 ] ) ) ? $size [ 1 ] : FALSE;

					//	we have a minimum size requirement
					if ( is_numeric ( $min ) ) {
						if ( $filesize < $min ) {//	too small, next...
							continue;
						}
					}

					//	we have a maximum size requirement
					if ( is_numeric ( $max ) ) {
						if ( $filesize > $max ) {//	too big, next...
							continue;
						}
					}
				}
				
				//	search by date modified if needed
				if ( is_array ( $date_modified ) && ! empty ( $date_modified ) )
				{
					$start = $date_modified [ 0 ];
					$end = $date_modified [ 1 ];
	
					//	we have a minimum modification date
					if ( is_numeric ( $start ) ) {
						if ( $modified < $start ) {//	too small, next...
							continue;
						}
					}

					//	we have a maximum modification date
					if ( is_numeric ( $end ) ) {
						if ( $modified > $end ) {//	too big, next...
							continue;
						}
					}
				}
	
				//	lastly..
				if ( $term != '' || $term != FALSE )
				{
					$terms = explode ( ' ', $term );
					
					//	we can't hit continue in the next loop as it won't affect the parent loop
					//	if any of the following become FALSE, we'll hit continue at the end of the loop
					$push_contents = TRUE;
					$push_filenames = TRUE;

					foreach ( $terms as $word )
					{
						//	search contents if needed
						if ( in_array ( 'contents', $mode ) )
						{//	search through contents
							$contents = WB_file_manager_functions::read ( $file );
							if ( mb_strpos ( mb_strtolower ( $contents ), mb_strtolower ( $word ) ) === false ) {
								$push_contents = FALSE;
							}
						}

						//	search file names if needed
						if ( in_array ( 'filenames', $mode ) )
						{//	search through file names
							if ( mb_strpos ( mb_strtolower ( $name ), mb_strtolower ( $word ) ) === false ) {
								$push_filenames = FALSE;
							}
						}
					}

					//	no match, skip this file
					if ( ! $push_filenames || ! $push_contents ) {
						continue;
					}
				}

				//	if we made it this far...
				array_push ( $found, $file );
				array_push ( $directories, WB_file_manager_functions::get_file_location ( $file ) );
			}
		}

		$found = WB_file_manager_functions::apply_callback ( array_unique ( $found ), $callback );
		$directories = WB_file_manager_functions::apply_callback ( array_unique ( $directories ), $callback );

		return array ( 'files' => $found, 'directories' => $directories );
	}
	
	function list_files_hierarchical ( $directory )
	{
		if ( ! WB_file_manager_functions::is_dir ( $directory ) )
		{//	we can't list files if this is not a directory
			//	store the error and return false, we're done here
			WB_file_manager_functions::_add_error ( 5, array ( $directory ) );
			return FALSE;
		}
		
		$out = array ();

		//	store files from the starting dir
		$source = WB_file_manager_functions::list_files ( $directory, FALSE, FALSE, FALSE, TRUE );
		$out [ WB_file_manager_functions::clean_path ( $directory ) ] = $source [ 'files' ];

		//	get all the other directories
		$directories = WB_file_manager_functions::list_files ( $directory, TRUE, FALSE, TRUE, FALSE );

		//	extract files from the other directories
		foreach ( $directories [ 'directories' ] as $dir )
		{
			$files = WB_file_manager_functions::list_files ( $dir, FALSE, FALSE, FALSE, TRUE );
			$out [ WB_file_manager_functions::clean_path ( $dir ) ] = $files [ 'files' ];
		}

		return $out;
	}
	
	/**
	 * Will perform a copy from a given source folder to a destination folder.
	 * It can also be used to move the files.
	 * 
	 * @see @move
	 * @param string $srcdir Source directory
	 * @param string $dstdir Destination directory
	 * @param boolean $overwrite Should we attempt to overwrite files if duplicates are found?
	 * @param boolean $move Should we perform a move instead of copy?
	 * @return boolean
	 */

	function copy ( $srcdir, $dstdir, $overwrite = FALSE )
	{
		$time = time ();
		//	anything to copy?
		if ( ! WB_file_manager_functions::is_dir ( $srcdir ) && ! WB_file_manager_functions::is_file ( $srcdir ) ) {
			return FALSE;
		}

		//	does the destination dir exists? Just a check :P
		if ( ! WB_file_manager_functions::is_dir ( $dstdir ) ) {
			$this->mkdir ( $dstdir, 0777 );
		}
		else {
			WB_file_manager::chmod ( $dstdir );
		}
		
		if ( WB_file_manager_functions::is_dir ( $srcdir ) ) {
			if ( strpos ( $dstdir, $srcdir ) === 0 ) {
				//	we can't perform this operation if the destination dir is a subfolder of the source dir
				$this->_add_error ( 18, array ( 'Copy', $dstdir, $srcdir ) );
			}
			
			//	copy the root source dir
			WB_file_manager::mkdir ( WB_file_manager_functions::clean_path ( $dstdir . DS . basename ( $srcdir ) ), 0777 );
		}
		
		$existing = array ();
		$new_map = array ();

		//	only needed if overwrite is FALSE
		if ( ! $overwrite )
		{
			$dest_files = WB_file_manager::list_files ( $dstdir, FALSE );
			foreach ( $dest_files [ 'files' ] as $file )
			{
				$existing [] = md5 ( $file );
			}
		}

		//	are we copying a file?
		if ( WB_file_manager_functions::is_file ( $srcdir ) )
		{
			$file = WB_file_manager_functions::get_file_from_path ( $srcdir );

			if ( WB_file_manager::_copy_file ( $srcdir, $dstdir . DS . $file, $overwrite, $existing ) ) {
				return TRUE;
			}
		}

		//	get the source structure
		$source_map = WB_file_manager::list_files ( $srcdir, TRUE );

		//	build the directories
		foreach ( $source_map [ 'directories' ] as $dir )
		{
			$get_dir_from_source = str_replace ( $srcdir, '', $dir );
			$dest_dir = WB_file_manager_functions::clean_path ( $dstdir . DS . $get_dir_from_source );
			WB_file_manager::mkdir ( $dest_dir, 0777 );

			if ( ! WB_file_manager_functions::_check_timing ( $time ) ) {
				WB_file_manager_functions::_add_error ( 16, 'Copy' );
				return TRUE;
			}
		}

		//	copy the files
		foreach ( $source_map [ 'files' ] as $file )
		{
			$get_dir_from_source = str_replace ( dirname ( $srcdir ), '', WB_file_manager_functions::get_file_location ( $file ) );
			$dest_dir = WB_file_manager_functions::clean_path ( $dstdir . DS . $get_dir_from_source );
			//	making sure the destination dir exists
			$this->mkdir ( $dest_dir, 0777 );

			WB_file_manager::_copy_file
			(
				$file,//	copy what?
				$dest_dir . DS . WB_file_manager_functions::get_file_from_path ( $file ),//	copy where?
				$overwrite,//	overwrite if duplicate?
				$existing//	existing files in the destination folder (md5's of their full path)
			);

			if ( ! WB_file_manager_functions::_check_timing ( $time ) ) {
				WB_file_manager_functions::_add_error ( 16, 'Copy' );
				return TRUE;
			}
		}

		unset ( $existing );
		unset ( $new_map );
		unset ( $source_map );

		return TRUE;
	}
	
	/**
	 * Will copy all files and folders from an array (full paths needed).
	 * The function also removes files and folders which are located within
	 * a present folder from the array.
	 * 
	 * @param array $items The array with files and folders to copy
	 * @param string $dstdir The destination directory
	 * @param boolean $overwrite Should we overwrite the existing files from the destination dir?
	 */
	
	function copy_by_array ( $items, $dstdir, $overwrite = FALSE )
	{
		$files_st = array ();
		$folders_st = array ();
		
		$dstdir = WB_file_manager_functions::clean_path ( $dstdir );
		
		foreach ( $items as $item )
		{
			if ( WB_file_manager_functions::is_file ( $item ) ) {
				array_push ( $files_st, $item );
			}
			elseif ( WB_file_manager_functions::is_dir ( $item ) ) {
				array_push ( $folders_st, WB_file_manager_functions::clean_path ( $item ) );
			}
		}

		//	clean the folders that are located in other "to copy" folders
		$folders = WB_file_manager_functions::clean_child_folders ( $folders_st );
		
		$diff_folders = array_diff ( $folders_st, $folders );
		
		if ( is_array ( $diff_folders ) ) {
			foreach ( $diff_folders as $diff_folder ) {
				$this->_add_log ( 3, $diff_folder );
			}
		}
		
		//	clean the files that are located in the "to copy" folders
		$files = WB_file_manager_functions::clean_child_files ( $files_st, $folders );
		
		$diff_files = array_diff ( $files_st, $files );
		
		if ( is_array ( $diff_files ) ) {
			foreach ( $diff_files as $diff_file ) {
				$this->_add_log ( 3, $diff_file );
			}
		}

		//	start copying the files
		if ( is_array ( $files ) && ! empty ( $files ) )
		{
			foreach ( $files as $file )
			{
				$file_loc = WB_file_manager_functions::clean_path ( WB_file_manager_functions::get_file_location ( $file ) );
				if ( $file_loc != $dstdir )
				{
					if ( WB_file_manager::copy ( $file, $dstdir ) ) {
						$this->_add_log ( 4, array ( $file, $file_loc, $dstdir ) );
					}
				}
			}
		}

		//	now with the folders
		if ( is_array ( $folders ) && ! empty ( $folders ) )
		{
			foreach ( $folders as $folder )
			{
				if ( WB_file_manager::copy ( $folder, $dstdir ) ) {
					$this->_add_log ( 4, array ( $folder, realpath ( dirname ( $folder ) ), $dstdir ) );
				}
			}
		}
		
		return TRUE;
	}
	
	function move_by_array ( $items, $dstdir, $overwrite = FALSE )
	{
		$files_st = array ();
		$folders_st = array ();
		
		$dstdir = WB_file_manager_functions::clean_path ( $dstdir );
		
		foreach ( $items as $item )
		{
			if ( WB_file_manager_functions::is_file ( $item ) ) {
				array_push ( $files_st, $item );
			}
			elseif ( WB_file_manager_functions::is_dir ( $item ) ) {
				array_push ( $folders_st, WB_file_manager_functions::clean_path ( $item ) );
			}
		}

		//	clean the folders that are located in other "to move" folders
		$folders = WB_file_manager_functions::clean_child_folders ( $folders_st );
		
		$diff_folders = array_diff ( $folders_st, $folders );
		
		if ( is_array ( $diff_folders ) ) {
			foreach ( $diff_folders as $diff_folder ) {
				$this->_add_log ( 3, $diff_folder );
			}
		}
		
		//	clean the files that are located in the "to move" folders
		$files = WB_file_manager_functions::clean_child_files ( $files_st, $folders );
		
		$diff_files = array_diff ( $files_st, $files );
		
		if ( is_array ( $diff_files ) ) {
			foreach ( $diff_files as $diff_file ) {
				$this->_add_log ( 3, $diff_file );
			}
		}

		//	start moving the files
		if ( is_array ( $files ) && ! empty ( $files ) )
		{
			foreach ( $files as $file )
			{
				$file_loc = WB_file_manager_functions::clean_path ( WB_file_manager_functions::get_file_location ( $file ) );
				if ( $file_loc != $dstdir )
				{
					if ( WB_file_manager::move ( $file, $dstdir ) ) {
						$this->_add_log ( 5, array ( $file, $file_loc, $dstdir ) );
					}
				}
			}
		}

		//	now with the folders
		if ( is_array ( $folders ) && ! empty ( $folders ) )
		{
			foreach ( $folders as $folder )
			{
				if ( WB_file_manager::move ( $folder, $dstdir ) ) {
					$this->_add_log ( 5, array ( $folder, realpath ( dirname ( $folder ) ), $dstdir ) );
				}
			}
		}
		
		return TRUE;
	}
	
	function delete_by_array ( $items )
	{
		$files_st = array ();
		$folders_st = array ();
		
		foreach ( $items as $item )
		{
			if ( WB_file_manager_functions::is_file ( $item ) ) {
				array_push ( $files_st, $item );
			}
			elseif ( WB_file_manager_functions::is_dir ( $item ) ) {
				array_push ( $folders_st, WB_file_manager_functions::clean_path ( $item ) );
			}
		}

		//	clean the folders that are located in other "to delete" folders
		$folders = WB_file_manager_functions::clean_child_folders ( $folders_st );
		
		//	clean the files that are located in the "to delete" folders
		$files = WB_file_manager_functions::clean_child_files ( $files_st, $folders );

		//	start deleting the files
		if ( is_array ( $files ) && ! empty ( $files ) )
		{
			foreach ( $files as $file )
			{
				if ( WB_file_manager::delete ( $file ) ) {
					$this->_add_log ( 6, $file );
				}
			}
		}

		//	now with the folders
		if ( is_array ( $folders ) && ! empty ( $folders ) )
		{
			foreach ( $folders as $folder )
			{
				if ( WB_file_manager::delete ( $folder ) ) {
					$this->_add_log ( 6, $folder );
				}
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Will move files from a source dir to a destination dir.
	 * 
	 * @uses @copy
	 * @param string $srcdir Source directory
	 * @param string $dstdir Destination directory
	 * @param boolean $overwrite Should we attempt to overwrite files if duplicates are found?
	 * @return boolean
	 */
	
	function move ( $srcdir, $dstdir, $overwrite = FALSE )
	{
		//	trigger the copy function with instructions
		//	to clean the source after finishing
		if ( WB_file_manager::copy ( $srcdir, $dstdir, $overwrite ) )
		{
			if ( WB_file_manager::delete ( $srcdir, TRUE ) ) {
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Will copy a file to a given location
	 * 
	 * @param string $src_file Path to file we're about to copy
	 * @param string $dest_file Path to the new file
	 * @param boolean $overwrite Should overwrite if duplicates found?
	 * @param array $existing_files Array with existing files in the destination folder.
	 * I could have done this inside here but it's taking too long in big loops.
	 */
	
	function _copy_file ( $src_file, $dest_file, $overwrite = FALSE, $existing_files_md5 = array () )
	{
		//	do we have anything to copy?
		if ( ! WB_file_manager_functions::is_file ( $src_file ) )
		{
			return FALSE;
		}
		
		$src_file = WB_file_manager_functions::clean_path ( $src_file );
		$dest_file = WB_file_manager_functions::clean_path ( $dest_file );

		$dest_dir = WB_file_manager_functions::get_file_location ( $dest_file );
		
		//	if the file exists on the destination folder
		//	and we're not allowed to overwrite
		if ( WB_file_manager_functions::is_file ( $dest_file ) && ! $overwrite )
		{
			$new_file_name = WB_file_manager_functions::get_unique_name ( $dest_file, $existing_files_md5 );

			//	did we failed to provide a new name for this file?
			if ( ! $new_file_name ) {
				WB_file_manager_functions::_add_error ( 15, $dest_file );
				return FALSE;
			}

			$dest_file = WB_file_manager_functions::clean_path ( $dest_dir . DS . $new_file_name );
		}
		//	if the file exists on the destination folder
		//	and we're allowed to overwrite let's delete the existing file
		elseif ( WB_file_manager_functions::is_file ( $dest_file ) && $overwrite  )
		{
			if ( ! $this->delete ( $dest_file ) )
			{
				WB_file_manager_functions::_add_error ( 10, $dest_file );
				return FALSE;
			}
		}

		//	if the file doesn't 'exists on the destination folder
		if ( @copy ( $src_file, $dest_file ) )
		{
			$this->_add_log ( 4, array ( $src_file, $src_file, $dest_file ) );
			//	Set access and modification time
			@touch ( $dest_file, @filemtime ( $src_file ) );
			WB_file_manager::chmod ( $dest_file );

			return TRUE;
		}
		else {
			//	copy failed, bad dog!
			WB_file_manager_functions::_add_error ( 14, $src_file );
			return FALSE;
		}
	
		return TRUE;
	}
	
	/**
	 * Creates directories
	 * 
	 * @param string $dir_name Path to the new folder
	 * @param integer $rights The folder permissions
	 * @return boolean
	 */
	
	function mkdir ( $dir_name, $rights = 0777 )
	{
		//	we have this dir already
		if ( WB_file_manager_functions::is_dir ( $dir_name ) )
		{
			//	if we're here than it must mean we're about
			//	to write to this folder so let's attempt to
			//	change it's rights according to our param
			$this->chmod ( $dir_name, $rights );
			return TRUE;
		}

		$dirName = WB_file_manager_functions::clean_path ( $dir_name );
		$dirs = explode ( DS, $dir_name );
		$dir='';
		foreach ( $dirs as $part )
		{
			$dir .= $this->add_end_slash ( $part );
			if ( ! WB_file_manager_functions::is_dir ( $dir ) && strlen ( $dir ) > 0 ) {
				@mkdir ( $dir, $rights );
			}
		}

		return TRUE;
	}


	/**
	 * Change the mode on a directory structure recursively. This includes changing the mode on files as well.
	 *
	 * @param string $path The path to chmod
	 * @param integer $mode octal value 0755
	 * @param boolean $recursive chmod recursively
	 * @param array $exceptions array of files, directories to skip
	 * @return boolean Returns TRUE on success, FALSE on failure
	 * @access public
	 */

	function chmod ( $path, $mode = FALSE, $recursive = TRUE, $exceptions = array () )
	{
		if ( ! $mode ) {
			if ( WB_file_manager_functions::is_dir ( $path ) ) {
				$mode = $this->folder_perm;
			}
			else {
				$mode = $this->file_perms;
			}
		}

		if ( $recursive === FALSE && WB_file_manager_functions::is_dir ( $path ) )
		{
			if ( @chmod ( $path, intval ( $mode, 8 ) ) )
			{
				WB_file_manager_functions::_add_log ( 1, $path, $mode );
				return TRUE;
			}

			WB_file_manager_functions::_add_error ( 13, $path, $mode );
			return FALSE;
		}

		if ( WB_file_manager_functions::is_dir ( $path ) )
		{
			$paths = WB_file_manager::list_files ( $path );

			foreach ( $paths as $type )
			{
				foreach ( $type as $key => $fullpath )
				{
					$check = explode ( DS, $fullpath );
					$count = count ( $check );

					if ( in_array ( $check [ $count - 1 ], $exceptions ) )
					{
						continue;
					}

					if ( @chmod ( $fullpath, intval ( $mode, 8 ) ) )
					{
						WB_file_manager_functions::_add_log ( 1, $fullpath, $mode );
					}
					else {
						WB_file_manager_functions::_add_error ( 13, $fullpath, $mode );
					}
				}
			}

			if ( empty ( $this->_errors ) ) {
				return TRUE;
			}
		}
		else {
			if ( @chmod ( $path, intval ( $mode, 8 ) ) )
			{
				WB_file_manager_functions::_add_log ( 1, $path, $mode );
				return TRUE;
			}
		}
		return FALSE;
	}
	
	function read ( $file, $mode = 'rb', $bytes = false, $force = false )
	{
		if ( ! WB_file_manager_functions::readable ( $file ) ) {
			return FALSE;
		}

		if ( $bytes === FALSE ) {
			return file_get_contents ( $file );
		}
		elseif ( $handle = @fopen ( $file, $mode ) )
		{
			if ( is_int ( $bytes ) )
			{
				return fread ( $handle, $bytes );
			}
			else {
				$data = '';
				while ( ! feof ( $handle ) ) {
					$data .= fgets ( $handle, 4096 );
				}
				return trim ( $data );
			}
		}

		return FALSE;
	}
	
	function write ( $file, $data, $mode = 'w', $length = FALSE )
	{
		if ( ! WB_file_manager_functions::writable ( $file ) ) {
			return FALSE;
		}

		$success = FALSE;

		$fh = fopen ( $file , $mode );

		$success = ( $length ) ? fwrite ( $fh, $data, $length ) : fwrite ( $fh, $data );

		fclose ( $fh );

		return $success;
	}

	/**
	 * Deletes files and/or folders from a given location
	 * 
	 * @param string $item Path to the item we're deleting
	 * @param boolean $deleteMe If we're deleting a folder's contents, setting
	 * this to TRUE will also delete the folder.
	 * 
	 * @return boolean
	 */

	function delete ( $item, $deleteMe = TRUE )
	{
		WB_file_manager::chmod ( $item );

		if ( WB_file_manager_functions::is_file ( $item ) )
		{
			if ( ! @unlink ( $item ) ) {
				WB_file_manager_functions::_add_error ( 10, $item );
				return FALSE;
			}
		}

		//	if it's a directory let's try to open it
		if ( WB_file_manager_functions::is_dir ( $item ) )
		{
			//	atore the contents in a variable to perform the loop
			$contents = WB_file_manager::list_files ( $item );
			
			//	if it's empty let's delete it, we're done here
			if ( empty ( $contents [ 'files' ] ) AND empty ( $contents [ 'directories' ] ) ) {
				if ( ! @rmdir ( $item ) ) {
					WB_file_manager_functions::_add_error ( 10, $item );
					return FALSE;
				}
				return TRUE;
			}

			//	delete the files first
			foreach ( $contents [ 'files' ] as $file )
			{
				WB_file_manager::delete ( $file );
			}
			
			//	delete the folders
			foreach ( $contents [ 'directories' ] as $file )
			{
				WB_file_manager::delete ( $file );
			}
			
			if ( $deleteMe ) {
				if ( ! @rmdir ( $item ) ) {
					WB_file_manager_functions::_add_error ( 10, $item );
					return FALSE;
				}
				return TRUE;
			}
		}

		return TRUE;
	}
	
	function create ( $file )
	{
		if ( WB_file_manager_functions::is_dir ( dirname ( $file ) ) && WB_file_manager_functions::writable ( dirname ( $file ) ) && ! WB_file_manager_functions::is_file ( $file ) )
		{
			if ( @touch ( $file ) ) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * Uploads file to server
	 * 
	 * @param string $field The field name
	 * @param string $dirPath Upload destination folder
	 * 
	 * @return boolean
	 */
	
	function upload ( $field = '', $dstdir = '', $overwrite = FALSE )
	{
		if ( ! isset ( $_FILES [ $field ] ) ) {
			$this->_add_error ( 22 );
			return FALSE;
		}

		foreach ( $_FILES [ $field ] as $key => $val ) {
			$$key = $val;
		}
		
		$file_type = @preg_replace ( "/^(.+?);.*$/", "\\1", $type );

		if ( $size > $this->max_upload_size && $this->max_upload_size != 0 ) {
			$this->_add_error ( 26, $tmp_name );
			return FALSE;
		}

		if ( $size == 0 ) {
			$this->_add_error ( 27, $tmp_name );
			return FALSE;
		}

		if ( ( ! is_uploaded_file ( $tmp_name ) ) || ( $error != 0 ) ) {
			// Was the file able to be uploaded? If not, determine the reason why.
			$error = ( ! isset ( $error ) ) ? 4 : $error;

			switch ( $error )
			{
				case 1:	// UPLOAD_ERR_INI_SIZE
					$this->_add_error ( 19, $name );
					break;
				case 2: // UPLOAD_ERR_FORM_SIZE
					$this->_add_error ( 20, $name );
					break;
				case 3: // UPLOAD_ERR_PARTIAL
					$this->_add_error ( 21, $name );
					break;
				case 4: // UPLOAD_ERR_NO_FILE
					$this->_add_error ( 22, $name );
					break;
				case 6: // UPLOAD_ERR_NO_TMP_DIR
					$this->_add_error ( 23, $name );
					break;
				case 7: // UPLOAD_ERR_CANT_WRITE
					$this->_add_error ( 24, array ( $name, $dirPath ) );
					break;
				case 8: // UPLOAD_ERR_EXTENSION
					$this->_add_error ( 25, $name );
					break;
			}

			return FALSE;
		}

		if ( ( is_array ( $this->allowed_upload ) ) && ( ! empty ( $this->allowed_upload ) ) )
		{
			include ( ROOTPATH . '/various/settings/mimes.php' );

			if ( ! in_array ( $file_type, array_values_recursive ( $mimes ) ) ) {
				$this->_add_error ( 30, array ( $file_type ) );
				return FALSE;	// file is not an allowed type
			}
		}

		$existing = array ();
		
		//	only needed if overwrite is FALSE
		if ( ! $overwrite )
		{
			$dest_files = WB_file_manager::list_files ( $dstdir, FALSE );
			foreach ( $dest_files [ 'files' ] as $file )
			{
				$existing [] = md5 ( $file );
			}
		}
		
		$dummy = $dstdir . DS . $name;

		$new_file = $dstdir . DS . WB_file_manager_functions::get_unique_name ( $dummy, $existing );

		if ( ! @copy ( $tmp_name, $new_file ) )
		{
			if ( ! @move_uploaded_file ( $tmp_name, $new_file ) )
			{
				 $this->_add_error ( 24, array ( $tmp_name, $dstdir ) );
				 return FALSE;
			}
		}

		return $new_file;
	}
	
	/**
	 * Downloads files, also hides the location. You can limit the download speed
	 * 
	 * @param string $file Path to file
	 * @param boolean $resume_on Should we allow download resuming
	 * @param integer $max_speed Max speed of download (kb). 0 = no limit
	 * @return mixed
	 */

	function download ( $file, $resume_on = TRUE, $max_speed = 0, $new_name = FALSE )
	{
		if ( ! WB_file_manager_functions::is_file ( $file ) ) {
			return FALSE;
		}

		$size = @filesize ( $file );
		$name = WB_file_manager_functions::get_file_from_path ( $file );
		$type = WB_file_manager_functions::file_extension ( $file, TRUE );

		// if resuming is allowed ...
		if ( $resume_on )
		{
			if ( isset ( $_SERVER [ 'HTTP_RANGE' ] ) )
			{// check if http_range is sent by browser (or download manager)
				list ( $a, $range ) = explode ( "=", $_SERVER [ 'HTTP_RANGE' ] );  
				ereg ( "([0-9]+)-([0-9]*)/?([0-9]*)", $range, $range_parts );	// parsing Range header
				$byte_from = $range_parts [ 1 ];     			// the download range : from $byte_from ...
				$byte_to = $range_parts [ 2 ];       			// ... to $byte_to 
			} 
			else {
				if ( isset ( $_ENV [ 'HTTP_RANGE' ] ) )
				{// some web servers do use the $_ENV['HTTP_RANGE'] instead
					list ( $a, $range ) = explode ( "=", $_ENV [ 'HTTP_RANGE' ] );
					ereg ( "([0-9]+)-([0-9]*)/?([0-9]*)", $range, $range_parts );	// parsing Range header
					$byte_from = $range_parts [ 1 ];     		// the download range : from $byte_from ...
					$byte_to = $range_parts [ 2 ];			// ... to $byte_to 
				}
				else {
					$byte_from = 0;					// if no range header is found, download the whole file from byte 0 ...
					$byte_to = $size - 1;				// ... to the last byte
				}
				if ( $byte_to == "" ) {// if the end byte is not specified, ...
					$byte_to = $size - 1;    			// ... set it to the last byte of the file
					header ( "HTTP/1.1 206 Partial Content" );	// send the partial content header
				}	
			}
		}
		else {// ... else, download the whole file
			$byte_from = 0;
			$byte_to = $size - 1;
		}
		
		$download_range = $byte_from . "-" . $byte_to . "/" . $size;		// the download range
		$download_size = $byte_to - $byte_from;					// the download length
		
		// download speed limitation
		if ( ( $speed = $max_speed ) > 0 )
		{// determine the max speed allowed ...
			$sleep_time = ( 8 / $speed ) * 1e6;				// ... if "max_speed" = 0 then no limit (default)
		}
		else {
			$sleep_time = 0;
		}
		
		// Fix IE bug [0]
		if ( strstr ( $_SERVER [ 'HTTP_USER_AGENT' ], 'MSIE' ) && ! $new_name ){
			$name = preg_replace ( '/\./', '%2e', $name, substr_count ( $name, '.' ) - 1 );
		}
		else {
			$name = $new_name;
		}

		// send the headers    
		header ( "Pragma: public" );						// purge the browser cache
		header ( "Cache-Control: max-age=0" );
		header ( "Content-Description: File Transfer" );
		header ( "Content-Type: " . $type );					// file type
		header ( 'Content-Disposition: attachment; filename="' . $name . '";' );
		header ( "Content-Transfer-Encoding: binary" );				// transfer method
		header ( "Content-Range: $download_range" );				// download range
		header ( "Content-Length: $download_size" );				// download length

		// send the file content        
		$fp = fopen ( $file,"rb" );			// open the file 
		fseek ( $fp, $byte_from );			// seek to start of missing part   
		while ( ! feof ( $fp ) )
		{// start buffered download  
			set_time_limit ( 0 );			// reset time limit for big files (has no effect if php is executed in safe mode)
			print ( fread ( $fp, 1024*8 ) );	// send 8ko 
			flush ();
			usleep ( $sleep_time );			// sleep (for speed limitation)
		}

		fclose ( $fp );					// close the file
		return TRUE;
	}
	
	// generate thumb from image and save it
	function generate_thumb ( $image )
	{
		//	thumbs dir
		if ( ! WB_file_manager_functions::is_dir ( $this->image_thumbs_dir ) && $this->image_thumbs_dir != NULL ) {
			WB_file_manager::mkdir ( $this->image_thumbs_dir );
		}

		// check if file exists
		if ( ! WB_file_manager::is_file ( $image ) ) {
			$this->_add_error ( 4, $image );
			return FALSE;
		}
		
		$file_name = WB_file_manager_functions::get_file_from_path ( $image );
		$dest_file = WB_file_manager_functions::clean_path ( $this->image_thumbs_dir . DS . $this->image_thumb_prefix . $file_name );
		
		// if src is URL then download file first
		$temp = FALSE;
		if ( substr ( $image, 0, 7 ) == 'http://' || substr ( $image, 0, 8 ) == 'https://' )
		{
			$tmpfname = tempnam ( "tmp/", "TmP-" );
			if ( WB_file_manager::write ( $tmpfname, @file_get_contents ( $from_name ) ) ) {
				$image = $tmpfname;
			}
			else {
				$this->_add_error ( 28 );
				return FALSE;
			}
		}
		
		// get source image size (width/height/type)
		// orig_img_type 1 = GIF, 2 = JPG, 3 = PNG
		list ( $orig_x, $orig_y, $orig_img_type, $img_sizes ) = GetImageSize ( $image );

		
		// should we override thumb image type?
		$this->image_type = ( $this->image_type != -1 ) ? $this->image_type : $orig_img_type;
		
		// check for allowed image types
		if ( $orig_img_type < 1 or $orig_img_type > 3 ) {
			$this->_add_error ( 29, $orig_img_type );
		}

		if ( $orig_x > $this->image_max_x or $orig_y > $this->image_max_y )
		{
			// resize
			$per_x = $orig_x / $this->image_max_x;
			$per_y = $orig_y / $this->image_max_y;

			if ( $per_y > $per_x ) {
				$this->image_max_x = $orig_x / $per_y;
			}
			else {
				$this->image_max_y = $orig_y / $per_x;
			}
		}
		else {
			// keep original sizes, i.e. just copy
			if ( $this->image_save_to_file ) {
				if ( WB_file_manager::_copy_file ( $image, $dest_file, TRUE ) )
				{
					return $dest_file;
				}
			}
			else {
				switch ( $this->image_type )
				{
					case 1:
						header ( "Content-type: image/gif" );
						return readfile ( $image );
					break;
					case 2:
						header ( "Content-type: image/jpeg");
						return readfile ( $image );
					break;
					case 3:
						header ( "Content-type: image/png" );
						return readfile ( $image );
					break;
				}
			}
		}

		if ( $this->image_type == 1 )
		{
			// should use this function for gifs (gifs are palette images)
			$ni = imagecreate ( $this->image_max_x, $this->image_max_y );
		}
		else {
			// Create a new true color image
			$ni = ImageCreateTrueColor ( $this->image_max_x, $this->image_max_y );
		}

		// Fill image with white background (255,255,255)
		$white = imagecolorallocate ( $ni, 255, 255, 255 );
		imagefilledrectangle ( $ni, 0, 0, $this->image_max_x, $this->image_max_y, $white );
		// Create a new image from source file
		$im = $this->img_create_from_type ( $orig_img_type, $image );
		// Copy the palette from one image to another
		imagepalettecopy ( $ni, $im );
		// Copy and resize part of an image with resampling
		imagecopyresampled
		(
			$ni, $im,			// destination, source
			0, 0, 0, 0,			// dstX, dstY, srcX, srcY
			$this->image_max_x, $this->image_max_y,	// dstW, dstH
			$orig_x, $orig_y		// srcW, srcH
		);    
		
		if ( $temp ) {
			WB_file_manager::delete ( $tmpfname );	// this removes the temp file
		}

		return $this->save_image ( $ni, $dest_file );
	}
	
	function CreateThumbnail ( $sourcefile, $destdir )
	{
		$new_file_name = WB_file_manager_functions::get_file_from_path ( $sourcefile );
		exec ( "convert \"" . $sourcefile . "\" -colorspace RGB $destdir/". $new_file_name . "");
		$patterns = array ('/[^a-z0-9\.]/i','/pdf/','/PDF/');
		$replace = array ('_','jpg','jpg');
		$new_file_name_image = preg_replace ( $patterns, $replace, $new_file_name );
		exec ( "convert $destdir/$new_file_name -thumbnail '150x150' $destdir/.thumbs/viewer_150x150_$new_file_name_image");
	}

}