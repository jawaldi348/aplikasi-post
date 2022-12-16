<?php
class Penjualan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->aksesgrup = $this->session->userdata('idgrup');
        if ($this->session->userdata('masuk') == true && ($this->aksesgrup == '1' || $this->aksesgrup == '2')) {
            $this->load->library(['form_validation']);
            $this->load->model('Modelkasir', 'kasir');
            $this->load->model('admin/penjualan/Modelpenjualan', 'jual');
            $this->load->model('admin/member/Modelmember', 'member');
            $this->load->model('admin/member/Modelupdatediskon', 'updatediskon');
            $this->load->model('admin/Modeltransaksineraca', 'neraca');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $hariini = date('Y-m-d');
        $bulanini = date('Y-m');
        $tahunini = date('Y');
        $sql_penjualanhariini = $this->db->query("SELECT jualfaktur FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') = '$hariini' ")->result();

        $sql_penjualanbulanini = $this->db->query("SELECT jualfaktur FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m') = '$bulanini' ")->result();

        $sql_penjualantahunini = $this->db->query("SELECT jualfaktur FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y') = '$tahunini' ")->result();

        $data = [
            'totalhariini' => count($sql_penjualanhariini),
            'totalbulanini' => count($sql_penjualanbulanini),
            'totaltahunini' => count($sql_penjualantahunini),
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-cash-register"></i> Penjualan',
            'isi' => $this->load->view('admin/penjualan/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function transaksiditahan()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-hand-holding-usd"></i> Data Transaksi di-Tahan',
            'isi' => $this->load->view('admin/penjualan/datatransaksiditahan', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }
    public function all_data()
    {
        $data = [
            'datauser' => $this->db->get_where('nn_users', ['useraktif' => 1])
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-list-alt"></i> Semua Data Penjualan',
            'isi' => $this->load->view('admin/penjualan/semuadatatransaksi', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function ambildatatransaksiditahan()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/penjualan/Modelholding', 'penjualanditahan');
            $list = $this->penjualanditahan->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $tombolhapus = "<button type=\"button\" class=\"btn btn-xs btn-outline-danger waves-effect waves-light\" onclick=\"hapus('" . $field->jualfaktur . "')\" title=\"Hapus Transaksi Holding $field->jualfaktur\">
                    <i class=\"fa fa-trash-alt\" style=\"font-size:12px;\"></i>
                </button>";

                $tomboledit = "<button type=\"button\" class=\"btn btn-xs btn-outline-info waves-effect waves-light\" onclick=\"edit('" . sha1($field->jualfaktur) . "')\" title=\"Edit Transaksi $field->jualfaktur\">
                    <i class=\"fa fa-tags\" style=\"font-size:12px;\"></i>
                </button>";

                $row[] = $no;
                $row[] = $field->jualfaktur;
                $row[] = date('d-m-Y', strtotime($field->jualtgl));
                $row[] = ($field->jualnapel == '') ? '-' : $field->jualnapel;

                // Menampilkan item dari tabel detail penjualan
                $query_penjualandetail = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $field->jualfaktur])->result();

                $row[] = "<a href='#' onclick=\"itempenjualan('" . $field->jualfaktur . "')\">" . count($query_penjualandetail) . "</a>";

                $row[] = number_format($field->jualtotalkotor, 2, ".", ",");
                $row[] = $tombolhapus . '&nbsp;' . $tomboledit;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->penjualanditahan->count_all(),
                "recordsFiltered" => $this->penjualanditahan->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function ambilsemuadata()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/penjualan/Modeldata', 'penjualan');
            $tglawal = $this->input->post('tglawal', true);
            $tglakhir = $this->input->post('tglakhir', true);
            if ($this->session->userdata('idgrup') == 1) {
                $users = $this->input->post('users', true);
            } else {
                $users = $this->session->userdata('username');
            }
            $list = $this->penjualan->get_datatables($tglawal, $tglakhir, $users);
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                if ($this->aksesgrup == '1') {
                    $tombolhapus = "<button type=\"button\" class=\"btn btn-sm btn-outline-danger waves-effect waves-light\" onclick=\"hapus('" . $field->jualfaktur . "')\" title=\"Hapus Transaksi $field->jualfaktur\">
                    <i class=\"fa fa-trash-alt\" style=\"font-size:10px;\"></i>
                </button>";
                } else {
                    $tombolhapus = '';
                }

                $tomboledit = "<button type=\"button\" class=\"btn btn-sm btn-outline-info waves-effect waves-light\" onclick=\"edit('" . sha1($field->jualfaktur) . "')\" title=\"Edit Transaksi $field->jualfaktur\">
                    <i class=\"fa fa-tags\" style=\"font-size:10px;\"></i>
                </button>";

                $tombolcetak = "<button type=\"button\" class=\"btn btn-sm btn-outline-success waves-effect waves-light\" onclick=\"cetakfaktur('" . $field->jualfaktur . "')\" title=\"Cetak Struk $field->jualfaktur\">
                    <i class=\"fa fa-print\" style=\"font-size:10px;\"></i>
                </button>";

                $row[] = $no;
                $row[] = $field->jualfaktur;
                $row[] = date('d-m-Y', strtotime($field->jualtgl));
                $row[] = ($field->jualmemberkode == '') ? '-' : $field->membernama;
                if ($field->jualstatusbayar == 'T') {
                    $row[] = '<span class="badge badge-success">Tunai</span>';
                }
                if ($field->jualstatusbayar == 'K') {
                    $sttbayar = '<span class="badge badge-warning">Kredit</span>';
                    if ($field->jualstatuslunas == 1) {
                        $row[] = $sttbayar . '&nbsp;<span class="badge badge-success">Sudah Lunas Tgl: ' . date('d-m-Y', strtotime($field->jualtglbayarkredit)) . '</;span>';
                    } else {
                        $row[] = $sttbayar;
                    }
                }
                if ($field->jualstatusbayar == 'H') {
                    $row[] = '<span class="badge badge-info">diTahan</span>';
                }

                if ($field->jualstatusbayar == 'M') {
                    $row[] = '<span class="badge" style="background-color:#eb3300; color:#fff">Member</span>';
                }
                $row[] = $field->jualuserinput;
                // Menampilkan item dari tabel detail penjualan
                $query_penjualandetail = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $field->jualfaktur])->result();

                $row[] = "<a href='#' onclick=\"itempenjualan('" . $field->jualfaktur . "')\">" . count($query_penjualandetail) . "</a>";
                $row[] = number_format($field->jualtotalbersih, 2, ".", ",");
                $row[] = $tombolhapus . '&nbsp;' . $tomboledit . '&nbsp;' . $tombolcetak;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->penjualan->count_all($tglawal, $tglakhir, $users),
                "recordsFiltered" => $this->penjualan->count_filtered($tglawal, $tglakhir, $users),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    function detail_itempenjualan()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);

            $datadetail = $this->db->query("SELECT detjualkodebarcode,namaproduk,detjualjml,satnama,detjualjmlreturn,detjualharga,detjualsubtotal,detjualdispersen,detjualdisuang FROM penjualan_detail JOIN produk ON kodebarcode=detjualkodebarcode LEFT JOIN satuan ON satuan.`satid`=detjualsatid where detjualfaktur = '$faktur'");

            if ($datadetail->num_rows() > 0) {
                $data = [
                    'tampildata' => $datadetail,
                    'faktur' => $faktur
                ];

                $msg = [
                    'data' => $this->load->view('admin/penjualan/modalitempenjualan', $data, true)
                ];

                echo json_encode($msg);
            }
        }
    }

    function hapustransaksiditahan()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);


            $this->db->trans_start();
            $this->neraca->hapus_neraca_penjualan($faktur);
            $this->neraca->hapus_kredit_piutang_dagang($faktur);
            // Hapus Neraca Untuk Kas Kecil
            $this->db->delete('neraca_transaksi', [
                'transno' => $faktur,
                'transnoakun' => '1-110',
                'transjenis' => 'K'
            ]);
            // End

            // Mengembalikan Stok, jika yang dihapus adalah produk paket
            $query_penjualan_detail = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
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

            // neraca 1-161 Persediaan saldo Pulsa
            $penjualandetail_pulsa = $this->db->query("SELECT SUM(detjualjml*detjualhargabeli) AS total_harga,detjualtgl FROM penjualan_detail JOIN produk on produk.`kodebarcode`=detjualkodebarcode WHERE detjualfaktur ='$faktur' AND produkpaket = 2");
            if ($penjualandetail_pulsa->num_rows() > 0) {
                // Hapus neraca transaksi persediaan saldo
                $this->db->delete('neraca_transaksi', [
                    'transno' => $faktur,
                    'transnoakun' => '1-161', 'transjenis' => 'D'
                ]);
            }

            // Update Tabungan diskon member jika penjualan member
            $ambil_datapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
            $jualmemberkode = $ambil_datapenjualan['jualmemberkode'];
            $jualtotalbersih = $ambil_datapenjualan['jualtotalbersih'];

            if (strlen($jualmemberkode) > 0) {
                $ambil_datamember = $this->db->get_where('member', ['memberkode' => $jualmemberkode])->row_array();
                $membertotaldiskon = $ambil_datamember['membertotaldiskon'];
                $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
                $diskon_setting = $ambil_datamembersettingdiskon['diskon'];

                $hitung_tabungandiskonmember = $jualtotalbersih * ($diskon_setting / 100);

                $this->db->where('memberkode', $jualmemberkode);
                $this->db->update('member', [
                    'membertotaldiskon' => $membertotaldiskon - $hitung_tabungandiskonmember
                ]);

                $this->neraca->hapussimpanantabunganmember($faktur);
            }
            // end

            $this->db->delete('penjualan_detail', ['detjualfaktur' => $faktur]);
            $this->db->delete('penjualan', ['jualfaktur' => $faktur]);


            $this->db->trans_complete();

            if ($this->db->trans_status() === true) {
                // Hapus neraca akun
                $msg = ['sukses' => 'Transaksi di berhasil di hapus'];
            }
            echo json_encode($msg);
        }
    }

    public function edittransaksiditahan()
    {
        $sha_faktur = $this->uri->segment('4');
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
                'tanggaljual' => date('d-m-Y', strtotime($r['jualtgl'])),
                'jualtgl' => date('Y-m-d', strtotime($r['jualtgl'])),
                'jualmemberkode' => $r['jualmemberkode'],
                'membernama' => $membernama,
                'jualnapel' => $r['jualnapel'],
                'statusbayar' => $r['jualstatusbayar']
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

    public function edit()
    {
        $sha_faktur = $this->uri->segment('4');
        $cekdata = $this->db->get_where('penjualan', ['sha1(jualfaktur)' => $sha_faktur]);

        if ($cekdata->num_rows() > 0) {
            $r = $cekdata->row_array();

            // ambildata member
            $querymember = $this->db->get_where('member', ['memberkode' => $r['jualmemberkode']]);

            $datadiskonmember = $this->db->get('member_setting_diskon')->row_array();
            if ($querymember->num_rows() > 0) {
                $rowMember = $querymember->row_array();
                $membernama = $rowMember['membernama'];
                $diskonmember = $datadiskonmember['diskon'];
            } else {
                $membernama = '-';
                $diskonmember = 0;
            }

            $data = [
                'jualfaktur' => $r['jualfaktur'],
                'tanggaljual' => date('d-m-Y', strtotime($r['jualtgl'])),
                'jualtgl' => date('Y-m-d', strtotime($r['jualtgl'])),
                'jualmemberkode' => $r['jualmemberkode'],
                'membernama' => $membernama,
                'jualnapel' => $r['jualnapel'],
                'diskonmember' => $diskonmember,
                'statusbayar' => $r['jualstatusbayar']
            ];

            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-tags"></i> Edit Transaksi',
                'isi' => $this->load->view('admin/penjualan/edittransaksi', $data, true)
            ];
            $this->parser->parse('layoutkasir/main', $view);
        } else {
            redirect('admin/penjualan/transaksiditahan');
        }
    }

    public function tampildatatemp()
    {
        if ($this->input->is_ajax_request() == true) {
            $jualfaktur = $this->input->post('jualfaktur', true);
            $jualtgl = $this->input->post('jualtgl', true);
            $diskonmember = $this->input->post('diskonmember', true);

            $ambil_datapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $jualfaktur])->row_array();

            $tampildata = $this->db->query("SELECT detjualid AS id, detjualfaktur AS faktur,detjualkodebarcode AS kode,produk.`namaproduk` AS namaproduk,detjualsatid AS idsatuan, satuan.`satnama` AS namasatuan,detjualsatqty AS satqty, detjualjml AS jml,detjualharga AS harga,detjualsubtotal AS subtotal,detjualdispersen AS dispersen, detjualdisuang AS disuang,detjualjmlreturn as jmlreturn,detjualdiskon,detjualsubtotalkotor FROM penjualan_detail JOIN produk ON penjualan_detail.`detjualkodebarcode`=produk.`kodebarcode` JOIN satuan ON penjualan_detail.`detjualsatid`=satuan.`satid` WHERE detjualfaktur='$jualfaktur' ORDER BY detjualid DESC");
            $data = [
                'data' => $tampildata,
                'jualtgl' => $jualtgl,
                'diskonmember' => $diskonmember,
                'jualmemberkode' => $ambil_datapenjualan['jualmemberkode'],
                'jualtotalkotor' => $ambil_datapenjualan['jualtotalkotor'],
                'jualdispersen' => $ambil_datapenjualan['jualdispersen'],
                'jualdisuang' => $ambil_datapenjualan['jualdisuang'],
                'jualtotalbersih' => $ambil_datapenjualan['jualtotalbersih'],
                'jualpembulatan' => $ambil_datapenjualan['jualpembulatan'],
                'jualsisapembulatan' => $ambil_datapenjualan['jualsisapembulatan'],
            ];
            $this->load->view('admin/penjualan/datadetail', $data);
        }
    }

    public function detailproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $namaproduk = $this->input->post('namaproduk', true);
            $kode = $this->input->post('kode', true);
            $faktur = $this->input->post('faktur', true);
            $jualtgl = $this->input->post('jualtgl', true);
            $jml = $this->input->post('jml', true);
            $dispersen = $this->input->post('dispersen', true);
            $disuang = $this->input->post('disuang', true);

            $cekproduk = $this->kasir->cekproduk($kode, $namaproduk);

            if ($cekproduk->num_rows() > 0) {
                if ($cekproduk->num_rows() === 1) {
                    $row_produk = $cekproduk->row_array();
                    $kodebarcode = $row_produk['kodebarcode'];
                    $stoktersedia = $row_produk['stok_tersedia'];
                    $idproduk = $row_produk['id'];
                    $produkpaket = $row_produk['produkpaket'];

                    // Ambil data pengaturan 
                    $datapengaturan = $this->db->get_where('pengaturan', ['id' => 1])->row_array();
                    // End

                    if ($datapengaturan['stokminus'] == 1 && ($produkpaket == '0' || $produkpaket == '1')) {
                        if ($jml > $stoktersedia) {
                            $msg = [
                                'error' => 'Maaf Stok tidak cukup'
                            ];
                        } else {
                            $query_cek_penjualan_detail = $this->db->query("SELECT * FROM penjualan_detail WHERE detjualfaktur='$faktur' AND detjualkodebarcode='$kodebarcode' AND detjualsatid='$row_produk[satid]'");

                            if ($query_cek_penjualan_detail->num_rows() > 0) {
                                $row_penjualan_detail = $query_cek_penjualan_detail->row_array();

                                $jml_update = $row_penjualan_detail['detjualjml'] + $jml;

                                $subtotal_update = $jml_update * $row_penjualan_detail['detjualharga'];

                                $update_temp_saja =  [
                                    'detjualjml' => $jml_update,
                                    'detjualsubtotal' => $subtotal_update
                                ];
                                $this->db->where('detjualid', $row_penjualan_detail['detjualid']);
                                $this->db->update('penjualan_detail', $update_temp_saja);

                                // Update Tabel Penjualan
                                $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                                $total_detailpenjualan = 0;
                                foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                                    $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                                endforeach;

                                $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                                $jualdispersen = $ambildata_penjualan['jualdispersen'];
                                $jualdisuang = $ambildata_penjualan['jualdisuang'];

                                $kodemember =  $ambildata_penjualan['jualmemberkode'];
                                $totalbersih = $ambildata_penjualan['jualtotalbersih'];
                                $jualfaktur =  $ambildata_penjualan['jualfaktur'];


                                $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                                $ambilratusan = substr($hitung_totalbersih, -2);
                                if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                                    $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                                } else {
                                    $hasilpembulatan = $hitung_totalbersih;
                                }

                                $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                                $this->db->where('jualfaktur', $faktur);
                                $this->db->update('penjualan', [
                                    'jualtotalkotor' => $total_detailpenjualan,
                                    'jualtotalbersih' => $hitung_totalbersih,
                                    'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                                    'jualpembulatan' => $hasilpembulatan,
                                    'jualsisapembulatan' => $sisapembulatan
                                ]);
                                // End

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
                                    // Lakukan update pada akun simpanan tabungan
                                    // $this->neraca->simpanantabunganmember($faktur, $diskon_setting);
                                    // end
                                    $msg = ['sukses' => 'berhasil'];
                                } else {
                                    // Lakukan update pada akun simpanan tabungan
                                    // $this->neraca->simpanantabunganmember($faktur, $diskon_setting);
                                    // end
                                    $msg = ['sukses' => 'berhasil'];
                                }
                                // End

                            } else {
                                $qty_satuan = $row_produk['jml_eceran'];
                                $hargajual = $row_produk['harga_jual_eceran'];
                                $hargabeli = $row_produk['harga_beli_eceran'];


                                $hitung_subtotal = ($jml * $qty_satuan * $hargajual);
                                $subtotal_bersih = $hitung_subtotal - ($hitung_subtotal * ($dispersen / 100)) - $disuang;
                                $hitung_diskon = $hitung_subtotal * ($dispersen / 100) + $disuang;

                                $datasimpan_temp = [
                                    'detjualfaktur' => $faktur,
                                    'detjualtgl' => date('Y-m-d', strtotime($jualtgl)),
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
                                $this->db->insert('penjualan_detail', $datasimpan_temp);

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
                                    // Update Tabel Penjualan
                                    $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                                    $total_detailpenjualan = 0;
                                    foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                                        $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                                    endforeach;

                                    $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                                    $jualdispersen = $ambildata_penjualan['jualdispersen'];
                                    $jualdisuang = $ambildata_penjualan['jualdisuang'];
                                    $kodemember =  $ambildata_penjualan['jualmemberkode'];
                                    $totalbersih = $ambildata_penjualan['jualtotalbersih'];
                                    $jualfaktur =  $ambildata_penjualan['jualfaktur'];

                                    $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                                    $ambilratusan = substr($hitung_totalbersih, -2);
                                    if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                                        $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                                    } else {
                                        $hasilpembulatan = $hitung_totalbersih;
                                    }

                                    $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                                    $this->db->where('jualfaktur', $faktur);
                                    $this->db->update('penjualan', [
                                        'jualtotalkotor' => $total_detailpenjualan,
                                        'jualtotalbersih' => $hitung_totalbersih,
                                        'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                                        'jualpembulatan' => $hasilpembulatan,
                                        'jualsisapembulatan' => $sisapembulatan
                                    ]);
                                    // End

                                    $msg = ['sukses' => 'berhasil'];
                                } else {
                                    // Update Tabel Penjualan
                                    $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                                    $total_detailpenjualan = 0;
                                    foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                                        $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                                    endforeach;

                                    $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                                    $jualdispersen = $ambildata_penjualan['jualdispersen'];
                                    $jualdisuang = $ambildata_penjualan['jualdisuang'];
                                    $kodemember =  $ambildata_penjualan['jualmemberkode'];
                                    $totalbersih = $ambildata_penjualan['jualtotalbersih'];
                                    $jualfaktur =  $ambildata_penjualan['jualfaktur'];


                                    $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                                    $ambilratusan = substr($hitung_totalbersih, -2);
                                    if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                                        $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                                    } else {
                                        $hasilpembulatan = $hitung_totalbersih;
                                    }

                                    $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                                    $this->db->where('jualfaktur', $faktur);
                                    $this->db->update('penjualan', [
                                        'jualtotalkotor' => $total_detailpenjualan,
                                        'jualtotalbersih' => $hitung_totalbersih,
                                        'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                                        'jualpembulatan' => $hasilpembulatan,
                                        'jualsisapembulatan' => $sisapembulatan
                                    ]);
                                    // End

                                    $msg = ['sukses' => 'berhasil'];
                                }
                                // End
                            }
                        }
                    } else {
                        $query_cek_penjualan_detail = $this->db->query("SELECT * FROM penjualan_detail WHERE detjualfaktur='$faktur' AND detjualkodebarcode='$kodebarcode' AND detjualsatid='$row_produk[satid]'");

                        if ($query_cek_penjualan_detail->num_rows() > 0) {
                            $row_penjualan_detail = $query_cek_penjualan_detail->row_array();

                            $jml_update = $row_penjualan_detail['detjualjml'] + $jml;

                            $subtotal_update = $jml_update * $row_penjualan_detail['detjualharga'];

                            $update_temp_saja =  [
                                'detjualjml' => $jml_update,
                                'detjualsubtotal' => $subtotal_update
                            ];
                            $this->db->where('detjualid', $row_penjualan_detail['detjualid']);
                            $this->db->update('penjualan_detail', $update_temp_saja);

                            // Update Tabel Penjualan
                            $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                            $total_detailpenjualan = 0;
                            foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                                $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                            endforeach;

                            $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                            $jualdispersen = $ambildata_penjualan['jualdispersen'];
                            $jualdisuang = $ambildata_penjualan['jualdisuang'];

                            $kodemember =  $ambildata_penjualan['jualmemberkode'];
                            $totalbersih = $ambildata_penjualan['jualtotalbersih'];
                            $jualfaktur =  $ambildata_penjualan['jualfaktur'];


                            $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                            $ambilratusan = substr($hitung_totalbersih, -2);
                            if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                                $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                            } else {
                                $hasilpembulatan = $hitung_totalbersih;
                            }

                            $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                            $this->db->where('jualfaktur', $faktur);
                            $this->db->update('penjualan', [
                                'jualtotalkotor' => $total_detailpenjualan,
                                'jualtotalbersih' => $hitung_totalbersih,
                                'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                                'jualpembulatan' => $hasilpembulatan,
                                'jualsisapembulatan' => $sisapembulatan
                            ]);
                            // End

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
                                // Lakukan update pada akun simpanan tabungan
                                // $this->neraca->simpanantabunganmember($faktur, $diskon_setting);
                                // end
                                $msg = ['sukses' => 'berhasil'];
                            } else {
                                // Lakukan update pada akun simpanan tabungan
                                // $this->neraca->simpanantabunganmember($faktur, $diskon_setting);
                                // end
                                $msg = ['sukses' => 'berhasil'];
                            }
                            // End

                        } else {
                            $qty_satuan = $row_produk['jml_eceran'];
                            $hargajual = $row_produk['harga_jual_eceran'];
                            $hargabeli = $row_produk['harga_beli_eceran'];


                            $hitung_subtotal = ($jml * $qty_satuan * $hargajual);
                            $subtotal_bersih = $hitung_subtotal - ($hitung_subtotal * ($dispersen / 100)) - $disuang;
                            $hitung_diskon = $hitung_subtotal * ($dispersen / 100) + $disuang;

                            $datasimpan_temp = [
                                'detjualfaktur' => $faktur,
                                'detjualtgl' => date('Y-m-d', strtotime($jualtgl)),
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
                            $this->db->insert('penjualan_detail', $datasimpan_temp);

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
                                // Update Tabel Penjualan
                                $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                                $total_detailpenjualan = 0;
                                foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                                    $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                                endforeach;

                                $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                                $jualdispersen = $ambildata_penjualan['jualdispersen'];
                                $jualdisuang = $ambildata_penjualan['jualdisuang'];
                                $kodemember =  $ambildata_penjualan['jualmemberkode'];
                                $totalbersih = $ambildata_penjualan['jualtotalbersih'];
                                $jualfaktur =  $ambildata_penjualan['jualfaktur'];

                                $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                                $ambilratusan = substr($hitung_totalbersih, -2);
                                if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                                    $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                                } else {
                                    $hasilpembulatan = $hitung_totalbersih;
                                }

                                $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                                $this->db->where('jualfaktur', $faktur);
                                $this->db->update('penjualan', [
                                    'jualtotalkotor' => $total_detailpenjualan,
                                    'jualtotalbersih' => $hitung_totalbersih,
                                    'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                                    'jualpembulatan' => $hasilpembulatan,
                                    'jualsisapembulatan' => $sisapembulatan
                                ]);
                                // End

                                $msg = ['sukses' => 'berhasil'];
                            } else {
                                // Update Tabel Penjualan
                                $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                                $total_detailpenjualan = 0;
                                foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                                    $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                                endforeach;

                                $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                                $jualdispersen = $ambildata_penjualan['jualdispersen'];
                                $jualdisuang = $ambildata_penjualan['jualdisuang'];
                                $kodemember =  $ambildata_penjualan['jualmemberkode'];
                                $totalbersih = $ambildata_penjualan['jualtotalbersih'];
                                $jualfaktur =  $ambildata_penjualan['jualfaktur'];


                                $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                                $ambilratusan = substr($hitung_totalbersih, -2);
                                if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                                    $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                                } else {
                                    $hasilpembulatan = $hitung_totalbersih;
                                }

                                $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                                $this->db->where('jualfaktur', $faktur);
                                $this->db->update('penjualan', [
                                    'jualtotalkotor' => $total_detailpenjualan,
                                    'jualtotalbersih' => $hitung_totalbersih,
                                    'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                                    'jualpembulatan' => $hasilpembulatan,
                                    'jualsisapembulatan' => $sisapembulatan
                                ]);
                                // End

                                $msg = ['sukses' => 'berhasil'];
                            }
                            // End
                        }
                    }
                } else {
                    $data = [
                        'tampildata' => $cekproduk
                    ];
                    $msg = ['banyakdata' => $this->load->view('admin/penjualan/modaldatacariproduk', $data, true)];
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

            $query_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualid' => $id])->row_array();
            $faktur = $query_detailpenjualan['detjualfaktur'];

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
                $this->db->delete('penjualan_detail', ['detjualid' => $id]);

                // Update Tabel Penjualan
                $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                $total_detailpenjualan = 0;
                foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                    $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                endforeach;

                $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                $jualdispersen = $ambildata_penjualan['jualdispersen'];
                $jualdisuang = $ambildata_penjualan['jualdisuang'];

                $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                $ambilratusan = substr($hitung_totalbersih, -2);
                if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                    $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                } else {
                    $hasilpembulatan = $hitung_totalbersih;
                }

                $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                $this->db->where('jualfaktur', $faktur);
                $this->db->update('penjualan', [
                    'jualtotalkotor' => $total_detailpenjualan,
                    'jualtotalbersih' => $hitung_totalbersih,
                    'jualpembulatan' => $hasilpembulatan,
                    'jualsisapembulatan' => $sisapembulatan,
                    'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang
                ]);
                // End

                $msg = [
                    'sukses' => 'berhasil'
                ];
            } else {
                //hapus item pada table temp
                $this->db->delete('penjualan_detail', ['detjualid' => $id]);

                // Update Tabel Penjualan
                $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                $total_detailpenjualan = 0;
                foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                    $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                endforeach;

                $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                $jualdispersen = $ambildata_penjualan['jualdispersen'];
                $jualdisuang = $ambildata_penjualan['jualdisuang'];

                $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                $ambilratusan = substr($hitung_totalbersih, -2);
                if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                    $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                } else {
                    $hasilpembulatan = $hitung_totalbersih;
                }

                $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                $this->db->where('jualfaktur', $faktur);
                $this->db->update('penjualan', [
                    'jualtotalkotor' => $total_detailpenjualan,
                    'jualtotalbersih' => $hitung_totalbersih,
                    'jualpembulatan' => $hasilpembulatan,
                    'jualsisapembulatan' => $sisapembulatan,
                    'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang
                ]);
                // End
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
            $total_kotor = $this->input->post('total_kotor', true);
            $total_bersih_semua = $this->input->post('total_bersih_semua', true);
            $pembulatan = $this->input->post('pembulatan', true);
            $dispersen =  $this->input->post('dispersensemua', true);
            $disuang =  $this->input->post('disuangsemua', true);

            $cek_data_temp = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);

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
                    'sukses' => $this->load->view('admin/penjualan/modalpembayaran', $data, true)
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

            $cekfaktur_penjualan_detail = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur, 'detjualuserinput' => $username]);

            if ($cekfaktur_penjualan_detail->num_rows() > 0) {
                // Hapus Neraca
                $this->neraca->hapus_neraca_penjualan($faktur);

                // Hapus Neraca Untuk Kas Kecil
                $this->db->delete('neraca_transaksi', [
                    'transno' => $faktur,
                    'transnoakun' => '1-110',
                    'transjenis' => 'K'
                ]);

                // Mengembalikan Stok, jika yang dihapus adalah produk paket
                $query_penjualan_detail = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
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

                // Update Tabungan diskon member jika penjualan member
                $ambil_datapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                $jualmemberkode = $ambil_datapenjualan['jualmemberkode'];
                $jualtotalbersih = $ambil_datapenjualan['jualtotalbersih'];

                if (strlen($jualmemberkode) > 0) {
                    $ambil_datamember = $this->db->get_where('member', ['memberkode' => $jualmemberkode])->row_array();
                    $membertotaldiskon = $ambil_datamember['membertotaldiskon'];
                    $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
                    $diskon_setting = $ambil_datamembersettingdiskon['diskon'];

                    $hitung_tabungandiskonmember = $jualtotalbersih * ($diskon_setting / 100);

                    $this->db->where('memberkode', $jualmemberkode);
                    $this->db->update('member', [
                        'membertotaldiskon' => $membertotaldiskon - $hitung_tabungandiskonmember
                    ]);
                }
                // end

                $this->db->delete('penjualan_detail', ['detjualfaktur' => $faktur]);
                $this->db->delete('penjualan', ['jualfaktur' => $faktur]);


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
            $napel = $this->input->post('napel', true);
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

            $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
            $diskon_setting = $ambil_datamembersettingdiskon['diskon'];

            if (strlen($kodemember) > 0) {
                // Update tabungan diskon member
                $ambil_datamember = $this->db->get_where('member', ['memberkode' => $kodemember])->row_array();
                $member_totaldiskon = $ambil_datamember['membertotaldiskon'];

                $ambil_datapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                $jual_totalbersih = $ambil_datapenjualan['jualtotalbersih'];

                $kurangi_diskonlama = $member_totaldiskon - ($jual_totalbersih * $diskon_setting / 100);

                $ambil_diskonbaru = $totalbersih * $diskon_setting / 100;

                $this->db->where('memberkode', $kodemember);
                $this->db->update('member', [
                    'membertotaldiskon' => $ambil_diskonbaru + $kurangi_diskonlama
                ]);
                // End
            }

            //simpan penjualan
            $simpan_penjualan = [
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
            $this->db->where('jualfaktur', $faktur);
            $this->db->update('penjualan', $simpan_penjualan);

            if (strlen($kodemember) > 0) {
                $this->neraca->simpanantabunganmember($faktur, $diskon_setting);
            }

            // Neraca 1-160 Persediaan Barang Dagang
            $ambildata_penjualan_detail = $this->db->query("SELECT SUM(detjualjml*detjualhargabeli) AS total_harga,detjualtgl FROM penjualan_detail JOIN produk on produk.`kodebarcode`=detjualkodebarcode WHERE detjualfaktur ='$faktur' AND produkpaket BETWEEN 0 and 1");
            $row_penjualan_detail = $ambildata_penjualan_detail->row_array();

            $this->neraca->debit_persediaan_dagang_penjualan($faktur, date('Y-m-d'), $row_penjualan_detail['total_harga']);

            // neraca 1-161 Persediaan saldo Pulsa
            $penjualandetail_pulsa = $this->db->query("SELECT SUM(detjualjml*detjualhargabeli) AS total_harga,detjualtgl FROM penjualan_detail JOIN produk on produk.`kodebarcode`=detjualkodebarcode WHERE detjualfaktur ='$faktur' AND produkpaket = 2");
            $row_penjualan_detail_pulsa = $penjualandetail_pulsa->row_array();
            if ($row_penjualan_detail_pulsa['total_harga'] == 0) {
                // cek di neraca transaksi persediaan saldo
                $cekTransaksi_persediaanSaldo = $this->db->get_where('neraca_transaksi', [
                    'transno' => $faktur,
                    'transnoakun' => '1-161'
                ]);
                if ($cekTransaksi_persediaanSaldo->num_rows() > 0) {
                    $rowTransaksi_persediaanSaldo = $cekTransaksi_persediaanSaldo->row_array();
                    $transid = $rowTransaksi_persediaanSaldo['transid'];

                    $this->db->delete('neraca_transaksi', ['transid' => $transid]);
                }
            } else {
                $this->neraca->debit_persediaan_pulsa($faktur, $row_penjualan_detail_pulsa['detjualtgl'], $row_penjualan_detail_pulsa['total_harga']);
            }


            // Neraca No.akun 1-130 Piutang Dagang
            $this->neraca->kredit_piutang_dagang($faktur);

            $this->neraca->simpan_neraca_penjualan($faktur, $totalbersih);

            // Update neraca kas kecil
            if ($statusbayar == 'T') {
                $cek_neraca_transaksi_kaskecil = $this->db->get_where('neraca_transaksi', [
                    'transno' => $faktur,
                    'transnoakun' => '1-110',
                    'transjenis' => 'K'
                ]);

                if ($cek_neraca_transaksi_kaskecil->num_rows() > 0) {
                    $this->db->where('transno', $faktur);
                    $this->db->where('transnoakun', '1-110');
                    $this->db->where('transjenis', 'K');
                    $this->db->update('neraca_transaksi', [
                        'transjml' => $pembulatan
                    ]);
                } else {
                    $this->db->insert('neraca_transaksi', [
                        'transno' => $faktur,
                        'transnoakun' => '1-110',
                        'transjenis' => 'K',
                        'transtgl' => date('Y-m-d'),
                        'transjml' => $pembulatan
                    ]);
                }
            }
            // End Update neraca kas kecil
            $msg = [
                'sukses' => 'Transaksi berhasil disimpan',
                'sisauang' => number_format($sisa, 0, ".", "."),
                'cetakfaktur' => site_url('admin/penjualan/cetakfaktur/') . $faktur
            ];
            echo json_encode($msg);
        }
    }

    public function cetakfaktur()
    {
        $faktur = $this->uri->segment('4');

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
            } else {
                $kodemember = "";
                $namamember = "";
                $totaldiskonmember = 0;
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

            $this->load->view('admin/penjualan/cetakfaktur', $data);
        } else {
            exit('Data tidak ditemukan...');
        }
    }

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
            $lebar_kolom_1 = 30;

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
            $lebar_kolom_1 = 6;
            $lebar_kolom_2 = 12;
            $lebar_kolom_3 = 12;

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
        $printer->text("\n");
        $printer->text("\n");

        // Jika Ini Member
        if (strlen($kodemember) > 0) :
            $printer->initialize();
            $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
            $printer->setJustification(Escpos\Printer::JUSTIFY_LEFT);
            $printer->text(buatBaris1Kolom("Pel : $kodemember/$namamember"));
        // $printer->text(buatBaris1Kolom("Total Tabungan: " . number_format($totaldiskonmember, 0, ",", ".")));
        endif;
        // End 
        $printer->initialize();
        $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
        $printer->text(buatBaris1Kolom("------------------------------"));
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
        $printer->text(buatBaris1Kolom("------------------------------"));
        $printer->text(buatBaris1Kolom("Item: $jmlitem ($totalitem)"));
        $printer->text(buatBaris3Kolom("", "Total:", number_format($totaljualbersih, 0, ",", ".")));
        // Jika ada diskon
        if ($jualdiskon != 0 || $jualdiskon != '0.00') :
            $printer->text(buatBaris3Kolom("", "#Dis:", number_format($jualdiskon, 0, ",", ".")));
        endif;
        // end
        $printer->text(buatBaris3Kolom("", "Bayar:", $jmluangbayar));
        $printer->text(buatBaris3Kolom("", "Kembali:", $jmluangsisa));
        $printer->text(buatBaris1Kolom("------------------------------"));
        $printer->text(buatBaris1Kolom("Terima kasih telah menjadi pelanggan kami :)"));
        $printer->feed(3); // mencetak 2 baris kosong, agar kertas terangkat ke atas
        $printer->cut();
        echo "Faktur berhasil dicetak";
        $printer->close();
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
                        'tampilmodal' => $this->load->view('admin/penjualan/modalgantisatuan', $data, true)
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
                        'detjualsubtotal' => $subtotal
                    ];
                    $this->db->where('detjualid', $id_tempjual);
                    $this->db->update('penjualan_detail', $data_update_tempjual);

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

            $query_cekdatatemp = $this->db->query("SELECT penjualan_detail.*,produk.`namaproduk`,satuan.`satnama` FROM penjualan_detail JOIN produk ON penjualan_detail.`detjualkodebarcode`=produk.`kodebarcode`
            JOIN satuan ON detjualsatid=satuan.`satid` WHERE detjualfaktur = '$faktur' order by detjualid desc");

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

    public function edititem_tempjual()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            $query_cekdatatemp = $this->db->query("SELECT penjualan_detail.*,produk.`namaproduk`,satuan.`satnama` FROM penjualan_detail JOIN produk ON penjualan_detail.`detjualkodebarcode`=produk.`kodebarcode`
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
            $query_ambildatatemp = $this->db->get_where('penjualan_detail', ['detjualid' => $id]);
            $row_penjualan_detail = $query_ambildatatemp->row_array();

            $detjualsatqty = $row_penjualan_detail['detjualsatqty'];
            $detjualjml = $row_penjualan_detail['detjualjml'];
            $detjualharga = $row_penjualan_detail['detjualharga'];
            $faktur = $row_penjualan_detail['detjualfaktur'];
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

            if ($datapengaturan['stokminus'] == 1 && ($produkpaket == '0' || $produkpaket == '1')) {
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
                    $this->db->update('penjualan_detail', $dataupdate);

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

                        // Update Tabel Penjualan
                        $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                        $total_detailpenjualan = 0;
                        foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                            $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                        endforeach;

                        $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                        $jualdispersen = $ambildata_penjualan['jualdispersen'];
                        $jualdisuang = $ambildata_penjualan['jualdisuang'];

                        $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                        $ambilratusan = substr($hitung_totalbersih, -2);
                        if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                            $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                        } else {
                            $hasilpembulatan = $hitung_totalbersih;
                        }

                        $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                        $this->db->where('jualfaktur', $faktur);
                        $this->db->update('penjualan', [
                            'jualtotalkotor' => $total_detailpenjualan,
                            'jualtotalbersih' => $hitung_totalbersih,
                            'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                            'jualpembulatan' => $hasilpembulatan,
                            'jualsisapembulatan' => $sisapembulatan
                        ]);
                        // End

                        $msg = [
                            'sukses' => 'Berhasil diupdate'
                        ];
                    } else {
                        // Update Tabel Penjualan
                        $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                        $total_detailpenjualan = 0;
                        foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                            $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                        endforeach;

                        $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                        $jualdispersen = $ambildata_penjualan['jualdispersen'];
                        $jualdisuang = $ambildata_penjualan['jualdisuang'];

                        $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                        $ambilratusan = substr($hitung_totalbersih, -2);
                        if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                            $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                        } else {
                            $hasilpembulatan = $hitung_totalbersih;
                        }

                        $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                        $this->db->where('jualfaktur', $faktur);
                        $this->db->update('penjualan', [
                            'jualtotalkotor' => $total_detailpenjualan,
                            'jualtotalbersih' => $hitung_totalbersih,
                            'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                            'jualpembulatan' => $hasilpembulatan,
                            'jualsisapembulatan' => $sisapembulatan
                        ]);
                        // End
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
                $this->db->update('penjualan_detail', $dataupdate);

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

                    // Update Tabel Penjualan
                    $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                    $total_detailpenjualan = 0;
                    foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                        $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                    endforeach;

                    $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                    $jualdispersen = $ambildata_penjualan['jualdispersen'];
                    $jualdisuang = $ambildata_penjualan['jualdisuang'];

                    $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                    $ambilratusan = substr($hitung_totalbersih, -2);
                    if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                        $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                    } else {
                        $hasilpembulatan = $hitung_totalbersih;
                    }

                    $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                    $this->db->where('jualfaktur', $faktur);
                    $this->db->update('penjualan', [
                        'jualtotalkotor' => $total_detailpenjualan,
                        'jualtotalbersih' => $hitung_totalbersih,
                        'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                        'jualpembulatan' => $hasilpembulatan,
                        'jualsisapembulatan' => $sisapembulatan
                    ]);
                    // End

                    $msg = [
                        'sukses' => 'Berhasil diupdate'
                    ];
                } else {
                    // Update Tabel Penjualan
                    $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                    $total_detailpenjualan = 0;
                    foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                        $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                    endforeach;

                    $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                    $jualdispersen = $ambildata_penjualan['jualdispersen'];
                    $jualdisuang = $ambildata_penjualan['jualdisuang'];

                    $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                    $ambilratusan = substr($hitung_totalbersih, -2);
                    if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                        $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                    } else {
                        $hasilpembulatan = $hitung_totalbersih;
                    }

                    $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                    $this->db->where('jualfaktur', $faktur);
                    $this->db->update('penjualan', [
                        'jualtotalkotor' => $total_detailpenjualan,
                        'jualtotalbersih' => $hitung_totalbersih,
                        'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                        'jualpembulatan' => $hasilpembulatan,
                        'jualsisapembulatan' => $sisapembulatan
                    ]);
                    // End
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
            $query_ambildatapenjualandetail = $this->db->get_where('penjualan_detail', ['detjualid' => $id]);
            $row_penjualan_detail = $query_ambildatapenjualandetail->row_array();

            $detjualsatqty = $row_penjualan_detail['detjualsatqty'];
            $detjualjml = $row_penjualan_detail['detjualjml'];
            $detjualharga = $row_penjualan_detail['detjualharga'];
            $detjualdispersen = $row_penjualan_detail['detjualdispersen'];
            $detjualdisuang = $row_penjualan_detail['detjualdisuang'];
            $faktur = $row_penjualan_detail['detjualfaktur'];
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
                    $this->db->update('penjualan_detail', $dataupdate);

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

                        // Update Tabel Penjualan
                        $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                        $total_detailpenjualan = 0;
                        foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                            $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                        endforeach;

                        $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                        $jualdispersen = $ambildata_penjualan['jualdispersen'];
                        $jualdisuang = $ambildata_penjualan['jualdisuang'];
                        $kodemember =  $ambildata_penjualan['jualmemberkode'];
                        $totalbersih = $ambildata_penjualan['jualtotalbersih'];
                        $jualfaktur =  $ambildata_penjualan['jualfaktur'];

                        // Update diskon member jika transaksi adalah member
                        // if (strlen($kodemember) > 0) {
                        //     $this->updatediskon->updatediskonmember($kodemember, $jualfaktur, $total_detailpenjualan);
                        // }
                        // End

                        $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                        $ambilratusan = substr($hitung_totalbersih, -2);
                        if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                            $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                        } else {
                            $hasilpembulatan = $hitung_totalbersih;
                        }

                        $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                        $this->db->where('jualfaktur', $faktur);
                        $this->db->update('penjualan', [
                            'jualtotalkotor' => $total_detailpenjualan,
                            'jualtotalbersih' => $hitung_totalbersih,
                            'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                            'jualpembulatan' => $hasilpembulatan,
                            'jualsisapembulatan' => $sisapembulatan
                        ]);
                        // End

                        $msg = [
                            'sukses' => 'Berhasil diupdate'
                        ];
                    } else {
                        // Update Tabel Penjualan
                        $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                        $total_detailpenjualan = 0;
                        foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                            $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                        endforeach;

                        $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                        $jualdispersen = $ambildata_penjualan['jualdispersen'];
                        $jualdisuang = $ambildata_penjualan['jualdisuang'];
                        $kodemember =  $ambildata_penjualan['jualmemberkode'];
                        $jualfaktur =  $ambildata_penjualan['jualfaktur'];
                        $totalbersih = $ambildata_penjualan['jualtotalbersih'];

                        // Update diskon member jika transaksi adalah member
                        // if (strlen($kodemember) > 0) {
                        //     $this->updatediskon->updatediskonmember($kodemember, $jualfaktur, $total_detailpenjualan);
                        // }
                        // End

                        $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                        $ambilratusan = substr($hitung_totalbersih, -2);
                        if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                            $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                        } else {
                            $hasilpembulatan = $hitung_totalbersih;
                        }

                        $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                        $this->db->where('jualfaktur', $faktur);
                        $this->db->update('penjualan', [
                            'jualtotalkotor' => $total_detailpenjualan,
                            'jualtotalbersih' => $hitung_totalbersih,
                            'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                            'jualpembulatan' => $hasilpembulatan,
                            'jualsisapembulatan' => $sisapembulatan
                        ]);
                        // End

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
                $this->db->update('penjualan_detail', $dataupdate);

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

                    // Update Tabel Penjualan
                    $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                    $total_detailpenjualan = 0;
                    foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                        $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                    endforeach;

                    $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                    $jualdispersen = $ambildata_penjualan['jualdispersen'];
                    $jualdisuang = $ambildata_penjualan['jualdisuang'];
                    $kodemember =  $ambildata_penjualan['jualmemberkode'];
                    $totalbersih = $ambildata_penjualan['jualtotalbersih'];
                    $jualfaktur =  $ambildata_penjualan['jualfaktur'];

                    // Update diskon member jika transaksi adalah member
                    // if (strlen($kodemember) > 0) {
                    //     $this->updatediskon->updatediskonmember($kodemember, $jualfaktur, $total_detailpenjualan);
                    // }
                    // End

                    $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                    $ambilratusan = substr($hitung_totalbersih, -2);
                    if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                        $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                    } else {
                        $hasilpembulatan = $hitung_totalbersih;
                    }

                    $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                    $this->db->where('jualfaktur', $faktur);
                    $this->db->update('penjualan', [
                        'jualtotalkotor' => $total_detailpenjualan,
                        'jualtotalbersih' => $hitung_totalbersih,
                        'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                        'jualpembulatan' => $hasilpembulatan,
                        'jualsisapembulatan' => $sisapembulatan
                    ]);
                    // End

                    $msg = [
                        'sukses' => 'Berhasil diupdate'
                    ];
                } else {
                    // Update Tabel Penjualan
                    $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                    $total_detailpenjualan = 0;
                    foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                        $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                    endforeach;

                    $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                    $jualdispersen = $ambildata_penjualan['jualdispersen'];
                    $jualdisuang = $ambildata_penjualan['jualdisuang'];
                    $kodemember =  $ambildata_penjualan['jualmemberkode'];
                    $jualfaktur =  $ambildata_penjualan['jualfaktur'];
                    $totalbersih = $ambildata_penjualan['jualtotalbersih'];

                    // Update diskon member jika transaksi adalah member
                    // if (strlen($kodemember) > 0) {
                    //     $this->updatediskon->updatediskonmember($kodemember, $jualfaktur, $total_detailpenjualan);
                    // }
                    // End

                    $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                    $ambilratusan = substr($hitung_totalbersih, -2);
                    if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                        $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                    } else {
                        $hasilpembulatan = $hitung_totalbersih;
                    }

                    $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                    $this->db->where('jualfaktur', $faktur);
                    $this->db->update('penjualan', [
                        'jualtotalkotor' => $total_detailpenjualan,
                        'jualtotalbersih' => $hitung_totalbersih,
                        'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                        'jualpembulatan' => $hasilpembulatan,
                        'jualsisapembulatan' => $sisapembulatan
                    ]);
                    // End

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
            $query_ambildatatemp = $this->db->get_where('penjualan_detail', ['detjualid' => $id]);
            $row_penjualan_detail = $query_ambildatatemp->row_array();

            $detjualsatqty = $row_penjualan_detail['detjualsatqty'];
            $detjualjml = $row_penjualan_detail['detjualjml'];
            $detjualharga = $row_penjualan_detail['detjualharga'];
            $detjualdispersen = $row_penjualan_detail['detjualdispersen'];
            $detjualdisuang = $row_penjualan_detail['detjualdisuang'];
            $faktur = $row_penjualan_detail['detjualfaktur'];
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
            $this->db->update('penjualan_detail', $dataupdate);

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

                // Update Tabel Penjualan
                $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                $total_detailpenjualan = 0;
                foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                    $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                endforeach;

                $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                $jualdispersen = $ambildata_penjualan['jualdispersen'];
                $jualdisuang = $ambildata_penjualan['jualdisuang'];
                $jualfaktur = $ambildata_penjualan['jualfaktur'];
                $kodemember = $ambildata_penjualan['jualmemberkode'];
                $totalbersih = $ambildata_penjualan['jualtotalbersih'];


                // Update diskon member jika transaksi adalah member
                // if (strlen($kodemember) > 0) {
                //     $this->updatediskon->updatediskonmember($kodemember, $jualfaktur, $total_detailpenjualan);
                // }
                // End

                $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                $ambilratusan = substr($hitung_totalbersih, -2);
                if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                    $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                } else {
                    $hasilpembulatan = $hitung_totalbersih;
                }

                $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                $this->db->where('jualfaktur', $faktur);
                $this->db->update('penjualan', [
                    'jualtotalkotor' => $total_detailpenjualan,
                    'jualtotalbersih' => $hitung_totalbersih,
                    'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                    'jualpembulatan' => $hasilpembulatan,
                    'jualsisapembulatan' => $sisapembulatan
                ]);
                // End

                // Update Diskon Member
                if (strlen($kodemember) > 0) {
                    $ambil_datamember = $this->db->get_where('member', ['memberkode' => $kodemember])->row_array();
                    $membertotaldiskon = $ambil_datamember['membertotaldiskon'];
                    $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
                    $diskon_setting = $ambil_datamembersettingdiskon['diskon'];

                    $ambil_datapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                    $hitung_diskonlama = $ambil_datapenjualan['jualtotalbersih'] * ($diskon_setting / 100);

                    $hitung_tabungandiskonmember = $totalbersih * ($diskon_setting / 100);

                    $this->db->where('memberkode', $kodemember);
                    $this->db->update('member', [
                        'membertotaldiskon' => ($membertotaldiskon - $hitung_diskonlama) + $hitung_tabungandiskonmember
                    ]);
                }
                // End Update Diskon Member

                $msg = [
                    'sukses' => 'Berhasil diupdate'
                ];
            } else {
                // Update Tabel Penjualan
                $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                $total_detailpenjualan = 0;
                foreach ($ambildata_detailpenjualan->result_array() as $detail) :
                    $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
                endforeach;

                $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
                $jualdispersen = $ambildata_penjualan['jualdispersen'];
                $jualdisuang = $ambildata_penjualan['jualdisuang'];
                $jualfaktur = $ambildata_penjualan['jualfaktur'];
                $kodemember = $ambildata_penjualan['jualmemberkode'];
                $totalbersih = $ambildata_penjualan['jualtotalbersih'];

                // Update diskon member jika transaksi adalah member
                if (strlen($kodemember) > 0) {
                    $this->updatediskon->updatediskonmember($kodemember, $jualfaktur, $total_detailpenjualan);
                }
                // End

                $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

                $ambilratusan = substr($hitung_totalbersih, -2);
                if ($ambilratusan >= 01 && $ambilratusan <= 99) {
                    $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
                } else {
                    $hasilpembulatan = $hitung_totalbersih;
                }

                $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

                $this->db->where('jualfaktur', $faktur);
                $this->db->update('penjualan', [
                    'jualtotalkotor' => $total_detailpenjualan,
                    'jualtotalbersih' => $hitung_totalbersih,
                    'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
                    'jualpembulatan' => $hasilpembulatan,
                    'jualsisapembulatan' => $sisapembulatan
                ]);
                // End
                $msg = [
                    'sukses' => 'Berhasil diupdate'
                ];
            }
            // }

            echo json_encode($msg);
        }
    }

    function holdingtransaksi()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $kodemember = $this->input->post('kodemember', true);
            $napel = $this->input->post('napel', true);
            $total_subtotal = $this->input->post('total_subtotal', true);
            $username = $this->session->userdata('username');

            // Update ke status bayar H
            $this->db->where('jualfaktur', $faktur);
            $this->db->update('penjualan', [
                'jualstatusbayar' => 'H',
            ]);

            $msg = [
                'sukses' => "Transaksi <strong>$faktur</strong> berhasil ditahan"
            ];
            echo json_encode($msg);
        }
    }

    // Piutang
    public function all_data_piutang()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-file-invoice"></i> Semua Data Piutang',
            'isi' => $this->load->view('admin/penjualan/piutang/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function daftar_piutang_pelanggan()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-file-invoice"></i> Daftar Piutang Pelanggan',
            'isi' => $this->load->view('admin/penjualan/piutang/new/daftarpiutang', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function ambilDataDaftarPiutang()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/penjualan/Modeldaftarpiutang', 'datapiutang');
            $list = $this->datapiutang->get_datatables();
            $data = array();
            $no = $_POST['start'];

            $aksi = '';

            foreach ($list as $field) {
                $no++;
                $row = array();

                $aksi = "<button onclick=\"detail('" . sha1($field->jualmemberkode) . "')\" class=\"btn btn-primary btn-sm\" type=\"button\">Detail</button>";

                $row[] = $no;
                $row[] = $field->jualmemberkode;
                $row[] = $field->membernama;
                $row[] = number_format($field->totalpiutang, 0, ",", ".");
                // Cari Total Bayar
                $tanggal = date('Y-m-d');
                $query_totalbayar = $this->db->query("SELECT IFNULL(SUM(jualpembulatan),0) AS totalbayar FROM penjualan JOIN member ON jualmemberkode=memberkode WHERE jualstatusbayar='K' AND jualstatuslunas=1 AND jualmemberkode = '$field->jualmemberkode'")->row_array();

                $sisabayar = ($field->totalpiutang) - ($query_totalbayar['totalbayar']);

                $row[] = number_format($query_totalbayar['totalbayar'], 0, ",", ".");
                $row[] = number_format($sisabayar, 0, ",", ".");

                $row[] = $aksi;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->datapiutang->count_all(),
                "recordsFiltered" => $this->datapiutang->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function detail_piutang_pelanggan($kodemember)
    {
        $cekmember = $this->db->get_where('member', ['sha1(memberkode)' => $kodemember]);
        if ($cekmember->num_rows() > 0) {
            $rowMember = $cekmember->row_array();
            $data = [
                'memberkode' => $rowMember['memberkode'],
                'membernama' => $rowMember['membernama'],
                'memberalamat' => $rowMember['memberalamat'],
                'detailpiutang' => $this->db->query("SELECT jualfaktur,DATE_FORMAT(jualtgl,'%Y-%m-%d') AS jualtgl,jualmemberkode,jualpembulatan,(SELECT COUNT(detjualkodebarcode) FROM penjualan_detail WHERE jualfaktur=detjualfaktur) AS jmlitem FROM penjualan WHERE jualstatusbayar='K' AND jualstatuslunas = 0 AND jualmemberkode ='$rowMember[memberkode]' ORDER BY jualtgl desc")
            ];
            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-file-invoice"></i> Detail Piutang Pelanggan',
                'isi' => $this->load->view('admin/penjualan/piutang/new/detailpiutang', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            exit('Data kosong');
        }
    }

    function tampilPiutangBelumBayar()
    {
        if ($this->input->is_ajax_request()) {
            $kodemember = $this->input->post('kodemember', true);
            $data = [
                'detailpiutang' => $this->db->query("SELECT jualfaktur,DATE_FORMAT(jualtgl,'%Y-%m-%d') AS jualtgl,jualmemberkode,jualpembulatan,(SELECT COUNT(detjualkodebarcode) FROM penjualan_detail WHERE jualfaktur=detjualfaktur) AS jmlitem FROM penjualan WHERE jualstatusbayar='K' AND jualstatuslunas = 0 AND jualmemberkode ='$kodemember' ORDER BY jualtgl desc")
            ];
            $msg = [
                'data' => $this->load->view('admin/penjualan/piutang/new/datapiutangbelumbayar', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function ambildata_piutang()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/penjualan/Modeldatapiutang', 'datapiutang');
            $list = $this->datapiutang->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                if ($field->jualstatuslunas == 1) {
                    $aksi = '<span class="badge badge-success">Sudah Lunas, Tgl : ' . $field->jualtglbayarkredit . '</span>';
                } else {
                    $aksi = "<div class=\"btn-group\">
                    <button type=\"button\" class=\"btn btn-primary btn-sm dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                      Aksi
                    </button>
                        <div class=\"dropdown-menu\">
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"bayarpiutang('" . sha1($field->jualfaktur) . "')\">Bayar Piutang</a>
                         </div>            
                    </div>";
                }


                $row[] = $no;
                $row[] = $field->jualfaktur;
                $row[] = date('d-m-Y', strtotime($field->jualtgl));
                $row[] = date('d-m-Y', strtotime($field->jualtgljatuhtempo));
                $row[] = ($field->jualmemberkode == '') ? '-' : $field->jualmemberkode . '/' . $field->membernama;
                if ($field->jualstatuslunas == 1) {
                    $statuslunas = '<span class="badge badge-success">Sudah Lunas, Tgl : ' . $field->jualtglbayarkredit . '</span>';
                } else {
                    $statuslunas = '<span class="badge badge-danger">Belum Lunas</span>';
                }
                $row[] = $statuslunas;
                $row[] = $field->jualuserinput;
                // Menampilkan item dari tabel detail penjualan
                $query_penjualandetail = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $field->jualfaktur])->result();

                $row[] = count($query_penjualandetail);

                $row[] = number_format($field->jualtotalbersih, 2, ".", ",");
                $row[] = $aksi;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->datapiutang->count_all(),
                "recordsFiltered" => $this->datapiutang->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function ambilJumlahPenjualan()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);

            $ambil_totalpenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
            $msg = [
                'sukses' => $ambil_totalpenjualan['jualpembulatan']
            ];
            echo json_encode($msg);
        }
    }

    public function bayar_piutang($faktur)
    {
        $cekfakturpenjualan = $this->db->get_where('penjualan', ['sha1(jualfaktur)' => $faktur]);

        if ($cekfakturpenjualan->num_rows() > 0) {
            $arraydata = $cekfakturpenjualan->row_array();

            // ambil data member
            $datamember = $this->db->get_where('member', ['memberkode' => $arraydata['jualmemberkode']]);
            if ($datamember->num_rows() > 0) {
                $row_member = $datamember->row_array();
                $member = "$row_member[memberkode] / $row_member[membernama]";
            } else {
                $member = "-/-";
            }

            $data = [
                'row' => $arraydata,
                'member' => $member
            ];
            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-file-invoice"></i> Pembayaran Piutang Faktur : ' . $arraydata['jualfaktur'],
                'isi' => $this->load->view('admin/penjualan/piutang/formbayarpiutang', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            redirect('admin/penjualan/all-data-piutang');
        }
    }

    public function bayarPiutangPelanggan()
    {
        $faktur = $this->input->post('faktur', true);

        $jmldata = count($faktur);

        for ($i = 0; $i < $jmldata; $i++) {
            $dataPenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur[$i]])->row_array();
            $fakturPenjualan = $dataPenjualan['jualfaktur'];
            $statusLunasPenjualan = $dataPenjualan['jualstatuslunas'];
            $jualPembulatan = $dataPenjualan['jualpembulatan'];

            // Hapus neraca Piutang 
            if ($statusLunasPenjualan == "0") {
                $this->db->delete('neraca_transaksi', [
                    'transno' => $fakturPenjualan,
                    'transnoakun' => '1-130',
                    'transjenis' => 'K'
                ]);
            }

            // insert neraca kas kecil
            $cekNeracaKasKecil = $this->db->get_where('neraca_transaksi', [
                'transnoakun' => '1-110',
                'transjenis' => 'K',
                'transno' => $fakturPenjualan
            ]);

            if ($cekNeracaKasKecil->num_rows() > 0) {
                $rowKasKecil = $cekNeracaKasKecil->row_array();
                $transid = $rowKasKecil['transid'];

                $this->db->where('transid', $transid);
                $this->db->update('neraca_transaksi', [
                    'transjml' => $jualPembulatan
                ]);
            } else {
                $this->db->insert('neraca_transaksi', [
                    'transno' => $fakturPenjualan,
                    'transtgl' => date('Y-m-d'),
                    'transnoakun' => '1-110',
                    'transjenis' => 'K',
                    'transjml' => $jualPembulatan,
                    'transket' => 'Pembayaran Piutang'
                ]);
            }

            // Update tabel Penjualan
            $this->db->where('jualfaktur', $fakturPenjualan);
            $this->db->update('penjualan', [
                'jualstatuslunas' => 1,
                'jualtglbayarkredit' => date('Y-m-d'),
                'jualjmlbayarkredit' => $jualPembulatan,
                'jualketkredit' => '-'
            ]);
        }

        $msg = [
            'sukses' => 'Pembayaran Piutang berhasil disimpan',
        ];
        echo json_encode($msg);
    }

    function tampilDataPiutangSudahBayar()
    {
        if ($this->input->is_ajax_request()) {
            $kodemember = $this->input->post('kodemember', true);

            $msg = [
                'data' => $this->load->view('admin/penjualan/piutang/new/datapiutangsudahbayar', ['kodememberx' => $kodemember], true)
            ];
            echo json_encode($msg);
        }
    }

    function ambildata_PiutangSudahBayar()
    {
        if ($this->input->is_ajax_request()) {
            $kodemember = $this->input->post('kodemember', true);

            $this->load->model('admin/penjualan/Modeldatapiutangsudahbayar', 'datapiutangsudahbayar');
            $list = $this->datapiutangsudahbayar->get_datatables($kodemember);
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();


                $row[] = $no;
                $row[] = $field->jualfaktur;
                $row[] = date('d-m-Y', strtotime($field->jualtgl));
                $row[] = number_format($field->jualpembulatan, 0, ",", ".");
                if ($field->jualstatuslunas == 1) {
                    $row[] = "<span class=\"badge badge-success\">Lunas, Tanggal $field->jualtglbayarkredit</span>";
                }
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->datapiutangsudahbayar->count_all($kodemember),
                "recordsFiltered" => $this->datapiutangsudahbayar->count_filtered($kodemember),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    function simpanbayarpiutang()
    {
        if ($this->input->is_ajax_request()) {
            $jualfaktur = $this->input->post('jualfaktur', true);
            $tglbayar = $this->input->post('tglbayar', true);
            $jmlbayar = str_replace(",", "", $this->input->post('jmlbayar', true));
            $ket = $this->input->post('ket', true);
            $totalbersih = $this->input->post('totalbersih', true);

            $this->form_validation->set_rules('tglbayar', 'Tgl.Pembayaran', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('jmlbayar', 'Jumlah Pembayaran', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                if ($totalbersih != $jmlbayar) {
                    $msg = [
                        'error' => [
                            'jmlbayar' => "Jumlah Pembayaran harus sama dengan total bersih",
                        ]
                    ];
                } else {
                    // Hapus neraca piutang dagang
                    $ambil_datapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $jualfaktur]);
                    $row = $ambil_datapenjualan->row_array();
                    if ($row['jualstatuslunas'] == 0) {
                        $this->db->delete('neraca_transaksi', [
                            'transno' => $jualfaktur, 'transnoakun' => '1-130', 'transjenis' => 'K'
                        ]);
                    }
                    // End

                    // Insert ke neraca Transaksi kas kecil.
                    $cek_neraca_kas_kecil = $this->db->get_where('neraca_transaksi', [
                        'transno' => $jualfaktur,
                        'transnoakun' => '1-110',
                        'transjenis' => 'K'
                    ]);
                    if ($cek_neraca_kas_kecil->num_rows() > 0) {
                        $update_neraca_kaskecil = [
                            'transjml' => $jmlbayar,
                            'transtgl' => $tglbayar,
                        ];
                        $this->db->where('transno', $jualfaktur);
                        $this->db->where('transnoakun', '1-110');
                        $this->db->update('neraca_transaksi', $update_neraca_kaskecil);
                    } else {
                        $this->db->insert('neraca_transaksi', [
                            'transno' => $jualfaktur,
                            'transtgl' => $tglbayar,
                            'transnoakun' => '1-110',
                            'transjenis' => 'K',
                            'transjml' => $jmlbayar,
                            'transket' => 'Pembayaran Piutang'
                        ]);
                    }
                    // end

                    $updatedata = [
                        'jualstatuslunas' => 1,
                        'jualtglbayarkredit' => $tglbayar, 'jualjmlbayarkredit' => $jmlbayar,
                        'jualketkredit' => $ket
                    ];

                    $this->db->where('jualfaktur', $jualfaktur);
                    $this->db->update('penjualan', $updatedata);


                    $msg = [
                        'sukses' => 'Pembayaran berhasil dilakukan',
                        'cetakfaktur' => site_url('admin/penjualan/cetakfaktur/') . $jualfaktur
                    ];
                }
            } else {
                $msg = [
                    'error' => [
                        'jmlbayar' => form_error('jmlbayar'),
                        'tglbayar' => form_error('tglbayar')
                    ]
                ];
            }

            echo json_encode($msg);
        }
    }
    // End Piutang


    // Return Penjualan
    public function return_input()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-exchange-alt"></i> Input Return Penjualan',
            'isi' => $this->load->view('admin/penjualan/return/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function return_tampildatapenjualan()
    {
        $msg = [
            'data' => $this->load->view('admin/penjualan/return/semuadatapenjualan', '', true)
        ];
        echo json_encode($msg);
    }

    function return_ambildatapenjualan()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/penjualan/Modelreturndatapenjualan', 'penjualan');
            $list = $this->penjualan->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $tombolpilih = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"pilih('" . $field->jualfaktur . "','" . date('Y-m-d', strtotime($field->jualtgl)) . "','" . $field->membernama . "')\">
                    <i class=\"fa fa-hand-point-up\"></i>
                </button>";
                $row[] = $no;
                $row[] = $field->jualfaktur;
                $row[] = date('d-m-Y', strtotime($field->jualtgl));
                $row[] = ($field->jualmemberkode == '') ? '-' : $field->membernama;
                if ($field->jualstatusbayar == 'T') {
                    $row[] = '<span class="badge badge-success">Tunai</span>';
                }
                if ($field->jualstatusbayar == 'K') {
                    $sttbayar = '<span class="badge badge-warning">Kredit</span>';
                    if ($field->jualstatuslunas == 1) {
                        $row[] = $sttbayar . '&nbsp;<span class="badge badge-success">Sudah Lunas Tgl: ' . date('d-m-Y', strtotime($field->jualtglbayarkredit)) . '</;span>';
                    } else {
                        $row[] = $sttbayar;
                    }
                }
                if ($field->jualstatusbayar == 'H') {
                    $row[] = '<span class="badge badge-info">diTahan</span>';
                }

                if ($field->jualstatusbayar == 'M') {
                    $row[] = '<span class="badge" style="background-color:#eb3300; color:#fff">Member</span>';
                }
                $row[] = number_format($field->jualtotalbersih, 2, ".", ",");
                $row[] = $tombolpilih;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->penjualan->count_all(),
                "recordsFiltered" => $this->penjualan->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    function returnambildata()
    {
        if (isset($_GET['term'])) {
            $result = $this->jual->caridata($_GET['term']);
            if (count($result) > 0) {
                foreach ($result as $row) :
                    // $arr_result[] = $row->nofaktur . "-Tanggal : $row->tglbeli - $row->nama";
                    $arr_result[] = array(
                        'label' => "Faktur : $row->jualfaktur, Tgl.Faktur : " . date('d-m-Y', strtotime($row->jualtgl)) . ", Member : $row->membernama, Pelanggan : $row->jualnapel",
                        'faktur' => $row->jualfaktur,
                        'tgl' => date('Y-m-d', strtotime($row->jualtgl)),
                        'member' => $row->membernama,
                    );
                endforeach;
                echo json_encode($arr_result);
            }
        }
    }

    function return_ambilitempenjualan()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);

            $query_detailpenjualan = $this->jual->ambildetailpenjualan($faktur);
            $ambil_penjualan = $this->db->query("SELECT DATE_FORMAT(jualtgl,'%Y-%m-%d') AS jualtgl,jualmemberkode,membernama,jualnapel FROM penjualan LEFT JOIN member ON memberkode = jualmemberkode WHERE jualfaktur='$faktur'")->row_array();

            $data = [
                'datadetail' => $query_detailpenjualan,
            ];

            $msg = [
                'data' => $this->load->view('admin/penjualan/return/datadetail', $data, true),
                'tgl' => $ambil_penjualan['jualtgl'],
                'member' => $ambil_penjualan['jualmemberkode'] . '-' . $ambil_penjualan['membernama'],
                'napel' => $ambil_penjualan['jualnapel'],
            ];
            echo json_encode($msg);
        }
    }

    function buatnomor_return()
    {
        $tglhariini = date('Y-m-d');
        $query = $this->db->query("SELECT MAX(returnid) AS idreturn FROM penjualan_return WHERE DATE_FORMAT(returntgl,'%Y-%m-%d') = '$tglhariini'");
        $hasil = $query->row_array();
        $data  = $hasil['idreturn'];


        $lastNoUrut = substr($data, -5);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = 'RJ-' . date('dmy', strtotime($tglhariini)) . sprintf('%05s', $nextNoUrut);
        return $nextNoTransaksi;
    }

    function buatnomor_return_lagi()
    {
        $tglhariini = $this->input->post('tglreturn', true);
        $query = $this->db->query("SELECT MAX(returnid) AS idreturn FROM penjualan_return WHERE DATE_FORMAT(returntgl,'%Y-%m-%d') = '$tglhariini'");
        $hasil = $query->row_array();
        $data  = $hasil['idreturn'];


        $lastNoUrut = substr($data, -5);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = 'RJ-' . date('dmy', strtotime($tglhariini)) . sprintf('%05s', $nextNoUrut);
        $msg = [
            'idreturn' => $nextNoTransaksi
        ];
        echo json_encode($msg);
    }

    function return_produk()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $faktur = $this->input->post('faktur', true);

            $query_detail = $this->jual->ambildetailpenjualan_berdasarkanid($id);

            $data = [
                'idreturn' => $this->buatnomor_return(),
                'datadetail' => $query_detail
            ];

            $msg = [
                'data' => $this->load->view('admin/penjualan/return/modalreturnitem', $data, true)
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

    function simpandatareturn()
    {
        if ($this->input->is_ajax_request()) {
            $jualfaktur = $this->input->post('jualfaktur', true);
            $id = $this->input->post('id', true);
            $kodebarcode = $this->input->post('kodebarcode', true);

            $idreturn  = $this->input->post('idreturn', true);
            $qty  = $this->input->post('qty', true);
            $jmlreturn  = str_replace(",", "", $this->input->post('jmlreturn', true));
            $stt = $this->input->post('stt', true);
            $ket = $this->input->post('ket', true);
            $tglreturn = $this->input->post('tglreturn', true);

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
                if ($jmlreturn > $qty) {
                    $msg = [
                        'error' => [
                            'jmlreturn' => 'Jumlah return tidak boleh melebihi',
                        ]
                    ];
                } else {
                    $query_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualid' => $id]);
                    $rr = $query_detailpenjualan->row_array();
                    // Simpan data

                    $simpan_datareturn = [
                        'returnid' => $idreturn,
                        'returntgl' => $tglreturn,
                        'returndetjualid' => $id,
                        'returnkodebarcode' => $kodebarcode,
                        'returndetjualsatid' => $rr['detjualsatid'],
                        'returndetjualsatqty' => $rr['detjualsatqty'],
                        'returndetjualharga' => $rr['detjualharga'],
                        'returnjml' => $jmlreturn,
                        'returnstatusid' => $stt,
                        'returnket' => $ket
                    ];

                    $this->db->insert('penjualan_return', $simpan_datareturn);

                    // Update total dari tabel penjualan
                    $query_total_subtotal = $this->db->query("SELECT SUM(detjualsubtotal) AS total_subtotal FROM penjualan_detail WHERE detjualfaktur = '$jualfaktur'");
                    $row_total_subtotal = $query_total_subtotal->row_array();
                    $total_subtotal = $row_total_subtotal['total_subtotal'];

                    $query_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $jualfaktur]);
                    $row_penjualan = $query_penjualan->row_array();
                    $diskonpersen = $row_penjualan['jualdispersen'];
                    $diskonuang =  $row_penjualan['jualdisuang'];

                    $hitungtotalbersih = $total_subtotal - ($total_subtotal * $diskonpersen / 100) - $diskonuang;

                    $updatepenjualan = [
                        'jualtotalkotor' => $total_subtotal,
                        'jualtotalbersih' => $hitungtotalbersih
                    ];
                    $this->db->where('jualfaktur', $jualfaktur);
                    $this->db->update('penjualan', $updatepenjualan);

                    // Neraca Akun Persediaan Barang Dagang
                    $query_ambildatareturn = $this->db->query("SELECT * FROM penjualan_return JOIN penjualan_detail ON penjualan_detail.`detjualid`=penjualan_return.`returndetjualid` WHERE returnid='$idreturn'");
                    $r_ambildatareturn = $query_ambildatareturn->row_array();

                    $this->db->insert('neraca_transaksi', [
                        'transno' => $idreturn,
                        'transtgl' => $tglreturn,
                        'transnoakun' => '1-160',
                        'transjenis' => 'K',
                        'transjml' => $r_ambildatareturn['returnjml'] * $r_ambildatareturn['detjualhargabeli']
                    ]);
                    // End

                    // Neraca Penjualan
                    $this->neraca->simpan_neraca_penjualan($jualfaktur, $hitungtotalbersih);
                    // End Neraca Penjualan


                    // Neraca Kas Kecil
                    $this->neraca->simpan_debit_kaskecil_return_penjualan_dan_hpp($idreturn, $tglreturn);
                    // End Neraca Kas Kecil

                    $msg = [
                        'sukses' => 'Return produk berhasil disimpan'
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
            'judul' => '<i class="fa fa-tasks"></i> Data Return Item Produk Penjualan',
            'isi' => $this->load->view('admin/penjualan/return/data', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function return_ambildata()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/penjualan/Modeldatareturn', 'datareturn');

            $list = $this->datareturn->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $tombolhapus = "<button type=\"button\" class=\"btn btn-danger btn-sm waves-effect waves-light\" onclick=\"hapusreturn('" . $field->returnid . "','" . $field->detjualfaktur . "')\" title=\"Hapus Return ID\">
                    <i class=\"fa fa-trash-alt\"></i>
                </button>";
                $row[] = $no;
                $row[] = date('d-m-Y', strtotime($field->returntgl));
                $row[] = $field->detjualfaktur;
                $row[] = $field->membernama;
                $row[] = $field->jualnapel;
                $row[] = $field->returnkodebarcode . '/' . $field->namaproduk;
                $row[] = $field->returnjml;
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
            $jualfaktur = $this->input->post('faktur', true);


            // Hapus Neraca
            $this->db->delete('neraca_transaksi', [
                'transno' => $id,
                'transnoakun' => '1-160',
                'transjenis' => 'K'
            ]);


            // Hapus Return
            $this->db->delete('penjualan_return', ['returnid' => $id]);

            // Update total dari tabel penjualan
            $query_total_subtotal = $this->db->query("SELECT SUM(detjualsubtotal) AS total_subtotal FROM penjualan_detail WHERE detjualfaktur = '$jualfaktur'");
            $row_total_subtotal = $query_total_subtotal->row_array();
            $total_subtotal = $row_total_subtotal['total_subtotal'];

            $query_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $jualfaktur]);
            $row_penjualan = $query_penjualan->row_array();
            $diskonpersen = $row_penjualan['jualdispersen'];
            $diskonuang =  $row_penjualan['jualdisuang'];

            $hitungtotalbersih = $total_subtotal - ($total_subtotal * $diskonpersen / 100) - $diskonuang;

            $updatepenjualan = [
                'jualtotalkotor' => $total_subtotal,
                'jualtotalbersih' => $hitungtotalbersih
            ];
            $this->db->where('jualfaktur', $jualfaktur);
            $this->db->update('penjualan', $updatepenjualan);

            // Neraca Penjualan
            $this->neraca->simpan_neraca_penjualan($jualfaktur, $hitungtotalbersih);
            // End Neraca Penjualan

            // Neraca Kas Kecil
            $cek_neraca_kaskecil = $this->db->get_where('neraca_transaksi', [
                'transno' => $id,
                'transnoakun' => '1-110',
                'transjenis' => 'D',
            ]);

            if ($cek_neraca_kaskecil->num_rows() > 0) {
                $this->db->delete('neraca_transaksi', [
                    'transno' => $id,
                    'transnoakun' => '1-110'
                ]);
                $this->db->delete('neraca_transaksi', [
                    'transno' => $id,
                    'transnoakun' => '5-100',
                    'transjenis' => 'D'
                ]);
            }
            // End Neraca Kas Kecil

            $msg = [
                'sukses' => 'Return item berhasil dihapus !'
            ];

            echo json_encode($msg);
        }
    }

    // End Return Penjualan

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
                'data' => $this->load->view('admin/penjualan/modalPilihanHargaProduk', $data, true)
            ];
            echo json_encode($json);
        }
    }

    function updateHargaPenjualanKasir()
    {
        $kodebarcode = $this->input->post('gantiKodeBarcode', true);
        $id = $this->input->post('gantiId', true);

        $ambilTemp = $this->db->get_where('penjualan_detail', ['detjualid' => $id])->row_array();
        $detJualJml = $ambilTemp['detjualjml'];
        $faktur = $ambilTemp['detjualfaktur'];

        $harga = $this->input->post('piliharga', true);

        // update Temp Penjualan 
        $this->db->where('detjualid', $id);
        $this->db->update('penjualan_detail', [
            'detjualharga' => $harga,
            'detjualsubtotal' => $harga * intval($detJualJml)
        ]);

        // Update Tabel Penjualan
        $ambildata_detailpenjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
        $total_detailpenjualan = 0;
        foreach ($ambildata_detailpenjualan->result_array() as $detail) :
            $total_detailpenjualan = $total_detailpenjualan + $detail['detjualsubtotal'];
        endforeach;

        $ambildata_penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
        $jualdispersen = $ambildata_penjualan['jualdispersen'];
        $jualdisuang = $ambildata_penjualan['jualdisuang'];

        $hitung_totalbersih = $total_detailpenjualan - ($total_detailpenjualan * $jualdispersen / 100) - $jualdisuang;

        $ambilratusan = substr($hitung_totalbersih, -2);
        if ($ambilratusan >= 01 && $ambilratusan <= 99) {
            $hasilpembulatan = $hitung_totalbersih + (100 - $ambilratusan);
        } else {
            $hasilpembulatan = $hitung_totalbersih;
        }

        $sisapembulatan = $hasilpembulatan - $hitung_totalbersih;

        $this->db->where('jualfaktur', $faktur);
        $this->db->update('penjualan', [
            'jualtotalkotor' => $total_detailpenjualan,
            'jualtotalbersih' => $hitung_totalbersih,
            'jualdiskon' => ($total_detailpenjualan * $jualdispersen / 100) + $jualdisuang,
            'jualpembulatan' => $hasilpembulatan,
            'jualsisapembulatan' => $sisapembulatan
        ]);
        // End

        $json = [
            'sukses' => 'Harga berhasil di update'
        ];
        echo json_encode($json);
    }
}