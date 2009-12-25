<?php

class Sitemap extends Controller {
	
	function __construct ()
	{
		parent::Controller ();
		$this->load->model ( 'msitemap' );
		$this->load->model ( 'mcategories' );
		$this->load->model ( 'mwallpaper' );
		$this->load->helper ( 'sitemap' );
	}

	function index ()
	{
		header ( "Content-type: text/xml" );
		$data [ 'categories' ] = $this->mcategories->getCatsForSitemap ();
		die ( $this->load->view ( 'sitemap_index', $data, true ) );
	}

	function show_cat ()
	{
		header ( "Content-type: text/xml" );
		$data [ 'wallpapers' ] = $this->mwallpaper->sitemap_get_wallpapers_by_category ( $this->uri->segment ( 3 ) );
		die ( $this->load->view ( 'sitemap', $data, true ) );
	}

	function submit ()
	{
		$url = urlencode ( site_url ( 'sitemap' ) );
	
		$engines [] = "http://api.moreover.com/ping?u=" . $url;
		$engines [] = "http://submissions.ask.com/ping?sitemap=" . $url;
		$engines [] = "http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=YahooDemo&url=" . $url;
		$engines [] = "http://www.google.com/webmasters/sitemaps/ping?sitemap=" . $url;
		
		$data = '';
	
		for ( $x = 0; $x < count ( $engines ); $x++ )
		{
			$ch = curl_init ( $engines [ $x ] );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_HEADER, 1 );
			curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
			curl_setopt ( $ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)" );
			$data .= curl_exec ( $ch );
			curl_close ( $ch );
		}

		$page = array
		(
			'page_title'		=> Lang ( 'submit_sitemap' ),
			'styles'		=> get_page_css ( 'sitemap' ),
			'javascript'		=> get_page_js ( 'sitemap' ),
			'message'		=> evaluate_response ( 'ok|' . Lang ( 'sitemap_submitted' ) ),
			'contact_users_form'	=> ''
		);
		
		echo load_template ( $page, 'contact_users' );die ();
	}
}

//END