<?php
class Pembelian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->aksesgrup = $this->session->userdata('idgrup');
        if ($this->session->userdata('masuk') == true && ($this->aksesgrup == 1 || $this->aksesgrup == 2)) {
            $this->load->library(['form_validation']);
            $this->load->model('admin/pembelian/Modelpembelian', 'beli');
            $this->load->model('admin/Modeltransaksineraca', 'neraca');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {

        $datahutang_jatuhtempo = $this->beli->tampilnotifhutangjatuhtempo();
        $data = [
            'datahutang_jatuhtempo' => $datahutang_jatuhtempo,
            'datahutang' => $this->beli->datahutang(),
            'totaltransaksipembelian' => $this->db->get('pembelian')->num_rows()
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-truck-moving"></i> Pembelian',
            'isi' => $this->load->view('admin/pembelian/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function tampildatasatuanproduk()
    {
        if ($this->input->is_ajax_request()) {
            // Menampilkan Data Satuan
            $datasatuan = $this->db->get('satuan')->result();

            $datax = "<option value=\"\">-Pilih Satuan-</option>";
            foreach ($datasatuan as $x) {
                $datax .= "<option value='" . $x->satid . "'>" . $x->satnama . "</option>";
            }
            $msg = [
                'data' => $datax,
            ];
            echo json_encode($msg);
        }
    }
    public function tampildatakategoriproduk()
    {
        if ($this->input->is_ajax_request()) {
            // Menampilkan Data Satuan
            $datakategori = $this->db->query("SELECT * FROM kategori WHERE katid <> 1")->result();

            $datax = "<option value=\"1\" selected>-</option>";
            foreach ($datakategori as $x) {
                $datax .= "<option value='" . $x->katid . "'>" . $x->katnama . "</option>";
            }
            $msg = [
                'data' => $datax,
            ];
            echo json_encode($msg);
        }
    }

    function simpanbaru_satuan()
    {
        if ($this->input->is_ajax_request()) {
            $satuan = $this->input->post('produksatuan', true);

            $this->db->insert('satuan', ['satnama' => $satuan]);

            $msg = [
                'sukses' => ''
            ];
            echo json_encode($msg);
        }
    }
    function simpanbaru_kategori()
    {
        if ($this->input->is_ajax_request()) {
            $kategori = $this->input->post('produkkategori', true);

            $this->db->insert('kategori', ['katnama' => $kategori]);

            $msg = [
                'sukses' => ''
            ];
            echo json_encode($msg);
        }
    }

    function simpanprodukbaru()
    {
        if ($this->input->is_ajax_request()) {
            $produkkode = $this->input->post('produkkode', true);
            $produknama = $this->input->post('produknama', true);
            $produksatuan = $this->input->post('produksatuan', true);
            $produkkategori = $this->input->post('produkkategori', true);
            $produkstoktersedia = $this->input->post('produkstoktersedia', true);
            $produkhargamodal = str_replace(".", "", $this->input->post('produkhargamodal', true));
            $produkhargajual = str_replace(".", "", $this->input->post('produkhargajual', true));
            $produkmargin = $this->input->post('produkmargin', true);

            $this->form_validation->set_rules('produkkode', 'Kode Produk', 'trim|required|is_unique[produk.kodebarcode]', [
                'required' => '%s tidak boleh kosong',
                'is_unique' => '%s tidak boleh ada yang sama'
            ]);
            $this->form_validation->set_rules('produknama', 'Nama Produk', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('produksatuan', 'Satuan', 'trim|required', [
                'required' => '%s wajib dipilih'
            ]);
            $this->form_validation->set_rules('produkhargamodal', 'Harga Modal', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('produkhargajual', 'Harga Jual', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $this->db->insert('produk', [
                    'kodebarcode' => $produkkode,
                    'namaproduk' => $produknama,
                    'satid' => $produksatuan,
                    'katid' => $produkkategori,
                    'stok_tersedia' => $produkstoktersedia,
                    'harga_jual_eceran' => $produkhargajual,
                    'harga_beli_eceran' => $produkhargamodal,
                    'margin' => $produkmargin,
                    'jml_eceran' => 1,
                    'tglinput' => date('Y-m-d'),
                    'userinput' => $this->session->userdata('username')
                ]);

                $msg = [
                    'sukses' => 'Produk baru berhasil ditambahkan',
                    'kodeproduk' => $produkkode
                ];
            } else {
                $msg = [
                    'error' => [
                        'produkkode' => form_error('produkkode'),
                        'produknama' => form_error('produknama'),
                        'produksatuan' => form_error('produksatuan'),
                        'produkhargamodal' => form_error('produkhargamodal'),
                        'produkhargajual' => form_error('produkhargajual'),
                    ]
                ];
            }
            echo json_encode($msg);
        }
    }

    function buatfakturotomatis()
    {
        if ($this->input->is_ajax_request()) {
            $tgl = $this->input->post('tgl', true);

            $query = $this->db->query("SELECT MAX(nofaktur) AS nofaktur FROM pembelian WHERE DATE_FORMAT(tglbeli,'%Y-%m-%d') = '$tgl'");
            $hasil = $query->row_array();
            $data  = $hasil['nofaktur'];


            $lastNoUrut = substr($data, 10, 4);

            // nomor urut ditambah 1
            $nextNoUrut = $lastNoUrut + 1;

            // membuat format nomor transaksi berikutnya
            $nextNoTransaksi = '13-' . date('dmy', strtotime($tgl)) . sprintf('%04s', $nextNoUrut);
            $msg = [
                'sukses' => $nextNoTransaksi
            ];
            echo json_encode($msg);
        }
    }

    public function input()
    {
        $view = [
            // 'menu' => $this->load->view('template/menu', '', TRUE),
            // 'judul' => '<i class="fa fa-sort-amount-down"></i> Input Barang Masuk / Pembelian',
            'isi' => $this->load->view('admin/pembelian/input', '', true)

        ];
        $this->parser->parse('layoutkasir/main', $view);
    }

    public function simpanfaktur()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);
            $tglfaktur = $this->input->post('tglfaktur', true);
            $idpemasok = $this->input->post('idpemasok', true);
            $namapemasok = $this->input->post('namapemasok', true);

            $this->form_validation->set_rules('faktur', 'Faktur', 'trim|required|is_unique[pembelian.nofaktur]', [
                'required' => '%s tidak boleh kosong',
                'is_unique' => '%s sudah ada didalam database'
            ]);
            $this->form_validation->set_rules('tglfaktur', 'Tgl.Faktur', 'trim|required', [
                'required' => '%s tidak boleh kosong',
            ]);
            $this->form_validation->set_rules('idpemasok', 'Pemasok', 'trim|required', [
                'required' => '%s tidak boleh kosong',
            ]);


            if ($this->form_validation->run() == TRUE) {
                // Simpan Faktur

                $simpanfaktur = [
                    'nofaktur' => $faktur, 'tglbeli' => $tglfaktur, 'idpemasok' => $idpemasok,
                    'statustransaksi' => 0
                ];
                $this->db->insert('pembelian', $simpanfaktur);

                $msg = [
                    'sukses' => 'Silahkan tambahkan Item'
                ];
            } else {
                $msg = [
                    'error' => [
                        'faktur' => form_error('faktur'),
                        'tglfaktur' => form_error('tglfaktur'),
                        'pemasok' => form_error('idpemasok')
                    ]
                ];
            }

            echo json_encode($msg);
        }
    }

    function tampilforminput()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);
            $tglfaktur = $this->input->post('tglfaktur', true);
            $msg = [
                'data' => $this->load->view('admin/pembelian/forminputitem', ['faktur' => $faktur, 'tglfaktur' => $tglfaktur], TRUE)
            ];
            echo json_encode($msg);
        }
    }

    function forminputproduk()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/pembelian/modalforminputproduk', '', true)
            ];
            echo json_encode($msg);
        }
    }

    public function caripemasok()
    {
        if ($this->input->is_ajax_request()) {
            $data   = [
                'datapemasok' => $this->db->get('pemasok')
            ];
            $msg = [
                'data' => $this->load->view('admin/pembelian/modalcaripemasok', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    public function bataltransaksi()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);

            // cek faktur 
            $cekfaktur = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);

            if ($cekfaktur->num_rows() > 0) {
                // hapus data
                // $this->db->trans_start();
                // $this->db->delete('pembelian_detail', ['detfaktur' => $faktur]);
                // $this->db->delete('pembelian', ['nofaktur' => $faktur]);
                // $this->db->trans_complete();

                // if ($this->db->trans_status() == true) {
                //     $msg = [
                //         'sukses' => 'Transaksi berhasil dibatalkan'
                //     ];
                // }

                // ! Lakukan update atau pengurangan stok pada produk_tglkadaluarsa

                $query_pembeliandetail = $this->db->get_where('pembelian_detail', ['detfaktur' => $faktur])->result();
                foreach ($query_pembeliandetail as $r) :
                    $detqtysat = $r->detqtysat;
                    $detjml = $r->detjml;
                    $sub = $detjml * $detqtysat;

                    //ambil data produk_tglkadaluarsa
                    $query_produk_tglkadaluarsa = $this->db->get_where('produk_tglkadaluarsa', ['tglkadaluarsa' => $r->dettglexpired, 'kodebarcode' => $r->detkodebarcode]);
                    $row_produk_tglkadaluarsa = $query_produk_tglkadaluarsa->row_array();

                    $update_produk_tglkadaluarsa = [
                        'jml' => $row_produk_tglkadaluarsa['jml'] - $sub
                    ];

                    $this->db->where('id', $row_produk_tglkadaluarsa['id']);
                    $this->db->update('produk_tglkadaluarsa', $update_produk_tglkadaluarsa);

                endforeach;
                // !End

                // Neraca
                $this->neraca->hapus_persediaan_dan_hutang($faktur);
                // end neraca

                // Hapus Transaksi Neraca Kas Kecil
                $cek_neraca_kaskecil = $this->db->get_where('neraca_transaksi', [
                    'transno' => $faktur,
                    'transnoakun' => '1-110',
                    'transjenis' => 'D'
                ]);
                if ($cek_neraca_kaskecil->num_rows() > 0) {
                    $row_kaskecil = $cek_neraca_kaskecil->row_array();
                    $this->db->delete('neraca_transaksi', ['transid' => $row_kaskecil['transid']]);
                }
                // End Hapus Transaksi Neraca Kas Kecil


                $this->db->delete('pembelian_detail', ['detfaktur' => $faktur]);
                $this->db->delete('pembelian', ['nofaktur' => $faktur]);
                $msg = [
                    'sukses' => 'Transaksi berhasil Hapus/diBatalkan'
                ];
            } else {
                $msg = [
                    'error' => 'Maaf, tidak ada yang bisa dihapus. Silahkan reload halaman dengan menekan tombol F5'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function tambahpemasok()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/pembelian/modaltambahpemasok', '', true)
            ];
            echo json_encode($msg);
        }
    }

    public function ambildataproduk()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);
            $namaproduk = $this->input->post('namaproduk', true);

            // Ambil data produk detail
            if (strlen($namaproduk) > 0) {
                $query_detailproduk = $this->db->query("SELECT produk.*,satuan.* FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` WHERE kodebarcode = '$kode' OR namaproduk = '$namaproduk'");
            } else {
                $query_detailproduk = $this->db->query("SELECT produk.*,satuan.* FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` WHERE kodebarcode LIKE '%$kode%' OR namaproduk LIKE '%$kode%'");
            }

            if ($query_detailproduk->num_rows() === 1) {
                $row = $query_detailproduk->row_array();

                $data = [
                    'kode' => $row['kodebarcode'],
                    'namaproduk' => $row['namaproduk'],
                    'hargabeli' => number_format($row['harga_beli_eceran'], 2, ".", ","),
                    'hargajual' => number_format($row['harga_jual_eceran'], 2, ".", ","),
                    'margin' => number_format($row['margin'], 2, ".", ","),
                    'idsatuan' => $row['satid'],
                    'namasatuan' => $row['satnama'],
                    'jmleceran' => $row['jml_eceran']
                ];

                $msg = [
                    'ada' => $data
                ];
            } elseif ($query_detailproduk->num_rows() > 1) {
                $dataproduk = [
                    'dataproduk' => $query_detailproduk, 'keyword' => $kode
                ];
                $msg = [
                    'datadetail' => $this->load->view('admin/pembelian/modaldatacariproduk', $dataproduk, TRUE)
                ];
            } else {
                $msg = [
                    'error' => 'Maaf Produk tidak ditemukan'
                ];
            }

            echo json_encode($msg);
        }
    }

    public function daftarproduksemua()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/pembelian/modaldaftarproduk', '', true)
            ];

            echo json_encode($msg);
        }
    }

    public function ambildatadaftarproduk()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/pembelian/Modeldaftarproduk', 'produk');

            $list = $this->produk->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $tombolpilih = "<button type=\"button\" class=\"btn btn-info btn-sm waves-effect waves-light btnpilih\" onclick=\"pilih('" . $field->kodebarcode . "')\" title=\"Pilih Item\">
                    <i class=\"fa fa-hand-point-up\"></i>
                </button>";
                $row[] = $no;
                $row[] = $field->kodebarcode;
                $row[] = $field->namaproduk;
                $row[] = number_format($field->harga_beli_eceran, 2, ".", ",");
                $row[] = number_format($field->margin, 2, ".", ",");
                $row[] = number_format($field->harga_jual_eceran, 2, ".", ",");
                $row[] = $tombolpilih;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->produk->count_all(),
                "recordsFiltered" => $this->produk->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function carisatuanproduk()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);

            $query_produkharga = $this->db->query("SELECT satnama,idsat,id FROM produk_harga JOIN satuan ON satuan.`satid`=idsat WHERE kodebarcode='$kode'");

            if ($query_produkharga->num_rows() > 0) {
                $data = [
                    'datasatuanproduk' => $query_produkharga
                ];

                $msg = [
                    'data' => $this->load->view('admin/pembelian/modalcarisatuanproduk', $data, true)
                ];
            } else {
                $msg = [
                    'error' => 'Tidak ada satuan yang ditemukan'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function ambildataprodukharga()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);
            $satuan = $this->input->post('satuan', true);

            $ambildataprodukharga = $this->db->get_where('produk_harga', ['kodebarcode' => $kode, 'idsat' => $satuan]);

            $ambildatasatuan = $this->db->get_where('satuan', ['satid' => $satuan]);

            if ($ambildataprodukharga->num_rows() > 0) {
                $r = $ambildataprodukharga->row_array();
                $s = $ambildatasatuan->row_array();
                $data = [
                    'namasatuan' => $s['satnama'],
                    'hargabeli' => number_format($r['hargamodal'], 2, ".", ","),
                    'hargajual' => number_format($r['hargajual'], 2, ".", ","),
                    'margin' => number_format($r['margin'], 2, ".", ","),
                    'jmldefault' => $r['jml_default'],
                    'idprodukharga' => $r['id']
                ];

                $msg = [
                    'sukses' => $data
                ];
            }
            echo json_encode($msg);
        }
    }

    public function simpanitem()
    {
        if ($this->input->is_ajax_request()) {
            $iddetail = $this->input->post('iddetail', true);
            $kode = $this->input->post('kode', true);
            $namaproduk = $this->input->post('namaproduk', true);
            $nofaktur = $this->input->post('faktur', true);
            $tglfaktur = $this->input->post('tglfaktur', true);
            $hargabeli_item = str_replace(",", "", $this->input->post('hargabeli', true));
            $dispersen_item = str_replace(",", "", $this->input->post('dispersenitem', true));
            $disuang_item = str_replace(",", "", $this->input->post('disuangitem', true));
            $jml_item = str_replace(",", "", $this->input->post('jml', true));
            $subtotal_item = str_replace(",", "", $this->input->post('subtotalitem', true));
            $hitung_hargabelibersih = $subtotal_item / $jml_item;


            $this->form_validation->set_rules('kode', 'Kode Barcode', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('namaproduk', 'Nama Produk', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $jmlbeli = $this->input->post('jml', true);
                // $hargabeli  = '';
                $hargajual = str_replace(",", "", $this->input->post('hargajual', true));
                // Simpan tabel pembelian detail
                if ($this->input->post('tgled') == '') {
                    $tgled = "0000-00-00";
                } else {
                    $tgled = $this->input->post('tgled', true);

                    // Simpan ke tabel expired produk
                    $cek_tabel_expired_produk = $this->db->get_where('produk_tglkadaluarsa', ['tglkadaluarsa' => $this->input->post('tgled', true), 'kodebarcode' => $kode]);
                    if ($cek_tabel_expired_produk->num_rows() > 0) {
                        $row_ed_produk = $cek_tabel_expired_produk->row_array();
                        //update datanya
                        $dataupdate_ed_produk = [
                            'jml' => $row_ed_produk['jml'] + ($jmlbeli * $this->input->post('qtysatuan', true)),
                            'hargabeli' => $hitung_hargabelibersih,
                            'hargajual' => $hargajual
                        ];
                        $this->db->where('tglkadaluarsa', $tgled);
                        $this->db->update('produk_tglkadaluarsa', $dataupdate_ed_produk);
                    } else {
                        $datasimpan_ed_produk = [
                            'kodebarcode' => $kode,
                            'tglkadaluarsa' => $tgled,
                            'jml' => $jmlbeli * $this->input->post('qtysatuan', true),
                            'hargabeli' => $hitung_hargabelibersih,
                            'hargajual' => $hargajual
                        ];
                        $this->db->insert('produk_tglkadaluarsa', $datasimpan_ed_produk);
                    }
                    // end
                }


                // $subtotal = $jmlbeli * $hargabeli;

                if (strlen($iddetail) > 0) {
                    // lakukan update detail pembelian 
                    $data_updatedetail = [
                        'detfaktur' => $nofaktur,
                        'dettglbeli' => $tglfaktur,
                        'detkodebarcode' => $kode,
                        'detsatid' => $this->input->post('idsatuan', true),
                        'detqtysat' => $this->input->post('qtysatuan', true),
                        'dethrgbelikotor' => $hargabeli_item,
                        'dethrgbeli' => $hitung_hargabelibersih,
                        'detmargin' => str_replace(",", "", $this->input->post('margin', true)),
                        'dethrgjual' => $hargajual,
                        'detjml' => $jml_item,
                        'dettglexpired' => $tgled,
                        'detsubtotal' => $subtotal_item,
                        'detdispersen' => $dispersen_item,
                        'detdisuang' => $disuang_item,
                    ];
                    $this->db->where('detid', $iddetail);
                    $this->db->update('pembelian_detail', $data_updatedetail);
                } else {
                    // lakukan simpan detail pembelian
                    $data_simpandetail = [
                        'detfaktur' => $nofaktur,
                        'dettglbeli' => $tglfaktur,
                        'detkodebarcode' => $kode,
                        'detsatid' => $this->input->post('idsatuan', true),
                        'detqtysat' => $this->input->post('qtysatuan', true),
                        'dethrgbelikotor' => $hargabeli_item,
                        'dethrgbeli' => $hitung_hargabelibersih,
                        'detmargin' => str_replace(",", "", $this->input->post('margin', true)),
                        'dethrgjual' => $hargajual,
                        'detjml' => $jml_item,
                        'dettglexpired' => $tgled,
                        'detsubtotal' => $subtotal_item,
                        'detdispersen' => $dispersen_item,
                        'detdisuang' => $disuang_item,
                    ];
                    $this->db->insert('pembelian_detail', $data_simpandetail);
                }


                //End

                // Update Harga Beli, margin, dan harga jual
                // cek kode produk
                $cekkode = $this->db->get_where('produk', ['kodebarcode' => $kode, 'satid' => $this->input->post('idsatuan', true)]);

                // cek kode di produk harga
                $cekkode_produkharga = $this->db->get_where('produk_harga', ['id' => $this->input->post('idprodukharga', true), 'kodebarcode' => $kode]);

                if ($cekkode->num_rows() > 0) {
                    $updateharga = [
                        'harga_beli_eceran' => $hitung_hargabelibersih,
                        'harga_jual_eceran' => str_replace(",", "", $this->input->post('hargajual', true)),
                        'margin' => str_replace(",", "", $this->input->post('margin', true))
                    ];
                    $this->db->where('kodebarcode', $kode);
                    $this->db->update('produk', $updateharga);
                }
                if ($cekkode_produkharga->num_rows() > 0) {
                    $updatehargax = [
                        'hargamodal' => $hitung_hargabelibersih,
                        'hargajual' => str_replace(",", "", $this->input->post('hargajual', true)),
                        'margin' => str_replace(",", "", $this->input->post('margin', true))
                    ];
                    $this->db->where('id', $this->input->post('idprodukharga', true));
                    $this->db->update('produk_harga', $updatehargax);
                }
                // end

                $msg = [
                    'sukses' => 'Berhasil di-tambahkan'
                ];
            } else {
                $msg = [
                    'error' => [
                        'kode' => form_error('kode'),
                        'namaproduk' => form_error('namaproduk')
                    ]
                ];
            }
            echo json_encode($msg);
        }
    }
    public function datadetailpembelian()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);
            $tglfaktur = $this->input->post('tglfaktur', true);

            $query_detailpembelian = $this->beli->querydetailpembelian($faktur);

            $query_pembelian = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);

            $data = [
                'faktur' => $faktur,
                'tglfaktur' => $tglfaktur,
                'datadetail' => $query_detailpembelian->result(),
                'pembelian' => $query_pembelian->row_array()
            ];

            $msg = [
                'data' => $this->load->view('admin/pembelian/datadetail', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    public function ambilitem()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $kode = $this->input->post('kodebarcode', true);
            $nama = $this->input->post('namaproduk', true);

            $ambildatadetail = $this->db->get_where('pembelian_detail', ['detid' => $id]);

            if ($ambildatadetail->num_rows() > 0) {
                $r = $ambildatadetail->row_array();
                $ambildatasatuan = $this->db->get_where('satuan', ['satid' => $r['detsatid']])->row_array();
                $ambildata_produkharga = $this->db->get_where('produk_harga', [
                    'kodebarcode' => $kode,
                    'idsat' => $r['detsatid']
                ])->row_array();
                $msg = [
                    'sukses' => [
                        'dethrgbelikotor' => $r['dethrgbelikotor'],
                        'detmargin' => $r['detmargin'],
                        'dethrgjual' => $r['dethrgjual'],
                        'detjml' => $r['detjml'],
                        'namasatuan' => $ambildatasatuan['satnama'],
                        'idsatuan' => $r['detsatid'],
                        'qtysatuan' => $r['detqtysat'],
                        'idprodukharga' => $ambildata_produkharga['id'],
                        'detdispersen' => $r['detdispersen'],
                        'detdisuang' => $r['detdisuang'],
                        'detsubtotal' => $r['detsubtotal'],
                        'dettglexpired' => $r['dettglexpired']
                    ]
                ];
                echo json_encode($msg);
            }
        }
    }

    public function hapusitem()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);

            $this->db->delete('pembelian_detail', ['detid' => $id]);

            $msg = [
                'sukses' => 'Item berhasil dihapus !'
            ];
            echo json_encode($msg);
        }
    }

    public function selesaitransaksi()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $faktur = $this->input->post('fakturdatadetail', true);
            $tglfaktur = $this->input->post('tglfaktur', true);
            $totalkotor = $this->input->post('totalkotor', true);
            $totalbersih = str_replace(",", "", $this->input->post('totalbersih', true));
            $pph = str_replace(",", "", $this->input->post('pph', true));
            $diskonpersen = str_replace(",", "", $this->input->post('diskonpersen', true));
            $diskonuang = str_replace(",", "", $this->input->post('diskonuang', true));

            $jenispembayaran = $this->input->post('jenispembayaran', true);
            $jatuhtempo = $this->input->post('jatuhtempo', true);
            $tgljatuhtempo = $this->input->post('tgljatuhtempo', true);


            $this->form_validation->set_rules('jenispembayaran', 'Jenis Pembayaran', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                if ($jenispembayaran == "T") {
                    $simpantgljatuhtempo = $tglfaktur;
                    $statusbayar = 1;
                    $statustransaksi = 1;
                } else {
                    if ($jatuhtempo == "1") {
                        $simpantgljatuhtempo = date('Y-m-d', strtotime("+1 week", strtotime($tglfaktur)));
                    } elseif ($jatuhtempo == "2") {
                        $simpantgljatuhtempo = date('Y-m-d', strtotime("+2 week", strtotime($tglfaktur)));
                    } elseif ($jatuhtempo == "3") {
                        $simpantgljatuhtempo = date('Y-m-d', strtotime("+3 week", strtotime($tglfaktur)));
                    } elseif ($jatuhtempo == "4") {
                        $simpantgljatuhtempo = date('Y-m-d', strtotime("+4 week", strtotime($tglfaktur)));
                    } elseif ($jatuhtempo == "5") {
                        $simpantgljatuhtempo = date('Y-m-d', strtotime($tgljatuhtempo));
                    } else {
                        $simpantgljatuhtempo = $tglfaktur;
                    }
                    $statusbayar = 0;
                    $statustransaksi = 1;
                }

                // Update Pembelian
                $datasimpantransaksi = [
                    'tgljatuhtempo' => $simpantgljatuhtempo,
                    'jenisbayar' => $jenispembayaran,
                    'pph' => $pph,
                    'diskonpersen' => $diskonpersen,
                    'diskonuang' => $diskonuang,
                    'totalkotor' => $totalkotor,
                    'totalbersih' => $totalbersih,
                    'statustransaksi' => $statustransaksi,
                    'statusbayar' => $statusbayar,
                    'userinput' => $this->session->userdata('username')
                ];

                $this->db->where('nofaktur', $faktur);
                $this->db->update('pembelian', $datasimpantransaksi);

                // Simpan saldo hutang pada tabel pemasok atau supplier Jika Pembayaran Kredit
                if ($jenispembayaran == 'K') {
                    $ambil_datapembelian = $this->db->get_where('pembelian', ['nofaktur' => $faktur])->row_array();
                    $idpemasok = $ambil_datapembelian['idpemasok'];

                    $ambil_datapemasok = $this->db->get_where('pemasok', ['id' => $idpemasok])->row_array();
                    $saldohutang = $ambil_datapemasok['saldohutang'];

                    $this->db->where('id', $idpemasok);
                    $this->db->update('pemasok', [
                        'saldohutang' => $saldohutang + $totalbersih
                    ]);
                }
                // End Simpan saldo hutang pada tabel pemasok atau supplier Jika Pembayaran Kredit


                // Simpan ke neraca akun 2-110 Hutang Dagang jika kredit & persediaan barang
                $this->neraca->simpan_transaksi_pembelian($faktur);
                // End Simpan ke neraca akun 2-110 Hutang Dagang jika kredit & persediaan barang

                // Simpan ke Neraca Kas Kecil Jika Pembayaran Tunai
                if ($jenispembayaran == 'T') {
                    $cek_neraca_kaskecil = $this->db->get_where('neraca_transaksi', [
                        'transno' => $faktur,
                        'transnoakun' => '1-110',
                        'transjenis' => 'D'
                    ]);

                    if ($cek_neraca_kaskecil->num_rows() > 0) {
                        $row_kaskecil = $cek_neraca_kaskecil->row_array();
                        $update_neraca_kaskecil = [
                            'transjml' => $totalbersih
                        ];
                        $this->db->where('transid', $row_kaskecil['transid']);
                        $this->db->update('neraca_transaksi', $update_neraca_kaskecil);
                    } else {
                        $insert_neraca_kaskecil = [
                            'transno' => $faktur,
                            'transtgl' => $tglfaktur,
                            'transnoakun' => '1-110',
                            'transjenis' => 'D',
                            'transjml' => $totalbersih,
                            'transket' => 'Faktur Pembelian'
                        ];
                        $this->db->insert('neraca_transaksi', $insert_neraca_kaskecil);
                    }
                }
                // End Simpan ke Neraca Kas Kecil Jika Pembayaran Tunai

                $msg = [
                    'sukses' => 'Transaksi berhasil disimpan',
                    'faktur' => $faktur,
                    'jenispembayaran' => $jenispembayaran,
                    'cetakpengeluarankas' => site_url('beli/cetakpengeluarankas/') . sha1($faktur)
                ];
            } else {
                $msg = [
                    'error' => [
                        'jenispembayaran' => form_error('jenispembayaran')
                    ]
                ];
            }
            echo json_encode($msg);
        }
    }

    public function tampiloghargaproduk()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $kode = $this->input->post('kode', true);

            if ($kode == '') {
                $msg = [
                    'error' => 'Kode barcode masih kosong...!!!'
                ];
            } else {
                $ambilproduk = $this->db->get_where('produk', ['kodebarcode' => $kode]);
                $rproduk = $ambilproduk->row_array();

                $query = $this->db->query("SELECT detfaktur AS faktur,detkodebarcode AS kode,namaproduk,dethrgbeli AS hargabeli,detmargin AS margin,dethrgjual AS hargajual,satnama,pemasok.`nama` AS namapemasok
                FROM pembelian_detail JOIN pembelian ON detfaktur=nofaktur JOIN produk ON detkodebarcode=kodebarcode JOIN satuan ON satuan.`satid`=detsatid JOIN pemasok ON pemasok.`id`=pembelian.`idpemasok` WHERE detkodebarcode='$kode' ORDER BY pembelian.`tglbeli` DESC")->result();

                if (count($query) > 0) {
                    $data = ['tampildata' => $query, 'namaproduk' => $rproduk['namaproduk']];
                    $msg = [
                        'data' => $this->load->view('admin/pembelian/modallogdatapembelianproduk', $data, true)
                    ];
                } else {
                    $msg = [
                        'error' => 'Maaf belum ada log pembelian untuk produk ini...'
                    ];
                }
            }
            echo json_encode($msg);
        }
    }

    // data pembelian
    public function data()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Data Faktur Pembelian',
            'isi' => $this->load->view('admin/pembelian/data', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function semuadata()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/pembelian/data/semua', '', true)
            ];
            echo json_encode($msg);
        }
    }

    public function ambilsemuadata()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/pembelian/Modeldatasemuapembelian', 'datasemua');
            $tglawal = $this->input->post('tglawal', true);
            $tglakhir = $this->input->post('tglakhir', true);
            $list = $this->datasemua->get_datatables($tglawal, $tglakhir);
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $tombolhapus = "<button type=\"button\" title=\"Hapus Data\" class=\"btn btn-sm btn-outline-danger\" onclick=\"hapus('" . $field->nofaktur . "')\"><i class=\"fa fa-trash-alt\"></i></button>";
                $tomboledit = "<button type=\"button\" title=\"Edit Data\" class=\"btn btn-sm btn-outline-info\" onclick=\"edit('" . sha1($field->nofaktur) . "')\"><i class=\"fa fa-pencil-alt\"></i></button>";
                $cetakpengeluarankas = "<button type=\"button\" title=\"Cetak Bukti Pengeluaran Kas\" class=\"btn btn-sm btn-outline-success\" onclick=\"cetakpengeluarankas('" . sha1($field->nofaktur) . "')\"><i class=\"fa fa-print\"></i></button>";

                if ($field->statusbayar == 1) {
                    $tombolcetak = $cetakpengeluarankas;
                } else {
                    $tombolcetak = '';
                }

                $statustransaksi = ($field->statustransaksi == 0) ? "<br><span class=\"badge badge-danger\">Belum Selesai</span>" : '';

                $row[] = $no;
                $row[] = "<a href=\"#\" onclick=\"edit('" . sha1($field->nofaktur) . "')\">$field->nofaktur</a>" . $statustransaksi;
                $row[] = date('d-m-Y', strtotime($field->tglbeli));
                $row[] = $field->nama;
                if ($field->jenisbayar == 'T') {
                    $jenispembayaran = "<span class=\"badge badge-success\">Tunai</span>";
                } else {
                    $jenispembayaran = "<span class=\"badge badge-warning\">Kredit</span>";
                }
                $row[] = $jenispembayaran;
                // Jumlah Item
                $q_detailpembelian = $this->db->query("SELECT COUNT(detid) AS jml FROM pembelian_detail WHERE detfaktur = '$field->nofaktur'")->row_array();
                $row[] = "<a href=\"#\" onclick=\"detailitem('" . $field->nofaktur . "')\">$q_detailpembelian[jml]</a>";
                // End
                $row[] = number_format($field->totalkotor, 2, ".", ",");
                $row[] = number_format($field->totalbersih, 2, ".", ",");
                if ($field->statusbayar == 1) {
                    $statusbayar = "<span class=\"badge badge-success\">Sudah Lunas</span>";
                } else {
                    $statusbayar = "<span class=\"badge badge-warning\">Belum Lunas</span>";
                }
                $row[] = $statusbayar;
                $row[] = $tombolhapus . ' ' . $tomboledit . ' ' . $tombolcetak;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->datasemua->count_all($tglawal, $tglakhir),
                "recordsFiltered" => $this->datasemua->count_filtered($tglawal, $tglakhir),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function detailitempembelian()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);
            $detailitem = $this->db->query("SELECT pembelian_detail.*, produk.`namaproduk`,satuan.`satnama` FROM pembelian_detail JOIN produk ON kodebarcode=detkodebarcode
            JOIN satuan ON satuan.`satid`=detsatid WHERE detfaktur='$faktur'");
            $data = [
                'tampildetail' => $detailitem,
                'faktur' => $faktur
            ];
            $msg = [
                'data' => $this->load->view('admin/pembelian/data/modaldetailitem', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    public function datahutang()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/pembelian/data/hutang', '', true)
            ];
            echo json_encode($msg);
        }
    }

    public function ambildatahutang()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/pembelian/Modeldatahutang', 'datasemua');

            $list = $this->datasemua->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $tombolbayar = "<button type=\"button\" class=\"btn btn-info btn-sm waves-effect waves-light\" onclick=\"bayar('" . sha1($field->nofaktur) . "')\" title=\"Bayar\">
                    Bayar
                </button>";
                $row[] = $no;
                $row[] = $field->nofaktur;
                $row[] = date('d-m-Y', strtotime($field->tglbeli));
                $row[] = $field->nama;
                if ($field->tgljatuhtempo == 'NULL' || $field->tgljatuhtempo == '0000-00-00') {
                    $tgljatuhtempo = '';
                } else {
                    $tgljatuhtempo = date('d-m-Y', strtotime($field->tgljatuhtempo));
                }
                $row[] = $tgljatuhtempo;
                $row[] = number_format($field->totalkotor, 2, ".", ",");
                $row[] = number_format($field->totalbersih, 2, ".", ",");

                if ($field->statusbayar == '1') {
                    $statusbayar = "<span class=\"badge badge-success\">Lunas</span>";
                } else {
                    $statusbayar = "<span class=\"badge badge-danger\">Belum Lunas</span>";
                }
                $row[] = $statusbayar;
                if ($field->statusbayar == 1) {
                    $tombollihat = "<button type=\"button\" class=\"btn btn-sm btn-pinterest\" onclick=\"editbayar('" . sha1($field->nofaktur) . "')\" title=\"Bayar\">
                    Bayar
                </button>";
                    $row[] = "<span class=\"badge badge-success\">Sudah Bayar Tgl. " . date('d-m-Y', strtotime($field->tglpembayarankredit)) . "</span>";
                } else {

                    $row[] = $tombolbayar;
                }
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->datasemua->count_all(),
                "recordsFiltered" => $this->datasemua->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function faktur_jatuh_tempo()
    {
        $data = [
            'data' => $this->beli->tampilnotifhutangjatuhtempo()
        ];

        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Faktur Jatuh Tempo 3 Hari Ter-akhir',
            'isi' => $this->load->view('admin/pembelian/data/jatuhtempo', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function daftar_hutang()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Data Daftar Hutang',
            'isi' => $this->load->view('admin/pembelian/data/hutang', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function bayar_hutang($faktur)
    {
        $datapembelian = $this->db->get_where('pembelian', ['SHA1(nofaktur)' => $faktur]);
        if ($datapembelian->num_rows() > 0) {
            $result = $datapembelian->row_array();
            $datapemasok = $this->db->get_where('pemasok', ['id' => $result['idpemasok']]);
            $rpemasok = $datapemasok->row_array();
            $data = [
                'faktur' => $result['nofaktur'],
                'tglbeli' => date('d-m-Y', strtotime($result['tglbeli'])),
                'namapemasok' => $rpemasok['nama'],
                'tgljatuhtempo' => date('d-m-Y', strtotime($result['tgljatuhtempo'])),
                'totalbersih' => number_format($result['totalbersih'], 2, ".", ","),
                'totalkotor' => number_format($result['totalkotor'], 2, ".", ","),
                'pph' => number_format($result['pph'], 2, ".", ","),
                'diskonpersen' => number_format($result['diskonpersen'], 2, ".", ","),
                'diskonuang' => number_format($result['diskonuang'], 2, ".", ","),
            ];

            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-store"></i> Pembayaran Hutang Faktur : ' . $result['nofaktur'],
                'isi' => $this->load->view('admin/pembelian/data/formbayarhutang', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            redirect('beli/faktur-jatuh-tempo');
        }
    }

    function simpanbayarhutang()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);
            $tglbayar = $this->input->post('tglbayar', true);
            $jmlbayar = str_replace(",", "", $this->input->post('jmlbayar', true));
            $pesan = $this->input->post('pesan', true);

            $this->form_validation->set_rules('tglbayar', 'Tgl.Bayar', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('jmlbayar', 'Jumlah Pembayaran', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $datapembelian = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);
                $row = $datapembelian->row_array();

                if ($row['totalbersih'] != $jmlbayar) {
                    $msg = [
                        'error' => [
                            'jmlbayar' => 'Jumlah Pembayaran Harus sama dengan hutang yang dibayarkan'
                        ]
                    ];
                } else {
                    // Update Saldo Hutang Pemasok
                    $ambil_datapembelian = $this->db->get_where('pembelian', ['nofaktur' => $faktur])->row_array();
                    $idpemasok = $ambil_datapembelian['idpemasok'];
                    $statusbayar = $ambil_datapembelian['statusbayar'];

                    $ambil_datapemasok = $this->db->get_where('pemasok', ['id' => $idpemasok])->row_array();
                    $saldohutang = $ambil_datapemasok['saldohutang'];

                    if ($statusbayar == 0) {
                        $this->db->where('id', $idpemasok);
                        $this->db->update('pemasok', [
                            'saldohutang' => $saldohutang - $jmlbayar
                        ]);
                    }
                    // End Update Saldo Hutang Pemasok

                    // Update faktur

                    $updatedata = [
                        'statusbayar' => 1,
                        'tglpembayarankredit' => $tglbayar,
                        'jmlpembayarankredit' => $jmlbayar,
                        'isipesanhutang' => $pesan,
                        'userinput' => $this->session->userdata('username')
                    ];

                    $this->db->where('nofaktur', $faktur);
                    $this->db->update('pembelian', $updatedata);



                    // Neraca, Hapus piutang dagang
                    $this->neraca->hapus_hutang_dagang($faktur);
                    // End Neraca

                    // Neraca Kas Kecil
                    $cek_neraca_kaskecil = $this->db->get_where('neraca_transaksi', [
                        'transno' => $faktur,
                        'transnoakun' => '1-110',
                        'transjenis' => 'D'
                    ]);

                    if ($cek_neraca_kaskecil->num_rows() > 0) {
                        $row_kaskecil = $cek_neraca_kaskecil->row_array();
                        $update_neraca_kaskecil = [
                            'transjml' => $jmlbayar,
                            'transtgl' => $tglbayar
                        ];
                        $this->db->where('transid', $row_kaskecil['transid']);
                        $this->db->update('neraca_transaksi', $update_neraca_kaskecil);
                    } else {
                        $insert_neraca_kaskecil = [
                            'transno' => $faktur,
                            'transtgl' => $tglbayar,
                            'transnoakun' => '1-110',
                            'transjenis' => 'D',
                            'transjml' => $jmlbayar,
                            'transket' => 'Pembayaran Hutang'
                        ];
                        $this->db->insert('neraca_transaksi', $insert_neraca_kaskecil);
                    }
                    // End Neraca Kas Kecil

                    $msg = [
                        'sukses' => 'Hutang berhasil dibayarkan',
                        'link' => site_url() . 'beli/daftar-hutang',
                        'cetakkonsinyasi' => site_url() . 'beli/cetak-konsinyasi/' . sha1($faktur)
                    ];
                }
            } else {
                $msg = [
                    'error' => [
                        'tglbayar' => form_error('tglbayar'),
                        'jmlbayar' => form_error('jmlbayar')
                    ]
                ];
            }

            echo json_encode($msg);
        }
    }

    function cetak_konsinyasi($faktur)
    {
        $ambildatapembelian = $this->db->get_where('pembelian', ['sha1(nofaktur)' => $faktur]);
        if ($ambildatapembelian->num_rows() > 0) {
            $row_pembelian = $ambildatapembelian->row_array();
            $query_toko = $this->db->get_where('nn_namatoko', ['idtoko' => 1]);
            $row_toko = $query_toko->row_array();

            // Ambil data total pembelian dari table detail pembelian
            $totalpembelian = $this->db->query("SELECT IFNULL(SUM(detjml*dethrgbeli),0) AS totalbeli FROM pembelian_detail WHERE detfaktur = '$row_pembelian[nofaktur]'")->row_array();

            $sisahutang = $totalpembelian['totalbeli'] - $row_pembelian['jmlpembayarankredit'];

            $data = [
                'nofaktur' => $row_pembelian['nofaktur'],
                'namatoko' => $row_toko['nmtoko'],
                'alamattoko' => $row_toko['alamat'],
                'telptoko' => $row_toko['telp'],
                'hptoko' => $row_toko['hp'],
                'tanggal' => date('d-m-Y', strtotime($row_pembelian['tglbeli'])),
                'totalbeli' => number_format($totalpembelian['totalbeli'], 0, ",", "."),
                'tanggalbayarhutang' => date('d-m-Y', strtotime($row_pembelian['tglpembayarankredit'])),
                'jumlahbayarhutang' => number_format($row_pembelian['jmlpembayarankredit'], 0, ",", "."),
                'sisabayar' => number_format($sisahutang, 0, ",", "."),
                'tglbayar' => date('d M Y', strtotime($row_pembelian['tglpembayarankredit'])),
            ];
            $this->load->view('admin/pembelian/data/cetak_laporan_konsinyasi', $data);
        }
    }

    public function edit_bayar_hutang($faktur)
    {
        $datapembelian = $this->db->get_where('pembelian', ['SHA1(nofaktur)' => $faktur]);
        if ($datapembelian->num_rows() > 0) {
            $result = $datapembelian->row_array();
            $datapemasok = $this->db->get_where('pemasok', ['id' => $result['idpemasok']]);
            $rpemasok = $datapemasok->row_array();
            $data = [
                'faktur' => $result['nofaktur'],
                'tglbeli' => date('d-m-Y', strtotime($result['tglbeli'])),
                'namapemasok' => $rpemasok['nama'],
                'tgljatuhtempo' => date('d-m-Y', strtotime($result['tgljatuhtempo'])),
                'totalbersih' => number_format($result['totalbersih'], 2, ".", ","),
                'totalkotor' => number_format($result['totalkotor'], 2, ".", ","),
                'pph' => number_format($result['pph'], 2, ".", ","),
                'diskonpersen' => number_format($result['diskonpersen'], 2, ".", ","),
                'diskonuang' => number_format($result['diskonuang'], 2, ".", ","),
                'tglpembayarankredit' => $result['tglpembayarankredit'],
                'isipesan' => $result['isipesanhutang'],
                'jmlpembayarankredit' => $result['jmlpembayarankredit'],
            ];

            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-store"></i> Pembayaran Hutang Faktur : ' . $result['nofaktur'],
                'isi' => $this->load->view('admin/pembelian/data/editbayarhutang', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            redirect('beli/faktur-jatuh-tempo');
        }
    }

    public function edit_faktur($faktur)
    {
        $query = $this->db->get_where('pembelian', ['sha1(nofaktur)' => $faktur]);

        if ($query->num_rows() > 0) {
            $d = $query->row_array();
            $query_pemasok = $this->db->get_where('pemasok', ['id' => $d['idpemasok']]);

            $data = [
                'data' => $d,
                'pemasok' => $query_pemasok->row_array()
            ];
            $view = [
                'isi' => $this->load->view('admin/pembelian/edit', $data, true)

            ];
            $this->parser->parse('layoutkasir/main', $view);
        } else {
            redirect('beli/data');
        }
    }

    public function pilihyangmenerima()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);
            $data = [
                'datauser' => $this->db->query("SELECT usernama FROM nn_users WHERE usergrup != 4"),
                'faktur' => $faktur
            ];
            $msg = ['data' => $this->load->view('admin/pembelian/data/modalpilihyangmenerima', $data, true)];
            echo json_encode($msg);
        }
    }

    public function cetakpengeluarankas($faktur)
    {
        // $faktur = $this->input->post('faktur', true);
        $this->load->model('Modeltoko', 'toko');
        $datapembelian = $this->db->get_where('pembelian', ['SHA1(nofaktur)' => $faktur])->row_array();
        $datapemasok = $this->db->get_where('pemasok', ['id' => $datapembelian['idpemasok']])->row_array();
        $datauser = $this->db->get_where('nn_users', ['userid' => $datapembelian['userinput']])->row_array();

        $data = [
            'pemasok' => $datapemasok['nama'],
            'totalbersih' => $datapembelian['totalbersih'],
            'tglbeli' => date('d M Y', strtotime($datapembelian['tglbeli'])),
            'untukpembayaran' => 'Pembelian persediaan barang dagang',
            'toko' => $this->toko->datatoko()->row_array(),
            'terbilang' => $this->terbilang($datapembelian['totalbersih']),
            // 'namauser' => $this->input->post('namauser', true),
            'namauser' => $this->session->userdata('namalengkap'),

        ];
        $this->load->view('admin/pembelian/cetakfakturpengeluarankas', $data);
    }

    // Membuat Fungsi terbilang
    function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->penyebut($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai / 10) . " puluh" . $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai / 100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai / 1000) . " ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai / 1000000) . " juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai / 1000000000) . " milyar" . $this->penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai / 1000000000000) . " trilyun" . $this->penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim($this->penyebut($nilai));
        } else {
            $hasil = trim($this->penyebut($nilai));
        }
        return $hasil;
    }
    // End

    // Return
    public function return_input()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-exchange-alt"></i> Return Pembelian Item',
            'isi' => $this->load->view('admin/pembelian/return/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function return_carifaktur()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/pembelian/return/modalfakturpembelian', '', true)
            ];
            echo json_encode($msg);
        }
    }

    public function return_ambildatafakturpembelian()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/pembelian/Modelreturndatapembelian', 'fakturpembelian');
            $list = $this->fakturpembelian->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $row[] = $no;
                $row[] = $field->nofaktur;
                $row[] = date('d-m-Y', strtotime($field->tglbeli));
                $row[] = $field->nama;
                if ($field->jenisbayar == 'T') {
                    $jenispembayaran = "<span class=\"badge badge-success\">Tunai</span>";
                } else {
                    $jenispembayaran = "<span class=\"badge badge-warning\">Kredit</span>";
                }
                $row[] = $jenispembayaran;
                $row[] = number_format($field->totalbersih, 2, ".", ",");
                $row[] = "<button type=\"button\" class=\"btn btn-sm btn-outline-info\" onclick=\"pilih('" . $field->nofaktur . "','" . $field->tglbeli . "','" . $field->idpemasok . "','" . $field->nama . "')\">Pilih</button>";
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->fakturpembelian->count_all(),
                "recordsFiltered" => $this->fakturpembelian->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    function ambil_data_pembelian()
    {
        // if (isset($_GET['term'])) {
        //     $result = $this->beli->caridatapembelian($_GET['term']);
        //     if (count($result) > 0) {
        //         foreach ($result as $row) {
        //             $arr_result[] = $row->nofaktur . "-Tanggal : $row->tglbeli - $row->nama";
        //             echo json_encode($arr_result);
        //         }
        //     }
        // }

        if (isset($_GET['term'])) {
            $result = $this->beli->caridatapembelian($_GET['term']);
            if (count($result) > 0) {
                foreach ($result as $row) :
                    // $arr_result[] = $row->nofaktur . "-Tanggal : $row->tglbeli - $row->nama";
                    $arr_result[] = array(
                        'label' => "Faktur : $row->nofaktur, Tgl.Faktur : $row->tglbeli, Pemasok : $row->nama",
                        'nofaktur' => $row->nofaktur,
                        'tglbeli' => $row->tglbeli,
                        'nama' => $row->nama,
                        'idpemasok' => $row->idpemasok
                    );
                endforeach;
                echo json_encode($arr_result);
            }
        }
    }

    function tampilkan_item_return()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);

            $query_detailpembelian = $this->beli->querydetailpembelian($faktur);

            $msg = [
                'data' => $this->load->view('admin/pembelian/return/datadetail', ['datadetail' => $query_detailpembelian->result()], true)
            ];

            echo json_encode($msg);
        }
    }

    function buatnomor_return()
    {
        $tglhariini = date('Y-m-d');
        $query = $this->db->query("SELECT MAX(blreturnid) AS idreturn FROM pembelian_return WHERE DATE_FORMAT(blreturntgl,'%Y-%m-%d') = '$tglhariini'");
        $hasil = $query->row_array();
        $data  = $hasil['idreturn'];


        $lastNoUrut = substr($data, 10, 5);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = 'RB-' . date('dmy', strtotime($tglhariini)) . sprintf('%05s', $nextNoUrut);
        return $nextNoTransaksi;
    }

    function buatnomor_return_lagi()
    {
        $tglhariini = $this->input->post('tglreturn', true);
        $query = $this->db->query("SELECT MAX(blreturnid) AS idreturn FROM pembelian_return WHERE DATE_FORMAT(blreturntgl,'%Y-%m-%d') = '$tglhariini'");
        $hasil = $query->row_array();
        $data  = $hasil['idreturn'];


        $lastNoUrut = substr($data, 10, 5);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = 'RB-' . date('dmy', strtotime($tglhariini)) . sprintf('%05s', $nextNoUrut);
        $msg = [
            'idreturn' => $nextNoTransaksi
        ];
        echo json_encode($msg);
    }

    function detailReturnProduk()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');

            $query = $this->beli->ambildata_detailpembelian_pemasok($id);

            $data = [
                'idreturn' => $this->buatnomor_return(),
                'data' => $query
            ];

            $msg = [
                'data' => $this->load->view('admin/pembelian/return/modalreturnitem', $data, true)
            ];

            echo json_encode($msg);
        }
    }

    function tampildata_statusreturn()
    {
        if ($this->input->is_ajax_request()) {
            // Menampilkan Data Satuan
            $datastatus = $this->db->get('status_return')->result();

            $datax = "<option value=\"\">-Silahkan Pilih-</option>";
            foreach ($datastatus as $x) {
                $datax .= "<option value='" . $x->id . "'>" . $x->nmstt . "</option>";
            }


            $msg = [
                'data' => $datax,
            ];
            echo json_encode($msg);
        }
    }

    function simpanbaru_statusreturn()
    {
        if ($this->input->is_ajax_request()) {
            $stt = $this->input->post('stt', true);

            $datasimpan = [
                'nmstt' => $stt
            ];
            $this->db->insert('status_return', $datasimpan);

            $msg = [
                'sukses' => ''
            ];
            echo json_encode($msg);
        }
    }
    function hapus_statusreturn()
    {
        if ($this->input->is_ajax_request()) {
            $stt = $this->input->post('stt', true);

            $this->db->delete('status_return', ['id' => $stt]);

            $msg = [
                'sukses' => ''
            ];
            echo json_encode($msg);
        }
    }

    function simpan_transaksi_return()
    {
        if ($this->input->is_ajax_request()) {
            $nofaktur  = $this->input->post('nofaktur', true);
            $id  = $this->input->post('id', true);
            $qty  = $this->input->post('qty', true);
            $detjmlreturn  = $this->input->post('detjmlreturn', true);
            $jmlreturn  = str_replace(",", "", $this->input->post('jmlreturn', true));
            $stt = $this->input->post('stt', true);
            $ket = $this->input->post('ket', true);
            $tglreturn = $this->input->post('tglreturn', true);
            $idreturn = $this->input->post('idreturn', true);

            $this->form_validation->set_rules('jmlreturn', 'Inputan Jumlah Return', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('stt', 'Status Return', 'trim|required', [
                'required' => '%s wajib dipilih'
            ]);
            $this->form_validation->set_rules('tglreturn', 'Tanggal Return', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                if ($jmlreturn > ($qty - $detjmlreturn)) {
                    $msg = [
                        'error' => [
                            'jmlreturn' => 'Jumlah return tidak boleh melebihi jumlah stok yang tersisa',
                        ]
                    ];
                } else {

                    // Ambil data detail pembelian
                    $q_detail = $this->db->get_where('pembelian_detail', ['detid' => $id])->row_array();

                    $simpan_return = [
                        'blreturnid' => $idreturn,
                        'blreturntgl' => $tglreturn,
                        'blreturndetid' => $id,
                        'blreturnkodebarcode' => $q_detail['detkodebarcode'],
                        'blreturndetsatid' => $q_detail['detsatid'],
                        'blreturndetqtysat' => $q_detail['detqtysat'],
                        'blreturndethrgbeli' => $q_detail['dethrgbeli'],
                        'blreturnjml' => $jmlreturn,
                        'blreturnstatusid' => $stt,
                        'blreturnket' => $ket
                    ];

                    $this->db->insert('pembelian_return', $simpan_return);

                    // update total dari tabel pembelian
                    $query_subtotal = $this->db->query("SELECT SUM(detsubtotal) AS subtotal FROM pembelian_detail WHERE detfaktur='$nofaktur'")->row_array();
                    $subtotal = $query_subtotal['subtotal'];

                    $ambil_pembelian = $this->db->get_where('pembelian', ['nofaktur' => $nofaktur]);
                    $rpembelian = $ambil_pembelian->row_array();
                    $pph = $rpembelian['pph'];
                    $diskonpersen = $rpembelian['diskonpersen'];
                    $diskonuang = $rpembelian['diskonuang'];

                    $hitungpph = $subtotal + ($subtotal * $pph / 100);
                    $hitungtotalbersih = $hitungpph - ($hitungpph * $diskonpersen / 100) - $diskonuang;

                    $update_pembelian = [
                        'totalkotor' => $subtotal,
                        'totalbersih' => $hitungtotalbersih
                    ];

                    $this->db->where('nofaktur', $nofaktur);
                    $this->db->update('pembelian', $update_pembelian);

                    // Simpan ke neraca akun 2-110 Hutang Dagang
                    $this->neraca->simpan_return_pembelian($nofaktur, $idreturn, $tglreturn, $jmlreturn * $q_detail['dethrgbeli']);
                    // end

                    // Kurangi Neraca Kas Kecil 1-110
                    // $this->db->insert('neraca_transaksi', [
                    //     'transno' => $idreturn,
                    //     'transtgl' => $tglreturn,
                    //     'transnoakun' => '1-110',
                    //     'transjenis' => 'K',
                    //     'transjml' => $jmlreturn * $q_detail['dethrgbeli'],
                    //     'transket'  => 'Return Produk'
                    // ]);
                    // End Kurangi Neraca Kas Kecil 1-110

                    $msg = [
                        'sukses' => 'Return Item produk berhasil di-eksekusi'
                    ];
                }
            } else {
                $msg = [
                    'error' => [
                        'jmlreturn' => form_error('jmlreturn'),
                        'stt' => form_error('stt'),
                        'tglreturn' => form_error('tglreturn')
                    ]
                ];
            }

            echo json_encode($msg);
        }
    }

    public function return_data()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Data Return Item Produk',
            'isi' => $this->load->view('admin/pembelian/return/data', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function return_tabel()
    {
        $msg = [
            'data' => $this->load->view('admin/pembelian/return/viewdata', '', true)
        ];
        echo json_encode($msg);
    }

    function return_ambildata()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/pembelian/Modeldatareturn', 'datareturn');

            $list = $this->datareturn->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $tombolhapus = "<button type=\"button\" class=\"btn btn-danger btn-sm waves-effect waves-light\" onclick=\"hapusreturn('" . $field->blreturnid . "','" . $field->detfaktur . "')\" title=\"Hapus Return ID\">
                    <i class=\"fa fa-trash-alt\"></i>
                </button>";
                $row[] = $no;
                $row[] = date('d-m-Y', strtotime($field->blreturntgl));
                $row[] = $field->detfaktur;
                $row[] = $field->nama;
                $row[] = $field->blreturnkodebarcode . '/' . $field->namaproduk;
                $row[] = $field->blreturnjml;
                $row[] = $field->nmstt;
                $row[] = $tombolhapus;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->datareturn->count_all(),
                "recordsFiltered" => $this->datareturn->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    function return_hapusitem()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $nofaktur = $this->input->post('faktur', true);

            $ambildatareturn = $this->db->get_where('pembelian_return', ['blreturnid' => $id]);
            $r_datareturn = $ambildatareturn->row_array();

            // Neraca
            $this->neraca->hapus_return_pembelian($nofaktur, $id);
            // End neraca

            // Kurangi Neraca Kas Kecil 1-110
            $this->db->delete('neraca_transaksi', [
                'transno' => $id,
                'transnoakun' => '1-110',
                'transjenis' => 'K',
            ]);
            // End Kurangi Neraca Kas Kecil 1-110te

            // Hapus return
            $this->db->delete('pembelian_return', ['blreturnid' => $id]);

            // update total dari tabel pembelian
            $query_subtotal = $this->db->query("SELECT SUM(detsubtotal) AS subtotal FROM pembelian_detail WHERE detfaktur='$nofaktur'")->row_array();
            $subtotal = $query_subtotal['subtotal'];

            $ambil_pembelian = $this->db->get_where('pembelian', ['nofaktur' => $nofaktur]);
            $rpembelian = $ambil_pembelian->row_array();
            $pph = $rpembelian['pph'];
            $diskonpersen = $rpembelian['diskonpersen'];
            $diskonuang = $rpembelian['diskonuang'];

            $hitungpph = $subtotal + ($subtotal * $pph / 100);
            $hitungtotalbersih = $hitungpph - ($hitungpph * $diskonpersen / 100) - $diskonuang;

            $update_pembelian = [
                'totalkotor' => $subtotal,
                'totalbersih' => $hitungtotalbersih
            ];

            $this->db->where('nofaktur', $nofaktur);
            $this->db->update('pembelian', $update_pembelian);




            $msg = [
                'sukses' => 'Data return berhasil dihapus'
            ];

            echo json_encode($msg);
        }
    }
    // End


}