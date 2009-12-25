<?php

class WS_Profiler extends CI_Profiler {

	private function compile_logs () {
		$CI = &get_instance ();
		$output  = "\n\n";
		$output .= '<fieldset style="border:1px solid #000;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
		$output .= "\n";
		$output .= '<legend style="color:#000;">&nbsp;&nbsp;Debug log&nbsp;&nbsp;</legend>';
		$output .= "\n";
		$output .= '<textarea style="width:99%;height:100px">' . WS_Log::$log . '</textarea>';
		$output .= "</fieldset>";

		return $output;
	}

	public function run () {
		$output = '<br clear="all" />';
		$output .= "<div style='background-color:#fff;padding:10px;'>";
		$output .= $this->_compile_memory_usage ();
		$output .= $this->_compile_benchmarks ();
		$output .= $this->_compile_uri_string ();
		$output .= $this->_compile_get ();
		$output .= $this->_compile_post ();
		$output .= $this->compile_logs ();
		$output .= $this->_compile_queries ();
		$output .= '</div>';
		return $output;
	}

}