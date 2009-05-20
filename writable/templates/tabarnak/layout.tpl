<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
   <head>
       <title>Tabarnak</title>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <style>
       	{include file="./tabarnak.css"}
       </style>
   </head>
   <body>
   <div id="baniere">
   	<h1>Tabarnak</h1>
   </div>
   
   <div id="menu">
   	{load_menu assign="liens"}
   	{foreach from=$liens key='k' item='v'}
   		<a href="{$v}">{$k}</a>
   	{/foreach}
   </div>
   
   <div id="content">
   	{php}
   		$this->layout()->content;
   	{/php}
   </div>
   <div style="clear:both;"></div>
   </body>
</html>