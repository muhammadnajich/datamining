<?php 
	class C_DataTraining extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model('M_Dataset');
			$this->load->model('excel_import_model');
			$this->load->helper('url');
		}

        function index()
        {
            $data['dataset'] = $this->M_Dataset->datacleaning()->result();
            $data['total_dataset'] = $this->excel_import_model->hitung_dataset();
            $this->load->view('Back/V_Data_Cleaning',$data);
		}

	}
 ?>