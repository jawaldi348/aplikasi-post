<?php
class Neraca extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && ($this->session->userdata('idgrup') == '1' || $this->session->userdata('idgrup') == '4')) {
            $this->load->library(array(
                'form_validation'
            ));
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function input_awal()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-newspaper"></i> Input Awal Akun Neraca',
            'isi' => $this->load->view('admin/neraca/inputawal', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function tampil_neraca()
    {
        if ($this->input->is_ajax_request()) {
            $data = [
                'tampildata' => $this->db->query("SELECT * FROM neraca_akun ORDER BY noakun ASC")
            ];

            $msg = [
                'data' => $this->load->view('admin/neraca/dataakun', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function hapustransaksineraca()
    {
        $id = $this->input->post('id', true);

        $this->db->delete('neraca_transaksi', ['transid' => $id]);

        $msg = [
            'sukses' => 'Transaksi neraca berhasil terhapus'
        ];
        echo json_encode($msg);
    }
    function editdata()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);

            $ambildata = $this->db->get_where('neraca_akun', ['noakun' => $noakun]);
            $r = $ambildata->row_array();
            $data = [
                'noakun' => $noakun,
                'namaakun' => $r['namaakun'],
                'jml' => $r['jmlsetdef'],
                'tgl' => $r['tglsetdef']
            ];

            $msg = [
                'data' => $this->load->view('admin/neraca/modaleditdata', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function updatedata()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);
            $jml = $this->input->post('jml', true);
            $tgl = $this->input->post('tgl', true);

            //cek akun jika akun kas dan kas bank
            // if ($noakun == '1-110') {
            //     $cek_neraca_transaksi_kaskecil = $this->db->get_where('neraca_transaksi', [
            //         'transno' => 'KK-110', 'transnoakun' => '1-110', 'transjenis' => 'K'
            //     ]);
            //     if ($cek_neraca_transaksi_kaskecil->num_rows() > 0) {
            //         $update_kaskecil = [
            //             'transjml' => str_replace(",", "", $jml)
            //         ];
            //         $this->db->where('transno', 'KK-110');
            //         $this->db->update('neraca_transaksi', $update_kaskecil);
            //     } else {
            //         $insert_kaskecil = [
            //             'transno' => 'KK-110',
            //             'transtgl' => $tgl,
            //             'transnoakun' => '1-110',
            //             'transjenis' => 'K',
            //             'transjml' => str_replace(",", "", $jml),
            //             'transket' => 'Input Awal'
            //         ];
            //         $this->db->insert('neraca_transaksi', $insert_kaskecil);
            //     }
            // }

            $cek_neraca_transaksi = $this->db->get_where('neraca_transaksi', ['transno' => 'KK-' . $noakun]);
            if ($cek_neraca_transaksi->num_rows() > 0) {
                $this->db->where('transno', 'KK-' . $noakun);
                $this->db->update('neraca_transaksi', [
                    'transjml' => str_replace(",", "", $jml)
                ]);
            } else {
                $this->db->insert('neraca_transaksi', [
                    'transno' => 'KK-' . $noakun,
                    'transtgl' => $tgl,
                    'transnoakun' => $noakun,
                    'transjenis' => 'K',
                    'transjml' => str_replace(",", "", $jml),
                    'transket' => 'Input Awal'
                ]);
            }

            $update = [
                'jmlsetdef' => str_replace(",", "", $jml),
                'tglsetdef' => $tgl
            ];
            $this->db->where('noakun', $noakun);
            $this->db->update('neraca_akun', $update);

            $msg = [
                'sukses' => 'Berhasil di update'
            ];
            echo json_encode($msg);
        }
    }

    // Pengecekkan Neraca
    public function cek()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-file-archive"></i> Lakukan Pengecekan Neraca & Cetak',
            'isi' => $this->load->view('admin/neraca/cek/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function cek_tampil()
    {
        if ($this->input->is_ajax_request()) {
            $bulan = $this->input->post('bulan', true);

            $data = [
                'bulan' => $bulan,
                'tampildata' => $this->db->query("SELECT * FROM neraca_akun ORDER BY noakun ASC")
            ];

            $msg = [
                'data' => $this->load->view('admin/neraca/cek/dataakun', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function lihatdata_akun()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);
            $query =  $this->db->query("SELECT transid,transnoakun,transno,transtgl,
            CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
            CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
            FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun' ORDER BY transtgl ASC");
            $data = [
                'datadetail' => $query,
                'noakun' => $noakun
            ];

            $msg = [
                'data' => $this->load->view('admin/neraca/cek/modaldetaildataakun', $data, true)
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

    function tambahdataakun()
    {
        $noakun = $this->input->post('noakun', true);
        $data = [
            'notrans' => $this->buatnomor_transaksi_neraca(),
            'noakun' => $noakun
        ];

        $msg = [
            'data' => $this->load->view('admin/neraca/cek/modaltambahdataakun', $data, true)
        ];
        echo json_encode($msg);
    }

    function cetakbalance_sheet($bulan)
    {
        // Hapus data dengan tanggal dan jml null
        $this->db->query("DELETE FROM neraca_transaksi WHERE transjml IS NULL AND transtgl IS NULL");
        $this->load->model('Modeltoko', 'toko');
        $data = [
            'toko' => $this->toko->datatoko()->row_array(),
            'bulan' => $bulan,
            'namalaporan' => 'balance sheet',
            'dataakun1' => $this->db->query("SELECT * FROM neraca_akun WHERE LEFT(noakun,1)=1 ORDER BY noakun ASC"),
            'dataakun2' => $this->db->query("SELECT * FROM neraca_akun WHERE LEFT(noakun,1)=2 ORDER BY noakun ASC"),
            'dataakun3' => $this->db->query("SELECT * FROM neraca_akun WHERE LEFT(noakun,1)=3 ORDER BY noakun ASC")
        ];
        $this->load->view('admin/neraca/cek/cetakbalancesheet', $data);
    }
    function cetakincome($bulan)
    {
        $this->load->model('Modeltoko', 'toko');
        $data = [
            'toko' => $this->toko->datatoko()->row_array(),
            'bulan' => $bulan,
            'namalaporan' => 'income statement',
            'dataakun4' => $this->db->query("SELECT * FROM neraca_akun WHERE LEFT(noakun,1)=4 ORDER BY noakun ASC"),
            'dataakun5' => $this->db->query("SELECT * FROM neraca_akun WHERE LEFT(noakun,1)=5 ORDER BY noakun ASC"),
            'dataakun6' => $this->db->query("SELECT * FROM neraca_akun WHERE LEFT(noakun,1)=6 ORDER BY noakun ASC")
        ];
        $this->load->view('admin/neraca/cek/cetakincome', $data);
    }

    function simpandataakun()
    {
        $noakun = $this->input->post('noakun', true);
        $notrans = $this->input->post('notrans', true);
        $tgl = $this->input->post('tgl', true);
        $jenis = $this->input->post('jenis', true);
        $jml = $this->input->post('jml', true);
        $ket = $this->input->post('ket', true);

        $this->form_validation->set_rules('tgl', 'Tanggal', 'trim|required', [
            'required' => '%s tidak boleh kosong'
        ]);
        $this->form_validation->set_rules('jenis', 'Jenis', 'trim|required', [
            'required' => '%s wajib diisi'
        ]);
        $this->form_validation->set_rules('jml', 'Inputan jumlah', 'trim|required', [
            'required' => '%s tidak boleh kosong'
        ]);


        if ($this->form_validation->run() == TRUE) {
            $simpandata = [
                'transno' => $notrans,
                'transnoakun' => $noakun,
                'transtgl' => $tgl,
                'transjenis' => $jenis,
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
                    'jenis' => form_error('jenis'),
                    'jml' => form_error('jml'),
                ]
            ];
        }
        echo json_encode($msg);
    }

    // End Pengecekan

}