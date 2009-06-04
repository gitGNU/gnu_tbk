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
class GroupRightForm extends Zend_Form
{
  public function __construct($option=null)
  {
    parent::__construct($option);
    $config = new Zend_Config_Ini(CONFIG_PATH.'tabarnak_acl.ini');
    $aListMod = getModulesNames(MOD_PATH);
    $listeRole = getRolesArray();
    $this->setAction('/admin/groupe/droit');
    $this->setMethod('post');

    foreach($aListMod as $module=>$modChemin){
      $liste = new Zend_Config_Ini($modChemin.DIRECTORY_SEPARATOR.'descriptor.ini','general');
      
      //Création du sous formulaire
      $subForm = new Zend_Form_SubForm();
      $subForm->setName($module);
      $subForm->setLegend($module);
      
      //Listage des différents controllers et de leurs actions
      foreach($liste->toArray() as $controller=>$actions){
        $subFormCont = new Zend_Form_SubForm();
        $subFormCont->setName($controller);
        $subFormCont->setLegend($controller);
        
        $aActions = getArrayFromIniString($actions);
        foreach($aActions as $action){
          $checked = array();
          $group = new Zend_Form_Element_MultiCheckbox($action);
          foreach($listeRole as $role){
            $group->addMultiOption("allow_$role","Autoriser $action pour $role");            
            
            if(isAllowed($role,$module,$controller,$action))
              array_push($checked,"allow_$role");
          }
          
          
          $group->setValue($checked);

          $subFormCont->addElement($group,$action);
        }
        
        $subForm->addSubForm($subFormCont,$controller);
      }
      
      //Ajout au formulaire de base
      $this->addSubForm($subForm,$module);
    }
    
    $submit = new Zend_Form_Element_Submit('enregistrer');
    $submit->setLabel('Enregistrer');
    $submit->setDecorators(array(
    'ViewHelper',
    array(array('row' => 'HtmlTag'), array('tag' => '<p>')))
    );
    $this->addElement($submit,'enregistrer');
  }
}
?>