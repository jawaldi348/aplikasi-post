<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth/Mlogin');
    }
    public function index()
    {
        $this->load->view('auth/login');
    }
    public function validasi_user()
    {
        $post = $this->input->post(null, TRUE);
        $check_user = $this->Mlogin->check_user($post);
        $this->form_validation->set_rules('username', 'Username', 'callback_username_check[' . $check_user->num_rows() . ']');
        $this->form_validation->set_rules('password', 'Password', 'callback_password_check[' . $post['username'] . ']');
        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run()) {
            $post = $this->input->post(null, TRUE);
            $data = $check_user->row_array();
            $bisnis = $this->common->get_bisnis();
            $user_data = [
                'masuk' => true,
                'userid' => $data['id_user'],
                'namalengkapuser' => $data['nama_user'],
                'username' => $data['username'],
                'fotouser' => $data['foto_user'],
                'idgrup' => $data['idgroup_user'],
                'idtoko' => $bisnis['idbisnis'],
                'logo' => $bisnis['logo'],
                'namatoko' => $bisnis['bisnis'],
                'namagrup' => 'Administrator'
            ];
            $this->session->set_userdata($user_data);
            // $user_data = array(
            //     'login_auth' => TRUE,
            //     'status_login' => 'success',
            //     'iduser' => $data['id_user']
            // );
            // $this->session->set_userdata('userData', $user_data);
            $resp = [
                'status' => true,
                'message' => 'Anda berhasil login',
            ];
        } else {
            $resp = array(
                'status' => false,
                'error' => [
                    'username' => form_error('username'),
                    'password' => form_error('password')
                ]
            );
        }
        echo json_encode($resp);
    }
    public function username_check($username, $recordCount)
    {
        if ($username == null) {
            $this->form_validation->set_message('username_check', 'Username tidak boleh kosong.');
            return false;
        } else if ($recordCount == 0) {
            $this->form_validation->set_message('username_check', 'Username yang anda masukkan salah.');
            return false;
        } else {
            return true;
        }
    }
    public function password_check($pass, $username)
    {
        if ($pass == '') {
            $this->form_validation->set_message('password_check', 'Password tidak boleh kosong.');
            return false;
        } else {
            $data = $this->Mlogin->check_pass($username);
            if ($data != null) :
                $password = $data['password'];
                if (password_verify($pass, $password)) :
                    return true;
                else :
                    $this->form_validation->set_message('password_check', 'Password yang anda masukkan salah.');
                    return false;
                endif;
            else :
                $this->form_validation->set_message('password_check', 'Password yang anda masukkan salah.');
                return false;
            endif;
        }
    }
}

/* End of file Login.php */
