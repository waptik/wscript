<div class="picture_wrapper_details">
	<img src="{$thumb}" alt="{$row->file_title}" />

{assign var=show_ad value=true}
{if ($row->type =='iphone' && WALLPAPER_IPHONE_AD_CODE == ' ')||($row->type !='iphone' && WALLPAPER_AD_CODE == ' ')}
	{assign var=show_ad value=false}
{/if}

{if $show_ad}
	<div id="wall_advert" style="display:none"><span class="ui-icon ui-icon-circle-close" onclick="jQuery('#wall_advert').hide();"></span>
{if $row->type !='iphone' && $row->width > 516}
{if WALLPAPER_AD_CODE != ''}
		{$smarty.const.WALLPAPER_AD_CODE|base64_decode}
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
   document.write ("?zoneid=5");
   document.write ('&amp;cb=' + m3_r);
   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
   document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
   document.write ("&amp;loc=" + escape(window.location));
   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
   if (document.context) document.write ("&context=" + escape(document.context));
   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
   document.write ("'><\/scr"+"ipt>");
//]]>--></script><noscript><a href='http://wallpaperscript.net/ads/www/delivery/ck.php?n=a54eb240&amp;cb=INSERT_RANDOM_NUMBER_HERE' target='_blank'><img src='http://wallpaperscript.net/ads/www/delivery/avw.php?zoneid=5&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=a54eb240' border='0' alt='' /></a></noscript>

{/if}
{elseif $row->type =='iphone'}
{if WALLPAPER_IPHONE_AD_CODE != ''}
		{$smarty.const.WALLPAPER_IPHONE_AD_CODE|base64_decode}
{else}
<!-- IPHONE AD UNIT -->
		<script type="text/javascript"><!--
		google_ad_client = "pub-9154953515880135";
		/* 234x60, created 7/19/09 */
		google_ad_slot = "0357948439";
		google_ad_width = 234;
		google_ad_height = 60;
		//-->
		</script>
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
{/if}
{/if}
	</div>
{/if}
</div>
<div class="clear"></div>
<div id="next_prev_wallpapers"><div class="pagination"><a href="javascript:void(0);">Loading...</a></div></div>

{$edit}

<h3 class="headers gray">{'download'|@Lang}</h3>
{$download_table}

{if $row->description != ''}
<div style="margin-top:10px">
<h2 class="headers gray">{'description'|@Lang}</h2>
<div class="more_options">{$row->description|stripslashes}</div>
</div>
{/if}
<div style="margin-top:10px">
<h3 class="headers gray">{'more_options'|@Lang}</h3>
<div class="more_options">
	<table>
		<tbody>
			<tr>
				<td class="left_col">{'rating'|@Lang|ucfirst}</td>
				<td class="right_col">
					<div class="relative">
						<span id="rating_{$row->ID}" class="star-rating-result">{'current_rating'|Lang}<b>{$row->rating|int}</b> {'from'|Lang} <b>{$row->votes_nr}</b> {'votes'|Lang}</span>
						<div id="rating"></div>
						<script type="text/javascript">
							{assign var=smarty_template_id value=''|selfUrl|md5}
							{literal}$('#rating').rater('{/literal}{"wallpapers/insert_rating/`$row->ID`/`$smarty_template_id`"|@site_url}{literal}', {style: 'basic', curvalue:{/literal}{$row->rating|int}});
						</script>
					</div>
				</td>
			</tr>
			<tr>
				<td class="left_col">{'author'|@Lang|ucfirst}</td>
				<td class="right_col">
					<a href="{"members/show/`$row->user_id`"|@site_url}" title="{'wallpapers'|@Lang} {'from'|@Lang} {$row->Username}">{$row->Username}</a>
					<a class="tooltip" href="{"rss/member/`$row->user_id`"|site_url}" title="{'member_grab_rss'|Lang}">
						<img src="{$images_path}icons/rss.gif" alt="{'member_grab_rss'|Lang}" />
					</a>
				</td>
			</tr>
			<tr style="display:none" id="tr_wallpaper_breadcrumb">
				<td class="left_col">{'category'|@Lang|ucfirst}</td>
				<td class="right_col" id="wallpaper_breadcrumb"></td>
			</tr>
			<tr>
				<td class="left_col">{'downloads'|@Lang|ucfirst}</td>
				<td class="right_col">{$row->downloads}</td>
			</tr>
			<tr>
				<td class="left_col">{'hits'|@Lang|ucfirst}</td>
				<td class="right_col">{$row->hits+0}</td>
			</tr>
			<tr>
				<td class="left_col">{'date_added'|@Lang|ucfirst}</td>
				<td class="right_col">{"%Y-%m-%d"|@mdate:$row->date_added}</td>
			</tr>

			<tr style="display:none" id="tr_wallpaper_colors">
				<td class="left_col">{'colors'|@Lang|ucfirst}</td>
				<td class="right_col" id="wallpaper_colors"></td>
			</tr>

			<tr style="display:none" id="tr_wallpaper_tags">
				<td class="left_col">{'tags'|@Lang|ucfirst}</td>
				<td class="right_col" id="wallpaper_tags"></td>
			</tr>

			<tr>
				<td class="left_col">{'img_url'|@Lang|ucfirst}</td>
				<td class="right_col"><input type="text" onclick="javascript:this.focus();this.select();" value='&lt;a href="{$wallpaper_url}"&gt;&lt;img src="{$thumb}" alt="{$row->file_title}" border="0" /&gt;&lt;/a&gt;' /></td>
			</tr>
			<tr>
				<td class="left_col">{'forum_url'|@Lang|ucfirst}</td>
				<td class="right_col"><input type="text" onclick="javascript:this.focus();this.select();" value='[URL={$wallpaper_url}][IMG]{$thumb}[/IMG][/URL]' /></td>
			</tr>
			<tr>
				<td class="left_col">{'bookmarks'|@Lang|ucfirst}</td>
				<td class="right_col">
					<!-- AddThis Button BEGIN -->
					<a href="http://www.addthis.com/bookmark.php?v=250&amp;pub=xa-4a477b6641859d7b" onmouseover="return addthis_open(this, '', '[URL]', '[TITLE]')" onmouseout="addthis_close()" onclick="return addthis_sendto()">
						<img src="http://s7.addthis.com/static/btn/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/>
					</a>
					<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?pub=xa-4a477b6641859d7b"></script>
					<!-- AddThis Button END -->
				</td>
			</tr>
		</tbody>
	</table>
</div>
</div>

<div id="wallpaper_comments"></div>
<div id="more_from_category"></div>
<div id="more_from_author"></div>