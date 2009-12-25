<?php echo '<?xml version="1.0" encoding="utf-8"?>' . "\n"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php 
if ( $wallpapers != FALSE ) {
foreach ( $wallpapers as $wallpaper ){ ?>
	<url>
		<loc><?= get_wallpaper_url ( $wallpaper ) ?></loc>
		<lastmod><?= mdate ( "%Y-%m-%d", $wallpaper->date_added ) ?></lastmod>
		<priority>0.5</priority>
		<changefreq>monthly</changefreq>
	</url>
<?php }} ?>
</urlset>