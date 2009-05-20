<?php
class AdminController extends Zend_Controller_Action
{
  /**
   * Affichage de la page d'accueil
   */
  public function indexAction()
  {
    $this->view->test = "Mouahahahahahaha";
  }
  
}
?>