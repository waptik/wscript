{$form_open}
<fieldset class="active">

	<ul>
                <li>
                        <label class="description" for="file_based">{'naming_method'|@Lang} <span class="required">*</span></label>
                        <div align="left">
                                <input type="radio" name="naming_method"{'naming_method'|form_validation_set_radio:'file_based':'file_based'} value="file_based" id="file_based" />
                                <label class="choice" for="file_based">{'file_based'|@Lang}</label>
                                <input type="radio" name="naming_method"{'naming_method'|form_validation_set_radio:'folder_based'} value="folder_based" id="folder_based" />
                                <label class="choice" for="folder_based">{'folder_based'|@Lang}</label>
                                {'naming_method'|@form_validation_print_error}
                                <p class="guidelines"><small>{'G_naming_method'|@Lang}</small></p>
                        </div>
                </li>
		<li>
			<label class="description" for="bulk_method">{'bulk_method'|@Lang} <span class="required">*</span></label>
			<div align="left">
				
				<input onclick="hidediv('bulk_categories');" type="radio" name="bulk_method"{'bulk_method'|form_validation_set_radio:'structure':'structure'} value="structure" id="bulk_method_N" />
                                <label class="choice" for="bulk_method_N">{'structure'|@Lang}</label>
				
                                <input onclick="showdiv('bulk_categories');" type="radio" name="bulk_method"{'bulk_method'|form_validation_set_radio:'cat_direct'} value="cat_direct" id="bulk_method_Y" />
                                <label class="choice" for="bulk_method_Y">{'cat_direct'|@Lang}</label>

                                {'bulk_method'|@form_validation_print_error}
                                <p class="guidelines"><small>{'G_bulk_method'|@Lang}</small></p>
			</div>
		</li>
	</ul>
	{if $CI->form_validation->getField_value ( 'bulk_method' ) == 'cat_direct'}{assign var='display' value='block'}{else}{assign var='display' value='none'}{/if}
	<div id="bulk_categories" style="display:{$display}">
		<ul>	
			<li>
				<label class="description" for="cat_id">{'main_cat'|@Lang}: <span class="required">*</span></label>
				<div>
				{'cat_id'|@get_grant_categs_select:TRUE:FALSE:TRUE}
				{'cat_id'|@form_validation_print_error}
				</div>
			</li>
                        <li>
                        <label class="description" for="ignore_folders">{'ignore_folders'|@Lang} <span class="required">*</span></label>
                        <div align="left">
                                <input type="radio" name="ignore_folders"{'ignore_folders'|form_validation_set_radio:'ignore':'ignore'} value="ignore" id="ignore" />
                                <label class="choice" for="ignore">{'yes'|@Lang}</label>
                                <input type="radio" name="ignore_folders"{'ignore_folders'|form_validation_set_radio:'keep'} value="keep" id="keep" />
                                <label class="choice" for="keep">{'no'|@Lang}</label>
                                {'ignore_folders'|@form_validation_print_error}
                                <p class="guidelines"><small>{'G_ignore_folders'|@Lang}</small></p>
                        </div>
                </li>
		</ul>
	</div>
	
	<ul>
		<li>
			<label class="description" for="add_watermarks">{'add_watermarks'|@Lang}</label>
			<div align="left">
				<input type="radio" class="element radio" name="add_watermarks" value="1" id="add_watermarks_y" {'add_watermarks'|form_validation_set_radio:1} />
				<label class="choice" for="add_watermarks_y">{'yes'|@Lang}</label>
				<input type="radio" class="element radio" name="add_watermarks" value="0" id="add_watermarks_n" {'add_watermarks'|form_validation_set_radio:0:0} />
				<label class="choice" for="add_watermarks_n">{'no'|@Lang}</label>
				{'add_watermarks'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_bulk_add_watermarks'|@Lang}</small></p>
			</div>
		</li>
		
                
		<li>
			<label class="description" for="use_schedule">{'use_schedule'|@Lang} <span class="required">*</span></label>
			<div align="left">
				<input onclick="showdiv('schedule_options');" type="radio" name="use_schedule"{'use_schedule'|form_validation_set_radio:'yes'} value="yes" id="use_schedule_Y" />
                                <label class="choice" for="use_schedule_Y">{'yes'|@Lang}</label>

                                <input onclick="hidediv('schedule_options');" type="radio" name="use_schedule"{'use_schedule'|form_validation_set_radio:'no':'no'} value="no" id="use_schedule_N" />
                                <label class="choice" for="use_schedule_N">{'no'|@Lang}</label>

                                {'use_schedule'|@form_validation_print_error}
                                <p class="guidelines"><small>{'G_use_schedule'|@Lang}</small></p>
			</div>
		</li>
	</ul>
	{if $CI->form_validation->getField_value ( 'use_schedule' ) == 'yes'}{assign var='display_schedule' value='block'}{else}{assign var='display_schedule' value='none'}{/if}
	<div id="schedule_options" style="display:{$display_schedule}">
		<ul>	
			<li>
				<label class="description" for="schedule">{'schedule_options'|@Lang}: <span class="required">*</span></label>
				<div class="left">
					<input type="text" class="element text large" name="schedule_amount" id="schedule_amount" value="{'schedule_amount'|form_validation_get_value}" />
					<label for="schedule_amount">{'schedule_amount'|Lang}</label>
					{'schedule_amount'|@form_validation_print_error}
				</div>
				<div class="right">
					<select name="schedule_interval" id="schedule_interval" class="element select large">
						<option value="1"{'schedule_interval'|form_validation_set_select:1}>1 hours</option>
						<option value="6"{'schedule_interval'|form_validation_set_select:6}>6 hours</option>
						<option value="12"{'schedule_interval'|form_validation_set_select:12}>12 hours</option>
						<option value="24"{'schedule_interval'|form_validation_set_select:24}>24 hours</option>
					</select>
					<label for="schedule_interval">{'schedule_interval'|Lang}</label>
					{'schedule_interval'|@form_validation_print_error}
				</div>
			</li>
		</ul>
	</div>
	
</fieldset>

	<div class="job_indicators">
		{'save'|Lang|__button}
	</div>
</form>