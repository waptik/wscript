<?php /* Smarty version 2.6.25, created on 2009-12-26 01:07:47
         compiled from default/html/section_advertisements.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'Lang', 'default/html/section_advertisements.tpl', 3, false),array('modifier', 'base64_decode', 'default/html/section_advertisements.tpl', 9, false),)), $this); ?>
<h2 class="headers" id="ac">
	<span class="ui-icon ui-icon-signal-diag c3">&nbsp;</span>
	<?php echo ((is_array($_tmp='advertisements')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>

	<span class="ui-icon ui-icon-triangle-1-w c4">&nbsp;</span>
</h2>

<div id="tr" class="hidden">
<?php if (AD_CODE != ''): ?>
		<?php echo ((is_array($_tmp=@AD_CODE)) ? $this->_run_mod_handler('base64_decode', true, $_tmp) : base64_decode($_tmp)); ?>

<?php else: ?>
<!-- make me rich!! :) -->
<div style="padding:10px 36px 10px 36px">
<!--/* OpenX Javascript Tag v2.8.2 */-->

<!--/*
* The backup image section of this tag has been generated for use on a
* non-SSL page. If this tag is to be placed on an SSL page, change the
*   'http://wallpaperscript.net/ads/www/delivery/...'
* to
*   'https://wallpaperscript.net/ads/www/delivery/...'
*
* This noscript section of this tag only shows image banners. There
* is no width or height in these banners, so if you want these tags to
* allocate space for the ad before it shows, you will need to add this
* information to the <img> tag.
*
* If you do not want to deal with the intricities of the noscript
* section, delete the tag (from <noscript>... to </noscript>). On
* average, the noscript tag is called from less than 1% of internet
* users.
*/-->

<script type='text/javascript'><!--//<![CDATA[
var m3_u = (location.protocol=='https:'?'https://wallpaperscript.net/ads/www/delivery/ajs.php':'http://wallpaperscript.net/ads/www/delivery/ajs.php');
var m3_r = Math.floor(Math.random()*99999999999);
if (!document.MAX_used) document.MAX_used = ',';
document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
document.write ("?zoneid=2");
document.write ('&amp;cb=' + m3_r);
if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
document.write ("&amp;loc=" + escape(window.location));
if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
if (document.context) document.write ("&context=" + escape(document.context));
if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
document.write ("'><\/scr"+"ipt>");
//]]>--></script><noscript><a href='http://wallpaperscript.net/ads/www/delivery/ck.php?n=a3f8d31e&amp;cb=INSERT_RANDOM_NUMBER_HERE' target='_blank'><img src='http://wallpaperscript.net/ads/www/delivery/avw.php?zoneid=2&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=a3f8d31e' border='0' alt='' /></a></noscript>

</div>
<?php endif; ?>
</div>