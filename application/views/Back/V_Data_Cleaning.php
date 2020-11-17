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
                                <div style="float: left;">
                                    <h4 class="card-title">Data Cleaning</h4>                            
                                    
                                    <a href="<?php echo base_url() ?>admin/mining_c45" class="btn btn-primary" type="submit" name=""  value=""><span class="fa fa-hourglass-half"> Proses Mining</span></a>
                                </div>
                                <div class="table-responsive"><br />
                                    <h4 style="text-align: center;">Jumlah Data : <?php echo $total_dataset ?></h4>
                                    <?php
                                    if ($this->session->flashdata('result_insert')) {
                                        ?>
                                        <div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            <strong>
                                            <?php echo $this->session->flashdata('result_insert'); ?></strong>
                                        </div>
                                    <?php } ?>
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>Bulan</th>
                                                <th>Hari</th>
                                                <th>Tahun</th>
                                                <th>Jenis Permohonan</th>
                                                <th>Tingkat Kepadatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                              <?php foreach ($dataset as $u) { ?>
                                            <tr>
                                                <td><?php echo $u->bulan ?></td>
                                                <td><?php echo $u->hari ?></td>
                                                <td><?php echo $u->tahun ?></td>
                                                <td><?php echo $u->jenis_permohonan ?></td>
                                                <td><?php echo $u->tingkat_kepadatan ?></td>
                                            </tr>
                                              <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Bulan</th>
                                                <th>Hari</th>
                                                <th>Tahun</th>
                                                <th>Jenis Permohonan</th>
                                                <th>Tingkat Kepadatan</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                             </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>
    </div>
    <!--<script>
    $(document).ready(function(){

        load_data();

        function load_data()
        {
            $.ajax({
                url:"<?php //echo base_url(); ?>admin/excel_import/fetch",
                method:"POST",
                success:function(data){
                    $('#dataset').html(data);
                }
            })
        }
 
        $('#import_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                url:"<?php //echo base_url() ?>admin/excel_import/import",
                method:"POST",
                data:new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                success:function(data){
                    $('#file').val('');
                    load_data();
                    alert(data);
                }
            })
        });

    });
    </script>
    <script type="text/javascript">
    $(document).ready(function(){
      $('#import_form').on('submit',function(e) { 
      $.ajax({  
          url:'<?php //echo base_url() ?>Excel_import/import', 
          data:$(this).serialize(),
          type:'POST',
          success:function(data){
            console.log(data);
         swal("Berhasil!", "Data Berhasil Diunggah!", "success");
          },
          error:function(data){
         swal("Oops...", "Data Gagal Diunggah :(", "error");
          }
        });
        e.preventDefault();
      });
    });
    </script>-->

<?php 
  $this->load->view("Back/Parts/V_Footer");
 ?> 