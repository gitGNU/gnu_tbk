<?php
class Tbk_Membre
{
  private $variables;
  
  public function Membre($nom,$mdp,$mail,$groupe = 'invite'){
    $this->variables['nom'] = $nom;
    $this->variables['mdp'] = $mdp;
    $this->variables['mail'] = $mail;
    $this->variables['groupe'] = $groupe;
  }
  
  public function get($nom){
    return $this->variables[$nom];
  }
  
  public function set($nom,$valeur){
    $this->variables[$nom] = $valeur;
  }
  
  public function toString(){
    $chaine = "";
    
    foreach($this->variables as $k=>$v){
      $chaine .= "[$k] => $v <br/>";
    }
    
    return $chaine;
  }
}
?>