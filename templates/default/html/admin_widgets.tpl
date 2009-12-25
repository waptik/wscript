<script type="text/javascript">{literal}
	$(document).ready(function(){
		$("#sortableList").sortable({ containment: 'parent', axis: 'y', opacity: 0.6, stop: save_widgets});
	});
	function save_widgets () {
		showUpdate();
		$.post
		(
			site_url+'/admin/change_widgets',
			jQuery('#admin_sections_frm').serialize(),
			function (html){
				refresh ();
			}
		);
	}

	function enable_widget(id) {
		showUpdate();
		$.post
		(
			site_url+'/admin/enable_widget',
			{widget_name:id},
			function (html){
				refresh ();
			}
		);
	}
	function disable_widget(id) {
		showUpdate();
		$.post
		(
			site_url+'/admin/disable_widget',
			{widget_name:id},
			function (html){
				refresh ();
			}
		);
	}{/literal}
</script>
<form action="" method="post" id="admin_sections_frm" name="admin_sections_frm" class="appnitro">
<ul id="sortableList">
{foreach from=$sections item=section key=id}
	<li class="ui-state-default admin_widget tooltip" style="width: 522px ! important;" title="{$section.title} - {$section.description}">
{if ! $section.show}
		<a href="javascript:enable_widget('{$id}');">
			<span class="ui-icon ui-icon-check" unselectable="on">Enable</span>
		</a>
{else}
		<a href="javascript:disable_widget('{$id}');">
			<span class="ui-icon ui-icon-closethick" unselectable="on">Enable</span>
		</a>
{/if}
		<p>{$section.title}</p>
		<input type="checkbox" class="element text hidden" id="el_{$id}" name="{$id}" value="1" {if $section.show}checked="checked"{/if} />
	</li>
{/foreach}
</ul>
</form>