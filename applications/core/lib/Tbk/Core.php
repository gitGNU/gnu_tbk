<?php
/**
 * Name: Core.php
 * Main Core class
 * Classe définissant le noyau des applications (modules)
 * Dernière modification : $Date$
 * @author     Marc-Henri PAMISEUX <marcori@users.sourceforge.net>
 * @copyright  Copyright (c) 2008 Marc-Henri PAMISEUX
 * @license    http://opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @link       http://www.gnu.org/licenses/licenses.html
 * @category   tabarnak
 * @package    core
 * @version    $Id$
 * @filesource
 *  +----------------------------------------------------------------------+
 *  | This file is part of TABARNAK - PHP Version 5                        |
 *  +----------------------------------------------------------------------+
 *  | Copyright (C) 2008-2009 Libricks                                     |
 *  +----------------------------------------------------------------------+
 *  | Ce programme est un logiciel libre distribue sous licence GNU/GPL.   |
 *  | Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne. |
 *  |                                                                      |
 *  | This program is free software; you can redistribute it and/or        |
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
 *  | Author: Marc-Henri PAMISEUX <marc-henri.pamiseux@libricks.org>       |
 *  +----------------------------------------------------------------------+
 */

/**
 * $Log$
 */


final class Tbk_Core
{
   /**
  * @var (array)
  * @desc Tableau des configurations par module
  */
  public $configs=array();

 /**
  * Constructeur
  * 
  * <p>création de l'instance de la classe Core</p>
  * 
  * @name TbkCore::__construct()
  * @param $sModName: Nom du module; par défaut, il vaut engine
  * @return void 
  */
  function __construct($sModName='core')
  {
    $_url=parse_url($_SERVER['SCRIPT_URI']);

    $this->configs[$mod_name]['prefix']=ucfirst(substr(strtolower(basename(__FILE__)),0,strpos(strtolower(basename(__FILE__)),strtolower($sModName),0)));
    // On définit l'URL de base:
    $this->configs[$mod_name]['path']['url']=$_SERVER['SCRIPT_URI'];
    // On définit le contexte de base:
    $this->configs[$mod_name]['path']['context']=dirname($_url['path']);
    // On définit le répertoire de base à ../../../TbkEngine.php
    $this->configs[$mod_name]['path']['base']=dirname(dirname(dirname(__FILE__)));

    echo('<pre>Constructeur d&eacute;clench&eacute;<br /></pre>');
    // On vient lister les répertoires présents dans modules
    
  }

  public static function getVersion()
  {
    return '1.0.0';
  }
  public static function run()
  {
    phpinfo();
    // TODO: Continuer
    /*
    // Verifie si un module a été invoqué
    if (array_key_exists('mod',$_GET))
      $module=filter_input(INPUT_GET,'mod',FILTER_SANITIZE_STRING);
    elseif (array_key_exists('mod',$_POST))
      $module=filter_input(INPUT_POST,'mod',FILTER_SANITIZE_STRING);
    else
      $module='pub';

    print($oCore->getVersion());

    if ((! isset($_REQUEST['mod']))||(is_null($_REQUEST['mod']))||(empty($_REQUEST['mod'])))
      $module='Core';
    else
      $module=addslashes($_REQUEST['mod']);
    */
  }
 /**
  * AutoLoad
  * 
  * <p>Inclusion automatique des classes manquantes</p>
  * 
  * @name TbkCore::__autoload()
  * @param sClassName
  * @return void 
  */
  function __autoload($sClassName)
  {
    if (strpos($sClassName, '/')!==false)
      return;
    $sClassFile = ucwords($sClassName, DIRECTORY_SEPARATOR).'.php';
    mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
    
    include_once($sClassFile);
  }
 /**
  * Destructeur
  * 
  * <p>Destruction de l'instance de la classe Engine</p>
  * 
  * @name Nom de la classe::__destruct()
  * @param nom du premier paramètre
  * @return void 
  */
  function __destruct()
  {
    echo('<pre>Destructeur d&eacute;clench&eacute;<br /></pre>');
  }
}
?>
