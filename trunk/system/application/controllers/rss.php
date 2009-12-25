<?php

class Rss extends Controller {
	
	function rss() {
		parent::Controller ();
		$this->load->model ( 'mcategories' );
		$this->load->model ( 'mwallpaper' );
		$this->load->helper ( 'xml' );
	}

	function member () {
		$mem_id = $this->uri->segment ( 3 );
		$username = get_username ( $mem_id );
		$data ['encoding'] = 'utf-8';
		$data ['feed_name'] = Lang ( 'rss_feed_for' ) . ' ' . $username;
		$data ['feed_url'] = site_url ( 'members/show/' . $mem_id );
		$data ['page_description'] = SITE_NAME . ': ' . SITE_SLOGAN;
		$data ['page_language'] = 'en-ca';
		$data ['creator_email'] = ADMIN_EMAIL;
		$data ['wallpapers'] = $this->mwallpaper->rss_get_wallpapers_by_member ( $mem_id );
		header ( "Content-Type: application/rss+xml" );
		die ( $this->load->view ( 'rss', $data, TRUE ) );
	}

	function welcome () {
		$data ['encoding'] = 'utf-8';
		$data ['feed_name'] = SITE_SLOGAN;
		$data ['feed_url'] = site_url ();
		$data ['page_description'] = SITE_NAME . ': ' . SITE_SLOGAN;
		$data ['page_language'] = 'en-ca';
		$data ['creator_email'] = ADMIN_EMAIL;
		$data ['wallpapers'] = $this->mwallpaper->rss_get_wallpapers_welcome ();
		header ( "Content-Type: application/rss+xml" );
		die ( $this->load->view ( 'rss', $data, TRUE ) );
	}

	function latest () {
		$data ['encoding'] = 'utf-8';
		$data ['feed_name'] = SITE_SLOGAN . ' ' . Lang ( 'latest_wallpapers' );
		$data ['feed_url'] = site_url ( 'welcome/latest' );
		$data ['page_description'] = SITE_NAME . ': ' . SITE_SLOGAN;
		$data ['page_language'] = 'en-ca';
		$data ['creator_email'] = ADMIN_EMAIL;
		$data ['wallpapers'] = $this->mwallpaper->rss_get_wallpapers_latest ();
		header ( "Content-Type: application/rss+xml" );
		die ( $this->load->view ( 'rss', $data, TRUE ) );
	}
	
	function type () {
		$type = $this->uri->segment ( 3 );
		$data ['encoding'] = 'utf-8';
		$data ['feed_name'] = Lang ( $type . '_wallpapers' );
		$data ['feed_url'] = site_url ( 'welcome/latest' );
		$data ['page_description'] = SITE_NAME . ': ' . SITE_SLOGAN;
		$data ['page_language'] = 'en-ca';
		$data ['creator_email'] = ADMIN_EMAIL;
		$data ['wallpapers'] = $this->mwallpaper->rss_get_wallpapers_type ( $type );
		header ( "Content-Type: application/rss+xml" );
		die ( $this->load->view ( 'rss', $data, TRUE ) );
	}
	
	function top () {
		$data ['encoding'] = 'utf-8';
		$data ['feed_name'] = SITE_SLOGAN . ' ' . Lang ( 'latest_wallpapers' );
		$data ['feed_url'] = site_url ( 'welcome/latest' );
		$data ['page_description'] = SITE_NAME . ': ' . SITE_SLOGAN;
		$data ['page_language'] = 'en-ca';
		$data ['creator_email'] = ADMIN_EMAIL;
		$data ['wallpapers'] = $this->mwallpaper->rss_get_wallpapers_top ();
		header ( "Content-Type: application/rss+xml" );
		die ( $this->load->view ( 'rss', $data, TRUE ) );
	}

	function cat () {
		$cat = $this->uri->segment ( 3 );
		$category = get_category ( $cat );
		$data ['encoding'] = 'utf-8';
		$data ['feed_name'] = $category->title;
		$data ['feed_url'] = get_category_url ( $cat );
		$data ['page_description'] = SITE_SLOGAN;
		$data ['page_language'] = 'en-ca';
		$data ['creator_email'] = ADMIN_EMAIL;
		$data ['wallpapers'] = $this->mwallpaper->rss_get_wallpapers_by_category ( $cat );
		header ( "Content-Type: application/rss+xml" );
		die ( $this->load->view ( 'rss', $data, TRUE ) );
	}

	function tag () {
		$tag = $this->uri->segment ( 3 );
		$data ['encoding'] = 'utf-8';
		$data ['feed_name'] = urldecode ( $tag ) . ' ' . Lang ( 'wallpapers' );
		$data ['feed_url'] = site_url ( 'tags/show/' . $tag );
		$data ['page_description'] = SITE_SLOGAN;
		$data ['page_language'] = 'en-ca';
		$data ['creator_email'] = ADMIN_EMAIL;
		$data ['wallpapers'] = $this->mwallpaper->rss_get_wallpapers_by_tag ( urldecode ( $tag ) );
		header ( "Content-Type: application/rss+xml" );
		die ( $this->load->view ( 'rss', $data, TRUE ) );
	}

	function color () {
		$color = $this->uri->segment ( 3 );
		$data ['encoding'] = 'utf-8';
		$data ['feed_name'] = Lang ( 'browse_by_color' ) . ' #' . $color;
		$data ['feed_url'] = site_url ( 'colors/browse/' . $color );
		$data ['page_description'] = SITE_SLOGAN;
		$data ['page_language'] = 'en-ca';
		$data ['creator_email'] = ADMIN_EMAIL;
		$data ['wallpapers'] = $this->mwallpaper->rss_get_wallpapers_by_color ( $color );
		header ( "Content-Type: application/rss+xml" );
		die ( $this->load->view ( 'rss', $data, TRUE ) );
	}
}

//END