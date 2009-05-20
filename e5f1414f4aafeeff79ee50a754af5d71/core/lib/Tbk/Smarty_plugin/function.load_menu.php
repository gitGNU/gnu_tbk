<?php
function smarty_function_load_menu($params,&$smarty)
{
  $liens = array();
  if(Zend_Auth::getInstance()->hasIdentity()){
    $tab = Zend_Auth::getInstance()->getStorage()->read();
    $role = $tab['role'];
  }
  else
    $role = 'invite';
    
  //Chargement du fichier ini
  $config = new Zend_Config_Ini(CONFIG_PATH.'menu.ini');
  $array = $config->toArray();
  
  $configDroit = new Zend_Config_Ini(CONFIG_PATH.'menu.ini','deny');
  $deny = $configDroit->toArray();
  
  
  foreach($array as $cle=>$valeur){
    if($cle != "deny"){
      if(!isset($deny[$cle]) || (isset($deny[$cle]) && !preg_match("#$role#",$deny[$cle]))){
        $liens[$cle] = '#';
        foreach($array[$cle] as $k=>$v){
          $allowed = true;
          $explode = explode('/',$v);
          $module = $explode[1];
          if(is_module_active($module)){
            if(isset($deny[$cle."_".$k])){
              if(preg_match("#$role#",$deny[$cle."_".$k]))
                $allowed = false;
            }
            if($allowed)
              $liens[$k] = $v;
          }
        }
      }
    }
  }
  
  $smarty->assign($params['assign'],$liens);
}
?>