<table width="100%" class="sortable-onload-1 rowstyle-alt no-arrow mytables">
	<thead>
		<tr>
			<th class="sortable-text left">{'user'|Lang}</th>
			<th class="sortable-numeric">{'last_activity'|Lang}</th>
			<th class="sortable-text" width="100">{'current_page'|@Lang}</th>
		</tr>
	</thead>
{foreach from=$online_users item=user key=k}
	<tr>
		<td class="left">{$user.$k.username}</td>
		<td class="left">{$user.$k.last_activity}</td>
		<td><a href="{$user.$k.current_page|site_url}">Link</a></td>
	</tr>
{/foreach}
</table>
{$pagination}