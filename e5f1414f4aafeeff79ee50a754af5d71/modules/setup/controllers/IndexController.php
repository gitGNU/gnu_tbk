<?php
/**
 * Contrôleur principal de l'application
 * 
 * @package application
 * @subpackage controllers
 */
class Setup_IndexController extends Zend_Controller_Action
{
    /**
     * Affichage de la page d'accueil
     */
    public function indexAction()
    {
      $this->view->content = "Test MHP 2";
    }
}