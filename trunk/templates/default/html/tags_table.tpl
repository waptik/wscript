<table width="100%" class="sortable-onload-1 rowstyle-alt no-arrow mytables">
	<thead>
		<tr>
			<th class="sortable-numeric" width="15">Nr.</th>
			<th class="sortable-text left">{'tag'|@Lang}</th>
			<th width="60">{'options'|@Lang}</th>
		</tr>
	</thead>
{counter start=$CI->uri->segment(4,0) print=0}
{foreach from=$query item=row}		
	<tr>
		<td>{counter}</td>
		<td class="left">{$row->tag|stripslashes}</td>
		<td width="60">
			<select name="option" onChange="MM_jumpMenu('parent',this,0)">
				<option>----------</option>
{if $CI->permissions->checkPermissions(array(41))}
				<option value="{"tags/options/delete/`$row->ID`"|@site_url}">{'delete'|@Lang}</option>
{/if}
			</select>
		</td>
	</tr>
{foreachelse}
	<tr>
		<td colspan="6">{'no_tags'|@Lang}</td>
	</tr>
{/foreach}
</table>