<div style="padding-top:15px">
<table width="100%" class="mytables">
	<thead>
		<tr>
			<th class="sortable-text">{'title'|@Lang}</th>
			<th class="sortable-text" width="100">{'author'|@Lang}</th>
		</tr>
	</thead>
	<tbody>
{foreach from=$query item=row}
		<tr>
			<td class="left"><a href="{"wallpapers/edit/`$row->ID`"|site_url}" target="_blank" class="img_tooltip" rel="{$row|get_wallpaper_url_location}thumb_{$row->wallpaper}">{$row->file_title|stripslashes|__character_limiter:30}</a></td>
			<td>{$row->Username}</td>
		</tr>
{foreachelse}
	
			<tr>
				<td colspan="2">{'no_wallpapers'|@Lang}</td>
			</tr>
{/foreach}
	</tbody>
</table>
</div>
{literal}<script type="text/javascript">
	$(document).ready(function(){
		$('.img_tooltip').tooltip({delay: 0,track: true,showURL: false,bodyHandler:function(){return jQuery("<img/>").attr("src", this.rel);}});
	});
</script>{/literal}