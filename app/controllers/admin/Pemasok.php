<?php
class Pemasok extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library(array(
                'form_validation'
            ));
            $this->load->model('admin/Modelpemasok', 'pemasok');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-fw fa-truck-moving"></i> Manajemen Data Pemasok',
            'isi' => $this->load->view('admin/pemasok/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function ambildata()
    {
        if ($this->input->is_ajax_request() == true) {
            $list = $this->pemasok->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $tombolhapus = "<button type=\"button\" title=\"Hapus Data\" class=\"btn btn-sm btn-danger\" onclick=\"hapus($field->id)\">
                        <i class=\"fa fa-fw fa-trash-alt\"></i>
                    </button>";

                $tomboledit = "<button type=\"button\" title=\"Edit Data\" class=\"btn btn-sm btn-info\" onclick=\"edit($field->id)\">
                    <i class=\"fa fa-fw fa-tags\"></i>
                </button>";
                $no++;
                $row = array();
                if ($field->id == 1) {
                    $row[] = "";
                } else {
                    $row[] = "<input type='checkbox' class='check-item' name='id[]' value='" . $field->id . "'>";
                }

                $row[] = $no;
                $row[] = $field->nama;
                $row[] = $field->alamat;
                $row[] = $field->telp;
                if ($field->id == 1) {
                    $row[] = "";
                } else {
                    $row[] = $tombolhapus . '&nbsp;' . $tomboledit;
                }
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->pemasok->count_all(),
                "recordsFiltered" => $this->pemasok->count_filtered(),
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
        if ($this->input->is_ajax_request() == TRUE) {
            $this->load->view('admin/pemasok/formtambah');
        } else {
            exit('Maaf tidak dapat diakses');
        }
    }

    public function edit()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', true);

            //ambildata
            $ambildata = $this->pemasok->ambildata($id);
            if ($ambildata->num_rows() > 0) {
                $row = $ambildata->row_array();
                $data = [
                    'id' => $id,
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'],
                    'telp' => $row['telp']
                ];

                $this->load->view('admin/pemasok/formedit', $data);
            }
        } else {
            exit('Maaf tidak dapat diakses');
        }
    }

    public function simpandata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nama = $this->input->post('nama', true);
            $alamat = $this->input->post('alamat', true);
            $telp = $this->input->post('telp', true);

            $this->form_validation->set_rules('nama', 'Nama Pemasok', 'trim|required', [
                'required' => 'Setidaknya %s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $this->pemasok->simpandata($nama, $alamat, $telp);

                // ambil data terakhir
                $dataakhir = $this->pemasok->ambildataterakhir();
                $row = $dataakhir->row_array();

                $msg = [
                    'sukses' => "Data dengan nama pemasok : $nama berhasil tersimpan",
                    'idpemasok' => $row['id'],
                    'namapemasok' => $row['nama']
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

    public function updatedata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $nama = $this->input->post('nama', true);
            $alamat = $this->input->post('alamat', true);
            $telp = $this->input->post('telp', true);
            $id = $this->input->post('id', true);

            $this->form_validation->set_rules('nama', 'Nama Pemasok', 'trim|required', [
                'required' => 'Setidaknya %s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $this->pemasok->updatedata($nama, $alamat, $telp, $id);

                $msg = [
                    'sukses' => "Data pemasok berhasil diupdate"
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

            $this->pemasok->hapus($id);

            $msg = [
                'sukses' => "Data berhasil dihapus"
            ];
            echo json_encode($msg);
        }
    }

    public function delete_multi()
    {
        $id = $this->input->post('id', true);

        for ($i = 0; $i < count($id); $i++) {
            $this->db->delete('pemasok', ['id' => $id[$i]]);
        }

        $pesan = [
            'msg' => '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            ' . count($id) . ' data pemasok berhasil terhapus
        </div>'
        ];
        $this->session->set_flashdata($pesan);

        redirect('admin/pemasok/index', 'refresh');
    }
}