<TABLE width="100%" class="mytables">

	<thead>
		<tr>
			<th class="center"><a href="{'users/manage_users/1'|@site_url}">Active</a></th>
			<th class="center"><a href="{'users/manage_users/0'|@site_url}">Inactive</a></th>
			<th class="center"><a href="{'users/manage_users/2'|@site_url}">Suspended</a></th>
		</tr>
	</thead>

	<tr>
		<td width="33%">{''|@get_active_users_nr ()}</td>
		<td width="33%">{''|get_inactive_users_nr ()}</td>
		<td>{''|@get_suspended_users_nr ()}</td>
	</tr>

</table>