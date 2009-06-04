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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--

Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Title      : Unembellished 
Version    : 1.0
Released   : 20090428
Description: A two-column, fixed-width and lightweight template ideal for 1024x768 resolutions. Suitable for blogs and small websites.

-->
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Tabarnak .::. {$title|default:"Accueil"}</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<link href="{$smarty.const.BASE_URL}writable/templates/ubuntu_style/default.css" rel="stylesheet" type="text/css" />
	</head>
<body>
<div id="wrapper">
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1><a href="#">Tabarnak</a></h1>
		<h2> Design by Free Css Templates</h2>
	</div>
	<div id="menu">
		<ul class="niveau1">
		{load_menu assign="liens"}
		{foreach from=$liens key='k' item='v'}
				<li class="sousmenu"><a href="#">{$k}</a>
				<ul class="niveau2">
				{foreach from=$v key='texte' item='lien'}
					<li><a href="{$lien}">{$texte}</a></li>
				{/foreach}
				</ul>
				</li>
		{/foreach}
		</ul>
	</div>
</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start content -->
   <div id="content">