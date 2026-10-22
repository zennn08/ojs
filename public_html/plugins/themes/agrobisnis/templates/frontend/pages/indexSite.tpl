{**
 * plugins/themes/agrobisnis/templates/frontend/pages/indexSite.tpl
 *
 * ITB Journals Directory Layout customized for Universitas Riau (UNRI)
 * Program Studi Agribisnis - OJS 3.5 LTS.
 *}
{include file="frontend/components/header.tpl"}

<div class="page_index_site itb-portal-container">

	{* Hero Dual-Panel Section (ITB Journals layout adapted for UNRI Agribusiness) *}
	<section class="itb-hero-section">
		<div class="itb-hero-grid">
			<div class="itb-hero-left">
				<div class="itb-hero-badge">
					<span class="itb-badge-dot"></span>
					UNIVERSITAS RIAU &bull; FAKULTAS PERTANIAN
				</div>
				<h1 class="itb-hero-title">
					UNRI Agribusiness Journals
				</h1>
				<p class="itb-hero-subtitle">
					Portal Direktori Jurnal Ilmiah Program Studi Agribisnis Universitas Riau. Pusat diseminasi hasil riset agribisnis, sosial ekonomi pertanian, dan rantai pasok komoditas tropika.
				</p>
			</div>

			<div class="itb-hero-right">
				<div class="itb-hero-card">
					<div class="itb-card-heading">
						Akses Publikasi Berkala Ilmiah
					</div>
					<p class="itb-card-text">
						Wadah ilmiah terbuka (Open Access) untuk civitas akademika, peneliti, dan praktisi dalam mempublikasikan karya penelitian terverifikasi serta telaah kritis di bidang pertanian berkelanjutan.
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

	{* Stat & Quick Navigation Bar *}
	<section class="itb-stat-bar">
		<div class="itb-stat-content">
			<span class="itb-stat-pill">
				<strong>{$journals|@count}</strong> Jurnal Terindeks Aktif
			</span>
			<span class="itb-stat-text">
				Direktori publikasi ilmiah peer-reviewed di bawah pengelolaan Program Studi Agribisnis, Jurusan Sosial Ekonomi Pertanian UNRI.
			</span>
		</div>
	</section>

	{* Discipline / Subject Cluster Cards (4 columns) *}
	<section id="disciplines" class="itb-disciplines-section">
		<div class="itb-section-header">
			<h2 class="itb-section-title">Rumpun Keilmuan & Fokus Kajian</h2>
			<p class="itb-section-desc">Telusuri jurnal ilmiah berdasarkan fokus kepakaran dan bidang kajian strategis pertanian.</p>
		</div>

		<div class="itb-disciplines-grid">
			<div class="itb-disc-card">
				<div class="itb-disc-tag">01 &bull; SOSIO-EKONOMI</div>
				<h3 class="itb-disc-title">Sosio-Ekonomi & Bisnis Pertanian</h3>
				<p class="itb-disc-desc">
					Manajemen usahatani, ekonomi sumberdaya, analisis pembiayaan agribisnis, tata niaga komoditas, dan kebijakan agraria tropis.
				</p>
				<a href="#journal-jsep" class="itb-disc-link">Lihat Jurnal: JSEP &rarr;</a>
			</div>

			<div class="itb-disc-card">
				<div class="itb-disc-tag">02 &bull; AGROTEKNOLOGI</div>
				<h3 class="itb-disc-title">Agroteknologi & Pengolahan</h3>
				<p class="itb-disc-desc">
					Inovasi pascapanen, hilirisasi produk perkebunan (sawit, karet, sagu, kelapa), bioteknologi terapan, dan mutu pangan.
				</p>
				<a href="#journal-japt" class="itb-disc-link">Lihat Jurnal: JAPT &rarr;</a>
			</div>

			<div class="itb-disc-card">
				<div class="itb-disc-tag">03 &bull; SUPPLY CHAIN</div>
				<h3 class="itb-disc-title">Rantai Pasok & Logistik Agro</h3>
				<p class="itb-disc-desc">
					Optimasi rantai pasok komoditas perkebunan, efisiensi logistik pedesaan-perkotaan, ketahanan pasokan, dan digitalisasi pasar tani.
				</p>
				<a href="#directory" class="itb-disc-link">Fokus Terintegrasi &rarr;</a>
			</div>

			<div class="itb-disc-card">
				<div class="itb-disc-tag">04 &bull; PEMBERDAYAAN</div>
				<h3 class="itb-disc-title">Kelembagaan & Pemberdayaan Petani</h3>
				<p class="itb-disc-desc">
					Penguatan koperasi tani, sosiologi pedesaan, adopsi teknologi agribisnis, penyuluhan partisipatif, dan mitigasi risiko iklim.
				</p>
				<a href="#directory" class="itb-disc-link">Fokus Terintegrasi &rarr;</a>
			</div>
		</div>
	</section>

	{* Journal Directory Section (3 columns grid) *}
	<section id="directory" class="itb-directory-section">
		<div class="itb-section-header">
			<h2 class="itb-section-title">Direktori Jurnal Ilmiah Agribisnis</h2>
			<p class="itb-section-desc">Jurnal berkala ilmiah yang diterbitkan dan dikelola secara profesional sesuai kaidah saintifik nasional.</p>
		</div>

		{if !$journals|@count}
			<div class="itb-empty-alert">
				{translate key="site.noJournals"}
			</div>
		{else}
			<div class="itb-journal-grid">
				{foreach from=$journals item=journal}
					{capture assign="url"}{url journal=$journal->getPath()}{/capture}
					{assign var="thumb" value=$journal->getLocalizedData('journalThumbnail')}
					{assign var="description" value=$journal->getLocalizedDescription()}
					{assign var="path" value=$journal->getPath()}

					<div class="itb-journal-card" id="journal-{$path}">
						<div class="itb-journal-header">
							<span class="itb-journal-acronym">
								{$path|upper}
							</span>
							<span class="itb-journal-cat">
								{if $path == 'jsep'}
									Sosial Ekonomi & Bisnis
								{elseif $path == 'japt'}
									Agroteknologi & Pengolahan
								{else}
									Jurnal Agribisnis
								{/if}
							</span>
						</div>

						{if $thumb}
							<div class="itb-journal-thumb">
								<a href="{$url}">
									<img src="{$journalFilesPath}{$journal->getId()}/{$thumb.uploadName|escape:"url"}"{if $thumb.altText} alt="{$thumb.altText|escape|default:''}"{/if}>
								</a>
							</div>
						{/if}

						<div class="itb-journal-body">
							<h3 class="itb-journal-title">
								<a href="{$url}" rel="bookmark">
									{$journal->getLocalizedName()|escape}
								</a>
							</h3>
							{if $description}
								<p class="itb-journal-desc">
									{$description|strip_tags|truncate:200:"..."}
								</p>
							{/if}
						</div>

						<div class="itb-journal-footer">
							<a href="{$url}" class="itb-action-primary">
								Kunjungi Jurnal <span class="itb-arrow">&nearr;</span>
							</a>
							<a href="{url journal=$journal->getPath() page="issue" op="current"}" class="itb-action-secondary">
								Terbitan Terbaru &rarr;
							</a>
						</div>
					</div>
				{/foreach}
			</div>
		{/if}
	</section>

	{* Bottom CTA Banner *}
	<section class="itb-cta-banner">
		<div class="itb-cta-inner">
			<div class="itb-cta-text">
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
