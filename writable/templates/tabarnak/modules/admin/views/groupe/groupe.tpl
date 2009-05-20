{include file="$chemin_patron/header.tpl"}
<h1>Administration des groupes</h1>
{if isset($erreur)}
	{$erreur}
{/if}
<hr/>
<h3>Créer un nouveau groupe</h3>
{$addGroupeForm}
<hr/>
<h3>Supprimer un groupe</h3>
{$removeGroupeForm}
<hr/>
<h3>Droits des groupes</h3>
<a href="{$lienDroits}">Gestion des droits des différents groupes.</a>
{include file="$chemin_patron/footer.tpl"}