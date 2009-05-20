{include file="$chemin_patron/header.tpl"}

<h1>Connexion</h1>

{if $erreur}
<b>{$erreur}</b>
{$form}
{/if}

{if $valeur}
	<ul>
	{foreach from=$valeur key=champ item=val}
		<li>{$champ} => {$val}</li>
	{/foreach}
	</ul>
{/if}
{include file="$chemin_patron/footer.tpl"}