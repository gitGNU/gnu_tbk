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
class Tbk_View_Smarty extends Zend_View_Abstract
{
  /**
   * Objet Smarty
   * @var Smarty
   */
  public $_smarty;
  public $_doctype;

  /**
   * Constructeur
   *
   * @param string $tmplPath
   * @param array $extraParams
   * @return void
   */
  public function __construct($tmplPath = null,$extraParams = array())
  {
    $this->_smarty = new Smarty();

    if (null !== $tmplPath) {
      $this->setScriptPath($tmplPath);
    }

    foreach ($extraParams as $key => $value) {
      $this->_smarty->$key = $value;
    }
    
    $this->_doctype = new Zend_View_Helper_Doctype();
    $this->_doctype->setDoctype(Zend_View_Helper_Doctype::XHTML1_STRICT);
  }

  /**
   * Retourne l'objet moteur de gabarit
   *
   * @return Smarty
   */
  public function getEngine()
  {
    return $this->_smarty;
  }

  /**
   * Affecte le dossier des scripts de gabarits
   *
   * @param string $path Le répertoire à affecter au path
   * @return void
   */
  public function setScriptPath($path)
  {
    if (is_readable($path)) {
      $this->_smarty->template_dir = $path;
      return;
    }

    throw new Exception('Répertoire fourni invalide');
  }

  /**
   * Récupère le dossier courant des gabarits
   *
   * @return string
   */
  public function getScriptPaths()
  {
    return array($this->_smarty->template_dir);
  }

  /**
   * Alias pour setScriptPath
   *
   * @param string $path
   * @param string $prefix Unused
   * @return void
   */
  public function setBasePath($path, $prefix = 'Zend_View')
  {
    return $this->setScriptPath($path);
  }

  /**
   * Alias pour setScriptPath
   *
   * @param string $path
   * @param string $prefix Unused
   * @return void
   */
  public function addBasePath($path, $prefix = 'Zend_View')
  {
    return $this->setScriptPath($path);
  }

  /**
   * Affectation une variable au gabarit
   *
   * @param string $key Le nom de la variable
   * @param mixed $val La valeur de la variable
   * @return void
   */
  public function __set($key, $val)
  {
    $this->_smarty->assign($key, $val);
  }

  /**
   * Autorise le fonctionnement du test avec empty() and isset()
   *
   * @param string $key
   * @return boolean
   */
  public function __isset($key)
  {
    return (null !== $this->_smarty->get_template_vars($key));
  }

  /**
   * Autorise l'effacement de toutes les variables du gabarit
   *
   * @param string $key
   * @return void
   */
  public function __unset($key)
  {
    $this->_smarty->clear_assign($key);
  }

  /**
   * Affectation de variables au gabarit
   *
   * Autorise une affectation simple (une clé => une valeur)
   * OU
   * le passage d'un tableau (paire de clé => valeur)
   * à affecter en masse
   *
   * @see __set()
   * @param string|array $spec Le type d'affectation à utiliser
   (clé ou tableau de paires clé => valeur)
   * @param mixed $value (Optionel) Si vous assignez une variable nommée,
   utilisé ceci comme valeur
   * @return void
   */
  public function assign($spec, $value = null)
  {
    if (is_array($spec)) {
      $this->_smarty->assign($spec);
      return;
    }

    $this->_smarty->assign($spec, $value);
  }

  /**
   * Effacement de toutes les variables affectées
   *
   * Efface toutes les variables affectées à Zend_View
   * via {@link assign()} ou surcharge de propriété
   * ({@link __get()}/{@link __set()}).
   *
   * @return void
   */
  public function clearVars()
  {
    $this->_smarty->clear_all_assign();
  }

  /**
   * Exécute le gabarit et retourne l'affichage
   *
   * @param string $name Le gabarit à exécuter
   * @return string L'affichage
   */
  public function render($name)
  {
    return $this->_smarty->fetch($name);
  }

  protected function _run()
  {
    include func_get_arg(0);
  }
  
  public function doctype(){
    return $this->_doctype;
  }
}
?>
