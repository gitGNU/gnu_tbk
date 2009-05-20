<?php
class Admin_ModuleController extends Zend_Controller_Action
{
  /**
   * Affichage de la page d'accueil
   */
  public function indexAction()
  {
    if(!Zend_Auth::getInstance()->hasIdentity()){
      $this->_forward('index','index','login');
      return ;
    }
    $link = array();
    $modList = getModulesNames(MOD_PATH);
    foreach($modList as $v){
      if(is_file($v.'/controllers/AdminController.php')){
        $decoupe = explode(DIRECTORY_SEPARATOR,$v);
        $modName = $decoupe[count($decoupe)-1];
        $link[$modName] = '/'.$modName.'/admin/index';
      }
    }
    
    $this->view->form = $this->getActiveForm();
    $this->view->liens = $link;
  }
  
  public function activationAction(){
    if($this->getRequest()->isPost()){
      $form = $this->getActiveForm();
      foreach($_POST as $module=>$perm){
        if(preg_match("#active_#",$module)){
          $mod = explode('_',$module,2);
          $file = MOD_PATH.DIRECTORY_SEPARATOR.$mod[1].DIRECTORY_SEPARATOR.'descriptor.ini';
          $config = new Zend_Config_Ini($file, null,
                              array('skipExtends'=> true,
                              'allowModifications'=> true));
          $config->activation->active = $perm;
          
          try{
            $writer = new Zend_Config_Writer_Ini(array('config'   => $config,
                                                   'filename' => $file));
            $writer->write();
          }catch(Exception $e){
            $this->view->erreur = $e->getTraceAsString();
          }          
        }
      }
    }
  }
  
  private function getActiveForm(){
    $form = new Zend_Form();
    $form->setAction('/admin/module/activation');
    $form->setMethod('post');
    $form->setDecorators(formDecorator());
    
    $liste_module = getModulesNames(MOD_PATH);
    foreach($liste_module as $module=>$module_path){
      $parent = new Zend_Form_Element_Checkbox("active_$module");
      $parent->setLabel("Activer $module");
      $parent->setDecorators(stdDecorator());
      if(is_module_active($module))
        $parent->setChecked(true);
        
      $form->addElement($parent,"active_$module");
    }
    

    $submit = new Zend_Form_Element_Submit('ajouter');
    $submit->setLabel('Enregistrer');
    $submit->setDecorators(buttonDecorator());
    
    $form->addElement($submit,'submit');
    return $form;
  }
  
}
?>