<?php
class Kaskecil extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library([
                'form_validation'
            ]);
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {

        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-comments-dollar"></i> Manajemen Kas Kecil',
            'isi' => $this->load->view('admin/kaskecil/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function ambildata()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/Modelkaskecil', 'kas');
            $list = $this->kas->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();
                $row[] = "<input type='checkbox' class='check-item' name='id[]' value='" . $field->id . "'>";
                $row[] = $no;
                $row[] = number_format($field->jmlkas, 0, ',', '.');
                $row[] = date('d-m-Y', strtotime($field->periodeawal));
                $row[] = date('d-m-Y', strtotime($field->periodeakhir));
                if ($field->statuskunci == '0') {
                    $row[] = '<span class="badge badge-danger"><i class="fa fa-ban"></i></span>';
                    $tombolhapus = "<button onclick=\"hapus('" . $field->id . "')\" type=\"button\" class=\"btn btn-sm btn-outline-danger\"><i class=\"fa fa-trash-alt\"></i></button>";
                    $tombolaktif = "<button onclick=\"aktif('" . $field->id . "')\" type=\"button\" class=\"btn btn-sm btn-outline-info\">Aktifkan</button>";
                } else {
                    $row[] = '<span class="badge badge-success"><i class="fa fa-key"></i></span>';
                    $tombolhapus = "<button onclick=\"hapus('" . $field->id . "')\" type=\"button\" class=\"btn btn-sm btn-outline-danger\"><i class=\"fa fa-trash-alt\"></i></button>";
                    $tombolaktif = "<button onclick=\"aktif('" . $field->id . "')\" type=\"button\" class=\"btn btn-sm btn-outline-info\">Non Aktifkan</button>";
                }
                $row[] = $tombolhapus . ' ' . $tombolaktif;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->kas->count_all(),
                "recordsFiltered" => $this->kas->count_filtered(),
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
        if ($this->input->is_ajax_request() == true) {
            $this->load->view('admin/kaskecil/modalformtambah');
        }
    }

    public function simpandata()
    {
        if ($this->input->is_ajax_request() == true) {
            $datasimpan = [
                'jmlkas' => str_replace(",", "", $this->input->post('jml', true)),
                'periodeawal' => $this->input->post('awal', true),
                'periodeakhir' => $this->input->post('akhir', true),
            ];

            $this->db->insert('kas_kecil', $datasimpan);

            $msg = ['sukses' => 'Jumlah Kas berhasil ditambahkan'];
            echo json_encode($msg);
        }
    }

    function ubahstatus()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            $cek = $this->db->get_where('kas_kecil', ['id' => $id]);
            $row = $cek->row_array();

            $statuskunci = $row['statuskunci'];

            if ($statuskunci == '1') {
                $update = [
                    'statuskunci' => 0
                ];
                $this->db->where('id', $id);
                $this->db->update('kas_kecil', $update);

                $msg = [
                    'sukses' => 'Berhasil di Non.Aktifkan'
                ];
            } else {
                $update = [
                    'statuskunci' => 0
                ];
                $this->db->update('kas_kecil', $update);

                $updatelagi = [
                    'statuskunci' => 1
                ];
                $this->db->where('id', $id);
                $this->db->update('kas_kecil', $updatelagi);

                $msg = [
                    'sukses' => 'Berhasil di Aktifkan'
                ];
            }
            echo json_encode($msg);
        }
    }

    function hapus()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            $cek = $this->db->get_where('kas_kecil', ['id' => $id]);
            $row = $cek->row_array();

            if ($row['statuskunci'] == '0') {
                $this->db->delete('kas_kecil', ['id' => $id]);
                $msg = [
                    'sukses' => 'Berhasil di hapus'
                ];
            } else {
                $msg = [
                    'error' => 'Tidak bisa dihapus, dikarenakan statusnya terkunci'
                ];
            }
            echo json_encode($msg);
        }
    }
}