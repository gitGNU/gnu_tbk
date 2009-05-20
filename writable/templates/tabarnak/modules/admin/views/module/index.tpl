{include file="$chemin_patron/header.tpl"}

<h1>Administration des modules</h1>

<div id="menu_admin">
	<p>
	{foreach from=$liens key=k item=v}
		<a href="{$v}"> {$k} </a><br/>
	{/foreach}
	</p>
</div>

<hr/>
<h1> Activation des modules </h1>
{$form}

{include file="$chemin_patron/footer.tpl"}