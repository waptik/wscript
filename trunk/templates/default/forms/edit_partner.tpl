<h3 class="headers gray">{'edit_partner'|@Lang}</h3>
{$form_open}
<fieldset>
	<ul>
		<li>
			<label class="description" for="title">{'title'|@Lang}: <span class="required">*</span></label>
			<div>
				<input id="title" name= "title" class="element text large" value="{'title'|@form_validation_get_value:$row->title}" />
				{'title'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_partner_title'|@Lang}</small></p>
		</li>

		<li>
			<label class="description" for="link">{'link'|@Lang}: <span class="required">*</span></label>
			<div>
				<input id="link" name="link" class="element text large" value="{'link'|@form_validation_get_value:$row->link}" />
				{'link'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_link'|@Lang}</small></p>
		</li>

		<li>
			<label class="description" for="description">{'description'|@Lang}:</label>
			<div>
				<textarea name="description" class="element textarea medium">{'description'|@form_validation_get_value:$row->description}</textarea>
				{'description'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_link_desc'|@Lang}</small></p>
		</li>

		<li>
			{'save'|Lang|__button}
		</li>
	</ul>
</fieldset>
</form>