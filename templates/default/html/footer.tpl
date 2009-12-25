<div id="footer">
	<div id="footer_cols"><!-- --></div>
	<div class="clear"></div>
	<div class="footer">
		<div id="foot_nav">
			<div class="users_online">
				<a href="{'welcome/users_online'|site_url}" title="{$num_users_online} {'users'|@Lang} {'online'|@Lang}"><strong>{$num_users_online} {'users'|@Lang}</strong> {'online'|@Lang}</a>
			</div>
			<ul>
				<li><a href="{''|base_url}" title="{'nav_home'|@Lang}">{'nav_home'|@Lang|ucfirst}</a></li>
{if $logged_in}
				<li><a href="{'members'|site_url}" title="{'my_account'|@Lang}">{'my_account'|@Lang|ucfirst}</a></li>
				<li><a href="{'login/logout'|site_url}" title="{'nav_logout'|@Lang}">{'nav_logout'|@Lang|ucfirst}</a></li>
{else}
				<li><a href="{'login'|site_url}" title="{'nav_login'|@Lang}">{'nav_login'|@Lang|ucfirst}</a></li>
{/if}
				<li><a href="{'contact'|site_url}" title="{'contact'|@Lang}">{'contact_us'|@Lang|ucfirst}</a></li>
			</ul>
		</div>
	</div>
</div>

<div class="footbox">
	<div class="sidebox boxbody">
		<p>
			<!--
				We request you leave in place the "Powered by W-script" line, with "W-script" linked to www.wallpaperscript.net.
				This not only gives respect to the large amount of time given freely by the developers but also helps build interest,
				traffic and use of W-script. If you refuse to include this then support on our forums may be affected.
				
				If you honestly can't keep this "Powered by W-script" line please, at least, consider making a donation at:
				http://www.wallpaperscript.net/index.php/donate/index
	
				W-script - www.wallpaperscript.net
			//-->
			Powered by <a href="http://www.wallpaperscript.net" title="Wallpaper script" target="_blank">W-script</a> v{$smarty.const.WS_VERSION}{$smarty.const.TRACKING_CODE|base64_decode}
		</p>
	</div>
</div>