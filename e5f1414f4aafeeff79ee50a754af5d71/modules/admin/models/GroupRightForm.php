<?php
class GroupRightForm extends Zend_Form
{
  public function __construct($option=null)
  {
    parent::__construct($option);
    $config = new Zend_Config_Ini(CONFIG_PATH.'tabarnak_acl.ini');
    $aListMod = getModulesNames(MOD_PATH);
    $listeRole = getRolesArray();
    $this->setAction('/admin/groupe/droit');
    $this->setMethod('post');

    foreach($aListMod as $module=>$modChemin){
      $liste = new Zend_Config_Ini($modChemin.DIRECTORY_SEPARATOR.'descriptor.ini','general');
      
      //Création du sous formulaire
      $subForm = new Zend_Form_SubForm();
      $subForm->setName($module);
      $subForm->setLegend($module);
      
      //Listage des différents controllers et de leurs actions
      foreach($liste->toArray() as $controller=>$actions){
        $subFormCont = new Zend_Form_SubForm();
        $subFormCont->setName($controller);
        $subFormCont->setLegend($controller);
        
        $aActions = getArrayFromIniString($actions);
        foreach($aActions as $action){
          $checked = array();
          $group = new Zend_Form_Element_MultiCheckbox($action);
          foreach($listeRole as $role){
            $group->addMultiOption("allow_$role","Autoriser $action pour $role");            
            
            if(isAllowed($role,$module,$controller,$action))
              array_push($checked,"allow_$role");
          }
          
          
          $group->setValue($checked);

          $subFormCont->addElement($group,$action);
        }
        
        $subForm->addSubForm($subFormCont,$controller);
      }
      
      //Ajout au formulaire de base
      $this->addSubForm($subForm,$module);
    }
    $this->addElement('submit', 'enregistrer', array('label' => 'Enregistrer'));
  }
}
?>