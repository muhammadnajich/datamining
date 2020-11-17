<?php 
include 'assets/C45/vendor/autoload.php';
use C45\C45;

class C_DecisionTree extends CI_Controller
{
    protected $file;

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
        $data['dataset'] = $this->M_Dataset->tampil_data()->result();
        if($data['dataset'] != null){
            $data['data_train'] = $this->tidyData($this->file, 5);
            $data['treeString'] = $this->buildTree();
        }
        $this->load->view('Back/V_DecisionTree', $data);
    }        

    function buildTree(){
        $c45 = new C45([
            'targetAttribute' => 'tingkat_kepadatan',
            'trainingFile' => $this->file,
            'splitCriterion' => C45::SPLIT_GAIN,
        ]);
        $Tree = $c45->buildTree();
        return $Tree->toString();
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

    function tidyData($csv_file, $count_attr)
    {
        $csv = [];
        $csvnew = [];
        $class = null;
        $lines = file($csv_file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $key => $value){
            $csv[$key] = str_getcsv($value);
            foreach($csv[$key] as $c => $v){
                if($c == $count_attr){
                    $class = $v;
                }
                $csvnew[$c] = $v;
                unset($csvnew[$count_attr]);
            }
            $csv[$key] = array($csvnew, $class);
        }
        unset($csv[0]);
        return $csv;
    }
}
 ?>