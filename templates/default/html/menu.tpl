{if isset($children)}
<ul>
{else}
<ul id="nav" class="sf-menu">
{/if}
{foreach from=$menu item=menu_item}
{assign var=has_childs value=$menu_item.children|@count}
	{if $menu_item.show_condition}
	<li class="{if $has_childs>0} current{/if} {$menu_item.class}">
		<a href="{$menu_item.link}">{$menu_item.text}</a>
{if !empty($menu_item.children)}
	{include file=$smarty.template menu=$menu_item.children children=1}
{/if}
	</li>
	{/if}
{/foreach}
</ul>
<div class="clear"></div>