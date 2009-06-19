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
class Admin_UtilisateurController extends Zend_Controller_Action
{
  /**
   * Affichage de la page d'accueil
   */
  public function indexAction(){
    $page = $this->_getParam('page');
    if(empty($page) || is_null($page))
      $page = 1;
    else
      $page = intval($page);
    
    $indexForm = $this->getIndexForm($page);
    $elements = $indexForm[1]->getElements();
    
    $infos = array();
    foreach($elements as $k=>$v){
      if(preg_match("#infos_#",$k))
      $infos[$k] = $v;
    }

    $this->view->ajout = $this->getFormAjout();
    $paginator =  $indexForm[0];
    $paginator->setView($this->view);
    $this->view->pages = $paginator->getPages();
    $this->view->liste = $indexForm[1];
    $this->view->elements = $elements;
    $this->view->infos = $infos;
    
  }

  private function getIndexForm($page = 1){
    $conf = new Zend_Config_Ini(CONFIG_PATH.'persistance.ini');
    
    $config = $conf->general;
    $base_auth = $conf->toArray();
    $auth = $base_auth["auth"]["db"];

    $db = Zend_Db::factory($config->db);
    Zend_Db_Table::setDefaultAdapter($db);


    $select = $db->select();
    $select->from($auth["table"],'*');
    $select->where($auth["champ"]["role"]."!=?",'admin');

    $users = $db->fetchAll($select);
    
    $paginator = Zend_Paginator::factory($users);
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(10);
    $paginator->setPageRange(3);

    $form = new Zend_Form();
    $form->setAction('/admin/utilisateur/suppression');
    $form->setMethod('post');
    $form->setAttrib('class', 'formulaire');

    foreach($paginator as $infos){
      $login = $infos[$auth["champ"]["login"]];
      $id = $infos[$auth["champ"]["id"]];
      $mail = $infos[$auth["champ"]["mail"]];
      $role = $infos[$auth["champ"]["role"]];
      
      $hidden = new Zend_Form_Element_Hidden("infos_$id");
      $hidden->setValue("$id;$login;$mail;$role");
      $checkbox = new Zend_Form_Element_Checkbox("del_$id");
      $checkbox->setLabel("$login");
      $checkbox->setDecorators(array('ViewHelper','Errors'));

      $form->addElement($hidden,"infos_$id");
      $form->addElement($checkbox,"del_$id");
    }

    $submit = new Zend_Form_Element_Submit('supprimer');
    $submit->setLabel('Supprimer');
    $submit->setDecorators(array('ViewHelper',
    array(array('row' => 'HtmlTag'), array('tag' => 'p'))));

    $form->addElement($submit,'supprimer');
    return array($paginator,$form);
  }
  

  /*
   * PARTIE SUR LA SUPPRESSION
   */

  public function suppressionAction(){
    if($this->getRequest()->isPost()){
      $to_del = array();
      foreach($_POST as $k=>$v){
        if(preg_match("#del_#",$k) && intval($v) == 1){
          $expl = explode("_",$k,2);
          array_push($to_del,intval($expl[1]));
        }
      }
      if(count($to_del)>0){
        $conf = new Zend_Config_Ini(CONFIG_PATH.'persistance.ini');

        $config = $conf->general;
        $base_auth = $conf->toArray();
        $auth = $base_auth["auth"]["db"];
    
        $db = Zend_Db::factory($config->db);
        Zend_Db_Table::setDefaultAdapter($db);
        
        foreach($to_del as $id){
          $where = $db->quoteInto($auth["champ"]["id"].' = ?', $id);
          $affected = $db->delete($auth["table"],$where);
        }
      }
    }
    $this->_forward('index','utilisateur','admin');
  }

  /*
   * PARTIE SUR LA MODIFICATION
   */

  public function modificationAction(){
    $var = $this->_getParam('user');
    if(!$this->getRequest()->isPost() && (empty($var) || is_null($var))){
      $this->_forward('index','utilisateur','admin');
    }
    else{
      if(!isset($_POST['user_id']))
        $form = $this->getFormModification($this->_getParam('user'));
      else{
        $form = $this->getFormModification($_POST['user_id']);
        
        if (!$form->isValid($_POST)) {
          $this->view->form = $form;
        }
        else{
          $conf = new Zend_Config_Ini(CONFIG_PATH.'persistance.ini');
  
          $config = $conf->general;
          $base_auth = $conf->toArray();
          $auth = $base_auth["auth"]["db"];
  
          $db = Zend_Db::factory($config->db);
          Zend_Db_Table::setDefaultAdapter($db);
  
          $values = array(
            $auth["champ"]["login"] => $_POST['user'],
            $auth["champ"]["role"] => $_POST['role'],
            $auth["champ"]["mail"] => $_POST['mail']
          );
          if(!empty($_POST['pass']))
            $values[$auth["champ"]["mdp"]] = md5($_POST['pass']);
          
          
            
          $where = $db->quoteInto($auth["champ"]["id"].' = ?', $_POST['user_id']);
          $affected = $db->update($auth["table"],$values,$where);
  
          if($affected>0)
            $this->_forward('index','utilisateur','admin');
        }
      }
      $this->view->form = $form;
    }
  }

  private function getFormModification($user_id = null){
    $conf = new Zend_Config_Ini(CONFIG_PATH.'persistance.ini');

    $config = $conf->general;
    $base_auth = $conf->toArray();
    $auth = $base_auth["auth"]["db"];

    $db = Zend_Db::factory($config->db);
    Zend_Db_Table::setDefaultAdapter($db);


    $select = $db->select();
    $select->from($auth["table"],"*");
    $select->where($auth["champ"]["id"]."=?",$user_id);

    $result = $db->fetchAll($select);

    $info_user = array();

    if(count($result)>0){
      $info_user['login'] = $result[0][$auth["champ"]["login"]];
      $info_user['role'] = $result[0][$auth["champ"]["role"]];
      $info_user['mail'] = $result[0][$auth["champ"]["mail"]];
    }

    $form = new Zend_Form();
    $form->setAction('/admin/utilisateur/modification');
    $form->setMethod('post');
    $form->setAttrib('class', 'formulaire');
    $form->setDecorators(formDecorator());

    $user = new Zend_Form_Element_Text('user');
    $user->addValidator('alnum');
    $user->setRequired(true);
    $user->addFilter('StringToLower');
    $user->setLabel("Identifiant");
    $user->setValue($info_user['login']);
    $user->setDecorators(stdDecorator());

    $pass = new Zend_Form_Element_Password('pass');
    $pass->addValidator('StringLength', false, array(6));
    $pass->setRequired(false);
    $pass->setLabel("Mot de passe");
    $pass->setDecorators(stdDecorator());

    $mail = new Zend_Form_Element_Text('mail');
    $mail->addValidator('EmailAddress');
    $mail->setRequired(true);
    $mail->setLabel("Email");
    $mail->setValue($info_user['mail']);
    $mail->setDecorators(stdDecorator());

    $listeRole = new Zend_Form_Element_Select('role');
    $listeRole->setRequired(true);
    $listeRole->setLabel("Role");
    $listeRole->addMultiOptions(getRolesArray());
    $listeRole->setValue($info_user['role']);
    $listeRole->setDecorators(stdDecorator());

    $submit = new Zend_Form_Element_Submit('login');
    $submit->setLabel('Modification');
    $submit->setDecorators(buttonDecorator());

    $hidden = new Zend_Form_Element_Hidden('user_id');
    $hidden->setValue($user_id);
    
    $source = new Zend_Form_Element_Hidden('source');
    $source->setValue("admin");

    $form->addElement($user,'user');
    $form->addElement($pass,'pass');
    $form->addElement($mail,'mail');
    $form->addElement($hidden,'user_id');
    $form->addElement($listeRole,'role');

    $form->addElement($submit,'login');
    return $form;
  }

  private function getListeModification(){
    $form = new Zend_Form();
    $form->setAction('/admin/utilisateur/modification');
    $form->setMethod('post');
    $form->setAttrib('class', 'formulaire');
    $form->setDecorators(formDecorator());

    $user = new Zend_Form_Element_Select('user');
    $user->setRequired(true);
    $user->setLabel("Utilisateur : ");

    $conf = new Zend_Config_Ini(CONFIG_PATH.'persistance.ini');

    $config = $conf->general;
    $base_auth = $conf->toArray();
    $auth = $base_auth["auth"]["db"];

    $db = Zend_Db::factory($config->db);
    Zend_Db_Table::setDefaultAdapter($db);
    $select = $db->select();
    $select->from($auth["table"],$auth["champ"]["login"]);

    $result = $db->fetchAll($select);
    foreach($result as $k=>$v){
      $user->addMultiOption($v[$auth["champ"]["login"]],$v[$auth["champ"]["login"]]);
    }

    $user->setDecorators(stdDecorator());


    $source = new Zend_Form_Element_Hidden('source');
    $source->setValue("liste");

    $submit = new Zend_Form_Element_Submit('login');
    $submit->setLabel('Modification');
    $submit->setDecorators(buttonDecorator());

    $form->addElement($user,'user');
    $form->addElement($source,'source_form');
    $form->addElement($submit,'login');

    return $form;
  }


  /*
   * PARTIE SUR L'AJOUT
   */

  private function getFormAjout(){
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

    $listeRole = new Zend_Form_Element_Select('role');
    $listeRole->setRequired(true);
    $listeRole->setLabel("Role");
    $listeRole->addMultiOptions(getRolesArray());
    $listeRole->setDecorators(stdDecorator());

    $source = new Zend_Form_Element_Hidden('source_form');
    $source->setValue("admin");

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
}
?>