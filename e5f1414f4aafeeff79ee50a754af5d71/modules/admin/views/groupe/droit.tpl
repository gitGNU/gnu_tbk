{include file="$chemin_patron/header.tpl"}
<h1>Droits des groupes</h1>

<form method="{$form->getMethod()}" action="{$form->getAction()}" enctype="{$form->getEnctype()}">
<table id="admin_table">
		<tr>
			<td></td>
{foreach from=$compte_array item=name}
      		<td>{$name}</td>
{/foreach}
		</tr>

{foreach from=$subs key=module item=v}
        <tr><td class="cell_module" colspan="{$nbCompte}">{$module}</td></tr>
        {foreach from=$v key=controller item=d}
        	<tr><td class="cell_controller" colspan="{$nbCompte}">{$controller}</td></tr>
          {foreach from=$d key=action item=c}
          	<tr>
          		<td>{$action}</td>
          		{foreach from=$c->getMultiOptions() key=droit item=h}
          			{assign var=trouve value=false}
          			{assign var=liste value=$c->getValue()}
          			
          			{foreach from=$liste key=nam item=it}
          				{if $it eq $droit}
          					{assign var=trouve value=true}
          				{/if}
          			{/foreach}
          			
          			{if $trouve eq true}
          				<td><input id="{$module}-{$controller}-{$action}-{$droit|replace:'_':''}" type="checkbox" checked="checked" name="{$module}[{$controller}][{$action}][]" value="{$droit}"/>Autoriser</td>
          			{else}
          				<td><input id="{$module}-{$controller}-{$action}-{$droit|replace:'_':''}" type="checkbox" name="{$module}[{$controller}][{$action}][]" value="{$droit}"/>Autoriser</td>
          			{/if}
          		{/foreach}
          	</tr>
          {/foreach}
        {/foreach}
{/foreach}
      
      <tr><td colspan="{$nbCompte}">{$form->enregistrer}</td></td></table>
</form>
{include file="$chemin_patron/footer.tpl"}