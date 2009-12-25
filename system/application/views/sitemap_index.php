<?php echo '<?xml version="1.0" encoding="utf-8"?>' . "\n"; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php
if ( $categories != FALSE ) {
foreach ( $categories->result () as $cat ){ ?>
      <sitemap>
	 <loc><?= site_url ( 'sitemap/show_cat/' . $cat->ID ) ?></loc>
	 <lastmod><?= mdate ( "%Y-%m-%d", now () ) ?></lastmod>
      </sitemap>
<?php }} ?>
</sitemapindex>