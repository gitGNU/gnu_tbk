<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Tabarnak .::. {$title|default:"Accueil"}</title>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <link rel="stylesheet" type="text/css" href="{$smarty.const.BASE_URL}writable/templates/tabarnak/tabarnak.css"/>
   </head>
   <body>
   <div id="baniere">
   </div>
   
   <div id="page">
   <div id="menu">
   	{load_menu assign="liens"}
   	{foreach from=$liens key='k' item='v'}
   		<h3>{$k}</h3>
   		{foreach from=$v key='texte' item='lien'}
   			<a href="{$lien}">{$texte}</a>
   		{/foreach}
   	{/foreach}
   </div>
   
   <div id="content">