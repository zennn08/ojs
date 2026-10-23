{**
 * plugins/themes/agrobisnis/templates/frontend/components/header.tpl
 *
 * Professional Academic Header & Navbar with Dark/Light Mode Toggle
 * ITB Journals Pattern for UNRI Agribisnis - Open Journal Systems 3.5 LTS
 *}
{strip}
	{assign var="showingLogo" value=true}
	{if !$displayPageHeaderLogo}
		{assign var="showingLogo" value=false}
	{/if}
{/strip}
<!DOCTYPE html>
<html lang="{$currentLocale|replace:"_":"-"}" xml:lang="{$currentLocale|replace:"_":"-"}">
{if !$pageTitleTranslated}{capture assign="pageTitleTranslated"}{translate key=$pageTitle}{/capture}{/if}
{include file="frontend/components/headerHead.tpl"}
<script>
	(function() {
		var savedTheme = localStorage.getItem('ojs-theme');
		if (savedTheme === 'dark' || (!savedTheme && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
			document.documentElement.setAttribute('data-theme', 'dark');
		} else {
			document.documentElement.setAttribute('data-theme', 'light');
		}
	})();
</script>
<body class="pkp_page_{$requestedPage|escape|default:"index"} pkp_op_{$requestedOp|escape|default:"index"}{if $showingLogo} has_site_logo{/if}" dir="{$currentLocaleLangDir|escape|default:"ltr"}">

<div class="pkp_structure_page">

	{* Top Institutional Navigation Bar (ITB Journals layout adapted for UNRI Agribisnis) *}
	<header class="itb-topbar" role="banner">
		<div class="itb-topbar-container">
			{* Left: Brand Identity *}
			<div class="itb-topbar-brand">
				<a href="{$baseUrl}/" class="itb-brand-anchor">
					<span class="itb-brand-monogram">UNRI</span>
					<div class="itb-brand-info">
						<span class="itb-brand-name">UNRI Agribusiness Journals</span>
						<span class="itb-brand-unit">Universitas Riau &bull; Fakultas Pertanian</span>
					</div>
				</a>
			</div>

			{* Right: Main Horizontal Menu, Auth & Theme Toggle *}
			<nav class="itb-topbar-nav" aria-label="{translate|escape key="common.navigation.site"}">
				<ul class="itb-menu-list">
					<li><a href="{$baseUrl}/" class="itb-menu-link">Beranda</a></li>
					<li><a href="{$baseUrl}/#directory" class="itb-menu-link">Jurnal</a></li>
					<li><a href="{$baseUrl}/#disciplines" class="itb-menu-link">Rumpun Keilmuan</a></li>
					<li><a href="{url journal='jsep' page='about' op='submissions'}" class="itb-menu-link">Pedoman Naskah</a></li>
					<li><a href="{url page='about'}" class="itb-menu-link">Tentang</a></li>
				</ul>

				<div class="itb-auth-group">
					{* Dark / Light Mode Toggle Button *}
					<button id="themeToggle" class="itb-theme-toggle" type="button" aria-label="Ganti Mode Gelap / Terang" title="Ganti Mode Gelap / Terang">
						<span class="itb-icon-sun" aria-hidden="true">&#9728;&#65039;</span>
						<span class="itb-icon-moon" aria-hidden="true">&#127769;</span>
					</button>

					{if $isUserLoggedIn}
						<a href="{url page='submissions'}" class="itb-auth-user">
							<span class="fa fa-user" aria-hidden="true"></span> {$currentUser->getUsername()|escape}
						</a>
						<a href="{url page='login' op='signOut'}" class="itb-auth-logout">Keluar</a>
					{else}
						<a href="{url page='login'}" class="itb-auth-login">Masuk</a>
						<a href="{url page='user' op='register'}" class="itb-btn-register">Daftar</a>
					{/if}
				</div>
			</nav>
		</div>
	</header>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var toggleBtn = document.getElementById('themeToggle');
			if (!toggleBtn) return;
			toggleBtn.addEventListener('click', function() {
				var current = document.documentElement.getAttribute('data-theme') || 'light';
				var next = current === 'dark' ? 'light' : 'dark';
				document.documentElement.setAttribute('data-theme', next);
				localStorage.setItem('ojs-theme', next);
			});
		});
	</script>

	{* Force full width to eliminate narrow 652px sidebar restriction *}
	{assign var="isFullWidth" value=true}
	{assign var="hasSidebar" value=0}

	<div class="pkp_structure_content">
		<div class="pkp_structure_main" role="main">
			<a id="pkp_content_main"></a>
