<?
class Download {
        var $df_path = "";
        var $df_contenttype = "";
        var $df_contentdisposition = "";
        var $df_filename = "";
        function dwload($df_path, $df_contenttype = "application/zip", $df_contentdisposition = "attachment", $df_filename = "") {
            $this->df_path = $df_path;
            $this->df_contenttype = $df_contenttype;
            $this->df_contentdisposition = $df_contentdisposition;
            $this->df_filename = ($df_filename)? $df_filename : basename($df_path);
        }
        function df_exists() {
            if(file_exists($this->df_path)) return true;
            return false;
        }
        function df_size() {
            if($this->df_exists()) return filesize($this->df_path);
            return false;
        }
        function df_permitother() {
            return substr(decoct(fileperms($this->df_path)),-1);
        }
        function df_download() {
            if($this->df_exists() && $this->df_permitother() >= 4) {
                header("Content-type: ".$this->df_contenttype);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');		
		
		if (strpos($_SERVER["HTTP_USER_AGENT"],'MSIE')!==false)
		{
			header('Content-Disposition: inline; filename="' . $this->df_filename . '"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		} 
		else {
			header('Content-Disposition: attachment; filename="' . $this->df_filename . '"');
			header('Pragma: no-cache');
		}
		
		header("Content-Length: ".$this->df_size());
		

                $fp = readfile($this->df_path, "r");
                return $fp;
            }
            return false;
        }
}
?>