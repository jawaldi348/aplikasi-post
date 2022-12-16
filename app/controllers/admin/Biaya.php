<?php
class Biaya extends CI_Controller
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
        $data = [
            'dataakun' => $this->db->query("SELECT * FROM neraca_akun WHERE LEFT(noakun,1)='6' AND kat=1 ORDER BY noakun ASC")
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-money-bill"></i> Biaya Pengeluaran',
            'isi' => $this->load->view('admin/biaya/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function modaltambahakun()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/biaya/modaltambahakun', '', true)
            ];
            echo json_encode($msg);
        }
    }

    function simpanakun()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun1', true) . $this->input->post('noakun2', true);
            $namaakun = $this->input->post('namaakun', true);

            $ceknoakun = $this->db->get_where('neraca_akun', ['noakun' => $noakun]);

            if ($ceknoakun->num_rows() > 0) {
                $msg = [
                    'error' => [
                        'noakun2' => 'No.Akun sudah terdaftar, coba lagi nomor yang lain'
                    ]
                ];
            } else {
                $simpan = [
                    'noakun' => $noakun,
                    'namaakun' => $namaakun,
                    'kat' => 1,
                ];
                $this->db->insert('neraca_akun', $simpan);

                $msg = ['sukses' => 'Akun berhasil ditambahkan'];
            }

            echo json_encode($msg);
        }
    }

    function hapusakun()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);

            $cektransaksiakun = $this->db->get_where('neraca_transaksi', ['transnoakun' => $noakun]);
            if ($cektransaksiakun->num_rows() > 0) {
                $msg = [
                    'error' => 'Maaf akun tidak bisa dihapus, ada transaksi !'
                ];
            } else {
                $this->db->delete('neraca_akun', ['noakun' => $noakun]);
                $msg = [
                    'sukses' => 'Berhasil dihapus !'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function detail($noakun)
    {
        $dataakun = $this->db->get_where('neraca_akun', ['noakun' => $noakun])->row_array();
        $data = [
            'data' => $dataakun
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-money-bill"></i> Detail Akun ' . $noakun,
            'isi' => $this->load->view('admin/biaya/detailakun', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function modaldetaildatatransaksi()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);

            $data = [
                'datatransaksi' => $this->db->get_where('neraca_transaksi', ['transnoakun' => $noakun])
            ];

            $msg = [
                'data' => $this->load->view('admin/biaya/datadetailtransaksi', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function buatnomor_transaksi_neraca()
    {
        $tglhariini = date('Y-m-d');
        $query = $this->db->query("SELECT MAX(transid) AS idtrans FROM neraca_transaksi");
        $hasil = $query->row_array();
        $data  = $hasil['idtrans'];


        // $lastNoUrut = substr($data, 10, 5);

        // nomor urut ditambah 1
        $nextNoUrut = $data + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = 'N-' . date('dmy', strtotime($tglhariini)) . $nextNoUrut;
        return $nextNoTransaksi;
    }

    function modaltambahtransaksiakun()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);
            $data = [
                'notrans' => $this->buatnomor_transaksi_neraca(),
                'noakun' => $noakun
            ];

            $msg = [
                'data' => $this->load->view('admin/biaya/modaltambahdataakun', $data, true)
            ];
            echo json_encode($msg);
        }
    }
    function simpandataakun()
    {
        $noakun = $this->input->post('noakun', true);
        $notrans = $this->input->post('notrans', true);
        $tgl = $this->input->post('tgl', true);
        $jml = $this->input->post('jml', true);
        $ket = $this->input->post('ket', true);

        $this->form_validation->set_rules('tgl', 'Tanggal', 'trim|required', [
            'required' => '%s tidak boleh kosong'
        ]);
        $this->form_validation->set_rules('jml', 'Inputan jumlah', 'trim|required', [
            'required' => '%s tidak boleh kosong'
        ]);


        if ($this->form_validation->run() == TRUE) {
            $simpandata = [
                'transno' => $notrans,
                'transnoakun' => $noakun,
                'transtgl' => $tgl,
                'transjenis' => 'K',
                'transjml' => str_replace(",", "", $jml),
                'transket' => $ket
            ];
            $this->db->insert('neraca_transaksi', $simpandata);
            $msg = [
                'sukses' => 'Berhasil ditambahkan'
            ];
        } else {
            $msg = [
                'error' => [
                    'tgl' => form_error('tgl'),
                    'jml' => form_error('jml'),
                ]
            ];
        }
        echo json_encode($msg);
    }

    function hapustransaksiakun()
    {
        $id = $this->input->post('id', true);
        $this->db->delete('neraca_transaksi', ['transid' => $id]);

        $msg = ['sukses' => 'Transaksi berhasil dihapus'];
        echo json_encode($msg);
    }
}