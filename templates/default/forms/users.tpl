<form action="" method="post">
<table width="100%" class="sortable rowstyle-alt no-arrow mytables">
	<thead>
		<tr>
			<th class="sortable-text left">{'username'|@Lang}</th>
			<th class="sortable-text center">{'email'|@Lang}</th>
			<th class="sortable-text center">{'group'|@Lang}</th>
			<th class="center">{'options'|@Lang}</th>
		</tr>

		<tr>
			<td class="left">
				<input type="text" style="width:100%" name="user_filter" id="user_filter" value="{'user_filter'|@form_validation_get_value}" />
	                        <script type="text/javascript">{literal}
	                                $(document).ready(function() {
	                                                $("#user_filter").suggest("{/literal}{'users/user_suggest'|site_url}{literal}");
	                                } );{/literal}
	                        </script>
			</td>
			<td class="center">
				<input type="text" style="width:100%" name="email_filter" id="email_filter" value="{'email_filter'|@form_validation_get_value}" />
	                        <script type="text/javascript">{literal}
	                                $(document).ready(function() {
	                                                $("#email_filter").suggest("{/literal}{'users/email_suggest'|site_url}{literal}");
	                                } );{/literal}
	                        </script>
			</td>
			<td class="center">
				{'groups'|get_groups_select}
			</td>
			<td><input type="submit" value="filter" /></td>
		</tr>
	</thead>

	<tbody>
{if $results != FALSE}
{foreach from=$results item=row}
					<tr>
						<td class="left">{$row->Username}</td>
						<td class="left">{$row->Email}</td>
						<td>{$row->title}</td>
						<td>
{if $row->ID != 1}
						<select name="option" onChange="MM_jumpMenu('parent',this,0)">
								<option>----------</option>
{if ($row->Active==1 || $row->Active==0) && $CI->permissions->checkPermissions(array(14))}
								<option value="{"users/options/suspend/`$row->ID`"|@site_url}">{'suspend'|@Lang}</option>
{/if}
{if ( $row->Active == 2 || $row->Active == 0 ) && $CI->permissions->checkPermissions ( array ( 13 ) ) }
								<option value="{"users/options/activate/`$row->ID`"|@site_url}">{'activate'|@Lang}</option>
{/if}
{if $CI->permissions->checkPermissions ( array ( 12 ) ) }
								<option value="{"users/options/delete/`$row->ID`"|@site_url}">{'delete'|@Lang}</option>
{/if}
{if $CI->permissions->checkPermissions ( array ( 11 ) ) }
								<option value="{"users/options/edit/`$row->ID`"|@site_url}">{'edit'|@Lang}</option>
{/if}
{if $CI->permissions->checkPermissions ( array ( 30 ) ) }
								<option value="{"users/manage_user_permissions/`$row->ID`"|@site_url}">{'permissions'|@Lang}</option>
{/if}
						</select>
{else} 
					&nbsp;
{/if}
						</td>
					</tr>
{/foreach}
{else}
				<tr class="alt">
					<td colspan="4">{'no_users'|@Lang}</td>
				</tr>
{/if}
	</tbody>
</table>
</form>