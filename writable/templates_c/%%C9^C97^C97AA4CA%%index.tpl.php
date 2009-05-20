<?php /* Smarty version 2.6.24, created on 2009-05-20 16:31:37
         compiled from admin/views/index/index.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['chemin_patron'])."/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h1>Administration</h1>

<div id="menu_admin">
	<p>
	<?php $_from = $this->_tpl_vars['liens']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
		<a href="<?php echo $this->_tpl_vars['v']; ?>
"><?php echo $this->_tpl_vars['k']; ?>
</a> | 
	<?php endforeach; endif; unset($_from); ?>
	</p>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['chemin_patron'])."/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>