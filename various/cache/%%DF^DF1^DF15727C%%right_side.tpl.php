<?php /* Smarty version 2.6.25, created on 2009-12-26 01:07:47
         compiled from default/html/right_side.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'site_url', 'default/html/right_side.tpl', 2, false),array('modifier', 'Lang', 'default/html/right_side.tpl', 3, false),)), $this); ?>
<div id="search">
        <form method="post" action="<?php echo ((is_array($_tmp="search/results")) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
" id="search_form">
                <input type="text" name="search_for" value="<?php echo ((is_array($_tmp='search')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>
..." onclick="javascript:this.focus();this.select();" id="search_input" />
        </form>
</div>
<form action="" method="post" onsubmit="return false;" id="sections_frm" name="sections_frm">
<?php echo $this->_tpl_vars['sidebarContents']; ?>

</form>