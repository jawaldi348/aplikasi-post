<?php
class Aset_tetap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library(array(
                'form_validation'
            ));
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $query_asettetap = $this->db->query(
            "SELECT * FROM neraca_akun WHERE noakun BETWEEN '1-200' AND '1-399' AND kat='1'"
        );
        $data = [
            'tampildata' => $query_asettetap
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-archway"></i> Manajemen Aset',
            'isi' => $this->load->view('admin/asettetap/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function hapusakun()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);

            $cek_transaksi_neraca = $this->db->get_where('neraca_transaksi', [
                'transnoakun' => $noakun
            ]);
            $cek_neraca_detail = $this->db->get_where('neraca_akun_detail', ['detnoakun' => $noakun]);

            if ($cek_transaksi_neraca->num_rows() > 0 && $cek_neraca_detail->num_rows() > 0) {
                $msg = [
                    'error' => 'Maaf tidak bisa dihapus, dikarenakan ada transaksi neraca pada akun ini'
                ];
            } else {

                $this->db->delete('neraca_akun', [
                    'noakun' => $noakun
                ]);
                $msg = [
                    'sukses' => 'Akun berhasil dihapus !'
                ];
            }
            echo json_encode($msg);
        }
    }

    function formtambahakun()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/asettetap/formtambahakun', '', true)
            ];
            echo json_encode($msg);
        }
    }

    function simpanakun()
    {
        if ($this->input->is_ajax_request()) {
            $noakunawal = $this->input->post('noakunawal', true);
            $noakunakhir = $this->input->post('noakunakhir', true);
            $namaakun = $this->input->post('namaakun', true);
            $penyusutan = $this->input->post('penyusutan', true);

            $noakun = $noakunawal . $noakunakhir;

            $this->form_validation->set_rules('noakunakhir', 'No.Akun', 'trim|required', [
                'required' => '%s tidak boleh kosong',
            ]);

            $this->form_validation->set_rules('namaakun', 'Nama Akun', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('penyusutan', 'Pilih Penyusutan', 'trim|required', [
                'required' => '%s wajib di pilih'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $cek_noakun = $this->db->get_where('neraca_akun', ['noakun' => $noakun]);
                if ($cek_noakun->num_rows() > 0) {
                    $msg = [
                        'error' => [
                            'noakunakhir' => 'No.Akun sudah ada ada, coba yang lain',
                        ]
                    ];
                } else {
                    $this->db->insert('neraca_akun', [
                        'noakun' => $noakun,
                        'namaakun' => $namaakun,
                        'kat' => '1',
                        'akunpenyusutan' => $penyusutan
                    ]);

                    $msg = [
                        'sukses' => 'Akun Aset Tetap Berhasil di Tambahkan'
                    ];
                }
            } else {
                $msg = [
                    'error' => [
                        'noakunakhir' => form_error('noakunakhir'),
                        'namaakun' => form_error('namaakun'),
                        'penyusutan' => form_error('penyusutan'),
                    ]
                ];
            }
            echo json_encode($msg);
        }
    }

    function detail($akun)
    {
        $cek_neraca_akun = $this->db->get_where('neraca_akun', ['sha1(noakun)' => $akun]);

        if ($cek_neraca_akun->num_rows() > 0) {
            $row = $cek_neraca_akun->row_array();
            $data = [
                'noakun' => $row['noakun'],
                'namaakun' => $row['namaakun'],
                'akunpenyusutan' => $row['akunpenyusutan'],
            ];

            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-tasks"></i> Detail Aset Tetap ' . $row['namaakun'],
                'isi' => $this->load->view('admin/asettetap/detail', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    function ambildatadetail()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);
            $akunpenyusutan = $this->input->post('akunpenyusutan', true);

            if ($akunpenyusutan == 0) {
                $data = [
                    'datadetail' => $this->db->get_where('neraca_akun_detail', ['detnoakun' => $noakun])
                ];
                $msg = [
                    'data' => $this->load->view('admin/asettetap/ambildatadetail', $data, true)
                ];
                echo json_encode($msg);
            } else {
                $data = [
                    'datadetail' => $this->db->get_where('neraca_transaksi', ['transnoakun' => $noakun])
                ];
                $msg = [
                    'data' => $this->load->view('admin/asettetap/ambildatadetailpenyusutan', $data, true)
                ];
                echo json_encode($msg);
            }
        }
    }

    function buatid()
    {
        $tgl = $this->input->post('tgl', true);
        $noakun = $this->input->post('noakun', true);
        $query = $this->db->query("SELECT MAX(detid) AS id FROM neraca_akun_detail WHERE detnoakun = '$noakun'");
        $hasil = $query->row_array();
        $data  = $hasil['id'];


        $lastNoUrut = substr($data, 12, 4);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = $noakun . date('dmy', strtotime($tgl)) . sprintf('%04s', $nextNoUrut);
        $msg = [
            'idaset' => $nextNoTransaksi
        ];
        echo json_encode($msg);
    }

    function formtambahdetailaset()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);
            $namaakun = $this->input->post('namaakun', true);
            $akunpenyusutan = $this->input->post('akunpenyusutan', true);

            $msg = [
                'data' => $this->load->view('admin/asettetap/formtambahdetailaset', ['noakun' => $noakun, 'namaakun' => $namaakun, 'akunpenyusutan' => $akunpenyusutan], true)
            ];
            echo json_encode($msg);
        }
    }

    function simpandetailaset()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);
            $idaset = $this->input->post('idaset', true);
            $tglaset = $this->input->post('tglaset', true);
            $namaaset = $this->input->post('namaaset', true);
            $jmlaset = $this->input->post('jmlaset', true);
            $harga = $this->input->post('harga', true);
            $akunpenyusutan = $this->input->post('akunpenyusutan', true);
            $subtotal = $jmlaset * $harga;


            $this->form_validation->set_rules('idaset', 'ID Aset', 'trim|required', [
                'required' => '%s tidak boleh kosong, pilih tanggal dulu'
            ]);
            $this->form_validation->set_rules('tglaset', 'Tgl. Aset', 'trim|required', [
                'required' => '%s tidak boleh kosong,'
            ]);
            $this->form_validation->set_rules('namaaset', 'Nama Aset', 'trim|required', [
                'required' => '%s tidak boleh kosong,'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $this->db->insert('neraca_akun_detail', [
                    'detid' => $idaset,
                    'dettgl' => $tglaset,
                    'detnoakun' => $noakun,
                    'detasetnama' => $namaaset,
                    'detasetjml' => $jmlaset,
                    'detasetharga' => $harga,
                    'detasetsubtotal' => $subtotal,
                    'detasetket' => $this->input->post('ket', true)
                ]);

                // Insert Ke Neraca Aset Tetap 
                if ($akunpenyusutan == 0) {
                    $this->db->insert('neraca_transaksi', [
                        'transno' => $idaset,
                        'transtgl' => $tglaset,
                        'transnoakun' => $noakun,
                        'transjenis' => 'K',
                        'transjml' => $subtotal,
                        'transket' => "Penambahan Aset $namaaset"
                    ]);
                }

                // end

                $msg = [
                    'sukses' => 'Aset detail berhasil ditambahkan'
                ];
            } else {
                $msg = [
                    'error' => [
                        'idaset' => form_error('idaset'),
                        'tglaset' => form_error('tglaset'),
                        'namaaset' => form_error('namaaset')
                    ]
                ];
            }
            echo json_encode($msg);
        }
    }

    function hapusasetdetail()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $this->db->delete('neraca_transaksi', ['transno' => $id]);
            $this->db->delete('neraca_akun_detail', ['detid' => $id]);

            $msg = [
                'sukses' => 'Berhasil di hapus'
            ];
            echo json_encode($msg);
        }
    }
    function hapusdetailpenyusutan()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $this->db->delete('neraca_transaksi', ['transno' => $id]);

            $msg = [
                'sukses' => 'Berhasil di hapus'
            ];
            echo json_encode($msg);
        }
    }

    function formpenyusutan()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);

            $msg = [
                'data' => $this->load->view('admin/asettetap/formpenyusutan', ['noakun' => $noakun], true)
            ];
            echo json_encode($msg);
        }
    }

    function buatidpenyusutan()
    {
        $tgl = $this->input->post('tgl', true);
        $noakun = $this->input->post('noakun', true);
        $query = $this->db->query("SELECT MAX(detid) AS id FROM neraca_akun_detail WHERE detnoakun = '$noakun'");
        $hasil = $query->row_array();
        $data  = $hasil['id'];


        $lastNoUrut = substr($data, 12, 4);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = $noakun . date('dmy', strtotime($tgl)) . sprintf('%04s', $nextNoUrut);
        $msg = [
            'idpenyusutan' => $nextNoTransaksi
        ];
        echo json_encode($msg);
    }

    function simpanpenyusutan()
    {
        if ($this->input->is_ajax_request()) {
            $noakun = $this->input->post('noakun', true);
            $idpenyusutan = $this->input->post('idpenyusutan', true);
            $tgl = $this->input->post('tgl', true);
            $harga = str_replace(",", "", $this->input->post('harga', true));


            $this->form_validation->set_rules('tgl', 'Tanggal', 'trim|required', [
                'required' => '%s tidak boleh kosong,'
            ]);


            if ($this->form_validation->run() == TRUE) {
                // Insert Ke Neraca Aset Tetap 
                $this->db->insert('neraca_transaksi', [
                    'transno' => $idpenyusutan,
                    'transtgl' => $tgl,
                    'transnoakun' => $noakun,
                    'transjenis' => 'D',
                    'transjml' => $harga,
                    'transket' => "Penyusutan Aset"
                ]);

                // end

                $msg = [
                    'sukses' => 'Penyusutan Aset detail berhasil ditambahkan'
                ];
            } else {
                $msg = [
                    'error' => [
                        'idaset' => form_error('idaset'),
                        'tglaset' => form_error('tglaset'),
                        'namaaset' => form_error('namaaset')
                    ]
                ];
            }
            echo json_encode($msg);
        }
    }
}