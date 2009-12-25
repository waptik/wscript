{if $partners!=FALSE}
	<ul>
{foreach from=$partners item=partner}
		<li><a href="{$partner->link|prep_url}" title="{$partner->title|stripslashes}" target="_blank">{$partner->title|stripslashes}<em>{$partner->description|stripslashes}</em></a></li>
{/foreach}
	</ul>
<div class="clear"></div>
{/if}