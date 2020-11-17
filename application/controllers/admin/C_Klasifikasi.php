<?php 
include 'assets/C45/vendor/autoload.php';
use C45\C45;

class C_Klasifikasi extends CI_Controller
{
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
        $data['kuartal'] = $this->M_Dataset->findColumn("kuartal")->result();
        $data['hari'] = $this->M_Dataset->findColumn("hari")->result();
        $data['waktu'] = $this->M_Dataset->findColumn("waktu")->result();
        $data['jenis_permohonan'] = $this->M_Dataset->findColumn("jenis_permohonan")->result();
		$this->load->view('Back/V_Klasifikasi', $data);
    }

    function classify()
    {
        $kuartal = $this->input->post('kuartal');
        $hari = $this->input->post('hari');
        $waktu = $this->input->post('waktu');
        $jenis_permohonan = $this->input->post('jenis_permohonan');

        $data_testing = [
            'kuartal' => $kuartal,
            'hari' => $hari,
            'waktu' => $waktu,
            'jenis_permohonan' => $jenis_permohonan,
        ];

        $c45 = new C45([
            'targetAttribute' => 'tingkat_kepadatan',
            'trainingFile' => $this->file,
            'splitCriterion' => C45::SPLIT_GAIN,
        ]);

        $Tree = $c45->buildTree();
        
        /**
        * Clasification Proccess
        */
        $hasil = $Tree->classify($data_testing);

        // Tampilkan datanya
        echo json_encode([
            "hasil"         => $hasil,
            "data_kuartal"   => $kuartal,
            "data_hari"    => $hari,
            "data_waktu"    => $waktu,
            "data_jenis_permohonan"  => $jenis_permohonan,
        ]);
    }
    
    function conv()
    {
        $row = $this->M_Dataset->tampil_data()->result();
        $fp = fopen($this->file, 'w');
        $hasil = '"kuartal","hari","waktu","jenis_permohonan","tingkat_kepadatan"';

        foreach ($row as $val){
            $hasil .= "\n".str_replace(' ', '_', $val->kuartal).",".str_replace(' ', '_', $val->hari).",".
                str_replace(' ', '_', $val->waktu).",".str_replace(' ', '_', $val->jenis_permohonan).",".
                str_replace(' ', '_', $val->tingkat_kepadatan);
        }

        fputs($fp, $hasil);
        fclose($fp);
    }

}
 ?>