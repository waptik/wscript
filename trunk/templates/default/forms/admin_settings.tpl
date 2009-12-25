<script type="text/javascript" language="javascript" charset="utf-8">
// <![CDATA[
	var TabSize = 7;
// ]]>
</script>
<ul id="s_tabs">
	<li><a href="javascript:void(0)" class="active" onClick="ShowTab(1)" id="tab1">{'db_details'|@Lang}</a></li>
	<li><a href="javascript:void(0)" onClick="ShowTab(2)" id="tab2">{'site_setts'|@Lang}</a></li>
	<li><a href="javascript:void(0)" onClick="ShowTab(3)" id="tab3">{'email_setts'|@Lang}</a></li>
	<li><a href="javascript:void(0)" onClick="ShowTab(4)" id="tab4">{'wallpaper_setts'|@Lang}</a></li>
	<li><a href="javascript:void(0)" onClick="ShowTab(5)" id="tab5">{'tags'|@Lang}</a></li>
	<li><a href="javascript:void(0)" onClick="ShowTab(6)" id="tab6">{'advertisements'|@Lang}</a></li>
	<li><a href="javascript:void(0)" onClick="ShowTab(7)" id="tab7">Design</a></li>
</ul>
<div class="clear"></div>
<form action="{"admin/save_admin_settings"|site_url}" method="post" class="appnitro" enctype="multipart/form-data" autocomplete="off">
	<input type="hidden" name="_submit_check" value="1" />
	<fieldset style="padding-top:10px">
		<div id="div1">
		<ul>
		<!-- ==================================	DATABASE ============================= -->
		<li>

			<label class="description" for="HOSTNAME">{'hostname'|@Lang}</label>
			<div align="left">
				<input name="HOSTNAME" type="text" class="element text large" id="HOSTNAME" value="{'HOSTNAME'|@form_validation_get_value:$smarty.const.HOSTNAME}" />
				{'HOSTNAME'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_hostname'|@Lang}</small></p>
		</li>

		<li>
			<label class="description" for="DATABASE">{'database'|@Lang}</label>
			<div align="left">
				<input name="DATABASE" type="text" class="element text large" id="DATABASE" value="{'DATABASE'|@form_validation_get_value:$smarty.const.DATABASE}" />
				{'DATABASE'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_database'|@Lang}</small></p>
		</li>

		<li>
			<label class="description" for="DBUSER">{'dbuser'|@Lang}</label>
			<div align="left">
				<input name="DBUSER" type="text" class="element text large" id="DBUSER" value="{'DBUSER'|@form_validation_get_value:$smarty.const.DBUSER}" />
				{'DBUSER'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_dbuser'|@Lang}</small></p>
		</li>

		<li>
			<label class="description" for="DBPASS">{'dbpass'|@Lang}</label>
			<div align="left">
				<input name="DBPASS" type="password" class="element text large" id="DBPASS" value="{'DBPASS'|@form_validation_get_value:$smarty.const.DBPASS}" />
				{'DBPASS'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_dbpass'|@Lang}</small></p>
		</li>
		</ul>
		</div>

		<div id="div2" style="display:none">
		<!-- ==================================	SITE SETTINGS ============================= -->
		<ul>
		<li>
			<label class="description" for="logo">Logo:</label>
			<div>
				<input type="file" id="logo" name="logo" class="element file" value="" />
				{'logo'|@form_validation_print_error}
			</div>
		</li>
		<li>
			<label class="description" for="APPLICATION_URL">{'app_url'|@Lang}</label>
			<div align="left">
				<input name="APPLICATION_URL" type="text" class="element text large" id="APPLICATION_URL" value="{'APPLICATION_URL'|@form_validation_get_value:$smarty.const.APPLICATION_URL}" />
				{'APPLICATION_URL'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_app_url'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="APPLICATION_FOLDER">{'app_folder'|@Lang}</label>
			<div align="left">
				<input name="APPLICATION_FOLDER" type="text" class="element text large" id="APPLICATION_FOLDER" value="{'APPLICATION_FOLDER'|@form_validation_get_value:$smarty.const.APPLICATION_FOLDER}" />
				{'APPLICATION_FOLDER'|@form_validation_print_error}
				<p class="guidelines"><small>{'app_folder'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="ADMIN_EMAIL">{'admin_email'|@Lang}</label>
			<div align="left">
				<input name="ADMIN_EMAIL" type="text" class="element text large" id="ADMIN_EMAIL" value="{'ADMIN_EMAIL'|@form_validation_get_value:$smarty.const.ADMIN_EMAIL}" />
				{'ADMIN_EMAIL'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_admin_email'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="DOMAIN_NAME">{'domain_name'|@Lang}</label>
			<div align="left">
				<input name="DOMAIN_NAME" type="text" class="element text large" id="DOMAIN_NAME" value="{'DOMAIN_NAME'|@form_validation_get_value:$smarty.const.DOMAIN_NAME}" />
				{'DOMAIN_NAME'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_domain_name'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="SITE_NAME">{'site_name'|@Lang}</label>
			<div align="left">
				<input name="SITE_NAME" type="text" class="element text large" id="SITE_NAME" value="{'SITE_NAME'|@form_validation_get_value:$smarty.const.SITE_NAME}" />
				{'SITE_NAME'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_site_name'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="SITE_SLOGAN">{'site_slogan'|@Lang}</label>
			<div align="left">
				<input name="SITE_SLOGAN" type="text" class="element text large" id="SITE_SLOGAN" value="{'SITE_SLOGAN'|@form_validation_get_value:$smarty.const.SITE_SLOGAN}" />
				{'SITE_SLOGAN'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_site_slogan'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="TRACKING_CODE">{'tracking_code'|@Lang}</label>
			<div align="left">
				<textarea name="TRACKING_CODE" class="element textarea small" id="TRACKING_CODE">{'TRACKING_CODE'|@form_validation_get_value:$smarty.const.TRACKING_CODE|base64_decode}</textarea>
				{'TRACKING_CODE'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_tracking_code'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="LANG_TYPE">{'global_language'|@Lang}</label>
			<div align="left">
			<select name="LANG_TYPE" id="LANG_TYPE" class="element select large">
			{foreach from=$languages item=language}
				<option value="{$language}"{'LANG_TYPE'|form_validation_set_select:$language:$smarty.const.LANG_TYPE}>{$language}</option>
			{/foreach}
			</select>
			{'LANG_TYPE'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_site_language'|@Lang}</small></p>
		</li>

		<li>
			<label class="description" for="DEFAULT_TEMPLATE">{'default_template'|@Lang}</label>
			<div align="left">
			<select name="DEFAULT_TEMPLATE" id="DEFAULT_TEMPLATE" class="element select large">
			{foreach from=$templates item=template}
				<option value="{$template}"{'DEFAULT_TEMPLATE'|form_validation_set_select:$template:$smarty.const.DEFAULT_TEMPLATE}>{$template}</option>
			{/foreach}
			</select>
			{'DEFAULT_TEMPLATE'|@form_validation_print_error}
			</div>
			<p class="guidelines"><small>{'G_default_template'|@Lang}</small></p>
		</li>

		<li>
			<label class="description" for="RUN_ON_DEVELOPMENT">{'run_development'|@Lang}</label>
			<div align="left">
			<input type="radio" name="RUN_ON_DEVELOPMENT" value="1" id="RUN_ON_DEVELOPMENT_Y" {'RUN_ON_DEVELOPMENT'|form_validation_set_radio:1:$smarty.const.RUN_ON_DEVELOPMENT} />
			<label class="choice" for="RUN_ON_DEVELOPMENT_Y">{'yes'|@Lang}</label>
			<input type="radio" name="RUN_ON_DEVELOPMENT" value="0" id="RUN_ON_DEVELOPMENT_N" {'RUN_ON_DEVELOPMENT'|form_validation_set_radio:0:$smarty.const.RUN_ON_DEVELOPMENT} />
			<label class="choice" for="RUN_ON_DEVELOPMENT_N">{'no'|@Lang}</label>
			{'RUN_ON_DEVELOPMENT'|@form_validation_print_error}
			<p class="guidelines"><small>{'G_run_development'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="ENABLE_MOD_REWRITE">{'enable_mod_rewrite'|@Lang}</label>
			<div align="left">
			<input type="radio" name="ENABLE_MOD_REWRITE" value="1" id="ENABLE_MOD_REWRITE_Y" {'ENABLE_MOD_REWRITE'|form_validation_set_radio:1:$smarty.const.ENABLE_MOD_REWRITE} />
			<label class="choice" for="ENABLE_MOD_REWRITE_Y">{'yes'|@Lang}</label>
			<input type="radio" name="ENABLE_MOD_REWRITE" value="0" id="ENABLE_MOD_REWRITE_N" {'ENABLE_MOD_REWRITE'|form_validation_set_radio:0:$smarty.const.ENABLE_MOD_REWRITE} />
			<label class="choice" for="ENABLE_MOD_REWRITE_N">{'no'|@Lang}</label>
			{'ENABLE_MOD_REWRITE'|@form_validation_print_error}
			<p class="guidelines"><small>{'G_enable_mod_rewrite'|@Lang}</small></p>
			</div>
		</li>
                
                <li>
			<label class="description" for="SITE_HAS_ADULT_MATERIALS">{'site_has_adult'|@Lang}</label>
			<div align="left">
			<input type="radio" name="SITE_HAS_ADULT_MATERIALS" value="1" id="SITE_HAS_ADULT_MATERIALS_Y" {'SITE_HAS_ADULT_MATERIALS'|form_validation_set_radio:1:$smarty.const.SITE_HAS_ADULT_MATERIALS} />
			<label class="choice" for="SITE_HAS_ADULT_MATERIALS_Y">{'yes'|@Lang}</label>
			<input type="radio" name="SITE_HAS_ADULT_MATERIALS" value="0" id="SITE_HAS_ADULT_MATERIALS_N" {'SITE_HAS_ADULT_MATERIALS'|form_validation_set_radio:0:$smarty.const.SITE_HAS_ADULT_MATERIALS} />
			<label class="choice" for="SITE_HAS_ADULT_MATERIALS_N">{'no'|@Lang}</label>
			{'SITE_HAS_ADULT_MATERIALS'|@form_validation_print_error}
			<p class="guidelines"><small>{'G_site_has_adult'|@Lang}</small></p>
			</div>
		</li>
		</ul>

		
		<ul>
			<li>
				<label class="description" for="MAX_COLORS">{'max_colors'|@Lang}</label>
				<div align="left">
					<input name="MAX_COLORS" type="text" class="element text small" id="MAX_COLORS" value="{'MAX_COLORS'|@form_validation_get_value:$smarty.const.MAX_COLORS}" />
					{'MAX_COLORS'|@form_validation_print_error}
					<p class="guidelines"><small>{'G_max_colors'|@Lang}</small></p>
				</div>
			</li>
		</ul>

		</div>

		<div id="div3" style="display:none">
		<!-- ==================================	EMAIL SETTINGS ============================= -->
		<ul>
		<li>
			<label class="description" for="USE_SMTP">{'use_smtp'|@Lang}</label>
			<div align="left">
				<input type="radio" class="element radio" name="USE_SMTP" value="1" id="USE_SMTP_Y" {'USE_SMTP'|form_validation_set_radio:1:$smarty.const.USE_SMTP} />
				<label class="choice" for="USE_SMTP_Y">{'yes'|@Lang}</label>
				<input type="radio" class="element radio" name="USE_SMTP" value="0" id="USE_SMTP_N" {'USE_SMTP'|form_validation_set_radio:0:$smarty.const.USE_SMTP} />
				<label class="choice" for="USE_SMTP_N">{'no'|@Lang}</label>
				{'USE_SMTP'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_use_SMTP'|@Lang}</small></p>
			</div>
		</li>
		<li>
			<label class="description" for="SMTP_PORT">{'smtp_port'|@Lang}</label>
			<div align="left">
				<input name="SMTP_PORT" type="text" class="element text large" id="SMTP_PORT" value="{'SMTP_PORT'|@form_validation_get_value:$smarty.const.SMTP_PORT}" />
				{'SMTP_PORT'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_SMTP_port'|@Lang}</small></p>
			</div>
		</li>
		<li>
			<label class="description" for="SMTP_HOST">{'smtp_host'|@Lang}</label>
			<div align="left">
				<input name="SMTP_HOST" type="text" class="element text large" id="SMTP_HOST" value="{'SMTP_HOST'|@form_validation_get_value:$smarty.const.SMTP_HOST}" />
				{'SMTP_HOST'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_SMTP_host'|@Lang}</small></p>
			</div>
		</li>
		<li>
			<label class="description" for="SMTP_USER">{'smtp_user'|@Lang}</label>
			<div align="left">
				<input name="SMTP_USER" type="text" class="element text large" id="SMTP_USER" value="{'SMTP_USER'|@form_validation_get_value:$smarty.const.SMTP_USER}" />
				{'SMTP_USER'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_SMTP_user'|@Lang}</small></p>
			</div>
		</li>
		<li>
			<label class="description" for="SMTP_PASS">{'smtp_pass'|@Lang}</label>
			<div align="left">
				<input name="SMTP_PASS" type="password" class="element text large" id="SMTP_PASS" value="{'SMTP_PASS'|@form_validation_get_value:$smarty.const.SMTP_PASS}" />
				{'SMTP_PASS'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_SMTP_pass'|@Lang}</small></p>
			</div>
		</li>
		<li>
			<label class="description" for="MAIL_IS_HTML">{'mail_html'|@Lang}</label>
			<div align="left">
				<input type="radio" class="element radio" name="MAIL_IS_HTML" value="1" id="MAIL_IS_HTML_Y" {'MAIL_IS_HTML'|form_validation_set_radio:1:$smarty.const.MAIL_IS_HTML} />
				<label class="choice" for="MAIL_IS_HTML_Y">{'yes'|@Lang}</label>
				<input type="radio" class="element radio" name="MAIL_IS_HTML" value="0" id="MAIL_IS_HTML_N" {'MAIL_IS_HTML'|form_validation_set_radio:0:$smarty.const.MAIL_IS_HTML} />
				<label class="choice" for="MAIL_IS_HTML_N">{'no'|@Lang}</label>
				{'MAIL_IS_HTML'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_mail_is_HTML'|@Lang}</small></p>
			</div>
		</li>
		</ul>
		</div>

		<div id="div4" style="display:none">
		<!-- ==================================	WALLPAPER SETTINGS ============================= -->
		<ul>

		<li>
			<label class="description" for="WALLPAPER_DISPLAY_ORDER">{'wall_display_order'|@Lang}</label>
			<div align="left">

			<select name="WALLPAPER_DISPLAY_ORDER" class="element select medium" id="WALLPAPER_DISPLAY_ORDER">
				<option value="RAND()"{'WALLPAPER_DISPLAY_ORDER'|form_validation_set_select:'RAND()':$smarty.const.WALLPAPER_DISPLAY_ORDER}>{'random'|@Lang}</option>
				<option value="date_added"{'WALLPAPER_DISPLAY_ORDER'|form_validation_set_select:'date_added':$smarty.const.WALLPAPER_DISPLAY_ORDER}>{'date_added'|@Lang}</option>
				<option value="hits"{'WALLPAPER_DISPLAY_ORDER'|form_validation_set_select:'hits':$smarty.const.WALLPAPER_DISPLAY_ORDER}>{'popularity'|@Lang}</option>
				<option value="rating"{'WALLPAPER_DISPLAY_ORDER'|form_validation_set_select:'rating':$smarty.const.WALLPAPER_DISPLAY_ORDER}>{'rating'|@Lang}</option>
			</select>
			<select name="WALLPAPER_ORDER_TYPE" class="element select" id="WALLPAPER_ORDER_TYPE">
				<option value="DESC"{'WALLPAPER_ORDER_TYPE'|form_validation_set_select:'DESC'}>{'descending'|@Lang}</option>
				<option value="ASC"{'WALLPAPER_ORDER_TYPE'|form_validation_set_select:'ASC'}>{'ascending'|@Lang}</option>
			</select>
			{'WALLPAPER_ORDER_TYPE'|@form_validation_print_error}
			<p class="guidelines"><small>{'G_wall_display_order'|@Lang}</small></p>
			</div>
		</li>
		
		<li>
			<label class="description" for="WALLPAPER_QUALITY">{'wallpaper_quality'|@Lang}</label>
			<div align="left">
				<input name="WALLPAPER_QUALITY" type="text" class="element text small" id="WALLPAPER_QUALITY" value="{'WALLPAPER_QUALITY'|@form_validation_get_value:$smarty.const.WALLPAPER_QUALITY}" /> %
				{'WALLPAPER_QUALITY'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_wallpaper_quality'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="MIN_USR_VOTES_HOMEPAGE">{'min_user_votes'|@Lang}</label>
			<div align="left">
				<input name="MIN_USR_VOTES_HOMEPAGE" type="text" class="element text small" id="MIN_USR_VOTES_HOMEPAGE" value="{'MIN_USR_VOTES_HOMEPAGE'|@form_validation_get_value:$smarty.const.MIN_USR_VOTES_HOMEPAGE}" />
				{'MIN_USR_VOTES_HOMEPAGE'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_min_user_votes'|@Lang}</small></p>
			</div>
		</li>



		<li>
			<label class="description" for="MIN_WALL_VOTES_HOMEPAGE">{'min_wall_votes'|@Lang}</label>
			<div align="left">
				<input name="MIN_WALL_VOTES_HOMEPAGE" type="text" class="element text small" id="MIN_WALL_VOTES_HOMEPAGE" value="{'MIN_WALL_VOTES_HOMEPAGE'|@form_validation_get_value:$smarty.const.MIN_WALL_VOTES_HOMEPAGE}" />
				{'MIN_WALL_VOTES_HOMEPAGE'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_min_wall_votes'|@Lang}</small></p>
			</div>
		</li>


		<li>
			<label class="description" for="CATEGORY_COLUMNS">{'category_columns'|@Lang}</label>
			<div align="left">
				<input name="CATEGORY_COLUMNS" type="text" class="element text small" id="CATEGORY_COLUMNS" value="{'CATEGORY_COLUMNS'|@form_validation_get_value:$smarty.const.CATEGORY_COLUMNS}" />
				{'MIN_WALL_VOTES_HOMEPAGE'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_category_columns'|@Lang}</small></p>
			</div>
		</li>


		<li>
			<label class="description" for="GUESTS_CAN_DOWNLOAD">{'guests_can_download'|@Lang}</label>
			<div align="left">
				<input type="radio" class="element radio" name="GUESTS_CAN_DOWNLOAD" value="1" id="GUESTS_CAN_DOWNLOAD_Y" {'GUESTS_CAN_DOWNLOAD'|form_validation_set_radio:1:$smarty.const.GUESTS_CAN_DOWNLOAD} />
				<label class="choice" for="GUESTS_CAN_DOWNLOAD_Y">{'yes'|@Lang}</label>
				<input type="radio" class="element radio" name="GUESTS_CAN_DOWNLOAD" value="0" id="GUESTS_CAN_DOWNLOAD_N" {'GUESTS_CAN_DOWNLOAD'|form_validation_set_radio:0:$smarty.const.GUESTS_CAN_DOWNLOAD} />
				<label class="choice" for="GUESTS_CAN_DOWNLOAD_N">{'no'|@Lang}</label>
				{'GUESTS_CAN_DOWNLOAD'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_guests_can_download'|@Lang}</small></p>
			</div>
		</li>


		<li>
			<label class="description" for="GUESTS_CAN_UPLOAD">{'guests_can_upload'|@Lang}</label>
			<div align="left">
				<input type="radio" class="element radio" name="GUESTS_CAN_UPLOAD" value="1" id="GUESTS_CAN_UPLOAD_Y" {'GUESTS_CAN_UPLOAD'|form_validation_set_radio:1:$smarty.const.GUESTS_CAN_UPLOAD} />
				<label class="choice" for="GUESTS_CAN_UPLOAD_Y">{'yes'|@Lang}</label>
				<input type="radio" class="element radio" name="GUESTS_CAN_UPLOAD" value="0" id="GUESTS_CAN_UPLOAD_N" {'GUESTS_CAN_UPLOAD'|form_validation_set_radio:0:$smarty.const.GUESTS_CAN_UPLOAD} />
				<label class="choice" for="GUESTS_CAN_UPLOAD_N">{'no'|@Lang}</label>
				{'GUESTS_CAN_UPLOAD'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_guests_can_upload'|@Lang}</small></p>
			</div>
		</li>


		<li>
			<label class="description" for="OPEN_WALLPAPERS_IN_NEW_WINDOW">{'open_wallpapers_in_new_window'|@Lang}</label>
			<div align="left">
				<input type="radio" class="element radio" name="OPEN_WALLPAPERS_IN_NEW_WINDOW" value="1" id="OPEN_WALLPAPERS_IN_NEW_WINDOW_Y" {'OPEN_WALLPAPERS_IN_NEW_WINDOW'|form_validation_set_radio:1:$smarty.const.OPEN_WALLPAPERS_IN_NEW_WINDOW} />
				<label class="choice" for="OPEN_WALLPAPERS_IN_NEW_WINDOW_Y">{'yes'|@Lang}</label>
				<input type="radio" class="element radio" name="OPEN_WALLPAPERS_IN_NEW_WINDOW" value="0" id="OPEN_WALLPAPERS_IN_NEW_WINDOW_N" {'OPEN_WALLPAPERS_IN_NEW_WINDOW'|form_validation_set_radio:0:$smarty.const.OPEN_WALLPAPERS_IN_NEW_WINDOW} />
				<label class="choice" for="OPEN_WALLPAPERS_IN_NEW_WINDOW_N">{'no'|@Lang}</label>
				{'OPEN_WALLPAPERS_IN_NEW_WINDOW'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_open_wallpapers_in_new_window'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="AUTO_APROVE_COMMENTS">{'auto_approve_comments'|@Lang}</label>
			<div align="left">
				<input type="radio" class="element radio" name="AUTO_APROVE_COMMENTS" value="1" id="AUTO_APROVE_COMMENTS_Y" {'AUTO_APROVE_COMMENTS'|form_validation_set_radio:1:$smarty.const.AUTO_APROVE_COMMENTS} />
				<label class="choice" for="AUTO_APROVE_COMMENTS_Y">{'yes'|@Lang}</label>
				<input type="radio" class="element radio" name="AUTO_APROVE_COMMENTS" value="0" id="AUTO_APROVE_COMMENTS_N" {'AUTO_APROVE_COMMENTS'|form_validation_set_radio:0:$smarty.const.AUTO_APROVE_COMMENTS} />
				<label class="choice" for="AUTO_APROVE_COMMENTS_N">{'no'|@Lang}</label>
				{'AUTO_APROVE_COMMENTS'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_auto_approve_comments'|@Lang}</small></p>
			</div>
		</li>

		<li>
			<label class="description" for="SHOW_CATEGORY_COUNTERS">{'show_cat_counters'|@Lang}</label>
			<div align="left">
				<input type="radio" class="element radio" name="SHOW_CATEGORY_COUNTERS" value="1" id="SHOW_CATEGORY_COUNTERS_Y" {'SHOW_CATEGORY_COUNTERS'|form_validation_set_radio:1:$smarty.const.SHOW_CATEGORY_COUNTERS} />
				<label class="choice" for="SHOW_CATEGORY_COUNTERS_Y">{'yes'|@Lang}</label>
				<input type="radio" class="element radio" name="SHOW_CATEGORY_COUNTERS" value="0" id="SHOW_CATEGORY_COUNTERS_N" {'SHOW_CATEGORY_COUNTERS'|form_validation_set_radio:0:$smarty.const.SHOW_CATEGORY_COUNTERS} />
				<label class="choice" for="SHOW_CATEGORY_COUNTERS_N">{'no'|@Lang}</label>
				{'SHOW_CATEGORY_COUNTERS'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_show_cat_counters'|@Lang}</small></p>
			</div>
		</li>
                
                <li>
			<label class="description" for="WALLPAPERS_PER_COLUMN">{'wallpapers_per_column'|@Lang}</label>
			<div align="left">
				<input name="WALLPAPERS_PER_COLUMN" type="text" class="element text small" id="WALLPAPERS_PER_COLUMN" value="{'WALLPAPERS_PER_COLUMN'|@form_validation_get_value:$smarty.const.WALLPAPERS_PER_COLUMN}" />
				{'WALLPAPERS_PER_COLUMN'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_wallpapers_per_column'|@Lang}</small></p>
			</div>
		</li>
                
		</ul>
		</div>
		
		<div id="div5" style="display:none">
		<ul>
			<li>
				<label class="description" for="TAGS_ORDER_BY">{'tags_order_by'|@Lang}</label>
				<div align="left">
	
				<select name="TAGS_ORDER_BY" class="element select medium" id="TAGS_ORDER_BY">
					<option value=""{'TAGS_ORDER_BY'|form_validation_set_select:'':$smarty.const.TAGS_ORDER_BY}>{'random'|@Lang}</option>
					<option value="word"{'TAGS_ORDER_BY'|form_validation_set_select:'word':$smarty.const.TAGS_ORDER_BY}>{'alphabetical'|@Lang}</option>
					<option value="size"{'TAGS_ORDER_BY'|form_validation_set_select:'size':$smarty.const.TAGS_ORDER_BY}>{'size'|@Lang}</option>
				</select>
				<select name="TAGS_ORDER_BY_METHOD" class="element select" id="TAGS_ORDER_BY_METHOD">
					<option value="DESC"{'TAGS_ORDER_BY_METHOD'|form_validation_set_select:'DESC'}>{'descending'|@Lang}</option>
					<option value="ASC"{'TAGS_ORDER_BY_METHOD'|form_validation_set_select:'ASC'}>{'ascending'|@Lang}</option>
				</select>
				{'TAGS_ORDER_BY_METHOD'|@form_validation_print_error}
				<p class="guidelines"><small>{'G_tags_display_order'|@Lang}</small></p>
				</div>
			</li>
			<li>
				<label class="description" for="MAX_TAGS">{'max_tags'|@Lang}</label>
				<div align="left">
					<input name="MAX_TAGS" type="text" class="element text small" id="MAX_TAGS" value="{'MAX_TAGS'|@form_validation_get_value:$smarty.const.MAX_TAGS}" />
					{'MAX_TAGS'|@form_validation_print_error}
					<p class="guidelines"><small>{'G_max_tags'|@Lang}</small></p>
				</div>
			</li>
	
			<li>
				<label class="description" for="TAGS_MIN_CHARACTERS">{'min_tag_characters'|@Lang}</label>
				<div align="left">
					<input name="TAGS_MIN_CHARACTERS" type="text" class="element text small" id="TAGS_MIN_CHARACTERS" value="{'TAGS_MIN_CHARACTERS'|@form_validation_get_value:$smarty.const.TAGS_MIN_CHARACTERS}" />
					{'TAGS_MIN_CHARACTERS'|@form_validation_print_error}
					<p class="guidelines"><small>{'G_min_tag_characters'|@Lang}</small></p>
				</div>
			</li>
	
		</ul>
		</div>

		<div id="div6" style="display:none">
			<ul>
				<li>
					<label class="description" for="AD_CODE">{'ad_code'|@Lang}</label>
					<div align="left">
						<textarea name="AD_CODE" class="element textarea small" id="AD_CODE">{'AD_CODE'|@form_validation_get_value:$smarty.const.AD_CODE|base64_decode}</textarea>
						{'AD_CODE'|@form_validation_print_error}
						<p class="guidelines"><small>{'G_ad_code'|@Lang}</small></p>
					</div>
				</li>
		
				<li>
					<label class="description" for="TOP_DOWNLOAD_AD_CODE">{'top_download_ad_code'|@Lang}</label>
					<div align="left">
						<textarea name="TOP_DOWNLOAD_AD_CODE" class="element textarea small" id="TOP_DOWNLOAD_AD_CODE">{'TOP_DOWNLOAD_AD_CODE'|@form_validation_get_value:$smarty.const.TOP_DOWNLOAD_AD_CODE|base64_decode}</textarea>
						{'TOP_DOWNLOAD_AD_CODE'|@form_validation_print_error}
						<p class="guidelines"><small>{'G_top_download_ad_code'|@Lang}</small></p>
					</div>
				</li>

				<li>
					<label class="description" for="WALLPAPER_AD_CODE">{'wallpaper_ad_code'|@Lang}</label>
					<div align="left">
						<textarea name="WALLPAPER_AD_CODE" class="element textarea small" id="WALLPAPER_AD_CODE">{'WALLPAPER_AD_CODE'|@form_validation_get_value:$smarty.const.WALLPAPER_AD_CODE|base64_decode}</textarea>
						{'WALLPAPER_AD_CODE'|@form_validation_print_error}
						<p class="guidelines"><small>{'G_wallpaper_ad_code'|@Lang}</small></p>
					</div>
				</li>
				<li>
					<label class="description" for="WALLPAPER_IPHONE_AD_CODE">{'wallpaper_iphone_code'|@Lang}</label>
					<div align="left">
						<textarea name="WALLPAPER_IPHONE_AD_CODE" class="element textarea small" id="WALLPAPER_IPHONE_AD_CODE">{'WALLPAPER_IPHONE_AD_CODE'|@form_validation_get_value:$smarty.const.WALLPAPER_IPHONE_AD_CODE|base64_decode}</textarea>
						{'WALLPAPER_IPHONE_AD_CODE'|@form_validation_print_error}
						<p class="guidelines"><small>{'G_wallpaper_iphone_code'|@Lang}</small></p>
					</div>
				</li>
				<li>
					<label class="description" for="WALLPAPER_DOWNLOAD_AD_CODE">{'wallpaper_download_code'|@Lang}</label>
					<div align="left">
						<textarea name="WALLPAPER_DOWNLOAD_AD_CODE" class="element textarea small" id="WALLPAPER_DOWNLOAD_AD_CODE">{'WALLPAPER_DOWNLOAD_AD_CODE'|@form_validation_get_value:$smarty.const.WALLPAPER_DOWNLOAD_AD_CODE|base64_decode}</textarea>
						{'WALLPAPER_DOWNLOAD_AD_CODE'|@form_validation_print_error}
						<p class="guidelines"><small>{'G_wallpaper_download_code'|@Lang}</small></p>
					</div>
				</li>
			</ul>
		</div>

		<div id="div7" style="display:none;font-size:11px">
			{$patterns_guide}
			<ul>
{foreach from=$backgrounds item=background}
				<li onclick="changebg('{$background}');" style="cursor:pointer;width:100%;margin-bottom:10px;padding:50px 0 50px 0;background:url({''|base_url}templates/{$smarty.const.DEFAULT_TEMPLATE}/images/patterns/{$background}?{''|print_unique_id})">&nbsp;</li>
{/foreach}
				<li class="clear">&nbsp;</li>
			</ul>
		</div>
	</fieldset>

	<div class="job_indicators">
		{'save'|Lang|__button}
	</div>
	</form>