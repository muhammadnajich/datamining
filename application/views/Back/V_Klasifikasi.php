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
                                <h4 class="card-title">Prediksi</h4>
                                </div>
                                <div style="float: right;">                               
                                <!-- <a href="<?php echo base_url() ?>admin/akun/add" class="btn btn-primary"><span class="fa fa-user-plus" style="float: right;">Tambah Akun</span></a> -->
                                </div>
                             </div>
                             <?php if($dataset == null){
                                echo '<div class="table-responsive">';
                                echo '<div class="alert alert-danger">Harap untuk export data data training terlebih dahulu !</div>';
                                echo '</div>';
                                } ?>
                            </div>
                            <?php if($dataset != null){?>
                            <div class="mb-4 ml-4 mr-4 form-row">
                                <div class="col-md-4">
                                    <div class="name">Kuartal </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group-desc">
                                        <select name="kuartal" required class="form-control">
                                            <?php foreach($kuartal as $k){ ?>
                                                <option value="<?= str_replace(' ', '_', $k->kuartal); ?>"><?= $k->kuartal; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 ml-4 mr-4 form-row">
                                <div class="col-md-4">
                                    <div class="name">Hari </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group-desc">
                                        <select name="hari" required class="form-control">
                                            <?php foreach($hari as $k){ ?>
                                                <option value="<?= str_replace(' ', '_', $k->hari); ?>"><?= $k->hari; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 ml-4 mr-4 form-row">
                                <div class="col-md-4">
                                    <div class="name">Waktu </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group-desc">
                                        <select name="waktu" required class="form-control">
                                            <?php foreach($waktu as $k){ ?>
                                                <option value="<?= str_replace(' ', '_', $k->waktu); ?>"><?= $k->waktu; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 ml-4 mr-4 form-row">
                                <div class="col-md-4">
                                    <div class="name">Jenis Permohonan </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group-desc">
                                        <select name="jenis_permohonan" required class="form-control">
                                            <?php foreach($jenis_permohonan as $k){ ?>
                                                <option value="<?= str_replace(' ', '_', $k->jenis_permohonan); ?>"><?= $k->jenis_permohonan; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>  

                            <center class="m-3">
                                <button type="submit" class="btn btn-dark" name="submit" id="submit">Decision!</button>
                            </center>
                            <?php } ?>
                        </div>

                        <div id="as"></div>
                        <div id="loc">
                            <div class="card card-5" style="" id="hasil1">
                                <center class="card-heading">
                                    <h3 class="m-4">Hasil Prediksi </h3> 
                                </center>
                                <div class="card-body">
                                    <table class="table text-center text-light">
                                        <thead class="">
                                            <tr>
                                                <th>Kuartal</th>
                                                <th>Hari</th>
                                                <th>Waktu</th>
                                                <th>Jenis Permohonan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="kuartal"></td>
                                                <td id="hari"></td>
                                                <td id="waktu"></td>
                                                <td id="jenis_permohonan"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <center class="mt-4">
                                        <h4>Prediksi : <span class="badge" id="hasil_hitung"></span></h4>
                                        <a href="#" onclick="reloadClick()"><i class="icon-reload"></i> Ulangi</a>
                                    </center>
                                </div>
                            </div>
                        </div>      

                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>
          </div>

<script>
function reloadClick(){        
    $("#hasil1").hide();
    $('#submit').show();
}
$("document").ready(function(){
    $("#hasil1").hide();

    $('#submit').click(function(){
        var kuartal = $("select[name=kuartal]").val();
        var hari = $("select[name=hari]").val();
        var waktu = $("select[name=waktu]").val();  
        var jenis_permohonan = $("select[name=jenis_permohonan]").val();       
        $("#hasil_hitung").removeClass("badge-danger");
        $("#hasil_hitung").removeClass("badge-success");
        $.ajax({
            method: 'POST',
            url: "klasifikasi/classify/",
            cache: false,
            data: {kuartal: kuartal, hari:hari, waktu:waktu, 
                    jenis_permohonan:jenis_permohonan, submit:"ada"},
            dataType: 'json',
                success: function(data){
                    console.log(data);
                    $("#hasil1").show();
                    $("#kuartal").text(data.data_kuartal);
                    $("#hari").text(data.data_hari);
                    $("#waktu").text(data.data_waktu);
                    $("#jenis_permohonan").text(data.data_jenis_permohonan);
                    $("#hasil_hitung").text(data.hasil);

                    if(data.hasil == 'Padat'){
                        $("#hasil_hitung").addClass("badge-danger");
                    }else if(data.hasil == 'Tidak_Padat'){
                        $("#hasil_hitung").addClass("badge-primary");
                    }

                    $('html, body').animate({
                        scrollTop: $("#as").offset().top
                    }, 500);
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    $("#hasil1").show();
                    $("#hasil_hitung").addClass("badge-danger");
                    $('#hasil_hitung').text(msg);
                    $('html, body').animate({
                        scrollTop: $("#as").offset().top
                    }, 500);
                },
                complete: function(){
                    $('#submit').hide();
                }
        });
    });
});
</script>

<?php 
  $this->load->view("Back/Parts/V_Footer");
 ?> 