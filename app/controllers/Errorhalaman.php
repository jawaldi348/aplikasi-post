<?php
class Errorhalaman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-info"></i> Error !',
            'isi' => $this->load->view('error/halamanerror', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }
}