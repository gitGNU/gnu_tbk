{*
 +----------------------------------------------------------------------+
 | This file is part of TABARNAK - PHP Version 5                        |
 +----------------------------------------------------------------------+
 | Copyright (C) 2008-2009 Libricks                                     |
 +----------------------------------------------------------------------+
 | Ce programme est un logiciel libre distribue sous licence GNU/GPL.   |
 | Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne. |
 |                                                                      |
 | This program is free software; you can redistribute it and/or        |
 | as published by the Free Software Foundation; either version 2       |
 | of the License, or (at your option) any later version.               |
 |                                                                      |
 | This program is distributed in the hope that it will be useful,      |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
 | GNU General Public License for more details.                         |
 |                                                                      |
 | You should have received a copy of the GNU General Public License    |
 | along with this program; if not, write to                            |
 | the Free Software Foundation, Inc.,                                  |
 | 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA         |
 |                                                                      |
 +----------------------------------------------------------------------+
 | Author: Marc-Henri PAMISEUX <marc-henri.pamiseux@libricks.org>       |
 +----------------------------------------------------------------------+
*}
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
      </table>
      {$form->enregistrer}
</form>
{include file="$chemin_patron/footer.tpl"}