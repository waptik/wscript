{if $CI->site_sentry->isadmingroup($CI->uri->segment(3))}
	<table width="100%" class="mytables">
	
		<thead>
			<tr>
				<th>Error</th>
			</tr>
		</thead>
	
		<tr>
			<td>{'group_not_editable'|@Lang}</td>
		</tr>
	</table>
{else}
	<form action="" method="post" class="appnitro">
	{'group'|@build_permissions_html_list:$CI->uri->segment(3)}
		<div class="job_indicators">
			{'save'|Lang|__button}
		</div>
	</form>
{/if}