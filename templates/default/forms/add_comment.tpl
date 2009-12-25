<form class="appnitro" method="post" action="">
	<input class="captcha" name="url" value="" />
	<fieldset>
		<ul>
			<li style="width:95%">
				<div class="left">
					<label class="description" for="c_name">{'c_name'|@Lang}: <span class="required">*</span></label>
					<span>
						<input id="c_name" name="c_name" class="element text" size="40" value="{'c_name'|@form_validation_get_value}" />
						{'c_name'|@form_validation_print_error}
					</span>
					
					<label class="description" for="c_email">{'c_email'|@Lang}: <span class="required">*</span></label>
					<span>
						<input id="c_email" name= "c_email" class="element text" size="40" value="{'c_email'|@form_validation_get_value}" />
						{'c_email'|@form_validation_print_error}
					</span>
					
					<label class="description" for="c_url">{'c_url'|@Lang}:</label>
					<span>
						<input id="c_url" name= "c_url" class="element text" size="40" value="{'c_url'|@form_validation_get_value}" />
						{'c_url'|@form_validation_print_error}
					</span>
				</div>
				
				<div class="right" style="text-align:left;padding-left:10px">
					<label class="description" for="c_comment">{'c_comment'|@Lang}: <span class="required">*</span></label>
					<div>
						<textarea name="c_comment" class="element textarea medium" rows="8">{'c_comment'|@form_validation_get_value}</textarea>
						{'c_comment'|@form_validation_print_error}
					</div>
				</div>
			</li>
		</ul>
	</fieldset>

	<div class="job_indicators">
		{'save'|Lang|__button}
	</div>
</form>