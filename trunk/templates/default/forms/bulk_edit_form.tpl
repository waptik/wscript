<form action="{"wallpapers/filter_bulk_edit"|site_url}" name="filter_bulk_edit" id="filter_bulk_edit" onsubmit="return false;" class="appnitro">
        <fieldset>
                <ul>
                        <li>
				<label class="description" for="tags">{'display'|@Lang}:</label>
				<div>
					<select name="output_limit" id="output_limit" class="element select large">
                                                <option value="10">10 {'wallpapers'|Lang|strtolower}</option>
                                                <option value="20">20 {'wallpapers'|Lang|strtolower}</option>
                                                <option value="30">30 {'wallpapers'|Lang|strtolower}</option>
                                                <option value="40">40 {'wallpapers'|Lang|strtolower}</option>
                                                <option value="50">50 {'wallpapers'|Lang|strtolower}</option>
                                        </select>
				</div>
			</li>
                        <li>
				<label class="description" for="title_contains">{'title_contains'|@Lang}:</label>
				<div>
					<input id="title_contains" name="title_contains" class="element text large" value="" />
				</div>
			</li>
                        <li>
				<label class="description">{'where'|Lang}:</label>
				<span>
					<input type="checkbox" id="title_is_duplicate" name="title_is_duplicate" class="element text" value="1" />
                                        <label class="choice" for="title_is_duplicate">{'title_is_duplicate'|Lang}</label>

                                        <input type="checkbox" id="description_is_empty" name="description_is_empty" class="element text" value="1" />
                                        <label class="choice" for="description_is_empty">{'description_is_empty'|Lang}</label>

                                        <input type="checkbox" id="keywords_is_empty" name="keywords_is_empty" class="element text" value="1" />
                                        <label class="choice" for="keywords_is_empty">{'keywords_is_empty'|Lang}</label>
				</span>
			</li>
                </ul>
        </fieldset>

	<div class="job_indicators">
		{$button}
	</div>
</form>
<div id="response"></div>