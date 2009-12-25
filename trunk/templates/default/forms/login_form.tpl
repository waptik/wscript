{$form_open}		
<fieldset class="active">

		<input type="text" class="captcha" name="url" value="" />
		<input type="hidden" name="redirect" value="{$value_redirect}" />
		<ul>
			<li>
				<label class="description" for="username">{'credentials'|@Lang}: <span class="required">*</span></label>
				<span>
					<input name="username" type="text" class="element text" size="20" id="username" value="{'username'|@form_validation_get_value}"  />
					<label>{'username'|@Lang}</label>
{'username'|@form_validation_print_error}
				</span>
				<span>
					<input name="password" type="password" class="element text" size="20" id="password" value="{'password'|@form_validation_get_value}"  />
					<label>{'password'|@Lang}</label>
{'password'|@form_validation_print_error}
				</span>
			</li>
{if $smarty.const.ALLOW_REMEMBER_ME }
			<li>
				<div>
					<input id="remember" name="remember" class="element checkbox" type="checkbox" />
					<label class="choice" for="remember">{'remember_me'|@Lang}</label>
				</div>
			</li>
{/if}
		</ul>			
</fieldset>

	<div class="job_indicators">
		{'login'|Lang|__button}{$fpass_btn}
	</div>
</form>