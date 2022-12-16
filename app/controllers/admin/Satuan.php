<?php
class Satuan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library(array(
                'form_validation'
            ));
            $this->load->model('admin/Modelsatuan', 'satuan');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-fw fa-file-alt"></i> Manajemen Data Satuan',
            'isi' => $this->load->view('admin/satuan/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function tampildata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->load->view('admin/satuan/tampildata');
        }
    }

    public function ambildata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $list = $this->satuan->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {

                $tombolhapus = "<button type=\"button\" title=\"Hapus Data\" class=\"btn btn-sm btn-danger\" onclick=\"hapus($field->satid)\">
                    <i class=\"fa fa-fw fa-trash-alt\"></i>
                </button>";

                $tomboledit = "<button type=\"button\" title=\"Edit Data\" class=\"btn btn-sm btn-info\" onclick=\"edit($field->satid)\">
                <i class=\"fa fa-fw fa-tags\"></i>
            </button>";
                $no++;
                $row = array();
                $row[] = "<input type='checkbox' class='check-item' name='id[]' value='" . $field->satid . "'>";
                $row[] = $no;
                $row[] = $field->satnama;
                $row[] = $field->satket;
                if ($field->satid == 0) {
                    $row[] = "";
                } else {
                    $row[] = $tombolhapus . '&nbsp;' . $tomboledit;
                }
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->satuan->count_all(),
                "recordsFiltered" => $this->satuan->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf tidak dapat diakses');
        }
    }

    public function tambah()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->load->view('admin/satuan/formtambah');
        } else {
            exit('Maaf tidak dapat diakses');
        }
    }

    public function edit()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', true);
            $ambildata = $this->satuan->ambildata($id);
            if ($ambildata->num_rows() > 0) {
                $r = $ambildata->row_array();
                $data = [
                    'satid' => $id,
                    'satnama' => $r['satnama'],
                    'satket' => $r['satket']
                ];
                $this->load->view('admin/satuan/formedit', $data);
            } else {
                redirect('admin/satuan/index', 'refresh');
            }
        } else {
            exit('Maaf tidak dapat diakses');
        }
    }

    public function simpandata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nama = $this->input->post('nama', true);
            $ket = $this->input->post('ket', true);

            $this->form_validation->set_rules('nama', 'Nama Satuan', 'trim|required', array(
                'required' => '%s tidak boleh kosong'
            ));


            if ($this->form_validation->run() == TRUE) {
                $this->satuan->simpandata($nama, $ket);

                $msg = [
                    'sukses' => 'Data satuan berhasil tersimpan'
                ];
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Error</strong>' . validation_errors() . '
                </div>'
                ];
            }
            echo json_encode($msg);
        } else {
            exit('Maaf tidak dapat diakses');
        }
    }

    public function hapus()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', true);
            $this->satuan->hapus($id);
            $msg = ['sukses' => 'Satuan berhasil dihapus'];
            echo json_encode($msg);
        } else {
            exit('Maaf tidak dapat diakses');
        }
    }

    public function updatedata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', true);
            $nama = $this->input->post('nama', true);
            $ket = $this->input->post('ket', true);

            $updatedata = $this->satuan->updatedata($id, $nama, $ket);

            if ($updatedata) {
                $msg = [
                    'sukses' => 'Data berhasil terupdate'
                ];
                echo json_encode($msg);
            }
        }
    }

    public function delete_multi()
    {
        $id = $this->input->post('id', true);

        for ($i = 0; $i < count($id); $i++) {
            $this->db->delete('satuan', ['satid' => $id[$i]]);
        }

        $pesan = [
            'msg' => '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            ' . count($id) . ' data satuan berhasil terhapus
        </div>'
        ];
        $this->session->set_flashdata($pesan);

        redirect('admin/satuan/index', 'refresh');
    }
}