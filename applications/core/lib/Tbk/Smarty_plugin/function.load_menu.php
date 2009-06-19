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
function smarty_function_load_menu($params,&$smarty)
{
  $liens = array();
  if(Zend_Auth::getInstance()->hasIdentity()){
    $tab = Zend_Auth::getInstance()->getStorage()->read();
    $role = $tab['role'];
  }
  else
    $role = 'invite';
    
  //Chargement du fichier ini
  $config = new Zend_Config_Ini(CONFIG_PATH.'menu.ini');
  $array = $config->toArray();
  
  $configDroit = new Zend_Config_Ini(CONFIG_PATH.'menu.ini','deny');
  $deny = $configDroit->toArray();
  
  
  foreach($array as $cle=>$valeur){
    if($cle != "deny"){
      if(!isset($deny[$cle]) || (isset($deny[$cle]) && !preg_match("#$role#",$deny[$cle]))){
        $liens[$cle] = array();
        foreach($array[$cle] as $k=>$v){
          $allowed = true;
          $explode = explode('/',$v);
          $module = $explode[1];
          if(is_module_active($module)){
            if(isset($deny[$cle."_".$k])){
              if(preg_match("#$role#",$deny[$cle."_".$k]))
                $allowed = false;
            }
            if($allowed)
              $liens[$cle][$k] = $v;
          }
        }
      }
    }
  }
  
  $smarty->assign($params['assign'],$liens);
}
?>