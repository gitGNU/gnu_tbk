<?php
class Tbk_AuthAdapter implements Zend_Auth_Adapter_Interface
{

  private $identifiant,$motdepasse,$role;

  /**
   * Définition de l'identifiant et du mot de passe
   * pour authentification
   *
   * @return void
   */
  public function __construct($identifiant, $motdepasse)
  {
    $this->identifiant = $identifiant;
    $this->motdepasse = $motdepasse;
  }

  /**
   * Réalise une tentative d'authentification
   *
   * @throws Zend_Auth_Adapter_Exception Si l'authentification
   *                                     ne peut pas être réalisée
   * @return Zend_Auth_Result
   */
  public function authenticate()
  {
    $conf = new Zend_Config_Ini(CONFIG_PATH.'persistance.ini');
    
    $config = $conf->general;
    $base_auth = $conf->toArray();
    $auth = $base_auth["auth"]["db"];
    
    $db = Zend_Db::factory($config->db);
    Zend_Db_Table::setDefaultAdapter($db);
    
    $select = $db->select();
    $select->from($auth['table'],"*");
    $select->where($auth['champ']['login']."=?",$this->identifiant);
    
    $result = $db->fetchAll($select);
    
    if(count($result) > 0){
      if (md5($this->motdepasse) == $result[0][$auth['champ']['mdp']]) {
        $res = new Zend_Auth_Result(
          Zend_Auth_Result::SUCCESS,
          $this->identifiant,
          array());
        return $res;
      }
      else {
        $res = new Zend_Auth_Result(
          Zend_Auth_Result::FAILURE,
          null,
          array("Mauvais mot de passe."));
        return $res;
      }
    }
    else{
      $res = new Zend_Auth_Result(
          Zend_Auth_Result::FAILURE,
          null,
          array("Login inconnu"));
      return $res;
    }
  }
}
?>