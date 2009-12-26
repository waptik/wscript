<?php /* Smarty version 2.6.25, created on 2009-12-26 01:08:33
         compiled from default/html/member_wallpapers_table.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'selfUrl', 'default/html/member_wallpapers_table.tpl', 2, false),array('modifier', 'Lang', 'default/html/member_wallpapers_table.tpl', 7, false),array('modifier', 'form_validation_get_value', 'default/html/member_wallpapers_table.tpl', 14, false),array('modifier', 'site_url', 'default/html/member_wallpapers_table.tpl', 17, false),array('modifier', 'get_wallpaper_url', 'default/html/member_wallpapers_table.tpl', 48, false),array('modifier', 'get_wallpaper_url_location', 'default/html/member_wallpapers_table.tpl', 48, false),array('modifier', 'stripslashes', 'default/html/member_wallpapers_table.tpl', 48, false),array('modifier', '__character_limiter', 'default/html/member_wallpapers_table.tpl', 48, false),)), $this); ?>
<form action="" id="form_wall" name="form_wall" method="post">
<input type="hidden" name="referer" id="referer" value="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('selfUrl', true, $_tmp) : selfUrl($_tmp)); ?>
" />
<table width="100%" class="pickme sortable-onload-1r rowstyle-alt no-arrow mytables">
<thead>
	<tr>
		<th width="15"><input type="checkbox" name="check_all" id="check_all" value="" onclick="javascript:CheckUncheckAll('form_wall');" /></th>
		<th class="sortable-text"><?php echo Lang('title'); ?>
</th>
		<th class="sortable-text" width="50"><?php echo Lang('hits'); ?>
</th>
		<th><?php echo Lang('options'); ?>
</th>
	</tr>
        <tr>
		<td width="15">&nbsp;</th>
		<td class="sortable-text left">
                        <input type="text" style="width:99%" name="title_filter" id="title_filter" value="<?php echo form_validation_get_value('title_filter'); ?>
" />
                        <script type="text/javascript"><?php echo '
                                $(document).ready(function() {
                                                $("#title_filter").suggest("'; ?>
<?php echo ((is_array($_tmp='wallpapers/title_suggest')) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
<?php echo '");
                                } );'; ?>

                        </script>
                </td>
		<td class="sortable-text left">&nbsp;</td>
		<td><input type="submit" value="filter" /></td>
	</tr>
</thead>
<tfoot>
	<tr>
		<th colspan="4" class="right">
			<?php echo ((is_array($_tmp='with_selected')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)); ?>
:
			<select style="width:150px" name="mass_action" id="mass_action" onchange="ajax_mass_options('<?php echo ((is_array($_tmp='wallpapers/mass_options')) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
','form_wall'); return false;">
				<option value=""><?php echo Lang('please_select_'); ?>
</option>
			<?php if ($this->_tpl_vars['CI']->permissions->checkPermissions ( array ( 7 ) ) && ( $this->_tpl_vars['CI']->uri->segment(3,1) == 1 || $this->_tpl_vars['CI']->uri->segment(3,1) == 0 )): ?>
				<option value="mass_suspend"><?php echo Lang('suspend'); ?>
</option>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['CI']->permissions->checkPermissions ( array ( 6 ) ) && ( $this->_tpl_vars['CI']->uri->segment(3,1) == 2 || $this->_tpl_vars['CI']->uri->segment(3,1) == 0 )): ?>
				<option value="mass_activate"><?php echo Lang('activate'); ?>
</option>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['CI']->permissions->checkPermissions ( array ( 8 ) ) || ( $this->_tpl_vars['row']->user_id == $this->_tpl_vars['CI']->session->userdata('AUTH_SESSION_ID') )): ?>
				<option value="mass_delete"><?php echo Lang('delete'); ?>
</option>
			<?php endif; ?>
			</select>
		</th>
	</tr>
</tfoot>

<?php $_from = $this->_tpl_vars['query']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
	<tr onclick="lockRowUsingCheckbox();lockRow();">
		<td><input type="checkbox" name="tablechoice[]" value="<?php echo $this->_tpl_vars['row']->ID; ?>
" /></td>
		<td class="left"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['row'])) ? $this->_run_mod_handler('get_wallpaper_url', true, $_tmp) : get_wallpaper_url($_tmp)); ?>
" target="_blank" class="img_tooltip" rel="<?php echo ((is_array($_tmp=$this->_tpl_vars['row'])) ? $this->_run_mod_handler('get_wallpaper_url_location', true, $_tmp) : get_wallpaper_url_location($_tmp)); ?>
thumb_<?php echo $this->_tpl_vars['row']->hash; ?>
.jpg"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['row']->file_title)) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('__character_limiter', true, $_tmp, 30) : __character_limiter($_tmp, 30)); ?>
</a></td>
		<td class="center"><?php echo $this->_tpl_vars['row']->hits; ?>
</td>
		<td width="60">
			<select name="option" onChange="MM_jumpMenu('parent',this,0)">
				<option>----------</option>	
	<?php if (( $this->_tpl_vars['row']->active == 0 || $this->_tpl_vars['row']->active == 2 ) && $this->_tpl_vars['CI']->permissions->checkPermissions ( array ( 6 ) )): ?>
				<option value="<?php echo site_url("wallpapers/options/activate/".($this->_tpl_vars['row']->ID)."/".($this->_tpl_vars['status'])); ?>
"><?php echo Lang('activate'); ?>
</option>
	<?php endif; ?>

	<?php if (( $this->_tpl_vars['row']->active == 0 || $this->_tpl_vars['row']->active == 1 ) && $this->_tpl_vars['CI']->permissions->checkPermissions ( array ( 7 ) )): ?>
				<option value="<?php echo site_url("wallpapers/options/suspend/".($this->_tpl_vars['row']->ID)."/".($this->_tpl_vars['status'])); ?>
"><?php echo Lang('suspend'); ?>
</option>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['CI']->permissions->checkPermissions ( array ( 5 ) ) || ( $this->_tpl_vars['row']->user_id == $this->_tpl_vars['CI']->session->userdata('AUTH_SESSION_ID') )): ?>
				<option value="<?php echo site_url("wallpapers/options/delete/".($this->_tpl_vars['row']->ID)."/".($this->_tpl_vars['status'])); ?>
"><?php echo Lang('delete'); ?>
</option>	
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['CI']->permissions->checkPermissions ( array ( 4 ) ) || ( $this->_tpl_vars['row']->user_id == $this->_tpl_vars['CI']->session->userdata('AUTH_SESSION_ID') )): ?>
				<option value="<?php echo site_url("wallpapers/options/edit/".($this->_tpl_vars['row']->ID)."/".($this->_tpl_vars['status'])); ?>
"><?php echo Lang('edit'); ?>
</option>
	<?php endif; ?>
			</select>
		</td>
	</tr>
<?php endforeach; else: ?>
	<tr>
		<td colspan="3"><?php echo Lang('no_wallpapers'); ?>
</td>
	</tr>
<?php endif; unset($_from); ?>
</table>
</form>