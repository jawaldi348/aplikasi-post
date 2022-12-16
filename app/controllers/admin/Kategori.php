<?php
class Kategori extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library(array(
                'form_validation'
            ));
            $this->load->model('admin/Modelkategori', 'kategori');
            return true;
        } else {
            redirect('auth/logout');
        }
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-fw fa-file-alt"></i> Manajemen Data Kategori',
            'isi' => $this->load->view('admin/kategori/index', '', true)

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
            $list = $this->kategori->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $tombolhapus = "<button type=\"button\" title=\"Hapus Data\" class=\"btn btn-sm btn-danger\" onclick=\"hapus($field->katid)\">
                    <i class=\"fa fa-fw fa-trash-alt\"></i>
                </button>";

                $tomboledit = "<button type=\"button\" title=\"Edit Data\" class=\"btn btn-sm btn-info\" onclick=\"edit($field->katid)\">
                <i class=\"fa fa-fw fa-tags\"></i>
            </button>";
                $no++;
                $row = array();
                if ($field->katid == 1) {
                    $row[] = "";
                } else {
                    $row[] = "<input type='checkbox' class='check-item' name='id[]' value='" . $field->katid . "'>";
                }

                $row[] = $no;
                $row[] = $field->katnama;
                if ($field->katid == 1) {
                    $row[] = "";
                } else {
                    $row[] = $tombolhapus . '&nbsp;' . $tomboledit;
                }
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->kategori->count_all(),
                "recordsFiltered" => $this->kategori->count_filtered(),
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
            $this->load->view('admin/kategori/formtambah');
        } else {
            exit('Maaf tidak dapat diakses');
        }
    }

    public function edit()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', true);
            $ambildata = $this->kategori->ambildata($id);
            if ($ambildata->num_rows() > 0) {
                $r = $ambildata->row_array();
                $data = [
                    'katid' => $id,
                    'katnama' => $r['katnama'],
                ];
                $this->load->view('admin/kategori/formedit', $data);
            } else {
                redirect('admin/kategori/index', 'refresh');
            }
        } else {
            exit('Maaf tidak dapat diakses');
        }
    }

    public function simpandata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nama = $this->input->post('nama', true);

            $this->form_validation->set_rules('nama', 'Nama Kategori', 'trim|required', array(
                'required' => '%s tidak boleh kosong'
            ));


            if ($this->form_validation->run() == TRUE) {
                $this->kategori->simpandata($nama);

                $msg = [
                    'sukses' => 'Data kategori berhasil tersimpan'
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
            $this->kategori->hapus($id);
            $msg = ['sukses' => 'Kategori berhasil dihapus'];
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

            $updatedata = $this->kategori->updatedata($id, $nama);

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
            $this->db->delete('kategori', ['katid' => $id[$i]]);
        }

        $pesan = [
            'msg' => '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            ' . count($id) . ' data kategori berhasil terhapus
        </div>'
        ];
        $this->session->set_flashdata($pesan);

        redirect('admin/kategori/index', 'refresh');
    }
}