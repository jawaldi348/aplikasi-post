<?php
defined('BASEPATH') or die('No direct script access allowed');

require('./apptot/novinaldi/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && ($this->session->userdata('idgrup') == '1' || $this->session->userdata('idgrup') == '4')) {
            $this->load->library(array(
                'form_validation'
            ));
            $this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
            $this->load->model('Modeltoko', 'toko');
            return true;
        } else {
            redirect('login/logout');
        }
    }

    // Penjualan Kasir
    public function penjualan_kasir()
    {
        $data = [
            'datakasir' => $this->db->query("SELECT * FROM nn_users WHERE usergrup between 1 and 2")
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-file-archive"></i> Penjualan Kasir',
            'isi' => $this->load->view('admin/laporan/penjualan/kasir/input', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function tampilpenjualankasir()
    {
        if ($this->input->is_ajax_request()) {
            $tglawal = $this->input->post('tglawal', true);
            $tglakhir = $this->input->post('tglakhir', true);
            $kasir = $this->input->post('kasir', true);

            if (strlen($kasir) == 0) {
                $penjualandetail = $this->db->query("SELECT detjualkodebarcode,namaproduk,detjualjml,detjualharga,detjualsubtotal,(detjualsubtotal*detjualdispersen/100-detjualdisuang) AS diskon
            FROM penjualan_detail JOIN produk ON detjualkodebarcode = kodebarcode WHERE DATE_FORMAT(detjualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' ORDER BY detjualtgl ASC");

                $data = [
                    'tglawal' => $tglawal,
                    'tglakhir' => $tglakhir,
                    'toko' => $this->toko->datatoko()->row_array(),
                    'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
                    'kasir' => '',
                    'penjualan' => $this->db->query("SELECT jualuserinput, SUM(jualtotalkotor) AS totalkotor, SUM((jualtotalkotor*jualdispersen/100-jualdisuang)) AS diskon, SUM(jualtotalbersih) AS totalbersih FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'"),
                    'detailpenjualan' => $penjualandetail,
                    'namalaporan' => 'Laporan Transaksi Penjualan'
                ];
            } else {
                $penjualandetail = $this->db->query("SELECT detjualkodebarcode,namaproduk,detjualjml,detjualharga,detjualsubtotal,(detjualsubtotal*detjualdispersen/100-detjualdisuang) AS diskon
            FROM penjualan_detail JOIN produk ON detjualkodebarcode = kodebarcode WHERE DATE_FORMAT(detjualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' AND detjualuserinput='$kasir' ORDER BY detjualtgl ASC");

                $data = [
                    'tglawal' => $tglawal,
                    'tglakhir' => $tglakhir,
                    'toko' => $this->toko->datatoko()->row_array(),
                    'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
                    'kasir' => $kasir,
                    'penjualan' => $this->db->query("SELECT jualuserinput, SUM(jualtotalkotor) AS totalkotor, SUM((jualtotalkotor*jualdispersen/100-jualdisuang)) AS diskon, SUM(jualtotalbersih) AS totalbersih FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' AND jualuserinput='$kasir'"),
                    'detailpenjualan' => $penjualandetail,
                    'namalaporan' => 'Laporan Transaksi Penjualan'
                ];
            }


            $msg = [
                'data' => $this->load->view('admin/laporan/penjualan/kasir/tampildata', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function cetak_penjualan_kasir()
    {
        $kasir = $this->input->post('kasir', true);
        $tglawal = $this->input->post('awal', true);
        $tglakhir = $this->input->post('akhir', true);
        $kasir = $this->input->post('kasir', true);

        $ambildatakasir = $this->db->get_where('nn_users', ['userid' => $kasir]);

        if (strlen($kasir) == 0) {
            $penjualandetail = $this->db->query("SELECT detjualkodebarcode,namaproduk,detjualjml,detjualharga,detjualsubtotal,(detjualsubtotal*detjualdispersen/100-detjualdisuang) AS diskon
        FROM penjualan_detail JOIN produk ON detjualkodebarcode = kodebarcode WHERE DATE_FORMAT(detjualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' ORDER BY detjualtgl ASC");

            $data = [
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir,
                'toko' => $this->toko->datatoko()->row_array(),
                'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
                'kasir' => '',
                'penjualan' => $this->db->query("SELECT jualuserinput, SUM(jualtotalkotor) AS totalkotor, SUM((jualtotalkotor*jualdispersen/100-jualdisuang)) AS diskon, SUM(jualtotalbersih) AS totalbersih FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'"),
                'detailpenjualan' => $penjualandetail,
                'namalaporan' => 'Laporan Transaksi Penjualan'
            ];
        } else {
            $datakasir = $ambildatakasir->row_array();
            $penjualandetail = $this->db->query("SELECT detjualkodebarcode,namaproduk,detjualjml,detjualharga,detjualsubtotal,(detjualsubtotal*detjualdispersen/100-detjualdisuang) AS diskon
            FROM penjualan_detail JOIN produk ON detjualkodebarcode = kodebarcode WHERE DATE_FORMAT(detjualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' AND detjualuserinput='$kasir' ORDER BY detjualtgl ASC");

            $data = [
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir,
                'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
                'kasir' => $datakasir['usernama'],
                'penjualan' => $this->db->query("SELECT jualuserinput, SUM(jualtotalkotor) AS totalkotor, SUM((jualtotalkotor*jualdispersen/100-jualdisuang)) AS diskon, SUM(jualtotalbersih) AS totalbersih FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' AND jualuserinput='$kasir'"),
                'detailpenjualan' => $penjualandetail,
                'toko' => $this->toko->datatoko()->row_array(),
                'namalaporan' => 'penjualan kasir'
            ];
        }

        $this->load->view('admin/laporan/penjualan/kasir/cetakdata', $data);
    }

    // ENd Penjualan kasir

    public function grafik_penjualanmember()
    {
        $tahun = $this->input->post('tahun', true);
        $query = $this->db->query("SELECT jualmemberkode AS kodemember,membernama AS namamember,SUM(jualpembulatan) AS totalbelanja FROM penjualan JOIN member ON jualmemberkode=memberkode
        WHERE DATE_FORMAT(jualtgl,'%Y') = '$tahun' GROUP BY jualmemberkode ORDER BY totalbelanja DESC LIMIT 10")->result();

        $data = [
            'grafik' => $query
        ];
        $msg = [
            'data' => $this->load->view('admin/laporan/grafikpenjualanmember/tampil', $data, true)
        ];
        echo json_encode($msg);
    }

    // Neraca Laba Rugi
    public function neraca_labarugi()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-file-archive"></i> Laporan Neraca Laba/Rugi',
            'isi' => $this->load->view('admin/laporan/penjualan/laba/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }
    // End Neraca Laba rugi

    // Grafik Penjualan Per-Bulan
    function grafik_penjualan()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-chart-bar"></i> Grafik Penjualan',
            'isi' => $this->load->view('admin/laporan/grafikpenjualan/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    // End Grafi Penjualan Per-Bulan
    // function tampil_grafik_penjualan_perbulan()
    // {
    //     $bulan = $this->input->post('bulan', true);
    //     $query = $this->db->query("SELECT DATE_FORMAT(jualtgl,'%d-%m-%Y') AS jualtgl,SUM(jualtotalbersih) AS total FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m') = '$bulan' GROUP BY DATE_FORMAT(jualtgl,'%Y-%m-%d')  ORDER BY jualtgl ASC")->result();

    //     $data = [
    //         'grafik' => json_encode($query)
    //     ];
    //     $msg = [
    //         'data' => $this->load->view('admin/laporan/grafikpenjualan/tampil', $data, true)
    //     ];
    //     echo json_encode($msg);
    // }

    function tampil_grafik_penjualan_perbulan()
    {
        $bulan = $this->input->post('bulan', true);
        $query = $this->db->query("SELECT DATE_FORMAT(jualtgl,'%d-%m-%Y') AS jualtgl,SUM(jualpembulatan) AS total FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m') = '$bulan' GROUP BY DATE_FORMAT(jualtgl,'%Y-%m-%d')  ORDER BY jualtgl ASC")->result();

        $data = [
            // 'grafik' => json_encode($query)
            'grafik' => $query
        ];
        $msg = [
            'data' => $this->load->view('admin/laporan/grafikpenjualan/tampil', $data, true)
        ];
        echo json_encode($msg);
    }
    function tampil_grafik_penjualan_pertahun()
    {
        $tahun = $this->input->post('tahun', true);
        $sql = "SELECT DATE_FORMAT(jualtgl,'%m') AS bulan,SUM(jualtotalbersih) AS total FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y') = '$tahun' GROUP BY DATE_FORMAT(jualtgl,'%m')";

        $query = $this->db->query($sql)->result();

        $data = [
            'grafik' => json_encode($query)
        ];
        $msg = [
            'data' => $this->load->view('admin/laporan/grafikpenjualan/tampil_pertahun', $data, true)
        ];
        echo json_encode($msg);
    }

    // Laporan Koreksi Stok
    function koreksi_stok()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Koreksi Stok',
            'isi' => $this->load->view('admin/laporan/koreksistok/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function cetak_koreksi_stok($tglawal, $tglakhir)
    {
        $query = $this->db->query("SELECT koreksi_stok.*,produk.`namaproduk`,pemasok.`nama` as namapemasok FROM koreksi_stok JOIN produk ON koreksikodebarcode=kodebarcode LEFT JOIN pemasok ON pemasok.`id`=koreksiidpemasok WHERE DATE_FORMAT(koreksitgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'");
        $data = [
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
            'tampildata' => $query,
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'koreksi stok'
        ];

        $this->load->view('admin/laporan/koreksistok/cetaklaporan', $data);
    }

    public function cetak_per_no_koreksi()
    {
        $nokoreksi = $this->input->post('nokoreksi', true);
        $pemasok = $this->input->post('pemasok', true);

        $this->form_validation->set_rules('nokoreksi', 'No.Koreksi', 'trim|required', [
            'required' => '%s tidak boleh kosong'
        ]);


        if ($this->form_validation->run() == TRUE) {
            $query = $this->db->get_where('koreksi_stok', ['koreksino' => $nokoreksi]);
            if ($query->num_rows() > 0) {
                $rq = $query->row_array();
                if ($rq['koreksiidpemasok'] == '' || $rq['koreksiidpemasok'] == NULL) {
                    $namapemasok = '-';
                } else {
                    $query_pemasok = $this->db->get_where('pemasok', ['id' => $rq['koreksiidpemasok']])->row_array();
                    $namapemasok = $query_pemasok['nama'];
                }

                $data = [
                    'nokoreksi' => $nokoreksi,
                    'tgl' => date('d-m-Y', strtotime($rq['koreksitgl'])),
                    'pemasok' => $namapemasok,
                    'toko' => $this->toko->datatoko()->row_array(),
                    'namalaporan' => "Laporan Koreksi Stok-Per.Nomor Koreksi",
                    'datakoreksi' => $this->db->query("SELECT koreksikodebarcode AS kode,namaproduk,koreksistoklalu AS stoklalu, koreksistoksekarang AS stoksekarang, koreksiselisih AS selisih, koreksialasan AS alasan,
                    koreksihargabeli AS hargabeli,(koreksihargabeli * koreksiselisih) AS subtotal FROM koreksi_stok JOIN produk ON koreksikodebarcode=kodebarcode WHERE koreksino='$nokoreksi'")
                ];
                $this->load->view('admin/laporan/koreksistok/cetaklaporan-pernokoreksi', $data);
            }
        } else {
            $pesan = [
                'pesan' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ' . validation_errors() . '
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>'
            ];
            $this->session->set_flashdata($pesan);
            redirect('laporan/koreksi-stok', 'refresh');
        }
    }

    function modalcarikoreksistok()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/laporan/koreksistok/modalcarikoreksi', '', true)
            ];
            echo json_encode($msg);
        }
    }

    function ambildatakoreksistok()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/Modelkoreksistok', 'koreksi');
            $list = $this->koreksi->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $aksi = "<button type=\"\" class=\"btn btn-sm btn-info\" onclick=\"pilih('" . $field->koreksino . "','" . $field->nama . "')\">
                Pilih
               </button>";

                $row[] = $no;
                $row[] = $field->koreksino;
                $row[] = date('d-m-Y', strtotime($field->koreksitgl));
                $row[] = ($field->nama == NULL || $field->nama == '') ? '-' : $field->nama;

                // Query Jumlah Item
                $query_detailkoreksi = $this->db->query("SELECT COUNT(koreksiid) AS jmlitem FROM koreksi_stok WHERE koreksino = '$field->koreksino'")->row_array();

                $row[] = "<span class=\"badge badge-info\">$query_detailkoreksi[jmlitem]</span>";
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

    // Laporan Diskon Member
    function tabungan_diskon_member()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Tabungan Diskon Member',
            'isi' => $this->load->view('admin/laporan/diskonmember/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function cetak_tabungan_diskon_member($bulan)
    {
        $ambil_datasettingdiskon = $this->db->get('member_setting_diskon')->row_array();
        $diskonsetting = $ambil_datasettingdiskon['diskon'];

        $query = $this->db->query("SELECT jualmemberkode,membernama,memberinstansi FROM penjualan JOIN member ON memberkode=jualmemberkode WHERE DATE_FORMAT(jualtgl,'%Y-%m')<='$bulan' GROUP BY memberkode");
        $data = [
            'bulan' => date('M Y', strtotime($bulan)),
            'tampildata' => $query,
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'tabungan diskon member',
            'bulanini' => $bulan,
            'diskonsetting' => $diskonsetting
        ];

        $this->load->view('admin/laporan/diskonmember/cetaklaporan', $data);
    }

    // Persediaan Produk
    function persediaan_produk()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Persediaan Stok Produk',
            'isi' => $this->load->view('admin/laporan/persediaanstok/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    // function sortirpersediaanstok()
    // {
    //     if ($this->input->is_ajax_request()) {
    // $tglawal = $this->input->post('tglawal', true);
    // $tglakhir = $this->input->post('tglakhir', true);

    //         $data = [
    //             'tglawal' => $tglawal,
    //             'tglakhir' => $tglakhir,
    //             'dataproduk' => $this->db->query("CALL persediaanstokproduk('$tglawal','$tglakhir')")
    //         ];
    //         $msg = [
    //             'data' => $this->load->view('admin/laporan/persediaanstok/tampildata', $data, true)
    //         ];

    //         echo json_encode($msg);
    //     }
    // }

    function tampilpersediaanstok()
    {
        if ($this->input->is_ajax_request()) {
            $tglawal = $this->input->post('tglawal', true);
            $tglakhir = $this->input->post('tglakhir', true);

            $data = [
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir
            ];
            $msg = [
                'data' => $this->load->view('admin/laporan/persediaanstok/tampildataproduk', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function ambildata_persediaanproduk()
    {
        if ($this->input->is_ajax_request()) {
            $tglawal = $this->input->post('tglawal', true);
            $tglakhir = $this->input->post('tglakhir', true);

            $this->load->model('admin/laporan/Modelpersediaanproduk', 'persediaanproduk');

            $list = $this->persediaanproduk->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $row[] = $field->kodebarcode;
                $row[] = $field->namaproduk;
                $row[] = number_format($field->stok_sekarang, 0, ",", ".");
                $row[] = number_format($field->hargamodal, 2, ",", ".");
                $row[] = number_format($field->saldo, 2, ",", ".");
                // Perhitungan stok keluar
                $query_penjualan_stok = $this->db->query("SELECT IFNULL(SUM((detjualjml * detjualsatqty)-detjualjmlreturn),0) AS jml_produk_penjualan FROM penjualan_detail JOIN penjualan ON jualfaktur=detjualfaktur WHERE detjualkodebarcode = '$field->kodebarcode' AND DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'")->row_array();

                $query_pemakaian_barang = $this->db->query("SELECT IFNULL(SUM(detpakaijml),0) AS jml_produk_pakai FROM pemakaian_detail JOIN pemakaian ON detpakaifaktur=pakaifaktur WHERE detpakaikodebarcode ='$field->kodebarcode' AND DATE_FORMAT(pakaitgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'")->row_array();

                $jmlStokKeluar = $query_penjualan_stok['jml_produk_penjualan'] + $query_pemakaian_barang['jml_produk_pakai'];
                // end perhitungan stok keluar

                // Perhitungan Stok Masuk
                $query_pembelian_stok = $this->db->query("SELECT IFNULL(SUM(detqtysat*detjml)-detjmlreturn,0) AS jml_pembelian FROM pembelian_detail JOIN pembelian ON detfaktur=nofaktur WHERE detkodebarcode='$field->kodebarcode' AND tglbeli BETWEEN '$tglawal' AND '$tglakhir'")->row_array();
                // End
                $row[] = number_format($query_pembelian_stok['jml_pembelian'], 0, ",", ".");
                $row[] = number_format($jmlStokKeluar, 0, ",", ".");
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->persediaanproduk->count_all(),
                "recordsFiltered" => $this->persediaanproduk->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function exportPersediaanStok()
    {
        $tglawal = $this->input->post('tglawal', true);
        $tglakhir = $this->input->post('tglakhir', true);

        $dataproduk = $this->db->query("SELECT kodebarcode,namaproduk,stok_tersedia AS stok_sekarang,harga_beli_eceran AS hargamodal,(harga_beli_eceran * stok_tersedia) AS saldo FROM produk")->result();

        $spreadsheet = new Spreadsheet;

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Kode Barcode')
            ->setCellValue('C1', 'Nama Produk')
            ->setCellValue('D1', 'Stok Sekarang')
            ->setCellValue('E1', 'Harga Modal')
            ->setCellValue('F1', 'Saldo')
            ->setCellValue('G1', 'Stok Masuk')
            ->setCellValue('H1', 'Stok Keluar');

        $kolom = 2;
        $nomor = 1;
        foreach ($dataproduk as $d) {

            // Perhitungan stok keluar
            $query_penjualan_stok = $this->db->query("SELECT IFNULL(SUM((detjualjml * detjualsatqty)-detjualjmlreturn),0) AS jml_produk_penjualan FROM penjualan_detail JOIN penjualan ON jualfaktur=detjualfaktur WHERE detjualkodebarcode = '$d->kodebarcode' AND DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'")->row_array();

            $query_pemakaian_barang = $this->db->query("SELECT IFNULL(SUM(detpakaijml),0) AS jml_produk_pakai FROM pemakaian_detail JOIN pemakaian ON detpakaifaktur=pakaifaktur WHERE detpakaikodebarcode ='$d->kodebarcode' AND DATE_FORMAT(pakaitgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'")->row_array();

            $jmlStokKeluar = $query_penjualan_stok['jml_produk_penjualan'] + $query_pemakaian_barang['jml_produk_pakai'];
            // $jmlStokKeluar = $query_penjualan_stok['jml_produk_penjualan'];
            // end perhitungan stok keluar

            // Perhitungan Stok Masuk
            $query_pembelian_stok = $this->db->query("SELECT IFNULL(SUM(detqtysat*detjml)-detjmlreturn,0) AS jml_pembelian FROM pembelian_detail JOIN pembelian ON detfaktur=nofaktur WHERE detkodebarcode='$d->kodebarcode' AND tglbeli BETWEEN '$tglawal' AND '$tglakhir'")->row_array();
            // End

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $d->kodebarcode)
                ->setCellValue('C' . $kolom, $d->namaproduk)
                ->setCellValue('D' . $kolom, $d->stok_sekarang)
                ->setCellValue('E' . $kolom, $d->hargamodal)
                ->setCellValue('F' . $kolom, $d->saldo)
                ->setCellValue('G' . $kolom, number_format($query_pembelian_stok['jml_pembelian'], 0, ",", "."))
                ->setCellValue('H' . $kolom, number_format($jmlStokKeluar, 0, ",", "."));

            $kolom++;
            $nomor++;
        }
        $tglhariini = date('dMY');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="persediaan-stok-04012021.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    // Laporan sisa pembulatan
    public function sisa_pembulatan()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Sisa Pembulatan Penjualan',
            'isi' => $this->load->view('admin/laporan/sisapembulatan/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function cetak_sisapembulatan()
    {
        $tglawal = $this->input->post('tglawal', true);
        $tglakhir = $this->input->post('tglakhir', true);

        $query_sisapembulatan = $this->db->query("SELECT jualfaktur,DATE_FORMAT(jualtgl,'%d-%m-%Y') AS jualtgl,jualsisapembulatan FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' AND jualsisapembulatan != 0");

        $data = [
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'datapembulatan' => $query_sisapembulatan,
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'laporan sisa pembulatan',
            'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir))
        ];
        $this->load->view('admin/laporan/sisapembulatan/cetak', $data);
    }

    // Laporan Arus Kas

    public function arus_kas()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Arus Kas',
            'isi' => $this->load->view('admin/laporan/aruskas/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function cetak_aruskas_pertanggal()
    {
        $tglawal = $this->input->post('tglawal', true);
        $tglakhir = $this->input->post('tglakhir', true);

        $query_kaskecil_input_awal = $this->db->query("SELECT IFNULL(jmlsetdef,0) AS jumlahkas FROM neraca_akun WHERE tglsetdef <= '$tglawal' AND noakun='1-110'")->row_array();
        $saldo_kas_input_awal = $query_kaskecil_input_awal['jumlahkas'];

        $query_kaskecil_awal = $this->db->query("SELECT 
        CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
        CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
        FROM `neraca_transaksi` a WHERE transnoakun='1-110' AND LEFT(transno,2)='N-' ORDER BY transtgl ASC");
        if ($query_kaskecil_awal->num_rows() > 0) {
            $saldo_kas = 0;
            foreach ($query_kaskecil_awal->result_array() as $kas) :
                $saldo_kas = ($saldo_kas + $kas['masuk']) - $kas['keluar'];
            endforeach;
        } else {
            $saldo_kas = 0;
        }


        $query_penjualan = $this->db->query("SELECT SUM(jualpembulatan) AS pembulatan FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' AND (jualstatusbayar = 'T' AND jualstatuslunas=1)")->row_array();

        // Pengeluaran
        $query_pembelian = $this->db->query("SELECT SUM(totalbersih) AS totalpembelian FROM pembelian WHERE tglbeli BETWEEN '$tglawal' AND '$tglakhir' AND (jenisbayar='T' OR statusbayar='1')")->row_array();
        // end

        // Pengeluaran Biaya
        $query_pengeluaranbiaya = $this->db->query("SELECT 
        CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
        CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
        FROM `neraca_transaksi` a WHERE transtgl BETWEEN '$tglawal' AND '$tglakhir' AND LEFT(transnoakun,1)='6' ORDER BY transtgl ASC");

        if ($query_pengeluaranbiaya->num_rows() > 0) {
            $pengeluaran = 0;
            foreach ($query_pengeluaranbiaya->result_array() as $biaya) :
                $pengeluaran = ($pengeluaran + $biaya['masuk']) - $biaya['keluar'];
            endforeach;
        } else {
            $pengeluaran = 0;
        }

        $totalpengeluaran = $query_pembelian['totalpembelian'] + $pengeluaran;
        // if ($query_kaskecil_awal->num_rows() > 0) {
        //     $saldo_kas = 0;
        //     foreach ($query_kaskecil_awal->result_array() as $kas) :
        //         $saldo_kas = ($saldo_kas + $kas['masuk']) - $kas['keluar'];
        //     endforeach;
        // } else {
        //     $saldo_kas = 0;
        // }

        $data = [
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'saldokas' => $saldo_kas + $saldo_kas_input_awal,
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'laporan Arus Kas',
            'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
            'pendapatan' => $query_penjualan['pembulatan'],
            'pengeluaran' => $totalpengeluaran
        ];
        $this->load->view('admin/laporan/aruskas/cetakaruskas', $data);
    }

    // Laporan Produk kadaluarsa
    public function produk_kadaluarsa()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Produk Kadaluarsa',
            'isi' => $this->load->view('admin/laporan/produk-kadaluarsa/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function cetak_laporan_produk_kadaluarsa()
    {
        $pilih = $this->input->post('pilih', true);
        $ed = $this->input->post('ed', true);

        if ($ed == '1') {
            $querylaporan = $this->db->query("SELECT produk.`id` AS idproduk,produk.`kodebarcode`,namaproduk,satuan.`satnama`,harga_beli_eceran AS hargabeli,harga_jual_eceran AS hargajual,stok_tersedia AS stok,tglkadaluarsa,produk_tglkadaluarsa.`jml` AS jmlkadaluarsa FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` JOIN produk_tglkadaluarsa ON produk.`kodebarcode`=produk_tglkadaluarsa.`kodebarcode` WHERE TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) > 3 AND TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) <= 6 ORDER BY tglkadaluarsa ASC");

            $namalaporan = "Laporan Produk Kadaluarsa < 6 Bulan Dari Tgl.Expired";
        }
        if ($ed == '2') {
            $querylaporan = $this->db->query("SELECT produk.`id` AS idproduk,produk.`kodebarcode`,namaproduk,satuan.`satnama`,harga_beli_eceran AS hargabeli,harga_jual_eceran AS hargajual,stok_tersedia AS stok,tglkadaluarsa,produk_tglkadaluarsa.`jml` AS jmlkadaluarsa FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` JOIN produk_tglkadaluarsa ON produk.`kodebarcode`=produk_tglkadaluarsa.`kodebarcode` WHERE TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) > 0 AND TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) <= 3 ORDER BY tglkadaluarsa ASC");
            $namalaporan = "Laporan Produk Kadaluarsa < 3 Bulan Dari Tgl.Expired";
        }
        if ($ed == '3') {
            $querylaporan = $this->db->query("SELECT produk.`id` AS idproduk,produk.`kodebarcode`,namaproduk,satuan.`satnama`,harga_beli_eceran AS hargabeli,harga_jual_eceran AS hargajual,stok_tersedia AS stok,tglkadaluarsa,produk_tglkadaluarsa.`jml` AS jmlkadaluarsa FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` JOIN produk_tglkadaluarsa ON produk.`kodebarcode`=produk_tglkadaluarsa.`kodebarcode` WHERE TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) <= 0 ORDER BY tglkadaluarsa ASC");
            $namalaporan = "Laporan Produk Yang Telah Kadaluarsa";
        }

        if ($pilih == 'nilai') {
            $data = [
                'toko' => $this->toko->datatoko()->row_array(),
                'namalaporan' => $namalaporan,
                'tampildata' => $querylaporan
            ];
            $this->load->view('admin/laporan/produk-kadaluarsa/cetaknilaiproduk', $data);
        } else {
            $data = [
                'toko' => $this->toko->datatoko()->row_array(),
                'namalaporan' => $namalaporan,
                'tampildata' => $querylaporan
            ];
            $this->load->view('admin/laporan/produk-kadaluarsa/cetakjumlahproduk', $data);
        }
    }

    // Laporan Stok Opname
    public function stok_opname()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Stok Opname',
            'isi' => $this->load->view('admin/laporan/stok-opname/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function cetak_laporan_stok_opname()
    {
        $tgl = $this->input->post('tgl', true);
        $query = $this->db->query("SELECT kodebarcode,namaproduk,stok_tersedia AS stok FROM produk");

        $data = [
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'Laporan Stok',
            'tampildata' => $query
        ];
        $this->load->view('admin/laporan/stok-opname/cetaklaporanstok', $data);
    }

    // Produk Yang Laku
    public function produk_laku()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Grafik 10 Produk Yang Laku',
            'isi' => $this->load->view('admin/laporan/produk-laku/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function grafikproduklaku()
    {
        if ($this->input->is_ajax_request()) {
            $bulan = $this->input->post('bulan', true);
            $query = $this->db->query("SELECT detjualkodebarcode,namaproduk,SUM(detjualsatqty * (detjualjml - detjualjmlreturn)) AS jml FROM penjualan_detail JOIN produk ON detjualkodebarcode=kodebarcode WHERE DATE_FORMAT(detjualtgl,'%Y-%m') = '$bulan' GROUP BY detjualkodebarcode ORDER BY jml DESC LIMIT 10")->result();

            $data = [
                'grafik' => $query
            ];
            $msg = [
                'data' => $this->load->view('admin/laporan/produk-laku/tampil', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function laba_rugi()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Laba Rugi',
            'isi' => $this->load->view('admin/laporan/labarugi/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function cetak_laba_rugi()
    {
        $tglawal = $this->input->post('tglawal', true);
        $tglakhir = $this->input->post('tglakhir', true);
        $cetakdetail = $this->input->post('cetakdetail', true);
        $cetakpertanggal = $this->input->post('cetakpertanggal', true);
        $cetakdetailitem = $this->input->post('cetakdetailitem', true);

        if (isset($cetakdetail)) {
            $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
            $diskon_setting = $ambil_datamembersettingdiskon['diskon'];

            $querypenjualan = $this->db->query("SELECT DATE_FORMAT(jualtgl,'%d-%m-%Y') AS tanggal,jualfaktur AS faktur, 'Penjualan Barang' AS ket,
            jualtotalbersih AS hargapenjualan,jualmemberkode,IF(jualmemberkode != '',ROUND($diskon_setting*jualtotalbersih/100,2),0) AS diskon FROM penjualan 
            WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' ORDER BY jualtgl ASC");


            $data = [
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir,
                'toko' => $this->toko->datatoko()->row_array(),
                'namalaporan' => 'Laporan Laba Rugi',
                'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
                'datapenjualan' => $querypenjualan,
            ];
            $this->load->view('admin/laporan/labarugi/cetakdetail', $data);
        }

        if (isset($cetakpertanggal)) {
            $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
            $diskon_setting = $ambil_datamembersettingdiskon['diskon'];
            $querypenjualan = $this->db->query("SELECT SUM(jualdiskon) as jualdiskon,jualtgl,DATE_FORMAT(jualtgl,'%d-%m-%Y') AS tanggal, 'Penjualan Barang' AS ket,IFNULL(SUM(jualtotalbersih),0) AS hargapenjualan FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir' GROUP BY DATE_FORMAT(jualtgl,'%Y-%m-%d') ORDER BY DATE_FORMAT(jualtgl,'%Y-%m-%d') ASC");


            $data = [
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir,
                'toko' => $this->toko->datatoko()->row_array(),
                'namalaporan' => 'Laporan Laba Rugi',
                'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
                'datapenjualan' => $querypenjualan,
                'diskon' => $diskon_setting
            ];
            $this->load->view('admin/laporan/labarugi/cetakpertanggal', $data);
        }
    }

    public function hutang()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Hutang Supplier',
            'isi' => $this->load->view('admin/laporan/hutang/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function cetak_hutang_supplier()
    {
        $tglawal = $this->input->post('tglawal', true);
        $tglakhir = $this->input->post('tglakhir', true);

        $query_cetak = $this->db->query("SELECT nofaktur,idpemasok,pemasok.`nama`,SUM(totalbersih) AS totalhutang,(SELECT IFNULL(SUM(totalbersih),0) FROM pembelian WHERE tglbeli BETWEEN '$tglawal' AND '$tglakhir' AND pembelian.`statusbayar`=1 AND idpemasok=id) AS totalbayar,
        (SELECT IFNULL(SUM(totalbersih),0) FROM pembelian WHERE tglbeli BETWEEN '$tglawal' AND '$tglakhir' AND pembelian.`statusbayar`=0 AND idpemasok=id) AS sisahutang
        FROM pembelian JOIN pemasok ON pemasok.`id`=idpemasok 
        WHERE jenisbayar='K' AND tglbeli BETWEEN '$tglawal' AND '$tglakhir'
        GROUP BY idpemasok ORDER BY pemasok.`nama` ASC");

        $data = [
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'Laporan Hutang Supplier',
            'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
            'datahutang' => $query_cetak
        ];
        $this->load->view('admin/laporan/hutang/cetaksemuasupplier', $data);
    }

    public function piutang()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Piutang Pelanggan',
            'isi' => $this->load->view('admin/laporan/piutang/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function piutang_carimember()
    {
        if ($this->input->is_ajax_request()) {
            $this->db->select('jualmemberkode,membernama,memberalamat')
                ->from('penjualan')
                ->join('member', 'jualmemberkode=memberkode', 'left')
                ->where('jualstatusbayar', 'K')
                ->group_by('jualmemberkode');
            $query = $this->db->get();

            $data = [
                'datamember' => $query
            ];
            $msg = [
                'data' => $this->load->view('admin/laporan/piutang/modalcarimember', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    public function cetak_piutang_pelanggan()
    {
        $tanggal = $this->input->post('tanggal', true);

        $query_cetak = $this->db->query("SELECT memberkode,membernama,memberalamat,
        SUM(jualpembulatan) AS totalpiutang,
        (SELECT IFNULL(SUM(jualpembulatan),0) FROM penjualan WHERE jualstatusbayar='K' AND jualmemberkode=memberkode AND DATE_FORMAT(jualtgl,'%Y-%m-%d') <= '$tanggal' AND jualstatuslunas=1) AS totalbayar
        FROM penjualan JOIN member ON memberkode=jualmemberkode WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') <= '$tanggal' AND jualstatusbayar='K' GROUP BY jualmemberkode");

        $data = [
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'Laporan Piutang Pelanggan',
            'datapiutang' => $query_cetak,
            'tanggal' => date('d-m-Y', strtotime($tanggal))
        ];
        $this->load->view('admin/laporan/piutang/cetak', $data);
    }

    function cetak_piutang_per_pelanggan()
    {
        $kodemember = htmlspecialchars($this->input->post('idmember', true));
        $datamember = $this->db->get_where('member', ['memberkode' => $kodemember])->row_array();

        $query_fakturpiutang = $this->db->query("SELECT jualfaktur AS faktur,DATE_FORMAT(jualtgl,'%d-%m-%Y') AS tgl,DATE_FORMAT(jualtgljatuhtempo,'%d-%m-%Y') AS tgltempo,
        CASE jualstatuslunas WHEN '0' THEN  jualpembulatan ELSE jualpembulatan END AS jumlahpiutang
        FROM penjualan WHERE jualmemberkode ='$kodemember' AND jualstatusbayar='K' AND jualstatuslunas = '0' ORDER BY jualtgl ASC");
        $data = [
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'Laporan Piutang Pelanggan',
            'kodemember' => $kodemember,
            'namamember' => $datamember['membernama'],
            'alamat' => $datamember['memberalamat'],
            'fakturpiutang' => $query_fakturpiutang,
        ];
        $this->load->view('admin/laporan/piutang/cetakperpelanggan', $data);
    }

    // Laporan Pembelian
    public function transaksi_pembelian()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Transaksi Pembelian',
            'isi' => $this->load->view('admin/laporan/pembelian/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function pembelian_cetak_per_tanggal()
    {
        $tglawal = $this->input->post('tglawal', true);
        $tglakhir = $this->input->post('tglakhir', true);
        $tombolcetakfaktur = $this->input->post('cetakfaktur', true);
        $tombolcetakdetail = $this->input->post('cetakdetail', true);
        if (isset($tombolcetakfaktur)) {
            $query_cetak = $this->db->query("SELECT nofaktur,tglbeli,pemasok.`nama` AS namapemasok,jenisbayar,totalbersih,statusbayar,tglpembayarankredit FROM pembelian LEFT JOIN pemasok ON pemasok.`id`=pembelian.`idpemasok` WHERE tglbeli BETWEEN '$tglawal' AND '$tglakhir'");

            $data = [
                'toko' => $this->toko->datatoko()->row_array(),
                'namalaporan' => 'Laporan Pembelian',
                'datapembelian' => $query_cetak,
                'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir))
            ];
            $this->load->view('admin/laporan/pembelian/cetaklappembelian', $data);
        }

        if (isset($tombolcetakdetail)) {
            $query_cetak = $this->db->query("SELECT nofaktur,tglbeli,pemasok.`nama` AS namapemasok,jenisbayar,totalbersih,statusbayar,tglpembayarankredit FROM pembelian LEFT JOIN pemasok ON pemasok.`id`=pembelian.`idpemasok` WHERE tglbeli BETWEEN '$tglawal' AND '$tglakhir'");
            $data = [
                'toko' => $this->toko->datatoko()->row_array(),
                'namalaporan' => 'Laporan Pembelian',
                'datapembelian' => $query_cetak,
                'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir))
            ];
            $this->load->view('admin/laporan/pembelian/cetaklappembeliandetail', $data);
        }
    }

    public function pembelian_caripemasok()
    {
        if ($this->input->is_ajax_request()) {
            $data = [
                'datapemasok' => $this->db->query("SELECT pembelian.`idpemasok` AS idpemasok, pemasok.`nama` AS namapemasok FROM pembelian LEFT JOIN pemasok ON idpemasok=id GROUP BY idpemasok ORDER BY pemasok.`nama` ASC")
            ];
            $msg = [
                'data' => $this->load->view('admin/laporan/pembelian/modalcaripemasok', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    public function pembelian_cetak_per_supplier()
    {
        $tglawal = $this->input->post('tglawal', true);
        $tglakhir = $this->input->post('tglakhir', true);
        $idpemasok = $this->input->post('idpemasok', true);
        $namapemasok = $this->input->post('namapemasok', true);

        $query_cetak = $this->db->query("SELECT nofaktur,tglbeli,IF(jenisbayar='T','Tunai','Kredit') AS jenis,totalbersih,statusbayar,tglpembayarankredit,jenisbayar FROM pembelian WHERE idpemasok='$idpemasok' AND tglbeli BETWEEN '$tglawal' AND '$tglakhir' ORDER BY tglbeli ASC");

        $ambildatapemasok = $this->db->get_where('pemasok', ['id' => $idpemasok])->row_array();

        $data = [
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'Laporan Pembelian',
            'datapembelian' => $query_cetak,
            'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
            'namapemasok' => $namapemasok,
            'alamatpemasok' => $ambildatapemasok['alamat']
        ];
        $this->load->view('admin/laporan/pembelian/cetaklappembelian_persupplier', $data);
    }
    // Laporan Pembelian

    // Laporan KSF Permintaan dari Seseorang
    public function ksf()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan KSF',
            'isi' => $this->load->view('admin/laporan/ksf/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function cetak_ksf()
    {
        $tglawal = $this->input->post('tglawal', true);
        $tglakhir = $this->input->post('tglakhir', true);

        $query_cetak = $this->db->query("SELECT jualtgl,DATE_FORMAT(jualtgl,'%d-%m-%Y') AS tanggal,IFNULL(SUM(jualtotalbersih),0) AS sales,COUNT(jualfaktur) AS jmltransaksi FROM penjualan WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') 
        BETWEEN '$tglawal' AND '$tglakhir' GROUP BY DATE_FORMAT(jualtgl,'%Y-%m-%d')");


        $data = [
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'Laporan KSF',
            'datapenjualan' => $query_cetak,
            'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
        ];
        $this->load->view('admin/laporan/ksf/cetaklaporan', $data);
    }

    // Pemakaian Barang
    public function pemakaian_barang()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Pemakaian Barang',
            'isi' => $this->load->view('admin/laporan/pemakaian/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function cetak_pemakaian_barang()
    {
        $tglawal = $this->input->post('tglawal', true);
        $tglakhir = $this->input->post('tglakhir', true);

        $query_cetak = $this->db->query("SELECT pakaifaktur AS faktur,pakaitgl AS tgl,pakaibiayanoakun,namaakun,pakaitotal FROM pemakaian JOIN neraca_akun ON neraca_akun.`noakun`=pakaibiayanoakun WHERE pakaitgl BETWEEN '$tglawal' AND '$tglakhir' ORDER BY pakaitgl ASC");


        $data = [
            'toko' => $this->toko->datatoko()->row_array(),
            'namalaporan' => 'Laporan Pemakaian Barang',
            'datapemakaian' => $query_cetak,
            'periode' => date('d-m-Y', strtotime($tglawal)) . ' s.d ' . date('d-m-Y', strtotime($tglakhir)),
        ];
        $this->load->view('admin/laporan/pemakaian/cetaklaporan', $data);
    }
    // End Pemakaian Barang

    public function perjalanan_stok_produk()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Laporan Perjalanan Stok Produk',
            'isi' => $this->load->view('admin/laporan/perjalananstok/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function tampilPerjalananStok()
    {
        if ($this->input->is_ajax_request()) {
            $tglawal = $this->input->post('tglawal', true);
            $tglakhir = $this->input->post('tglakhir', true);

            $data = [
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir
            ];
            $msg = [
                'data' => $this->load->view('admin/laporan/perjalananstok/tampildataproduk', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    public function ambildata_perjalananstok()
    {
        if ($this->input->is_ajax_request()) {
            $tglawal = $this->input->post('tglawal', true);
            $tglakhir = $this->input->post('tglakhir', true);

            $this->load->model('admin/laporan/Modelpersediaanproduk', 'persediaanproduk');

            $list = $this->persediaanproduk->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();



                // $row[] = number_format($field->hargamodal, 2, ",", ".");
                // $row[] = number_format($field->saldo, 2, ",", ".");
                // Perhitungan stok keluar
                $query_penjualan_stok = $this->db->query("SELECT IFNULL(SUM((detjualjml * detjualsatqty)-detjualjmlreturn),0) AS jml_produk_penjualan FROM penjualan_detail JOIN penjualan ON jualfaktur=detjualfaktur WHERE detjualkodebarcode = '$field->kodebarcode' AND DATE_FORMAT(jualtgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'")->row_array();

                $query_pemakaian_barang = $this->db->query("SELECT IFNULL(SUM(detpakaijml),0) AS jml_produk_pakai FROM pemakaian_detail JOIN pemakaian ON detpakaifaktur=pakaifaktur WHERE detpakaikodebarcode ='$field->kodebarcode' AND DATE_FORMAT(pakaitgl,'%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'")->row_array();

                // $jmlStokKeluar = $query_penjualan_stok['jml_produk_penjualan'] + $query_pemakaian_barang['jml_produk_pakai'];
                // end perhitungan stok keluar

                // Perhitungan Stok Masuk
                $query_pembelian_stok = $this->db->query("SELECT IFNULL(SUM(detqtysat*detjml),0) AS jml_pembelian FROM pembelian_detail JOIN pembelian ON detfaktur=nofaktur WHERE detkodebarcode='$field->kodebarcode' AND tglbeli BETWEEN '$tglawal' AND '$tglakhir'")->row_array();
                // End

                // Perhitungan stok return
                $query_return_stok = $this->db->query("SELECT IFNULL(SUM(blreturndetqtysat*blreturnjml),0) AS jml_return FROM pembelian_return WHERE blreturnkodebarcode='$field->kodebarcode' AND blreturntgl BETWEEN '$tglawal' AND '$tglakhir'")->row_array();

                // Perhitungan stok koreksi
                $query_koreksi_stok = $this->db->query("SELECT IFNULL(SUM(koreksiselisih),0) AS jml_koreksi FROM koreksi_stok WHERE koreksikodebarcode = '$field->kodebarcode' AND koreksitgl BETWEEN '$tglawal' AND '$tglakhir'")->row_array();

                $row[] = $field->kodebarcode;
                $row[] = $field->namaproduk;
                // Hitung Stok Awal
                $stokAwal = ($field->stok_sekarang) - $query_koreksi_stok['jml_koreksi'] + $query_pemakaian_barang['jml_produk_pakai'] + $query_penjualan_stok['jml_produk_penjualan'] + $query_return_stok['jml_return'] - $query_pembelian_stok['jml_pembelian'];
                $row[] = number_format($stokAwal, 0, ",", ".");
                $row[] = number_format($query_pembelian_stok['jml_pembelian'], 0, ",", ".");
                $row[] = number_format($query_return_stok['jml_return'], 0, ",", ".");
                $row[] = number_format($query_penjualan_stok['jml_produk_penjualan'], 0, ",", ".");
                $row[] = number_format($query_pemakaian_barang['jml_produk_pakai'], 0, ",", ".");
                $row[] = number_format($query_koreksi_stok['jml_koreksi'], 0, ",", ".");
                $row[] = number_format($field->stok_sekarang, 0, ",", ".");
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->persediaanproduk->count_all(),
                "recordsFiltered" => $this->persediaanproduk->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }
}