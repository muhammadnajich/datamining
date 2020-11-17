<?php 
  
class M_Dataset extends CI_Model
{
    function tampil_data()
    {
		$dataset = $this->db->query('SELECT * FROM `dataset`');
		return $dataset;
	}

	function datacleaning()
    {
		$dataset = $this->db->query('SELECT bulan, hari, tahun, jenis_permohonan, tingkat_kepadatan FROM `dataset`');
		return $dataset;
	}

    function hitung()
    {
		$sql = $this->db->query('SELECT count(id) as jumlah FROM dataset');
		return $sql->row();
	}

	function findColumn($attr){
		$sql = $this->db->query('SELECT '.$attr.' FROM dataset GROUP BY '.$attr.' ');
		return $sql;
	}
}

?>