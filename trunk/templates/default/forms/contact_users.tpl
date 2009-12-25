{$form_open}
	<fieldset class="active">
		<ul>
			<li>
				<label class="description" for="groups">{'select_mass_groups'|@Lang}: <span class="required">*</span></label>
{'groups'|@form_validation_print_error}
				<span>

{foreach from=$groups item=group}
					<input id="group_{$group->ID}" name="groups[]" class="element checkbox" type="checkbox" value="{$group->ID}" />
					<label class="choice" for="group_{$group->ID}">{$group->title}</label>
{foreachelse}
					{'no_groups'|@Lang}
{/foreach}

				</span>
			</li>
			<li class="section_break">&nbsp;</li>
			<li>
				<label class="description" for="groups">{'select_user_status'|@Lang}: <span class="required">*</span></label>
{'status'|@form_validation_print_error}
				<span>
					<input id="status_1" name="status[]" class="element checkbox" type="checkbox" value="1" />
					<label class="choice" for="status_1">{'active_user'|@Lang}</label>

					<input id="status_0" name="status[]" class="element checkbox" type="checkbox" value="0" />
					<label class="choice" for="status_0">{'inactive_user'|@Lang}</label>

					<input id="status_2" name="status[]" class="element checkbox" type="checkbox" value="2" />
					<label class="choice" for="status_2">{'suspended_user'|@Lang}</label>
				</span>
			</li>
			<li class="section_break">&nbsp;</li>
			<li>
				<label class="description" for="subject">{'subject'|@Lang}: <span class="required">*</span></label>
				<div align="left">
					<input name="subject" type="text" class="element text large" id="subject" value="{'subject'|@form_validation_get_value}" />
{'subject'|@form_validation_print_error}
				</div>
			</li>

			<li>
				<label class="description" for="message">{'message'|@Lang}:</label>
				<div>
					<textarea name="message" class="element textarea medium">{'message'|@form_validation_get_value}</textarea>
					{'message'|@form_validation_print_error}
				</div>				
			</li>
		</ul>
	</fieldset>

	<div class="job_indicators">
		{'send_email'|Lang|__button}
	</div>
</form>