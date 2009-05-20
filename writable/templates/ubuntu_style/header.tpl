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