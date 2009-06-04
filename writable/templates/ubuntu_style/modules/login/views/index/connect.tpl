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