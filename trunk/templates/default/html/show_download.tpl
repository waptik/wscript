<div id="download_simple">
<div class="misc">
<table style="width:100%" cellspacing="2">
	<thead>
		<tr>
			<td colspan="2">
{if TOP_DOWNLOAD_AD_CODE != ''}
		{$smarty.const.TOP_DOWNLOAD_AD_CODE|base64_decode}
{else}
<!-- make me rich!! :) -->
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
   document.write ("?zoneid=3");
   document.write ('&amp;cb=' + m3_r);
   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
   document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
   document.write ("&amp;loc=" + escape(window.location));
   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
   if (document.context) document.write ("&context=" + escape(document.context));
   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
   document.write ("'><\/scr"+"ipt>");
//]]>--></script><noscript><a href='http://wallpaperscript.net/ads/www/delivery/ck.php?n=a75dd469&amp;cb=INSERT_RANDOM_NUMBER_HERE' target='_blank'><img src='http://wallpaperscript.net/ads/www/delivery/avw.php?zoneid=3&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=a75dd469' border='0' alt='' /></a></noscript>

{/if}
			</td>
		</tr>
	</thead>
	<tbody>
{if isset($childs.normal)}
		<tr>
			<th width="20%"><h3>{'normal'|@Lang}</h3></th>
			<td style="padding:4px;vertical-align: top;">
				<ul>
	{foreach from=$childs.normal item=height key=width}
					<li>
						<a title="{'download'|@Lang} {$row->file_title} {'wallpaper'|@Lang}" href="{"wallpapers/download/`$row->ID`/`$width`/`$height`"|site_url}" rel="nofollow" {if $smarty.const.OPEN_WALLPAPERS_IN_NEW_WINDOW} target="_blank"{/if}>
							<b>{$width}</b> X <b>{$height}</b>
						</a>
					</li>
	{/foreach}
				</ul>
			</td>
		</tr>
{/if}
{if isset($childs.wide)}
		<tr>
			<th width="20%"><h3>{'wide'|@Lang}</h3></th>
			<td style="padding:4px;vertical-align: top;">
				<ul>

	{foreach from=$childs.wide item=height key=width}
					<li>
						<a title="{'download'|@Lang} {$row->file_title} {'wallpaper'|@Lang}" href="{"wallpapers/download/`$row->ID`/`$width`/`$height`"|site_url}" rel="nofollow" {if $smarty.const.OPEN_WALLPAPERS_IN_NEW_WINDOW} target="_blank"{/if}>
							<b>{$width}</b> X <b>{$height}</b>
						</a>
					</li>
	{/foreach}

				</ul>
			</td>
		</tr>
{/if}
{if isset($childs.psp)}
		<tr>
			<th width="20%"><h3>PSP</h3></th>
			<td style="padding:4px;vertical-align: top;">

				<ul>

	{foreach from=$childs.psp item=height key=width}
					<li>
						<a title="{'download'|@Lang} {$row->file_title} {'wallpaper'|@Lang}" href="{"wallpapers/download/`$row->ID`/`$width`/`$height`"|site_url}" rel="nofollow" {if $smarty.const.OPEN_WALLPAPERS_IN_NEW_WINDOW} target="_blank"{/if}>
							<b>{$width}</b> X <b>{$height}</b>
						</a>
					</li>
	{/foreach}
				</ul>
			</td>
		</tr>
{/if}
{if isset($childs.iphone)}
		<tr>
			<th width="20%"><h3>IPhone</h3></th>
			<td style="padding:4px;vertical-align: top;">

				<ul>

	{foreach from=$childs.iphone item=height key=width}
					<li>
						<a title="{'download'|@Lang} {$row->file_title} {'wallpaper'|@Lang}" href="{"wallpapers/download/`$row->ID`/`$width`/`$height`"|site_url}" rel="nofollow" {if $smarty.const.OPEN_WALLPAPERS_IN_NEW_WINDOW} target="_blank"{/if}>
							<b>{$width}</b> X <b>{$height}</b>
						</a>
					</li>
	{/foreach}

				</ul>

			</td>
		</tr>
{/if}
{if isset($childs.hd)}
		<tr>
			<th width="20%"><h3>{'hd'|@Lang}</h3></th>
			<td style="padding:4px;vertical-align: top;">

				<ul>

	{foreach from=$childs.hd item=height key=width}
					<li>
						<a title="{'download'|@Lang} {$row->file_title} {'wallpaper'|@Lang}" href="{"wallpapers/download/`$row->ID`/`$width`/`$height`"|site_url}" rel="nofollow" {if $smarty.const.OPEN_WALLPAPERS_IN_NEW_WINDOW} target="_blank"{/if}>
							<b>{$width}</b> X <b>{$height}</b>
						</a>
					</li>
	{/foreach}

				</ul>

			</td>
		</tr>
{/if}
{if isset($childs.multi)}
		<tr>
			<th width="20%"><h3>{'multi'|@Lang}</h3></th>
			<td style="padding:4px;vertical-align: top;">

				<ul>

	{foreach from=$childs.multi item=height key=width}
					<li>
						<a title="{'download'|@Lang} {$row->file_title} {'wallpaper'|@Lang}" href="{"wallpapers/download/`$row->ID`/`$width`/`$height`"|site_url}" rel="nofollow" {if $smarty.const.OPEN_WALLPAPERS_IN_NEW_WINDOW} target="_blank"{/if}>
							<b>{$width}</b> X <b>{$height}</b>
						</a>
					</li>
	{/foreach}

				</ul>

			</td>
		</tr>
{/if}

{if isset($childs.other)}
		<tr>
			<th width="20%"><h3>{'other'|@Lang}</h3></th>
			<td style="padding:4px;vertical-align: top;">

				<ul>

	{foreach from=$childs.other item=height key=width}
					<li>
						<a title="{'download'|@Lang} {$row->file_title} {'wallpaper'|@Lang}" href="{"wallpapers/download/`$row->ID`/`$width`/`$height`"|site_url}" rel="nofollow" {if $smarty.const.OPEN_WALLPAPERS_IN_NEW_WINDOW} target="_blank"{/if}>
							<b>{$width}</b> X <b>{$height}</b>
						</a>
					</li>
	{/foreach}

				</ul>

			</td>
		</tr>
{/if}
		<tr>
			<th width="20%"><h3>{'original'|@Lang}</h3></th>
			<td style="padding:4px;vertical-align: top;">
				<ul>
					<li>
						<a title="{'download'|@Lang} {$row->file_title} {'wallpaper'|@Lang}" href="{"wallpapers/download/`$row->ID`/`$row->width`/`$row->height`"|site_url}" rel="nofollow" {if $smarty.const.OPEN_WALLPAPERS_IN_NEW_WINDOW} target="_blank"{/if}>
							<b>{$row->width}</b> X <b>{$row->height}</b>
						</a>
					</li>

				</ul>
			</td>
		</tr>
	</tbody>
</table>

<div class="clear"></div>
</div>
</div>