<?php

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->model('Modeltoko', 'toko');
            $this->load->model('admin/pembelian/Modelpembelian', 'beli');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {

        $datahutang_jatuhtempo = $this->beli->tampilnotifhutangjatuhtempo();
        $data = [
            'datahutang_jatuhtempo' => $datahutang_jatuhtempo,
            'datahutang' => $this->beli->datahutang(),
            'bulanini' => date('Y-m')
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => 'Selamat Datang',
            'isi' => $this->load->view('admin/home/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }
}