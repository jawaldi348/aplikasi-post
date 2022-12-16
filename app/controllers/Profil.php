<?php
class Profil extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true) {
            $this->load->library([
                'form_validation',
                'Bcrypt'
            ]);
            return true;
        } else redirect('login/logout');
    }
    public function index()
    {
        $ambildata = $this->db->get_where('nn_users', ['userid' => $this->session->userdata('username')]);
        $row = $ambildata->row_array();

        $ambildatagrup = $this->db->get_where('nn_grup', ['id' => $row['usergrup']]);
        $row_grup = $ambildatagrup->row_array();

        $data = [
            'id' => $row['id'],
            'iduser' => $row['userid'],
            'namauser' => $row['usernama'],
            'foto' => $row['userfoto'],
            'namagrup' => $row_grup['nmgrup']
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => 'Profil User',
            'isi' => $this->load->view('profil/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function formgantifoto()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->view('profil/formgantifoto');
        }
    }

    public function formgantiprofil()
    {
        if ($this->input->is_ajax_request() == true) {
            $iduser = $this->session->userdata('username');
            $ambiluser = $this->db->get_where('nn_users', ['userid' => $iduser]);
            $row = $ambiluser->row_array();
            $data = [
                'id' => $row['id'],
                'iduser' => $row['userid'],
                'namalengkap' => $row['usernama']
            ];
            $this->load->view('profil/formgantiprofil', $data);
        }
    }

    public function formgantipassword()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->view('profil/formgantipassword');
        }
    }

    public function updatefoto()
    {
        $file_name = $_FILES['uploadfoto']['name'];
        $config['upload_path'] = './assets/images/users/';
        $config['file_name'] = $this->session->userdata('username');
        $config['allowed_types'] = 'png|jpg|jpeg';
        $config['max_size'] = 2024;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('uploadfoto')) {
            $pesan =  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">Error</h4><hr>
                        ' . $this->upload->display_errors() . '
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('profil/index', 'refresh');
        } else {
            $iduser = $this->session->userdata('username');
            //ambildata
            $q = $this->db->get_where('nn_users', ['userid' => $iduser]);
            $r = $q->row_array();
            $pathfotolama = $r['userfoto'];
            @unlink($pathfotolama);
            // end

            $media = $this->upload->data();
            $pathfilebaru = './assets/images/users/' . $media['file_name'];
            $dataupdate = [
                'userfoto' => $pathfilebaru
            ];
            $this->db->where('userid', $iduser);
            $this->db->update('nn_users', $dataupdate);

            $pesan =  '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">Berhasil !</h4><hr>
                        Foto berhasil diganti, silahkan logout terlebih dahulu dan login kembali
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
            $this->session->set_flashdata('pesan', $pesan);
            redirect('profil/index', 'refresh');
        }
    }

    public function updateprofil()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);
            $iduser = $this->input->post('iduser', true);
            $namauser = $this->input->post('namauser', true);

            $data_update = [
                'userid' => $iduser,
                'usernama' => $namauser,
            ];
            $this->db->where('id', $id);
            $this->db->update('nn_users', $data_update);

            $pesan =  '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Berhasil !</h4><hr>
                    Berhasil diupdate...
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            $this->session->set_flashdata('pesan', $pesan);

            $msg = [
                'sukses' => 'berhasil'
            ];

            echo json_encode($msg);
        }
    }

    public function updatepassword()
    {
        if ($this->input->is_ajax_request() == true) {
            $passlama = $this->input->post('passlama', true);
            $passbaru = $this->input->post('passbaru', true);
            $ulangipass = $this->input->post('ulangipass', true);

            $this->form_validation->set_rules('passlama', 'Password Lama', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('passbaru', 'Password Baru', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('ulangipass', 'Ulangi Password', 'trim|required|matches[passbaru]', [
                'required' => '%s tidak boleh kosong',
                'matches' => '%s tidak sama'
            ]);


            if ($this->form_validation->run() == TRUE) {
                //cek password lama
                $iduser = $this->session->userdata('username');
                $q = $this->db->get_where('nn_users', ['userid' => $iduser]);
                $r = $q->row_array();
                $pass_hash_lama = $r['userpass'];

                if ($this->bcrypt->check_password($passlama, $pass_hash_lama)) {
                    $pass_hash_baru = $this->bcrypt->hash_password($passbaru);

                    $dataupdate = [
                        'userpass' => $pass_hash_baru
                    ];
                    $this->db->where('userid', $iduser);
                    $this->db->update('nn_users', $dataupdate);

                    $pesan = [
                        'pesan' => '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        Silahkan Login Kembali
                    </div>',
                        'iduser' => $iduser
                    ];
                    $this->session->set_flashdata($pesan);

                    $msg = [
                        'sukses' => 'Password berhasil diganti'
                    ];
                } else {
                    $msg = [
                        'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h4 class="alert-heading">Error !</h4><hr>
                                   Password Lama anda salah
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>'
                    ];
                }
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h4 class="alert-heading">Error !</h4><hr>
                                ' . validation_errors() . '
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>'
                ];
            }
            echo json_encode($msg);
        }
    }
}