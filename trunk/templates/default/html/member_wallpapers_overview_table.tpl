<table width="100%" class="mytables">

	<thead>
		<tr>
			<th class="center"><a href="{'members/index/1'|@site_url}">{'active'|Lang|strtoupper}</a></th>
			<th class="center"><a href="{'members/index/0'|@site_url}">{'inactive'|Lang|strtoupper}</a></th>
			<th class="center"><a href="{'members/index/2'|@site_url}">{'suspended'|Lang|strtoupper}</a></th>
		</tr>
	</thead>

	<tr>
		<td width="33%">{1|@get_wallpapers_nr:$member_id}</td>
		<td width="33%">{0|@get_wallpapers_nr:$member_id}</td>
		<td width="33%">{2|@get_wallpapers_nr:$member_id}</td>
	</tr>
	
</table>