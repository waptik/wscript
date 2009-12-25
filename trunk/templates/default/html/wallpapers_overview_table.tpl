<TABLE width="100%" class="mytables">

	<thead>
		<tr>
			<th class="center"><a href="{'wallpapers/manage/1'|@site_url}">Active</a></th>
			<th class="center"><a href="{'wallpapers/manage/0'|@site_url}">Inactive</a></th>
			<th class="center"><a href="{'wallpapers/manage/2'|@site_url}">Suspended</a></th>
		</tr>
	</thead>
	
	<tr>
		<td width="33%">{1|@get_wallpapers_nr:0}</td>
		<td width="33%">{0|@get_wallpapers_nr:0}</td>
		<td width="33%">{2|@get_wallpapers_nr:0}</td>
	</tr>
	
</table>