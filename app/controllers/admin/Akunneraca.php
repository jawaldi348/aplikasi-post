<?php
class Akunneraca extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->aksesgrup = $this->session->userdata('idgrup');
        if ($this->session->userdata('masuk') == true && ($this->aksesgrup == 1)) {
            $this->load->library(['form_validation']);
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Manajeman Data Akun Neraca',
            'isi' => $this->load->view('admin/akunneraca/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function detail($kode)
    {
        $cekData = $this->db->get_where('akun_neraca', ['sha1(akunkode)' => $kode]);

        if ($cekData->num_rows() > 0) {
            $r = $cekData->row_array();
            $data = [
                'kode' => $r['akunkode'],
                'nama' => $r['akunnama'],
            ];
            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-tasks"></i> Detail Akun <strong>' . $r['akunkode'] . '</strong>',
                'isi' => $this->load->view('admin/akunneraca/detailakun', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        }
    }

    function tampiDataDetail()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kodeakun');

            $query =  $this->db->query("SELECT id,tgl,kodeakun,
            CASE a.`jenis` WHEN 'D' THEN jumlah ELSE 0 END AS debit,
            CASE a.`jenis` WHEN 'K' THEN jumlah ELSE 0 END AS kredit
            FROM akun_neraca_detail a WHERE a.`kodeakun` = '$kode' ORDER BY tgl ASC");
            $data = [
                'datadetail' => $query,
                'kodeakun' => $kode
            ];

            $msg = [
                'data' => $this->load->view('admin/akunneraca/tampildetaildata', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function tambahakun()
    {
        if ($this->input->is_ajax_request()) {
            $json = [
                'data' => $this->load->view('admin/akunneraca/modaltambahakun', '', true)
            ];
            echo json_encode($json);
        }
    }

    function tambahDetail()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode');
            $data = [
                'kode' => $kode
            ];
            $json = [
                'data' => $this->load->view('admin/akunneraca/modaltambahdetail', $data, true)
            ];
            echo json_encode($json);
        }
    }

    function tampildata()
    {
        if ($this->input->is_ajax_request()) {
            $data = [
                'tampildata' => $this->db->get('akun_neraca')
            ];
            $json = [
                'data' => $this->load->view('admin/akunneraca/tampildata', $data, true)
            ];
            echo json_encode($json);
        }
    }

    function simpanakun()
    {
        if ($this->input->is_ajax_request()) {
            $kodeakun1 = $this->input->post('kodeakun1');
            $kodeakun2 = $this->input->post('kodeakun2');
            $namaakun = $this->input->post('namaakun');

            $this->form_validation->set_rules('kodeakun1', 'Kode Akun', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('kodeakun2', 'Kode Akun', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('namaakun', 'Nama Akun', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $akunkode = $kodeakun1 . '-' . $kodeakun2;
                $cekAkun = $this->db->get_where('akun_neraca', ['akunkode' => $akunkode]);
                if ($cekAkun->num_rows() > 0) {
                    $json = [
                        'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Maaf !</strong><br>
                                        Kode Akun Sudah ada, silahkan coba dengan kode yang lain.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>'
                    ];
                } else {
                    $simpandata = [
                        'akunkode' => $akunkode,
                        'akunnama' => $namaakun,
                        'akunsaldo' => 0
                    ];
                    $this->db->insert('akun_neraca', $simpandata);
                    $json = [
                        'sukses' => 'Akun berhasil ditambahkan'
                    ];
                }
            } else {
                $json = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Maaf !</strong><br>
                                    ' . validation_errors() . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>'
                ];
            }
            echo json_encode($json);
        }
    }
    function simpandetail()
    {
        if ($this->input->is_ajax_request()) {
            $kodeakun = $this->input->post('kodeakun');
            $tanggal = $this->input->post('tanggal');
            $jenis = $this->input->post('jenis');
            $jml = str_replace(",", "", $this->input->post('jml', true));;

            $this->form_validation->set_rules('tanggal', 'Inputan Tanggal', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('jenis', 'Jenis Transaksi', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('jml', 'Inputan Jumlah', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $simpandata = [
                    'tgl' => $tanggal,
                    'kodeakun' => $kodeakun,
                    'jenis' => $jenis,
                    'jumlah' => $jml
                ];
                $this->db->insert('akun_neraca_detail', $simpandata);
                $json = [
                    'sukses' => 'Transaksi berhasil ditambahkan'
                ];
            } else {
                $json = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Maaf !</strong><br>
                                    ' . validation_errors() . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>'
                ];
            }
            echo json_encode($json);
        }
    }

    function hapusAkun()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);

            $this->db->delete('akun_neraca_detail', ['kodeakun' => $kode]);
            $this->db->delete('akun_neraca', ['akunkode' => $kode]);

            $json = [
                'sukses' => 'Data Akun Berhasil dihapus'
            ];
            echo json_encode($json);
        }
    }

    function hapusDetail()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);

            $hapusdata = $this->db->delete('akun_neraca_detail', ['id' => $id]);
            if ($hapusdata) {
                $json = [
                    'sukses' => 'Data berhasil dihapus'
                ];
                echo json_encode($json);
            }
        }
    }
}