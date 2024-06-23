<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group extends CI_Controller
{
    var $input;
    var $form_validation;
    var $layouts;
    var $Mgroup;

    public function __construct()
    {
        parent::__construct();
        check_logged_in();

        $this->load->model('users/Mgroup');

        $this->form_validation->set_message('required', errorRequired());
        $this->form_validation->set_message('is_unique', errorUnique());
        $this->form_validation->set_error_delimiters(errorDelimiter(), errorDelimiter_close());
    }
    public function index()
    {
        $data = [
            'title' => 'Grup User',
        ];
        $this->load->view('users/group/index', $data);
    }
    public function data()
    {
        $results = $this->Mgroup->get_all();
        $data = array();
        $no = $_POST['start'];
        foreach ($results as $result) {
            $edit = '<a href="javascript:void(0)" class="btn btn-sm btn-success edit" id="' . $result['id_group'] . '" title="Edit"><i class="fa fa-fw fa-edit"></i></a>';
            $destroy = '<a href="javascript:void(0)" class="btn btn-sm btn-danger destroy" id="' . $result['id_group'] . '" title="Hapus"><i class="fa fa-fw fa-trash-alt"></i></a>';
            $no++;
            $data[] = [
                'no' => $no . '.',
                'nama_group' => $result['nama_group'],
                'kode' => $result['kode_group'],
                'button' => $edit . '&nbsp;' . $destroy
            ];
        }
        $response = array(
            'draw' => $_POST['draw'],
            'recordsTotal' => $this->Mgroup->count_all(),
            'recordsFiltered' => $this->Mgroup->count_filtered(),
            'data' => $data
        );
        echo json_encode($response);
    }
    public function create()
    {
        $data = [
            'title' => 'Tambah Grup User',
            'post' => 'group-user/store',
        ];
        $this->layouts->modal_form('users/group/create', $data);
    }
    public function store()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Nama grup', 'trim|required');
        $this->form_validation->set_rules('kode', 'Kode grup', 'trim|required|callback_group_check_blank|is_unique[group_user.kode_group]');
        if ($this->form_validation->run() == TRUE) :
            $store = $this->Mgroup->store($post);
            if ($store) :
                $json = array(
                    'status' => 'success',
                    'message' => 'Data grup user berhasil disimpan'
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
            'title' => 'Edit Grup User',
            'post' => 'group-user/update',
            'data' => $this->Mgroup->get_by_id($id)
        ];
        $this->layouts->modal_form('users/group/edit', $data);
    }
    public function update()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Nama grup', 'trim|required');
        $this->form_validation->set_rules('kode', 'Kode grup', 'required|callback_group_check_blank|callback_group_check_duplicate[' . $post['id'] . ']');
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post(null, TRUE);
            $this->Mgroup->update($post);
            $json = array(
                'status' => 'success',
                'message' => 'Data grup user berhasil dirubah'
            );
        } else {
            $json['status'] = 'fail';
            foreach ($_POST as $key => $value) {
                $json['error'][$key] = form_error($key);
            }
        }
        echo json_encode($json);
    }
    public function group_check_blank($str)
    {
        $pattern = '/ /';
        $result = preg_match($pattern, $str);
        if ($result) {
            $this->form_validation->set_message('group_check_blank', '%s tidak boleh memiliki spasi');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public function group_check_duplicate($kode, $id)
    {
        $query = $this->Mgroup->get_by_kode($kode, $id);
        if ($query > 0) :
            $this->form_validation->set_message('group_check_duplicate', '%s sudah digunakan');
            return false;
        else :
            return true;
        endif;
    }
    public function destroy()
    {
        $id = $this->input->post('id', true);
        $destroy = $this->Mgroup->destroy($id);
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
    public function autocomplete()
    {
        $search = $this->input->get('search');
        $data = $this->Mgroup->autocomplete($search);
        $json = array();
        foreach ($data as $row) {
            $json[] = array(
                'id' => $row['id_group'],
                'text' => $row['nama_group']
            );
        }
        echo json_encode($json);
    }
}

/* End of file Group.php */
