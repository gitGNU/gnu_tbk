<?php
/**
 * Name: Factory.php
 * Class : Persistance_Factory
 * Factory de la persistance. Pattern factory utilisé pour l'abstraction de la persistance.
 * Dernière modification : $Date$
 * @author     Jean-Baptiste BLANC <blanc.j.baptiste@gmail.com>
 * @copyright  Copyright (c) 2008 Jean-Baptiste BLANC
 * @license    http://opensource.org/licenses/gpl-2.0.php  GNU General Public License (GPL)
 * @link       http://www.gnu.org/licenses/licenses.html
 * @category   tabarnak
 * @package    Persistance
 * @version    $Id$
 * @filesource
 */

include_once 'BDDFactory.php';
include_once 'XMLFactory.php';
class Tbk_Persistance_Factory
{
  /**
   * @param  string $type : Indique le type de persistance (XML, BDD ...)
   * @param  string $config_file : chemin absolu du fichier contenant les requêtes et les configurations
   * @return Persistance if $type is correct, null else.
   */
  public static function createFactory($type,$config_file){
    switch ($type) {
    	case 'XML':
    	  echo 'Creation d\'une persistance XML<br/>';
    	  return Tbk_Persistance_XMLFactory::createFactory($config_file);
    	  break;
    	case 'BDD':
    	  echo 'Creation d\'une persistance BDD<br/>';
    	  return Tbk_Persistance_BDDFactory::createFactory($config_file);
    	  break;
    	default:
    	  return null;
    	  break;
    }
  }

}
?>