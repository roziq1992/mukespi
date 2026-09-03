 <?php
                                    $start = 0;
                                    $tahun=$this->input->get('tahun');
                                    $bulan=$this->input->get('bulan');
                                    $jml=0;
                                    if ($bulan==""){
                                    $bulan = date("m");
                                    }
                                    if ($tahun==""){
                                    $tahun = date("Y");
                                    }
                                    //  echo  $tahun ;
                                    //  echo  $bulan ;
                                     
                                    $cuci_tangan_data=$this->db->query('SELECT 
                                    cuci_tangan.tanggal,   
                                    SUM(case when cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N,
                                    SUM(case when cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D,
                                    count(cuci_tangan.cucitangan) as jml
                                    from cuci_tangan where MONTH(cuci_tangan.tanggal) = "'.$bulan.'"
                                    and year(cuci_tangan.tanggal) = "'.$tahun.'"
                                    ')->row();
                                    
                                    $cucitangan=$cuci_tangan_data->N;
                                    $tidakcucitangan=$cuci_tangan_data->D;
                                    $jml=$cuci_tangan_data->jml;
                                    $persenN=@(($cucitangan/$jml)*100);
                                    $persenD=@(($tidakcucitangan/$jml)*100);
                                    
                                   
                                    
?>
 
 <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Grafik Cuci Tangan</h1>
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cuci Tangan</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $cucitangan; ?> Orang</div>
                    </div>
                    <div class="col-auto">
                      <i class="far fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tidak Cuci Tangan</div>
                      <div class="h5 mb-0 font-weight-bold text-red-800"><?php echo $tidakcucitangan; ?> Orang</div>
                    </div>
                    <div class="col-auto">
                        
                      <i class="fas fa-calendar-times fa-2x text-red-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-primary mb-1">% CUCI TANGAN</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo number_format($persenN,2) ?>%</div>
                        </div>
                        <div class="col">
                          <div class="progress progress-sm mr-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Requests Card Example -->
             <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-danger mb-1">% TIDAK CUCI TANGAN</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-red-800"><?php echo number_format($persenD,2) ?>%</div>
                        </div>
                        <div class="col">
                          <div class="progress progress-sm mr-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->
          
           <form action="#" method="get">
           <div class="d-sm-flex align-items-center justify-content-between mb-1">
            
            <div class="mb-4">
           <label for="exampleFormControlSelect1">Tahun</label><select class="form-control form-control-solid" id="tahun" name="tahun">
            <option >2022</option>
            <option selected>2023</option>
            <option selected>2024</option>
             <option selected>2025</option>
        </select>
        </div>
        <div class="mb-4">
           <label for="exampleFormControlSelect1">Bulan</label><select class="form-control form-control-solid" id="bulan" name="bulan">
            <option >1</option>
            <option >2</option>
            <option >3</option>
            <option >4</option>
            <option >5</option>
            <option >6</option>
            <option >7</option>
            <option >8</option>
            <option >9</option>
            <option >10</option>
            <option >11</option>
            <option >12</option>
        </select>
        </div>
            <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter fa-sm text-white-50"></i> Apply Filter</a>-->
          <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
              <i class="fas fa-filter fa-sm text-white-50"></i> Apply Filter</a>
            </button>
          </div>
         </form>
        </div>

          <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Grafik Kepatuhan Cuci Tangan</h6>
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
        
        
        
 