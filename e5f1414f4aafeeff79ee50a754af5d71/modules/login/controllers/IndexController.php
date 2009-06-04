<?php
/**
 *  +----------------------------------------------------------------------+
 *  | This file is part of TABARNAK - PHP Version 5                        |
 *  +----------------------------------------------------------------------+
 *  | Copyright (C) 2008-2009 Libricks                                     |
 *  +----------------------------------------------------------------------+
 *  | Ce programme est un logiciel libre distribue sous licence GNU/GPL.   |
 *  | Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne. |
 *  |                                                                      |
 *  | This program is free software; you can redistribute it and/or        |
 *  | modify it under the terms of the GNU General Public License          |
 *  | as published by the Free Software Foundation; either version 2       |
 *  | of the License, or (at your option) any later version.               |
 *  |                                                                      |
 *  | This program is distributed in the hope that it will be useful,      |
 *  | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
 *  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
 *  | GNU General Public License for more details.                         |
 *  |                                                                      |
 *  | You should have received a copy of the GNU General Public License    |
 *  | along with this program; if not, write to                            |
 *  | the Free Software Foundation, Inc.,                                  |
 *  | 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA         |
 *  |                                                                      |
 *  +----------------------------------------------------------------------+
 *  | Authors: 	* Marc-Henri PAMISEUX <marc-henri.pamiseux@libricks.org>   |
 *  | 			* Jean-Baptiste BLANC <blanc.j.baptiste@gmail.com>		   |
 *  +----------------------------------------------------------------------+
 */

/**
 * Contrôleur principal de l'application
 *
 * @package application
 * @subpackage controllers
 */

class Login_IndexController extends Zend_Controller_Action
{
  public function getForm(){
    $form = new Zend_Form();
    $form->setAction('/login/index/connect');
    $form->setMethod('post');
    $form->setAttrib('class', 'formulaire');
    $form->setDecorators(formDecorator());

    $user = new Zend_Form_Element_Text('user');
    $user->addValidator('alnum');
    $user->setRequired(true);
    $user->addFilter('StringToLower');
    $user->setLabel("Identifiant");
    $user->setDecorators(stdDecorator());

    $pass = new Zend_Form_Element_Password('pass');
    $pass->addValidator('StringLength', false, array(6));
    $pass->setRequired(true);
    $pass->setLabel("Mot de passe");
    $pass->setDecorators(stdDecorator());

    $submit = new Zend_Form_Element_Submit('login');
    $submit->setLabel('Connexion');
    $submit->setDecorators(buttonDecorator());
    
    $form->addElement($user,'user');
    $form->addElement($pass,'pass');
    $form->addElement($submit,'login');
    return $form;
  }

  /**
   * Affichage de la page d'accueil
   */
  public function indexAction()
  {
    if(Zend_Session::namespaceIsset('Membre')){
      $this->view->form = "Vous êtes déjà connecté.";
      return null;
    }
    
    $form = $this->getForm();
    $this->view->form = $form;
  }

  public function connectAction(){
    if(Zend_Auth::getInstance()->hasIdentity()){
      $this->view->form = "";
      $this->view->erreur = "Vous êtes déjà connecté.";
      return null;
    }
    
    if (!$this->getRequest()->isPost()){
      return $this->_forward('index');
    }

    $form = $this->getForm();

    if (!$form->isValid($_POST)) {
      $this->view->form = $form;
      $this->view->erreur = "Veuillez correctement remplir les champs !";
    }
    else{
      $auth = Zend_Auth::getInstance();
      $authAdapter = new Tbk_AuthAdapter($_POST['user'],$_POST['pass']);
      
      $resultat = $auth->authenticate($authAdapter);
      
      if(!$resultat->isValid()){
        $this->view->form = $form;
        $erreur = "Imposible de vous connecter !";
        foreach($resultat->getMessages() as $v){
          $erreur .= "<br/>".$v;
        }
        $this->view->erreur = $erreur;
      }
      else{
        //on stock les infos dans la session
        $config = new Zend_Config_Ini(CONFIG_PATH.'persistance.ini', 'general');
    
        $db = Zend_Db::factory($config->db);
        Zend_Db_Table::setDefaultAdapter($db);
        
        $select = $db->select();
        $select->from("membre","*");
        $select->where("login=?",$_POST['user']);
        
        $result = $db->fetchAll($select);
        
        $infosSession = array('user' => $result[0]['login'], 'pass' => $result[0]['password'],
        'role' => $result[0]['role'], 'mail' => $result[0]['mail']);
        Zend_Auth::getInstance()->getStorage()->write($infosSession);
        $this->_forward('index','index','index');
      }
    }
  }
  
  public function disconnectAction(){
    Zend_Auth::getInstance()->clearIdentity();
    Zend_Session::destroy();
    $this->_forward('index','index','index');
  }
}