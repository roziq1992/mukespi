<div class="container-fluid">

    <!-- Page Heading -->
    <!-- <h1 class="h3 mb-2 text-gray-800">Tables</h1>
    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
        For more information about DataTables, please visit the <a target="_blank"
            href="https://datatables.net">official DataTables documentation</a>.</p> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Cuci Tangan Per Moment - per Kesempatan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <!-- <div class="row"> -->
                        <div class="col-sm-12">
                            <div class="row" style="margin-bottom: 10px">
                               
                                
                                <div class="col-md-6 text-right">
                                    <form action="<?php echo site_url('cuci_tangan/lprmoment'); ?>" class="form-inline" method="get">
                                        <div class="input-group">
                                        <input type="date" class="form-control" name="tgl1" id="tgl1" placeholder="Tanggal" value="" /> 
                                       
            <h6 class="m-0 font-weight-bold text-primary"> sd </h6>
        
                                            <input type="date" class="form-control" name="tgl2" id="tgl2" placeholder="Tanggal" value="" />
                                            <span class="input-group-btn">
                                              <button class="btn btn-primary" type="submit">Cari</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Laporan Cuci Tangan Kesempatan dan Profesi</h6>
                            </div>
                            <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0"
                                role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                   
                                    <th colspan="2">Moment</th>
                                     <th>Kesempatan</th>
                                        <th>Tanggal</th>
                                        <th>N</th>
                                        <th>D</th>
                                        <th>Total</th>
                                        <th>Persen</th>
                                       
                                    </tr>
                                </thead>
                              
                                <tbody>
                                    <?php
                                    $start = 1;
                                    $N=0;
                                    $D=0;
                                    $jml=0;
                                    $tgl1=$this->input->get('tgl1');
                                    $tgl2=$this->input->get('tgl2');



                                    $unit=$this->db->query('SELECT 
                                    moment.nm_moment,
                                    cuci_tangan.moment
                                    from cuci_tangan 
                                    inner join profesi on cuci_tangan.profesi=profesi.nm_profesi
                                    inner join moment on  cuci_tangan.moment=moment.id_moment
                                    where cuci_tangan.tanggal between "'.$tgl1.'" and "'.$tgl2.'"
                                    GROUP BY cuci_tangan.moment
                                    order by cuci_tangan.moment asc
                                    ')->result();
foreach ($unit as $unit)
{   ?>
                                    <tr>
                                     <td colspan="8"><div class="card-header py-1">
                                        <h6 class="m-0 font-weight-bold text-primary"><?=$start.'. '.$unit->nm_moment?></h6>
                                    </div>
                                    </td>
                                  </tr>
<?php
                                    $cuci_tangan_data=$this->db->query('SELECT  cuci_tangan.profesi,
                                    unit.nm_unit,   cuci_tangan.tanggal,	cuci_tangan.kesempatan,
                                    SUM(case when cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N,
                                    SUM(case when cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D,
                                    count(cuci_tangan.cucitangan) as jml
                                    from cuci_tangan inner join unit on cuci_tangan.unit=unit.id_unit 
                                    where cuci_tangan.tanggal between "'.$tgl1.'" and "'.$tgl2.'" and cuci_tangan.moment="'.$unit->moment.'"
                                    GROUP BY cuci_tangan.moment,cuci_tangan.kesempatan,cuci_tangan.tanggal	
                                    order BY cuci_tangan.moment,cuci_tangan.kesempatan, cuci_tangan.tanggal asc
                                    ')->result();
                                    foreach ($cuci_tangan_data as $cuci_tangan)
                                    {
                                        ?>
                                        <tr>
                                   
                                    <td colspan="3" align ="center"><?php echo $cuci_tangan->kesempatan ?></td>
                                    <td><?php echo $cuci_tangan->tanggal ?></td>
                                    <td><?php echo $cuci_tangan->N ?></td>
                                    <td><?php echo $cuci_tangan->D ?></td>
                                    <td><?php echo $cuci_tangan->jml ?></td>
                                      <td><?php echo number_format(($cuci_tangan->N/$cuci_tangan->jml)*100,2) ?></td>
                                   
                                </tr>
                                        <?php
                                        $N=$cuci_tangan->N + $N;
                                        $D=$cuci_tangan->D + $D;
                                        $jml=$cuci_tangan->jml + $jml;
                                    } ?>

                                   
                                    <td colspan="4" align ="center"><strong>Total</strong></td>
                                    <td><strong><?php echo $N ?></strong></td>
                                    <td><strong><?php echo $D ?></strong></td>
                                    <td><strong><?php echo $jml ?></strong></td>
                                     <td><strong><?php echo number_format(($N/$jml)*100,2) ?> </strong></td>
                                    <?php
    ++$start;
    $N=0;
    $D=0;
    $jml=0;
}
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>

</div>