<?php
$this->load->view("Back/Parts/V_Header");
$this->load->view("Back/Parts/V_Navigation");
?>

    <div class="content-body">
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                              <div>
                                <div style="float: left;">
                                
                                <h4 class="card-title">Data Training</h4>
                                </div>
                                <div style="float: right;">   
                                <!-- <a href="<?php echo base_url() ?>admin/akun/add" class="btn btn-primary"><span class="fa fa-user-plus" style="float: right;">Tambah Akun</span></a> -->
                                </div>
                             </div>
                                <div class="table-responsive">
                                    <?php if($dataset == null){
                                        echo '<div class="alert alert-danger">Harap untuk export data data training terlebih dahulu !</div>';
                                    } ?>
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kuartal</th>
                                                <th>Hari</th>
                                                <th>Waktu</th>
                                                <th>Jenis Permohonan</th>
                                                <th>Tingkat Kepadatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                              foreach ($dataset as $u) { ?>
                                                <td><?php echo $no ?></td>
                                                <td><?php echo $u->kuartal ?></td>
                                                <td><?php echo $u->hari ?></td>
                                                <td><?php echo $u->waktu ?></td>
                                                <td><?php echo $u->jenis_permohonan ?></td>
                                                <td><?php echo $u->tingkat_kepadatan ?></td>
                                                
                                            </tr>
                                              <?php $no++; } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>
          </div>
<?php 
  $this->load->view("Back/Parts/V_Footer");
 ?> 