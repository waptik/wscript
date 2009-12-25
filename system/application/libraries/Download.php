<?
class Download {
	var $df_path = "";
	var $df_contenttype = "";
	var $df_contentdisposition = "";
	var $df_filename = "";
	function dwload($df_path, $df_contenttype = "image/jpeg", $df_contentdisposition = "attachment", $df_filename = "")
	{
		$this->file_type = preg_replace("/^(.+?);.*$/", "\\1", file_extension ( $df_path ) );
		$this->df_path = $df_path;
		$this->df_contenttype = ( $this->mimes_types ( $this->file_type ) != FALSE ) ? $this->mimes_types ( $this->file_type ) : $df_contenttype;
		$this->df_contentdisposition = $df_contentdisposition;
		$this->df_filename = ( $df_filename ) ? $df_filename : basename ( $df_path );
	}
	function mimes_types ( $mime )
	{
		if ( @include ( APPPATH . 'config/mimes' . EXT ) )
		{
			$this->mimes = $mimes;
			unset ( $mimes );
		}
	
		return ( ! isset ( $this->mimes [ $mime ] ) ) ? FALSE : ( is_array ( $this->mimes [ $mime ] ) ) ? $this->mimes [ $mime ] [ 0 ] : $this->mimes [ $mime ];
	}
	function df_exists()
	{
		if ( file_exists ( $this->df_path ) ) return true;
		return false;
	}
	function df_size()
	{
		if($this->df_exists()) return filesize($this->df_path);
		return false;
	}
	function df_permitother()
	{
		return substr(decoct(fileperms($this->df_path)),-1);
	}
	function df_download()
	{
		if($this->df_exists() && $this->df_permitother() >= 4) {
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: private");
			header("Content-type: ".$this->df_contenttype);
			header("Content-Disposition: ".$this->df_contentdisposition."; filename=\"".$this->df_filename."\"");
			header("Content-Length: ".$this->df_size());
			
			$fp = readfile($this->df_path, "r");
			return $fp;
		}
		return false;
	}
}
//END