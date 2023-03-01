<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemasok extends CI_Controller
{
    public $layouts;
    public $input;
    public $form_validation;
    public $Mpemasok;

    public function __construct()
    {
        parent::__construct();
        check_logged_in();

        $this->load->model('kontak/Mpemasok');

        $this->form_validation->set_message('required', errorRequired());
        $this->form_validation->set_error_delimiters(errorDelimiter(), errorDelimiter_close());
    }
    public function index()
    {
        $data = [
            'title' => 'Pemasok',
        ];
        $this->load->view('kontak/pemasok/index', $data);
    }
    public function data()
    {
        $results = $this->Mpemasok->fetch_all();
        $data = array();
        $no = 0;
        foreach ($results as $result) {
            $no++;
            $edit = '<a href="javascript:void(0)" class="btn btn-sm btn-success edit" id="' . $result->id_pemasok . '" title="Edit"><i class="fa fa-fw fa-edit"></i></a>';
            $destroy = '<a href="javascript:void(0)" class="btn btn-sm btn-danger destroy" id="' . $result->id_pemasok . '" title="Hapus"><i class="fa fa-fw fa-trash-alt"></i></a>';
            $data[] = [
                'nomor' => $no,
                'nama_pemasok' => $result->nama_pemasok,
                'alamat' => $result->alamat_pemasok,
                'telp' => $result->telp_pemasok,
                'button' => $edit . '&nbsp;' . $destroy
            ];
        }
        $response = array(
            'draw' => $_GET['draw'],
            'recordsTotal' => $this->Mpemasok->count_all(),
            'recordsFiltered' => $this->Mpemasok->count_filtered(),
            'data' => $data,
        );
        echo json_encode($response);
    }
    public function create()
    {
        $data = [
            'title' => 'Tambah Pemasok',
            'post' => 'pemasok/store',
        ];
        $this->layouts->modal_form('kontak/pemasok/create', $data);
    }
    public function store()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Nama pemasok', 'trim|required');
        if ($this->form_validation->run() == TRUE) :
            $return = $this->Mpemasok->store($post);
            if ($return) :
                $json = array(
                    'status' => 'success',
                    'message' => 'Data pemasok telah disimpan'
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
            'title' => 'Edit Pemasok',
            'post' => 'pemasok/update',
            'data' => $this->Mpemasok->get_by_id($id)
        ];
        $this->layouts->modal_form('kontak/pemasok/edit', $data);
    }
    public function update()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Nama pemasok', 'trim|required');
        if ($this->form_validation->run() == TRUE) :
            $return = $this->Mpemasok->update($post);
            if ($return) :
                $json = array(
                    'status' => 'success',
                    'message' => 'Data pemasok telah dirubah'
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
        $destroy = $this->Mpemasok->destroy($id);
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
}

/* End of file Pemasok.php */
