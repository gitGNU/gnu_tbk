<?php
class Admin_UtilisateurController extends Zend_Controller_Action
{
  /**
   * Affichage de la page d'accueil
   */
  public function indexAction(){
    $this->view->ajout = $this->getFormAjout();
    $this->view->modification = $this->getListeModification();
  }

  
  /*
   * PARTIE SUR LA SUPPRESSION
   */
  
  public function suppressionAction(){
    
  }

  
  /*
   * PARTIE SUR LA MODIFICATION
   */
  
  public function modificationAction(){
    if(!$this->getRequest()->isPost()){
      $this->view->form = $this->getListeModification();
    }
    else{
      if($_POST['source'] == "liste"){
        $this->view->form = $this->getFormModification($_POST['user']);
      }
      else{
        $form = $this->getFormModification($_POST['user']);
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
              $auth["champ"]["mail"] => $_POST['mail'],
            );
            
            echo "<pre>";
            var_dump($values);
            echo "</pre>";
            
            $db->update($auth["table"],$values,$auth["champ"]["login"]."=".$_POST['user']);

            $this->view->form = $form;
        }
      }
    }
  }
  
  private function getFormModification($user_name = null){
    $conf = new Zend_Config_Ini(CONFIG_PATH.'persistance.ini');

    $config = $conf->general;
    $base_auth = $conf->toArray();
    $auth = $base_auth["auth"]["db"];

    $db = Zend_Db::factory($config->db);
    Zend_Db_Table::setDefaultAdapter($db);

    
    $select = $db->select();
    $select->from($auth["table"],"*");
    $select->where($auth["champ"]["login"]."=?",$user_name);

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
    
    
    $source = new Zend_Form_Element_Hidden('source');
    $source->setValue("admin");
    
    $form->addElement($user,'user');
    $form->addElement($pass,'pass');
    $form->addElement($mail,'mail');
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