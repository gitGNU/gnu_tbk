<?php
abstract class Tbk_Persistance_Persistance
{
  
  private $config_file;
  public $requeteur;
  
  public function __construct($config_file){
    $this->config_file = $config_file;
  }
  
  protected function _setRequeteur($requeteur){
    $this->requeteur = $requeteur;
  }
  
  /**
   * @param  String $categorie : Indique l'element où se fera la modification (table, fichier ...)
   * @param  String $requete_name : le nom de la requete (tel qu'il est dans le fichier de configuration)
   * @param  Array<String> $params : les éléments à récupérer (champ, balise ...)
   * @return Un tableau de résultats.
   */
  public abstract function executeSelect($categorie,$requete_name,$params);
  
  /**
   * @param  String $categorie : Indique l'element où se fera la modification (table, fichier ...)
   * @param  String $requete_name : le nom de la requete (tel qu'il est dans le fichier de configuration)
   * @param  Array<String> $params : les éléments à insérer (champ, balise ...)
   * @return boolean : true si la requête s'est effectuée, false sinon.
   */
  public abstract function executeInsert($categorie,$requete_name,$params);
  
  /**
   * @param  String $categorie : Indique l'element où se fera la modification (table, fichier ...)
   * @param  String $requete_name : le nom de la requete (tel qu'il est dans le fichier de configuration)
   * @param  Array<String> $params : les éléments à mettre à jour et leurs nouvelles valeurs (champ, balise ...)
   * @return boolean : true si la requête s'est effectuée, false sinon.
   */
  public abstract function executeUpdate($categorie,$requete_name,$params);
  
  /**
   * @param  String $categorie : Indique l'element où se fera la modification (table, fichier ...)
   * @param  String $requete_name : le nom de la requete (tel qu'il est dans le fichier de configuration)
   * @param  Array<String> $params : les éléments à supprimé, les conditions ...
   * @return boolean : true si la requête s'est effectuée, false sinon.
   */
  public abstract function executeDelete($categorie,$requete_name,$params);
  
}
?>