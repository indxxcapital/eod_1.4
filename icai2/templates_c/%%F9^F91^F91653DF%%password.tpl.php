<?php /* Smarty version 2.6.14, created on 2015-08-06 09:23:11
         compiled from C:/xampp/htdocs/ical1.4.1/icai2/templates//formfields/password.tpl */ ?>
<div class="control-group"><label for="password2" class="control-label"><?php echo $this->_tpl_vars['formParams']['feild_label'];  if ($this->_tpl_vars['formParams']['is_required'] == '1'): ?> <sup style="color:#F00;">*</sup><?php endif; ?>:</label><div class="controls">
		 <input type="password" name="<?php echo $this->_tpl_vars['formParams']['feild_code']; ?>
" id="<?php echo $this->_tpl_vars['formParams']['feild_code']; ?>
" class="input-xlarge" <?php echo $this->_tpl_vars['formParams']['feildValues']; ?>
/></div>
<!--<label></label>--><span id="error_<?php echo $this->_tpl_vars['formParams']['feild_code']; ?>
" <?php echo $this->_tpl_vars['formParams']['errorClass']; ?>
><?php echo $this->_tpl_vars['formParams']['errorMessage']; ?>
</span></div>