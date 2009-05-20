<?php
/**
 * Contrôleur principal de l'application
 *
 * @package application
 * @subpackage controllers
 */

class Admin_GroupeController extends Zend_Controller_Action
{
  
  private $groupform = null;
  
  /**
   * Affichage de la page d'accueil
   */
  public function indexAction()
  {
    $this->_forward('groupe','groupe','admin');
  }
  
  private function getStringForRights($current,$add){
    return ($current == "" || is_null($current)) ? $add : "$current,$add";
  }
  
  public function droitAction(){
    $myform = $this->getGroupeDroitsForm();
    $this->view->form = $myform;
    $this->view->subs = $myform->getSubForms();
    $this->view->compte_array = getRolesArray();
    $this->view->nbCompte = count(getRolesArray())+1;
    
    if($this->getRequest()->isPost()){
      $config = new Zend_Config_Ini(CONFIG_PATH.'tabarnak_acl.ini', null,
                              array('skipExtends'        => true,
                              'allowModifications' => true));
      
      //Vide tous les droits dans le fichier                 
      foreach(getRolesArray() as $r){
        $config->$r->__unset('deny');
        $config->$r->__unset('allow');
      }
      
      //Créer un tableau contenant la totalité des descripteurs de modules
      //Cela permet de créer ensuite les deny.
      $deny = array();
      $aListMod = getModulesNames(MOD_PATH);
      foreach($aListMod as $module=>$modChemin){
        $liste = new Zend_Config_Ini($modChemin.DIRECTORY_SEPARATOR.'descriptor.ini','general');
        foreach($liste->toArray() as $control=>$act){
          $deny[$module][$control] = array_flip(getArrayFromIniString($act));
        }
      }
      
      $allDeny = array();
      foreach(getRolesArray() as $r){
        $allDeny[$r] = $deny;
      }
      
      //Traite les données envoyés par le formulaire (seulement des allow)
      foreach($_POST as $module=>$array){
        if(is_array($array)){
          foreach($array as $controller=>$actions){
            $ressource = $module."_".$controller;
            foreach($actions as $action=>$auth){
              foreach($auth as $bla=>$role){
                $a = explode("_",$role);
                $perm = $a[0];
                $rol = $a[1];
                $current = $config->$rol->__get("$perm.$ressource");
                $str = $this->getStringForRights($current,$action);
                
                //Retire de l'array deny l'action ajoutée aux allow
                unset($allDeny[$rol][$module][$controller][$action]);
                if(count($allDeny[$rol][$module][$controller])<=0)
                  unset($allDeny[$rol][$module][$controller]);
                  
                $config->$rol->__set("$perm.$ressource",$str);
              }
            }
          }
        }
      }
      
      //Ecrit les deny pour chaque roles et modules
      foreach($allDeny as $role=>$array){
        foreach($array as $module=>$den){
          foreach($den as $controller=>$v){
            foreach($v as $action=>$l){
              $ressource = $module."_".$controller;
              $current = $config->$role->__get("deny.$ressource");
              $str = $this->getStringForRights($current,$action);
              $config->$role->__set("deny.$ressource",$str);
            }
          }
        }
      }
      
      try{
        $writer = new Zend_Config_Writer_Ini(array('config'   => $config,
                                               'filename' => CONFIG_PATH.'tabarnak_acl.ini'));
        $writer->write();
      }catch(Exception $e){
        $this->view->erreur = $e->getTraceAsString();
      }
      
      $acl_ini = new Tbk_Acl_Ini(CONFIG_PATH.'tabarnak_acl.ini');
      $sessionNS = new Zend_Session_Namespace('Droits');
      $sessionNS->__set('acl',$acl_ini);
      
      $this->_forward('index','groupe','admin');
    }
  }
  
  public function groupeAction(){
    $this->view->addGroupeForm = $this->getGroupeAjouterForm();
    $this->view->removeGroupeForm = $this->getGroupeSupprimerForm();
    $this->view->lienDroits = '/admin/groupe/droit';
  }
  
  public function addgroupeAction(){
    $config = new Zend_Config_Ini(CONFIG_PATH.'tabarnak_acl.ini', null,
                              array('skipExtends'        => true,
                              'allowModifications' => true));
    $config->roles->$_POST['nom_groupe'] = "";
    $dt_tmp = $config->$_POST['parent'];
    $config->$_POST['nom_groupe'] = $dt_tmp;
 
    try{
      $writer = new Zend_Config_Writer_Ini(array('config'   => $config,
                                             'filename' => CONFIG_PATH.'tabarnak_acl.ini'));
      $writer->write();
    }catch(Exception $e){
      $this->view->erreur = $e->getTraceAsString();
    }
    
    $acl_ini = new Tbk_Acl_Ini(CONFIG_PATH.'tabarnak_acl.ini');
    $sessionNS = new Zend_Session_Namespace('Droits');
    $sessionNS->__set('acl',$acl_ini);
    $this->_forward('groupe','groupe','admin');
  }
  
  public function removegroupeAction(){
    $config = new Zend_Config_Ini(CONFIG_PATH.'tabarnak_acl.ini', null,
                              array('skipExtends'        => true,
                              'allowModifications' => true));

    $config->__unset($_POST['nom_groupe']);
    $config->roles->__unset($_POST['nom_groupe']);    
    
    try{
      $writer = new Zend_Config_Writer_Ini(array('config'   => $config,
                                             'filename' => CONFIG_PATH.'tabarnak_acl.ini'));
      $writer->write();
    }catch(Exception $e){
      $this->view->erreur = $e->getTraceAsString();
    }
    
    $acl_ini = new Tbk_Acl_Ini(CONFIG_PATH.'tabarnak_acl.ini');
    $sessionNS = new Zend_Session_Namespace('Droits');
    $sessionNS->__set('acl',$acl_ini);
    $this->_forward('groupe','groupe','admin');
  }
  
  
  /**
   * @desc Cette fonction permet de générer le formulaire pour ajouter un groupe.
   * @return Zend_Form
   */
  private function getGroupeAjouterForm(){
    $form = new Zend_Form();
    $form->setAction('/admin/groupe/addgroupe');
    $form->setMethod('post');
    $form->setDecorators(formDecorator());
    
    $parent = new Zend_Form_Element_Select('parent');
    $parent->setRequired(true);
    $parent->setLabel("Role de base");
    $parent->addMultiOptions(getRolesArray());
    $parent->setDecorators(stdDecorator());

    $user = new Zend_Form_Element_Text('nom_groupe');
    $user->addValidator('alnum');
    $user->setRequired(true);
    $user->addFilter('StringToLower');
    $user->setLabel("Nom du groupe");
    $user->setDecorators(stdDecorator());

    $submit = new Zend_Form_Element_Submit('ajouter');
    $submit->setLabel('Ajouter');
    $submit->setDecorators(buttonDecorator());
    
    $form->addElement($parent,'parent');
    $form->addElement($user,'nom_groupe');
    $form->addElement($submit,'submit');
    return $form;
  }
  
  /**
   * @desc Cette fonction permet de générer le formulaire pour ajouter un groupe.
   * @return Zend_Form
   */
  private function getGroupeSupprimerForm(){
    $form = new Zend_Form();
    $form->setAction('/admin/groupe/removegroupe');
    $form->setMethod('post');
    $form->setDecorators(formDecorator());
    
    $parent = new Zend_Form_Element_Select('nom_groupe');
    $parent->setRequired(true);
    $parent->setLabel("Groupe à supprimer");
    $parent->addMultiOptions(getRolesArray());
    $parent->setDecorators(stdDecorator());

    $submit = new Zend_Form_Element_Submit('supprimer');
    $submit->setLabel('Supprimer');
    $submit->setDecorators(buttonDecorator());
    
    $form->addElement($parent,'nom_groupe');
    $form->addElement($submit,'submit');
    
    return $form;
  }
  
  /**
   * 
   * @desc Cette fonction permet de générer le formulaire pour gérer les droits des groupes.
   * @return Zend_Form
   */
  private function getGroupeDroitsForm(){
      if(is_null($this->groupform))
        $this->groupform = new GroupRightForm();
        
      return $this->groupform;
  }
}
?>