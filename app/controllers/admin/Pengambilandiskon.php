<?php
class Pengambilandiskon extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->aksesgrup = $this->session->userdata('idgrup');
        if ($this->session->userdata('masuk') == true && ($this->aksesgrup == '1')) {
            $this->load->library(['form_validation']);
            $this->load->model('admin/Modeltransaksineraca', 'neraca');
            return true;
        } else {
            redirect('login/logout');
        }
    }

    public function data()
    {
        $data = [
            'datapengambilan' => $this->db->get('pengambilan_diskon')
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Form Pengambilan Diskon Member',
            'isi' => $this->load->view('admin/pengambilandiskon/data', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }
    function buatkodepengambilan()
    {
        if ($this->input->is_ajax_request()) {
            $tgl = $this->input->post('tgl', true);

            $query = $this->db->query("SELECT MAX(ambilkode) AS kodepengambilan FROM pengambilan_diskon WHERE DATE_FORMAT(ambiltgl,'%Y-%m-%d') = '$tgl'");
            $hasil = $query->row_array();
            $data  = $hasil['kodepengambilan'];


            $lastNoUrut = substr($data, 10, 4);

            // nomor urut ditambah 1
            $nextNoUrut = $lastNoUrut + 1;

            // membuat format nomor transaksi berikutnya
            $nextNoTransaksi = 'PD-' . date('dmy', strtotime($tgl)) . sprintf('%04s', $nextNoUrut);
            $msg = [
                'sukses' => $nextNoTransaksi
            ];
            echo json_encode($msg);
        }
    }
    public function input()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Form Pengambilan Diskon Member',
            'isi' => $this->load->view('admin/pengambilandiskon/forminput', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function carimemberdiskon()
    {
        if ($this->input->is_ajax_request()) {
            $tglsekarang = date('Y-m-d');

            $query_diskonmember = $this->db->query("SELECT jualmemberkode,membernama FROM penjualan JOIN member ON memberkode=jualmemberkode WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$tglsekarang' GROUP BY memberkode");

            $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
            $diskon_setting = $ambil_datamembersettingdiskon['diskon'];
            $data = [
                'datamember' => $query_diskonmember,
                'diskonsetting' => $diskon_setting,
                'tglsekarang' => $tglsekarang
            ];

            $msg = [
                'sukses' => $this->load->view('admin/pengambilandiskon/modalcarimember', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function tampilmemberdiskon()
    {
        if ($this->input->is_ajax_request()) {
            $kodepengambilan = $this->input->post('kodepengambilan', true);
            $tglsekarang = date('Y-m-d');

            $query_diskonmember = $this->db->query("SELECT jualmemberkode,membernama FROM penjualan JOIN member ON memberkode=jualmemberkode WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$tglsekarang' GROUP BY memberkode");

            $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
            $diskon_setting = $ambil_datamembersettingdiskon['diskon'];
            $data = [
                'datamember' => $query_diskonmember,
                'diskonsetting' => $diskon_setting,
                'kodepengambilan' => $kodepengambilan,
                'tglsekarang' => $tglsekarang
            ];

            $msg = [
                'sukses' => $this->load->view('admin/pengambilandiskon/modalcarimemberseluruh', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function simpandata()
    {
        if ($this->input->is_ajax_request()) {
            $kodemember = $this->input->post('kodemember', true);
            $kodepengambilan = $this->input->post('kodepengambilan', true);
            $tglpengambilan = $this->input->post('tglpengambilan', true);
            $jenispengambilan = $this->input->post('jenispengambilan', true);
            $totaltabungan = str_replace(",", "", $this->input->post('totaltabungan', true));
            $totaltabunganseluruh = str_replace(",", "", $this->input->post('totaltabunganseluruh', true));

            $this->form_validation->set_rules('jenispengambilan', 'Jenis Pengambilan', 'trim|required', [
                'required' => '%s harus dipilih'
            ]);


            if ($this->form_validation->run() == TRUE) {
                if ($jenispengambilan == 0) {
                    if ($totaltabungan == 0) {
                        $msg = [
                            'error' => 'Tabungan kosong'
                        ];
                    } else {
                        $this->db->insert('pengambilan_diskon', [
                            'ambilkode' => $kodepengambilan,
                            'ambiltgl' => $tglpengambilan,
                            'ambiljenis' => $jenispengambilan,
                            'ambiltotal' => $totaltabungan
                        ]);

                        $this->db->insert('pengambilan_diskon_detail', [
                            'detambilkode' => $kodepengambilan,
                            'detambilmemberkode' => $kodemember,
                            'detambiljumlah' => $totaltabungan
                        ]);

                        // Kurangi neraca 2-130 simpanan tabungan
                        $this->db->insert('neraca_transaksi', [
                            'transno' => $kodepengambilan,
                            'transtgl' => $tglpengambilan,
                            'transnoakun' => '2-130',
                            'transjenis' => 'D',
                            'transjml' => $totaltabungan,
                            'transket' => 'Pengambilan tabungan member'
                        ]);
                        // end

                        $msg = [
                            'sukses' => 'Berhasil di Simpan'
                        ];
                    }
                } else {
                    if ($totaltabunganseluruh == 0) {
                        $msg = [
                            'error' => 'Tabungan kosong'
                        ];
                    } else {
                        $this->db->insert('pengambilan_diskon', [
                            'ambilkode' => $kodepengambilan,
                            'ambiltgl' => $tglpengambilan,
                            'ambiljenis' => $jenispengambilan,
                            'ambiltotal' => $totaltabunganseluruh
                        ]);

                        $ambil_datatemppengambilan = $this->db->get_where('temp_pengambilan_diskon', ['detambilkode' => $kodepengambilan]);

                        foreach ($ambil_datatemppengambilan->result_array() as $rr) :
                            $this->db->insert('pengambilan_diskon_detail', [
                                'detambilkode' => $rr['detambilkode'],
                                'detambilmemberkode' => $rr['detambilmemberkode'],
                                'detambiljumlah' => $rr['detambiljumlah'],
                            ]);
                        endforeach;

                        // hapus temp
                        $this->db->delete('temp_pengambilan_diskon', [
                            'detambilkode' => $kodepengambilan
                        ]);

                        // Kurangi neraca 2-130 simpanan tabungan
                        $this->db->insert('neraca_transaksi', [
                            'transno' => $kodepengambilan,
                            'transtgl' => $tglpengambilan,
                            'transnoakun' => '2-130',
                            'transjenis' => 'D',
                            'transjml' => $totaltabunganseluruh,
                            'transket' => 'Pengambilan tabungan member'
                        ]);
                        // end

                        $msg = [
                            'sukses' => 'Berhasil di Simpan'
                        ];
                    }
                }
            } else {
                $msg = [
                    'error' => [
                        'jenispengambilan' => form_error('jenispengambilan')
                    ]
                ];
            }
            echo json_encode($msg);
        }
    }

    public function simpantemp()
    {
        if ($this->input->is_ajax_request()) {
            $kodepengambilan = $this->input->post('ambilkode', true);
            $tabungandiskonmember = $this->input->post('sisadiskon', true);
            $kodemember = $this->input->post('memberkode', true);

            for ($i = 0; $i < count($kodemember); $i++) {
                $this->db->insert('temp_pengambilan_diskon', [
                    'detambilkode' => $kodepengambilan,
                    'detambilmemberkode' => $kodemember[$i],
                    'detambiljumlah' => $tabungandiskonmember[$i]
                ]);
            }

            // hitung total tabungan dari tabel temp
            $query_temppengambilan = $this->db->get_where('temp_pengambilan_diskon', [
                'detambilkode' => $kodepengambilan
            ]);
            $totaltabungandiskon = 0;
            foreach ($query_temppengambilan->result_array() as $r) :
                $totaltabungandiskon = $totaltabungandiskon + $r['detambiljumlah'];
            endforeach;

            $msg = [
                'sukses' => [
                    'totaltabungandiskon' => $totaltabungandiskon
                ]
            ];
            echo json_encode($msg);
        }
    }

    function batalinput()
    {
        if ($this->input->is_ajax_request()) {
            $kodepengambilan = $this->input->post('kodepengambilan', true);
            // hapus temp pengambilan diskon
            $this->db->delete('temp_pengambilan_diskon', ['detambilkode' => $kodepengambilan]);
            $msg = ['sukses' => 'berhasil'];

            echo json_encode($msg);
        }
    }

    public function hapusdata()
    {
        if ($this->input->is_ajax_request()) {
            $kodepengambilan = $this->input->post('kode', true);

            // HAPUS DETAIL
            $this->db->delete('pengambilan_diskon_detail', ['detambilkode' => $kodepengambilan]);

            $this->db->delete('pengambilan_diskon', ['ambilkode' => $kodepengambilan]);

            // Tambah Kembali neraca 2-130 simpanan tabungan
            $this->db->delete('neraca_transaksi', [
                'transno' => $kodepengambilan,
                'transnoakun' => '2-130',
                'transjenis' => 'D',
            ]);
            // end

            $msg = [
                'sukses' => 'Berhasil'
            ];
            echo json_encode($msg);
        }
    }
}