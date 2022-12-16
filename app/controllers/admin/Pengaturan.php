<?php
class Pengaturan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->aksesgrup = $this->session->userdata('idgrup');
        if ($this->session->userdata('masuk') == true && ($this->aksesgrup == 1)) {
            $this->load->library(['form_validation']);
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $data = [
            'datapengaturan' => $this->db->get('pengaturan')->row_array()
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Pengaturan Lainnya',
            'isi' => $this->load->view('admin/pengaturan/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function simpan()
    {
        $stokminus = $this->input->post('stokminus', true);

        $this->db->where('id', 1);
        $this->db->update('pengaturan', [
            'stokminus' => $stokminus
        ]);

        redirect('pengaturan/index', 'refresh');
    }
}