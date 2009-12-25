{$form_open}
<input type="text" name="url" class="captcha" />
<fieldset class="active">
		<ul>
			<li>
				<label class="description" for="email">{'email'|@Lang}: <span class="required">*</span></label>
				<div align="left">
					<input name="email" type="text" class="element text large" id="email" value="{'email'|@form_validation_get_value}" />
{'email'|@form_validation_print_error}
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
		{'contact_us'|Lang|__button}
	</div>
</form>