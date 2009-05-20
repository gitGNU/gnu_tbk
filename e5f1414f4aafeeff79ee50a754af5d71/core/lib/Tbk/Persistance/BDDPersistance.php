<?php
include_once 'Persistance.php';
class Tbk_Persistance_BDDPersistance extends Tbk_Persistance_Persistance
{
  //TODO : Implémenter cette classe pour la rendre fonctionnelle.
  
  public function __construct($config_file){
    parent::__construct($config_file);
    //Préparer toute les requêtes de manière à les utiliser rapidement par la suite.
  }
  
  public function executeSelect($categorie,$requete_name,$params){
    	  echo 'SELECT en BDD<br/>';
  }
  public function executeInsert($categorie,$requete_name,$params){
    	  echo 'INSERT en BDD<br/>';
  }
  public function executeUpdate($categorie,$requete_name,$params){
    	  echo 'UPDATE en BDD<br/>';
  }
  public function executeDelete($categorie,$requete_name,$params){
    	  echo 'DELETE en BDD<br/>';
  }
  
}
?>