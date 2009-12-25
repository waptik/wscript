<table width="100%" class="sortable-onload-1 rowstyle-alt no-arrow mytables">
	<thead>
		<tr>
			<th class="sortable-numeric" width="20">Nr.</th>
			<th class="left">{'site_language'|@Lang}</th>
			<th class="center" width="40">ISO</th>
			<th class="center" width="70">{'options'|@Lang}</th>
		</tr>
	</thead>
	<tbody>
{counter start=0 print=0}
{foreach from=$languages item=language}
		<tr>
			<td>
				{counter}
			</td>
			<td class="left">
				{$language->language}
			</td>
			<td class="center">
				{$language->iso}
			</td>
			<td>
				<select name="option" onChange="MM_jumpMenu('parent',this,0)">
					<option>----------</option>{assign var=lang value=$language->language|urlencode}
					<option value="{"language/edit/`$lang`"|@site_url}">{'edit'|@Lang}</option>
{if $smarty.const.LANG_TYPE != $language->language}
					<option value="{"language/delete/`$lang`"|@site_url}">{'delete'|@Lang}</option>
{/if}
				</select>	
			</td>
		</tr>
{/foreach}
	</tbody>
</table>