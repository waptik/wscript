{if $site_sentry_isadmin}
<table width="100%" class="mytables">

	<thead>
		<tr>
			<th>Error</th>
		</tr>
	</thead>

	<tr>
		<td>{'user_not_editable'|@Lang}</td>
	</tr>
</table>
{else}
	{$form_open}
	{$build_permissions_html_list}
		<div class="job_indicators">
			{'save_u_permissions'|Lang|__button}
		</div>
	</form>
{/if}
