<?php
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['Bcrypt', 'form_validation']);
        $this->load->model('Modeltoko', 'toko');
    }
    public function index()
    {
        if ($this->session->userdata('masuk') == true) {
            if ($this->session->userdata('idgrup') == '1') {
                redirect('admin/home/index');
            } else if ($this->session->userdata('idgrup') == '2') {
                redirect('k/home/index');
            } else {
                redirect('login/logout');
            }
        } else {
            //Ambil Data toko
            $toko = $this->toko->datatoko();
            $rtoko = $toko->row_array();
            $data = [
                'logo' => $rtoko['logo'],
                'namatoko' => $rtoko['nmtoko'],
                'datauser' => $this->db->get('nn_users')
            ];
            $this->load->view('login/index', $data);
        }
    }

    public function validasi_user()
    {


        $iduser = $this->input->post('iduser', true);
        $pass = $this->input->post('pass', true);

        $this->form_validation->set_rules('iduser', 'Username', 'trim|required', [
            'required' => '%s tidak boleh kosong'
        ]);
        $this->form_validation->set_rules('pass', 'Password', 'trim|required', [
            'required' => '%s tidak boleh kosong'
        ]);



        if ($this->form_validation->run() == TRUE) {
            //cek user
            $cekuser = $this->db->get_where('nn_users', ['userid' => $iduser]);
            if ($cekuser->num_rows() > 0) {
                //Ambil Data toko
                $toko = $this->toko->datatoko();
                $rtoko = $toko->row_array();

                // end

                $row = $cekuser->row_array();
                $pass_hash = $row['userpass'];

                // Ambil data grup
                $datagrup = $this->db->get_where('nn_grup', ['id' => $row['usergrup']]);
                $row_grup = $datagrup->row_array();

                if ($this->bcrypt->check_password($pass, $pass_hash)) {
                    $simpan_session = [
                        'masuk' => true,
                        'username' => $iduser,
                        'userid' => $row['id'],
                        'namalengkapuser' => $row['usernama'],
                        'fotouser' => $row['userfoto'],
                        'idgrup' => $row['usergrup'],
                        'idtoko' => $rtoko['idtoko'],
                        'logo' => $rtoko['logo'],
                        'namatoko' => $rtoko['nmtoko'],
                        'namagrup' => $row_grup['nmgrup']
                    ];
                    $this->session->set_userdata($simpan_session);
                } else {
                    $pesan = [
                        'pesan' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong>Maaf !</strong> Password anda salah
                    </div>',
                        'iduser' => $iduser
                    ];
                    $this->session->set_flashdata($pesan);
                    redirect('login/index');
                }
            } else {
                $pesan = [
                    'pesan' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Maaf !</strong> User tidak ditemukan...
                </div>',
                    'iduser' => $iduser
                ];
                $this->session->set_flashdata($pesan);
                redirect('login/index');
            }
        } else {
            $pesan = [
                'pesan' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <strong>Error !</strong>' . validation_errors() . '
            </div>'
            ];
            $this->session->set_flashdata($pesan);
            redirect('login/index');
        }

        if ($this->session->userdata('masuk') == true) {
            if ($this->session->userdata('idgrup') == '1') {
                redirect('admin/home/index');
            }
            if ($this->session->userdata('idgrup') == '2') {
                // redirect('kasir/input');
                redirect('k/home/index');
            }
            if ($this->session->userdata('idgrup') == '4') {
                // redirect('kasir/input');
                redirect('p/home/index');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login/index');
    }
}