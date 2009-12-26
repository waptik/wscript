<?php /* Smarty version 2.6.25, created on 2009-12-26 01:07:47
         compiled from default/html/section_categories.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'Lang', 'default/html/section_categories.tpl', 3, false),)), $this); ?>
<h2 class="headers" id="cc">
	<span class="ui-icon ui-icon-folder-collapsed c3">&nbsp;</span>
	<?php echo ((is_array($_tmp='categories')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>

	<span class="ui-icon ui-icon-triangle-1-w c4">&nbsp;</span>
</h2>
<div class="desc hidden" id="fcw"><?php echo $this->_tpl_vars['content']; ?>
</div>