{include file="$chemin_patron/header.tpl"}
{literal}
<script language="javascript" type="text/javascript">
	var visible = new Array();
	
	function cacher(id){
		var element = document.getElementById(id);
		element.style.display = 'none';
	}
	
	function montrer(id){
		var element = document.getElementById(id);
		element.style.display = 'block';
	}

	function action(id,item){
		if(visible[id] == null)
			visible[id] = true;
		
		if(visible[id] == true){
			visible[id] = false;
			cacher(id);
			item.innerHTML = "(Montrer le bloc)";
		}
		else{
			visible[id] = true;
			montrer(id);
			item.innerHTML = "(Cacher le bloc)";
		}
	}
</script>
{/literal}
<h1>Utilisateurs</h1>
<h3>Ajout : <a href="#" style="font-size:0.7em;" onclick="javascript:action('ajout',this);">(Cacher le bloc)</a></h3>
<div id="ajout">{$ajout}
<hr/>
</div>
<br/>
<h3>Liste des utilisateurs : <a href="#" style="font-size:0.7em;" onclick="javascript:action('users',this);">(Cacher le bloc)</a></h3>
<br/>
<div id="users">
<form method="{$liste->getMethod()}" action="{$liste->getAction()}" enctype="{$liste->getEnctype()}">
<table id="admin_table">
		<tr>
			<td>Id</td>
      		<td>Login</td>
      		<td>Mail</td>
      		<td>Role</td>
      		<td>Modifier</td>
      		<td>Supprimer</td>
		</tr>

{foreach from=$infos key=k item=v}
	{assign var='infos_user' value=$v->getValue()}
	{php}
		$var = $this->_tpl_vars['infos_user'];
		$this->assign('donnees',explode(';',$var));
	{/php}
	{assign var='name_elem' value="del_$donnees[0]"}
		<tr>
     		<td>{$donnees[0]}</td>
     		<td>{$donnees[1]}</td>
     		<td>{$donnees[2]}</td>
     		<td>{$donnees[3]}</td>
     		<td style="text-align:center"><a href="/admin/utilisateur/modification/user/{$donnees[0]}">modification</a></td>
     		<td style="text-align:center">{$elements[$name_elem]}</td>
     	</tr>
{/foreach}
      </table><br/>
      {load_paginator paginator=$pages assign='allPages'}
      {foreach from=$allPages item=it}
      	{if $pages->current == $it}
      		<strong><a href="/admin/utilisateur/index/page/{$it}">{$it}</a></strong> 
      	{elseif $it == '...'}
      		{$it}
      	{else}
      		<a href="/admin/utilisateur/index/page/{$it}">{$it}</a>
      	{/if}
      {/foreach}
      <br/>
      {$liste->supprimer}
</form>
</div>
{include file="$chemin_patron/footer.tpl"}