<?php
class Satuan extends CI_Controller
{
    public $layouts;
    public $input;
    public $form_validation;
    public $Msatuan;

    public function __construct()
    {
        parent::__construct();
        check_logged_in();

        $this->load->model('produk/Msatuan');

        $this->form_validation->set_message('required', errorRequired());
        $this->form_validation->set_error_delimiters(errorDelimiter(), errorDelimiter_close());
    }
    public function index()
    {
        $data = [
            'title' => 'Satuan',
        ];
        $this->load->view('produk/satuan/index', $data);
    }
    public function data()
    {
        $results = $this->Msatuan->fetch_all();
        $data = array();
        foreach ($results as $result) {
            $edit = '<a href="javascript:void(0)" class="btn btn-sm btn-success edit" id="' . $result->id_satuan . '" title="Edit"><i class="fa fa-fw fa-edit"></i></a>';
            $destroy = '<a href="javascript:void(0)" class="btn btn-sm btn-danger destroy" id="' . $result->id_satuan . '" title="Hapus"><i class="fa fa-fw fa-trash-alt"></i></a>';
            $data[] = [
                'nama_satuan' => $result->nama_satuan,
                'detail' => $result->detail_satuan,
                'button' => $edit . '&nbsp;' . $destroy
            ];
        }
        $response = array(
            'draw' => $_GET['draw'],
            'recordsTotal' => $this->Msatuan->count_all(),
            'recordsFiltered' => $this->Msatuan->count_filtered(),
            'data' => $data,
        );
        echo json_encode($response);
    }
    public function create()
    {
        $data = [
            'title' => 'Tambah Satuan',
            'post' => 'satuan/store',
        ];
        $this->layouts->modal_form('produk/satuan/create', $data);
    }
    public function store()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Nama satuan', 'trim|required');
        if ($this->form_validation->run() == TRUE) :
            $store = $this->Msatuan->store($post);
            if ($store) :
                $json = array(
                    'status' => 'success',
                    'message' => 'Data satuan telah disimpan'
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
            'title' => 'Edit Satuan',
            'post' => 'satuan/update',
            'data' => $this->Msatuan->get_by_id($id)
        ];
        $this->layouts->modal_form('produk/satuan/edit', $data);
    }
    public function update()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Nama satuan', 'trim|required');
        if ($this->form_validation->run() == TRUE) :
            $update = $this->Msatuan->update($post);
            if ($update) :
                $json = array(
                    'status' => 'success',
                    'message' => 'Data satuan telah dirubah'
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
        $destroy = $this->Msatuan->destroy($id);
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
    public function store_quick()
    {
        $post = $this->input->post(null, TRUE);
        $idsatuan = $this->Msatuan->store_quick($post);
        $data = $this->Msatuan->get_by_id($idsatuan);
        $json = [
            'status' => true,
            'data' => $data,
            'message' => 'Data satuan berhasil disimpan'
        ];
        echo json_encode($json);
    }
    public function autocomplete()
    {
        $search = $this->input->get('search');
        $data = $this->Msatuan->autocomplete($search);
        $json = array();
        foreach ($data as $row) {
            $json[] = array(
                'id' => $row['id_satuan'],
                'text' => $row['nama_satuan']
            );
        }
        echo json_encode($json);
    }
}
