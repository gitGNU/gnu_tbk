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

class Login_RegisterController extends Zend_Controller_Action
{
  public function getForm(){
    $form = new Zend_Form();
    $form->setAction('/login/register/register');
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

    $passconf = new Zend_Form_Element_Password('passconf');
    $passconf->addValidator('StringLength', false, array(6));
    $passconf->setRequired(true);
    $passconf->setLabel("Confirmation");
    $passconf->setDecorators(stdDecorator());

    $mail = new Zend_Form_Element_Text('mail');
    $mail->addValidator('EmailAddress');
    $mail->setRequired(true);
    $mail->setLabel("Email");
    $mail->setDecorators(stdDecorator());

    $listeRole = new Zend_Form_Element_Hidden('role');
    $listeRole->setRequired(true);
    $listeRole->setValue("membre");

    $source = new Zend_Form_Element_Hidden('source_form');
    $source->setValue("register");

    $submit = new Zend_Form_Element_Submit('login');
    $submit->setLabel('Inscription');
    $submit->setDecorators(buttonDecorator());

    $form->addElement($user,'user');
    $form->addElement($pass,'pass');
    $form->addElement($passconf,'passconf');
    $form->addElement($mail,'mail');
    $form->addElement($listeRole,'role');
    $form->addElement($source,'source_form');
    $form->addElement($submit,'login');
    return $form;
  }

  /**
   * Affichage de la page d'accueil
   */
  public function indexAction()
  {
    $form = $this->getForm();
    $this->view->form = $form;
  }

  public function registerAction(){

    if (!$this->getRequest()->isPost()){
      return $this->_forward('index');
    }

    $form = $this->getForm();

    if (!$form->isValid($_POST)) {
      $this->view->form = $form;
      $this->view->erreur = "Veuillez correctement remplir les champs !";
    }
    else{
      $conf = new Zend_Config_Ini(CONFIG_PATH.'persistance.ini');
    
      $config = $conf->general;
      $base_auth = $conf->toArray();
      $auth = $base_auth["auth"]["db"];
      
      $db = Zend_Db::factory($config->db);
      Zend_Db_Table::setDefaultAdapter($db);

      $select = $db->select();
      $select->from($auth["table"],"*");
      $select->where($auth["champ"]["login"]."=?",$_POST['user']);

      $result = $db->fetchAll($select);

      $erreur = array();

      if(count($result)<=0){
        //Si personne ne porte ce nom
        if($_POST['pass'] === $_POST['passconf']){
          $array = array();
          $array[$auth["champ"]["id"]] = null;
          $array[$auth["champ"]["login"]] = $_POST['user'];
          $array[$auth["champ"]["mdp"]] = md5($_POST['pass']);
          $array[$auth["champ"]["mail"]] = $_POST['mail'];
          $array[$auth["champ"]["role"]] = $_POST['role'];
          $db->insert($auth["table"],$array);
          $this->_forward('index','index','index');
        }
        else{
          $this->view->erreur = "Les mots de passe entrés sont différents.";
        }
      }
      else{
        $this->view->erreur = "Ce nom existe déjà dans la base de données.";
      }
      $this->view->form = $form;
    }
    if($_POST['source_form'] == 'admin')
    $this->_forward('index','utilisateur','admin');
  }
}
?>