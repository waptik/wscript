<script type="text/javascript">{literal}
	function enable_module(id) {
		showUpdate();
		$.post
		(
			site_url+'/admin/enable_module',
			{module_name:id},
			function (html){
				refresh ();
			}
		);
	}
	function disable_module(id) {
		showUpdate();
		$.post
		(
			site_url+'/admin/disable_module',
			{module_name:id},
			function (html){
				refresh ();
			}
		);
	}{/literal}
</script>
<form action="" method="post" id="admin_modules_frm" name="admin_modules_frm" class="appnitro">
<ul id="sortableList">
{foreach from=$modules item=module key=id}
	<li class="ui-state-default admin_widget tooltip" style="width: 522px ! important;cursor:default;" title="{$module.title} - {$module.description}">
{if ! $module.loaded}
		<a href="javascript:enable_module('{$id}');">
			<span class="ui-icon ui-icon-check" unselectable="on">Enable</span>
		</a>
{else}
		<a href="javascript:disable_module('{$id}');">
			<span class="ui-icon ui-icon-closethick" unselectable="on">Enable</span>
		</a>
{/if}
		<p>{$module.title}</p>
	</li>
{/foreach}
</ul>
</form>