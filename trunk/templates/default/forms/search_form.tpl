{$form_open}
	<fieldset>
		<ul>
			<li>
				<label class="description" for="search_for">{'search_for'|@Lang}: <span class="required">*</span></label>
				<div>
					<input id="search_for" name="search_for" class="element text large" value="{'search_for'|form_validation_get_value}" />
					{'search_for'|form_validation_print_error}
				</div>
			</li>

			<li>
				<label class="description" for="size">{'size'|@Lang}:</label>
				<div>
					<select name="size" id="size" class="element select large">
						<option value="all"{'size'|form_validation_set_select:'all':'all'}>{'all'|Lang}</option>

{if !empty($wide)}
						<option value="" disabled="disabled">{'wide'|Lang}</option>
						{foreach from=$wide item=value}
                                                        {assign var=opt_value value="`$value.width`X`$value.height`"}
                                                        <option value="{$opt_value}"{'size'|form_validation_set_select:$opt_value}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$opt_value}</option>
						{/foreach}
{/if}

{if !empty($normal)}
						<option value="" disabled="disabled">{'normal'|Lang}</option>
						{foreach from=$normal item=value}
                                                        {assign var=opt_value value="`$value.width`X`$value.height`"}
                                                        <option value="{$opt_value}"{'size'|form_validation_set_select:$opt_value}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$opt_value}</option>
						{/foreach}
{/if}

{if !empty($iphone)}
						<option value="" disabled="disabled">Iphone</option>
						{foreach from=$iphone item=value}
                                                        {assign var=opt_value value="`$value.width`X`$value.height`"}
                                                        <option value="{$opt_value}"{'size'|form_validation_set_select:$opt_value}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$opt_value}</option>
						{/foreach}
{/if}

{if !empty($psp)}
						<option value="" disabled="disabled">PSP</option>
						{foreach from=$psp item=value}
                                                        {assign var=opt_value value="`$value.width`X`$value.height`"}
                                                        <option value="{$opt_value}"{'size'|form_validation_set_select:$opt_value}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$opt_value}</option>
						{/foreach}
{/if}

{if !empty($hd)}
						<option value="" disabled="disabled">{'hd'|@Lang}</option>
						{foreach from=$hd item=value}
                                                        {assign var=opt_value value="`$value.width`X`$value.height`"}
                                                        <option value="{$opt_value}"{'size'|form_validation_set_select:$opt_value}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$opt_value}</option>
						{/foreach}
{/if}

{if !empty($multi)}
						<option value="" disabled="disabled">{'multi'|@Lang}</option>
						{foreach from=$multi item=value}
                                                        {assign var=opt_value value="`$value.width`X`$value.height`"}
                                                        <option value="{$opt_value}"{'size'|form_validation_set_select:$opt_value}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$opt_value}</option>
						{/foreach}
{/if}
					</select>
					{'login_redirect'|@form_validation_print_error}
				</div>
			</li>

			<li>
				<label class="description" for="category">{'category'|@Lang}:</label>
				<div>
					{'category'|@get_grant_categs_select}
					{'category'|@form_validation_print_error}
				</div>
			</li>
		</ul>
	</fieldset>

	<div class="job_indicators">
		{'search'|Lang|__button}
	</div>
</form>
<div id="wallpapers_wrp" style="margin-top:15px"></div>