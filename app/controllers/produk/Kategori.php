<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori extends CI_Controller
{
    public $layouts;
    public $input;
    public $form_validation;
    public $Mkategori;

    public function __construct()
    {
        parent::__construct();
        check_logged_in();

        $this->load->model('produk/Mkategori');

        $this->form_validation->set_message('required', errorRequired());
        $this->form_validation->set_error_delimiters(errorDelimiter(), errorDelimiter_close());
    }
    public function index()
    {
        $data = [
            'title' => 'Kategori',
        ];
        $this->load->view('produk/kategori/index', $data);
    }
    public function data()
    {
        $results = $this->Mkategori->fetch_all();
        $data = array();
        foreach ($results as $result) {
            $edit = '<a href="javascript:void(0)" class="btn btn-sm btn-success edit" id="' . $result->id_kategori . '" title="Edit"><i class="fa fa-fw fa-edit"></i></a>';
            $destroy = '<a href="javascript:void(0)" class="btn btn-sm btn-danger destroy" id="' . $result->id_kategori . '" title="Hapus"><i class="fa fa-fw fa-trash-alt"></i></a>';
            $data[] = [
                'nama_kategori' => $result->nama_kategori,
                'button' => $edit . '&nbsp;' . $destroy
            ];
        }
        $response = array(
            'draw' => $_GET['draw'],
            'recordsTotal' => $this->Mkategori->count_all(),
            'recordsFiltered' => $this->Mkategori->count_filtered(),
            'data' => $data,
        );
        echo json_encode($response);
    }
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
            'post' => 'kategori/store',
        ];
        $this->layouts->modal_form('produk/kategori/create', $data);
    }
    public function store()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Kategori', 'trim|required');
        if ($this->form_validation->run() == TRUE) :
            $return = $this->Mkategori->store($post);
            if ($return) :
                $json = array(
                    'status' => 'success',
                    'message' => 'Data kategori telah disimpan'
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
            'title' => 'Edit Kategori',
            'post' => 'kategori/update',
            'data' => $this->Mkategori->get_by_id($id)
        ];
        $this->layouts->modal_form('produk/kategori/edit', $data);
    }
    public function update()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Kategori', 'trim|required');
        if ($this->form_validation->run() == TRUE) :
            $return = $this->Mkategori->update($post);
            if ($return) :
                $json = array(
                    'status' => 'success',
                    'message' => 'Data kategori telah dirubah'
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
        $destroy = $this->Mkategori->destroy($id);
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
    public function autocomplete()
    {
        $search = $this->input->get('search');
        $data = $this->Mkategori->autocomplete($search);
        $json = array();
        foreach ($data as $row) {
            $json[] = array(
                'id' => $row['id_kategori'],
                'text' => $row['nama_kategori']
            );
        }
        echo json_encode($json);
    }
}

/* End of file Kategori.php */
