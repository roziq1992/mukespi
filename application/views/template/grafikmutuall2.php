 <?php
                                    $start = 0;
                                    $tanggal1=$this->input->get('tanggal');
                                     $idindikator=$this->input->get('mutu');
                                    $tanggal2=$this->input->get('tanggal2');
                                    $jml=0;
                                    if ($tanggal1==""){
                                    $tanggal1 = date("Y-m-d");
                                    $tanggal2 = date("Y-m-d");
                                    }
                                    if ($tanggal1==""){
                                    $tanggal1 = date("Y-m-d");
                                    $tanggal2 = date("Y-m-d");
                                    }
                                    //  echo  $tahun ;
                                    //  echo  $bulan ;
                                     
                                  $mutuindikator=$this->db->query('SELECT 
                                        list_indikator.judul as judul,
                                        SUM( mutu_indikator.num ) AS num,
                                        sum( mutu_indikator.demu ) AS demu,
                                        CASE
                                        WHEN list_indikator.jenis <> "PPI" THEN
                                        SUM( mutu_indikator.num )/ SUM( mutu_indikator.demu )* 100 ELSE SUM( mutu_indikator.num )/ SUM( mutu_indikator.demu )* 1000 
                                        END AS capaian,
                                        mutu_indikator.target 
                                        FROM
                                        	mutu_indikator
                                        	INNER JOIN list_indikator ON mutu_indikator.id_indikator = list_indikator.id_indikator 
                                        WHERE  mutu_indikator.tanggal  between  "'.$tanggal1.'"  and "'.$tanggal2.'"
                                        GROUP BY
                                        list_indikator.judul
                                        ORDER BY
                                        MONTH ( mutu_indikator.tanggal ) ASC')->result();
                                	$tnum=0;
                                	$tdenum=0;
                                	$rcapaian=0;
                                	$no=0;
                                	foreach ($mutuindikator as $data) {
                                	   $tnum=$data->num+$tnum;
                                       $tdenum=$data->demu+$tdenum;
                                       $rcapaian=$data->capaian+$rcapaian;
                                       $no++;
                                    } 
                                    if($rcapaian>0){
                                    $rata2=$rcapaian/$no;
                                    }else{
                                        $rata2=0;
                                    }
                                   
                                    
?>
 
 <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><?php echo $this->input->get('judul'); ?></h1>
            <?php echo anchor(site_url('Mutu_indikator?id='.$this->input->get('id').'&judul='.$this->input->get('judul')),'Kembali', 'class="btn btn-primary"'); ?>
          </div>
       <form action="#" method="get">
           <div class="d-sm-flex align-items-center justify-content-between mb-1">
            <!--<div class="mb-4">-->
         <!--   <label for="varchar">Mutu </label>-->
         <!--   <select class="form-control form-control-solid" id="mutu" name="mutu">-->
         <!--<?php  foreach ($mutu as $mutu){ ?>-->
         <!--  <option  value="<?php echo $mutu->id_indikator ?>"><?php echo $mutu->judul?></option>-->
         <!-- <?php } ?>-->
         <!--   </select>-->
         <!--    </div>-->
            <div class="mb-4">
           <label for="exampleFormControlSelect1">Awal</label>
           <input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $this->input->get('tanggal'); ?>" />
           <input type="hidden" class="form-control" name="id" id="id" placeholder="Id Indikator" value="<?php echo $this->input->get('id'); ?>" />
            <input type="hidden" class="form-control" name="judul" id="judul" placeholder="" value="<?php echo $this->input->get('judul'); ?>" />
            <input type="hidden" class="form-control" name="target" id="target" placeholder="Id Indikator" value="<?php echo $this->input->get('target'); ?>" />
        </select>
        </div>
        <div class="mb-4">
           <label for="exampleFormControlSelect1">Akhir</label>
            <input type="date" class="form-control" name="tanggal2" id="tanggal2" placeholder="Tanggal" value="<?php echo $this->input->get('tanggal2'); ?>" />
        </div>
            <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter fa-sm text-white-50"></i> Apply Filter</a>-->
          <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
              <i class="fas fa-filter fa-sm text-white-50"></i> Apply Filter</a>
            </button>
          </div>
         </form>
          <!-- Content Row -->
          <div class="row">
            
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Target Indikator</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $this->input->get('target'); ?> %</div>
                    </div>
                    <div class="col-auto">
                      <i class="far fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
             <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Rata Rata Capaian</div>
                      <div class="h5 mb-0 font-weight-bold text-success-800"><?php echo number_format($rata2,2);  ?> %</div>
                    </div>
                    <div class="col-auto">
                      <i class="far fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tot Numerator</div>
                      <div class="h5 mb-0 font-weight-bold text-success-800"><?php echo $tnum; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="far fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tot Denumerator</div>
                      <div class="h5 mb-0 font-weight-bold text-success-800"><?php echo $tdenum; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="far fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <!--<div class="col-xl-3 col-md-6 mb-4">-->
            <!--  <div class="card border-left-success shadow h-100 py-2">-->
            <!--    <div class="card-body">-->
            <!--      <div class="row no-gutters align-items-center">-->
            <!--        <div class="col mr-2">-->
            <!--          <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tidak Cuci Tangan</div>-->
            <!--          <div class="h5 mb-0 font-weight-bold text-red-800"><?php echo $tidakcucitangan; ?> Orang</div>-->
            <!--        </div>-->
            <!--        <div class="col-auto">-->
                        
            <!--          <i class="fas fa-calendar-times fa-2x text-red-300"></i>-->
            <!--        </div>-->
            <!--      </div>-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->

            <!-- Earnings (Monthly) Card Example -->
            <!--<div class="col-xl-3 col-md-6 mb-4">-->
            <!--  <div class="card border-left-info shadow h-100 py-2">-->
            <!--    <div class="card-body">-->
            <!--      <div class="row no-gutters align-items-center">-->
            <!--        <div class="col mr-2">-->
            <!--          <div class="text-xs font-weight-bold text-info text-primary mb-1">% CUCI TANGAN</div>-->
            <!--          <div class="row no-gutters align-items-center">-->
            <!--            <div class="col-auto">-->
            <!--              <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo number_format($persenN,2) ?>%</div>-->
            <!--            </div>-->
            <!--            <div class="col">-->
            <!--              <div class="progress progress-sm mr-2">-->
            <!--                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>-->
            <!--              </div>-->
            <!--            </div>-->
            <!--          </div>-->
            <!--        </div>-->
            <!--        <div class="col-auto">-->
            <!--          <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>-->
            <!--        </div>-->
            <!--      </div>-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->

            <!-- Pending Requests Card Example -->
            <!-- <div class="col-xl-3 col-md-6 mb-4">-->
            <!--  <div class="card border-left-info shadow h-100 py-2">-->
            <!--    <div class="card-body">-->
            <!--      <div class="row no-gutters align-items-center">-->
            <!--        <div class="col mr-2">-->
            <!--          <div class="text-xs font-weight-bold text-info text-danger mb-1">% TIDAK CUCI TANGAN</div>-->
            <!--          <div class="row no-gutters align-items-center">-->
            <!--            <div class="col-auto">-->
            <!--              <div class="h5 mb-0 mr-3 font-weight-bold text-red-800"><?php echo number_format($persenD,2) ?>%</div>-->
            <!--            </div>-->
            <!--            <div class="col">-->
            <!--              <div class="progress progress-sm mr-2">-->
            <!--                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>-->
            <!--              </div>-->
            <!--            </div>-->
            <!--          </div>-->
            <!--        </div>-->
            <!--        <div class="col-auto">-->
            <!--          <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>-->
            <!--        </div>-->
            <!--      </div>-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->
          </div>

          <!-- Content Row -->
          
    
        </div>

          <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Grafik Capaian Per Indikator</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
         
          </div>
        
        
        
 