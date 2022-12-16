<?php
class Kasir extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true) {
            $this->load->library(['form_validation']);
            $this->load->model('Modelkasir', 'kasir');
            $this->load->model('admin/Modeltransaksineraca', 'neraca');
            return true;
        } else {
            redirect('login/logout');
        }
    }

    function buatnomor()
    {
        $username = $this->session->userdata('username');
        $userid = $this->session->userdata('userid');
        $tglhariini = date('Y-m-d');
        $query = $this->db->query("SELECT MAX(jualfaktur) AS nota FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') = '$tglhariini' AND jualuserinput ='$username'");
        $hasil = $query->row_array();
        $data  = $hasil['nota'];


        // $lastNoUrut = substr($data, 10, 5);
        $lastNoUrut = substr($data, -5);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = $userid . date('dmy', strtotime($tglhariini)) . sprintf('%05s', $nextNoUrut);
        return $nextNoTransaksi;
    }
    public function input()
    {
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
            $diskonmember = $this->input->post('diskonmember', true);

            $tampildata = $this->kasir->tampildatatemp($jualfaktur);
            $data = [
                'data' => $tampildata,
                'diskonmember' => $diskonmember
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
            $namaproduk = $this->input->post('namaproduk', true);

            $cekproduk = $this->kasir->cekproduk($kode, $namaproduk);

            if ($cekproduk->num_rows() > 0) {
                if ($cekproduk->num_rows() === 1) {
                    $row_produk = $cekproduk->row_array();
                    $kodebarcode = $row_produk['kodebarcode'];
                    $stoktersedia = $row_produk['stok_tersedia'];
                    $hargabeli = $row_produk['harga_beli_eceran'];
                    $produkpaket = $row_produk['produkpaket'];
                    $idproduk = $row_produk['id'];

                    // Ambil data pengaturan 
                    $datapengaturan = $this->db->get_where('pengaturan', ['id' => 1])->row_array();
                    // End

                    if ($datapengaturan['stokminus'] == '1' && ($produkpaket == '0' || $produkpaket == '1')) {
                        if ($jml > $stoktersedia) {
                            $msg = [
                                'error' => 'Maaf Stok tidak cukup'
                            ];
                        } else {
                            $query_cek_temp_jual = $this->db->query("SELECT * FROM temp_jual WHERE detjualfaktur='$faktur' AND detjualkodebarcode='$kodebarcode' AND detjualsatid='$row_produk[satid]'");

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

                                // jika Produk Paket

                                if ($produkpaket == 1) {
                                    $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $idproduk]);

                                    foreach ($query_produkpaket->result_array() as $paket) :
                                        $paketkodebarcode = $paket['paketkodebarcode'];
                                        $paketjml = $paket['paketjml'];
                                        // Kurangi stok tersedia
                                        $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                                        $row_dataproduk = $query_dataproduk->row_array();
                                        $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                                        $this->db->where('kodebarcode', $paketkodebarcode);
                                        $this->db->update('produk', [
                                            'stok_tersedia' => $stok_dataproduk - $paketjml
                                        ]);
                                    // end kurang stok tersedia
                                    endforeach;

                                    $msg = ['sukses' => 'berhasil'];
                                } else {
                                    $msg = ['sukses' => 'berhasil'];
                                }
                                // End
                            } else {

                                $qty_satuan = $row_produk['jml_eceran'];
                                $hargajual = $row_produk['harga_jual_eceran'];

                                $hitung_subtotal = ($jml * $qty_satuan * $hargajual);
                                $subtotal_bersih = $hitung_subtotal - ($hitung_subtotal * ($dispersen / 100)) - $disuang;
                                $hitung_diskon = $hitung_subtotal * ($dispersen / 100) + $disuang;

                                $datasimpan_temp = [
                                    'detjualfaktur' => $faktur,
                                    'detjualtgl' => date('Y-m-d'),
                                    'detjualkodebarcode' => $kodebarcode,
                                    'detjualsatid' => $row_produk['satid'],
                                    'detjualsatqty' => $qty_satuan,
                                    'detjualjml' => $jml,
                                    'detjualharga' => $hargajual,
                                    'detjualhargabeli' => $hargabeli,
                                    'detjualuserinput' => $this->session->userdata('username'),
                                    'detjualdispersen' => $dispersen,
                                    'detjualdisuang' => $disuang,
                                    'detjualsubtotalkotor' => $hitung_subtotal,
                                    'detjualsubtotal' => $subtotal_bersih,
                                    'detjualdiskon' => $hitung_diskon
                                ];
                                $this->db->insert('temp_jual', $datasimpan_temp);

                                // jika Produk Paket

                                if ($produkpaket == 1) {
                                    $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $idproduk]);

                                    foreach ($query_produkpaket->result_array() as $paket) :
                                        $paketkodebarcode = $paket['paketkodebarcode'];
                                        $paketjml = $paket['paketjml'];
                                        // Kurangi stok tersedia
                                        $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                                        $row_dataproduk = $query_dataproduk->row_array();
                                        $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                                        $this->db->where('kodebarcode', $paketkodebarcode);
                                        $this->db->update('produk', [
                                            'stok_tersedia' => $stok_dataproduk - ($paketjml * $jml)
                                        ]);
                                    // end kurang stok tersedia
                                    endforeach;

                                    $msg = ['sukses' => 'berhasil'];
                                } else {
                                    $msg = ['sukses' => 'berhasil'];
                                }
                                // End

                            }
                        }
                    } else {
                        $query_cek_temp_jual = $this->db->query("SELECT * FROM temp_jual WHERE detjualfaktur='$faktur' AND detjualkodebarcode='$kodebarcode' AND detjualsatid='$row_produk[satid]'");

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

                            // jika Produk Paket

                            if ($produkpaket == 1) {
                                $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $idproduk]);

                                foreach ($query_produkpaket->result_array() as $paket) :
                                    $paketkodebarcode = $paket['paketkodebarcode'];
                                    $paketjml = $paket['paketjml'];
                                    // Kurangi stok tersedia
                                    $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                                    $row_dataproduk = $query_dataproduk->row_array();
                                    $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                                    $this->db->where('kodebarcode', $paketkodebarcode);
                                    $this->db->update('produk', [
                                        'stok_tersedia' => $stok_dataproduk - $paketjml
                                    ]);
                                // end kurang stok tersedia
                                endforeach;

                                $msg = ['sukses' => 'berhasil'];
                            } else {
                                $msg = ['sukses' => 'berhasil'];
                            }
                            // End
                        } else {

                            $qty_satuan = $row_produk['jml_eceran'];
                            $hargajual = $row_produk['harga_jual_eceran'];

                            $hitung_subtotal = ($jml * intval($qty_satuan) * intval($hargajual));
                            $subtotal_bersih = $hitung_subtotal - ($hitung_subtotal * ($dispersen / 100)) - $disuang;
                            $hitung_diskon = $hitung_subtotal * ($dispersen / 100) + $disuang;

                            $datasimpan_temp = [
                                'detjualfaktur' => $faktur,
                                'detjualtgl' => date('Y-m-d'),
                                'detjualkodebarcode' => $kodebarcode,
                                'detjualsatid' => $row_produk['satid'],
                                'detjualsatqty' => $qty_satuan,
                                'detjualjml' => $jml,
                                'detjualharga' => $hargajual,
                                'detjualhargabeli' => $hargabeli,
                                'detjualuserinput' => $this->session->userdata('username'),
                                'detjualdispersen' => $dispersen,
                                'detjualdisuang' => $disuang,
                                'detjualsubtotalkotor' => $hitung_subtotal,
                                'detjualsubtotal' => $subtotal_bersih,
                                'detjualdiskon' => $hitung_diskon
                            ];
                            $this->db->insert('temp_jual', $datasimpan_temp);

                            // jika Produk Paket

                            if ($produkpaket == 1) {
                                $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $idproduk]);

                                foreach ($query_produkpaket->result_array() as $paket) :
                                    $paketkodebarcode = $paket['paketkodebarcode'];
                                    $paketjml = $paket['paketjml'];
                                    // Kurangi stok tersedia
                                    $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                                    $row_dataproduk = $query_dataproduk->row_array();
                                    $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                                    $this->db->where('kodebarcode', $paketkodebarcode);
                                    $this->db->update('produk', [
                                        'stok_tersedia' => $stok_dataproduk - ($paketjml * $jml)
                                    ]);
                                // end kurang stok tersedia
                                endforeach;

                                $msg = ['sukses' => 'berhasil'];
                            } else {
                                $msg = ['sukses' => 'berhasil'];
                            }
                            // End

                        }
                    }
                } else {
                    $data = [
                        'tampildata' => $cekproduk
                    ];
                    $msg = ['banyakdata' => $this->load->view('layoutkasir/modaldatacariproduk', $data, true)];
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
            $kode = $this->input->post('kode', true);
            $jml = $this->input->post('jml', true);

            $ambil_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $kode])->row_array();

            if ($ambil_dataproduk['produkpaket'] == 1) {
                $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $ambil_dataproduk['id']]);

                foreach ($query_produkpaket->result_array() as $paket) :
                    $paketkodebarcode = $paket['paketkodebarcode'];
                    $paketjml = $paket['paketjml'];
                    // Kurangi stok tersedia
                    $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                    $row_dataproduk = $query_dataproduk->row_array();
                    $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                    $this->db->where('kodebarcode', $paketkodebarcode);
                    $this->db->update('produk', [
                        'stok_tersedia' => $stok_dataproduk + ($paketjml * $jml)
                    ]);
                // end kurang stok tersedia
                endforeach;

                //hapus item pada table temp
                $this->db->delete('temp_jual', ['detjualid' => $id]);

                $msg = [
                    'sukses' => 'berhasil'
                ];
            } else {
                //hapus item pada table temp
                $this->db->delete('temp_jual', ['detjualid' => $id]);

                $msg = [
                    'sukses' => 'berhasil'
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
            $saatini = date('Y-m-d');
            $this->load->model('admin/member/Modelmember', 'member');
            $list = $this->member->get_datatables();
            $data = array();
            $no = $_POST['start'];

            $data_diskonmember = $this->db->get('member_setting_diskon')->row_array();
            foreach ($list as $field) {
                $no++;
                $row = array();

                $row[] = $no;
                $row[] = $field->memberkode;
                $row[] = $field->membernama;
                $row[] = $field->membertelp;
                $row[] = $field->memberalamat;
                // Hitung sisa diskon member
                $ambil_datasettingdiskon = $this->db->get('member_setting_diskon')->row_array();
                $diskonsetting = $ambil_datasettingdiskon['diskon'];

                $query_tabungandiskon = $this->db->query("SELECT IFNULL(ROUND(SUM(jualtotalbersih * ($diskonsetting / 100)),0),0) AS totaldiskon FROM penjualan WHERE jualmemberkode='$field->memberkode' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$saatini' AND (jualstatusbayar='T' OR jualstatusbayar='K')")->row_array();

                $query_diskondigunakan = $this->db->query("SELECT IFNULL(SUM(jualtotalbersih),0) AS totaldigunakan FROM penjualan WHERE jualmemberkode='$field->memberkode' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$saatini' AND jualstatusbayar='M'")->row_array();

                $query_diskondiambil = $this->db->query("SELECT IFNULL(SUM(detambiljumlah),0) AS totaldiambil FROM pengambilan_diskon_detail JOIN pengambilan_diskon ON  detambilkode=ambilkode WHERE detambilmemberkode = '$field->memberkode' AND DATE_FORMAT(ambiltgl,'%Y-%m-%d') <= '$saatini'")->row_array();

                $totaldiskon = $query_tabungandiskon['totaldiskon'];
                $totaldigunakan = $query_diskondigunakan['totaldigunakan'];
                $totaldiambil = $query_diskondiambil['totaldiambil'];
                $sisadiskon = $totaldiskon - ($totaldigunakan + $totaldiambil);
                $row[] = number_format($sisadiskon, 0, ",", ".");
                // end
                $row[] = "<button type=\"button\" class=\"btn btn-sm btn-outline-info\" onclick=\"pilih('" . $field->memberkode . "','" . $field->membernama . "','" . $data_diskonmember['diskon'] . "','" . number_format($sisadiskon, 0, ",", ".") . "')\"><i class=\"fa fa-hand-point-up\"></i></button>";
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
            $total_kotor = $this->input->post('total_kotor', true);
            $total_bersih_semua = $this->input->post('total_bersih_semua', true);
            $pembulatan = $this->input->post('pembulatan', true);
            $dispersen =  $this->input->post('dispersensemua', true);
            $disuang =  $this->input->post('disuangsemua', true);

            $cek_data_temp = $this->db->get_where('temp_jual', ['detjualfaktur' => $faktur, 'detjualuserinput' => $this->session->userdata('username')]);

            if ($cek_data_temp->num_rows() > 0) {
                $data = [
                    'faktur' => $faktur,
                    'kodemember' => $kodemember,
                    'namamember' => $namamember,
                    'total_kotor' => $total_kotor,
                    'total_bersih_semua' => $total_bersih_semua,
                    'pembulatan' => ($pembulatan == 0) ? $total_bersih_semua : $pembulatan,
                    'dispersensemua' => $dispersen,
                    'disuangsemua' => $disuang
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
                // Mengembalikan Stok, jika yang dihapus adalah produk paket
                $query_penjualan_detail = $this->db->get_where('temp_jual', ['detjualfaktur' => $faktur]);
                foreach ($query_penjualan_detail->result_array() as $penjualanDetail) :
                    $detjualkodebarcode = $penjualanDetail['detjualkodebarcode'];
                    $detjualjml = $penjualanDetail['detjualjml'];
                    $cek_produk = $this->db->get_where('produk', ['kodebarcode' => $detjualkodebarcode])->row_array();
                    if ($cek_produk['produkpaket'] == '1') {
                        $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $cek_produk['id']]);

                        foreach ($query_produkpaket->result_array() as $paket) :
                            $paketkodebarcode = $paket['paketkodebarcode'];
                            $paketjml = $paket['paketjml'];
                            // Kurangi stok tersedia
                            $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                            $row_dataproduk = $query_dataproduk->row_array();
                            $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                            $this->db->where('kodebarcode', $paketkodebarcode);
                            $this->db->update('produk', [
                                'stok_tersedia' => $stok_dataproduk + ($paketjml * $detjualjml)
                            ]);
                        // end kurang stok tersedia
                        endforeach;
                    }
                endforeach;
                // End
                $this->db->delete('temp_jual', ['detjualfaktur' => $faktur, 'detjualuserinput' => $username]);

                $msg = ['sukses' => 'Berhasil dihapus'];
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
            $totalkotor = $this->input->post('total_kotor', true);
            $diskonpersen = $this->input->post('dispersensemua', true);
            $diskonuang = $this->input->post('disuangsemua', true);
            $totalbersih = $this->input->post('total_bersih_semua', true);
            $pembulatan = $this->input->post('pembulatan', true);
            $sisapembulatan = $pembulatan - $totalbersih;
            $jumlahuang = str_replace(",", "", $this->input->post('jumlahuang', true));
            $sisa = str_replace(",", "", $this->input->post('sisa', true));

            if ($statusbayar == 'T') {
                $input_tglpembayaran = '0000-00-00';
                $statuslunas = '1';
            } else {
                $input_tglpembayaran = $tglpembayaran;
                $statuslunas = '0';
            }

            $username = $this->session->userdata('username');
            $hitung_diskon = $totalkotor * ($diskonpersen / 100) + $diskonuang;

            if ($statusbayar == 'K' && (strlen($kodemember) == 0 || $kodemember == '-')) {
                $msg = [
                    'error' => 'Maaf untuk pembayaran kredit, member tidak boleh kosong'
                ];
            } else {
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
                    'jualuserinput' => $username,
                    'jualdiskon' => $hitung_diskon,
                    'jualpembulatan' => $pembulatan,
                    'jualsisapembulatan' => $sisapembulatan
                ];
                $this->db->insert('penjualan', $simpan_penjualan);

                //simpan penjualan detail
                $this->db->query("INSERT INTO penjualan_detail(detjualfaktur,detjualtgl,detjualkodebarcode,detjualsatid,detjualsatqty,detjualjml,detjualharga,detjualhargabeli,detjualsubtotal,detjualuserinput,detjualdispersen,detjualdisuang,detjualsubtotalkotor,detjualdiskon) (SELECT detjualfaktur,detjualtgl,detjualkodebarcode,detjualsatid,detjualsatqty,detjualjml,detjualharga,detjualhargabeli,detjualsubtotal,detjualuserinput,detjualdispersen,detjualdisuang,detjualsubtotalkotor,detjualdiskon FROM temp_jual WHERE detjualfaktur='$faktur' AND detjualuserinput='$username')");

                // Tambah Diskon Member
                if (strlen($kodemember) > 0) {
                    $ambil_datamember = $this->db->get_where('member', ['memberkode' => $kodemember])->row_array();
                    $membertotaldiskon = $ambil_datamember['membertotaldiskon'];
                    $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
                    $diskon_setting = $ambil_datamembersettingdiskon['diskon'];

                    $hitung_tabungandiskonmember = $totalbersih * ($diskon_setting / 100);

                    $this->db->where('memberkode', $kodemember);
                    $this->db->update('member', [
                        'membertotaldiskon' => $membertotaldiskon + $hitung_tabungandiskonmember
                    ]);

                    // Tambahkan ke Neraca 2-130 Simpanan tabungan member
                    $this->neraca->simpanantabunganmember($faktur, $diskon_setting);
                }
                // End Tambah Diskon Member

                // Hapus temp jual
                $this->db->delete('temp_jual', ['detjualfaktur' => $faktur, 'detjualuserinput' => $username]);


                // Neraca 1-160 Persediaan Barang Dagang
                $ambildata_penjualan_detail = $this->db->query("SELECT SUM(detjualjml*detjualhargabeli) AS total_harga,detjualtgl FROM penjualan_detail JOIN produk on produk.`kodebarcode`=detjualkodebarcode WHERE detjualfaktur ='$faktur' AND produkpaket BETWEEN 0 and 1");
                $row_penjualan_detail = $ambildata_penjualan_detail->row_array();

                $this->neraca->debit_persediaan_dagang_penjualan($faktur, $row_penjualan_detail['detjualtgl'], $row_penjualan_detail['total_harga']);

                // neraca 1-161 Persediaan saldo Pulsa
                $penjualandetail_pulsa = $this->db->query("SELECT SUM(detjualjml*detjualhargabeli) AS total_harga,detjualtgl FROM penjualan_detail JOIN produk on produk.`kodebarcode`=detjualkodebarcode WHERE detjualfaktur ='$faktur' AND produkpaket = 2")->row_array();
                $this->neraca->debit_persediaan_pulsa($faktur, $penjualandetail_pulsa['detjualtgl'], $penjualandetail_pulsa['total_harga']);

                // Neraca No.akun 1-130 Piutang Dagang
                $this->neraca->kredit_piutang_dagang($faktur);

                // Neraca akun 4-100 & 4-110
                $this->neraca->simpan_neraca_penjualan($faktur, $totalbersih);

                // Neraca Tambah Kas Kecil
                if ($statusbayar == 'T') {
                    $transket = "Transaksi Penjualan $faktur";
                    $this->db->insert('neraca_transaksi', [
                        'transno' => $faktur,
                        'transtgl' => date('Y-m-d'),
                        'transnoakun' => '1-110',
                        'transjenis' => 'K',
                        'transjml' => $pembulatan,
                        'transket' => $transket
                    ]);
                }
                // End Tambah Kas Kecil
                $msg = [
                    'sukses' => 'Transaksi berhasil disimpan',
                    'sisauang' => number_format($sisa, 0, ".", "."),
                    'nofaktur' => $faktur,
                    'cetakfaktur' => site_url('kasir/cetakfaktur/') . $faktur
                ];
            }
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

            $query_detail = $this->db->query("SELECT penjualan_detail.*, satuan.`satnama`,produk.`namaproduk` FROM penjualan_detail JOIN satuan ON detjualsatid=satid JOIN produk ON produk.`kodebarcode`=detjualkodebarcode WHERE detjualfaktur='$faktur'");

            $ambil_datamember = $this->db->get_where('member', ['memberkode' => $row_penjualan['jualmemberkode']]);
            if ($ambil_datamember->num_rows() > 0) {
                $row_member = $ambil_datamember->row_array();
                $kodemember = $row_member['memberkode'];
                $namamember = $row_member['membernama'];
                $tglsekarang = date('Y-m-d');
                $ambil_datasettingdiskon = $this->db->get('member_setting_diskon')->row_array();
                $diskonsetting = $ambil_datasettingdiskon['diskon'];

                // Hitung sisa tabungan diskon member
                $query_tabungandiskon = $this->db->query("SELECT IFNULL(ROUND(SUM(jualtotalbersih * ($diskonsetting / 100)),0),0) AS totaldiskon FROM penjualan WHERE jualmemberkode='$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$tglsekarang' AND (jualstatusbayar='T' OR jualstatusbayar='K')")->row_array();

                $query_diskondigunakan = $this->db->query("SELECT IFNULL(SUM(jualtotalbersih),0) AS totaldigunakan FROM penjualan WHERE jualmemberkode='$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$tglsekarang' AND jualstatusbayar='M'")->row_array();

                $query_diskondiambil = $this->db->query("SELECT IFNULL(SUM(detambiljumlah),0) AS totaldiambil FROM pengambilan_diskon_detail JOIN pengambilan_diskon ON  detambilkode=ambilkode WHERE detambilmemberkode = '$kodemember' AND ambiltgl <= '$tglsekarang'")->row_array();

                $totaldiskon = $query_tabungandiskon['totaldiskon'];
                $totaldigunakan = $query_diskondigunakan['totaldigunakan'];
                $totaldiambil = $query_diskondiambil['totaldiambil'];
                // end sisa tabungan diskon member

                $totaldiskonmember = $totaldiskon - ($totaldigunakan + $totaldiambil);
            }

            $data = [
                'namatoko' => $row_toko['nmtoko'],
                'alamattoko' => $row_toko['alamat'],
                'telptoko' => $row_toko['telp'],
                'hptoko' => $row_toko['hp'],
                'faktur' => $faktur,
                'totalkotor' => number_format($row_penjualan['jualtotalkotor'], 0, ",", "."),
                'totalbersih' => number_format($row_penjualan['jualtotalbersih'], 0, ",", "."),
                'disuang' => number_format($row_penjualan['jualdisuang'], 0, ",", "."),
                'dispersen' => $row_penjualan['jualdispersen'] . ' %',
                'namauser' => $this->session->userdata('namalengkapuser'),
                'tglfaktur' => $row_penjualan['jualtgl'],
                'jmluangbayar' => number_format($row_penjualan['jualjmluangbayar'], 0, ",", "."),
                'jmluangsisa' => number_format($row_penjualan['jualjmluangsisa'], 0, ",", "."),
                'detailpenjualan' => $query_detail,
                'member' => $row_penjualan['jualmemberkode'],
                'jualdispersen' => $row_penjualan['jualdispersen'],
                'jualdiskon' => $row_penjualan['jualdiskon'],
                'jualtotalbersih' => $row_penjualan['jualtotalbersih'],
                'jualjmluangsisa' => $row_penjualan['jualjmluangsisa'],
                'jualjmluangbayar' => $row_penjualan['jualjmluangbayar'],
                'kodemember' => $kodemember,
                'namamember' => $namamember,
                'totaldiskonmember' => $totaldiskonmember
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

                $tombolpilih = "<button type=\"button\" class=\"btn btn-info btn-sm waves-effect waves-light btnpilih\" onclick=\"pilih('" . $field->kodebarcode . "','" . $field->namaproduk . "')\" title=\"Pilih Item\">
                    <i class=\"fa fa-hand-point-up\"></i>
                </button>";
                $row[] = $no;
                $row[] = $field->kodebarcode;
                $row[] = $field->namaproduk;
                // Ambil data supplier Pembelian Produk
                $query_pembelianproduk = $this->db->query("SELECT idpemasok,pemasok.`nama` as namapemasok FROM pembelian JOIN pemasok ON pemasok.`id`=idpemasok JOIN pembelian_detail ON nofaktur=detfaktur
                JOIN produk ON kodebarcode=detkodebarcode WHERE detkodebarcode = '" . $field->kodebarcode . "' ");
                $datapemasok = '';
                foreach ($query_pembelianproduk->result_array() as $d) :
                    $datapemasok .= $d['namapemasok'] . "<br>";
                endforeach;
                $row[] = $datapemasok;
                // End
                $row[] = $field->satnama;
                $row[] = number_format($field->harga_jual_eceran, 2, ".", ",");
                $row[] = number_format($field->stok_tersedia, 2, ".", ",");
                // $row[] = $tombolpilih;
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
                $hargamodal = $row_produkharga['hargamodal'];
                $jml_default = $row_produkharga['jml_default'];
                $kodebarcode = $row_produkharga['kodebarcode'];

                //ambil data produk
                $query_produk = $this->db->get_where('produk', ['kodebarcode' => $kodebarcode]);
                $row_produk = $query_produk->row_array();
                $stok_tersedia = $row_produk['stok_tersedia'];

                if (($jualjml * $jml_default) > $stok_tersedia) {
                    $msg = [
                        'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button> Maaf stok tidak mencukupi
                                    </div>'
                    ];
                } else {
                    $subtotal = ($jualjml * $hargajual);

                    $data_update_tempjual = [
                        'detjualsatid' => $idsat,
                        'detjualsatqty' => $jml_default,
                        'detjualharga' => $hargajual,
                        'detjualhargabeli' => $hargamodal,
                        'detjualsubtotal' => $subtotal
                    ];
                    $this->db->where('detjualid', $id_tempjual);
                    $this->db->update('temp_jual', $data_update_tempjual);

                    $msg = [
                        'sukses' => 'Berhasil diganti'
                    ];
                }
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

    public function ambildatatemp_terakhir()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $username = $this->session->userdata('username');

            $query_cekdatatemp = $this->db->query("SELECT temp_jual.*,produk.`namaproduk`,satuan.`satnama` FROM temp_jual JOIN produk ON temp_jual.`detjualkodebarcode`=produk.`kodebarcode`
            JOIN satuan ON detjualsatid=satuan.`satid` WHERE detjualfaktur = '$faktur' AND detjualuserinput='$username' ORDER BY detjualid DESC");

            if ($query_cekdatatemp->num_rows() > 0) {
                $row = $query_cekdatatemp->row_array();

                $data = [
                    'id' => $row['detjualid'],
                    'kode' => $row['detjualkodebarcode'],
                    'namaproduk' => $row['namaproduk'],
                    'jml' => number_format($row['detjualjml'], 0),
                    'satuan' => $row['satnama'],
                    'hargajual' => number_format($row['detjualharga'], 2),
                    'dispersen' => number_format($row['detjualdispersen'], 2),
                    'disuang' => number_format($row['detjualdisuang'], 2, ".", ","),
                ];

                $msg = ['sukses' => $data];
            } else {
                $msg = [
                    'error' => 'Data transaksi belum ada'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function edititem_tempjual()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            $query_cekdatatemp = $this->db->query("SELECT temp_jual.*,produk.`namaproduk`,satuan.`satnama` FROM temp_jual JOIN produk ON temp_jual.`detjualkodebarcode`=produk.`kodebarcode`
            JOIN satuan ON detjualsatid=satuan.`satid` WHERE detjualid = '$id'");

            if ($query_cekdatatemp->num_rows() > 0) {
                $row = $query_cekdatatemp->row_array();

                $data = [
                    'id' => $row['detjualid'],
                    'kode' => $row['detjualkodebarcode'],
                    'namaproduk' => $row['namaproduk'],
                    'jml' => $row['detjualjml'],
                    'satuan' => $row['satnama'],
                    'hargajual' => number_format($row['detjualharga'], 2),
                    'dispersen' => number_format($row['detjualdispersen'], 2),
                    'disuang' => number_format($row['detjualdisuang'], 2, ".", ","),
                ];

                $msg = ['sukses' => $data];
            } else {
                $msg = [
                    'error' => 'Data transaksi belum ada'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function updatejmlproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $jml = $this->input->post('jml', true);
            $id = $this->input->post('id', true);
            $dispersen = $this->input->post('dispersen', true);
            $disuang = $this->input->post('disuang', true);
            $kode = $this->input->post('kode', true);

            // ambil data temp jual
            $query_ambildatatemp = $this->db->get_where('temp_jual', ['detjualid' => $id]);
            $row_temp_jual = $query_ambildatatemp->row_array();

            $detjualsatqty = $row_temp_jual['detjualsatqty'];
            $detjualjml = $row_temp_jual['detjualjml'];
            $detjualharga = $row_temp_jual['detjualharga'];
            // end temp jual

            // ambil data produk
            $query_ambildataproduk = $this->db->get_where('produk', ['kodebarcode' => $kode]);
            $row_produk = $query_ambildataproduk->row_array();
            $stoktersedia = $row_produk['stok_tersedia'];
            $produkpaket = $row_produk['produkpaket'];
            $idproduk = $row_produk['id'];
            // end produk

            //cek stok produk
            $kembalikan_stok = $stoktersedia + ($detjualjml * $detjualsatqty);
            $kali_jmlbaru = $jml * $detjualsatqty;

            // Ambil data pengaturan 
            $datapengaturan = $this->db->get_where('pengaturan', ['id' => 1])->row_array();
            // End

            if ($datapengaturan['stokminus'] == '1' && ($produkpaket == '0' || $produkpaket == '1')) {
                if ($kali_jmlbaru > $kembalikan_stok) {
                    $msg = [
                        'error' => 'Stok tidak mencukupi'
                    ];
                } else {
                    //update temp
                    $hitung_subtotal = ($jml *  $detjualharga);
                    $subtotal_bersih = $hitung_subtotal - ($hitung_subtotal * ($dispersen / 100)) - $disuang;
                    $hitung_diskon = $hitung_subtotal * ($dispersen / 100) + $disuang;
                    $dataupdate = [
                        'detjualjml' => $jml,
                        'detjualdispersen' => $dispersen,
                        'detjualdisuang' => $disuang,
                        'detjualsubtotalkotor' => $hitung_subtotal,
                        'detjualsubtotal' => $subtotal_bersih,
                        'detjualdiskon' => $hitung_diskon
                    ];
                    $this->db->where('detjualid', $id);
                    $this->db->update('temp_jual', $dataupdate);

                    if ($produkpaket == 1) {
                        $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $idproduk]);

                        foreach ($query_produkpaket->result_array() as $paket) :
                            $paketkodebarcode = $paket['paketkodebarcode'];
                            $paketjml = $paket['paketjml'];
                            // Kurangi stok tersedia
                            $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                            $row_dataproduk = $query_dataproduk->row_array();
                            $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                            $this->db->where('kodebarcode', $paketkodebarcode);
                            $this->db->update('produk', [
                                'stok_tersedia' => $stok_dataproduk - $paketjml
                            ]);
                        // end kurang stok tersedia
                        endforeach;

                        $msg = [
                            'sukses' => 'Berhasil diupdate'
                        ];
                    } else {
                        $msg = [
                            'sukses' => 'Berhasil diupdate'
                        ];
                    }
                }
            } else {
                //update temp
                $hitung_subtotal = ($jml *  $detjualharga);
                $subtotal_bersih = $hitung_subtotal - ($hitung_subtotal * ($dispersen / 100)) - $disuang;
                $hitung_diskon = $hitung_subtotal * ($dispersen / 100) + $disuang;
                $dataupdate = [
                    'detjualjml' => $jml,
                    'detjualdispersen' => $dispersen,
                    'detjualdisuang' => $disuang,
                    'detjualsubtotalkotor' => $hitung_subtotal,
                    'detjualsubtotal' => $subtotal_bersih,
                    'detjualdiskon' => $hitung_diskon
                ];
                $this->db->where('detjualid', $id);
                $this->db->update('temp_jual', $dataupdate);

                if ($produkpaket == 1) {
                    $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $idproduk]);

                    foreach ($query_produkpaket->result_array() as $paket) :
                        $paketkodebarcode = $paket['paketkodebarcode'];
                        $paketjml = $paket['paketjml'];
                        // Kurangi stok tersedia
                        $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                        $row_dataproduk = $query_dataproduk->row_array();
                        $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                        $this->db->where('kodebarcode', $paketkodebarcode);
                        $this->db->update('produk', [
                            'stok_tersedia' => $stok_dataproduk - $paketjml
                        ]);
                    // end kurang stok tersedia
                    endforeach;

                    $msg = [
                        'sukses' => 'Berhasil diupdate'
                    ];
                } else {
                    $msg = [
                        'sukses' => 'Berhasil diupdate'
                    ];
                }
            }



            echo json_encode($msg);
        }
    }
    public function updatetambahjml()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);
            $kode = $this->input->post('kode', true);

            // ambil data temp jual
            $query_ambildatatemp = $this->db->get_where('temp_jual', ['detjualid' => $id]);
            $row_temp_jual = $query_ambildatatemp->row_array();

            $detjualsatqty = $row_temp_jual['detjualsatqty'];
            $detjualjml = $row_temp_jual['detjualjml'];
            $detjualharga = $row_temp_jual['detjualharga'];
            $detjualdispersen = $row_temp_jual['detjualdispersen'];
            $detjualdisuang = $row_temp_jual['detjualdisuang'];
            // end temp jual

            // ambil data produk
            $query_ambildataproduk = $this->db->get_where('produk', ['kodebarcode' => $kode]);
            $row_produk = $query_ambildataproduk->row_array();
            $stoktersedia = $row_produk['stok_tersedia'];
            $produkpaket = $row_produk['produkpaket'];
            $idproduk = $row_produk['id'];
            // end produk

            //cek stok produk
            $kembalikan_stok = $stoktersedia + ($detjualjml * $detjualsatqty);
            $kali_jmlbaru = ($detjualjml + 1) * $detjualsatqty;

            // Ambil data pengaturan 
            $datapengaturan = $this->db->get_where('pengaturan', ['id' => 1])->row_array();
            // End

            if ($datapengaturan['stokminus'] == 1 && ($produkpaket == '0' || $produkpaket == '1')) {
                if ($kali_jmlbaru > $kembalikan_stok) {
                    $msg = [
                        'error' => 'Stok tidak mencukupi'
                    ];
                } else {
                    //update temp
                    $hitung_subtotal = ($detjualjml + 1) *  $detjualharga;
                    $subtotal_bersih = $hitung_subtotal - ($hitung_subtotal * ($detjualdispersen / 100)) - $detjualdisuang;
                    $hitung_diskon = $hitung_subtotal * ($detjualdispersen / 100) + $detjualdisuang;
                    $dataupdate = [
                        'detjualjml' => ($detjualjml + 1),
                        'detjualsubtotalkotor' => $hitung_subtotal,
                        'detjualsubtotal' => $subtotal_bersih,
                        'detjualdiskon' => $hitung_diskon
                    ];
                    $this->db->where('detjualid', $id);
                    $this->db->update('temp_jual', $dataupdate);

                    if ($produkpaket == 1) {
                        $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $idproduk]);

                        foreach ($query_produkpaket->result_array() as $paket) :
                            $paketkodebarcode = $paket['paketkodebarcode'];
                            $paketjml = $paket['paketjml'];
                            // Kurangi stok tersedia
                            $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                            $row_dataproduk = $query_dataproduk->row_array();
                            $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                            $this->db->where('kodebarcode', $paketkodebarcode);
                            $this->db->update('produk', [
                                'stok_tersedia' => $stok_dataproduk - $paketjml
                            ]);
                        // end kurang stok tersedia
                        endforeach;

                        $msg = [
                            'sukses' => 'Berhasil diupdate'
                        ];
                    } else {
                        $msg = [
                            'sukses' => 'Berhasil diupdate'
                        ];
                    }
                }
            } else {
                //update temp
                $hitung_subtotal = ($detjualjml + 1) *  $detjualharga;
                $subtotal_bersih = $hitung_subtotal - ($hitung_subtotal * ($detjualdispersen / 100)) - $detjualdisuang;
                $hitung_diskon = $hitung_subtotal * ($detjualdispersen / 100) + $detjualdisuang;
                $dataupdate = [
                    'detjualjml' => ($detjualjml + 1),
                    'detjualsubtotalkotor' => $hitung_subtotal,
                    'detjualsubtotal' => $subtotal_bersih,
                    'detjualdiskon' => $hitung_diskon
                ];
                $this->db->where('detjualid', $id);
                $this->db->update('temp_jual', $dataupdate);

                if ($produkpaket == 1) {
                    $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $idproduk]);

                    foreach ($query_produkpaket->result_array() as $paket) :
                        $paketkodebarcode = $paket['paketkodebarcode'];
                        $paketjml = $paket['paketjml'];
                        // Kurangi stok tersedia
                        $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                        $row_dataproduk = $query_dataproduk->row_array();
                        $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                        $this->db->where('kodebarcode', $paketkodebarcode);
                        $this->db->update('produk', [
                            'stok_tersedia' => $stok_dataproduk - $paketjml
                        ]);
                    // end kurang stok tersedia
                    endforeach;

                    $msg = [
                        'sukses' => 'Berhasil diupdate'
                    ];
                } else {
                    $msg = [
                        'sukses' => 'Berhasil diupdate'
                    ];
                }
            }



            echo json_encode($msg);
        }
    }

    function updatekurangjml()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);
            $kode = $this->input->post('kode', true);

            // ambil data temp jual
            $query_ambildatatemp = $this->db->get_where('temp_jual', ['detjualid' => $id]);
            $row_temp_jual = $query_ambildatatemp->row_array();

            $detjualsatqty = $row_temp_jual['detjualsatqty'];
            $detjualjml = $row_temp_jual['detjualjml'];
            $detjualharga = $row_temp_jual['detjualharga'];
            $detjualdispersen = $row_temp_jual['detjualdispersen'];
            $detjualdisuang = $row_temp_jual['detjualdisuang'];
            // end temp jual

            // ambil data produk
            $query_ambildataproduk = $this->db->get_where('produk', ['kodebarcode' => $kode]);
            $row_produk = $query_ambildataproduk->row_array();
            $stoktersedia = $row_produk['stok_tersedia'];
            $produkpaket = $row_produk['produkpaket'];
            $idproduk = $row_produk['id'];
            // end produk

            //cek stok produk
            $kembalikan_stok = $stoktersedia + ($detjualjml * $detjualsatqty);
            $kali_jmlbaru = ($detjualjml - 1) * $detjualsatqty;

            // if ($kali_jmlbaru > $kembalikan_stok) {
            //     $msg = [
            //         'error' => 'Stok tidak mencukupi'
            //     ];
            // } else {
            //update temp
            $hitung_subtotal = ($detjualjml - 1) *  $detjualharga;
            $subtotal_bersih = $hitung_subtotal - ($hitung_subtotal * ($detjualdispersen / 100)) - $detjualdisuang;
            $hitung_diskon = $hitung_subtotal * ($detjualdispersen / 100) + $detjualdisuang;
            $dataupdate = [
                'detjualjml' => ($detjualjml - 1),
                'detjualsubtotalkotor' => $hitung_subtotal,
                'detjualsubtotal' => $subtotal_bersih,
                'detjualdiskon' => $hitung_diskon
            ];
            $this->db->where('detjualid', $id);
            $this->db->update('temp_jual', $dataupdate);

            if ($produkpaket == 1) {
                $query_produkpaket = $this->db->get_where('produk_paket_item', ['paketidproduk' => $idproduk]);

                foreach ($query_produkpaket->result_array() as $paket) :
                    $paketkodebarcode = $paket['paketkodebarcode'];
                    $paketjml = $paket['paketjml'];
                    // Kurangi stok tersedia
                    $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $paketkodebarcode]);
                    $row_dataproduk = $query_dataproduk->row_array();
                    $stok_dataproduk = $row_dataproduk['stok_tersedia'];

                    $this->db->where('kodebarcode', $paketkodebarcode);
                    $this->db->update('produk', [
                        'stok_tersedia' => $stok_dataproduk - $paketjml
                    ]);
                // end kurang stok tersedia
                endforeach;

                $msg = [
                    'sukses' => 'Berhasil diupdate'
                ];
            } else {
                $msg = [
                    'sukses' => 'Berhasil diupdate'
                ];
            }
            // }

            echo json_encode($msg);
        }
    }

    public function holdingtransaksi()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $kodemember = $this->input->post('kodemember', true);
            $napel = $this->input->post('napel', true);
            $total_subtotal = $this->input->post('total_subtotal', true);
            $username = $this->session->userdata('username');

            //cek dulu di temp jual
            $cek_tempjual = $this->db->get_where('temp_jual', ['detjualfaktur' => $faktur, 'detjualuserinput' => $username]);
            //end
            if ($cek_tempjual->num_rows() > 0) {
                $simpan_datapenjualan = [
                    'jualfaktur' => $faktur,
                    'jualtgl' => date('Y-m-d H:i:s'),
                    'jualmemberkode' => $kodemember,
                    'jualstatusbayar' => 'H',
                    'jualtotalkotor' => $total_subtotal,
                    'jualtotalbersih' => $total_subtotal,
                    'jualstatuslunas' => 0,
                    'jualuserinput' => $username,
                    'jualnapel' => $napel
                ];
                $this->db->insert('penjualan', $simpan_datapenjualan);

                //simpan penjualan detail
                $this->db->query("INSERT INTO penjualan_detail(detjualfaktur,detjualtgl,detjualkodebarcode,detjualsatid,detjualsatqty,detjualjml,detjualharga,detjualhargabeli,detjualsubtotal,detjualuserinput,detjualdispersen,detjualdisuang,detjualsubtotalkotor,detjualdiskon) (SELECT detjualfaktur,detjualtgl,detjualkodebarcode,detjualsatid,detjualsatqty,detjualjml,detjualharga,detjualhargabeli,detjualsubtotal,detjualuserinput,detjualdispersen,detjualdisuang,detjualsubtotalkotor,detjualdiskon FROM temp_jual WHERE detjualfaktur='$faktur' AND detjualuserinput='$username')");

                // Hapus temp jual
                $this->db->delete('temp_jual', ['detjualfaktur' => $faktur, 'detjualuserinput' => $username]);

                $msg = [
                    'sukses' => 'Transaksi berhasil ditahan',
                ];
            } else {
                $msg = [
                    'error' => 'Maaf item belum ada untuk faktur ini !'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function data_transaksi_ditahan()
    {
        if ($this->input->is_ajax_request() == true) {
            $username = $this->session->userdata('username');

            $this->db->order_by('jualtgl', 'desc');
            $query = $this->db->get_where('penjualan', ['jualuserinput' => $username, 'jualstatusbayar' => 'H']);

            $data = [
                'tampildata' => $query
            ];

            $msg = [
                'data' => $this->load->view('admin/penjualan/modaltransaksiditahan', $data, true)
            ];

            echo json_encode($msg);
        }
    }

    public function edittransaksiditahan()
    {
        $sha_faktur = $this->uri->segment('3');
        $cekdata = $this->db->get_where('penjualan', ['sha1(jualfaktur)' => $sha_faktur, 'jualstatusbayar' => 'H']);

        if ($cekdata->num_rows() > 0) {
            $r = $cekdata->row_array();

            // ambildata member
            $querymember = $this->db->get_where('member', ['memberkode' => $r['jualmemberkode']]);
            if ($querymember->num_rows() > 0) {
                $rowMember = $querymember->row_array();
                $membernama = $rowMember['membernama'];
            } else {
                $membernama = '-';
            }

            $data = [
                'jualfaktur' => $r['jualfaktur'],
                'jualtgl' => date('d-m-Y', strtotime($r['jualtgl'])),
                'jualmemberkode' => $r['jualmemberkode'],
                'membernama' => $membernama,
                'jualnapel' => $r['jualnapel']
            ];

            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-tags"></i> Edit Transaksi',
                'isi' => $this->load->view('admin/penjualan/edittransaksi', $data, true)
            ];
            $this->parser->parse('layoutkasir/main', $view);
        } else {
            redirect('kasir/input');
        }
    }

    function detaildatamember()
    {
        if ($this->input->is_ajax_request()) {
            $kodemember = $this->input->post('kodemember', true);

            $cek_datamember = $this->db->get_where('member', ['memberkode' => $kodemember]);
            if ($cek_datamember->num_rows() > 0) {
                $row = $cek_datamember->row_array();
                $cek_datadiskon = $this->db->get('member_setting_diskon')->row_array();
                // Hitung sisa diskon member
                $saatini = date('Y-m-d');
                $ambil_datasettingdiskon = $this->db->get('member_setting_diskon')->row_array();
                $diskonsetting = $ambil_datasettingdiskon['diskon'];

                $query_tabungandiskon = $this->db->query("SELECT IFNULL(ROUND(SUM(jualtotalbersih * ($diskonsetting / 100)),0),0) AS totaldiskon FROM penjualan WHERE jualmemberkode='$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$saatini' AND (jualstatusbayar='T' OR jualstatusbayar='K')")->row_array();

                $query_diskondigunakan = $this->db->query("SELECT IFNULL(SUM(jualtotalbersih),0) AS totaldigunakan FROM penjualan WHERE jualmemberkode='$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$saatini' AND jualstatusbayar='M'")->row_array();

                $query_diskondiambil = $this->db->query("SELECT IFNULL(SUM(detambiljumlah),0) AS totaldiambil FROM pengambilan_diskon_detail JOIN pengambilan_diskon ON  detambilkode=ambilkode WHERE detambilmemberkode = '$kodemember' AND DATE_FORMAT(ambiltgl,'%Y-%m-%d') <= '$saatini'")->row_array();

                $totaldiskon = $query_tabungandiskon['totaldiskon'];
                $totaldigunakan = $query_diskondigunakan['totaldigunakan'];
                $totaldiambil = $query_diskondiambil['totaldiambil'];
                $sisadiskon = $totaldiskon - ($totaldigunakan + $totaldiambil);
                // End
                $msg = [
                    'sukses' => [
                        'namamember' => $row['membernama'],
                        'diskonmember' => $cek_datadiskon['diskon'],
                        'tabunganmember' => number_format($sisadiskon, 0, ",", ".")
                    ]
                ];
            } else {
                $msg = [
                    'error' => 'Data member tidak ditemukan...'
                ];
            }

            echo json_encode($msg);
        }
    }

    public function pembayaranmember()
    {
        if ($this->input->is_ajax_request()) {
            $tglsekarang = date('Y-m-d');
            $faktur = $this->input->post('faktur', true);
            $kodemember = $this->input->post('kodemember', true);
            $pembulatan = $this->input->post('pembulatan', true);
            $totalkotor = $this->input->post('total_kotor', true);
            $diskonpersen = $this->input->post('dispersensemua', true);
            $diskonuang = $this->input->post('disuangsemua', true);
            $totalbersih = $this->input->post('total_bersih_semua', true);

            $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
            $diskon_setting = $ambil_datamembersettingdiskon['diskon'];

            $query_totaldiskonmember = $this->db->query("SELECT IFNULL(ROUND(SUM(jualtotalbersih * ($diskon_setting / 100)),0),0) AS totaldiskonterkumpul FROM penjualan WHERE jualmemberkode = '$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d') <= '$tglsekarang' AND (jualstatusbayar = 'T' OR jualstatusbayar = 'K')")->row_array();

            $totaldiskon_terkumpul = $query_totaldiskonmember['totaldiskonterkumpul'];

            $query_totaldiskondigunakan = $this->db->query("SELECT IFNULL(SUM(jualtotalbersih),0) AS totaldiskondigunakan FROM penjualan WHERE jualmemberkode = '$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d') <= '$tglsekarang' AND jualstatusbayar = 'M'")->row_array();

            $totaldiskon_digunakan = $query_totaldiskondigunakan['totaldiskondigunakan'];

            $query_diskondiambil = $this->db->query("SELECT IFNULL(SUM(detambiljumlah),0) AS totaldiambil FROM pengambilan_diskon_detail JOIN pengambilan_diskon ON  detambilkode=ambilkode WHERE detambilmemberkode = '$kodemember' AND ambiltgl <= '$tglsekarang'")->row_array();

            $sisa_diskonmember = $totaldiskon_terkumpul - $totaldiskon_digunakan - $query_diskondiambil['totaldiambil'];

            if ($pembulatan > $sisa_diskonmember) {
                $msg = [
                    'error' => 'Maaf tabungan point anda tidak mencukupi !'
                ];
            } else {
                $username = $this->session->userdata('username');
                $hitung_diskon = $totalkotor * ($diskonpersen / 100) + $diskonuang;
                //simpan penjualan
                $simpan_penjualan = [
                    'jualfaktur' => $faktur,
                    'jualtgl' => date('Y-m-d H:i:s'),
                    'jualmemberkode' => $kodemember,
                    'jualstatusbayar' => 'M',
                    'jualtotalkotor' => $totalkotor,
                    'jualdispersen' => $diskonpersen,
                    'jualdisuang' => $diskonuang,
                    'jualtotalbersih' => $totalbersih,
                    'jualstatuslunas' => '1',
                    'jualuserinput' => $username,
                    'jualdiskon' => $hitung_diskon,
                    'jualpembulatan' => $pembulatan
                ];
                $this->db->insert('penjualan', $simpan_penjualan);

                // Update neraca 2-130 simpanan tabungan member
                $this->neraca->debit_simpanantabunganmember($faktur, $totalbersih);
                // End

                //simpan penjualan detail
                $this->db->query("INSERT INTO penjualan_detail(detjualfaktur,detjualtgl,detjualkodebarcode,detjualsatid,detjualsatqty,detjualjml,detjualharga,detjualhargabeli,detjualsubtotal,detjualuserinput,detjualdispersen,detjualdisuang,detjualsubtotalkotor,detjualdiskon) (SELECT detjualfaktur,detjualtgl,detjualkodebarcode,detjualsatid,detjualsatqty,detjualjml,detjualharga,detjualhargabeli,detjualsubtotal,detjualuserinput,detjualdispersen,detjualdisuang,detjualsubtotalkotor,detjualdiskon FROM temp_jual WHERE detjualfaktur='$faktur' AND detjualuserinput='$username')");

                // Hapus temp jual
                $this->db->delete('temp_jual', ['detjualfaktur' => $faktur, 'detjualuserinput' => $username]);


                // Neraca 1-160 Persediaan Barang Dagang
                $ambildata_penjualan_detail = $this->db->query("SELECT SUM(detjualjml*detjualhargabeli) AS total_harga,detjualtgl FROM penjualan_detail WHERE detjualfaktur ='$faktur'");
                $row_penjualan_detail = $ambildata_penjualan_detail->row_array();

                $this->neraca->debit_persediaan_dagang_penjualan($faktur, $row_penjualan_detail['detjualtgl'], $row_penjualan_detail['total_harga']);

                $msg = ['sukses' => 'Pembayaran menggunakan tabungan point berhasil dilakukan.'];
            }

            echo json_encode($msg);
        }
    }

    // Ukuran 80mm
    public function printDirect()
    {
        $faktur = $this->input->post('faktur');
        $cekfaktur = $this->db->get_where('penjualan', ['jualfaktur' => $faktur]);
        $row_penjualan = $cekfaktur->row_array();

        $query_toko = $this->db->get_where('nn_namatoko', ['idtoko' => 1]);
        $row_toko = $query_toko->row_array();

        $ambil_datamember = $this->db->get_where('member', ['memberkode' => $row_penjualan['jualmemberkode']]);
        if ($ambil_datamember->num_rows() > 0) {
            $row_member = $ambil_datamember->row_array();
            $kodemember = $row_member['memberkode'];
            $namamember = $row_member['membernama'];
            $tglsekarang = date('Y-m-d');
            $ambil_datasettingdiskon = $this->db->get('member_setting_diskon')->row_array();
            $diskonsetting = $ambil_datasettingdiskon['diskon'];

            // Hitung sisa tabungan diskon member
            $query_tabungandiskon = $this->db->query("SELECT IFNULL(ROUND(SUM(jualtotalbersih * ($diskonsetting / 100)),0),0) AS totaldiskon FROM penjualan WHERE jualmemberkode='$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$tglsekarang' AND (jualstatusbayar='T' OR jualstatusbayar='K')")->row_array();

            $query_diskondigunakan = $this->db->query("SELECT IFNULL(SUM(jualtotalbersih),0) AS totaldigunakan FROM penjualan WHERE jualmemberkode='$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$tglsekarang' AND jualstatusbayar='M'")->row_array();

            $query_diskondiambil = $this->db->query("SELECT IFNULL(SUM(detambiljumlah),0) AS totaldiambil FROM pengambilan_diskon_detail JOIN pengambilan_diskon ON  detambilkode=ambilkode WHERE detambilmemberkode = '$kodemember' AND ambiltgl <= '$tglsekarang'")->row_array();

            $totaldiskon = $query_tabungandiskon['totaldiskon'];
            $totaldigunakan = $query_diskondigunakan['totaldigunakan'];
            $totaldiambil = $query_diskondiambil['totaldiambil'];
            // end sisa tabungan diskon member

            $totaldiskonmember = $totaldiskon - ($totaldigunakan + $totaldiambil);
        } else {
            $kodemember = "";
            $namamember = "";
            $totaldiskonmember = 0;
        }

        // me-load library escpos
        $this->load->library('escpos');

        // membuat connector printer ke shared printer bernama "printer_a" (yang telah disetting sebelumnya)
        $connector = new Escpos\PrintConnectors\WindowsPrintConnector("printer_tokoku");

        // membuat objek $printer agar dapat di lakukan fungsinya
        $printer = new Escpos\Printer($connector);

        function buatBaris1Kolom($kolom1)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 45;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = count($kolom1Array);

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode($hasilBaris, "\n") . "\n";
        }

        function buatBaris3Kolom($kolom1, $kolom2, $kolom3)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 10;
            $lebar_kolom_2 = 18;
            $lebar_kolom_3 = 17;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);
            $kolom3Array = explode("\n", $kolom3);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode($hasilBaris, "\n") . "\n";
        }


        /* ---------------------------------------------------------
         * Teks biasa | text()
         */
        $tglfaktur = $row_penjualan['jualtgl'];
        $namauser = $this->session->userdata('namalengkapuser');
        $jualdispersen = $row_penjualan['jualdispersen'];
        $jualdiskon = $row_penjualan['jualdiskon'];
        $jmluangbayar = number_format($row_penjualan['jualjmluangbayar'], 0, ",", ".");
        $jmluangsisa = number_format($row_penjualan['jualjmluangsisa'], 0, ",", ".");

        $printer->initialize();
        $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
        $printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
        $printer->text("$row_toko[nmtoko]\n");
        ($row_toko['alamat']) ? $printer->text("$row_toko[alamat]\n") : '';
        ($row_toko['telp']) ? $printer->text("$row_toko[telp]\n") : '-';
        ($row_toko['hp']) ? $printer->text("$row_toko[hp]\n") : '-';
        $printer->text("\n");

        $printer->initialize();
        $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
        $printer->setJustification(Escpos\Printer::JUSTIFY_LEFT);
        $printer->text("No : $faktur, " . date('d/m/Y', strtotime($tglfaktur)));
        $printer->text("\n");
        $printer->text("Opr : $namauser, " . date('H:i:s', strtotime($tglfaktur)));

        // Jika Ini Member
        if (strlen($kodemember) > 0) :
            $printer->initialize();
            $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
            $printer->setJustification(Escpos\Printer::JUSTIFY_LEFT);
            $printer->text(buatBaris1Kolom("Pel: $namamember"));
        // $printer->text(buatBaris1Kolom("Total Tabungan: " . number_format($totaldiskonmember, 0, ",", ".")));
        endif;
        // End 

        $printer->initialize();
        $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
        $printer->text(buatBaris1Kolom("---------------------------------------------"));
        $query_detail = $this->db->query("SELECT penjualan_detail.*, satuan.`satnama`,produk.`namaproduk` FROM penjualan_detail JOIN satuan ON detjualsatid=satid JOIN produk ON produk.`kodebarcode`=detjualkodebarcode WHERE detjualfaktur='$faktur'");

        $totaljualkotor = 0;
        $totaljualbersih = 0;
        $totalitem = 0;
        foreach ($query_detail->result_array() as $d) :
            $jmlitem = $query_detail->num_rows();
            $totalitem = $totalitem + $d['detjualjml'];
            $totaljualkotor = $totaljualkotor + $d['detjualsubtotalkotor'];
            $totaljualbersih = $totaljualbersih + $d['detjualsubtotal'];
            $printer->text(buatBaris1Kolom("$d[namaproduk]"));
            $printer->text(buatBaris3Kolom(number_format($d['detjualjml'], 0, ',', '.') . " $d[satnama]", number_format($d['detjualharga'], 0, ',', '.'), number_format($d['detjualsubtotal'], 0, ',', '.')));
        endforeach;
        $printer->text(buatBaris1Kolom("---------------------------------------------"));
        $printer->text(buatBaris1Kolom("Item: $jmlitem ($totalitem)"));
        $printer->text(buatBaris3Kolom("", "Total:", number_format($totaljualbersih, 0, ",", ".")));
        // Jika ada diskon
        if ($jualdiskon != 0 || $jualdiskon != '0.00') :
            $printer->text(buatBaris3Kolom("", "#Dis:", number_format($jualdiskon, 0, ",", ".")));
        endif;
        // end
        $printer->text(buatBaris3Kolom("", "Bayar:", $jmluangbayar));
        $printer->text(buatBaris3Kolom("", "Kembali:", $jmluangsisa));
        $printer->text(buatBaris1Kolom("---------------------------------------------"));

        $printer->initialize();
        $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
        $printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
        $printer->text(buatBaris1Kolom("$row_toko[tulisanstruk]"));
        $printer->feed(3); // mencetak 2 baris kosong, agar kertas terangkat ke atas
        $printer->cut();
        echo "Faktur berhasil dicetak";
        $printer->close();
    }

    // Print Kerta 58mm
    // public function printDirect()
    // {
    //     $faktur = $this->input->post('faktur');
    //     $cekfaktur = $this->db->get_where('penjualan', ['jualfaktur' => $faktur]);
    //     $row_penjualan = $cekfaktur->row_array();

    //     $query_toko = $this->db->get_where('nn_namatoko', ['idtoko' => 1]);
    //     $row_toko = $query_toko->row_array();

    //     $ambil_datamember = $this->db->get_where('member', ['memberkode' => $row_penjualan['jualmemberkode']]);
    //     if ($ambil_datamember->num_rows() > 0) {
    //         $row_member = $ambil_datamember->row_array();
    //         $kodemember = $row_member['memberkode'];
    //         $namamember = $row_member['membernama'];
    //         $tglsekarang = date('Y-m-d');
    //         $ambil_datasettingdiskon = $this->db->get('member_setting_diskon')->row_array();
    //         $diskonsetting = $ambil_datasettingdiskon['diskon'];

    //         // Hitung sisa tabungan diskon member
    //         $query_tabungandiskon = $this->db->query("SELECT IFNULL(ROUND(SUM(jualtotalbersih * ($diskonsetting / 100)),0),0) AS totaldiskon FROM penjualan WHERE jualmemberkode='$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$tglsekarang' AND (jualstatusbayar='T' OR jualstatusbayar='K')")->row_array();

    //         $query_diskondigunakan = $this->db->query("SELECT IFNULL(SUM(jualtotalbersih),0) AS totaldigunakan FROM penjualan WHERE jualmemberkode='$kodemember' AND DATE_FORMAT(jualtgl,'%Y-%m-%d')<='$tglsekarang' AND jualstatusbayar='M'")->row_array();

    //         $query_diskondiambil = $this->db->query("SELECT IFNULL(SUM(detambiljumlah),0) AS totaldiambil FROM pengambilan_diskon_detail JOIN pengambilan_diskon ON  detambilkode=ambilkode WHERE detambilmemberkode = '$kodemember' AND ambiltgl <= '$tglsekarang'")->row_array();

    //         $totaldiskon = $query_tabungandiskon['totaldiskon'];
    //         $totaldigunakan = $query_diskondigunakan['totaldigunakan'];
    //         $totaldiambil = $query_diskondiambil['totaldiambil'];
    //         // end sisa tabungan diskon member

    //         $totaldiskonmember = $totaldiskon - ($totaldigunakan + $totaldiambil);
    //     } else {
    //         $kodemember = "";
    //         $namamember = "";
    //         $totaldiskonmember = 0;
    //     }

    //     // me-load library escpos
    //     $this->load->library('escpos');

    //     // membuat connector printer ke shared printer bernama "printer_a" (yang telah disetting sebelumnya)
    //     $connector = new Escpos\PrintConnectors\WindowsPrintConnector("printer_tokoku");

    //     // membuat objek $printer agar dapat di lakukan fungsinya
    //     $printer = new Escpos\Printer($connector);

    //     function buatBaris1Kolom($kolom1)
    //     {
    //         // Mengatur lebar setiap kolom (dalam satuan karakter)
    //         $lebar_kolom_1 = 30;

    //         // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
    //         $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);

    //         // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
    //         $kolom1Array = explode("\n", $kolom1);

    //         // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
    //         $jmlBarisTerbanyak = count($kolom1Array);

    //         // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
    //         $hasilBaris = array();

    //         // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
    //         for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

    //             // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
    //             $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");

    //             // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
    //             $hasilBaris[] = $hasilKolom1;
    //         }

    //         // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
    //         return implode($hasilBaris, "\n") . "\n";
    //     }

    //     function buatBaris3Kolom($kolom1, $kolom2, $kolom3)
    //     {
    //         // Mengatur lebar setiap kolom (dalam satuan karakter)
    //         $lebar_kolom_1 = 6;
    //         $lebar_kolom_2 = 12;
    //         $lebar_kolom_3 = 12;

    //         // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
    //         $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
    //         $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
    //         $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

    //         // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
    //         $kolom1Array = explode("\n", $kolom1);
    //         $kolom2Array = explode("\n", $kolom2);
    //         $kolom3Array = explode("\n", $kolom3);

    //         // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
    //         $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

    //         // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
    //         $hasilBaris = array();

    //         // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
    //         for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

    //             // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
    //             $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
    //             // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
    //             $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

    //             $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

    //             // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
    //             $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
    //         }

    //         // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
    //         return implode($hasilBaris, "\n") . "\n";
    //     }


    //     /* ---------------------------------------------------------
    //      * Teks biasa | text()
    //      */
    //     $tglfaktur = $row_penjualan['jualtgl'];
    //     $namauser = $this->session->userdata('namalengkapuser');
    //     $jualdispersen = $row_penjualan['jualdispersen'];
    //     $jualdiskon = $row_penjualan['jualdiskon'];
    //     $jmluangbayar = number_format($row_penjualan['jualjmluangbayar'], 0, ",", ".");
    //     $jmluangsisa = number_format($row_penjualan['jualjmluangsisa'], 0, ",", ".");

    //     $printer->initialize();
    //     $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
    //     $printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
    //     $printer->text("$row_toko[nmtoko]\n");
    //     ($row_toko['alamat']) ? $printer->text("$row_toko[alamat]\n") : '';
    //     ($row_toko['telp']) ? $printer->text("$row_toko[telp]\n") : '-';
    //     ($row_toko['hp']) ? $printer->text("$row_toko[hp]\n") : '-';
    //     $printer->text("\n");

    //     $printer->initialize();
    //     $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
    //     $printer->setJustification(Escpos\Printer::JUSTIFY_LEFT);
    //     $printer->text("No : $faktur, " . date('d/m/Y', strtotime($tglfaktur)));
    //     $printer->text("\n");
    //     $printer->text("Opr : $namauser, " . date('H:i:s', strtotime($tglfaktur)));
    //     $printer->text("\n");
    //     $printer->text("\n");

    //     // Jika Ini Member
    //     if (strlen($kodemember) > 0) :
    //         $printer->initialize();
    //         $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
    //         $printer->setJustification(Escpos\Printer::JUSTIFY_LEFT);
    //         $printer->text(buatBaris1Kolom("Pel : $kodemember/$namamember"));
    //     // $printer->text(buatBaris1Kolom("Total Tabungan: " . number_format($totaldiskonmember, 0, ",", ".")));
    //     endif;
    //     // End 
    //     $printer->initialize();
    //     $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
    //     $printer->text(buatBaris1Kolom("------------------------------"));
    //     $query_detail = $this->db->query("SELECT penjualan_detail.*, satuan.`satnama`,produk.`namaproduk` FROM penjualan_detail JOIN satuan ON detjualsatid=satid JOIN produk ON produk.`kodebarcode`=detjualkodebarcode WHERE detjualfaktur='$faktur'");

    //     $totaljualkotor = 0;
    //     $totaljualbersih = 0;
    //     $totalitem = 0;
    //     foreach ($query_detail->result_array() as $d) :
    //         $jmlitem = $query_detail->num_rows();
    //         $totalitem = $totalitem + $d['detjualjml'];
    //         $totaljualkotor = $totaljualkotor + $d['detjualsubtotalkotor'];
    //         $totaljualbersih = $totaljualbersih + $d['detjualsubtotal'];
    //         $printer->text(buatBaris1Kolom("$d[namaproduk]"));
    //         $printer->text(buatBaris3Kolom(number_format($d['detjualjml'], 0, ',', '.') . " $d[satnama]", number_format($d['detjualharga'], 0, ',', '.'), number_format($d['detjualsubtotal'], 0, ',', '.')));
    //     endforeach;
    //     $printer->text(buatBaris1Kolom("------------------------------"));
    //     $printer->text(buatBaris1Kolom("Item: $jmlitem ($totalitem)"));
    //     $printer->text(buatBaris3Kolom("", "Total:", number_format($totaljualbersih, 0, ",", ".")));
    //     // Jika ada diskon
    //     if ($jualdispersen != 0 || $jualdispersen != '0.00') :
    //         $printer->text(buatBaris3Kolom("", "#Dis:", number_format($jualdiskon, 0, ",", ".")));
    //     endif;
    //     // end
    //     $printer->text(buatBaris3Kolom("", "Bayar:", $jmluangbayar));
    //     $printer->text(buatBaris3Kolom("", "Kembali:", $jmluangsisa));
    //     $printer->text(buatBaris1Kolom("------------------------------"));
    //     $printer->text(buatBaris1Kolom("Terima kasih telah menjadi pelanggan kami :)"));
    //     $printer->feed(3); // mencetak 2 baris kosong, agar kertas terangkat ke atas
    //     $printer->cut();
    //     echo "Faktur berhasil dicetak";
    //     $printer->close();
    // }

    function modalPilihanHargaProduk()
    {
        if ($this->input->is_ajax_request()) {
            $kodebarcode = $this->input->post('kodebarcode', true);
            $id = $this->input->post('id', true);

            $ambilProduk = $this->db->get_where('produk', ['kodebarcode' => $kodebarcode])->row_array();
            $data = [
                'kode' => $kodebarcode,
                'namaproduk' => $ambilProduk['namaproduk'],
                'id' => $id,
                'hargaeceran' => $ambilProduk['harga_jual_eceran'],
                'hargagrosiran' => $ambilProduk['harga_jual_grosir'],
            ];

            $json = [
                'data' => $this->load->view('layoutkasir/modalPilihanHargaProduk', $data, true)
            ];
            echo json_encode($json);
        }
    }

    function updateHargaPenjualanKasir()
    {
        $kodebarcode = $this->input->post('gantiKodeBarcode', true);
        $id = $this->input->post('gantiId', true);

        $ambilTemp = $this->db->get_where('temp_jual', ['detjualid' => $id])->row_array();
        $detJualJml = $ambilTemp['detjualjml'];

        $harga = $this->input->post('piliharga', true);

        // update Temp Penjualan 
        $this->db->where('detjualid', $id);
        $this->db->update('temp_jual', [
            'detjualharga' => $harga,
            'detjualsubtotal' => $harga * intval($detJualJml)
        ]);

        $json = [
            'sukses' => 'Harga berhasil di update'
        ];
        echo json_encode($json);
    }
}