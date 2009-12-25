{$form_open}
<fieldset class="register active">

		<input type="text" class="captcha" name="url" value="" />
		<ul>
			<li>
				<label class="description" for="r_username">{'username'|@Lang}: <span class="required">*</span></label>
				<div align="left">
					<input name="r_username" id="r_username" type="text" size="46" class="element text" value="{'r_username'|@form_validation_get_value}" />
				</div>
{'r_username'|@form_validation_print_error:'error_small'}
			</li>

			<li>
				<label class="description" for="r_password">{'password'|@Lang}: <span class="required">*</span></label>
				<span>
					<input name="r_password" id="r_password" type="password" size="20" class="element text" value="{'r_password'|@form_validation_get_value}" />
					<label>{'password'|@Lang}</label>
{'r_password'|@form_validation_print_error}
				</span>


				<span>
					<input name="r_password_confirmed" id="r_password_confirmed" type="password" size="20" class="element text" value="{'r_password_confirmed'|@form_validation_get_value}" />
					<label>{'password_re'|@Lang}</label>
{'r_password_confirmed'|@form_validation_print_error}
				</span>

			</li>

			<li>
				<label class="description" for="r_email">{'email'|@Lang}: <span class="required">*</span></label>
				<div align="left">
					<input name="r_email" id="r_email" type="text" class="element text" size="46" value="{'r_email'|@form_validation_get_value}" />
				</div>
{'r_email'|@form_validation_print_error}
			</li>
		</ul>
</fieldset>
	<div class="job_indicators">
		{'register'|Lang|__button}
	</div>
</form>