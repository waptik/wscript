<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$page_title}</title>
	<meta name="description" content="{$meta_description}" />
	<meta name="keywords" content="{$meta_keywords}" />
	<meta http-equiv="Content-Type" content="text/html; charset={'charset'|Lang}" />
	<meta name="content-language" content="{'iso'|Lang}" />
{$styles}
	<script type="text/javascript">
		var base_url = "{$base_url}";
		var site_url = "{$site_url}";
		var active_template = "{$smarty.const.DEFAULT_TEMPLATE}";
	</script>
{$javascript}
</head>
<body style="background:#F2FCFF url('{''|base_url}templates/default/images/patterns/{'bg_pattern'|get_setting}');">
{if $smarty.const.SITE_HAS_ADULT_MATERIALS && !$adult_confirmed}
	<div id="adult_confirmation_wrapper" style="display:none">
		<p>
			<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
			{'adult_materials'|Lang}
		</p>
	</div>
{/if}
<!-- MENU -->
	<div id="header" style="height:{$header_height}px">
		<div id="logo" style="background-image: url({''|base_url}/various/logo.gif?{''|print_unique_id})">
			<a href="{$home}" title="{$smarty.const.SITE_SLOGAN}">
				<img src="{$images_path}pixel.gif" alt="{$slogan}" width="{$logo_size_x}" height="{$logo_size_y}" />
			</a>
		</div>
{if $smarty.const.GUESTS_CAN_UPLOAD || is_logged_in()}
		<div id="upload">
			<a href="javascript:dialog(700,600,'{'upload_wallpaper'|Lang}',true,true,'{'wallpapers/upload'|site_url}');" title="{'upload_wallpaper'|Lang}">
				<img src="{$images_path}pixel.gif" alt="{'upload_wallpaper'|Lang}" width="96" height="24" />
			</a>
		</div>
{/if}
	</div>
	<div id="top_menu">{$top_menu}</div>
	<div class="clear"></div>
	<div id="wrapper"><!-- WRAPPER -->
		<div id="left"><!-- LEFT START -->
			{$header_msg|write_header}
			<div id="content"><!-- CONTENT START -->
			{$content}
			</div><!-- CONTENT END -->
		</div><!-- LEFT END -->
		<div id="right"><!-- RIGHT START -->
			{$right}	
		</div><!-- RIGHT END -->
		<div class="clear"></div>
	</div>
	{$footer}
</body>
</html>