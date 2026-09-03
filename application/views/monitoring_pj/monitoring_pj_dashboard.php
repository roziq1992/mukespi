<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="container-fluid mutu-dashboard">
<style>
	:root {
		--mutu-primary: #3b82f6;
		--mutu-primary-hover: #1d4ed8;
		--mutu-dark: #0f172a;
		--mutu-slate: #334155;
		--mutu-muted: #64748b;
		--mutu-border: #e2e8f0;
		--mutu-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.05);
		--mutu-radius: 16px;
		--mutu-font: 'Inter', system-ui, -apple-system, sans-serif;
	}
	.mutu-dashboard { font-family: var(--mutu-font); color: var(--mutu-slate); padding: 1rem 0; }
	.mutu-wrapper { background: #fff; border-radius: var(--mutu-radius); box-shadow: var(--mutu-shadow); border: 1px solid var(--mutu-border); }
	.mutu-header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 28px 32px; border-top-left-radius: var(--mutu-radius); border-top-right-radius: var(--mutu-radius); color: #fff; }
	.mutu-header h5 { margin: 0; font-size: 1.25rem; font-weight: 700; }
	.mutu-header p { margin: 4px 0 0; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 600; }

	.dash-filter { padding: 20px 32px 0; display: flex; gap: 12px; align-items: end; flex-wrap: wrap; }
	.dash-filter .form-group { margin-bottom: 0; }
	.dash-filter label { font-size: 0.75rem; font-weight: 700; color: var(--mutu-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
	.dash-filter select { border: 1px solid var(--mutu-border); border-radius: 8px; padding: 8px 14px; font-size: 0.875rem; }
	.btn-filter { border: none; background: var(--mutu-primary); color: #fff; font-weight: 600; font-size: 0.85rem; padding: 9px 20px; border-radius: 8px; cursor: pointer; }
	.btn-filter:hover { background: var(--mutu-primary-hover); }

	.chart-wrap { padding: 24px 32px 32px; }
	.chart-box { background: #fafafa; border: 1px solid var(--mutu-border); border-radius: 12px; padding: 24px; }
	.mutu-empty-state { text-align: center; padding: 56px 20px; color: var(--mutu-muted); }
</style>

	<div class="mutu-wrapper">
		<div class="mutu-header">
			<h5>Dashboard Progres Aplikasi</h5>
			<p>Monitoring PJ Aplikasi per Periode</p>
		</div>

		<form action="<?php echo site_url('Monitoring_pj/dashboard'); ?>" method="get" class="dash-filter">
			<div class="form-group">
				<label>Bulan</label><br>
				<select name="bulan">
					<?php foreach ($bulan_list as $b) { ?>
						<option value="<?php echo $b; ?>" <?php echo ($bulan_selected == $b) ? 'selected' : ''; ?>><?php echo $b; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label>Tahun</label><br>
				<select name="tahun">
					<?php foreach ($tahun_list as $t) { ?>
						<option value="<?php echo $t->tahun; ?>" <?php echo ($tahun_selected == $t->tahun) ? 'selected' : ''; ?>><?php echo $t->tahun; ?></option>
					<?php } ?>
					<?php if (!in_array($tahun_selected, array_column($tahun_list, 'tahun'))) { ?>
						<option value="<?php echo $tahun_selected; ?>" selected><?php echo $tahun_selected; ?></option>
					<?php } ?>
				</select>
			</div>
			<button type="submit" class="btn-filter"><i class="fa fa-filter mr-1"></i> Terapkan</button>
		</form>

		<div class="chart-wrap">
			<?php if (count($chart_data) > 0) { ?>
			<div class="chart-box">
				<canvas id="chartProgres" height="90"></canvas>
			</div>
			<?php } else { ?>
			<div class="mutu-empty-state">
				<p>Belum ada data progres untuk periode <?php echo $bulan_selected . ' ' . $tahun_selected; ?>.</p>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php if (count($chart_data) > 0) { ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartProgres').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Progres (%) - <?php echo $bulan_selected . " " . $tahun_selected; ?>',
                data: <?php echo json_encode($progres); ?>,
                backgroundColor: '#3b82f6',
                borderRadius: 6,
                maxBarThickness: 48
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: function(v) { return v + '%'; } }
                },
                x: {
                    ticks: { autoSkip: false, maxRotation: 30, minRotation: 0 }
                }
            }
        }
    });
});
</script>
<?php } ?>