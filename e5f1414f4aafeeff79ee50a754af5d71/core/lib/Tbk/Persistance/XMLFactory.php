<?php
include_once 'Factory.php';
include_once 'XMLPersistance.php';
class Tbk_Persistance_XMLFactory extends Tbk_Persistance_Factory
{
  /**
   * @param  String $config_file : l'emplacement du fichier de configuration pour cette persistance
   * @return Persistance
   */
  public static function createFactory($config_file){
    //TODO : implémenter cette fonction pour charger le fichier XML
    echo 'Creation d\'une persistance type XML<br/>';
    return new Tbk_Persistance_XMLPersistance($config_file);
  }
  
}
?>