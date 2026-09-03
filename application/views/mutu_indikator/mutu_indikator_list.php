<?php
                     $indikator=$this->db->query('SELECT 
	target,ket_num,ket_denum,ket_judul,jenis,judul
	
FROM
	list_indikator 
where id_indikator = "'.$this->input->get('id').'"')->row();
                                    



$target=$indikator->target;
$ketnum=$indikator->ket_num;
$ketdenum=$indikator->ket_denum;
$ketjudul=$indikator->ket_judul;
$tanggal=$this->input->get('tanggal');
$jenis=$indikator->jenis;
$judul=$indikator->judul;
if ($tanggal==""){
$tanggal = date("Y-m-d");
}
?>
<div class="container-fluid mutu-page">

<style>
	.mutu-page{
		--ink:#0F1B2A;
		--paper:#F6F8F9;
		--card:#FFFFFF;
		--accent:#2F6F5E;
		--accent-dark:#234F44;
		--accent-soft:#E3EEE9;
		--gold:#C08A34;
		--gold-soft:#F6ECDA;
		--line:#E4E9EA;
		--muted:#62767C;
		--danger:#B3483D;
		--danger-soft:#F7E5E2;
		--font-body:'IBM Plex Sans','Nunito',-apple-system,sans-serif;
		--font-mono:'IBM Plex Mono',monospace;
		font-family:var(--font-body);
		color:var(--ink);
	}
	.mutu-page *{ box-sizing:border-box; }

	.mutu-card{
		background:var(--card);
		border:1px solid var(--line);
		border-radius:14px;
		box-shadow:0 16px 40px -26px rgba(15,27,42,0.35);
		overflow:hidden;
		margin-bottom:22px;
	}

	/* ---------- HEADER ---------- */
	.mutu-card-head{
		position:relative;
		padding:22px 26px 20px;
		background:var(--ink);
		overflow:hidden;
	}
	.mutu-head-row{ position:relative; z-index:1; }
	.mutu-card-head .eyebrow{
		margin:0 0 4px;
		font-family:var(--font-mono);
		font-size:10.5px;
		letter-spacing:.09em;
		text-transform:uppercase;
		color:#7FA69A;
	}
	.mutu-card-head h6{
		margin:0;
		font-weight:700;
		font-size:16.5px;
		line-height:1.4;
		color:#F3F6F5;
	}
	.mutu-pulse{
		position:absolute;
		left:0; right:0; bottom:0;
		height:34px;
		opacity:.55;
		z-index:0;
	}
	.mutu-pulse svg{ width:100%; height:100%; display:block; }

	/* target + keterangan strip */
	.mutu-info-bar{
		display:flex;
		align-items:center;
		gap:14px;
		flex-wrap:wrap;
		padding:16px 26px;
		border-bottom:1px solid var(--line);
		background:var(--paper);
	}
	.info-target{
		display:inline-flex;
		align-items:center;
		gap:8px;
		background:var(--gold-soft);
		color:#8A6416;
		font-weight:700;
		font-size:13px;
		padding:7px 14px;
		border-radius:999px;
		white-space:nowrap;
	}
	.info-target span.num{ font-family:var(--font-mono); }
	.info-ket{
		font-size:12.5px;
		color:var(--muted);
		line-height:1.5;
	}
	.info-ket b{ color:var(--ink); font-weight:600; }

	.mutu-flash:not(:empty){
		margin:14px 26px 0;
		background:var(--gold-soft);
		border:1px solid #E7CE9A;
		color:#8A6416;
		font-size:13px;
		padding:9px 14px;
		border-radius:9px;
		text-align:center;
	}

	/* ---------- FORM ---------- */
	.mutu-form{ padding:22px 26px 26px; }

	/* Tanggal berdiri sendiri di baris atas, ringkas dan tidak melar */
	.form-row-top{
		display:flex;
		gap:16px;
		flex-wrap:wrap;
		margin-bottom:16px;
	}
	.field-date{ width:220px; max-width:100%; }

	/* Numerator & Denumerator masing-masing baris penuh, karena keterangan bisa panjang */
	.form-field{ margin-bottom:16px; }
	.form-field:last-of-type{ margin-bottom:18px; }

	.field-label{
		display:flex;
		align-items:baseline;
		flex-wrap:wrap;
		gap:6px;
		margin-bottom:7px;
	}
	.field-name{
		flex:none;
		font-family:var(--font-mono);
		font-size:11px;
		letter-spacing:.05em;
		text-transform:uppercase;
		color:var(--accent-dark);
		background:var(--accent-soft);
		padding:3px 8px;
		border-radius:6px;
	}
	.field-desc{
		flex:1 1 320px;
		font-size:12.5px;
		line-height:1.5;
		color:var(--muted);
	}
	.form-field input[type="date"],
	.form-field input[type="text"]{
		width:100%;
		border:1px solid var(--line);
		background:var(--paper);
		border-radius:9px;
		padding:10px 12px;
		font-size:13.5px;
		color:var(--ink);
		outline:none;
		transition:border-color .15s, box-shadow .15s, background .15s;
	}
	.form-field input:focus{
		border-color:var(--accent);
		box-shadow:0 0 0 3px var(--accent-soft);
		background:#fff;
	}

	.form-actions{
		display:flex;
		gap:10px;
		flex-wrap:wrap;
	}
	.btn-action{
		display:inline-flex;
		align-items:center;
		gap:7px;
		font-size:13px;
		font-weight:600;
		text-decoration:none;
		padding:10px 16px;
		border-radius:8px;
		border:1px solid transparent;
		cursor:pointer;
		transition:background .15s, border-color .15s, color .15s;
	}
	.btn-primary-solid{ background:var(--accent); color:#fff; border:none; }
	.btn-primary-solid:hover{ background:var(--accent-dark); color:#fff; }
	.btn-outline{ background:#fff; color:var(--ink); border-color:var(--line); }
	.btn-outline:hover{ border-color:var(--ink); background:var(--paper); color:var(--ink); }
	.btn-ghost-gold{ background:var(--gold-soft); color:#8A6416; border-color:#E7CE9A; }
	.btn-ghost-gold:hover{ background:#F1E1BC; color:#8A6416; }

	/* ---------- TOOLBAR / SEARCH ---------- */
	.mutu-toolbar{
		display:flex;
		align-items:center;
		justify-content:flex-end;
		padding:18px 26px 0;
	}
	.mutu-search .input-group{
		display:flex;
		align-items:center;
		background:var(--paper);
		border:1px solid var(--line);
		border-radius:9px;
		padding:2px;
		transition:border-color .15s, box-shadow .15s;
	}
	.mutu-search .input-group:focus-within{
		border-color:var(--accent);
		box-shadow:0 0 0 3px var(--accent-soft);
		background:#fff;
	}
	.mutu-search input[type="text"]{
		border:none;
		background:transparent;
		outline:none;
		padding:9px 12px;
		font-size:13.5px;
		width:220px;
		max-width:100%;
		color:var(--ink);
	}
	.mutu-search .btn-reset{
		border:none;
		background:transparent;
		color:var(--muted);
		font-size:12.5px;
		padding:8px 10px;
		text-decoration:none;
		white-space:nowrap;
	}
	.mutu-search .btn-reset:hover{ color:var(--danger); }
	.mutu-search .btn-cari{
		border:none;
		background:var(--accent);
		color:#fff;
		font-weight:600;
		font-size:13px;
		padding:9px 16px;
		border-radius:7px;
		cursor:pointer;
		white-space:nowrap;
	}
	.mutu-search .btn-cari:hover{ background:var(--accent-dark); }

	/* ---------- TABLE ---------- */
	.mutu-table-wrap{ padding:18px 26px 26px; overflow-x:auto; }
	table.mutu-table{
		width:100%;
		border-collapse:separate;
		border-spacing:0;
		font-size:13.5px;
	}
	table.mutu-table thead th{
		text-align:left;
		font-family:var(--font-mono);
		font-size:10.5px;
		letter-spacing:.08em;
		text-transform:uppercase;
		color:var(--muted);
		font-weight:500;
		padding:0 14px 10px;
		border-bottom:1px solid var(--line);
	}
	table.mutu-table tbody td{
		padding:13px 14px;
		border-bottom:1px solid var(--line);
		vertical-align:middle;
		color:var(--ink);
	}
	table.mutu-table tbody tr:last-child td{ border-bottom:none; }
	table.mutu-table tbody tr{ transition:background .12s; }
	table.mutu-table tbody tr:hover{ background:var(--paper); }
	.col-no{ width:44px; color:var(--muted); font-family:var(--font-mono); font-size:12.5px; }
	.txt-mono{ font-family:var(--font-mono); color:var(--muted); font-size:12.5px; }

	.badge-capaian{
		display:inline-flex;
		align-items:center;
		gap:6px;
		font-family:var(--font-mono);
		font-weight:600;
		font-size:12.5px;
		padding:5px 10px;
		border-radius:8px;
	}
	.badge-capaian.ok{ background:var(--accent-soft); color:var(--accent-dark); }
	.badge-capaian.low{ background:var(--danger-soft); color:var(--danger); }
	.badge-capaian .dot{ width:6px; height:6px; border-radius:50%; background:currentColor; flex:none; }

	.pill-target-ref{
		font-family:var(--font-mono);
		font-size:12.5px;
		color:var(--muted);
	}

	.action-group{ display:flex; gap:8px; justify-content:center; flex-wrap:wrap; }
	.link-update{
		display:inline-flex; align-items:center; gap:5px;
		font-size:12.5px; font-weight:600; text-decoration:none;
		padding:6px 11px; border-radius:7px;
		background:#fff; color:var(--ink); border:1px solid var(--line);
	}
	.link-update:hover{ border-color:var(--ink); background:var(--paper); }
	.link-delete{
		display:inline-flex; align-items:center; gap:5px;
		font-size:12.5px; font-weight:600; text-decoration:none;
		padding:6px 11px; border-radius:7px;
		background:var(--danger-soft); color:var(--danger); border:1px solid transparent;
	}
	.link-delete:hover{ background:#F0D2CC; }

	/* ---------- FOOTER ---------- */
	.mutu-footer{
		display:flex;
		align-items:center;
		justify-content:space-between;
		gap:14px;
		flex-wrap:wrap;
		padding:16px 26px 22px;
		border-top:1px solid var(--line);
	}
	.mutu-footer-left{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
	.chip-total{
		display:inline-flex; align-items:center; gap:7px;
		background:var(--ink); color:#EAF3EF;
		font-size:12.5px; font-weight:600;
		padding:8px 14px; border-radius:999px; text-decoration:none;
	}
	.chip-total span.n{ font-family:var(--font-mono); color:#7FC4AE; }
	.btn-excel{
		display:inline-flex; align-items:center; gap:7px;
		background:#1E8E5A; color:#fff;
		font-size:12.5px; font-weight:600;
		padding:8px 14px; border-radius:999px; text-decoration:none;
	}
	.btn-excel:hover{ background:#166E45; color:#fff; }

	.mutu-pagination :where(a, span){
		display:inline-flex; align-items:center; justify-content:center;
		min-width:30px; height:30px; padding:0 8px; margin:0 2px;
		border-radius:7px; font-size:12.5px; text-decoration:none; color:var(--ink);
	}
	.mutu-pagination a:hover{ background:var(--accent-soft); color:var(--accent-dark); }
	.mutu-pagination span{ background:var(--accent); color:#fff; font-weight:600; }
	.mutu-pagination ul{ list-style:none; display:flex; margin:0; padding:0; }

	/* ---------- RESPONSIVE ---------- */
	@media (max-width: 760px){
		.form-grid{ grid-template-columns:1fr; }
		.mutu-toolbar{ justify-content:stretch; }
		.mutu-search{ width:100%; }
		.mutu-search .input-group{ flex-wrap:wrap; }
		.mutu-search input[type="text"]{ width:100%; }

		table.mutu-table thead{ display:none; }
		table.mutu-table, table.mutu-table tbody, table.mutu-table tr, table.mutu-table td{
			display:block; width:100%;
		}
		table.mutu-table tr{
			border:1px solid var(--line);
			border-radius:12px;
			margin-bottom:12px;
			padding:6px 4px;
		}
		table.mutu-table tbody tr:hover{ background:transparent; }
		table.mutu-table td{
			border-bottom:none;
			display:flex;
			align-items:center;
			justify-content:space-between;
			gap:12px;
			padding:8px 12px;
		}
		table.mutu-table td::before{
			content:attr(data-label);
			font-family:var(--font-mono);
			font-size:10.5px;
			letter-spacing:.06em;
			text-transform:uppercase;
			color:var(--muted);
			flex:none;
		}
		.col-no{ display:none; }
		.action-group{ justify-content:flex-end; }
		.mutu-footer{ flex-direction:column; align-items:stretch; }
		.mutu-footer-left{ justify-content:space-between; }
	}
</style>

	<!-- ===== CARD: FORM INPUT ===== -->
	<div class="mutu-card">

		<div class="mutu-card-head">
			<div class="mutu-head-row">
				<p class="eyebrow">Data Mutu Indikator</p>
				<h6><?php echo $this->input->get('judul'); ?></h6>
			</div>
			<div class="mutu-pulse">
				<svg viewBox="0 0 600 34" preserveAspectRatio="none">
					<polyline points="0,17 140,17 160,4 180,30 200,17 340,17 360,6 380,28 400,17 600,17"
						fill="none" stroke="#3E8B79" stroke-width="1.6" />
				</svg>
			</div>
		</div>

		<div class="mutu-info-bar">
			<span class="info-target">Target Mutu <span class="num">&nbsp;<?php echo $target; ?>%</span></span>
			<span class="info-ket"><b>Keterangan Indikator:</b> <?php echo $ketjudul; ?></span>
		</div>

		<div class="mutu-flash" id="message">
			<?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
		</div>

		<form action="<?php echo $action; ?>" method="post" class="mutu-form">
			<div class="form-grid">
				<div class="form-field">
					<label for="tanggal">Tanggal</label>
					<input type="date" name="tanggal" id="tanggal" value="<?php echo $tanggal; ?>" required/>
					<?php echo form_error('tanggal') ?>
				</div>
				<div class="form-field">
					<label for="num"><b>Numerator</b> &nbsp;=&nbsp; <?php echo $ketnum; ?></label>
					<input type="text" name="num" id="num" placeholder="Num" value="<?php echo $num; ?>" required/>
					<?php echo form_error('num') ?>
				</div>
				<div class="form-field">
					<label for="demu"><b>Denumerator</b> &nbsp;=&nbsp; <?php echo $ketdenum; ?></label>
					<input type="text" name="demu" id="demu" placeholder="Denum" value="<?php echo $demu; ?>" required/>
					<?php echo form_error('demu') ?>
				</div>
			</div>

			<input type="hidden" name="id_indikator" id="id_indikator" value="<?php echo $this->input->get('id'); ?>" />
			<input type="hidden" name="judul" id="judul" value="<?php echo $this->input->get('judul'); ?>" />
			<input type="hidden" name="target" id="target" value="<?php echo $target; ?>" required/>
			<input type="hidden" name="id_mutu" value="<?php echo $id_mutu; ?>" />

			<div class="form-actions">
				<button type="submit" class="btn-action btn-primary-solid"><i class="fa fa-save"></i> <?php echo $button ?></button>
				<a class="btn-action btn-outline" href="<?php echo site_url('list_indikator') ?>"><i class="fa fa-backward"></i> Kembali</a>
				<a class="btn-action btn-ghost-gold" href="<?php echo site_url('dashboard/mutugrafik?id='.$this->input->get('id').'&judul='.$this->input->get('judul').'&target='.$target) ?>" target="blank"><i class="fa fa-bar-chart"></i> Laporan Grafik</a>
				<a class="btn-action btn-ghost-gold" href="<?php echo site_url('mutu_fmea?id='.$this->input->get('id').'&judul='.$this->input->get('judul').'&target='.$target) ?>" target="blank"><i class="fa fa-newspaper-o"></i> Laporan Analisa</a>
			</div>
		</form>
	</div>

	<!-- ===== CARD: RIWAYAT CAPAIAN ===== -->
	<div class="mutu-card">

		<div class="mutu-toolbar">
			<form action="<?php echo site_url('Mutu_indikator/index'); ?>" class="mutu-search" method="get">
				<div class="input-group">
					<input type="text" name="q" value="<?php echo $q; ?>" placeholder="Cari tanggal...">
					<input type="hidden" name="id" value="<?php echo $this->input->get('id'); ?>">
					<input type="hidden" name="judul" value="<?php echo $this->input->get('judul'); ?>" />
					<?php if ($q <> '') { ?>
						<a href="<?php echo site_url('Mutu_indikator'); ?>" class="btn-reset">Reset</a>
					<?php } ?>
					<button class="btn-cari" type="submit">Cari</button>
				</div>
			</form>
		</div>

		<div class="mutu-table-wrap">
			<table class="mutu-table">
				<thead>
					<tr>
						<th class="col-no">No</th>
						<th>Tanggal</th>
						<th>Numerator</th>
						<th>Denumerator</th>
						<th>Capaian</th>
						<th>Target</th>
						<th style="text-align:center">Aksi</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($mutu_indikator_data as $mutu_indikator)
				{
					$capaian = 0;
					if ($mutu_indikator->num > 0) {

						if ($jenis<>"PPI" and $judul<>"Kepuasan Pasien"){
							$capaian = round($mutu_indikator->num/$mutu_indikator->demu*100,1);
						}
						if ($judul=="Kepuasan Pasien"){
							$capaian = round($mutu_indikator->num*25,2);
						}
						if($jenis=="PPI"){
							$capaian = round($mutu_indikator->num/$mutu_indikator->demu*1000,1);
						}
					}
					$capaian_class = ($capaian >= $target) ? 'ok' : 'low';
					?>
					<tr>
						<td class="col-no"><?php echo ++$start ?></td>
						<td data-label="Tanggal" class="txt-mono"><?php echo $mutu_indikator->tanggal ?></td>
						<td data-label="Numerator"><?php echo $mutu_indikator->num ?></td>
						<td data-label="Denumerator"><?php echo $mutu_indikator->demu ?></td>
						<td data-label="Capaian">
							<span class="badge-capaian <?php echo $capaian_class ?>"><span class="dot"></span><?php echo $capaian ?><?php echo ($jenis=="PPI") ? '&permil;' : '%'; ?></span>
						</td>
						<td data-label="Target"><span class="pill-target-ref"><?php echo $mutu_indikator->target ?>%</span></td>
						<td data-label="Aksi">
							<div class="action-group">
								<?php
								echo anchor(site_url('mutu_indikator/update?id='.$this->input->get('id').'&judul='.$this->input->get('judul').'&target='.$target.'&idmutu='.$mutu_indikator->id_mutu), '<i class="fa fa-edit"></i> Update', 'class="link-update"');
								echo anchor(site_url('mutu_indikator/delete?id='.$this->input->get('id').'&judul='.$this->input->get('judul').'&target='.$target.'&idmutu='.$mutu_indikator->id_mutu), '<i class="fa fa-trash"></i> Delete', 'class="link-delete" onclick="javascript: return confirm(\'Are You Sure ?\')"');
								?>
							</div>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>

		<div class="mutu-footer">
			<div class="mutu-footer-left">
				<a href="#" class="chip-total">Total Record <span class="n">&nbsp;<?php echo $total_rows ?></span></a>
				<?php echo anchor(site_url('mutu_indikator/excel'), '<i class="fa fa-file-excel-o"></i> Excel', 'class="btn-excel"'); ?>
			</div>
			<div class="mutu-pagination">
				<?php echo $pagination ?>
			</div>
		</div>

	</div>

</div>