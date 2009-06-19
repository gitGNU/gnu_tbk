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
class Tbk_AuthAdapter implements Zend_Auth_Adapter_Interface
{

  private $identifiant,$motdepasse,$role;

  /**
   * Définition de l'identifiant et du mot de passe
   * pour authentification
   *
   * @return void
   */
  public function __construct($identifiant, $motdepasse)
  {
    $this->identifiant = $identifiant;
    $this->motdepasse = $motdepasse;
  }

  /**
   * Réalise une tentative d'authentification
   *
   * @throws Zend_Auth_Adapter_Exception Si l'authentification
   *                                     ne peut pas être réalisée
   * @return Zend_Auth_Result
   */
  public function authenticate()
  {
    $conf = new Zend_Config_Ini(CONFIG_PATH.'persistance.ini');
    
    $config = $conf->general;
    $base_auth = $conf->toArray();
    $auth = $base_auth["auth"]["db"];
    
    $db = Zend_Db::factory($config->db);
    Zend_Db_Table::setDefaultAdapter($db);
    
    $select = $db->select();
    $select->from($auth['table'],"*");
    $select->where($auth['champ']['login']."=?",$this->identifiant);
    
    $result = $db->fetchAll($select);
    
    if(count($result) > 0){
      if (md5($this->motdepasse) == $result[0][$auth['champ']['mdp']]) {
        $res = new Zend_Auth_Result(
          Zend_Auth_Result::SUCCESS,
          $this->identifiant,
          array());
        return $res;
      }
      else {
        $res = new Zend_Auth_Result(
          Zend_Auth_Result::FAILURE,
          null,
          array("Mauvais mot de passe."));
        return $res;
      }
    }
    else{
      $res = new Zend_Auth_Result(
          Zend_Auth_Result::FAILURE,
          null,
          array("Login inconnu"));
      return $res;
    }
  }
}
?>