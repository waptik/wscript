<div class="pagination">
<div class="left">
{if $first_wallpaper != FALSE}
	<a href="{$first_wallpaper|get_wallpaper_url}">FIRST</a>
{else}
	<a class="current" href="javascript:void(0);">FIRST</a>
{/if}
{if $prev_wallpaper != FALSE}
	<a href="{$prev_wallpaper|get_wallpaper_url}">&#9668;</a>
{else}
	<a class="current" href="javascript:void(0);">&#9668;</a>
{/if}
</div>
<div align="right" class="right">
{if $next_wallpaper != FALSE}
	<a href="{$next_wallpaper|get_wallpaper_url}">&#9658;</a>
{else}
	<a class="current" href="javascript:void(0);">&#9658;</a>
{/if}

{if $last_wallpaper != FALSE}
	<a href="{$last_wallpaper|get_wallpaper_url}">LAST</a>
{else}
	<a class="current" href="javascript:void(0);">LAST</a>
{/if}
</div>
<div class="clear"></div>
</div>