<?php /* Smarty version 2.6.25, created on 2009-12-26 01:07:49
         compiled from default/html/most_downloaded.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'Lang', 'default/html/most_downloaded.tpl', 1, false),array('modifier', 'make_thumb_if_not_exists', 'default/html/most_downloaded.tpl', 1, false),array('modifier', 'get_wallpaper_url_location', 'default/html/most_downloaded.tpl', 1, false),array('modifier', 'get_wallpaper_url', 'default/html/most_downloaded.tpl', 1, false),array('modifier', '__character_limiter', 'default/html/most_downloaded.tpl', 1, false),)), $this); ?>
<div id="leftcol"><h3 class="most_downloaded"><?php echo ((is_array($_tmp='most_downloaded')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>
</h3><?php if ($this->_tpl_vars['wallpapers'] != FALSE): ?><ul><?php $_from = $this->_tpl_vars['wallpapers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?><?php echo ((is_array($_tmp=$this->_tpl_vars['row'])) ? $this->_run_mod_handler('make_thumb_if_not_exists', true, $_tmp) : make_thumb_if_not_exists($_tmp)); ?>
<li><a class="img_tooltip" rel="<?php echo ((is_array($_tmp=$this->_tpl_vars['row'])) ? $this->_run_mod_handler('get_wallpaper_url_location', true, $_tmp) : get_wallpaper_url_location($_tmp)); ?>
thumb_<?php echo $this->_tpl_vars['row']->hash; ?>
.jpg" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['row'])) ? $this->_run_mod_handler('get_wallpaper_url', true, $_tmp) : get_wallpaper_url($_tmp)); ?>
" title="<?php echo $this->_tpl_vars['row']->file_title; ?>
"><span class="ui-icon ui-icon-arrowthick-1-s" style="-moz-user-select: none;">&nbsp;</span>[ <?php echo $this->_tpl_vars['row']->downloads; ?>
 ] <?php echo ((is_array($_tmp=$this->_tpl_vars['row']->file_title)) ? $this->_run_mod_handler('__character_limiter', true, $_tmp, 20) : __character_limiter($_tmp, 20)); ?>
</a></li><?php endforeach; endif; unset($_from); ?></ul><?php endif; ?></div>