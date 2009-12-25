<TABLE width="100%" class="sortable-onload-1 rowstyle-alt no-arrow mytables">
	<thead>
		<tr>
			<th class="sortable-numeric" width="15">Nr.</th>
			<th class="sortable-text left">{'title'|@Lang}</th>
			<th width="200">{'description'|@Lang}</th>
			<th class="center">{'options'|@Lang}</th>
		</tr>
	</thead>
	<tbody>
{counter start=0 print=0}
{foreach from=$query item=row}
		<tr>
			<td>{counter}</td>
			<td class="left"><b><a href="{$row->link}" target="_blank">{$row->title}</a></b></td>
			<td class="left">{$row->description}</td>
			<td width="60" class="center">
				<select name="option" onChange="MM_jumpMenu('parent',this,0)">
					<option>----------</option>
					<option value="{"partners/options/delete/`$row->ID`"|@site_url}">{'delete'|@Lang}</option>
					<option value="{"partners/options/edit/`$row->ID`"|@site_url}">{'edit'|@Lang}</option>
				</select>
			</td>
		</tr>
{foreachelse}
		<tr>
			<td colspan="5">{'no_users'|@Lang}</td>
		</tr>
{/foreach}
	</tbody>
</table>