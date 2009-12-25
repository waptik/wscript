<applet
	code="wjhk.jupload2.JUploadApplet"
	codebase="{$smarty.const.APPLICATION_URL}scripts/jupload/"
	archive="wjhk.jupload.jar,NimRODLF-0.98b.jar"
	width="100%"
	height="480px"
	mayscript>
		<param name="postURL" id="JUploadPostURLParam" value="{'wallpapers/java_do_upload'|site_url}/" />
		<param name = "lookAndFeel" value = "com.nilo.plaf.nimrod.NimRODLookAndFeel" />
		<param name="highQualityPreview" value="true" />
		<param name="maxChunkSize" value="{$maxChunkSize}" />
		<param name="nbFilesPerRequest" value="1" />
		<param name="serverProtocol" value="HTTP/1.1" />
		<param name="stringUploadSuccess" value="SUCCESS" />
		<param name="stringUploadError" value="ERROR: (.*)" />
		<param name="showLogWindow" value="{if $smarty.const.RUN_ON_DEVELOPMENT===TRUE}true{else}false{/if}" />
		<param name="showStatusBar" value="1" />
		<param name="debugLevel" value="{if $smarty.const.RUN_ON_DEVELOPMENT}1{else}0{/if}" />
		<param name="afterUploadURL" value="{'wallpapers/upload_show_summary'|site_url}" />
	<h2 style="color:red">Java 1.5 or higher plugin required.</h2>
</applet>