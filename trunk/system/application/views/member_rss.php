<?php 
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<rss version="2.0"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:admin="http://webns.net/mvcb/"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:content="http://purl.org/rss/1.0/modules/content/">
	<channel>
		<title><?= $feed_name; ?></title>
		<link><?= $feed_url; ?></link>
		<description><?= $page_description; ?></description>
		<dc:language><?= $page_language; ?></dc:language>
		<dc:creator><?= $creator_email; ?></dc:creator>
		<dc:rights>Copyright <?php echo gmdate ( "Y", time () ); ?></dc:rights>
		<admin:generatorAgent rdf:resource="<?= $feed_name ?>" />
<?php foreach ( $wallpapers as $entry ): ?>
		<item>
			<title><?= xml_convert ( do_xhtml ( $entry->file_title ) ); ?></title>
			<link><?= get_wallpaper_url ( $entry ) ?></link>
			<guid><?= get_wallpaper_url ( $entry ) ?></guid>
<?php
	make_thumb_if_not_exists ( $entry );
	$thumb = get_wallpaper_url_location ( $entry ) . 'thumb_' . $entry->hash . '.jpg';
?>
			<description><?= do_xhtml ( '<a href="' . get_wallpaper_url ( $entry ) . '"><img src="' . $thumb . '" alt="' . $entry->file_title . ' /></a>' ); ?></description>
			<pubDate><?= date ( 'r', $entry->date_added ); ?></pubDate>
		</item>
<?php endforeach; ?>
	</channel>
</rss>