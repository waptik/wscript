<div id="comm">
	<div class="picture_wrapper_details">
		<img src="{''|base_url}uploads/watermark/watermark_bg_compiled.jpg?{''|print_unique_id}" id="watermark_preview" />
	</div>
</div><div class="clear"></div>
{'watermark_picture'|@Lang|write_header:'h3'}
<form name="form" action="{'admin/upload_watermark'|site_url}" method="POST" class="appnitro" enctype="multipart/form-data">
	<fieldset>
		<ul>
			<li>
				<div align="left">
					<input name="watermark_picture" type="file" class="element text large" id="watermark_picture" />
				</div>
			</li>
		</ul>
	</fieldset>

	<div class="job_indicators">
		{'upload'|Lang|__button}
	</div>
</form>
<div class="clear" style="margin-bottom:10px"></div>
{'watermark_settings'|@Lang|write_header:'h3'}
<form method="post" action="{'admin/save_watermark_settings'|site_url}" id="save_watermark_settings" class="appnitro">
<fieldset>
	<ul>
		<li>
			<label class="description" for="">{'enable_watermark'|Lang}</label>
			<div>
				<input class="element radio" type="radio" name="ENABLE_WATERMARK" value="1" id="ENABLE_WATERMARK_Y" {'ENABLE_WATERMARK'|form_validation_set_radio:1:$smarty.const.ENABLE_WATERMARK} />
				<label for="ENABLE_WATERMARK_Y" class="choice">{'yes'|@Lang}</label>
				<input class="element radio" type="radio" name="ENABLE_WATERMARK" value="0" id="ENABLE_WATERMARK_N" {'ENABLE_WATERMARK'|form_validation_set_radio:0:$smarty.const.ENABLE_WATERMARK} />
				<label for="ENABLE_WATERMARK_N" class="choice">{'no'|@Lang}</label>
			</div>
		</li>
		<li>
			<label class="description" for="">{'watermark_position'|Lang}</label>
			<div>
				<select name="WATERMARK_POSITION" id="WATERMARK_POSITION" class="element select medium">
					<option value="TL"{'WATERMARK_POSITION'|form_validation_set_select:'TL':$smarty.const.WATERMARK_POSITION}>{'top_left'|Lang}</option>
					<option value="TR"{'WATERMARK_POSITION'|form_validation_set_select:'TR':$smarty.const.WATERMARK_POSITION}>{'top_right'|Lang}</option>
					<option value="T"{'WATERMARK_POSITION'|form_validation_set_select:'T':$smarty.const.WATERMARK_POSITION}>{'top'|Lang}</option>
					<option value="L"{'WATERMARK_POSITION'|form_validation_set_select:'L':$smarty.const.WATERMARK_POSITION}>{'left'|Lang}</option>
					<option value="R"{'WATERMARK_POSITION'|form_validation_set_select:'R':$smarty.const.WATERMARK_POSITION}>{'right'|Lang}</option>
					<option value="BL"{'WATERMARK_POSITION'|form_validation_set_select:'BL':$smarty.const.WATERMARK_POSITION}>{'bottom_left'|Lang}</option>
					<option value="BR"{'WATERMARK_POSITION'|form_validation_set_select:'BR':$smarty.const.WATERMARK_POSITION}>{'bottom_right'|Lang}</option>
					<option value="B"{'WATERMARK_POSITION'|form_validation_set_select:'B':$smarty.const.WATERMARK_POSITION}>{'bottom'|Lang}</option>
				</select>
			</div>
		</li>
	</ul>
</fieldset>

	<div class="job_indicators">
		{'save'|Lang|__button}
	</div>
</form>
