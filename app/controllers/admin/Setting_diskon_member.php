<?php
class Setting_diskon_member extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library(array(
                'form_validation'
            ));
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $data = [
            'data' => $this->db->get('member_setting_diskon')->row_array()
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Setting Diskon Member',
            'isi' => $this->load->view('admin/settingdiskon/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function simpan()
    {
        if ($this->input->is_ajax_request()) {
            $diskon = $this->input->post('diskon', true);

            $this->db->update('member_setting_diskon', [
                'diskon' => $diskon
            ]);

            $msg = [
                'sukses' => 'Diskon berhasil di update'
            ];
            echo json_encode($msg);
        }
    }
}