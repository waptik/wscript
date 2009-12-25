{'add_partner'|@Lang|write_header:'h3'}
{$form_open}
<fieldset>
	<ul>
		<li>
			<label class="description" for="title">{'title'|@Lang}: <span class="required">*</span></label>
			<div>
				<input id="title" name= "title" class="element text large" value="{'title'|@form_validation_get_value}" />
				{'title'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_partner_title'|@Lang}</small></p>
			</div>
		</li>
		<li>
			<label class="description" for="link">{'link'|@Lang}: <span class="required">*</span></label>
			<div>
				<input id="link" name="link" class="element text large" value="{'link'|@form_validation_get_value}" />
				{'link'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_link'|@Lang}</small></p>
			</div>
		</li>
		<li>
			<label class="description" for="description">{'description'|@Lang}:</label>
			<div>
				<textarea name="description" class="element textarea medium">{'description'|@form_validation_get_value}</textarea>
				{'description'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_link_desc'|@Lang}</small></p>
			</div>
		</li>
	</ul>
</fieldset>

	<div class="job_indicators">
		{'save'|Lang|__button}
	</div>
</form>