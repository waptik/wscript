<?php /* Smarty version 2.6.25, created on 2009-12-26 01:08:29
         compiled from default/forms/login_form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'Lang', 'default/forms/login_form.tpl', 8, false),array('modifier', 'form_validation_get_value', 'default/forms/login_form.tpl', 10, false),array('modifier', 'form_validation_print_error', 'default/forms/login_form.tpl', 12, false),array('modifier', '__button', 'default/forms/login_form.tpl', 32, false),)), $this); ?>
<?php echo $this->_tpl_vars['form_open']; ?>
		
<fieldset class="active">

		<input type="text" class="captcha" name="url" value="" />
		<input type="hidden" name="redirect" value="<?php echo $this->_tpl_vars['value_redirect']; ?>
" />
		<ul>
			<li>
				<label class="description" for="username"><?php echo Lang('credentials'); ?>
: <span class="required">*</span></label>
				<span>
					<input name="username" type="text" class="element text" size="20" id="username" value="<?php echo form_validation_get_value('username'); ?>
"  />
					<label><?php echo Lang('username'); ?>
</label>
<?php echo form_validation_print_error('username'); ?>

				</span>
				<span>
					<input name="password" type="password" class="element text" size="20" id="password" value="<?php echo form_validation_get_value('password'); ?>
"  />
					<label><?php echo Lang('password'); ?>
</label>
<?php echo form_validation_print_error('password'); ?>

				</span>
			</li>
<?php if (@ALLOW_REMEMBER_ME): ?>
			<li>
				<div>
					<input id="remember" name="remember" class="element checkbox" type="checkbox" />
					<label class="choice" for="remember"><?php echo Lang('remember_me'); ?>
</label>
				</div>
			</li>
<?php endif; ?>
		</ul>			
</fieldset>

	<div class="job_indicators">
		<?php echo ((is_array($_tmp=((is_array($_tmp='login')) ? $this->_run_mod_handler('Lang', true, $_tmp) : Lang($_tmp)))) ? $this->_run_mod_handler('__button', true, $_tmp) : __button($_tmp)); ?>
<?php echo $this->_tpl_vars['fpass_btn']; ?>

	</div>
</form>