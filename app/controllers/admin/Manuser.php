<?php
class Manuser extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library([
                'form_validation', 'Bcrypt'
            ]);
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {

        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-cogs"></i> Manajemen User',
            'isi' => $this->load->view('admin/manuser/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function ambildata()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/user/Modeluser', 'user');
            $list = $this->user->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                if ($this->session->userdata('username') == $field->userid) {
                    $tombolhapus = "";
                } else {
                    $tombolhapus = "<button type=\"button\" title=\"Hapus Data\" class=\"btn btn-sm btn-outline-danger\" onclick=\"hapus('" . $field->userid . "')\">
                        <i class=\"fa fa-trash-alt\"></i>
                    </button>";
                    if ($field->useraktif == 1) {
                        $tombollock = "<button type=\"button\" title=\"Non Aktifkan User ?\" class=\"btn btn-sm btn-outline-info\" onclick=\"editstatus('" . $field->userid . "')\">
                        <i class=\"fa fa-lock\"></i>
                    </button>";
                    } else {
                        $tombollock = "<button type=\"button\" title=\"Aktifkan User ?\" class=\"btn btn-sm btn-outline-info\" onclick=\"editstatus('" . $field->userid . "')\">
                        <i class=\"fa fa-lock-open\"></i>
                    </button>";
                    }
                    $tomboledit = "<button type=\"button\" title=\"Edit User\" class=\"btn btn-sm btn-outline-primary\" onclick=\"edituser('" . $field->userid . "')\">
                    <i class=\"fa fa-tag\"></i>
                </button>";
                }
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $field->userid;
                $row[] = $field->usernama;
                if ($field->useraktif == '1') {
                    $useraktif = '<i class="fa fa-check-circle" style="color:green;" title="Aktif"></i>';
                } else {
                    $useraktif = '<i class="fa fa-ban" style="color:red;" title="Tidak Aktif"></i>';
                }
                $row[] = $useraktif;
                $row[] = $field->nmgrup;
                $row[] = $tombolhapus . ' ' . $tombollock . ' ' . $tomboledit;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->user->count_all(),
                "recordsFiltered" => $this->user->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function tambah()
    {
        if ($this->input->is_ajax_request() == true) {

            $this->load->view('admin/manuser/formtambah', [
                'grup' => $this->db->get('nn_grup')->result()
            ]);
        }
    }

    public function formedituser()
    {
        if ($this->input->is_ajax_request() == true) {
            $userid = $this->input->post('userid', true);

            $ambildatauser = $this->db->get_where('nn_users', ['userid' => $userid]);
            $row = $ambildatauser->row_array();
            $data = [
                'grup' => $this->db->get('nn_grup')->result(),
                'userid' => $userid,
                'usernama' => $row['usernama'],
                'usergrup' => $row['usergrup'],
            ];
            $this->load->view('admin/manuser/formedit', $data);
        }
    }

    function simpandata()
    {
        if ($this->input->is_ajax_request() == true) {
            $iduser = $this->input->post('iduser', true);
            $namalengkap = $this->input->post('namalengkap', true);
            $grup = $this->input->post('grup', true);
            $pass = $this->input->post('pass', true);

            $this->form_validation->set_rules('iduser', 'ID User', 'trim|required|is_unique[nn_users.userid]', [
                'required' => 'Setidaknya %s tidak boleh kosong',
                'is_unique' => '%s sudah ada, coba yang lain'
            ]);

            $this->form_validation->set_rules('namalengkap', 'Nama Lengkap User', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);

            $this->form_validation->set_rules('pass', 'Password', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);

            $this->form_validation->set_rules('cpass', 'Confirm Password', 'trim|required|matches[pass]', [
                'matches' => '%s harus sama',
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $pass_hash_baru = $this->bcrypt->hash_password($pass);

                $simpanuser = [
                    'userid' => $iduser,
                    'usernama' => $namalengkap,
                    'useraktif' => 0,
                    'userpass' => $pass_hash_baru,
                    'usergrup' => $grup,
                    'usercreate' => date('Y-m-d H:i:s')
                ];
                $this->db->insert('nn_users', $simpanuser);
                $msg = [
                    'sukses' => 'User berhasil ditambahkan'
                ];
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Error !</strong> 
                                <p>' . validation_errors() . '</p>
                            </div>'
                ];
            }

            echo json_encode($msg);
        }
    }
    function updatedata()
    {
        if ($this->input->is_ajax_request() == true) {
            $iduser = $this->input->post('iduser', true);
            $namalengkap = $this->input->post('namalengkap', true);
            $grup = $this->input->post('grup', true);
            $passbaru = $this->input->post('passbaru', true);

            $pass_hash_baru = $this->bcrypt->hash_password($passbaru);

            $simpanuser = [
                'usernama' => $namalengkap,
                'userpass' => $pass_hash_baru,
                'usergrup' => $grup
            ];
            $this->db->where('userid', $iduser);
            $this->db->update('nn_users', $simpanuser);
            $msg = [
                'sukses' => 'User berhasil di Update'
            ];

            echo json_encode($msg);
        }
    }

    function hapus()
    {
        if ($this->input->is_ajax_request() == true) {
            $userid = $this->input->post('userid', true);

            $cekuser_aktif = $this->db->get_where('nn_users', ['userid' => $userid]);
            $row = $cekuser_aktif->row_array();
            $useraktif = $row['useraktif'];

            if ($useraktif == 1) {
                $msg = [
                    'error' => 'User tidak bisa dihapus, dikarenakan status sedang aktif'
                ];
            } else {
                $this->db->delete('nn_users', ['userid' => $userid]);
                $msg = ['sukses' => 'User berhasil dihapus'];
            }
            echo json_encode($msg);
        }
    }

    function ubahstatus()
    {
        if ($this->input->is_ajax_request() == true) {
            $userid = $this->input->post('userid', true);

            $cekuser_aktif = $this->db->get_where('nn_users', ['userid' => $userid]);
            $row = $cekuser_aktif->row_array();
            $useraktif = $row['useraktif'];

            if ($useraktif == 1) {
                $dataupdate = [
                    'useraktif' => 0
                ];
                $this->db->where('userid', $userid);
                $this->db->update('nn_users', $dataupdate);
                $msg = ['sukses' => 'User berhasil di Non.Aktifkan'];
            } else {
                $dataupdate = [
                    'useraktif' => 1
                ];
                $this->db->where('userid', $userid);
                $this->db->update('nn_users', $dataupdate);
                $msg = ['sukses' => 'User berhasil di Aktifkan'];
            }
            echo json_encode($msg);
        }
    }
}