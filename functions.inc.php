<?php
/**
 * Name: functions.inc.php
 * Fichiers contenant les fonctions de bases utilisées par le bootStrap
 * Dernière modification : $Date$
 * @author     Marc-Henri PAMISEUX <marcori@users.sourceforge.net>
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
/**
 * Lecture du dossier des applications
 *
 * <p>Fonction de lecture du dossier des applications</p>
 *
 * @name bootstrap::getAppPath()
 * @param $pSecureKey: Clé de sécurité, par défaut, vaut NUL
 * @var $sAppDirName='applications';
 * @return $sAppPath: Chemin du dossier des applications;
 */
function getAppPath($pSecureKey='')
{
  $sAppDirName='applications';

  if (!isset($pSecureKey) || empty($pSecureKey))
    $sAppPath=dirname(__FILE__).DIRECTORY_SEPARATOR.$sAppDirName;
  else
    $sAppPath=dirname(__FILE__).DIRECTORY_SEPARATOR.md5($sAppDirName ^ $pSecureKey,FALSE);

  return $sAppPath;
}
/**
 * Lecture du nom du répertoire de base des applications
 *
 * <p>Fonction de lecture du nom du dossier des applications</p>
 *
 * @name bootstrap::getAppBaseName()
 * @param $pSecureKey: Clé de sécurité, par défaut, vaut NUL
 * @var $sAppPath: Chemin du dossier des applications;
 * @return Nom du dossier des applications;
 */
function getAppBaseName($pSecureKey='')
{
  $sAppPath=getAppPath($pSecureKey);
  return basename($sAppPath);
}
/**
 * Lecture du dossier des modules
 *
 * <p>Fonction de lecture du dossier des modules</p>
 *
 * @name bootstrap::getModPath()
 * @param $pAppPath: Chemin du dossier des applications, par défaut, vaut NULL
 * @param $pModDirName: nom du dossier des modules, par défaut, vaut 'modules'
 * @return $sModPath: Chemin du dossier des modules;
 */
function getModPath($pAppPath,$pModDirName='modules')
{
  if (!isset($pAppPath) || empty($pAppPath))
    return APP_PATH.DIRECTORY_SEPARATOR.$pModDirName;
  else
    return $pAppPath.DIRECTORY_SEPARATOR.$pModDirName;
}
/**
 * Appel du module d'installation
 *
 * <p>Fonction d'invocation du module d'installation</p>
 *
 * @name bootstrap::loadInstall()
 * @param $pAppDirName: Nom du répertoire des applications
 * @param $pLevel: Niveau d'erreur lié à l'installation
 * @return void
 */
function loadInstall($pAppDirName,$pLevel)
{
  header('Content-Type: text/html; charset=utf-8');
  switch ($pLevel)
  {
    case 1:
      $setupPage=<<<DELIMITHEREDOC
<html>
  <head>
    <title>Setup Page</title>
  </head>
  <body>
    <h1>Error level $pLevel</h1>
    <p>Your Application folder should be call:<b>$pAppDirName</b>
    <br />Please, rename your application folder, and refresh this page.</p>
  <body>
</html>
DELIMITHEREDOC;
      break;
    case 2:
      $setupPage=<<<DELIMITHEREDOC
<html>
  <head>
    <title>Setup Page</title>
  </head>
  <body>
    <h1>Error level $pLevel</h1>
    <p>There is no core folder in <b>$pAppDirName</b>
    <br />Maybe, you didn't rename it using the name given in configBoot.inc.php
    <br />Please, contact your administrator or reseller.</p>
  <body>
</html>
DELIMITHEREDOC;
      break;
    default:
      $setupPage=<<<DELIMITHEREDOC
<html>
  <head>
    <title>Setup Page</title>
  </head>
  <body>
    <h1>No Error level</h1>
    <p>There is a defaut in your application installation, but i cannot determine it.
    <br />Please, contact your administrator or reseller.</p>
  <body>
</html>
DELIMITHEREDOC;
      break;
  }
  echo($setupPage);
}
/**
 * Lecture du nom des modules installés
 *
 * <p>Fonction de lecture du nom des modules</p>
 *
 * @name bootstrap::getModulesNames()
 * @param $sModPath: Chemin du dossier des modules
 * @return $sModName
 */
function getModulesNames($sModPath)
{
  $sModName=Array();

  // Peut-on arriver à lire le contenu du dossier des modules 
  if ($dh = opendir($sModPath))
  {
    // Pour chaque module, on vient l'ajouter au tableau de retour
    while (($file=readdir($dh)) !== FALSE)
    {
      if ($file != '.' && $file != '..')
      {
        if (is_dir($sModPath.DIRECTORY_SEPARATOR.$file))
        {
          if (is_dir($sModPath.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR.'controllers'))
            $sModName[$file]=$sModPath.DIRECTORY_SEPARATOR.$file;
        }
      }
    }
    closedir($dh);
  }
  else
      return false;
  return $sModName;
}


function getURIBase()
{
  // Permet de retrouver et definir les paramètres du site
  $url=parse_url($_SERVER['SCRIPT_URI']);

  return dirname($url['path']);
}

function getRolesArray(){
  $conf = new Zend_Config_Ini(CONFIG_PATH.'tabarnak_acl.ini','roles');
  foreach($conf->toArray() as $k=>$v){
    $roles[$k] = $k;
  }
  return $roles;
}

function getArrayFromIniString($string){
  return strrpos($string,',') ? explode(',',$string) : array(0 => $string);
}

function isAllowed($role,$module,$controller,$action){
   $acl = getAclFromSession();
   $r = $acl->getRole($role);
   $ressource = $module."_".$controller;
   if(!$acl->has($ressource))
     return false;
   return $acl->isAllowed($role,$ressource,$action);
}

/**
 * <p>Récupération des droits de l'appli à partir de la session.</p>
 *
 * @name functions.inc::getAclFromSession()
 * @return Zend_Acl
 */
function getAclFromSession(){
  $ns = new Zend_Session_Namespace('Droits');
  $acl = $ns->__get('acl');
  return $acl;
}

function stdDecorator(){
  return array(
  'ViewHelper',
  'Errors',
  array(
    array('data' => 'HtmlTag'), 
    array('tag' => 'td', 'class' => 'element')),
  array('Label', array('tag' => 'td')),
  array(array('row' => 'HtmlTag'), array('tag' => 'tr')));
}

function buttonDecorator(){
  return array(
  'ViewHelper',
  array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
  array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
  array(array('row' => 'HtmlTag'), array('tag' => 'tr')));
}

function formDecorator(){
  return array(
  'FormElements',
  array('HtmlTag', array('tag' => 'table')),
  'Form',);
}

function is_module_active($module_name){
  $chemin = APP_PATH.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$module_name;
  if(!is_dir($chemin) || !file_exists($chemin.DIRECTORY_SEPARATOR.'descriptor.ini'))
    return false;
    
  $conf = new Zend_Config_Ini($chemin.DIRECTORY_SEPARATOR.'descriptor.ini','activation');
  return $conf->active == 1;
}

function get_all_design(){
  $templates = WRI_PATH.DIRECTORY_SEPARATOR.'templates';
  $designs = array();
  if ($dh = opendir($templates))
  {
    // Pour chaque module, on vient l'ajouter au tableau de retour
    while (($file=readdir($dh)) !== FALSE)
    {
      if ($file != '.' && $file != '..')
      {
        if (is_dir($templates.DIRECTORY_SEPARATOR.$file))
        {
          array_push($designs,$file);
        }
      }
    }
    closedir($dh);
  }
  else
    return $designs;
}

function set_design($design_name){
  setcookie('design',$design_name,time()+3600*30);
}
?>