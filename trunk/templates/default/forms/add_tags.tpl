{'add_tags_to_exclusion'|@Lang|write_header:'h3'}
{$form_open}
<fieldset class="active">
<ul>
{section name=foo start=0 loop=4}
	<li>
		<div align="left">
			<input name="tags[]" type="text" id="tags[]"  class="element text large" />
		</div>
	</li>
{/section}
</ul>
</fieldset>

	<div class="job_indicators">
		{'add_to_exclusion'|Lang|__button}
	</div>
</form>