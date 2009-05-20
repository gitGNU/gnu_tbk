<?php
/**
 * Name: bootstrap.php
 * Programme point d'entrée de toutes les requètes (programme principal)
 * Dernière modification : $Date$
 * @author     Marc-Henri PAMISEUX <marcori@users.sourceforge.net>
 * @author     Jean-Baptiste BLANC <blanc.j.baptiste@gmail.com>
 * @copyright  Copyright (c) 2008 Marc-Henri PAMISEUX
 * @license    http://opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @link       http://www.gnu.org/licenses/licenses.html
 * @category   tabarnak
 * @package    main
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

/**
 * $Log$
 */
// Definition de la constante du chemin de base
define('ROOTPATH',dirname(__FILE__));
define('BASE_URL','http://'.$_SERVER['SERVER_NAME'].'/');
define('WRI_PATH',ROOTPATH.DIRECTORY_SEPARATOR.'writable');

// Lecture du fichier de configuration du BootStrap
if (file_exists(WRI_PATH.DIRECTORY_SEPARATOR.'configBoot.inc.php'))
  include_once(WRI_PATH.DIRECTORY_SEPARATOR.'configBoot.inc.php');
else
  die('<pre>FATAL: Cannot load '.WRI_PATH.DIRECTORY_SEPARATOR.'configBoot.inc.php.<br />You should contact your administrator.</pre>');

// Lecture du fichier de définition des fonctions du BootStrap
if (file_exists(ROOTPATH.DIRECTORY_SEPARATOR.'functions.inc.php'))
  include_once(ROOTPATH.DIRECTORY_SEPARATOR.'functions.inc.php');
else
  die('<pre>FATAL: Cannot load '.ROOTPATH.DIRECTORY_SEPARATOR.'functions.inc.php.<br />You should contact your administrator.</pre>');

// Definition de la constante du chemin de l'application
define('APP_PATH',getAppPath($sSecretKey));
define('CONFIG_PATH',WRI_PATH.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR);

// Si le dossier des applications n'existe pas, on invoque le programme d'installation
if (! is_dir(APP_PATH))
{
  loadInstall(getAppBaseName($sSecretKey,1));
  exit;
}

// Si le module core n'existe pas, on invoque le programme d'installation
if (! is_dir(APP_PATH.DIRECTORY_SEPARATOR.$sCore))
  loadInstall(getAppBaseName($sSecretKey,2));

// Modifier le chemin include_path pour les librairies du core
set_include_path(APP_PATH.DIRECTORY_SEPARATOR.$sCore.DIRECTORY_SEPARATOR.'lib'.PATH_SEPARATOR.get_include_path());

// Definition de la constante du chemin des modules de l'application
define('MOD_PATH',getModPath(APP_PATH,$sModDirName));

// Initialisation de la liste des modules
$aListMods=getModulesNames(MOD_PATH);

// Existe-il des modules ?
if ($aListMods)
{
  // Pour chaque module, on vient ajouter son sous-dossier lib, quand il existe, à l'include_path
  foreach($aListMods as $key=>$value)
  {
    if ($key != $sCore)
    {
      if (is_dir($value.DIRECTORY_SEPARATOR.'lib'))
        set_include_path(get_include_path().PATH_SEPARATOR.$value.DIRECTORY_SEPARATOR.'lib');
      if (is_dir($value.DIRECTORY_SEPARATOR.'models'))
        set_include_path(get_include_path().PATH_SEPARATOR.$value.DIRECTORY_SEPARATOR.'models');
    }
  }
}
else
  load_setup(MOD_PATH,$setup_dir);

// TODO: Finaliser ce code à partir d'ici
// Charge le loader général.
require_once('Zend/Loader/Autoloader.php');
require_once('Smarty/Smarty.class.php');

// Chargement automatique des classes
$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);
// Démarrage de la session
Zend_Session::start();

// ********************* CACHE ******************************
// ********************* CONFIG *****************************
// On stock les données relatives à l'authentification dans le namespace Membre de la session
Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('Membre'));

// ********************* MVC ********************************
// Definit le controlleur frontal (Objet singleton)
$frontController = Zend_Controller_Front::getInstance();

// ********************* DROITS *****************************
if(!Zend_Session::namespaceIsset("Droits")){
  $acl_ini = new Tbk_Acl_Ini(CONFIG_PATH.'tabarnak_acl.ini');
  $sessionNS = new Zend_Session_Namespace('Droits');
  $sessionNS->__set('acl',$acl_ini);
}
$acl = getAclFromSession();
$frontController->registerPlugin(new Tbk_Controller_Auth($acl)) ;


/**
 * Configuration du contrôleur frontal
 */
// Pour chaque module, on vient ajouter son sous-dossier controllers, quand il existe
$aModules=array();

foreach($aListMods as $key=>$value)
{
  if (is_dir($value.DIRECTORY_SEPARATOR.'controllers'))
    $aModules[$key]=$value.DIRECTORY_SEPARATOR.'controllers';
}
// Definition de la liste des controlleurs disponibles
$frontController->setControllerDirectory($aModules);
// Définition de l'emplacement du dossier des modules
$frontController->addModuleDirectory(MOD_PATH);
// Définition de l'URL de base de l'application
$frontController->setBaseUrl($base_url);
// Définition du module à utiliser par défaut
$frontController->setDefaultModule($sDefaultModule);
// Définition
$frontController->setDefaultControllerName('index');
//
$frontController->throwExceptions(TRUE);
// propagation de paramètres dans le système MVC
$frontController->setParam('debug',TRUE);

// ********************* LOG ********************************
// ********************* VIEW ********************************
if(!isset($_COOKIE['design']))
  setcookie('design','ubuntu_style',time()+3600*30);

$templates_name = $_COOKIE['design'];

$view = new Tbk_View_Smarty(WRI_PATH.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$templates_name.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR);
$view->_smarty->compile_dir = WRI_PATH.DIRECTORY_SEPARATOR.'templates_c';
$view->_smarty->config_dir = WRI_PATH.DIRECTORY_SEPARATOR.'configs';
$view->_smarty->cache_dir = WRI_PATH.DIRECTORY_SEPARATOR.'cache';
array_push($view->_smarty->plugins_dir,APP_PATH.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Tbk'.DIRECTORY_SEPARATOR.'Smarty_plugin');

$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
$viewRenderer->setView($view)
             ->setViewBasePathSpec($view->_smarty->template_dir)
             ->setViewScriptPathSpec(':module/views/:controller/:action.:suffix')
             ->setViewScriptPathNoControllerSpec(':module/views/:action.:suffix')
             ->setViewSuffix('tpl');
             
$view->setEncoding('UTF-8');
// ********************* ROUTE ********************************
// ********************* DISPATCH ***************************
// http://framework.zend.com/manual/fr/zend.controller.response.html
// Demande au contrôleur frontal de ne pas afficher, mais retourner
//Affecte une variable à toutes les vues pour pouvoir inclure le header et le footer.
$view->assign('chemin_patron',WRI_PATH.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$templates_name);

$frontController->returnResponse(TRUE);

$response=$frontController->dispatch();

if($response->isException())
{
  echo('Une exception est survenue');
  $response->renderExceptions();
}
// Affichage de la page
$response->sendResponse();
?>
