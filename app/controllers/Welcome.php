<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		check_logged_in();

		$this->load->model('Modeltoko');
		$this->load->model('admin/pembelian/Modelpembelian');
	}
	public function index()
	{
		$datahutang_jatuhtempo = $this->Modelpembelian->tampilnotifhutangjatuhtempo();
		$data = [
			'title' => 'Dashboard',
			'datahutang_jatuhtempo' => $datahutang_jatuhtempo,
			'datahutang' => $this->Modelpembelian->datahutang(),
			'bulanini' => date('Y-m')
		];
		$this->load->view('home/index', $data);
		// if ($this->session->userdata('masuk') == true) {
		// 	if ($this->session->userdata('idgrup') == '1') {
		// 		redirect('admin/home/index');
		// 	} else if ($this->session->userdata('idgrup') == '2') {
		// 		redirect('k/home/index');
		// 	} else {
		// 		redirect('logout');
		// 	}
		// } else {
		// 	redirect('logout');
		// }
	}
}
