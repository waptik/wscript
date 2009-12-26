<?php /* Smarty version 2.6.25, created on 2009-12-26 01:07:47
         compiled from default/html/show_partners.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'prep_url', 'default/html/show_partners.tpl', 4, false),array('modifier', 'stripslashes', 'default/html/show_partners.tpl', 4, false),)), $this); ?>
<?php if ($this->_tpl_vars['partners'] != FALSE): ?>
	<ul>
<?php $_from = $this->_tpl_vars['partners']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['partner']):
?>
		<li><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['partner']->link)) ? $this->_run_mod_handler('prep_url', true, $_tmp) : prep_url($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['partner']->title)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" target="_blank"><?php echo ((is_array($_tmp=$this->_tpl_vars['partner']->title)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<em><?php echo ((is_array($_tmp=$this->_tpl_vars['partner']->description)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</em></a></li>
<?php endforeach; endif; unset($_from); ?>
	</ul>
<div class="clear"></div>
<?php endif; ?>