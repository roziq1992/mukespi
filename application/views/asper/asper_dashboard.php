<?php
// =============================================
// File: application/views/asper/asper_dashboard.php
// =============================================
?>
<style>
:root{
    --p:#0d6efd; --p2:#4f8cff; --pl:rgba(13,110,253,.1);
    --g:#198754; --y:#f59e0b; --r:#e5484d; --v:#7c3aed;
    --tl:#0891b2; --or:#ea580c;
    --bg:#f4f6fb; --card:#fff; --bdr:#e2e6ee;
    --tx:#1f2430; --mu:#6b7280; --rad:14px;
}
*{box-sizing:border-box;margin:0;padding:0;}
body{background:var(--bg);}

.db{background:var(--bg);padding:14px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;color:var(--tx);max-width:960px;margin:0 auto;min-height:100vh;}

/* HEADER */
.db-hd{background:linear-gradient(135deg,var(--p),var(--p2));border-radius:var(--rad);padding:18px 16px 14px;color:#fff;margin-bottom:14px;box-shadow:0 6px 18px rgba(13,110,253,.22);}
.db-hd-row{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;margin-bottom:10px;}
.db-hd h1{font-size:1.05rem;font-weight:700;margin-bottom:2px;}
.db-hd p{font-size:.75rem;opacity:.88;}
.db-btn-sm{display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.2);color:#fff;text-decoration:none;padding:6px 12px;border-radius:8px;font-size:.78rem;font-weight:600;border:none;cursor:pointer;font-family:inherit;}
.db-btn-sm:hover{background:rgba(255,255,255,.3);color:#fff;}

/* FILTER */
.db-filter{background:var(--card);border:1px solid var(--bdr);border-radius:var(--rad);padding:12px 14px;margin-bottom:14px;display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap;}
.db-filter label{font-size:.72rem;font-weight:700;display:block;margin-bottom:4px;color:var(--mu);text-transform:uppercase;letter-spacing:.03em;}
.db-filter select,.db-filter input{padding:8px 12px;border:1.5px solid var(--bdr);border-radius:8px;font-size:.88rem;color:var(--tx);background:#fbfcfe;font-family:inherit;appearance:none;-webkit-appearance:none;}
.db-filter select:focus,.db-filter input:focus{outline:none;border-color:var(--p);}
.db-filter button{padding:8px 18px;background:var(--p);color:#fff;border:none;border-radius:8px;font-size:.88rem;font-weight:700;cursor:pointer;font-family:inherit;}
.db-filter button:active{opacity:.85;}

/* STAT CARDS */
.db-stats{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:14px;}
@media(min-width:540px){.db-stats{grid-template-columns:repeat(4,1fr);}}
.db-stat{background:var(--card);border:1px solid var(--bdr);border-radius:var(--rad);padding:14px 10px;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.04);}
.db-stat-ico{font-size:1.6rem;margin-bottom:5px;}
.db-stat-val{font-size:1.7rem;font-weight:800;line-height:1;margin-bottom:4px;}
.db-stat-lbl{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--mu);}
.c-blue .db-stat-val{color:var(--p);}
.c-green .db-stat-val{color:var(--g);}
.c-yellow .db-stat-val{color:var(--y);}
.c-red .db-stat-val{color:var(--r);}

/* SHIFT MINI BADGE */
.shift-row{display:flex;gap:8px;margin-top:10px;justify-content:center;flex-wrap:wrap;}
.shift-badge{background:rgba(255,255,255,.15);border-radius:8px;padding:5px 10px;font-size:.72rem;font-weight:700;color:#fff;text-align:center;}
.shift-badge span{display:block;font-size:.95rem;font-weight:800;}

/* CARD */
.db-card{background:var(--card);border:1px solid var(--bdr);border-radius:var(--rad);padding:16px;margin-bottom:14px;box-shadow:0 2px 8px rgba(0,0,0,.04);}
.db-card-title{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--p);margin-bottom:14px;display:flex;align-items:center;gap:7px;}
.db-card-title::before{content:'';display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--p);flex-shrink:0;}

/* GRID */
.db-g2{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;}
@media(max-width:540px){.db-g2{grid-template-columns:1fr;}}

/* CHART */
.ch-wrap{position:relative;width:100%;}
.ch-wrap canvas{display:block;width:100%!important;}

/* TABLE */
.db-tbl{width:100%;border-collapse:collapse;font-size:.82rem;}
.db-tbl th{background:var(--pl);color:var(--p);font-size:.68rem;text-transform:uppercase;letter-spacing:.04em;padding:8px 10px;text-align:left;}
.db-tbl td{padding:8px 10px;border-bottom:1px solid var(--bdr);color:var(--tx);}
.db-tbl tr:last-child td{border-bottom:none;}
.db-tbl tr:hover td{background:#fafbff;}
.bar-bg{background:#e9ecef;border-radius:99px;height:8px;overflow:hidden;}
.bar-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,var(--p),var(--p2));}

/* LEGEND */
.leg-item{display:flex;align-items:center;gap:8px;margin-bottom:8px;font-size:.8rem;}
.leg-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
.leg-pct{margin-left:auto;color:var(--mu);font-size:.75rem;}

/* EMPTY */
.db-empty{text-align:center;padding:28px 16px;color:var(--mu);font-size:.84rem;}
.db-empty-ico{font-size:2.2rem;margin-bottom:8px;}

/* BADGE RUANG */
.ruang-badge{display:inline-block;padding:2px 7px;border-radius:6px;font-size:.72rem;font-weight:700;background:var(--pl);color:var(--p);}
</style>

<?php
// ---- helper bulan ----
$nama_bulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

// ---- hitung total ----
$total_pasien   = array_sum(array_column($ruang, 'total'));
$total_kamar    = array_sum($zona);
$total_verbed   = array_sum($verbed);
?>

<div class="db">

    <!-- HEADER -->
    <div class="db-hd">
        <div class="db-hd-row">
            <a href="<?php echo site_url('asper') ?>" class="db-btn-sm">&#8592; Daftar</a>
            <a href="<?php echo site_url('asper/create') ?>" class="db-btn-sm">&#43; Input Baru</a>
        </div>
        <h1>&#128202; Dashboard Serah Terima Asper</h1>
        <p>Rekap Bulan: <strong><?php echo $nama_bulan[(int)$bulan] . ' ' . $tahun; ?></strong></p>
        <div class="shift-row">
            <div class="shift-badge">Pagi<span><?php echo $stats['shifts']['Pagi']; ?></span></div>
            <div class="shift-badge">Siang<span><?php echo $stats['shifts']['Siang']; ?></span></div>
            <div class="shift-badge">Malam<span><?php echo $stats['shifts']['Malam']; ?></span></div>
        </div>
    </div>

    <!-- FILTER -->
    <form class="db-filter" method="get" action="<?php echo site_url('asper/dashboard') ?>">
        <div>
            <label>Bulan</label>
            <select name="bulan">
                <?php for($i=1;$i<=12;$i++): ?>
                <option value="<?php echo $i ?>" <?php if((int)$bulan===$i) echo 'selected'; ?>><?php echo $nama_bulan[$i]; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label>Tahun</label>
            <input type="number" name="tahun" value="<?php echo $tahun ?>" min="2020" max="2099" style="width:86px;">
        </div>
        <button type="submit">Tampilkan</button>
    </form>

    <!-- STAT CARDS -->
    <div class="db-stats">
        <div class="db-stat c-blue">
            <div class="db-stat-ico">&#128203;</div>
            <div class="db-stat-val"><?php echo $stats['total']; ?></div>
            <div class="db-stat-lbl">Total Entri</div>
        </div>
        <div class="db-stat c-green">
            <div class="db-stat-ico">&#128101;</div>
            <div class="db-stat-val"><?php echo $total_pasien; ?></div>
            <div class="db-stat-lbl">Total Pasien</div>
        </div>
        <div class="db-stat c-yellow">
            <div class="db-stat-ico">&#128682;</div>
            <div class="db-stat-val"><?php echo $total_kamar; ?></div>
            <div class="db-stat-lbl">MRS / KRS</div>
        </div>
        <div class="db-stat c-red">
            <div class="db-stat-ico">&#128260;</div>
            <div class="db-stat-val"><?php echo $total_verbed; ?></div>
            <div class="db-stat-lbl">Verbed</div>
        </div>
    </div>

    <!-- ROW 1: Line Harian + Donut Shift -->
    <div class="db-g2">
        <div class="db-card">
            <p class="db-card-title">Tren Pasien Harian</p>
            <?php if(!empty($harian)): ?>
            <div class="ch-wrap" style="height:175px;"><canvas id="chHarian"></canvas></div>
            <?php else: ?>
            <div class="db-empty"><div class="db-empty-ico">&#128197;</div>Belum ada data</div>
            <?php endif; ?>
        </div>
        <div class="db-card">
            <p class="db-card-title">Distribusi Shift</p>
            <?php if(!empty($shift)): ?>
            <div class="ch-wrap" style="height:175px;"><canvas id="chShift"></canvas></div>
            <?php else: ?>
            <div class="db-empty"><div class="db-empty-ico">&#128336;</div>Belum ada data</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ROW 2: Bar Zona Kamar + Bar Zona Verbed -->
    <div class="db-g2">
        <div class="db-card">
            <p class="db-card-title">MRS / KRS per Zona</p>
            <div class="ch-wrap" style="height:190px;"><canvas id="chZonaKamar"></canvas></div>
        </div>
        <div class="db-card">
            <p class="db-card-title">Verbed per Zona</p>
            <div class="ch-wrap" style="height:190px;"><canvas id="chZonaVerbed"></canvas></div>
        </div>
    </div>

    <!-- ROW 3: Donut Unit + Pasien per Ruang bar -->
    <div class="db-g2">
        <!-- Donut unit -->
        <div class="db-card">
            <p class="db-card-title">Entri per Unit / Divisi</p>
            <?php if(!empty($unit)): ?>
            <div style="display:flex;gap:14px;align-items:center;flex-wrap:wrap;">
                <div style="flex:0 0 140px;">
                    <canvas id="chUnit" style="width:140px!important;height:140px!important;"></canvas>
                </div>
                <div style="flex:1;min-width:100px;" id="unitLeg"></div>
            </div>
            <?php else: ?>
            <div class="db-empty"><div class="db-empty-ico">&#127973;</div>Belum ada data</div>
            <?php endif; ?>
        </div>

        <!-- Bar pasien per ruang -->
        <div class="db-card">
            <p class="db-card-title">Rata-rata Pasien per Ruang</p>
            <?php if(!empty($ruang)): ?>
            <div class="ch-wrap" style="height:190px;"><canvas id="chRuang"></canvas></div>
            <?php else: ?>
            <div class="db-empty"><div class="db-empty-ico">&#128202;</div>Belum ada data</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ROW 4: Tabel detail pasien per ruang -->
    <?php if(!empty($ruang)): ?>
    <?php $max_total = max(array_column($ruang,'total')); ?>
    <div class="db-card">
        <p class="db-card-title">Detail Total &amp; Rata-rata Pasien per Ruang</p>
        <table class="db-tbl">
            <thead>
                <tr>
                    <th>Ruang</th>
                    <th>Total</th>
                    <th>Rata-rata/entri</th>
                    <th style="width:35%">Proporsi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ruang as $r): ?>
                <tr>
                    <td><span class="ruang-badge"><?php echo htmlspecialchars($r['ruang']); ?></span></td>
                    <td><strong><?php echo $r['total']; ?></strong></td>
                    <td><?php echo $r['rata']; ?></td>
                    <td>
                        <div class="bar-bg">
                            <div class="bar-fill" style="width:<?php echo $max_total > 0 ? round($r['total']/$max_total*100) : 0; ?>%"></div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family='-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif';
Chart.defaults.font.size=11;
Chart.defaults.color='#6b7280';

const C={
    blue:'#0d6efd',green:'#198754',yellow:'#f59e0b',
    red:'#e5484d',purple:'#7c3aed',teal:'#0891b2',
    orange:'#ea580c',pink:'#db2777',indigo:'#4338ca'
};
const CL=Object.values(C);

// ---- 1. LINE: Tren Harian ----
<?php if(!empty($harian)): ?>
new Chart(document.getElementById('chHarian'),{
    type:'line',
    data:{
        labels:<?php echo json_encode(array_column($harian,'tgl')); ?>,
        datasets:[{
            label:'Rata Pasien',
            data:<?php echo json_encode(array_column($harian,'rata')); ?>,
            borderColor:C.blue,
            backgroundColor:'rgba(13,110,253,.08)',
            fill:true,tension:.35,
            pointBackgroundColor:C.blue,
            pointRadius:3,pointHoverRadius:5,borderWidth:2
        }]
    },
    options:{
        responsive:true,maintainAspectRatio:false,
        plugins:{legend:{display:false}},
        scales:{
            x:{grid:{display:false},ticks:{maxTicksLimit:10}},
            y:{beginAtZero:true,grid:{color:'#f0f0f0'}}
        }
    }
});
<?php endif; ?>

// ---- 2. DONUT: Shift ----
<?php if(!empty($shift)): ?>
new Chart(document.getElementById('chShift'),{
    type:'doughnut',
    data:{
        labels:<?php echo json_encode(array_column($shift,'shift')); ?>,
        datasets:[{
            data:<?php echo json_encode(array_map(function($r){return (int)$r->jml;},$shift)); ?>,
            backgroundColor:[C.yellow,C.blue,C.indigo],
            borderWidth:2,borderColor:'#fff',hoverOffset:6
        }]
    },
    options:{
        responsive:true,maintainAspectRatio:false,cutout:'62%',
        plugins:{legend:{position:'bottom',labels:{padding:10,boxWidth:10}}}
    }
});
<?php endif; ?>

// ---- 3. BAR: Zona Kamar ----
new Chart(document.getElementById('chZonaKamar'),{
    type:'bar',
    data:{
        labels:['Zona A','Zona B','Zona C','Zona D','Zona E'],
        datasets:[{
            label:'MRS/KRS',
            data:<?php echo json_encode(array_values($zona)); ?>,
            backgroundColor:['rgba(13,110,253,.75)','rgba(25,135,84,.75)','rgba(245,158,11,.75)','rgba(124,58,237,.75)','rgba(229,72,77,.75)'],
            borderRadius:6,borderSkipped:false
        }]
    },
    options:{
        responsive:true,maintainAspectRatio:false,
        plugins:{legend:{display:false}},
        scales:{x:{grid:{display:false}},y:{beginAtZero:true,grid:{color:'#f0f0f0'}}}
    }
});

// ---- 4. BAR: Zona Verbed ----
new Chart(document.getElementById('chZonaVerbed'),{
    type:'bar',
    data:{
        labels:['Zona A','Zona B','Zona C','Zona D','Zona E'],
        datasets:[{
            label:'Verbed',
            data:<?php echo json_encode(array_values($verbed)); ?>,
            backgroundColor:['rgba(8,145,178,.75)','rgba(234,88,12,.75)','rgba(219,39,119,.75)','rgba(67,56,202,.75)','rgba(16,185,129,.75)'],
            borderRadius:6,borderSkipped:false
        }]
    },
    options:{
        responsive:true,maintainAspectRatio:false,
        plugins:{legend:{display:false}},
        scales:{x:{grid:{display:false}},y:{beginAtZero:true,grid:{color:'#f0f0f0'}}}
    }
});

// ---- 5. DONUT: Unit/Divisi ----
<?php if(!empty($unit)): ?>
(function(){
    const uLabels=<?php echo json_encode(array_column($unit,'unit_divisi')); ?>;
    const uVals=<?php echo json_encode(array_map(function($r){return (int)$r->jml;},$unit)); ?>;
    const bg=CL.slice(0,uLabels.length);
    new Chart(document.getElementById('chUnit'),{
        type:'doughnut',
        data:{labels:uLabels,datasets:[{data:uVals,backgroundColor:bg,borderWidth:2,borderColor:'#fff',hoverOffset:5}]},
        options:{responsive:false,cutout:'60%',plugins:{legend:{display:false}}}
    });
    const tot=uVals.reduce((a,b)=>a+b,0);
    document.getElementById('unitLeg').innerHTML=uLabels.map((l,i)=>
        `<div class="leg-item"><span class="leg-dot" style="background:${bg[i]}"></span><span>${l}</span><span class="leg-pct">${uVals[i]} (${tot>0?Math.round(uVals[i]/tot*100):0}%)</span></div>`
    ).join('');
})();
<?php endif; ?>

// ---- 6. BAR HORIZONTAL: Rata Pasien per Ruang ----
<?php if(!empty($ruang)): ?>
new Chart(document.getElementById('chRuang'),{
    type:'bar',
    data:{
        labels:<?php echo json_encode(array_column($ruang,'ruang')); ?>,
        datasets:[{
            label:'Rata-rata',
            data:<?php echo json_encode(array_column($ruang,'rata')); ?>,
            backgroundColor:'rgba(13,110,253,.7)',
            borderRadius:5,borderSkipped:false
        }]
    },
    options:{
        indexAxis:'y',
        responsive:true,maintainAspectRatio:false,
        plugins:{legend:{display:false}},
        scales:{
            x:{beginAtZero:true,grid:{color:'#f0f0f0'}},
            y:{grid:{display:false}}
        }
    }
});
<?php endif; ?>
</script>