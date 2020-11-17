<?php 
class C_MiningC45 extends CI_Controller
{
    protected $file;
    protected $negvAttr = "Padat";
    protected $posvAttr = "Tidak_Padat";

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_Dataset');
        $this->load->helper('url');
        $this->file = 'assets/C45/antrian.csv';
    }
    
    function index()
    {
        $this->conv();
        $data['dataset'] = $this->M_Dataset->datacleaning()->result();
        if($data['dataset'] != null){
            $data['all'] = $this->main();
        }
        $this->load->view('Back/V_MiningC45',$data);
    }

    /**
    * 
    * PROSES ITERASI
    */
    function main(){
        $text = "";
        $attributRev = ['bulan','hari','tahun','jenis_permohonan'];
        $valAttribut = [
            '0' => [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            '1' => [ 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            '2' => [ '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
            '3' => [ 'KTP', 'KK'],
        ];
        $getTrainAll = $this->counting(4, $this->posvAttr) + $this->counting(4, $this->negvAttr);
        $getTrainBoleh = $this->counting(4, $this->posvAttr);
        $getTrainTidak = $this->counting(4, $this->negvAttr);
        $text .= $this->bentukTreeRoot($getTrainAll, $getTrainBoleh, $getTrainTidak, $valAttribut, $attributRev);
        //$text .= '<div class="alert alert-dark text-dark mb-5">
                //Cabang 1 : jenis permohonan == KTP (Leaf | Padat)<br>    
                //Cabang 2 : jenis permohonan == KK <br>
            //</div>';

        $attributRev = ['hari','tahun','jenis_permohonan'];
        $valAttributLoop = [
            '0' => [ 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
            '1' => [ '2014', '2015', '2016', '2017', '2018', '2019', '2020'],
            '2' => [ 'KTP', 'KK'],
        ];
        
        $text .= "<strong>Cabang (Bulan == Juli)</strong> <br>";
        $getTrainAll = $this->counting(0, 'Juli');
        $getTrainBoleh = $this->counting(0, 'Juli', $this->posvAttr);
        $getTrainTidak = $this->counting(0, 'Juli', $this->negvAttr);
        $text .= $this->bentukTreeNode2($getTrainAll, $getTrainBoleh, $getTrainTidak, $valAttribut, 
                                    $valAttributLoop, $attributRev, 0, 'Juli');

        $text .= "<strong>Cabang (Bulan == Juni)</strong> <br>";
        $getTrainAll = $this->counting(2, 'Juni');
        $getTrainBoleh = $this->counting(2, 'Juni', $this->posvAttr);
        $getTrainTidak = $this->counting(2, 'Juni', $this->negvAttr);
        $text .= $this->bentukTreeNode2($getTrainAll, $getTrainBoleh, $getTrainTidak, $valAttribut, 
                                    $valAttributLoop, $attributRev, 2, 'Juni');

        $text .= "<strong>Cabang (Bulan == Maret)</strong> <br>";
        $getTrainAll = $this->counting(2, 'Maret');
        $getTrainBoleh = $this->counting(2, 'Maret', $this->posvAttr);
        $getTrainTidak = $this->counting(2, 'Maret', $this->negvAttr);
        $text .= $this->bentukTreeNode2($getTrainAll, $getTrainBoleh, $getTrainTidak, $valAttribut, 
                                    $valAttributLoop, $attributRev, 2, 'Maret');

        $text .= "<strong>Cabang (Bulan == Agustus)</strong> <br>";
        $getTrainAll = $this->counting(0, 'Agustus');
        $getTrainBoleh = $this->counting(0, 'Agustus', $this->posvAttr);
        $getTrainTidak = $this->counting(0, 'Agustus', $this->negvAttr);
        $text .= $this->bentukTreeNode2($getTrainAll, $getTrainBoleh, $getTrainTidak, $valAttribut, 
                                    $valAttributLoop, $attributRev, 0, 'Agustus');

        $text .= "<strong>Cabang (Bulan == Februari)</strong> <br>";
        $getTrainAll = $this->counting(0, 'Februari');
        $getTrainBoleh = $this->counting(0, 'Februari', $this->posvAttr);
        $getTrainTidak = $this->counting(0, 'Februari', $this->negvAttr);
        $text .= $this->bentukTreeNode2($getTrainAll, $getTrainBoleh, $getTrainTidak, $valAttribut, 
                                    $valAttributLoop, $attributRev, 0, 'Februari');

        $text .= "<strong>Cabang (Bulan == April)</strong> <br>";
        $getTrainAll = $this->counting(0, 'April');
        $getTrainBoleh = $this->counting(0, 'April', $this->posvAttr);
        $getTrainTidak = $this->counting(0, 'April', $this->negvAttr);
        $text .= $this->bentukTreeNode2($getTrainAll, $getTrainBoleh, $getTrainTidak, $valAttribut, 
                                    $valAttributLoop, $attributRev, 0, 'April');

        return $text;
    }

    function entropy($values) {
        $e = 0;
        $sum = array_sum($values); // dijumlahkan untuk mencari (S) dri rumusnya
        foreach ($values as $value) {
            if ($value > 0) {
                // hitung entropy nya 
                $e += - ((($value / $sum) * log($value / $sum, 2)));
            }else{
                $e = 0;
            }
        }
        return $e;
    }

    function gain($entropy_all, $values) { 
        // $entropy_all = hasil entropy dri pencarian internal / root node 
        // $values  = jumlah colom sesuai kategori class target (boleh/tidak)
        $total_records = 0;
        foreach ($values as $sub_values) {
            $total_records += array_sum($sub_values);
        }
        $gain = 0;
        foreach ($values as $sub_values) {
            $sub_total_value = array_sum($sub_values);
            $gain += ($sub_total_value / $total_records * $this->entropy($sub_values));
        }
        $gain = $entropy_all - $gain;
        return $gain;
    }

    /**
     * 
     * DEFINISI FUNCTION LAIN
     */
    function getCSV(){
        $arrTrain = [];
        if (($handle = fopen($this->file, 'r')) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $arrTrain[] = $data;
            }
            fclose($handle);
        }
        return $arrTrain;
    }

    function counting($indexKolom, ...$kondisi){
        $arrTrain = $this->getCSV();
        $count = 0;
        for ($i=0; $i < count($arrTrain); $i++) { 
            if(count($kondisi) == 1){
                if($arrTrain[$i][$indexKolom] == $kondisi[0]){
                    $count++;
                }
            }else if(count($kondisi) == 2){
                if($arrTrain[$i][$indexKolom] == $kondisi[0] && 
                    $arrTrain[$i][4] == $kondisi[1]){
                    $count++;
                }
            }
        }
        return $count;
    }

    function counting2($indexKolom1, $kondisi1, $indexKolom, ...$kondisi){
        $arrTrain = $this->getCSV();
        $count = 0;
        for ($i=0; $i < count($arrTrain); $i++) { 
            if(count($kondisi) == 1){
                if($arrTrain[$i][$indexKolom] == $kondisi[0] && 
                    $arrTrain[$i][$indexKolom1] == $kondisi1){
                    $count++;
                }
            }else if(count($kondisi) == 2){
                if($arrTrain[$i][$indexKolom] == $kondisi[0] && 
                    $arrTrain[$i][$indexKolom1] == $kondisi1 && 
                    $arrTrain[$i][4] == $kondisi[1]){
                    $count++;
                }
            }
        }
        return $count;
    }

    function counting3($indexKolom1, $kondisi1, $indexKolom2, $kondisi2, $indexKolom, ...$kondisi){
        $arrTrain = $this->getCSV();
        $count = 0;
        for ($i=0; $i < count($arrTrain); $i++) { 
            if(count($kondisi) == 1){
                if($arrTrain[$i][$indexKolom] == $kondisi[0] && 
                    $arrTrain[$i][$indexKolom1] == $kondisi1 && 
                    $arrTrain[$i][$indexKolom2] == $kondisi2){
                    $count++;
                }
            }else if(count($kondisi) == 2){
                if($arrTrain[$i][$indexKolom] == $kondisi[0] && 
                    $arrTrain[$i][$indexKolom1] == $kondisi1 && 
                    $arrTrain[$i][$indexKolom2] == $kondisi2 &&
                    $arrTrain[$i][4] == $kondisi[1]){
                    $count++;
                }
            }
        }
        return $count;
    }

    function counting4($indexKolom1, $kondisi1, $indexKolom2, $kondisi2, $indexKolom3, $kondisi3, $indexKolom, ...$kondisi){
        $arrTrain = $this->getCSV();
        $count = 0;
        for ($i=0; $i < count($arrTrain); $i++) { 
            if(count($kondisi) == 1){
                if($arrTrain[$i][$indexKolom] == $kondisi[0] && 
                    $arrTrain[$i][$indexKolom1] == $kondisi1 && 
                    $arrTrain[$i][$indexKolom2] == $kondisi2 && 
                    $arrTrain[$i][$indexKolom3] == $kondisi3){
                    $count++;
                }
            }else if(count($kondisi) == 2){
                if($arrTrain[$i][$indexKolom] == $kondisi[0] && 
                    $arrTrain[$i][$indexKolom1] == $kondisi1 && 
                    $arrTrain[$i][$indexKolom2] == $kondisi2 &&
                    $arrTrain[$i][$indexKolom3] == $kondisi3 &&
                    $arrTrain[$i][4] == $kondisi[1]){
                    $count++;
                }
            }
        }
        return $count;
    }

    function kolomTop(){
        return '<table class="table">
                    <thead> <tr>
                        <th scope="col">Atribut</th>
                        <th scope="col">Value</th>
                        <th scope="col">Jumlah Kasus</th>
                        <th scope="col">Jumlah Kasus Tidak Padat</th>
                        <th scope="col">Jumlah Kasus Padat</th>
                        <th scope="col">Entropy</th>
                        <th scope="col">Gain</th>
                    </tr></thead>
                    <tbody>';
    }

    function bentukTreeRoot($getTrainAll, $getTrainBoleh, $getTrainTidak, $valAttribut, $attributRev){
        $text = '';
        $text .= 'Total Data: '.$getTrainAll. '<br>';
        $text .= 'Nilai Class ('.$this->posvAttr.'): '.$getTrainBoleh. '<br>';
        $text .= 'Nilai Class ('.$this->negvAttr.'): '.$getTrainTidak. '<br>';
        $text .= 'Entropy: '.$this->entropy([$getTrainBoleh, $getTrainTidak]). '<br>';
        $text .= $this->kolomTop();
        $entropyAll = $this->entropy([$getTrainBoleh, $getTrainTidak]);
        $gain = [];
        $cocok = [];
        for ($i=0; $i < count($valAttribut); $i++) {
            $gainSementara = [];
            $attrSementara = "";
            for ($j=0; $j < count($valAttribut[$i]); $j++) { 
                $jmlAll = $this->counting($i, $valAttribut[$i][$j]);
                $jmlAllBoleh = $this->counting($i, $valAttribut[$i][$j], $this->posvAttr);
                $jmlAllTidak = $this->counting($i, $valAttribut[$i][$j], $this->negvAttr);
                $gainSementara[] = [$jmlAllBoleh, $jmlAllTidak];
                $attrSementara = $attributRev[$i];
                $text .='<tr>
                        <td>'.$attributRev[$i].'</td>
                        <td>'.$valAttribut[$i][$j].'</td> 
                        <td>'.$jmlAll.'</td> 
                        <td>'.$jmlAllBoleh.'</td> 
                        <td>'.$jmlAllTidak.'</td> 
                        <td>'.$this->entropy([$jmlAllBoleh,$jmlAllTidak]).'</td>';
            }
            $text .=       '<td>'. $this->gain($entropyAll, $gainSementara) .'</td>
                    </tr>';
            $gain[] = $this->gain($entropyAll, $gainSementara);
            $cocok[] = [
                "attribute" => $attrSementara,
                "gain" => $this->gain($entropyAll, $gainSementara)
            ];
        }
        $maxAttribute = '';
        for ($i=0; $i < count($cocok); $i++) { 
            if($cocok[$i]['gain'] == max($gain)){
                $maxAttribute = $cocok[$i]['attribute'];
            }
        }
        $text .= '      </tbody>
                </table>
                <div class="alert alert-primary font-weight-bold text-center text-primary mt-3">
                    Atribut Terpilih '.$maxAttribute.', gain : '.max($gain).'
                </div>';
        return $text;
    }

    // JIKA BERCABANG 2
    function bentukTreeNode2($getTrainAll, $getTrainBoleh, $getTrainTidak, 
                                $valAttribut, $valAttributLoop, $attributRev, $inx, $kond){
        $text = '';
        $text .= 'Total Data: '.$getTrainAll. '<br>';
        $text .= 'Nilai Class ('.$this->posvAttr.'): '.$getTrainBoleh. '<br>';
        $text .= 'Nilai Class ('.$this->negvAttr.'): '.$getTrainTidak. '<br>';
        $text .= 'Entropy: '.$this->entropy([$getTrainBoleh, $getTrainTidak]). '<br>';
        $text .= $this->kolomTop();
        $entropyAll = $this->entropy([$getTrainBoleh, $getTrainTidak]);
        $gain = [];
        for ($i=0; $i < count($valAttributLoop); $i++) {
            $gainSementara = [];
            $attrSementara = "";
            for ($j=0; $j < count($valAttributLoop[$i]); $j++) { 
                if($i == 0){
                    $iBaru = 0;
                }elseif($i == 1){
                    $iBaru = 2;
                }elseif($i == 2){
                    $iBaru = 3;
                }
                $jmlAll = $this->counting2($inx, $kond, $iBaru, $valAttribut[$iBaru][$j]);
                $jmlAllBoleh = $this->counting2($inx, $kond, $iBaru, $valAttribut[$iBaru][$j], $this->posvAttr);
                $jmlAllTidak = $this->counting2($inx, $kond, $iBaru, $valAttribut[$iBaru][$j], $this->negvAttr);
                $gainSementara[] = [$jmlAllBoleh, $jmlAllTidak];
                $attrSementara = $attributRev[$i];
                $text .='<tr>
                        <td>'.$attributRev[$i].'</td>
                        <td>'.$valAttributLoop[$i][$j].'</td> 
                        <td>'.$jmlAll.'</td> 
                        <td>'.$jmlAllBoleh.'</td> 
                        <td>'.$jmlAllTidak.'</td> 
                        <td>'.$this->entropy([$jmlAllBoleh,$jmlAllTidak]).'</td>';
            }
            $text .=        '<td>'. $this->gain($entropyAll, $gainSementara) .'</td>
                    </tr>';
            $gain[] = $this->gain($entropyAll, $gainSementara);
            $cocok[] = [
                "attribute" => $attrSementara,
                "gain" => $this->gain($entropyAll, $gainSementara)
            ];
        }
        $maxAttribute = '';
        for ($i=0; $i < count($cocok); $i++) { 
            if($cocok[$i]['gain'] == max($gain)){
                $maxAttribute = $cocok[$i]['attribute'];
            }
        }
        $text .= '      </tbody>
                </table>
                <div class="alert alert-primary font-weight-bold text-center text-primary mt-3">
                    Atribut Terpilih '.$maxAttribute.', gain : '.max($gain).'
                </div>';
        return $text;
    }

    // JIKA BERCABANG 3
    function bentukTreeNode3($getTrainAll, $getTrainBoleh, $getTrainTidak, 
                                $valAttribut, $attribut, $valAttributLoop, $attributRev, 
                                $inx, $kond, $inx1, $kond1){
        $text = '';
        $text .= 'Total Data: '.$getTrainAll. '<br>';
        $text .= 'Nilai Class ('.$this->posvAttr.'): '.$getTrainBoleh. '<br>';
        $text .= 'Nilai Class ('.$this->negvAttr.'): '.$getTrainTidak. '<br>';
        $text .= 'Entropy: '.$this->entropy([$getTrainBoleh, $getTrainTidak]). '<br>';
        $text .= $this->kolomTop();
        $entropyAll = $this->entropy([$getTrainBoleh, $getTrainTidak]);
        $gain = [];
        for ($i=0; $i < count($valAttributLoop); $i++) {
            $gainSementara = [];
            $attrSementara = "";
            for ($j=0; $j < count($valAttributLoop[$i]); $j++) { 
                if($i == 0){
                    $iBaru = 1;
                }elseif($i == 1){
                    $iBaru = 2;
                }
                $jmlAll = $this->counting3($inx, $kond, $inx1, $kond1, $iBaru, $valAttribut[$iBaru][$j]);
                $jmlAllBoleh = $this->counting3($inx, $kond, $inx1, $kond1, $iBaru, $valAttribut[$iBaru][$j], $this->posvAttr);
                $jmlAllTidak = $this->counting3($inx, $kond, $inx1, $kond1, $iBaru, $valAttribut[$iBaru][$j], $this->negvAttr);
                $gainSementara[] = [$jmlAllBoleh, $jmlAllTidak];
                $attrSementara = $attributRev[$i];
                $text .='<tr>
                        <td>'.$attributRev[$i].'</td>
                        <td>'.$valAttributLoop[$i][$j].'</td> 
                        <td>'.$jmlAll.'</td> 
                        <td>'.$jmlAllBoleh.'</td> 
                        <td>'.$jmlAllTidak.'</td> 
                        <td>'.$this->entropy([$jmlAllBoleh,$jmlAllTidak]).'</td>';
            }
            $text .=        '<td>'. $this->gain($entropyAll, $gainSementara) .'</td>
                    </tr>';
            $gain[] = $this->gain($entropyAll, $gainSementara);
            $cocok[] = [
                "attribute" => $attrSementara,
                "gain" => $this->gain($entropyAll, $gainSementara)
            ];
        }
        $maxAttribute = '';
        for ($i=0; $i < count($cocok); $i++) { 
            if($cocok[$i]['gain'] == max($gain)){
                $maxAttribute = $cocok[$i]['attribute'];
            }
        }
        $text .= '      </tbody>
                </table>
                <div class="alert alert-primary font-weight-bold text-center text-primary mt-3">
                    Atribut Terpilih '.$maxAttribute.', gain : '.max($gain).'
                </div>';
    }

    // JIKA BERCABANG 4
    function bentukTreeNode4($getTrainAll, $getTrainBoleh, $getTrainTidak, 
                                $valAttribut, $attribut, $valAttributLoop, $attributRev, 
                                $inx, $kond, $inx1, $kond1, $inx2, $kond2){
        $text = '';
        $text .= 'Total Data: '.$getTrainAll. '<br>';
        $text .= 'Nilai Class ('.$this->posvAttr.'): '.$getTrainBoleh. '<br>';
        $text .= 'Nilai Class ('.$this->negvAttr.'): '.$getTrainTidak. '<br>';
        $text .= 'Entropy: '.$this->entropy([$getTrainBoleh, $getTrainTidak]). '<br>';
        $text .= $this->kolomTop();
        $entropyAll = $this->entropy([$getTrainBoleh, $getTrainTidak]);
        $gain = [];
        for ($i=0; $i < count($valAttributLoop); $i++) {
            $gainSementara = [];
            $attrSementara = "";
            for ($j=0; $j < count($valAttributLoop[$i]); $j++) { 
                if($i == 0){
                    $iBaru = 1;
                }
                $jmlAll = $this->counting4( $inx, $kond, $inx1, $kond1, $inx2, $kond2, $iBaru, $valAttribut[$iBaru][$j]);
                $jmlAllBoleh = $this->counting4( $inx, $kond, $inx1, $kond1, $inx2, $kond2, $iBaru, $valAttribut[$iBaru][$j], $this->posvAttr);
                $jmlAllTidak = $this->counting4( $inx, $kond, $inx1, $kond1, $inx2, $kond2, $iBaru, $valAttribut[$iBaru][$j], $this->negvAttr);
                $gainSementara[] = [$jmlAllBoleh, $jmlAllTidak];
                $attrSementara = $attributRev[$i];
                $text .='<tr>
                        <td>'.$attributRev[$i].'</td>
                        <td>'.$valAttributLoop[$i][$j].' '.$iBaru.' '.$j.' '.$valAttribut[$iBaru][$j].'</td> 
                        <td>'.$jmlAll.'</td> 
                        <td>'.$jmlAllBoleh.'</td> 
                        <td>'.$jmlAllTidak.'</td> 
                        <td>'.$this->entropy([$jmlAllBoleh,$jmlAllTidak]).'</td>';
            }
            $text .=        '<td>'. $this->gain($entropyAll, $gainSementara) .'</td>
                    </tr>';
            $gain[] = $this->gain($entropyAll, $gainSementara);
            $cocok[] = [
                "attribute" => $attrSementara,
                "gain" => $this->gain($entropyAll, $gainSementara)
            ];
        }
        $maxAttribute = '';
        for ($i=0; $i < count($cocok); $i++) { 
            if($cocok[$i]['gain'] == max($gain)){
                $maxAttribute = $cocok[$i]['attribute'];
            }
        }
        $text .= '      </tbody>
                </table>
                <div class="alert alert-primary font-weight-bold text-center text-primary mt-3">
                    Atribut Terpilih '.$maxAttribute.', gain : '.max($gain).'
                </div>';
    }

    function conv()
    {
        $row = $this->M_Dataset->tampil_data()->result();
        $fp = fopen($this->file, 'w');
        $hasil = '"bulan","hari","tahun","jenis_permohonan","tingkat_kepadatan"';

        foreach ($row as $val){
            $hasil .= "\n".str_replace(' ', '_', $val->bulan).",".str_replace(' ', '_', $val->hari).",".
                str_replace(' ', '_', $val->tahun).",".
                str_replace(' ', '_', $val->jenis_permohonan).",".
                str_replace(' ', '_', $val->tingkat_kepadatan);
        }

        fputs($fp, $hasil);
        fclose($fp);
    }

}
?>