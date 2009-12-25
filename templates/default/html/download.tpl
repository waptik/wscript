{if WALLPAPER_DOWNLOAD_AD_CODE != ''}
	{$smarty.const.WALLPAPER_DOWNLOAD_AD_CODE|base64_decode}
{else}<!--/* OpenX Javascript Tag v2.8.2 */-->

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
   document.write ("?zoneid=4");
   document.write ('&amp;cb=' + m3_r);
   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
   document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
   document.write ("&amp;loc=" + escape(window.location));
   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
   if (document.context) document.write ("&context=" + escape(document.context));
   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
   document.write ("'><\/scr"+"ipt>");
//]]>--></script><noscript><a href='http://wallpaperscript.net/ads/www/delivery/ck.php?n=ae90ef15&amp;cb=INSERT_RANDOM_NUMBER_HERE' target='_blank'><img src='http://wallpaperscript.net/ads/www/delivery/avw.php?zoneid=4&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=ae90ef15' border='0' alt='' /></a></noscript>
{/if}
<div class="clear"></div>
<img src="{$new_file}" />
