<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends CI_Controller
{
    public $layouts;
    public $input;
    public $form_validation;
    public $Mmember;

    public function __construct()
    {
        parent::__construct();
        check_logged_in();

        $this->load->model('kontak/Mmember');

        $this->form_validation->set_message('required', errorRequired());
        $this->form_validation->set_message('unique', errorUnique());
        $this->form_validation->set_error_delimiters(errorDelimiter(), errorDelimiter_close());
    }
    public function index()
    {
        $data = [
            'title' => 'Member',
        ];
        $this->load->view('kontak/member/index', $data);
    }
    public function data()
    {
        $results = $this->Mmember->fetch_all();
        $data = array();
        $no = 0;
        foreach ($results as $result) {
            $no++;
            $edit = '<a href="javascript:void(0)" class="btn btn-sm btn-success edit" id="' . $result->kode_member . '" title="Edit"><i class="fa fa-fw fa-edit"></i></a>';
            $destroy = '<a href="javascript:void(0)" class="btn btn-sm btn-danger destroy" id="' . $result->kode_member . '" title="Hapus"><i class="fa fa-fw fa-trash-alt"></i></a>';
            $detail = '<a href="' . site_url('member/detail/' . $result->kode_member) . '" class="btn btn-sm btn-outline-primary" title="Detail"><i class="fa fa-id-card"></i></a>';
            $data[] = [
                'nomor' => $no,
                'kode' => $result->kode_member,
                'nama_member' => $result->nama_member,
                'jenkel' => $result->jenkel_member == 'L' ? 'Pria' : 'Wanita',
                'alamat' => $result->alamat_member,
                'telp' => $result->telp_member,
                'button' => $edit . '&nbsp;' . $destroy . '&nbsp;' . $detail
            ];
        }
        $response = array(
            'draw' => $_GET['draw'],
            'recordsTotal' => $this->Mmember->count_all(),
            'recordsFiltered' => $this->Mmember->count_filtered(),
            'data' => $data,
        );
        echo json_encode($response);
    }
    public function create()
    {
        $data = [
            'title' => 'Tambah Member',
            'post' => 'member/store',
            'kodeMember' => rand(1, 999999)
        ];
        $this->layouts->modal_form('kontak/member/create', $data);
    }
    public function store()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('kode', 'Kode member', 'trim|required|is_unique[kontak_member.kode_member]');
        $this->form_validation->set_rules('nama', 'Nama member', 'trim|required');
        if ($this->form_validation->run() == TRUE) :
            $return = $this->Mmember->store($post);
            if ($return) :
                $json = array(
                    'status' => 'success',
                    'message' => 'Data member telah disimpan'
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
    public function show()
    {
        $id = $this->input->post('id');
        $data = [
            'title' => 'Edit Member',
            'post' => 'member/update',
            'data' => $this->Mmember->get_by_id($id)
        ];
        $this->layouts->modal_form('kontak/member/edit', $data);
    }
    public function update()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Nama member', 'trim|required');
        if ($this->form_validation->run() == TRUE) :
            $return = $this->Mmember->update($post);
            if ($return) :
                $json = array(
                    'status' => 'success',
                    'message' => 'Data member telah dirubah'
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
    public function destroy()
    {
        $id = $this->input->post('id', true);
        $destroy = $this->Mmember->destroy($id);
        if ($destroy) {
            $json = array(
                'status' => true,
                'message' => successDestroy()
            );
        } else {
            $json = array(
                'status' => false,
                'message' => errorDestroy()
            );
        }
        echo json_encode($json);
    }
    public function detail($id)
    {
        $data = [
            'title' => 'Member',
            'data' => $this->Mmember->get_by_id($id)
        ];
        $this->load->view('kontak/member/detail', $data);
    }
    public function cetak_kartu($id)
    {
        $data = [
            'title' => 'Member',
            'data' => $this->Mmember->get_by_id($id)
        ];
        $this->load->view('kontak/member/kartu', $data);
    }
}

/* End of file Member.php */
