<h3 class="headers gray">#{$row->ID} {$row->file_title}</h3>
<form action="{"wallpapers/do_bulk_edit/`$row->ID`"|site_url}" name="form_{$row->ID}" id="form_{$row->ID}" onsubmit="return false;">
	<fieldset>
                {$message}
		<ul>
                        <li>
                                <div style="float:left;width:330px">
                                        <div class="bulk_edit_misc">
                                                <ul class="b_left">
                                                        <li>{'title_duplicates'|Lang|ucfirst}</li>
                                                        <li>{'author'|Lang|ucfirst}</li>
                                                        <li>{'category'|Lang|ucfirst}</li>
                                                        <li>{'downloads'|Lang|ucfirst}</li>
                                                </ul>
                                        
                                                <ul class="b_right">
                                                        <li>
                                                                {if $row->total_duplicates>0}
                                                                <font style="color:red;">
                                                                {else}
                                                                <font style="color:green;">{/if}{$row->total_duplicates}</font>
                                                        </li>
                                                        <li><a href="{"members/show/`$row->user_id`"|site_url}" target="_blank">{$row->user_id|get_username}</a></li>
                                                        <li><a href="{$row->cat_id|get_category_url}" target="_blank">{$row->cat_id|get_cat_title}</a></li>
                                                        <li>{$row->downloads}</li>
                                                </ul>
                                                <div class="clear"></div>
                                        </div>
                                        <input id="id_{$row->ID}" name="id_{$row->ID}" type="hidden" value="{$row->ID}" />
                                </div>
                                <div style="float:right;width:146px">
                                        <img src="{$row|get_wallpaper_url_location}thumb_{$row->wallpaper}" />
                                </div>
			</li>
			<li>
				<label class="description" for="title_{$row->ID}">{'title'|@Lang}:</label>
				<div class="left" style="padding-right:7px">
					<input id="title_{$row->ID}" name="title_{$row->ID}" class="element text medium" value="{$row->file_title}" />
				</div>
                                
				<div class="left">
					<input id="title_alias_{$row->ID}" name="title_alias_{$row->ID}" class="element text medium" value="{$row->title_alias}" />
                                        <label for="title_alias_{$row->ID}" class="left">{'title_alias'|@Lang}:</label>
				</div>
			</li>
			<li>
				<label class="description" for="tags_{$row->ID}">{'keywords'|@Lang}:</label>
				<div>
					<input id="tags_{$row->ID}" name="tags_{$row->ID}" class="element text large" value="{$row->tags}" />
				</div>
			</li>
			<li>
				<label class="description" for="description_{$row->ID}">{'description'|@Lang}:</label>
				<div>
					<textarea name="description_{$row->ID}" class="element textarea small">{$row->description}</textarea>
				</div>
			</li>
                        <li class="buttons">
				<button type="submit" class="positive" onclick="ajax_form('form_{$row->ID}','{"wallpapers/save_bulk_edit/`$row->ID`"|site_url}','response_{$row->ID}')">
				<img src="{$images_path}icons/tick.gif" alt="{'save'|@Lang}" /> 
					{'save'|@Lang}
				</button>
			</li>
		</ul>
	</fieldset>
</form>