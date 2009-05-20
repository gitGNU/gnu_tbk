<?php /* Smarty version 2.6.24, created on 2009-05-20 10:08:00
         compiled from admin/views/module/index.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['chemin_patron'])."/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<h1>Administration des modules</h1>

<div id="menu_admin">
	<p>
	<?php $_from = $this->_tpl_vars['liens']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
		<a href="<?php echo $this->_tpl_vars['v']; ?>
"> <?php echo $this->_tpl_vars['k']; ?>
 </a><br/>
	<?php endforeach; endif; unset($_from); ?>
	</p>
</div>

<hr/>
<h1> Activation des modules </h1>
<?php echo $this->_tpl_vars['form']; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['chemin_patron'])."/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>