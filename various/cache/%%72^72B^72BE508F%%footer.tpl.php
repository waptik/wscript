<?php /* Smarty version 2.6.25, created on 2009-12-26 01:07:47
         compiled from default/html/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'site_url', 'default/html/footer.tpl', 7, false),array('modifier', 'Lang', 'default/html/footer.tpl', 7, false),array('modifier', 'base_url', 'default/html/footer.tpl', 10, false),array('modifier', 'ucfirst', 'default/html/footer.tpl', 10, false),array('modifier', 'base64_decode', 'default/html/footer.tpl', 36, false),)), $this); ?>
<div id="footer">
	<div id="footer_cols"><!-- --></div>
	<div class="clear"></div>
	<div class="footer">
		<div id="foot_nav">
			<div class="users_online">
				<a href="<?php echo ((is_array($_tmp='welcome/users_online')) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
" title="<?php echo $this->_tpl_vars['num_users_online']; ?>
 <?php echo Lang('users'); ?>
 <?php echo Lang('online'); ?>
"><strong><?php echo $this->_tpl_vars['num_users_online']; ?>
 <?php echo Lang('users'); ?>
</strong> <?php echo Lang('online'); ?>
</a>
			</div>
			<ul>
				<li><a href="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('base_url', true, $_tmp) : base_url($_tmp)); ?>
" title="<?php echo Lang('nav_home'); ?>
"><?php echo ((is_array($_tmp=Lang('nav_home'))) ? $this->_run_mod_handler('ucfirst', true, $_tmp) : ucfirst($_tmp)); ?>
</a></li>
<?php if ($this->_tpl_vars['logged_in']): ?>
				<li><a href="<?php echo ((is_array($_tmp='members')) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
" title="<?php echo Lang('my_account'); ?>
"><?php echo ((is_array($_tmp=Lang('my_account'))) ? $this->_run_mod_handler('ucfirst', true, $_tmp) : ucfirst($_tmp)); ?>
</a></li>
				<li><a href="<?php echo ((is_array($_tmp='login/logout')) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
" title="<?php echo Lang('nav_logout'); ?>
"><?php echo ((is_array($_tmp=Lang('nav_logout'))) ? $this->_run_mod_handler('ucfirst', true, $_tmp) : ucfirst($_tmp)); ?>
</a></li>
<?php else: ?>
				<li><a href="<?php echo ((is_array($_tmp='login')) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
" title="<?php echo Lang('nav_login'); ?>
"><?php echo ((is_array($_tmp=Lang('nav_login'))) ? $this->_run_mod_handler('ucfirst', true, $_tmp) : ucfirst($_tmp)); ?>
</a></li>
<?php endif; ?>
				<li><a href="<?php echo ((is_array($_tmp='contact')) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
" title="<?php echo Lang('contact'); ?>
"><?php echo ((is_array($_tmp=Lang('contact_us'))) ? $this->_run_mod_handler('ucfirst', true, $_tmp) : ucfirst($_tmp)); ?>
</a></li>
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
			Powered by <a href="http://www.wallpaperscript.net" title="Wallpaper script" target="_blank">W-script</a> v<?php echo @WS_VERSION; ?>
<?php echo ((is_array($_tmp=@TRACKING_CODE)) ? $this->_run_mod_handler('base64_decode', true, $_tmp) : base64_decode($_tmp)); ?>

		</p>
	</div>
</div>