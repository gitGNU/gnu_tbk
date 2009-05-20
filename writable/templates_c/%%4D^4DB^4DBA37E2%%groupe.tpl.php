<?php /* Smarty version 2.6.24, created on 2009-05-20 16:20:35
         compiled from admin/views/groupe/groupe.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['chemin_patron'])."/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h1>Administration des groupes</h1>
<?php if (isset ( $this->_tpl_vars['erreur'] )): ?>
	<?php echo $this->_tpl_vars['erreur']; ?>

<?php endif; ?>
<hr/>
<h3>Créer un nouveau groupe</h3>
<?php echo $this->_tpl_vars['addGroupeForm']; ?>

<hr/>
<h3>Supprimer un groupe</h3>
<?php echo $this->_tpl_vars['removeGroupeForm']; ?>

<hr/>
<h3>Droits des groupes</h3>
<a href="<?php echo $this->_tpl_vars['lienDroits']; ?>
">Gestion des droits des différents groupes.</a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['chemin_patron'])."/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>