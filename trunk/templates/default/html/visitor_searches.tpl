<form id="form_searches" method="post" action="" name="form_searches">
<table width="100%" class="sortable-onload-1 rowstyle-alt no-arrow mytables">
	<thead>
		<tr>
			<th class="sortable-numeric" width="15">Nr.</th>
			<th class="sortable-text left">{'tag'|@Lang}</th>
			<th class="sortable-numeric" width="60">{'occurences'|@Lang}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th class="right" colspan=3>
				{'delete_records_older_than'|Lang}:
				<select style="width:150px" name="searches_options" id="searches_options" onchange="ajax_mass_options('{'search/delete_searches_options'|site_url}','form_searches'); return false;" class="element select">
					<option value="">{'please_select_'|@Lang}</option>
					<option value="one_hour">{'one_hour'|@Lang}</option>
					<option value="one_day">{'one_day'|@Lang}</option>
					<option value="one_week">{'one_week'|@Lang}</option>
					<option value="one_month">{'one_month'|@Lang}</option>
					<option value="one_year">{'one_year'|@Lang}</option>
					<option value="all">{'all'|@Lang}</option>
				</select>
			</th>
		</tr>
	</tfoot>
{counter start=$CI->uri->segment(3,0) print=0}
{foreach from=$searches item=row}		
	<tr>
		<td>{counter}</td>
		<td class="left">{$row->search_string|stripslashes}</td>
		<td width="60">{$row->occurences}</td>
	</tr>
{/foreach}
</table>
</form>