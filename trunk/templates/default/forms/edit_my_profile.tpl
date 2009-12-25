<h3 class="headers gray">{'update_profile'|@Lang}</h3>
{$form_open}
		<fieldset class="active">
			<ul>
		{if $smarty.const.ALLOW_USERNAME_CHANGE }
					<li>
						<label class="description" for="username">{'username'|@Lang}: <span class="required">*</span></label>
						<div align="left">
							<input name="username" type="text" class="element text" size="45" id="username" value="{'username'|@form_validation_get_value:$row->Username}" />
							{'username'|@form_validation_print_error}
						</div>
					</li>
		{/if}
				<li>
					<label class="description" for="password">{'password'|@Lang}:</label>
					<div align="left">
						<input name="password" type="password" class="element text" size="45" id="password" value="{'password'|@form_validation_get_value}" />
						{'password'|@form_validation_print_error}
					</div>
				</li>

				<li>
					<label class="description" for="password_confirmed">{'password_re'|@Lang}:</label>
					<div align="left">
						<input name="password_confirmed" type="password" class="element text" size="45" id="password_confirmed" value="{'password_confirmed'|@form_validation_get_value}" />
						{'password_confirmed'|@form_validation_print_error}
					</div>
				</li>

				<li>
					<label class="description" for="email">{'email'|@Lang}: <span class="required">*</span></label>
					<div align="left">
						<input name="email" type="text" class="element text" size="45" id="email" value="{'email'|@form_validation_get_value:$row->Email}" />
						{'email'|@form_validation_print_error}
					</div>
				</li>
			</ul>
		</fieldset>

	<div class="job_indicators">
		{'update_profile'|Lang|__button}
	</div>
	</form>