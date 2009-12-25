<div class="more_options" style="margin-bottom:15px">
	<table>
		<tbody>
			<tr>
				<td class="left_col">{'wallpapers'|Lang|ucfirst}</td>
				<td class="right_col">{$row->walls_nr+0}</td>
			</tr>
			
			<tr>
				<td class="left_col">{'rating'|Lang|ucfirst}</td>
				<td class="right_col"><b>{$row->user_rating+0|round:2}</b> {'from'|Lang} <b>{$row->user_votes}</b> {'votes'|Lang}</td>
			</tr>
			
			<tr>
				<td class="left_col">{'downloads'|Lang|ucfirst}</td>
				<td class="right_col">{$row->user_downloads+0}</td>
			</tr>
			
			<tr>
				<td class="left_col">{'hits'|Lang|ucfirst}</td>
				<td class="right_col">{$row->user_hits+0}</td>
			</tr>
			
			<tr>
				<td class="left_col">{'date_registered'|Lang|ucfirst}</td>
				<td class="right_col">{"%Y-%m-%d"|mdate:$row->date_registered}</td>
			</tr>
		</tbody>
	</table>
</div>

<div id="wallpapers_wrapper"></div>