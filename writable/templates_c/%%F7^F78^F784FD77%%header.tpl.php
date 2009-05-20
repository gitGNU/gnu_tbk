<?php /* Smarty version 2.6.24, created on 2009-05-20 11:44:00
         compiled from /var/www/tabarnak/writable/templates/tabarnak/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', '/var/www/tabarnak/writable/templates/tabarnak/header.tpl', 4, false),array('function', 'load_menu', '/var/www/tabarnak/writable/templates/tabarnak/header.tpl', 14, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Tabarnak .::. <?php echo ((is_array($_tmp=@$this->_tpl_vars['title'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Accueil') : smarty_modifier_default($_tmp, 'Accueil')); ?>
</title>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <link rel="stylesheet" type="text/css" href="<?php echo @BASE_URL; ?>
writable/templates/tabarnak/tabarnak.css"/>
   </head>
   <body>
   <div id="baniere">
   </div>
   
   <div id="page">
   <div id="menu">
   	<?php echo smarty_function_load_menu(array('assign' => 'liens'), $this);?>

   	<?php $_from = $this->_tpl_vars['liens']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
   		<?php if ($this->_tpl_vars['v'] == "#"): ?>
   			<h3><?php echo $this->_tpl_vars['k']; ?>
</h3>
   		<?php else: ?>
   			<a href="<?php echo $this->_tpl_vars['v']; ?>
"><?php echo $this->_tpl_vars['k']; ?>
</a>
   		<?php endif; ?>
   	<?php endforeach; endif; unset($_from); ?>
   </div>
   
   <div id="content">