{$form_open}
<fieldset class="active">
	<ul>
		<li>
			<label class="description" for="description">{'description'|@Lang}:</label>
			<div>		
				<textarea name="description" id="description" class="element textarea medium">{'description'|@form_validation_get_value:$category->description}</textarea>
				{'description'|@form_validation_print_error}
			</div>
			<p class="guidelines">{'G_cat_description'|@Lang}</p>	
		</li>
		<li>
			<label class="description" for="meta_description">{'meta_description'|@Lang}:</label>
			<div>		
				<textarea name="meta_description" id="meta_description" class="element textarea medium">{'meta_description'|@form_validation_get_value:$category->meta_description}</textarea>
				{'meta_description'|@form_validation_print_error}
			</div>
			<p class="guidelines">{'G_cat_meta_d'|@Lang}</p>	
		</li>		
		<li>
			<label class="description" for="meta_keywords">{'meta_keywords'|@Lang}:</label>
			<div>	
				<textarea name="meta_keywords" id="meta_keywords" class="element textarea medium">{'meta_keywords'|@form_validation_get_value:$category->meta_keywords}</textarea>
				{'meta_keywords'|@form_validation_print_error}
			</div>
			<p class="guidelines">{'G_cat_meta_k'|@Lang}</p>	
		</li>
	</ul>
</fieldset>

	<div class="job_indicators">
		{'save'|Lang|__button}
	</div>
</form>