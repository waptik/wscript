<?php /* Smarty version 2.6.25, created on 2009-12-26 01:07:47
         compiled from default/pages/template.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'Lang', 'default/pages/template.tpl', 7, false),array('modifier', 'base_url', 'default/pages/template.tpl', 17, false),array('modifier', 'get_setting', 'default/pages/template.tpl', 17, false),array('modifier', 'print_unique_id', 'default/pages/template.tpl', 28, false),array('modifier', 'site_url', 'default/pages/template.tpl', 35, false),array('modifier', 'write_header', 'default/pages/template.tpl', 45, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $this->_tpl_vars['page_title']; ?>
</title>
	<meta name="description" content="<?php echo $this->_tpl_vars['meta_description']; ?>
" />
	<meta name="keywords" content="<?php echo $this->_tpl_vars['meta_keywords']; ?>
" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ((is_array($_tmp='charset')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>
" />
	<meta name="content-language" content="<?php echo ((is_array($_tmp='iso')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>
" />
<?php echo $this->_tpl_vars['styles']; ?>

	<script type="text/javascript">
		var base_url = "<?php echo $this->_tpl_vars['base_url']; ?>
";
		var site_url = "<?php echo $this->_tpl_vars['site_url']; ?>
";
		var active_template = "<?php echo @DEFAULT_TEMPLATE; ?>
";
	</script>
<?php echo $this->_tpl_vars['javascript']; ?>

</head>
<body style="background:#F2FCFF url('<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('base_url', true, $_tmp) : base_url($_tmp)); ?>
templates/default/images/patterns/<?php echo ((is_array($_tmp='bg_pattern')) ? $this->_run_mod_handler('get_setting', true, $_tmp) : get_setting($_tmp)); ?>
');">
<?php if (@SITE_HAS_ADULT_MATERIALS && ! $this->_tpl_vars['adult_confirmed']): ?>
	<div id="adult_confirmation_wrapper" style="display:none">
		<p>
			<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
			<?php echo ((is_array($_tmp='adult_materials')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>

		</p>
	</div>
<?php endif; ?>
<!-- MENU -->
	<div id="header" style="height:<?php echo $this->_tpl_vars['header_height']; ?>
px">
		<div id="logo" style="background-image: url(<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('base_url', true, $_tmp) : base_url($_tmp)); ?>
/various/logo.gif?<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
)">
			<a href="<?php echo $this->_tpl_vars['home']; ?>
" title="<?php echo @SITE_SLOGAN; ?>
">
				<img src="<?php echo $this->_tpl_vars['images_path']; ?>
pixel.gif" alt="<?php echo $this->_tpl_vars['slogan']; ?>
" width="<?php echo $this->_tpl_vars['logo_size_x']; ?>
" height="<?php echo $this->_tpl_vars['logo_size_y']; ?>
" />
			</a>
		</div>
<?php if (@GUESTS_CAN_UPLOAD || is_logged_in ( )): ?>
		<div id="upload">
			<a href="javascript:dialog(700,600,'<?php echo ((is_array($_tmp='upload_wallpaper')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>
',true,true,'<?php echo ((is_array($_tmp='wallpapers/upload')) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
');" title="<?php echo ((is_array($_tmp='upload_wallpaper')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>
">
				<img src="<?php echo $this->_tpl_vars['images_path']; ?>
pixel.gif" alt="<?php echo ((is_array($_tmp='upload_wallpaper')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>
" width="96" height="24" />
			</a>
		</div>
<?php endif; ?>
	</div>
	<div id="top_menu"><?php echo $this->_tpl_vars['top_menu']; ?>
</div>
	<div class="clear"></div>
	<div id="wrapper"><!-- WRAPPER -->
		<div id="left"><!-- LEFT START -->
			<?php echo ((is_array($_tmp=$this->_tpl_vars['header_msg'])) ? $this->_run_mod_handler('write_header', true, $_tmp) : write_header($_tmp)); ?>

			<div id="content"><!-- CONTENT START -->
			<?php echo $this->_tpl_vars['content']; ?>

			</div><!-- CONTENT END -->
		</div><!-- LEFT END -->
		<div id="right"><!-- RIGHT START -->
			<?php echo $this->_tpl_vars['right']; ?>
	
		</div><!-- RIGHT END -->
		<div class="clear"></div>
	</div>
	<?php echo $this->_tpl_vars['footer']; ?>

</body>
</html>