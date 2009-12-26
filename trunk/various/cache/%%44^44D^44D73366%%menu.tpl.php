<?php /* Smarty version 2.6.25, created on 2009-12-26 01:07:47
         compiled from default/html/menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'default/html/menu.tpl', 7, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['children'] )): ?>
<ul>
<?php else: ?>
<ul id="nav" class="sf-menu">
<?php endif; ?>
<?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu_item']):
?>
<?php $this->assign('has_childs', count($this->_tpl_vars['menu_item']['children'])); ?>
	<?php if ($this->_tpl_vars['menu_item']['show_condition']): ?>
	<li class="<?php if ($this->_tpl_vars['has_childs'] > 0): ?> current<?php endif; ?> <?php echo $this->_tpl_vars['menu_item']['class']; ?>
">
		<a href="<?php echo $this->_tpl_vars['menu_item']['link']; ?>
"><?php echo $this->_tpl_vars['menu_item']['text']; ?>
</a>
<?php if (! empty ( $this->_tpl_vars['menu_item']['children'] )): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'default/html/menu.tpl', 'smarty_include_vars' => array('menu' => $this->_tpl_vars['menu_item']['children'],'children' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
	</li>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</ul>
<div class="clear"></div>