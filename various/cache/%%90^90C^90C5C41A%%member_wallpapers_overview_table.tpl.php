<?php /* Smarty version 2.6.25, created on 2009-12-26 01:08:33
         compiled from default/html/member_wallpapers_overview_table.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'site_url', 'default/html/member_wallpapers_overview_table.tpl', 5, false),array('modifier', 'Lang', 'default/html/member_wallpapers_overview_table.tpl', 5, false),array('modifier', 'strtoupper', 'default/html/member_wallpapers_overview_table.tpl', 5, false),array('modifier', 'get_wallpapers_nr', 'default/html/member_wallpapers_overview_table.tpl', 12, false),)), $this); ?>
<table width="100%" class="mytables">

	<thead>
		<tr>
			<th class="center"><a href="<?php echo site_url('members/index/1'); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp='active')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)))) ? $this->_run_mod_handler('strtoupper', true, $_tmp) : strtoupper($_tmp)); ?>
</a></th>
			<th class="center"><a href="<?php echo site_url('members/index/0'); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp='inactive')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)))) ? $this->_run_mod_handler('strtoupper', true, $_tmp) : strtoupper($_tmp)); ?>
</a></th>
			<th class="center"><a href="<?php echo site_url('members/index/2'); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp='suspended')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)))) ? $this->_run_mod_handler('strtoupper', true, $_tmp) : strtoupper($_tmp)); ?>
</a></th>
		</tr>
	</thead>

	<tr>
		<td width="33%"><?php echo get_wallpapers_nr(1, $this->_tpl_vars['member_id']); ?>
</td>
		<td width="33%"><?php echo get_wallpapers_nr(0, $this->_tpl_vars['member_id']); ?>
</td>
		<td width="33%"><?php echo get_wallpapers_nr(2, $this->_tpl_vars['member_id']); ?>
</td>
	</tr>
	
</table>