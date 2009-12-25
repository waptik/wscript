{'add_groups'|@Lang|write_header:'h3'}
{$form_open}
	<fieldset>
		<ul>
			<li>
				<label class="description" for="title">{'title'|@Lang}: <span class="required">*</span></label>
				<span>
					<input id="title" name= "title" class="element text" size="40" value="{'title'|@form_validation_get_value}" />
					{'title'|@form_validation_print_error}
					<p class="guidelines"><small>{'G_group_title'|@Lang}</small></p>
				</span>
			</li>

			<li>
				<label class="description" for="login_redirect">{'login_redirect'|@Lang}:</label>
				<span>
					<input id="login_redirect" name= "login_redirect" class="element text" size="40" value="{'login_redirect'|@form_validation_get_value}" />
					{'login_redirect'|@form_validation_print_error}
					<p class="guidelines"><small>{'G_group_login_redir'|@Lang}</small></p>
				</span>
			</li>

			<li>
				<label class="description" for="logout_redirect">{'logout_redirect'|@Lang}:</label>
				<span>
					<input id="logout_redirect" name= "logout_redirect" class="element text" size="40" value="{'logout_redirect'|@form_validation_get_value}" />
					{'logout_redirect'|@form_validation_print_error}
					<p class="guidelines"><small>{'G_group_logout_redir'|@Lang}</small></p>
				</span>
			</li>

			<li>
				<label class="description" for="Message">{'description'|@Lang}:</label>
				<div>
					<textarea name="description" class="element textarea medium">{'description'|@form_validation_get_value}</textarea>
					{'description'|@form_validation_print_error}
					<p class="guidelines"><small>{'G_group_desc'|@Lang}</small></p>
				</div>
			</li>
		</ul>
	</fieldset>
	<div class="job_indicators">
		{'save'|Lang|__button}
	</div>
</form>