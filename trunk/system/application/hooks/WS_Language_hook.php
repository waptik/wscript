<?php

function prep_lang ()
{
	$CI = &get_instance ();
	$CI->load->helper ( 'file' );
	$uri_string = md5 ( selfUrlClean () );
	$temp_lang_file = TEMP_DIR . "$uri_string.lang";

	if ( ! file_exists ( $temp_lang_file ) ) {
		write_file ( $temp_lang_file, @serialize ( $CI->site_language->used_keys ), 'w' );
	}
}