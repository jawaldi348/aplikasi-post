<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_logged_in();
    }
    public function index()
    {
        $data = [
            'title' => 'Produk',
        ];
        $this->load->view('produk/produk/index', $data);
    }
}

/* End of file Produk.php */
