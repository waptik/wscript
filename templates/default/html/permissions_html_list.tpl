{foreach from=$permission_types key=parent item=childs}
<table id="options_{$parent}" width="100%" class="sortable-onload-1 rowstyle-alt no-arrow mytables">

	<thead>
		<tr>
			<th class="sortable-numeric" width="20">Nr</th>
			<th class="left">{$parent|@get_permission_title}</th>
			<th width="40" class="center"><a href="#" onclick="mark_options('options_{$parent}', 'y');return false;">Yes</a></th>
			<th width="40" class="center"><a href="#" onclick="mark_options('options_{$parent}', 'n');return false;">No</a></th>
		</tr>
	</thead>

{counter start=0 print=0}

{foreach from=$childs item=child}
		<tr>
			<td>{counter}</td>
			<td class="left">{$child|@get_permission_title|ucfirst}</td>
			<td><label for="{$child}_y"><input type="radio" id="{$child}_y" value="y" {$child|@get_y_current_label_state:$perm_type:$perm_id} name="setting[{$parent}][{$child}]" /></label></td>
			<td><label for="{$child}_n"><input type="radio" id="{$child}_n" value="n" {$child|@get_n_current_label_state:$perm_type:$perm_id} name="setting[{$parent}][{$child}]" /></label></td>
		</tr>
{/foreach}

</table>
{/foreach}