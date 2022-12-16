<?php
class Toko extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->model('Modeltoko', 'toko');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $data = [
            'data' => $this->db->get('nn_namatoko')->row_array()
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-store"></i> Konfigurasi Toko',
            'isi' => $this->load->view('admin/toko/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function formupdate()
    {
        if ($this->input->is_ajax_request() == true) {
            $data = [
                'd' => $this->db->get('nn_namatoko')->row_array()
            ];
            $this->load->view('admin/toko/modalform', $data);
        }
    }

    function update()
    {
        // if ($this->input->is_ajax_request() == true) {
        $namatoko = $this->input->post('namatoko', true);
        $alamat = $this->input->post('alamat', true);
        $telp = $this->input->post('telp', true);
        $hp = $this->input->post('hp', true);
        $pemilik = $this->input->post('pemilik', true);
        $tulisanstruk = $this->input->post('tulisanstruk', true);

        $file_logo = $_FILES['logo']['name'];
        if ($file_logo == NULL) {
            $dataupdate = [
                'nmtoko' => $namatoko,
                'alamat' => $alamat,
                'telp' => $telp,
                'pemilik' => $pemilik,
                'hp' => $hp,
                'tulisanstruk' => $tulisanstruk
            ];
            $this->db->update('nn_namatoko', $dataupdate);

            $pesan =  '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">Berhasil !</h4><hr>
            Toko berhasil diupdate
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('admin/toko/index', 'refresh');
        } else {
            $config['upload_path'] = './assets/images/';
            $config['file_name'] = $file_logo;
            $config['allowed_types'] = 'png|jpg|jpeg';
            $config['max_size'] = 2024;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('logo')) {
                $pesan =  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Error</h4><hr>
                ' . $this->upload->display_errors() . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>';
                $this->session->set_flashdata('pesan', $pesan);
                redirect('admin/toko/index', 'refresh');
            } else {

                //ambil gambar lama
                $datatoko = $this->db->get('nn_namatoko');
                $rr = $datatoko->row_array();
                $logotokolama = $rr['logo'];
                @unlink($logotokolama);

                $media = $this->upload->data();
                $logotokobaru = './assets/images/' . $media['file_name'];

                $dataupdate = [
                    'nmtoko' => $namatoko,
                    'alamat' => $alamat,
                    'telp' => $telp,
                    'pemilik' => $pemilik,
                    'logo' => $logotokobaru,
                    'hp' => $hp,
                    'tulisanstruk' => $tulisanstruk
                ];
                $this->db->update('nn_namatoko', $dataupdate);

                $pesan =  '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Berhasil !</h4><hr>
                Toko berhasil diupdate
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>';
                $this->session->set_flashdata('pesan', $pesan);
                redirect('admin/toko/index', 'refresh');
            }
        }
        // echo json_encode($msg);
        // }
    }
}