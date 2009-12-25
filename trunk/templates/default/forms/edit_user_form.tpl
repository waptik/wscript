{$form_open}
		<fieldset class="active">
			<ul>
				<li>
					<label class="description" for="username">{'username'|@Lang}: <span class="required">*</span></label>
					<div align="left">
						<input name="username" type="text" class="element text" size="45" id="username" value="{'username'|@form_validation_get_value:$row->Username}" />
						{'username'|@form_validation_print_error}
					</div>
				</li>

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

				<li>
					<label class="description" for="email">{'group'|@Lang}: <span class="required">*</span></label>
					<div align="left">
						<select name="user_group" class="element select large">
{foreach from=$groups item=group}
	{if $group->ID == $user_group || $row->Level_access == $group->ID }
							<option value="{$group->ID}" selected="selected">{$group->title}</option>
	{else}
							<option value="{$group->ID}">{$group->title}</option>
	{/if}
{foreachelse}
							<option value="">No groups defined</option>
{/foreach}
						</select>
					</div>
				</li>
			
				<li>
					<label class="description" for="auto_approve">{'auto_approve'|@Lang}</label>
					<div align="left">
	
						<input type="radio" class="element radio" name="auto_approve" value="1" id="auto_approve_Y" {'auto_approve'|form_validation_set_radio:1:$row->auto_approve} />
						<label class="choice" for="auto_approve_Y">{'yes'|@Lang}</label>
		
						<input type="radio" class="element radio" name="auto_approve" value="0" id="auto_approve_N" {'auto_approve'|form_validation_set_radio:0:$row->auto_approve} />
						<label class="choice" for="auto_approve_N">{'no'|@Lang}</label>
						{'auto_approve'|@form_validation_print_error}
						<p class="guidelines"><small>{'G_auto_approve'|@Lang}</small></p>
					</div>
				</li>

				<li>
					{'adm_update_profile'|Lang|__button}
				</li>
			</ul>
		</fieldset>
	</form>