{**
 * plugins/themes/agrobisnis/templates/frontend/pages/indexSite.tpl
 *
 * ITB Journals Directory Layout customized for Universitas Riau (UNRI)
 * Program Studi Agribisnis - OJS 3.5 LTS.
 *}
{include file="frontend/components/header.tpl"}

<div class="page_index_site itb-portal-container">

	{* Hero Dual-Panel Section (Expansive ITB Journals layout adapted for UNRI Agribusiness) *}
	<section class="itb-hero-section">
		<div class="itb-hero-grid">
			<div class="itb-hero-left">
				<div class="itb-hero-badge">
					<span class="itb-badge-dot"></span>
					PUBLIKASI ILMIAH UNIVERSITAS RIAU
				</div>
				<h1 class="itb-hero-title">
					UNRI Agribusiness Journals
				</h1>
				<p class="itb-hero-subtitle">
					Jelajahi jurnal ilmiah yang diterbitkan dan dikelola oleh Program Studi Agribisnis, Jurusan Sosial Ekonomi Pertanian, Fakultas Pertanian, Universitas Riau.
				</p>
			</div>

			<div class="itb-hero-right">
				<div class="itb-hero-card">
					<h3 class="itb-card-heading">
						Direktori UNRI Agribusiness Journals menghadirkan publikasi berkala dari rumpun agribisnis dan pertanian tropika berkelanjutan.
					</h3>
					<p class="itb-card-text">
						Publikasi mencakup bidang sosial ekonomi pertanian, manajemen usahatani, tata niaga komoditas perkebunan, agroteknologi, hilirisasi produk pangan, logistik rantai pasok, dan kelembagaan petani di Indonesia.
					</p>
					<div class="itb-hero-actions">
						<a href="#directory" class="itb-btn itb-btn-primary">
							Jelajahi Jurnal
						</a>
						<a href="#disciplines" class="itb-btn itb-btn-outline">
							Pilih Rumpun Keilmuan
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	{* ITB-Style Split Stat Bar *}
	<section class="itb-stat-bar">
		<div class="itb-stat-split">
			<div class="itb-stat-count-box">
				<span class="itb-stat-number">{$journals|@count}</span>
				<span class="itb-stat-label">Jurnal dalam berbagai bidang keilmuan</span>
			</div>
			<div class="itb-stat-info-box">
				<div class="itb-stat-kicker">DIREKTORI JURNAL ILMIAH AGRIBISNIS UNRI</div>
				<p class="itb-stat-desc">
					Temukan jurnal ilmiah terakreditasi dan peer-reviewed di bawah pengelolaan Program Studi Agribisnis, Jurusan Sosial Ekonomi Pertanian UNRI.
				</p>
			</div>
		</div>
	</section>

	{* Discipline / Subject Cluster Cards (4 columns) *}
	<section id="disciplines" class="itb-disciplines-section">
		<div class="itb-section-header-split">
			<div class="itb-section-header-left">
				<span class="itb-section-kicker">TEMUKAN BERDASARKAN BIDANG</span>
				<h2 class="itb-section-title">Jelajahi Bidang Keilmuan</h2>
			</div>
			<div class="itb-section-header-right">
				<p class="itb-section-desc">Pilih kategori untuk menuju kelompok jurnal yang paling sesuai dengan bidang penelitian Anda.</p>
			</div>
		</div>

		<div class="itb-disciplines-grid">
			<div class="itb-disc-card">
				<div class="itb-disc-header">
					<div class="itb-disc-tag">01 &bull; SOSIO-EKONOMI</div>
					<h3 class="itb-disc-title">Sosio-Ekonomi & Bisnis Pertanian</h3>
				</div>
				<p class="itb-disc-desc">
					Manajemen usahatani, ekonomi sumberdaya, analisis pembiayaan agribisnis, tata niaga komoditas, dan kebijakan agraria tropis.
				</p>
				<div class="itb-disc-footer">
					<a href="#cluster-jsep" class="itb-disc-link">Jurnal: JSEP <span class="itb-disc-arrow">&darr;</span></a>
				</div>
			</div>

			<div class="itb-disc-card">
				<div class="itb-disc-header">
					<div class="itb-disc-tag">02 &bull; AGROTEKNOLOGI</div>
					<h3 class="itb-disc-title">Agroteknologi & Pengolahan</h3>
				</div>
				<p class="itb-disc-desc">
					Inovasi pascapanen, hilirisasi produk perkebunan (sawit, karet, sagu, kelapa), bioteknologi terapan, dan mutu pangan.
				</p>
				<div class="itb-disc-footer">
					<a href="#cluster-japt" class="itb-disc-link">Jurnal: JAPT <span class="itb-disc-arrow">&darr;</span></a>
				</div>
			</div>

			<div class="itb-disc-card">
				<div class="itb-disc-header">
					<div class="itb-disc-tag">03 &bull; SUPPLY CHAIN</div>
					<h3 class="itb-disc-title">Rantai Pasok & Logistik Agro</h3>
				</div>
				<p class="itb-disc-desc">
					Optimasi rantai pasok komoditas perkebunan, efisiensi logistik pedesaan-perkotaan, ketahanan pasokan, dan digitalisasi pasar tani.
				</p>
				<div class="itb-disc-footer">
					<a href="#directory" class="itb-disc-link">Fokus Terintegrasi <span class="itb-disc-arrow">&darr;</span></a>
				</div>
			</div>

			<div class="itb-disc-card">
				<div class="itb-disc-header">
					<div class="itb-disc-tag">04 &bull; PEMBERDAYAAN</div>
					<h3 class="itb-disc-title">Kelembagaan & Pemberdayaan Petani</h3>
				</div>
				<p class="itb-disc-desc">
					Penguatan koperasi tani, sosiologi pedesaan, adopsi teknologi agribisnis, penyuluhan partisipatif, dan mitigasi risiko iklim.
				</p>
				<div class="itb-disc-footer">
					<a href="#directory" class="itb-disc-link">Fokus Terintegrasi <span class="itb-disc-arrow">&darr;</span></a>
				</div>
			</div>
		</div>
	</section>

	{* Journal Directory Section Organized by Category (Like ITB Journals) *}
	<div id="directory" class="itb-directory-wrapper">

		{* Category 1: Sosio-Ekonomi & Bisnis Pertanian *}
		<section id="cluster-jsep" class="itb-category-section">
			<div class="itb-section-header-split">
				<div class="itb-section-header-left">
					<span class="itb-section-kicker">SOSIO-EKONOMI &amp; BISNIS PERTANIAN</span>
					<h2 class="itb-section-title">Sosio-Ekonomi &amp; Bisnis Pertanian</h2>
				</div>
				<div class="itb-section-header-right">
					<p class="itb-section-desc">
						Jurnal di bidang sosial ekonomi pertanian, analisis usahatani, tata niaga komoditas perkebunan tropis, dan kebijakan agraria.
					</p>
				</div>
			</div>

			<div class="itb-journal-grid">
				{foreach from=$journals item=journal}
					{if $journal->getPath() == 'jsep'}
						{capture assign="url"}{url journal=$journal->getPath()}{/capture}
						{assign var="thumb" value=$journal->getLocalizedData('journalThumbnail')}
						{assign var="description" value=$journal->getLocalizedDescription()}
						{assign var="path" value=$journal->getPath()}

						<div class="itb-journal-card" id="journal-{$path}">
							<div class="itb-journal-header">
								<span class="itb-journal-acronym">{$path|upper}</span>
								<span class="itb-journal-cat">Sosial Ekonomi Pertanian</span>
							</div>

							<div class="itb-journal-body">
								<h3 class="itb-journal-title">
									<a href="{$url}" rel="bookmark">
										{$journal->getLocalizedName()|escape}
									</a>
								</h3>
								{if $description}
									<p class="itb-journal-desc">
										{$description|strip_tags|truncate:220:"..."}
									</p>
								{/if}
							</div>

							<div class="itb-journal-footer">
								<a href="{$url}" class="itb-action-link">
									Kunjungi Jurnal <span class="itb-arrow">&nearr;</span>
								</a>
								<a href="{url journal=$journal->getPath() page="issue" op="current"}" class="itb-action-sublink">
									Terbitan Terbaru &rarr;
								</a>
							</div>
						</div>
					{/if}
				{/foreach}
			</div>
		</section>

		{* Category 2: Agroteknologi & Pengolahan *}
		<section id="cluster-japt" class="itb-category-section">
			<div class="itb-section-header-split">
				<div class="itb-section-header-left">
					<span class="itb-section-kicker">AGROTEKNOLOGI &amp; PENGOLAHAN HASIL</span>
					<h2 class="itb-section-title">Agroteknologi &amp; Pengolahan</h2>
				</div>
				<div class="itb-section-header-right">
					<p class="itb-section-desc">
						Jurnal di bidang inovasi budidaya tropika, pascapanen, teknologi pengolahan hasil perkebunan rakyat, dan bioteknologi pangan.
					</p>
				</div>
			</div>

			<div class="itb-journal-grid">
				{foreach from=$journals item=journal}
					{if $journal->getPath() == 'japt'}
						{capture assign="url"}{url journal=$journal->getPath()}{/capture}
						{assign var="thumb" value=$journal->getLocalizedData('journalThumbnail')}
						{assign var="description" value=$journal->getLocalizedDescription()}
						{assign var="path" value=$journal->getPath()}

						<div class="itb-journal-card" id="journal-{$path}">
							<div class="itb-journal-header">
								<span class="itb-journal-acronym">{$path|upper}</span>
								<span class="itb-journal-cat">Agroteknologi &amp; Pengolahan</span>
							</div>

							<div class="itb-journal-body">
								<h3 class="itb-journal-title">
									<a href="{$url}" rel="bookmark">
										{$journal->getLocalizedName()|escape}
									</a>
								</h3>
								{if $description}
									<p class="itb-journal-desc">
										{$description|strip_tags|truncate:220:"..."}
									</p>
								{/if}
							</div>

							<div class="itb-journal-footer">
								<a href="{$url}" class="itb-action-link">
									Kunjungi Jurnal <span class="itb-arrow">&nearr;</span>
								</a>
								<a href="{url journal=$journal->getPath() page="issue" op="current"}" class="itb-action-sublink">
									Terbitan Terbaru &rarr;
								</a>
							</div>
						</div>
					{/if}
				{/foreach}
			</div>
		</section>

	</div><!-- #directory -->

	{* Bottom CTA Banner *}
	<section class="itb-cta-banner">
		<div class="itb-cta-inner">
			<div class="itb-cta-text">
				<div class="itb-cta-kicker">UNRI AGRIBUSINESS JOURNALS</div>
				<h3 class="itb-cta-heading">
					Membuka Akses terhadap Riset dan Publikasi Agribisnis Universitas Riau
				</h3>
				<p class="itb-cta-subheading">
					Mendukung transparansi riset saintifik berbasis Open Access untuk memperkuat ekosistem pertanian, perkebunan rakyat, dan kedaulatan pangan tropis.
				</p>
			</div>
			<div class="itb-cta-button-wrap">
				<a href="{url journal='jsep' page='about' op='submissions'}" class="itb-btn itb-btn-cta">
					Pedoman Pengiriman Naskah &rarr;
				</a>
			</div>
		</div>
	</section>

</div><!-- .page_index_site -->

{include file="frontend/components/footer.tpl"}
