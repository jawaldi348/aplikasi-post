<?php
class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '2') {
            $this->load->model('Modeltoko', 'toko');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $hariini = date('Y-m-d');
        $bulanini = date('Y-m');
        $tahunini = date('Y');
        $sql_penjualanhariini = $this->db->query("SELECT jualfaktur FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') = '$hariini' ")->result();

        $sql_penjualanbulanini = $this->db->query("SELECT jualfaktur FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m') = '$bulanini' ")->result();

        $sql_penjualantahunini = $this->db->query("SELECT jualfaktur FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y') = '$tahunini' ")->result();

        $data = [
            'totalhariini' => count($sql_penjualanhariini),
            'totalbulanini' => count($sql_penjualanbulanini),
            'totaltahunini' => count($sql_penjualantahunini),
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => 'Selamat Datang',
            'isi' => $this->load->view('kasir/home/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }
}