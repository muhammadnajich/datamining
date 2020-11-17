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
                                    <h4 class="card-title">Hasil Proses Mining</h4>
                                </div>
                                <div class="col-md-5">
                                    
                                </div>
                             </div>

                                <div class="mr-5 ml-5 mb-5">
                                <?php if($dataset != null){ ?>
                                    <?= $all ?>
                                <?php }else{ ?>
                                    <div class="alert alert-danger">Harap untuk import data data training terlebih dahulu !</div>
                                <?php } ?>
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