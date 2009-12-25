<TABLE width="100%" class="sortable-onload-1 rowstyle-alt no-arrow mytables">
	<thead>
		<tr>
			<th class="sortable-numeric" width="15">Nr.</th>
			<th class="sortable-text left">{'title'|@Lang}</th>
			<th width="200">{'description'|@Lang}</th>
			<th>{'login_redirect'|@Lang}</th>
			<th>{'logout_redirect'|@Lang}</th>
			<th width="50" class="sortable-numeric" class="center">{'members'|@Lang}</th>
			<th class="center">{'options'|@Lang}</th>
		</tr>
	</thead>
	{counter start=0 print=0}
	{foreach from=$result item=row}
		<tr>
			<td>{counter}</td>
			<td class="left"><b>{$row->title}</b></td>
			<td class="left">{$row->description}</td>
			<td>{$row->login_redirect}</td>
			<td>{$row->logout_redirect}</td>
			<td class="center">{$row->ID|@get_group_members}</td>
			<td width="60" class="center">
				<select name="option" onChange="MM_jumpMenu('parent',this,0)">
					<option>----------</option>
	{if ($row->ID != 1 && $row->ID != 2) && $CI->permissions->checkPermissions ( array ( 19 ) )}
					<option value="{"user_groups/options/delete/`$row->ID`"|@site_url}">{'delete'|@Lang}</option>
	{/if}
	{if $CI->permissions->checkPermissions ( array ( 18 ) )}
					<option value="{"user_groups/options/edit/`$row->ID`"|@site_url}">{'edit'|@Lang}</option>
	{/if}
	{if $CI->permissions->checkPermissions ( array ( 30 ) )}
					<option value="{"user_groups/manage_group_permissions/`$row->ID`"|@site_url}">{'permissions'|@Lang}</option>
	{/if}
				</select>
			</td>
		</tr>
	{foreachelse}
	<tr>
		<td colspan="7">{'no_groups'|@Lang}</td>
	</tr>
	{/foreach}

</table>