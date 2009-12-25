{$form_open}
<fieldset class="active">
	<ul>
		<li>
			<label class="description" for="cat_id">{'main_cat'|@Lang}: <span class="required">*</span></label>
			<div>
				{'cat_id'|@get_grant_categs_select:TRUE:$row->cat_id:TRUE}
				{'cat_id'|@form_validation_print_error}
			</div>
		</li>
		<li>
			<label class="description" for="file_title">{'title'|@Lang}: <span class="required">*</span></label>
			<div>
				<input id="file_title" name= "file_title" class="element text large" size="40" value="{'file_title'|@form_validation_get_value:$row->file_title}" />
				{'file_title'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_wall_title'|@Lang}</small></p>
		</li>
		<li>
			<label class="description" for="title_alias">{'title_alias'|@Lang}:</label>
			<div>
				<input id="title_alias" name="title_alias" class="element text large" size="40" value="{'title_alias'|@form_validation_get_value:$row->title_alias}" />
				{'title_alias'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_title_alias'|@Lang}</small></p>
		</li>

		<li>
			<label class="description" for="wallpaper">{'wallpaper'|@Lang}:</label>
			<div id="ws_wallpapers_file">
				<input type="file" id="wallpapers[]" name="wallpapers[]" class="element file" value="" />
				{'wallpapers'|@form_validation_print_error}
			</div>
			<a href="javascript:void(0);" onclick="add_file_input();"><span style="padding:0" class="ui-icon ui-icon-circle-plus"></span></a>
			<p class="guidelines"><small>{'G_wallpaper'|@Lang}</small></p>
		</li>
		<li>
			<label class="description" for="description">{'description'|@Lang}:</label>
			<div>
				<textarea name="description" class="element textarea medium">{'description'|@form_validation_get_value:$row->description}</textarea>
				{'description'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_wall_description'|@Lang}</small></p>
		</li>
		<li>
			<label class="description" for="keywords">{'keywords'|@Lang}:</label>
			<div>
				<textarea name="keywords" class="element textarea small">{'keywords'|@form_validation_get_value:$row->tags}</textarea>
				{'keywords'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_wall_tags'|@Lang}</small></p>
			</div>
		</li>
		<li>
			{'save'|Lang|__button}
		</li>
	</ul>
</fieldset>
</form>