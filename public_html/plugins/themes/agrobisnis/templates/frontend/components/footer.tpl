{**
 * plugins/themes/agrobisnis/templates/frontend/components/footer.tpl
 *
 * UNRI Agribusiness Institutional Footer - OJS 3.5 LTS.
 *}

	</div><!-- pkp_structure_main -->

	{* Sidebars *}
	{if empty($isFullWidth)}
		{capture assign="sidebarCode"}{call_hook name="Templates::Common::Sidebar"}{/capture}
		{if $sidebarCode}
			<div class="pkp_structure_sidebar left" role="complementary">
				{$sidebarCode}
			</div><!-- pkp_sidebar.left -->
		{/if}
	{/if}
</div><!-- pkp_structure_content -->

<footer class="itb-footer-wrapper" role="contentinfo">
	<a id="pkp_content_footer"></a>

	<div class="itb-footer-container">
		<div class="itb-footer-grid">
			{* Column 1: Institutional Identity *}
			<div class="itb-footer-col itb-footer-institution">
				<div class="itb-footer-brand">
					<div class="itb-footer-monogram">UNRI</div>
					<div>
						<h3 class="itb-footer-univ">Universitas Riau</h3>
						<div class="itb-footer-dept">Program Studi Agribisnis &bull; Fakultas Pertanian</div>
					</div>
				</div>
				<p class="itb-footer-desc">
					Jurusan Sosial Ekonomi Pertanian, Fakultas Pertanian, Universitas Riau.<br>
					Kampus Bina Widya Km. 12,5 Simpang Baru, Pekanbaru, Riau 28293.
				</p>
				<p class="itb-footer-contact">
					Surel: <a href="mailto:agribisnis@unri.ac.id">agribisnis@unri.ac.id</a><br>
					Laman Resmi: <a href="https://fp.unri.ac.id" target="_blank" rel="noopener">fp.unri.ac.id</a>
				</p>
			</div>

			{* Column 2: Indexing & Directory Affiliations *}
			<div class="itb-footer-col itb-footer-indexing">
				<h4 class="itb-footer-heading">Indeksasi & Direktori</h4>
				<ul class="itb-indexing-list">
					<li><span class="itb-index-badge">SINTA</span> Science and Technology Index</li>
					<li><span class="itb-index-badge">GARUDA</span> Garba Rujukan Digital</li>
					<li><span class="itb-index-badge">Google Scholar</span> Google Cendekia</li>
					<li><span class="itb-index-badge">Crossref</span> DOI Registration Agency</li>
					<li><span class="itb-index-badge">ROAD</span> Directory of OA Resources</li>
				</ul>
			</div>

			{* Column 3: Publishing Ethics & Systems *}
			<div class="itb-footer-col itb-footer-governance">
				<h4 class="itb-footer-heading">Tata Kelola & Penerbitan</h4>
				<ul class="itb-footer-nav">
					<li><a href="{url journal='jsep' page='about' op='submissions'}">Pedoman Naskah & Template</a></li>
					<li><a href="{url journal='jsep' page='about' op='editorialTeam'}">Dewan Redaksi & Mitra Bestari</a></li>
					<li><a href="{url page='about' op='aboutThisPublishingSystem'}">Tentang OJS 3.5 LTS</a></li>
				</ul>
				<div class="itb-license-note">
					Artikel didistribusikan di bawah lisensi Creative Commons Attribution 4.0 International (CC BY 4.0).
				</div>
			</div>
		</div>

		{* Bottom Copyright Bar *}
		<div class="itb-footer-bottom">
			<div class="itb-footer-copy">
				&copy; 2026 Program Studi Agribisnis, Fakultas Pertanian, Universitas Riau. Seluruh hak cipta dilindungi undang-undang.
			</div>
			<div class="itb-footer-platform">
				Platform &amp; alur kerja oleh <a href="https://pkp.sfu.ca/ojs/" target="_blank" rel="noopener">Open Journal Systems</a>
			</div>
		</div>
	</div>
</footer><!-- .itb-footer-wrapper -->

</div><!-- pkp_structure_page -->

{load_script context="frontend"}

{call_hook name="Templates::Common::Footer::PageFooter"}
</body>
</html>
