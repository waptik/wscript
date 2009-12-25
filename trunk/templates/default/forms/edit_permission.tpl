<h3 class="headers gray">{'edit_permission'|@Lang}</h3>
{$form_open}
<fieldset>
	<ul>
		<li>
			<label class="description" for="title">{'title'|@Lang}: <span class="required">*</span></label>
			<div>		
				<input id="title" name= "title" class="element text large" value="{'title'|@form_validation_get_value:$permission->label}" />
				{'title'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_permission_title'|@Lang}</small></p>
		</li>
		
		<li>
			<label class="description" for="types">{'types'|@Lang}:</label>
			<div>		
				<textarea name="types" id="types" class="element textarea small">{'types'|@form_validation_get_value:$permissions_list}</textarea>
				{'types'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_permission_type'|@Lang}</small></p>
		</li>
		
		<li class="buttons">
			<button type="submit" class="positive">
			<img src="{$images_path}icons/tick.gif" alt="{'save'|@Lang}" />
				{'save'|@Lang}
			</button>
		</li>
	</ul>
</fieldset>
</form>