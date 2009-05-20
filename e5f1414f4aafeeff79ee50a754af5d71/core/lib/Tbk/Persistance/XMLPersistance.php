<?php
include_once 'Factory.php';
include_once 'Persistance.php';
class Tbk_Persistance_XMLPersistance extends Tbk_Persistance_Persistance
{
  
  public function __construct($config_file){
    parent::__construct($config_file);
    //créer le requeteur
  }
  
  public function executeSelect($categorie,$requete_name,$params){
    	  echo 'SELECT en XML<br/>';
  }
  public function executeInsert($categorie,$requete_name,$params){
    	  echo 'INSERT en XML<br/>';
  }
  public function executeUpdate($categorie,$requete_name,$params){
    	  echo 'UPDATE en XML<br/>';
  }
  public function executeDelete($categorie,$requete_name,$params){
    	  echo 'DELETE en XML<br/>';
  }
  
}
?>