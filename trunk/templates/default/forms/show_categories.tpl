{$form_open}
{if $categories!=FALSE}
<div id="sortableContainer">
	<ul id="sortableList" style="width:auto!important;">	
{foreach from=$categories->result() item=record}
		<li class="ui-state-default" style="width:512px!important;">
			<input type="hidden" name="id[]" value="{$record->ID}" />
			{if $CI->permissions->checkPermissions(array(25))}
				<a href="javascript:dialog(800,650,'{'edit_cat_option'|@Lang}',true,true,'{"categories/edit_cat_meta/`$record->ID`"|site_url}');" title="{'cat_details'|@get_tooltip:'title'} - {'cat_details'|@get_tooltip:'content'}" class="thickbox tooltip">
					<span class="ui-icon ui-icon-pencil" unselectable="on" style="-moz-user-select: none;">{'edit_cat_option'|@Lang}</span>
				</a>
			{/if}
		
			{if $CI->permissions->checkPermissions(array(23))}
				<a href="{"categories/index/`$record->ID`"|@site_url}" title="{'manage_subcats'|@get_tooltip:'title'} - {'manage_subcats'|@get_tooltip:'content'}" class="tooltip">
					<span class="ui-icon ui-icon-newwin" unselectable="on" style="-moz-user-select: none;">{'manage_subcats'|@get_tooltip:'title'}</span>
				</a>
			{/if}
	                
	                {if $CI->permissions->checkPermissions(array(25))}
				<a href="javascript:dialog(800,300,'{'migrate_wallpapers'|@Lang}',true,true,'{"categories/migrate/`$record->ID`"|site_url}');" title="{'migrate_wallpapers'|Lang} - {'migrate_details'|Lang}" class="tooltip thickbox">
					<span class="ui-icon ui-icon-transferthick-e-w" unselectable="on" style="-moz-user-select: none;">{'migrate_wallpapers'|Lang}</span>
				</a>
			{/if}
	
			{if $record->is_locked == 0 && $CI->permissions->checkPermissions(array(27))}
				<a href="{"categories/options/`$record->ID`/lock"|site_url}" onclick="showUpdate();" title="{'lock_cat'|@get_tooltip:'title'} - {'lock_cat'|@get_tooltip:'content'}" class="tooltip">
					<span class="ui-icon ui-icon-locked" unselectable="on" style="-moz-user-select: none;">{'lock_cat'|@get_tooltip:'title'}</span>
				</a>
	                
			{elseif $CI->permissions->checkPermissions(array(28))}
				<a href="{"categories/options/`$record->ID`/unlock"|site_url}" onclick="showUpdate();" title="{'unlock_cat'|@get_tooltip:'title'} - {'unlock_cat'|@get_tooltip:'content'}" class="tooltip">
					<span class="ui-icon ui-icon-unlocked" unselectable="on" style="-moz-user-select: none;">{'unlock_cat'|@get_tooltip:'title'}</span>
				</a>		
			{/if}
	
			{if $CI->permissions->checkPermissions(array(26))}
				<a href="{"categories/options/`$record->ID`/delete"|site_url}" onclick="showUpdate();" title="{'delete_cat'|@get_tooltip:'title'} - {'delete_cat'|@get_tooltip:'content'}" class="tooltip">
					<span class="ui-icon ui-icon-circle-close" unselectable="on" style="-moz-user-select: none;">{'delete_cat'|@get_tooltip:'title'}</span>
				</a>
			{/if}
	                
	                <input name="name[]" type="text" id="name[]" onclick="javascript:this.focus();this.select();" value="{$record->title}"  class="element text" style="width:145px!important" />
			<span class="walls_ctr"><b>{$record->items_counter}</b> {'wallpapers'|@Lang|ucfirst}</span>
			<span class="categs_ctr"><b>{$record->subcats_counter}</b> {'subcategories'|@Lang|ucfirst}</span>
		</li>	
{/foreach}
	</ul>
</div>
{else}
	{$no_categories}
{/if}

{if $CI->permissions->checkPermissions(array(24))}

	<div style="margin:15px 0 15px 0">
	{'add_categories'|@Lang|write_header:'h3'}
	<fieldset class="active" id="add_categories">
		<ul id="inputs_receiver">
{section name=inputs start=0 loop=4}
			<li>
				<div align="left">
					<input name="newtitle[]" type="text" id="newtitle[]"  class="element text large" />
				</div>
			</li>
{/section}
		</ul>
		<ul>
			<li>
				<input name="fields_nr" type="text" id="fields_nr"  class="element text small" value="1" style="float:left;margin:3px 8px 0 0;padding:6px" />
				{$add_fields_button}
			</li>
		</ul>
	</fieldset>

	<div class="job_indicators">
		{'save'|Lang|__button}
	</div>
	</div>
{/if}

</form>
<div class="clear"></div>