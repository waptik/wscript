{$form_open}
<fieldset class="active">
	<ul>

		<li>
			<label class="description" for="from_cat">{'from_cat'|@Lang}: <span class="required">*</span></label>
			<div>
			{'from_cat'|@get_grant_categs_select}
			{'from_cat'|@form_validation_print_error}
			</div>
		</li>

                <li>
			<label class="description" for="to_cat">{'to_cat'|@Lang}: <span class="required">*</span></label>
			<div>
                        {assign var=post_value value='to_cat'|@form_validation_get_value}
			{'to_cat'|@get_grant_categs_select}
			{'to_cat'|@form_validation_print_error}
			</div>
		</li>
	</ul>
</fieldset>

	<div class="job_indicators">
		{'migrate'|Lang|__button}
	</div>
</form>