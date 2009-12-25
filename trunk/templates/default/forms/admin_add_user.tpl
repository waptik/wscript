{$form_open}
<fieldset class="active">
		<input type="hidden" name="_submit_check" value="1" /> 
		<ul>
			<li>
				<label class="description" for="user_group">{'group'|@Lang}: <span class="required">*</span></label>
				<div>
					<select name="user_group" id="user_group" class="element select large">
						<option value="">Please select a group</option>
		{foreach from=$groups item=group}
				{assign var='select' value=' '}
				{if form_validation_get_value('user_group') == $group->ID}
					{assign var='select' value='selected="selected"'}
				{/if}
						<option value="{$group->ID}" {$select}>{$group->title}</option>
		{foreachelse}
						<option value="">{'no_groups'|@Lang}</option>
		{/foreach}

					</select>
{'user_group'|@form_validation_print_error}
				</div>
			</li>

			<li>
				<label class="description" for="username">{'username'|@Lang}: <span class="required">*</span></label>
				<div>
					<input name="username" type="text" class="element text large" id="username" value="{'username'|@form_validation_get_value}" />
{'username'|@form_validation_print_error}
				</div>
			</li>

			<li>
				<label class="description" for="password">{'password'|@Lang}: <span class="required">*</span></label>
				<div>
					<input name="password" type="password" class="element text large" id="password" value="{'password'|@form_validation_get_value}" />
{'password'|@form_validation_print_error}
				</div>
			</li>

			<li>
				<label class="description" for="password_confirmed">{'password_re'|@Lang}: <span class="required">*</span></label>
				<div>
					<input name="password_confirmed" type="password" class="element text large" id="password_confirmed" value="{'password_confirmed'|@form_validation_get_value}" />
{'password_confirmed'|@form_validation_print_error}
				</div>
			</li>

			<li>
				<label class="description" for="email">{'email'|@Lang}: <span class="required">*</span></label>
				<div>
					<input name="email" type="text" class="element text large" id="email" value="{'email'|@form_validation_get_value}" />
{'email'|@form_validation_print_error}
				</div>
			</li>
			<li>
				<label class="description" for="IS_USER_ACTIVE">{'def_active'|@Lang}</label>
				<div align="left">
					<input type="radio" class="element radio" name="IS_USER_ACTIVE" value="1" id="IS_USER_ACTIVE_Y" {'IS_USER_ACTIVE'|form_validation_set_radio:1} />
					<label class="choice" for="IS_USER_ACTIVE_Y">{'yes'|@Lang}</label>
					<input type="radio" class="element radio" name="IS_USER_ACTIVE" value="0" id="IS_USER_ACTIVE_N" {'IS_USER_ACTIVE'|form_validation_set_radio:0} />
					<label class="choice" for="IS_USER_ACTIVE_N">{'no'|@Lang}</label>
{$error_REDIRECT_AFTER_CONFIRMATION}
				</div>
			</li>
			
				<li>
					<label class="description" for="auto_approve">{'auto_approve'|@Lang}</label>
					<div align="left">
		
						<input type="radio" class="element radio" name="auto_approve" value="1" id="auto_approve_Y" {'auto_approve'|form_validation_set_radio:1} />
						<label class="choice" for="auto_approve_Y">{'yes'|@Lang}</label>
		
						<input type="radio" class="element radio" name="auto_approve" value="0" id="auto_approve_N" {'auto_approve'|form_validation_set_radio:0} />
						<label class="choice" for="auto_approve_N">{'no'|@Lang}</label>
						{'auto_approve'|@form_validation_print_error}
						<p class="guidelines"><small>{'G_auto_approve'|@Lang}</small></p>
					</div>
				</li>
		</ul>
</fieldset>

	<div class="job_indicators">
		{'save'|Lang|__button}
	</div>
</form>