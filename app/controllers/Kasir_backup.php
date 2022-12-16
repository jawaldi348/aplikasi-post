<?php
class Kasir_backup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true) {
            $this->load->library(['form_validation']);
            $this->load->model('Modelkasir', 'kasir');
            return true;
        } else {
            redirect('login/logout');
        }
    }

    function buatnomor()
    {
        $iduser = $this->session->userdata('username');
        $tglhariini = date('Y-m-d');
        $query = $this->db->query("SELECT MAX(jualfaktur) AS nota FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') = '$tglhariini'");
        $hasil = $query->row_array();
        $data  = $hasil['nota'];


        $lastNoUrut = substr($data, 10, 5);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = 'KOP-' . date('dmy', strtotime($tglhariini)) . sprintf('%05s', $nextNoUrut);
        return $nextNoTransaksi;
    }
    public function input()
    {
        // $data = [
        //     'jualfaktur' => $this->buatnomor()
        // ];
        // $view = [
        //     'menu' => $this->load->view('template/menu', '', TRUE),
        //     'judul' => '<i class="fa fa-cash-register"></i> Input Kasir Penjualan',
        //     'isi' => $this->load->view('kasir/index', $data, true)

        // ];
        // $this->parser->parse('template/main', $view);

        $data = [
            'jualfaktur' => $this->buatnomor()
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-cash-register"></i> Input Kasir Penjualan',
            'isi' => $this->load->view('layoutkasir/input', $data, true)

        ];
        $this->parser->parse('layoutkasir/main', $view);
    }

    public function tampildatatemp()
    {
        if ($this->input->is_ajax_request() == true) {
            $jualfaktur = $this->input->post('jualfaktur', true);

            $tampildata = $this->kasir->tampildatatemp($jualfaktur);
            $data = [
                'data' => $tampildata
            ];
            $this->load->view('layoutkasir/datatemp', $data);
        }
    }

    public function detailproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $kode = $this->input->post('kode', true);
            $faktur = $this->input->post('faktur', true);
            $jml = $this->input->post('jml', true);
            $dispersen = $this->input->post('dispersen', true);
            $disuang = $this->input->post('disuang', true);

            $cekproduk = $this->kasir->cekproduk($kode);

            if ($cekproduk->num_rows() > 0) {
                $row_produk = $cekproduk->row_array();

                $stoktersedia = $row_produk['stok_tersedia'];

                if ($jml > $stoktersedia) {
                    $msg = [
                        'error' => 'Maaf Stok tidak cukup'
                    ];
                } else {
                    $query_cek_temp_jual = $this->db->query("SELECT * FROM temp_jual WHERE detjualfaktur='$faktur' AND detjualkodebarcode='$kode' AND detjualsatid='$row_produk[satid]'");

                    if ($query_cek_temp_jual->num_rows() > 0) {
                        $row_temp_jual = $query_cek_temp_jual->row_array();

                        $jml_update = $row_temp_jual['detjualjml'] + $jml;

                        $subtotal_update = $jml_update * $row_temp_jual['detjualharga'];

                        $update_temp_saja =  [
                            'detjualjml' => $jml_update,
                            'detjualsubtotal' => $subtotal_update
                        ];
                        $this->db->where('detjualid', $row_temp_jual['detjualid']);
                        $this->db->update('temp_jual', $update_temp_saja);

                        $msg = ['sukses' => 'Berhasil ditambahkan'];
                    } else {
                        $qty_satuan = $row_produk['jml_eceran'];
                        $hargajual = $row_produk['harga_jual_eceran'];

                        $hitung_subtotal = ($jml * $qty_satuan * $hargajual);
                        $subtotal_bersih = $hitung_subtotal - ($hitung_subtotal * ($dispersen / 100)) - $disuang;

                        $datasimpan_temp = [
                            'detjualfaktur' => $faktur,
                            'detjualkodebarcode' => $kode,
                            'detjualsatid' => $row_produk['satid'],
                            'detjualsatqty' => $qty_satuan,
                            'detjualjml' => $jml,
                            'detjualharga' => $hargajual,
                            'detjualuserinput' => $this->session->userdata('username'),
                            'detjualdispersen' => $dispersen,
                            'detjualdisuang' => $disuang,
                            'detjualsubtotal' => $subtotal_bersih
                        ];
                        $this->db->insert('temp_jual', $datasimpan_temp);

                        $msg = ['sukses' => 'Berhasil ditambahkan'];
                    }
                }
            } else {
                $msg = ['error' => 'Kode produk tidak ditemukan'];
            }

            echo json_encode($msg);
        }
    }

    public function hapusitem()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            //hapus item pada table temp
            $hapusitem = $this->db->delete('temp_jual', ['detjualid' => $id]);

            if ($hapusitem) {
                $msg = [
                    'sukses' => 'Item berhasil terhapus'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function carimember()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->view('kasir/modalcarimember');
        }
    }

    public function ambildatamember()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/member/Modelmember', 'member');
            $list = $this->member->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $row[] = $no;
                $row[] = $field->memberkode;
                $row[] = $field->membernama;
                $row[] = $field->membertelp;
                $row[] = $field->memberalamat;
                $row[] = "<button type=\"button\" class=\"btn btn-sm btn-outline-info\" onclick=\"pilih('" . $field->memberkode . "','" . $field->membernama . "')\"><i class=\"fa fa-hand-point-up\"></i></button>";
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->member->count_all(),
                "recordsFiltered" => $this->member->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        } else {
            exit('Maaf data tidak bisa ditampilkan');
        }
    }

    public function pembayaran()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $kodemember = $this->input->post('kodemember', true);
            $namamember = $this->input->post('namamember', true);
            $total_subtotal = $this->input->post('total_subtotal', true);

            $cek_data_temp = $this->db->get_where('temp_jual', ['detjualfaktur' => $faktur, 'detjualuserinput' => $this->session->userdata('username')]);

            if ($cek_data_temp->num_rows() > 0) {
                $data = [
                    'faktur' => $faktur,
                    'kodemember' => $kodemember,
                    'namamember' => $namamember,
                    'total_subtotal' => $total_subtotal
                ];
                $msg = [
                    'sukses' => $this->load->view('kasir/modalpembayaran', $data, true)
                ];
            } else {
                $msg = ['error' => 'Item belum ada.'];
            }
            echo json_encode($msg);
        }
    }

    function bataltransaksi()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $username = $this->session->userdata('username');

            $cekfaktur_temp_jual = $this->db->get_where('temp_jual', ['detjualfaktur' => $faktur, 'detjualuserinput' => $username]);

            if ($cekfaktur_temp_jual->num_rows() > 0) {
                $this->db->delete('temp_jual', ['detjualfaktur' => $faktur, 'detjualuserinput' => $username]);

                $msg = ['sukses' => 'Transaksi Berhasil dibatalkan, halaman akan direload !'];
            } else {
                $msg = ['error' => 'Tidak ada yang dibatalkan'];
            }
            echo json_encode($msg);
        }
    }

    public function simpantransaksi()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $kodemember = $this->input->post('kodemember', true);
            $namamember = $this->input->post('namamember', true);
            $statusbayar = $this->input->post('jenispembayaran', true);
            $tglpembayaran = $this->input->post('tglpembayaran', true);
            $totalkotor = $this->input->post('total_subtotal', true);
            $diskonpersen = str_replace(",", "", $this->input->post('diskonpersen', true));
            $diskonuang = str_replace(",", "", $this->input->post('diskonuang', true));
            $totalbersih = str_replace(",", "", $this->input->post('totalbersih', true));
            $jumlahuang = str_replace(",", "", $this->input->post('jumlahuang', true));
            $sisa = $this->input->post('sisa', true);

            if ($statusbayar == 'T') {
                $input_tglpembayaran = '0000-00-00';
                $statuslunas = '1';
            } else {
                $input_tglpembayaran = $tglpembayaran;
                $statuslunas = '0';
            }

            $username = $this->session->userdata('username');
            //simpan penjualan
            $simpan_penjualan = [
                'jualfaktur' => $faktur,
                'jualtgl' => date('Y-m-d H:i:s'),
                'jualmemberkode' => $kodemember,
                'jualstatusbayar' => $statusbayar,
                'jualtotalkotor' => $totalkotor,
                'jualdispersen' => $diskonpersen,
                'jualdisuang' => $diskonuang,
                'jualtotalbersih' => $totalbersih,
                'jualstatuslunas' => $statuslunas,
                'jualtgljatuhtempo' => $input_tglpembayaran,
                'jualjmluangbayar' => $jumlahuang,
                'jualjmluangsisa' => $sisa,
                'jualuserinput' => $username
            ];
            $this->db->insert('penjualan', $simpan_penjualan);

            //simpan penjualan detail
            $this->db->query("INSERT INTO penjualan_detail(detjualfaktur,detjualkodebarcode,detjualsatid,detjualsatqty,detjualjml,detjualharga,detjualsubtotal,detjualuserinput,detjualdispersen,detjualdisuang) (SELECT detjualfaktur,detjualkodebarcode,detjualsatid,detjualsatqty,detjualjml,detjualharga,detjualsubtotal,detjualuserinput,detjualdispersen,detjualdisuang FROM temp_jual WHERE detjualfaktur='$faktur' AND detjualuserinput='$username')");

            // Hapus temp jual
            $this->db->delete('temp_jual', ['detjualfaktur' => $faktur, 'detjualuserinput' => $username]);

            $msg = [
                'sukses' => 'Transaksi berhasil disimpan',
                'cetakfaktur' => site_url('kasir/cetakfaktur/') . $faktur
            ];
            echo json_encode($msg);
        }
    }

    public function cetakfaktur()
    {
        $faktur = $this->uri->segment('3');

        $cekfaktur = $this->db->get_where('penjualan', ['jualfaktur' => $faktur]);

        if ($cekfaktur->num_rows() > 0) {
            $row_penjualan = $cekfaktur->row_array();

            $query_toko = $this->db->get_where('nn_namatoko', ['idtoko' => 1]);
            $row_toko = $query_toko->row_array();

            $query_detail = $this->db->query("SELECT detjualkodebarcode AS kodebarcode,produk.`namaproduk` AS namaproduk,detjualjml AS jml, satnama AS namasatuan, detjualharga AS hargajual,detjualsubtotal AS subtotal FROM penjualan_detail JOIN produk ON detjualkodebarcode = kodebarcode JOIN satuan ON detjualsatid=satuan.satid WHERE detjualfaktur='$faktur'")->result();

            $data = [
                'namatoko' => $row_toko['nmtoko'],
                'alamattoko' => $row_toko['alamat'],
                'faktur' => $faktur,
                'totalkotor' => number_format($row_penjualan['jualtotalkotor'], 0, ",", "."),
                'totalbersih' => number_format($row_penjualan['jualtotalbersih'], 0, ",", "."),
                'disuang' => number_format($row_penjualan['jualdisuang'], 0, ",", "."),
                'dispersen' => $row_penjualan['jualdispersen'] . ' %',
                'namauser' => $this->session->userdata('namalengkapuser'),
                'tglfaktur' => date('d-m-Y H:i:s', strtotime($row_penjualan['jualtgl'])),
                'detaildata' => $query_detail,
                'jmluangbayar' => number_format($row_penjualan['jualjmluangbayar'], 0, ",", "."),
                'jmluangsisa' => number_format($row_penjualan['jualjmluangsisa'], 0, ",", "."),
            ];

            $this->load->view('kasir/cetakfaktur', $data);
        } else {
            exit('Data tidak ditemukan...');
        }
    }

    public function cariproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            if ($this->input->is_ajax_request() == true) {
                $this->load->view('kasir/modalcariproduk');
            }
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

                $tombolpilih = "<button type=\"button\" class=\"btn btn-info btn-sm waves-effect waves-light btnpilih\" onclick=\"pilih('" . $field->kodebarcode . "')\" title=\"Pilih Item\">
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

    function gantisatuan()
    {
        if ($this->input->is_ajax_request() == true) {
            $kode = $this->input->post('kode', true);
            $id_tempjual = $this->input->post('id', true);
            $jualfaktur = $this->input->post('jualfaktur', true);
            $jualjml = $this->input->post('jml', true);

            $ambildata = $this->db->query("SELECT produk_harga.`idsat`,satuan.`satnama`,produk_harga.`id` as id FROM produk_harga JOIN satuan ON produk_harga.`idsat`=satuan.`satid` WHERE kodebarcode='$kode'");

            $ambildataproduk = $this->db->get_where('produk', ['kodebarcode' => $kode]);
            $row = $ambildataproduk->row_array();

            if ($ambildata->num_rows() > 0) {
                $data = [
                    'id_tempjual' => $id_tempjual,
                    'satuanprodukharga' => $ambildata,
                    'namaproduk' => $row['namaproduk'],
                    'jualfaktur' => $jualfaktur,
                    'jualjml' => $jualjml
                ];
                $msg = [
                    'sukses' => [
                        'tampilmodal' => $this->load->view('kasir/modalgantisatuan', $data, true)
                    ]
                ];
            } else {
                $msg = ['error' => 'Tidak ditemukan satuan yang lain nya pada produk ini.'];
            }
            echo json_encode($msg);
        }
    }

    function updategantisatuan()
    {
        if ($this->input->is_ajax_request() == true) {
            $id_tempjual = $this->input->post('id_tempjual', true);
            $satuan = $this->input->post('satuan', true);
            $jualjml = $this->input->post('jualjml', true);

            $this->form_validation->set_rules('satuan', 'Satuan', 'trim|required', [
                'required' => '%s harus dipilih'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $ambildata_produkharga = $this->db->get_where('produk_harga', ['id' => $satuan]);
                $row_produkharga = $ambildata_produkharga->row_array();

                $idsat = $row_produkharga['idsat'];
                $hargajual = $row_produkharga['hargajual'];
                $jml_default = $row_produkharga['jml_default'];

                $subtotal = ($jualjml * $hargajual);

                $data_update_tempjual = [
                    'detjualsatid' => $idsat,
                    'detjualsatqty' => $jml_default,
                    'detjualharga' => $hargajual,
                    'detjualsubtotal' => $subtotal
                ];
                $this->db->where('detjualid', $id_tempjual);
                $this->db->update('temp_jual', $data_update_tempjual);

                $msg = [
                    'sukses' => 'Berhasil diganti'
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
}