<?php
class Tbk_Acl_Ini extends Zend_Acl{

  private $file;

  public function __construct($file)	{
    $roles = new Zend_Config_Ini($file, 'roles') ;
    $this->_setRoles($roles) ;

    $ressources = new Zend_Config_Ini($file, 'ressources') ;
    $this->_setRessources($ressources) ;

    foreach ($roles->toArray() as $role => $parents)	{
      $privileges = new Zend_Config_Ini($file, $role) ;
      $this->_setPrivileges($role, $privileges) ;
    }
    $this->file = $file;
  }

  protected function _setRoles($roles)	{
    foreach ($roles as $role => $parents)	{
      if (empty($parents))	{
        $parents = null ;
      } else {
        $parents = explode(',', $parents) ;
      }

      $this->addRole(new Zend_Acl_Role($role), $parents);
    }

    return $this ;
  }

  protected function _setRessources($ressources)	{
    foreach ($ressources as $ressource => $parents)	{
      if (empty($parents))	{
        $parents = null ;
      } else {
        $parents = explode(',', $parents) ;
      }

      $this->add(new Zend_Acl_Resource($ressource), $parents);
    }

    return $this ;
  }

  protected function _setPrivileges($role, $privileges)	{
    foreach ($privileges as $do => $ressources)	{
      foreach ($ressources as $ressource => $actions)	{
        if (empty($actions))	{
          $actions = null ;
        } else {
          $actions = explode(',', $actions) ;
        }

        $this->{$do}($role, $ressource, $actions);
      }
    }

    return $this ;
  }

  public function getRoles(){
    $roles = new Zend_Config_Ini($this->file,'roles');
    $roles = $roles->toArray();
    $return = null;
    $i = 0;
    foreach($roles as $k=>$v){
      $return[$i++] = $k;
    }
    return $return;
  }

  public function getRightsByModule(){
    // Si on veux les droits généraux
    $roles = $this->getRoles();
     
    foreach($roles as $key){
      $tmp = new Zend_Config_Ini($this->file,$key);
      $tmp = $tmp->toArray();
      foreach($tmp as $control => $values){
        foreach($values as $cute=>$actions){
          $cuter = explode('_',$cute);
          $module = $cuter[0];
          $controller = $cuter[1];
          $array[$module][$controller][$key][$control] = $actions;
        }
      }
    }
  }
}
?>