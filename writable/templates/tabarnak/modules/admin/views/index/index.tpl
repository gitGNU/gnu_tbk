{include file="$chemin_patron/header.tpl"}
<h1>Administration</h1>

<div id="menu_admin">
	<p>
	{foreach from=$liens key=k item=v}
		<a href="{$v}">{$k}</a> | 
	{/foreach}
	</p>
</div>
{include file="$chemin_patron/footer.tpl"}