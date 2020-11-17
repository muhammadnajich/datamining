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
                                    <h4 class="card-title">Hasil Pohon Keputusan</h4>
                                </div>
                                <div class="col-md-5">

                                </div>
                             </div>
                             <div>
                                <!-- TAMPILKAN POHON KEPUTUSAN -->
                                <div class="ml-4">
                                    <?php if($dataset != null){ ?>
                                        <pre>Data Training : <?= count($data_train); ?> Row </pre>
                                        <pre><?php print_r($treeString); ?></pre>
                                    <?php }else{ ?>
                                        <div class="alert alert-danger">Harap untuk import data data training terlebih dahulu !</div>
                                    <?php } ?>
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

<?php 
  $this->load->view("Back/Parts/V_Footer");
 ?> 