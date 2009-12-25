{$form_open}		
<fieldset class="email">

		<input type="text" class="captcha" name="url" value="" />
		<ul>
			<li>
				<label class="description" for="f_email">{'email'|@Lang}: <span class="required">*</span></label>
				<div>
					<input name="f_email" type="text" class="element text large" id="f_email" value="{'f_email'|@form_validation_get_value}"  />
{'f_email'|@form_validation_print_error}
				</div>
			</li>
		</ul>
</fieldset>

	<div class="job_indicators">
		{'pass_send'|Lang|__button}
	</div>
</form>