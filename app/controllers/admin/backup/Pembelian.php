<?php
class Pembelian extends CI_Controller
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
    public function view()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-truck-moving"></i> Transaksi Pembelian',
            'isi' => $this->load->view('admin/pembelian/view', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-truck-moving"></i> Input Transaksi Pembelian',
            'isi' => $this->load->view('admin/pembelian/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function caridatapemasok()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->view('admin/pembelian/modalcaripemasok');
        }
    }

    function ambildatapemasok()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/Modelpemasok', 'pemasok');
            $list = $this->pemasok->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $pilih = "<button type=\"button\" title=\"Pilih Data\" class=\"btn btn-sm btn-danger\" onclick=\"pilih('" . $field->id . "','" . $field->nama . "')\">
                        <i class=\"fa fa-hand-pointer\"></i> Pilih
                    </button>";

                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $field->nama;
                $row[] = $field->alamat;
                $row[] = $field->telp;
                $row[] = $pilih;
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
        }
    }

    public function simpanfaktur()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $tgl = $this->input->post('tgl', true);
            $idpemasok = $this->input->post('idpemasok', true);

            $this->form_validation->set_rules('faktur', 'No.Faktur', 'trim|required|is_unique[pembelian.nofaktur]', [
                'required' => '%s tidak boleh kosong',
                'is_unique' => '%s yang diinputkan tidak boleh sama'
            ]);
            $this->form_validation->set_rules('tgl', 'Tgl.Faktur', 'trim|required', [
                'required' => '%s tidak boleh kosong',
            ]);


            if ($this->form_validation->run() == TRUE) {
                // Simpan Faktur ke tabel pembelian
                $datasimpanfaktur = [
                    'nofaktur' =>  $faktur,
                    'tglbeli' => date('Y-m-d', strtotime($tgl)),
                    'idpemasok' => $idpemasok,
                    'statustransaksi' => 0,
                    'userinput' => $this->session->userdata('username')
                ];
                $this->db->insert('pembelian', $datasimpanfaktur);

                $msg = [
                    'sukses' => 'Berhasil disimpan, silahkan lanjutkan mengisi item produk'
                ];
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <strong>Error !</strong>' . validation_errors() . '
                                </div>'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function bataltransaksi()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);

            //cek faktur pada table pembelian
            $cekdatafaktur = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);

            if ($cekdatafaktur->num_rows() > 0) {
                // Update stok produk ketika menghapus detail pembelian
                $query_detailpembelian = $this->db->get_where('pembelian_detail', ['detfaktur' => $faktur]);
                foreach ($query_detailpembelian->result_array() as $row_detail) :
                    $detqtysat = $row_detail['detqtysat'];
                    $detjml = $row_detail['detjml'];
                    $sub_jml = $detqtysat * $detjml;
                    $dettglexpired = $row_detail['dettglexpired'];

                    // Ambil data produk
                    $ambildataproduk = $this->db->get_where('produk', ['kodebarcode' => $row_detail['detkodebarcode']]);
                    $d_produk = $ambildataproduk->row_array();
                    $stok_tersedia = $d_produk['stok_tersedia'];
                    // Update stok produk
                    $update_stokproduk = [
                        'stok_tersedia' => $stok_tersedia - $sub_jml
                    ];
                    $this->db->where('kodebarcode', $row_detail['detkodebarcode']);
                    $this->db->update('produk', $update_stokproduk);

                    // update stok pada tabel produk_tglkadaluarsa
                    $ambildataproduk_ed = $this->db->get_where('produk_tglkadaluarsa', ['tglkadaluarsa' => $dettglexpired, 'kodebarcode' => $row_detail['detkodebarcode']]);
                    $row_produk_ed = $ambildataproduk_ed->row_array();

                    $update_jml_produk_tglkadaluarsa = [
                        'jml' => $row_produk_ed['jml'] - $sub_jml
                    ];
                    $this->db->where([
                        'kodebarcode' => $row_detail['detkodebarcode'],
                        'tglkadaluarsa' => $dettglexpired
                    ]);
                    $this->db->update('produk_tglkadaluarsa', $update_jml_produk_tglkadaluarsa);
                endforeach;
                // end

                //Hapus detail pembelian
                $this->db->delete('pembelian_detail', ['detfaktur' => $faktur]);


                // Hapus pembelian
                $this->db->delete('pembelian', ['nofaktur' => $faktur]);

                $msg = ['sukses' => 'Transaksi berhasil dibatalkan'];
            } else {
                $msg = ['error' => 'Maaf tidak ada no.faktur yang dibatalkan !'];
            }
            echo json_encode($msg);
        }
    }

    public function forminputdetail()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);

            $this->load->view('admin/pembelian/forminputdetail', [
                'faktur' => $faktur
            ]);
        }
    }
    public function formeditdetail()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);

            $this->load->view('admin/pembelian/formeditdetail', [
                'faktur' => $faktur
            ]);
        }
    }

    public function cariproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->view('admin/pembelian/modalcariproduk');
        }
    }

    public function ambildataproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/produk/Modelcariproduk', 'produk');

            $list = $this->produk->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $tombolpilih = "<button type=\"button\" class=\"btn btn-info btn-sm waves-effect waves-light\" onclick=\"pilih('" . $field->kodebarcode . "')\" title=\"Pilih Item\">
                    <i class=\"fa fa-hand-point-up\"></i>
                </button>";
                $row[] = $no;
                $row[] = $field->kodebarcode;
                $row[] = $field->namaproduk;
                $row[] = $field->satnama;
                $row[] = number_format($field->harga_beli_eceran, 2, ".", ",");
                $row[] = number_format($field->harga_jual_eceran, 2, ".", ",");
                $row[] = number_format($field->stok_tersedia, 2, ".", ",");
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

    public function carisatuan()
    {
        if ($this->input->is_ajax_request() == true) {
            $kode = $this->input->post('kode', true);
            $query = $this->db->query("SELECT * FROM produk_harga JOIN satuan ON satuan.`satid` = produk_harga.`idsat` WHERE kodebarcode='$kode'");

            $this->load->view('admin/pembelian/modalcarisatuan', [
                'data' => $query
            ]);
        }
    }

    public function detailproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $kode = $this->input->post('kode', true);

            // Detail data produk
            $detaildataproduk = $this->db->query("SELECT id,kodebarcode,namaproduk,produk.`satid`,satnama,stok_tersedia,harga_jual_eceran,harga_beli_eceran,jml_eceran FROM
            produk JOIN satuan ON produk.`satid`=satuan.`satid` WHERE kodebarcode='$kode'");

            if ($detaildataproduk->num_rows() > 0) {
                $row = $detaildataproduk->row_array();

                $msg = [
                    'sukses' => [
                        'namaproduk' => $row['namaproduk'],
                        'idsatuan' => $row['satid'],
                        'namasatuan' => $row['satnama'],
                        'stoktersedia' => number_format($row['stok_tersedia'], 0),
                        'jmleceran' => $row['jml_eceran'],
                        'hargabeli' => number_format($row['harga_beli_eceran'], 2, ",", "."),
                        'hargajual' => number_format($row['harga_jual_eceran'], 2, ",", ".")
                    ]
                ];
                echo json_encode($msg);
            } else {
                $msg = ['error' => 'Produk tidak ditemukan'];
                echo json_encode($msg);
            }
        }
    }

    public function tampilsatuanhargaproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            $datasatuanproduk = $this->db->query("SELECT * FROM produk_harga JOIN satuan ON satuan.`satid` = produk_harga.`idsat` WHERE id='$id'");

            if ($datasatuanproduk->num_rows() > 0) {
                $row = $datasatuanproduk->row_array();
                $msg = [
                    'sukses' => [
                        'idprodukharga' => $id,
                        'idsatuan' => $row['satid'],
                        'namasatuan' => $row['satnama'],
                        'jmleceran' => $row['jml_default'],
                        'hargabeli' => number_format($row['hargamodal'], 2, ",", "."),
                        'hargajual' => number_format($row['hargajual'], 2, ",", ".")
                    ]
                ];
                echo json_encode($msg);
            }
        }
    }

    public function updatehargabeliproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $hargabeli = $this->input->post('hargabeli', true);
            $kode = $this->input->post('kode', true);
            $idsatuan = $this->input->post('idsatuan', true);
            $idprodukharga = $this->input->post('idprodukharga', true);

            // cek kode produk
            $cekkode = $this->db->get_where('produk', ['kodebarcode' => $kode, 'satid' => $idsatuan]);

            // cek kode di produk harga
            $cekkode_produkharga = $this->db->get_where('produk_harga', ['id' => $idprodukharga, 'kodebarcode' => $kode]);

            if ($cekkode->num_rows() > 0) {
                $updateharga = [
                    'harga_beli_eceran' => $hargabeli
                ];
                $this->db->where('kodebarcode', $kode);
                $this->db->update('produk', $updateharga);

                $cekkodelagi = $this->db->get_where('produk', ['kodebarcode' => $kode]);
                $row = $cekkodelagi->row_array();
                $msg = [
                    'sukses' => 'Harga beli berhasil diupdate, silahkan lanjutkan !',
                    'hargabeli' => number_format($row['harga_beli_eceran'], 2, ",", ".")
                ];
            } elseif ($cekkode_produkharga->num_rows() > 0) {
                $updatehargax = [
                    'hargamodal' => $hargabeli
                ];
                $this->db->where('id', $idprodukharga);
                $this->db->update('produk_harga', $updatehargax);

                $cekkode_produkhargalagi = $this->db->get_where('produk_harga', ['id' => $idprodukharga]);
                $xx = $cekkode_produkhargalagi->row_array();

                $msg = [
                    'sukses' => 'Harga beli berhasil diupdate, silahkan lanjutkan !',
                    'hargabeli' => number_format($xx['hargamodal'], 2, ",", ".")
                ];
            } else {
                $msg = [
                    'error' => 'Terjadi kesalahan kode barcode',
                ];
            }

            echo json_encode($msg);
        }
    }

    public function updatehargajualproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $hargajual = $this->input->post('hargajual', true);
            $kode = $this->input->post('kode', true);
            $idsatuan = $this->input->post('idsatuan', true);
            $idprodukharga = $this->input->post('idprodukharga', true);

            // cek kode produk
            $cekkode = $this->db->get_where('produk', ['kodebarcode' => $kode, 'satid' => $idsatuan]);

            // cek kode di produk harga
            $cekkode_produkharga = $this->db->get_where('produk_harga', ['id' => $idprodukharga, 'kodebarcode' => $kode]);

            if ($cekkode->num_rows() > 0) {
                $updateharga = [
                    'harga_jual_eceran' => $hargajual
                ];
                $this->db->where('kodebarcode', $kode);
                $this->db->update('produk', $updateharga);

                $cekkodelagi = $this->db->get_where('produk', ['kodebarcode' => $kode]);
                $row = $cekkodelagi->row_array();
                $msg = [
                    'sukses' => 'Harga jual berhasil diupdate, silahkan lanjutkan !',
                    'hargajual' => number_format($row['harga_jual_eceran'], 2, ",", ".")
                ];
            } elseif ($cekkode_produkharga->num_rows() > 0) {
                $updatehargax = [
                    'hargajual' => $hargajual
                ];
                $this->db->where('id', $idprodukharga);
                $this->db->update('produk_harga', $updatehargax);

                $cekkode_produkhargalagi = $this->db->get_where('produk_harga', ['id' => $idprodukharga]);
                $xx = $cekkode_produkhargalagi->row_array();

                $msg = [
                    'sukses' => 'Harga beli berhasil diupdate, silahkan lanjutkan !',
                    'hargajual' => number_format($xx['hargajual'], 2, ",", ".")
                ];
            } else {
                $msg = [
                    'error' => 'Terjadi kesalahan kode barcode',
                ];
            }
            echo json_encode($msg);
        }
    }

    public function simpanitem()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $kode = $this->input->post('kode', true);
            $idsatuan = $this->input->post('idsatuan', true);
            $jmlsatuan = $this->input->post('jmleceran', true);
            $hargabeli = $this->input->post('hargabeli', true);
            $jml = $this->input->post('jmlbeli', true);
            $ed = $this->input->post('ed', true);


            $this->form_validation->set_rules('faktur', 'Faktur', 'trim|required', [
                'required' => 'Sepertinya %s masih kosong'
            ]);
            $this->form_validation->set_rules('kode', 'Kode Barcode Produk', 'trim|required', [
                'required' => '%s masih kosong'
            ]);
            $this->form_validation->set_rules('idsatuan', 'Satuan', 'trim|required', [
                'required' => '%s masih kosong'
            ]);
            $this->form_validation->set_rules('hargabeli', 'Harga Beli', 'trim|required', [
                'required' => '%s masih kosong'
            ]);
            $this->form_validation->set_rules('jmlbeli', 'Jumlah Beli Produk', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $hargabeli_ganti = str_replace(".", "", $hargabeli);
                $hargabeli_gantix = str_replace(",", ".", $hargabeli_ganti);

                $subtotal = $hargabeli_gantix * $jml;
                // Simpan ke tabel detail
                $datasimpan = [
                    'detfaktur' => $faktur,
                    'detkodebarcode' => $kode,
                    'detsatid' => $idsatuan,
                    'detqtysat' => $jmlsatuan,
                    'dethrgbeli' => $hargabeli_gantix,
                    'detjml' => $jml,
                    'dettglexpired' => $ed,
                    'detsubtotal' => $subtotal
                ];
                $this->db->insert('pembelian_detail', $datasimpan);

                // Simpan ke tabel expired produk
                $cek_tabel_expired_produk = $this->db->get_where('produk_tglkadaluarsa', ['tglkadaluarsa' => $ed, 'kodebarcode' => $kode]);
                if ($cek_tabel_expired_produk->num_rows() > 0) {
                    $row_ed_produk = $cek_tabel_expired_produk->row_array();
                    //update datanya
                    $dataupdate_ed_produk = [
                        'jml' => $row_ed_produk['jml'] + ($jml * $jmlsatuan)
                    ];
                    $this->db->where('tglkadaluarsa', $ed);
                    $this->db->update('produk_tglkadaluarsa', $dataupdate_ed_produk);
                } else {
                    $datasimpan_ed_produk = [
                        'kodebarcode' => $kode,
                        'tglkadaluarsa' => $ed,
                        'jml' => $jml * $jmlsatuan
                    ];
                    $this->db->insert('produk_tglkadaluarsa', $datasimpan_ed_produk);
                }
                // end

                // Update stok tersedia pada tabel produk
                // Ambil data produk
                $ambildataproduk = $this->db->get_where('produk', ['kodebarcode' => $kode]);
                $d_produk = $ambildataproduk->row_array();
                $stok_tersedia = $d_produk['stok_tersedia'];

                $update_stokproduk = [
                    'stok_tersedia' => $stok_tersedia + ($jml * $jmlsatuan)
                ];
                $this->db->where('kodebarcode', $kode);
                $this->db->update('produk', $update_stokproduk);
                // end

                //Update tabel pembelian
                $ambiltotal_detailpembelian = $this->db->query("SELECT SUM(detsubtotal) AS total FROM pembelian_detail WHERE detfaktur = '$faktur'");
                $row_total = $ambiltotal_detailpembelian->row_array();

                $totalkotor = $row_total['total'];

                $q_pembelian = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);
                $row_pembelian = $q_pembelian->row_array();
                $pph = $row_pembelian['pph'];
                $diskonpersen = $row_pembelian['diskonpersen'];
                $diskonuang = $row_pembelian['diskonuang'];

                $hitungpph = $totalkotor + ($totalkotor * $pph / 100);
                // if ($diskonpersen != 0 || $diskonpersen != NULL) {
                //     $totalbersih = $hitungpph - ($totalkotor * $diskonpersen / 100);
                // } elseif ($diskonuang != 0 || $diskonuang != NULL) {
                //     $totalbersih = $hitungpph - ($hitungpph - $diskonuang);
                // } else {
                //     $totalbersih = $hitungpph;
                // }
                $totalbersih = $hitungpph - ($hitungpph * $diskonpersen / 100) - $diskonuang;

                $data_update_pembelian = [
                    'totalkotor' => $totalkotor,
                    'totalbersih' => $totalbersih
                ];
                $this->db->where('nofaktur', $faktur);
                $this->db->update('pembelian', $data_update_pembelian);
                // end                

                $msg = [
                    'sukses' => 'Silahkan tambahkan item lagi !'
                ];
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>' . validation_errors() . '
                                </div>'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function datadetail()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);

            $query = $this->db->query("SELECT detid,detfaktur,detkodebarcode,detsatid,satnama,namaproduk,detjml,dethrgbeli,dettglexpired,detsubtotal FROM pembelian_detail JOIN pembelian ON pembelian_detail.`detfaktur`=pembelian.`nofaktur` JOIN satuan ON satuan.`satid`=detsatid JOIN produk ON kodebarcode=detkodebarcode WHERE detfaktur='$faktur' ORDER BY detid DESC");

            $data = [
                'faktur' => $faktur,
                'data' => $query
            ];
            $this->load->view('admin/pembelian/viewdetaildata', $data);
        }
    }

    public function editdatadetail()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);

            $ambildatapembelian = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);
            $row = $ambildatapembelian->row_array();

            $query = $this->db->query("SELECT detid,detfaktur,detkodebarcode,detsatid,satnama,namaproduk,detjml,dethrgbeli,dettglexpired,detsubtotal FROM pembelian_detail JOIN pembelian ON pembelian_detail.`detfaktur`=pembelian.`nofaktur` JOIN satuan ON satuan.`satid`=detsatid JOIN produk ON kodebarcode=detkodebarcode WHERE detfaktur='$faktur' ORDER BY detid DESC");

            if ($row['tgljatuhtempo'] != '0000-00-00') {
                $tgljatuhtempo = $row['tgljatuhtempo'];
            } else {
                $tgljatuhtempo = '';
            }
            $data = [
                'faktur' => $faktur,
                'data' => $query,
                'pph' => number_format($row['pph'], 2, ".", ","),
                'diskonpersen' => number_format($row['diskonpersen'], 2, ".", ","),
                'diskonuang' => number_format($row['diskonuang'], 2, ".", ","),
                'totalbersih' => $row['totalbersih'],
                'jenisbayar' => $row['jenisbayar'],
                'tgljatuhtempo' => $tgljatuhtempo
            ];
            $this->load->view('admin/pembelian/viewdetaildata_edit', $data);
        }
    }

    public function hapusitem()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            //ambil data detail
            $query_detailpembelian = $this->db->get_where('pembelian_detail', ['detid' => $id]);
            $row_detail = $query_detailpembelian->row_array();

            $detqtysat = $row_detail['detqtysat'];
            $detjml = $row_detail['detjml'];
            $sub_jml = $detqtysat * $detjml;
            $dettglexpired = $row_detail['dettglexpired'];
            $detnofaktur = $row_detail['detfaktur'];

            // Ambil data produk
            $ambildataproduk = $this->db->get_where('produk', ['kodebarcode' => $row_detail['detkodebarcode']]);
            $d_produk = $ambildataproduk->row_array();
            $stok_tersedia = $d_produk['stok_tersedia'];
            // Update stok produk
            $update_stokproduk = [
                'stok_tersedia' => $stok_tersedia - $sub_jml
            ];
            $this->db->where('kodebarcode', $row_detail['detkodebarcode']);
            $this->db->update('produk', $update_stokproduk);

            // update stok pada tabel produk_tglkadaluarsa
            $ambildataproduk_ed = $this->db->get_where('produk_tglkadaluarsa', ['tglkadaluarsa' => $dettglexpired, 'kodebarcode' => $row_detail['detkodebarcode']]);
            $row_produk_ed = $ambildataproduk_ed->row_array();

            $update_jml_produk_tglkadaluarsa = [
                'jml' => $row_produk_ed['jml'] - $sub_jml
            ];
            $this->db->where([
                'kodebarcode' => $row_detail['detkodebarcode'],
                'tglkadaluarsa' => $dettglexpired
            ]);
            $this->db->update('produk_tglkadaluarsa', $update_jml_produk_tglkadaluarsa);

            //Hapus detail
            $this->db->delete('pembelian_detail', ['detid' => $id]);


            //Update tabel pembelian
            $ambiltotal_detailpembelian = $this->db->query("SELECT SUM(detsubtotal) AS total FROM pembelian_detail WHERE detfaktur = '$detnofaktur'");
            $row_total = $ambiltotal_detailpembelian->row_array();

            $totalkotor = $row_total['total'];

            $q_pembelian = $this->db->get_where('pembelian', ['nofaktur' => $detnofaktur]);
            $row_pembelian = $q_pembelian->row_array();
            $pph = $row_pembelian['pph'];
            $diskonpersen = $row_pembelian['diskonpersen'];
            $diskonuang = $row_pembelian['diskonuang'];

            $hitungpph = $totalkotor + ($totalkotor * $pph / 100);
            // if ($diskonpersen != 0 || $diskonpersen != NULL) {
            //     $totalbersih = $hitungpph - ($totalkotor * $diskonpersen / 100);
            // } elseif ($diskonuang != 0 || $diskonuang != NULL) {
            //     $totalbersih = $hitungpph - ($hitungpph - $diskonuang);
            // } else {
            //     $totalbersih = $hitungpph;
            // }
            $totalbersih = $hitungpph - ($hitungpph * $diskonpersen / 100) - $diskonuang;

            $data_update_pembelian = [
                'totalkotor' => $totalkotor,
                'totalbersih' => $totalbersih
            ];
            $this->db->where('nofaktur', $detnofaktur);
            $this->db->update('pembelian', $data_update_pembelian);

            $msg = [
                'sukses' => 'Berhasil di hapus'
            ];
            echo json_encode($msg);
        }
    }

    public function selesaitransaksi()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $pph = $this->input->post('pph', true);
            $totalkotor = $this->input->post('totalseluruh', true);
            $totalbersih = $this->input->post('totalpembayaranx', true);
            $diskonpersen = $this->input->post('dispersen', true);
            $diskonrp  = $this->input->post('disrp', true);
            $jenispembayaran = $this->input->post('jenispembayaran', true);
            $jatuhtempo = $this->input->post('jatuhtempo', true);

            $this->form_validation->set_rules('faktur', 'Faktur Pembelian', 'trim|required', [
                'required' => 'Sepertinya %s masih kosong'
            ]);

            $this->form_validation->set_rules('jenispembayaran', 'Jenis Pembayaran', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {

                if ($jatuhtempo == "1") {
                    $simpantgljatuhtempo = date('Y-m-d', strtotime("+1 week", strtotime(date("Y-m-d"))));
                } elseif ($jatuhtempo == "2") {
                    $simpantgljatuhtempo = date('Y-m-d', strtotime("+2 week", strtotime(date("Y-m-d"))));
                } elseif ($jatuhtempo == "3") {
                    $simpantgljatuhtempo = date('Y-m-d', strtotime("+3 week", strtotime(date("Y-m-d"))));
                } elseif ($jatuhtempo == "4") {
                    $simpantgljatuhtempo = date('Y-m-d', strtotime("+4 week", strtotime(date("Y-m-d"))));
                } elseif ($jatuhtempo == "5") {
                    $simpantgljatuhtempo = date('Y-m-d', strtotime($this->input->post('tgljatuhtempo', true)));
                } else {
                    $simpantgljatuhtempo = "";
                }

                if ($jenispembayaran == 'T') {
                    $statusbayar = 1;
                } else {
                    $statusbayar = 0;
                }

                // Update Pembelian
                $datasimpantransaksi = [
                    'tgljatuhtempo' => $simpantgljatuhtempo,
                    'jenisbayar' => $jenispembayaran,
                    'pph' => $pph,
                    'diskonpersen' => $diskonpersen,
                    'diskonuang' => str_replace(",", "", $diskonrp),
                    'totalkotor' => $totalkotor,
                    'totalbersih' => $totalbersih,
                    'statustransaksi' => 1,
                    'statusbayar' => $statusbayar
                ];

                $this->db->where('nofaktur', $faktur);
                $this->db->update('pembelian', $datasimpantransaksi);

                $msg = [
                    'sukses' => 'Transaksi berhasil disimpan'
                ];
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <strong>Error !</strong>' . validation_errors() . '
                                </div>'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function data()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-list-alt"></i> Data Transaksi Pembelian',
            'isi' => $this->load->view('admin/pembelian/data', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function ambildatapembelian()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/pembelian/Modeldatapembelian', 'pembelian');
            $list = $this->pembelian->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $hapus = "<button type=\"button\" class=\"btn btn-sm btn-outline-danger\" onclick=\"hapusfaktur('" . $field->nofaktur . "')\" title=\"Hapus Faktur\">
                    <i class=\"fa fa-trash-alt\"></i>
                    </button>";

                $edit = "<button type=\"button\" class=\"btn btn-sm btn-outline-info\" onclick=\"editfaktur('" . $field->nofaktur . "')\" title=\"Edit Faktur Pembelian\">
                    <i class=\"fa fa-tags\"></i>
                    </button>";

                $no++;
                $row = array();
                $row[] = $no;
                $row[] = "<a href=\"#\" onclick=\"detailfaktur('" . $field->nofaktur . "')\">$field->nofaktur</a>";
                $row[] = date('d-m-Y', strtotime($field->tglbeli));

                if ($field->jenisbayar == 'K') {
                    $row[] = "<span class=\"badge badge-warning\">Kredit</span>";
                } else {
                    $row[] = "<span class=\"badge badge-primary\">Tunai</span>";
                }

                if ($field->tgljatuhtempo == '0000-00-00') {
                    $row[] = '-';
                } else {
                    $row[] = date('d-m-Y', strtotime($field->tgljatuhtempo));
                }
                $row[] = number_format($field->totalkotor, 2, ",", ".");
                $row[] = number_format($field->totalbersih, 2, ",", ".");

                if ($field->statustransaksi == '1') {
                    $row[] = "<span class=\"badge badge-primary\">Selesai</span>";
                } else {
                    $row[] = "<span class=\"badge badge-danger\">Belum</span>";
                }

                $row[] = $hapus . '&nbsp;' . $edit;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->pembelian->count_all(),
                "recordsFiltered" => $this->pembelian->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function tampildetailfaktur()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);

            $ambildatapembelian = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);
            $row = $ambildatapembelian->row_array();

            $ambildatapemasok =  $this->db->get_where('pemasok', ['id' => $row['idpemasok']]);
            $row_pemasok = $ambildatapemasok->row_array();

            if ($row['jenisbayar'] == 'K') {
                $jenisbayar = "<span class=\"badge badge-warning\">Kredit</span>";
            } else {
                $jenisbayar = "<span class=\"badge badge-primary\">Tunai</span>";
            }

            if ($row['tgljatuhtempo'] == '0000-00-00') {
                $tgljatuhtempo = '-';
            } else {
                $tgljatuhtempo = date('d-m-Y', strtotime($row['tgljatuhtempo']));
            }

            if ($row['statustransaksi'] == '1') {
                $statustransaksi = "<span class=\"badge badge-primary\">Selesai</span>";
            } else {
                $statustransaksi = "<span class=\"badge badge-danger\">Belum</span>";
            }

            $data = [
                'nofaktur' => $faktur,
                'tglbeli' => date('d-m-Y', strtotime($row['tglbeli'])),
                'pemasok' => $row_pemasok['nama'],
                'jenisbayar' => $jenisbayar,
                'tgljatuhtempo' => $tgljatuhtempo,
                'totalkotor' => number_format($row['totalkotor'], 2, ".", ","),
                'pph' => number_format($row['pph'], 2, ".", ","),
                'diskonpersen' => number_format($row['diskonpersen'], 2, ".", ","),
                'diskonuang' => number_format($row['diskonuang'], 2, ".", ","),
                'totalbersih' => number_format($row['totalbersih'], 2, ".", ","),
                'statustransaksi' => $statustransaksi,
                'jmlpembayarankredit' => $row['jmlpembayarankredit'],
                'totalbersihx' => $row['totalbersih'],
                'statusbayar' => $row['statusbayar'],
                'tglpembayarankredit' => $row['tglpembayarankredit'],
                'jenispembayaran' => $row['jenisbayar']
            ];

            $this->load->view('admin/pembelian/modaldetailfakturpembelian', $data);
        }
    }

    public function edit()
    {
        $faktur = $this->uri->segment(4);

        $ambildatapembelian = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);

        if ($ambildatapembelian->num_rows() > 0) {

            $row = $ambildatapembelian->row_array();

            $ambildatapemasok =  $this->db->get_where('pemasok', ['id' => $row['idpemasok']]);
            $row_pemasok = $ambildatapemasok->row_array();

            $data = [
                'nofaktur' => $faktur,
                'tglbeli' => date('d-m-Y', strtotime($row['tglbeli'])),
                'namapemasok' => $row_pemasok['nama'],
                'idpemasok' => $row['idpemasok']
            ];

            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-truck-moving"></i> Edit Transaksi Pembelian',
                'isi' => $this->load->view('admin/pembelian/edit', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function edititem()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            $ambildatadetailpembelian = $this->db->get_where('pembelian_detail', ['detid' => $id]);
            if ($ambildatadetailpembelian->num_rows() > 0) {
                $row_detailpembelian = $ambildatadetailpembelian->row_array();
                $kodebarcode = $row_detailpembelian['detkodebarcode'];
                $idsatuan = $row_detailpembelian['detsatid'];

                $ambildataproduk = $this->db->query("SELECT kodebarcode,namaproduk,produk.`satid` AS idsatuan,satnama,harga_jual_eceran,harga_beli_eceran,jml_eceran FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` WHERE kodebarcode='$kodebarcode' AND produk.`satid`='$idsatuan'");

                if ($ambildataproduk->num_rows() > 0) {
                    $xx = $ambildataproduk->row_array();

                    $namaproduk = $xx['namaproduk'];
                    $satuanid = $xx['idsatuan'];
                    $qtydefault = $xx['jml_eceran'];
                    $hargajual = $xx['harga_jual_eceran'];
                    $hargabeli = $xx['harga_beli_eceran'];
                    $satuannama = $xx['satnama'];
                } else {
                    $ambildata_produk_harga = $this->db->query("SELECT produk_harga.kodebarcode,namaproduk,idsat,satnama,jml_default,hargamodal,hargajual FROM produk_harga JOIN produk ON produk.`kodebarcode`=produk_harga.`kodebarcode` JOIN satuan ON satuan.`satid`=produk_harga.`idsat` WHERE produk_harga.`kodebarcode` = '$kodebarcode' AND produk_harga.`idsat`='$idsatuan'");

                    $yy = $ambildata_produk_harga->row_array();

                    $namaproduk = $yy['namaproduk'];
                    $satuanid = $yy['idsat'];
                    $qtydefault = $yy['jml_default'];
                    $hargajual = $yy['hargajual'];
                    $hargabeli = $yy['hargamodal'];
                    $satuannama = $yy['satnama'];
                }

                $data = [
                    'id' => $id,
                    'kodebarcode' => $kodebarcode,
                    'namaproduk' => $namaproduk,
                    'jmlbeli' => $row_detailpembelian['detjml'],
                    'tgled' => $row_detailpembelian['dettglexpired'],
                    'hargabeli' => $row_detailpembelian['dethrgbeli'],
                    'jmldefault' => $qtydefault,
                    'hargajual' => $hargajual,
                    'idsatuan' => $satuanid,
                    'namasatuan' => $satuannama
                ];

                $this->load->view('admin/pembelian/modaledititem', $data);
            }
        }
    }

    public function updateitem()
    {
        if ($this->input->is_ajax_request() == TRUE) {

            $id = $this->input->post('id', true);
            $hargabeli = str_replace(",", "", $this->input->post('hargabeli', true));
            $hargajual = str_replace(",", "", $this->input->post('hargajual', true));
            $jmlbeli = $this->input->post('jmlbeli', true);
            $tgled = $this->input->post('tgled', true);

            $this->form_validation->set_rules('hargabeli', 'Harga Beli', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('hargajual', 'Harga Jual', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('jmlbeli', 'Jumlah Beli', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                //ambil data dari table detail pembelian
                $q_detailpembelian = $this->db->get_where('pembelian_detail', ['detid' => $id]);
                $a = $q_detailpembelian->row_array();
                $detkodebarcode = $a['detkodebarcode'];
                $detqtysat = $a['detqtysat'];
                $detjml = $a['detjml'];
                $dettglexpired = $a['dettglexpired'];
                $detnofaktur = $a['detfaktur'];

                $update_detailpembelian = [
                    'detjml' => $jmlbeli,
                    'dethrgbeli' => $hargabeli,
                    // 'dettglexpired' => $tgled,
                    'detsubtotal' => $jmlbeli * $hargabeli
                ];
                $this->db->where('detid', $id);
                $this->db->update('pembelian_detail', $update_detailpembelian);

                // update harga beli dan harga jual
                $q_produk  = $this->db->get_where('produk', ['kodebarcode' => $detkodebarcode, 'satid' => $a['detsatid']]);
                if ($q_produk->num_rows() > 0) {
                    $dataupdateproduk_harga = [
                        'harga_jual_eceran' => $hargajual,
                        'harga_beli_eceran' => $hargabeli
                    ];
                    $this->db->where('kodebarcode', $detkodebarcode);
                    $this->db->update('produk', $dataupdateproduk_harga);
                } else {
                    $q_produk_harga = $this->db->get_where('produk_harga', ['kodebarcode' => $detkodebarcode, 'idsat' => $a['detsatid']]);
                    $row_produk_harga = $q_produk_harga->row_array();
                    $idprodukharga = $row_produk_harga['id'];

                    if ($q_produk_harga->num_rows() > 0) {
                        $dataupdateharga_produk = [
                            'hargamodal' => $hargabeli,
                            'hargajual' => $hargajual
                        ];
                        $this->db->where('id', $idprodukharga);
                        $this->db->update('produk_harga', $dataupdateharga_produk);
                    }
                }

                // update stok produk
                $row_produk = $q_produk->row_array();
                $stok_tersedia = $row_produk['stok_tersedia'];

                $kurangi_lama = $stok_tersedia - ($detjml * $detqtysat);

                $data_update_stok_produk = [
                    'stok_tersedia' => $kurangi_lama + ($detqtysat * $jmlbeli)
                ];
                $this->db->where('kodebarcode', $row_produk['kodebarcode']);
                $this->db->update('produk', $data_update_stok_produk);

                // Update stok pada tabel produk_tglkadaluarsa
                $ambildata_produk_tglkadaluarsa = $this->db->get_where('produk_tglkadaluarsa', ['kodebarcode' => $detkodebarcode, 'tglkadaluarsa' => $dettglexpired]);
                if ($ambildata_produk_tglkadaluarsa->num_rows() > 0) {
                    $row_produk_tglkadaluarsa = $ambildata_produk_tglkadaluarsa->row_array();
                    $jml_produk_tglkadaluarsa = $row_produk_tglkadaluarsa['jml'];
                    $id_produk_tglkadaluarsa = $row_produk_tglkadaluarsa['id'];

                    $kurangi_jml = $jml_produk_tglkadaluarsa - ($detjml * $detqtysat);

                    $data_update_produk_tglkadaluarsa = [
                        'jml' => $kurangi_jml + ($detqtysat * $jmlbeli)
                    ];
                    $this->db->where('id', $id_produk_tglkadaluarsa);
                    $this->db->update('produk_tglkadaluarsa', $data_update_produk_tglkadaluarsa);
                }

                //Update tabel pembelian
                $ambiltotal_detailpembelian = $this->db->query("SELECT SUM(detsubtotal) AS total FROM pembelian_detail WHERE detfaktur = '$detnofaktur'");
                $row_total = $ambiltotal_detailpembelian->row_array();

                $totalkotor = $row_total['total'];

                $q_pembelian = $this->db->get_where('pembelian', ['nofaktur' => $detnofaktur]);
                $row_pembelian = $q_pembelian->row_array();
                $pph = $row_pembelian['pph'];
                $diskonpersen = $row_pembelian['diskonpersen'];
                $diskonuang = $row_pembelian['diskonuang'];

                $hitungpph = $totalkotor + ($totalkotor * $pph / 100);
                $totalbersih = $hitungpph - ($hitungpph * $diskonpersen / 100) - $diskonuang;
                // if ($diskonpersen != 0 || $diskonpersen != NULL) {
                //     $totalbersih = $hitungpph - ($totalkotor * $diskonpersen / 100);
                // } elseif ($diskonuang != 0 || $diskonuang != NULL) {
                //     $totalbersih = $hitungpph - ($hitungpph - $diskonuang);
                // } else {
                //     $totalbersih = $hitungpph;
                // }

                $data_update_pembelian = [
                    'totalkotor' => $totalkotor,
                    'totalbersih' => $totalbersih
                ];
                $this->db->where('nofaktur', $detnofaktur);
                $this->db->update('pembelian', $data_update_pembelian);
                // end

                $msg = [
                    'sukses' => 'Item ini berhasil di-update'
                ];
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <strong>Error !</strong>' . validation_errors() . '
                                </div>'
                ];
            }
            echo json_encode($msg);
        }
    }
}