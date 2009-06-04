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
class Tbk_Membre
{
  private $variables;
  
  public function Membre($nom,$mdp,$mail,$groupe = 'invite'){
    $this->variables['nom'] = $nom;
    $this->variables['mdp'] = $mdp;
    $this->variables['mail'] = $mail;
    $this->variables['groupe'] = $groupe;
  }
  
  public function get($nom){
    return $this->variables[$nom];
  }
  
  public function set($nom,$valeur){
    $this->variables[$nom] = $valeur;
  }
  
  public function toString(){
    $chaine = "";
    
    foreach($this->variables as $k=>$v){
      $chaine .= "[$k] => $v <br/>";
    }
    
    return $chaine;
  }
}
?>