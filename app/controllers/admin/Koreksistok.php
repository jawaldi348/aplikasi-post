<?php
class Koreksistok extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library(array(
                'form_validation'
            ));
            // $this->load->model('admin/produk/Modelkoreksistok', 'koreksi');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Manajemen Koreksi Stok',
            'isi' => $this->load->view('admin/koreksistok/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function buatnomor()
    {
        $tglhariini = date('Y-m-d');
        $query = $this->db->query("SELECT MAX(koreksino) AS koreksino FROM koreksi_stok WHERE DATE_FORMAT(koreksitgl,'%Y-%m-%d') = '$tglhariini'");
        $hasil = $query->row_array();
        $data  = $hasil['koreksino'];


        $lastNoUrut = substr($data, -4);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = 'KS-' . date('dmy', strtotime($tglhariini)) . sprintf('%04s', $nextNoUrut);
        return $nextNoTransaksi;
    }
    function buatnomor_lagi()
    {
        $tglhariini = $this->input->post('tgl', true);
        $query = $this->db->query("SELECT MAX(koreksino) AS koreksino FROM koreksi_stok WHERE DATE_FORMAT(koreksitgl,'%Y-%m-%d') = '$tglhariini'");
        $hasil = $query->row_array();
        $data  = $hasil['koreksino'];


        $lastNoUrut = substr($data, -4);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = 'KS-' . date('dmy', strtotime($tglhariini)) . sprintf('%04s', $nextNoUrut);
        $msg = [
            'koreksino' => $nextNoTransaksi
        ];
        echo json_encode($msg);
    }

    public function input()
    {
        $data =  [
            'koreksino' => $this->buatnomor()
        ];
        $view = [
            // 'menu' => $this->load->view('template/menu', '', TRUE),
            // 'judul' => '<i class="fa fa-sort-amount-down"></i> Input Barang Masuk / Pembelian',
            'isi' => $this->load->view('admin/koreksistok/input', $data, true)

        ];
        $this->parser->parse('layoutkasir/main', $view);
    }

    public function caripemasok()
    {
        if ($this->input->is_ajax_request()) {
            $data   = [
                'datapemasok' => $this->db->get('pemasok')
            ];
            $msg = [
                'data' => $this->load->view('admin/koreksistok/modalcaripemasok', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function forminputproduk()
    {
        if ($this->input->is_ajax_request()) {
            $koreksino = $this->input->post('koreksino', true);
            $tgl = $this->input->post('tgl', true);

            $data = [
                'koreksino' => $koreksino,
                'tgl' => $tgl,
                'idpemasok' => $this->input->post('idpemasok', true)
            ];

            $msg = [
                'data' => $this->load->view('admin/koreksistok/forminputproduk', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function tampildata()
    {
        if ($this->input->is_ajax_request()) {
            $koreksino = $this->input->post('koreksino', true);
            $querydata = $this->db->query("SELECT koreksi_stok.*,produk.`namaproduk` FROM koreksi_stok JOIN produk ON kodebarcode=koreksikodebarcode WHERE koreksino='$koreksino'");

            $data = [
                'tampildata' => $querydata
            ];

            $msg = [
                'data' => $this->load->view('admin/koreksistok/tampildatakoreksi', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function ambilproduk()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);
            $namaproduk = $this->input->post('namaproduk', true);
            $koreksino = $this->input->post('koreksino', true);
            $tgl = $this->input->post('tgl', true);

            //cek kode produk 
            $this->load->model('Modelkasir', 'kasir');

            $cek_produk = $this->kasir->cekproduk($kode, $namaproduk);

            if ($cek_produk->num_rows() > 0) {
                if ($cek_produk->num_rows() === 1) {
                    $row_produk = $cek_produk->row_array();
                    $kodebarcode = $row_produk['kodebarcode'];
                    $namaproduk = $row_produk['namaproduk'];
                    $satnama = $row_produk['satnama'];
                    $stoktersedia = $row_produk['stok_tersedia'];
                    $hargabeli = $row_produk['harga_beli_eceran'];
                    $hargajual = $row_produk['harga_jual_eceran'];

                    $msg = [
                        'sukses' => [
                            'kode' => $kodebarcode,
                            'namaproduk' => $namaproduk,
                            'stoktersedia' => $stoktersedia,
                            'namasatuan' => $satnama,
                            'hargabeli' => $hargabeli,
                            'hargajual' => $hargajual
                        ]
                    ];
                } else {
                    $data = ['tampildata' => $cek_produk];
                    $msg = [
                        'banyakdata' => $this->load->view('admin/koreksistok/modalcaridataproduk', $data, true)
                    ];
                }
            } else {
                $msg = [
                    'error' => 'Produk tidak ditemukan'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function simpankoreksi()
    {
        if ($this->input->is_ajax_request()) {
            $idkoreksi = $this->input->post('idkoreksi', true);
            $kode = $this->input->post('kode', true);
            $koreksino = $this->input->post('koreksino', true);
            $tgl = $this->input->post('tgl', true);
            $hargajual = $this->input->post('hargajual', true);
            $hargabeli = $this->input->post('hargabeli', true);
            $stoklalu = $this->input->post('stoklalu', true);
            $stoksekarang = $this->input->post('stoksekarang', true);
            $alasan = $this->input->post('alasan', true);
            $selisih = $this->input->post('selisih', true);
            $idpemasok = $this->input->post('idpemasok', true);

            $this->form_validation->set_rules('kode', 'Kode', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('stoksekarang', 'Inputan Stok ini', 'trim|required|numeric', [
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus angka'
            ]);


            if ($this->form_validation->run() == TRUE) {
                if (strlen($idkoreksi) == 0) {
                    $simpandata = [
                        'koreksino' => $koreksino,
                        'koreksitgl' => date('Y-m-d', strtotime($tgl)),
                        'koreksikodebarcode' => $kode,
                        'koreksihargabeli' => $hargabeli,
                        'koreksihargajual' => $hargajual,
                        'koreksistoklalu' => $stoklalu,
                        'koreksistoksekarang' => $stoksekarang,
                        'koreksialasan' => $alasan,
                        'koreksiselisih' => $selisih,
                        'koreksiidpemasok' => $idpemasok
                    ];
                    $this->db->insert('koreksi_stok', $simpandata);
                    // update stok tersedia pada produk
                    $ambildata_produk = $this->db->get_where('produk', ['kodebarcode' => $kode]);
                    $row_produk = $ambildata_produk->row_array();
                    $stok_tersedia = $row_produk['stok_tersedia'];

                    $hitung_stok = $stok_tersedia + $selisih;

                    $updatedata_produk = [
                        'stok_tersedia' => $hitung_stok
                    ];
                    $this->db->where('kodebarcode', $kode);
                    $this->db->update('produk', $updatedata_produk);

                    // Simpan ke Neraca Transaksi
                    $ambildata_neraca_transaksi = $this->db->get_where('neraca_transaksi', [
                        'transno' => $koreksino,
                        'transnoakun' => '1-160',
                    ]);
                    if ($ambildata_neraca_transaksi->num_rows() > 0) {
                        $row_neraca_transaksi = $ambildata_neraca_transaksi->row_array();
                        $transjml = $row_neraca_transaksi['transjml'];

                        $update_neraca_transaksi = [
                            'transjml' => $transjml + ($selisih * $hargabeli)
                        ];
                        $this->db->where('transno', $koreksino);
                        $this->db->update('neraca_transaksi', $update_neraca_transaksi);
                    } else {
                        $insert_neraca_transaksi = [
                            'transno' => $koreksino,
                            'transtgl' => $tgl,
                            'transnoakun' => '1-160',
                            'transjenis' => 'K',
                            'transjml' => ($selisih * $hargabeli)
                        ];
                        $this->db->insert('neraca_transaksi', $insert_neraca_transaksi);
                    }

                    $msg = [
                        'sukses' => 'Berhasil disimpan'
                    ];
                } else {
                    // Update data produk terlebih dahulu
                    $ambildata_koreksi = $this->db->get_where('koreksi_stok', ['koreksiid' => $idkoreksi])->row_array();
                    $koreksiSelisih = $ambildata_koreksi['koreksiselisih'];

                    $ambildataProduk = $this->db->get_where('produk', ['kodebarcode' => $kode])->row_array();
                    $stok_tersedia = $ambildataProduk['stok_tersedia'];

                    $this->db->where('kodebarcode', $kode);
                    $this->db->update('produk', [
                        'stok_tersedia' => ($stok_tersedia - $koreksiSelisih) + $selisih
                    ]);

                    // Update Neraca Persediaan
                    $cek_neraca = $this->db->get_where('neraca_transaksi', [
                        'transno' => $koreksino,
                        'transnoakun' => '1-160',
                        'transjenis' => 'K'
                    ]);
                    if ($cek_neraca->num_rows() > 0) {
                        $rowNeraca = $cek_neraca->row_array();
                        $transid = $rowNeraca['transid'];
                        $transjml = $rowNeraca['transjml'];

                        $jumlahkanYangLama = $transjml - ($hargabeli * $koreksiSelisih);

                        $this->db->where('transid', $transid);
                        $this->db->update('neraca_transaksi', [
                            'transjml' => $jumlahkanYangLama +  ($hargabeli * $selisih)
                        ]);
                    }

                    // update tabel koreksi stok
                    $this->db->where('koreksiid', $idkoreksi);
                    $this->db->update('koreksi_stok', [
                        'koreksistoklalu' => $stoklalu,
                        'koreksistoksekarang' => $stoksekarang,
                        'koreksialasan' => $alasan,
                        'koreksiselisih' => $selisih,
                    ]);



                    $msg = [
                        'sukses' => 'Berhasil diupdate '
                    ];
                }
            } else {
                $msg = [
                    'error' => [
                        'kode' => form_error('kode'),
                        'stoksekarang' => form_error('stoksekarang'),
                    ]
                ];
            }

            echo json_encode($msg);
        }
    }

    function hapusnokoreksi()
    {
        if ($this->input->is_ajax_request()) {
            $no = $this->input->post('no', true);
            $ambildata_koreksistok = $this->db->get_where('koreksi_stok', ['koreksino' => $no]);
            foreach ($ambildata_koreksistok->result_array() as $r) :
                $kodebarcode = $r['koreksikodebarcode'];
                $selisih = $r['koreksiselisih'];
                // update stok tersedia pada produk
                $ambildata_produk = $this->db->get_where('produk', ['kodebarcode' => $kodebarcode]);
                $row_produk = $ambildata_produk->row_array();
                $stok_tersedia = $row_produk['stok_tersedia'];

                $hitung_stok = $stok_tersedia - $selisih;

                $updatedata_produk = [
                    'stok_tersedia' => $hitung_stok
                ];
                $this->db->where('kodebarcode', $kodebarcode);
                $this->db->update('produk', $updatedata_produk);
            endforeach;

            // Hapus Neraca Koreksi pada persediaan
            $this->db->delete('neraca_transaksi', [
                'transno' => $no,
                'transnoakun' => '1-160',
                'transjenis' => 'K'
            ]);

            // Hapus data koreksi dengan berdasarkan No.Koreksi
            $this->db->delete('koreksi_stok', [
                'koreksino' => $no
            ]);

            $msg = [
                'sukses' => "Koreksi dengan Nomor <strong>$no</strong>, berhasil dihapus !"
            ];
            echo json_encode($msg);
        }
    }

    function hapuskoreksistok()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);

            $ambildata_koreksistok = $this->db->get_where('koreksi_stok', ['koreksiid' => $id]);
            $row_koreksistok = $ambildata_koreksistok->row_array();
            $kode = $row_koreksistok['koreksikodebarcode'];
            $selisih = $row_koreksistok['koreksiselisih'];
            $koreksino = $row_koreksistok['koreksino'];
            $hargabeli = $row_koreksistok['koreksihargabeli'];

            // update stok tersedia pada produk
            $ambildata_produk = $this->db->get_where('produk', ['kodebarcode' => $kode]);
            $row_produk = $ambildata_produk->row_array();
            $stok_tersedia = $row_produk['stok_tersedia'];

            $hitung_stok = $stok_tersedia - $selisih;

            $updatedata_produk = [
                'stok_tersedia' => $hitung_stok
            ];
            $this->db->where('kodebarcode', $kode);
            $this->db->update('produk', $updatedata_produk);

            // Simpan ke Neraca Transaksi
            $ambildata_neraca_transaksi = $this->db->get_where('neraca_transaksi', [
                'transno' => $koreksino,
                'transnoakun' => '1-160',
            ]);
            if ($ambildata_neraca_transaksi->num_rows() > 0) {
                $row_neraca_transaksi = $ambildata_neraca_transaksi->row_array();
                $transjml = $row_neraca_transaksi['transjml'];

                $update_neraca_transaksi = [
                    'transjml' => $transjml - ($selisih * $hargabeli)
                ];
                $this->db->where('transno', $koreksino);
                $this->db->update('neraca_transaksi', $update_neraca_transaksi);
            }

            // Hapus Data Koreksi Stok
            $this->db->delete('koreksi_stok', ['koreksiid' => $id]);


            $msg = [
                'sukses' => 'Berhasil dihapus'
            ];

            echo json_encode($msg);
        }
    }

    function ambildata()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/Modelkoreksistok', 'koreksi');
            $list = $this->koreksi->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $aksi = "<button type=\"\" class=\"btn btn-sm btn-outline-danger\" onclick=\"hapus('" . $field->koreksino . "')\">
                <i class=\"fa fa-fw fa-trash-alt\"></i>            
               </button>&nbsp;<button type=\"\" class=\"btn btn-sm btn-outline-info\" onclick=\"edit('" . sha1($field->koreksino) . "')\">
               <i class=\"fa fa-fw fa-tags\"></i>            
              </button>";

                $row[] = $no;
                $row[] = $field->koreksino;
                $row[] = date('d-m-Y', strtotime($field->koreksitgl));
                $row[] = ($field->nama == NULL || $field->nama == '') ? '-' : $field->nama;

                // Query Jumlah Item
                $query_detailkoreksi = $this->db->query("SELECT COUNT(koreksiid) AS jmlitem FROM koreksi_stok WHERE koreksino = '$field->koreksino'")->row_array();

                $row[] = "<span class=\"badge badge-info\" style=\"cursor:pointer;\" onclick=\"item('" . $field->koreksino . "')\">$query_detailkoreksi[jmlitem]</span>";
                $row[] = $aksi;

                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->koreksi->count_all(),
                "recordsFiltered" => $this->koreksi->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function detailitem()
    {
        if ($this->input->is_ajax_request()) {
            $no = $this->input->post('no', true);

            $cek_koreksi = $this->db->get_where(
                'koreksi_stok',
                ['koreksino' => $no]
            );

            if ($cek_koreksi->num_rows() > 0) {
                $data = [
                    'datadetail' => $this->db->query("SELECT koreksiid as id,koreksikodebarcode AS kode, namaproduk,koreksistoklalu AS stoklalu,koreksistoksekarang AS stoksekarang,koreksialasan AS alasan,
                    koreksiselisih AS selisih,koreksihargabeli AS hargabeli  FROM koreksi_stok JOIN produk ON kodebarcode=koreksikodebarcode WHERE koreksino='$no'")
                ];

                $msg = [
                    'data' => $this->load->view('admin/koreksistok/modaldetailitem', $data, true)
                ];
                echo json_encode($msg);
            }
        }
    }

    function ambildatakoreksi()
    {
        if ($this->input->is_ajax_request()) {
            $idkoreksi = $this->input->post('idkoreksi', true);

            $ambildatakoreksi = $this->db->get_where('koreksi_stok', ['koreksiid' => $idkoreksi])->row_array();
            $ambildataproduk = $this->db->get_where('produk', ['kodebarcode' => $ambildatakoreksi['koreksikodebarcode']])->row_array();
            $ambildatasatuan = $this->db->get_where('satuan', ['satid' => $ambildataproduk['satid']])->row_array();

            $data = [
                'kode' => $ambildatakoreksi['koreksikodebarcode'],
                'namaproduk' => $ambildataproduk['namaproduk'],
                'satuan' => $ambildatasatuan['satnama'],
                'stoklalu' => $ambildatakoreksi['koreksistoklalu'],
                'stoksekarang' => $ambildatakoreksi['koreksistoksekarang'],
                'alasan' => $ambildatakoreksi['koreksialasan'],
                'selisih' => $ambildatakoreksi['koreksiselisih'],
                'hargabeli' => $ambildataproduk['harga_beli_eceran'],
                'hargajual' => $ambildataproduk['harga_jual_eceran'],
            ];

            $msg = [
                'sukses' => $data
            ];
            echo json_encode($msg);
        }
    }

    public function edit($no)
    {
        $ambildata = $this->db->get_where('koreksi_stok', ['sha1(koreksino)' => $no])->row_array();
        $ambildataPemasok = $this->db->get_where('pemasok', ['id' => $ambildata['koreksiidpemasok']])->row_array();
        $data = [
            'nokoreksi' => $ambildata['koreksino'],
            'tgl' => $ambildata['koreksitgl'],
            'idpemasok' => $ambildataPemasok['id'],
            'namapemasok' => $ambildataPemasok['nama']
        ];
        $view = [

            'isi' => $this->load->view('admin/koreksistok/edit', $data, true)

        ];
        $this->parser->parse('layoutkasir/main', $view);
    }

    function updatekoreksi()
    {
        if ($this->input->is_ajax_request()) {
            $no = $this->input->post('no', true);
            $idpemasok = $this->input->post('idpemasok', true);

            $this->db->where('koreksino', $no);
            $this->db->update('koreksi_stok', [
                'koreksiidpemasok' => $idpemasok
            ]);

            $msg = [
                'sukses' => 'Data Koreksi Stok berhasil diupdate'
            ];
            echo json_encode($msg);
        }
    }
}