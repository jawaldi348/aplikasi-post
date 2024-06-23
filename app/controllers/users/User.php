<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    // var $db;
    var $input;
    var $form_validation;
    var $layouts;
    var $Muser;

    public function __construct()
    {
        parent::__construct();
        check_logged_in();

        $this->load->model('users/Muser');

        $this->form_validation->set_message('required', errorRequired());
        $this->form_validation->set_message('is_unique', errorUnique());
        $this->form_validation->set_error_delimiters(errorDelimiter(), errorDelimiter_close());
    }
    public function index()
    {
        $data = [
            'title' => 'Manajemen User',
        ];
        $this->load->view('users/user/index', $data);
    }
    public function data()
    {
        $results = $this->Muser->get_all();
        $data = array();
        $no = $_POST['start'];
        foreach ($results as $result) {
            if ($result['aktif_user'] == '1') :
                $status = '<i class="fa fa-check-circle" style="color:green;" title="Aktif"></i>';
            else :
                $status = '<i class="fa fa-ban" style="color:red;" title="Tidak Aktif"></i>';
            endif;

            $edit = '<a href="javascript:void(0)" class="btn btn-sm btn-success edit" id="' . $result['id_user'] . '" title="Edit"><i class="fa fa-fw fa-edit"></i></a>';
            $destroy = '<a href="javascript:void(0)" class="btn btn-sm btn-danger destroy" id="' . $result['id_user'] . '" title="Hapus"><i class="fa fa-fw fa-trash-alt"></i></a>';
            $no++;
            $data[] = [
                'no' => $no . '.',
                'nama_user' => $result['nama_user'],
                'username' => $result['username'],
                'group' => $result['nama_group'],
                'status' => $status,
                'button' => $edit . '&nbsp;' . $destroy
            ];
        }
        $response = array(
            'draw' => $_POST['draw'],
            'recordsTotal' => $this->Muser->count_all(),
            'recordsFiltered' => $this->Muser->count_filtered(),
            'data' => $data
        );
        echo json_encode($response);
    }
    public function create()
    {
        $data = [
            'title' => 'Tambah User',
            'post' => 'users/store',
        ];
        $this->layouts->modal_form('users/user/create', $data);
    }
    public function store()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'required|callback_username_check_blank|is_unique[users.username]');
        $this->form_validation->set_rules(
            'password',
            'Password',
            'required|min_length[8]',
            ['min_length' => 'Minimal panjang password 8 karakter']
        );
        $this->form_validation->set_rules('group', 'Grup User', 'trim|required');
        if ($this->form_validation->run() == TRUE) :
            $store = $this->Muser->store($post);
            if ($store) :
                $json = array(
                    'status' => 'success',
                    'message' => 'Data user berhasil disimpan'
                );
            endif;
        else :
            $json['status'] = 'fail';
            foreach ($post as $key => $value) {
                $json['error'][$key] = form_error($key);
            }
        endif;
        echo json_encode($json);
    }
    public function edit()
    {
        $id = $this->input->post('id');
        $data = [
            'title' => 'Edit User',
            'post' => 'users/update',
            'data' => $this->Muser->get_by_id($id)
        ];
        $this->layouts->modal_form('users/user/edit', $data);
    }
    public function update()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Nama lengkap', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|callback_username_check_blank|callback_username_check_duplicate[' . $post['id'] . ']');
        $this->form_validation->set_rules('group', 'Grup user', 'required');
        if ($post['password'] != '') :
            $this->form_validation->set_rules(
                'password',
                'Password',
                'required|min_length[8]',
                ['min_length' => 'Minimal panjang password 8 karakter']
            );
        endif;
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post(null, TRUE);
            $this->Muser->update($post);
            $json = array(
                'status' => 'success',
                'message' => 'Data user berhasil dirubah'
            );
        } else {
            $json['status'] = 'fail';
            foreach ($_POST as $key => $value) {
                $json['error'][$key] = form_error($key);
            }
        }
        echo json_encode($json);
    }
    public function username_check_blank($str)
    {
        $pattern = '/ /';
        $result = preg_match($pattern, $str);
        if ($result) {
            $this->form_validation->set_message('username_check_blank', '%s tidak boleh memiliki spasi');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public function username_check_duplicate($username, $id)
    {
        $query = $this->Muser->get_by_username($username, $id);
        if ($query > 0) :
            $this->form_validation->set_message('username_check_duplicate', '%s sudah digunakan');
            return false;
        else :
            return true;
        endif;
    }
    public function destroy()
    {
        $id = $this->input->post('id', true);
        $destroy = $this->Muser->destroy($id);
        if ($destroy) {
            $json = array(
                'status' => 'success',
                'message' => successDestroy()
            );
        } else {
            $json = array(
                'status' => 'fail',
                'message' => errorDestroy()
            );
        }
        echo json_encode($json);
    }
}

/* End of file User.php */
