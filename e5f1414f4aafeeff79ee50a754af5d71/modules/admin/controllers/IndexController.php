<?php
/**
 * Contrôleur principal de l'application
 *
 * @package application
 * @subpackage controllers
 */

class Admin_IndexController extends Zend_Controller_Action
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
    
    $link = array('Modules' => 'admin/module/index', 
    'Serveur' => 'admin/index/phpinfo',
    'Groupes' => 'admin/groupe/index',
    'Utilisateurs' => 'admin/utilisateur/index', );
    
    $this->view->liens = $link;
  }
  
  public function phpinfoAction(){
    
  }
  
}
?>