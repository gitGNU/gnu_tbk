<?php
include_once 'Factory.php';
include_once 'BDDPersistance.php';
class Tbk_Persistance_BDDFactory extends Tbk_Persistance_Factory
{
  
  public static function createFactory($config_file){
    //TODO : implémenter cette fonction pour charger le fichier XML
    echo 'Creation d\'une persistance type BDD<br/>';
    return new Tbk_Persistance_BDDPersistance($config_file);
  }
  
}
?>