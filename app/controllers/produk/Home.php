<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_logged_in();
    }
    public function index()
    {
        $data = [
            'title' => 'Manajemen Data Produk',
        ];
        $this->load->view('produk/home', $data);
    }
}

/* End of file Home.php */
