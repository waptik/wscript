<form action="" id="form_wall" name="form_wall" method="post">
<input type="hidden" name="referer" id="referer" value="{''|selfUrl}" />
<table width="100%" class="pickme rowstyle-alt no-arrow mytables">
<thead>
	<tr>
		<th width="15"><input type="checkbox" name="check_all" id="check_all" value="" onclick="javascript:CheckUncheckAll('form_wall');" /></th>
		<th class="sortable-text">{'title'|@Lang}</th>
		<th class="sortable-text" width="100">{'author'|@Lang}</th>
		<th>{'options'|@Lang}</th>
	</tr>
        <tr>
		<td width="15">&nbsp;</th>
		<td class="sortable-text left">
                        <input type="text" style="width:99%" name="title_filter" id="title_filter" value="{'title_filter'|@form_validation_get_value}" />
                        <script type="text/javascript">{literal}
                                $(document).ready(function() {
                                                $("#title_filter").suggest("{/literal}{'wallpapers/title_suggest'|site_url}{literal}");
                                } );{/literal}
                        </script>
                </td>
		<td class="sortable-text">
                        <input type="text" size="10" name="user_filter" id="user_filter" value="{'user_filter'|@form_validation_get_value}" />
                        <script type="text/javascript">{literal}
                                $(document).ready(function() {
                                                $("#user_filter").suggest("{/literal}{'users/user_suggest'|site_url}{literal}");
                                });{/literal}
                        </script>
                </td>
		<td><input type="submit" value="filter" /></td>
	</tr>
</thead>
<tfoot>
	<tr>
		<th colspan="4" class="right">
			{'with_selected'|Lang}:
			<select style="width:150px" name="mass_action" id="mass_action" onchange="ajax_mass_options('{'wallpapers/mass_options'|site_url}','form_wall'); return false;">
				<option value="">{'please_select_'|@Lang}</option>
			{if $CI->permissions->checkPermissions(array(7)) && ($CI->uri->segment(3,1) == 1 || $CI->uri->segment(3,1) == 0)}
				<option value="mass_suspend">{'suspend'|@Lang}</option>
			{/if}
			{if $CI->permissions->checkPermissions(array(6)) && ($CI->uri->segment(3,1) == 2 || $CI->uri->segment(3,1) == 0)}
				<option value="mass_activate">{'activate'|@Lang}</option>
			{/if}
			{if $CI->permissions->checkPermissions(array(8)) || ($row->user_id == $CI->session->userdata(AUTH_SESSION_ID))}
				<option value="mass_delete">{'delete'|@Lang}</option>
			{/if}
			</select>
		</th>
	</tr>
</tfoot>

{foreach from=$query item=row}
	<tr onclick="lockRowUsingCheckbox();lockRow();">
		<td><input type="checkbox" name="tablechoice[]" value="{$row->ID}" /></td>
		<td class="left"><a href="{$row|get_wallpaper_url}" target="_blank" class="img_tooltip" rel="{$row|get_wallpaper_url_location}thumb_{$row->hash}.jpg">{$row->file_title|stripslashes|__character_limiter:30}</a></td>
		<td>{$row->Username}</td>
		<td width="60">
			<select name="option" onChange="MM_jumpMenu('parent',this,0)">
				<option>----------</option>	
	
	{if ( $row->active == 0 || $row->active == 2 ) && $CI->permissions->checkPermissions(array(6))}
				<option value="{"wallpapers/options/activate/`$row->ID`/`$status`"|@site_url}">{'activate'|@Lang}</option>
	{/if}
	
	{if ( $row->active == 0 || $row->active == 1 ) && $CI->permissions->checkPermissions(array(7))}
				<option value="{"wallpapers/options/suspend/`$row->ID`/`$status`"|@site_url}">{'suspend'|@Lang}</option>
	{/if}
	
	{if $CI->permissions->checkPermissions(array(5)) || ($row->user_id == $CI->session->userdata(AUTH_SESSION_ID))}
				<option value="{"wallpapers/options/delete/`$row->ID`/`$status`"|@site_url}">{'delete'|@Lang}</option>	
	{/if}
	
	{if $CI->permissions->checkPermissions(array(4)) || ($row->user_id == $CI->session->userdata(AUTH_SESSION_ID))}
				<option value="{"wallpapers/options/edit/`$row->ID`/`$status`"|@site_url}">{'edit'|@Lang}</option>
	{/if}
			</select>
		</td>
	</tr>
{foreachelse}

		<tr>
			<td colspan="4">{'no_wallpapers'|@Lang}</td>
		</tr>
{/foreach}
</table>
</form>