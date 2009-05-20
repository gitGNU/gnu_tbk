<?php /* Smarty version 2.6.24, created on 2009-05-20 13:57:37
         compiled from admin/views/groupe/droit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'admin/views/groupe/droit.tpl', 31, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['chemin_patron'])."/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h1>Droits des groupes</h1>

<form method="<?php echo $this->_tpl_vars['form']->getMethod(); ?>
" action="<?php echo $this->_tpl_vars['form']->getAction(); ?>
" enctype="<?php echo $this->_tpl_vars['form']->getEnctype(); ?>
">
<table id="admin_table">
		<tr>
			<td></td>
<?php $_from = $this->_tpl_vars['compte_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name']):
?>
      		<td><?php echo $this->_tpl_vars['name']; ?>
</td>
<?php endforeach; endif; unset($_from); ?>
		</tr>

<?php $_from = $this->_tpl_vars['subs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['module'] => $this->_tpl_vars['v']):
?>
        <tr><td class="cell_module" colspan="<?php echo $this->_tpl_vars['nbCompte']; ?>
"><?php echo $this->_tpl_vars['module']; ?>
</td></tr>
        <?php $_from = $this->_tpl_vars['v']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['controller'] => $this->_tpl_vars['d']):
?>
        	<tr><td class="cell_controller" colspan="<?php echo $this->_tpl_vars['nbCompte']; ?>
"><?php echo $this->_tpl_vars['controller']; ?>
</td></tr>
          <?php $_from = $this->_tpl_vars['d']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['action'] => $this->_tpl_vars['c']):
?>
          	<tr>
          		<td><?php echo $this->_tpl_vars['action']; ?>
</td>
          		<?php $_from = $this->_tpl_vars['c']->getMultiOptions(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['droit'] => $this->_tpl_vars['h']):
?>
          			<?php $this->assign('trouve', false); ?>
          			<?php $this->assign('liste', $this->_tpl_vars['c']->getValue()); ?>
          			
          			<?php $_from = $this->_tpl_vars['liste']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['nam'] => $this->_tpl_vars['it']):
?>
          				<?php if ($this->_tpl_vars['it'] == $this->_tpl_vars['droit']): ?>
          					<?php $this->assign('trouve', true); ?>
          				<?php endif; ?>
          			<?php endforeach; endif; unset($_from); ?>
          			
          			<?php if ($this->_tpl_vars['trouve'] == true): ?>
          				<td><input id="<?php echo $this->_tpl_vars['module']; ?>
-<?php echo $this->_tpl_vars['controller']; ?>
-<?php echo $this->_tpl_vars['action']; ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['droit'])) ? $this->_run_mod_handler('replace', true, $_tmp, '_', '') : smarty_modifier_replace($_tmp, '_', '')); ?>
" type="checkbox" checked="checked" name="<?php echo $this->_tpl_vars['module']; ?>
[<?php echo $this->_tpl_vars['controller']; ?>
][<?php echo $this->_tpl_vars['action']; ?>
][]" value="<?php echo $this->_tpl_vars['droit']; ?>
"/>Autoriser</td>
          			<?php else: ?>
          				<td><input id="<?php echo $this->_tpl_vars['module']; ?>
-<?php echo $this->_tpl_vars['controller']; ?>
-<?php echo $this->_tpl_vars['action']; ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['droit'])) ? $this->_run_mod_handler('replace', true, $_tmp, '_', '') : smarty_modifier_replace($_tmp, '_', '')); ?>
" type="checkbox" name="<?php echo $this->_tpl_vars['module']; ?>
[<?php echo $this->_tpl_vars['controller']; ?>
][<?php echo $this->_tpl_vars['action']; ?>
][]" value="<?php echo $this->_tpl_vars['droit']; ?>
"/>Autoriser</td>
          			<?php endif; ?>
          		<?php endforeach; endif; unset($_from); ?>
          	</tr>
          <?php endforeach; endif; unset($_from); ?>
        <?php endforeach; endif; unset($_from); ?>
<?php endforeach; endif; unset($_from); ?>
      
      <tr><td colspan="<?php echo $this->_tpl_vars['nbCompte']; ?>
"><?php echo $this->_tpl_vars['form']->enregistrer; ?>
</td></table>
</form>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['chemin_patron'])."/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>