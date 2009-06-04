<?php
/**
 *  +----------------------------------------------------------------------+
 *  | This file is part of TABARNAK - PHP Version 5                        |
 *  +----------------------------------------------------------------------+
 *  | Copyright (C) 2008-2009 Libricks                                     |
 *  +----------------------------------------------------------------------+
 *  | Ce programme est un logiciel libre distribue sous licence GNU/GPL.   |
 *  | Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne. |
 *  |                                                                      |
 *  | This program is free software; you can redistribute it and/or        |
 *  | modify it under the terms of the GNU General Public License          |
 *  | as published by the Free Software Foundation; either version 2       |
 *  | of the License, or (at your option) any later version.               |
 *  |                                                                      |
 *  | This program is distributed in the hope that it will be useful,      |
 *  | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
 *  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
 *  | GNU General Public License for more details.                         |
 *  |                                                                      |
 *  | You should have received a copy of the GNU General Public License    |
 *  | along with this program; if not, write to                            |
 *  | the Free Software Foundation, Inc.,                                  |
 *  | 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA         |
 *  |                                                                      |
 *  +----------------------------------------------------------------------+
 *  | Authors: 	* Marc-Henri PAMISEUX <marc-henri.pamiseux@libricks.org>   |
 *  | 			* Jean-Baptiste BLANC <blanc.j.baptiste@gmail.com>		   |
 *  +----------------------------------------------------------------------+
 */
class Admin_ModuleController extends Zend_Controller_Action
{
  /**
   * Affichage de la page d'accueil
   */
  public function indexAction()
  {
    if(!Zend_Auth::getInstance()->hasIdentity()){
      $this->_forward('index','index','login');
      return ;
    }
    $link = array();
    $modList = getModulesNames(MOD_PATH);
    foreach($modList as $v){
      if(is_file($v.'/controllers/AdminController.php')){
        $decoupe = explode(DIRECTORY_SEPARATOR,$v);
        $modName = $decoupe[count($decoupe)-1];
        $link[$modName] = '/'.$modName.'/admin/index';
      }
    }
    
    $this->view->form = $this->getActiveForm();
    $this->view->liens = $link;
  }
  
  public function activationAction(){
    if($this->getRequest()->isPost()){
      $form = $this->getActiveForm();
      foreach($_POST as $module=>$perm){
        if(preg_match("#active_#",$module)){
          $mod = explode('_',$module,2);
          $file = MOD_PATH.DIRECTORY_SEPARATOR.$mod[1].DIRECTORY_SEPARATOR.'descriptor.ini';
          $config = new Zend_Config_Ini($file, null,
                              array('skipExtends'=> true,
                              'allowModifications'=> true));
          $config->activation->active = $perm;
          
          try{
            $writer = new Zend_Config_Writer_Ini(array('config'   => $config,
                                                   'filename' => $file));
            $writer->write();
          }catch(Exception $e){
            $this->view->erreur = $e->getTraceAsString();
          }          
        }
      }
    }
  }
  
  private function getActiveForm(){
    $form = new Zend_Form();
    $form->setAction('/admin/module/activation');
    $form->setMethod('post');
    $form->setDecorators(formDecorator());
    
    $liste_module = getModulesNames(MOD_PATH);
    foreach($liste_module as $module=>$module_path){
      $parent = new Zend_Form_Element_Checkbox("active_$module");
      $parent->setLabel("Activer $module");
      $parent->setDecorators(stdDecorator());
      if(is_module_active($module))
        $parent->setChecked(true);
        
      $form->addElement($parent,"active_$module");
    }
    

    $submit = new Zend_Form_Element_Submit('ajouter');
    $submit->setLabel('Enregistrer');
    $submit->setDecorators(buttonDecorator());
    
    $form->addElement($submit,'submit');
    return $form;
  }
  
}
?>