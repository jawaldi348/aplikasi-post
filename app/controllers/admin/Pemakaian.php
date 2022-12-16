<?php
class Pemakaian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->aksesgrup = $this->session->userdata('idgrup');
        if ($this->session->userdata('masuk') == true && ($this->aksesgrup == '1' || $this->aksesgrup == '2')) {
            $this->load->library(['form_validation']);
            $this->load->model('Modelkasir', 'kasir');
            $this->load->model('admin/Modeltransaksineraca', 'neraca');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-list-alt"></i> Data Pemakaian Barang',
            'isi' => $this->load->view('admin/pemakaian/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function ambildatapemakaian()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->load->model('admin/Modelpemakaian', 'pemakaian');
            $list = $this->pemakaian->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $aksi = "<div class=\"btn-group mb-2 dropright\">
                        <button type=\"button\" class=\"btn btn-info btn-sm waves-effect waves-light dropdown-toggle\" data-toggle=\"dropdown\"
                            aria-haspopup=\"true\" aria-expanded=\"false\">
                            Aksi
                        </button>
                        <div class=\"dropdown-menu\" x-placement=\"left-start\"
                            style=\"position: absolute; transform: translate3d(-2px, 0px, 0px); top: 0px; left: 0px; will-change: transform;\">
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"hapus('" . $field->pakaifaktur . "')\">
                                <i class=\"fa fa-fw fa-trash-alt\"></i> Hapus
                            </a>
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"edit('" . sha1($field->pakaifaktur) . "')\">
                                <i class=\"fa fa-fw fa-tag\"></i> Edit
                            </a>
                        </div>
                        </div>";

                $row[] = $no;
                $row[] = $field->pakaifaktur;
                $row[] = date('d-m-Y', strtotime($field->pakaitgl));
                $row[] = $field->pakaibiayanoakun . '(' . $field->namaakun . ')';
                $nofaktur = $field->pakaifaktur;
                $query_detail = $this->db->query("SELECT COUNT(detpakaiid) AS jmlitem FROM pemakaian_detail WHERE detpakaifaktur='$nofaktur'")->row_array();
                $jumlahitem = $query_detail['jmlitem'];
                $row[] = "<span style=\"cursor:pointer;\" class=\"badge badge-info\" onclick=\"item('" . $field->pakaifaktur . "')\">$jumlahitem</span>";
                $row[] = number_format($field->pakaitotal, 2, ",", ".");
                $row[] = $aksi;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->pemakaian->count_all(),
                "recordsFiltered" => $this->pemakaian->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    function buatnomor()
    {
        if ($this->input->is_ajax_request()) {
            $tglhariini = $this->input->post('tgl', true);
            $query = $this->db->query("SELECT MAX(pakaifaktur) AS faktur FROM pemakaian WHERE DATE_FORMAT(pakaitgl,'%Y-%m-%d') = '$tglhariini'");
            $hasil = $query->row_array();
            $data  = $hasil['faktur'];


            $lastNoUrut = substr($data, -4);

            // nomor urut ditambah 1
            $nextNoUrut = $lastNoUrut + 1;

            // membuat format nomor transaksi berikutnya
            $nextNoTransaksi = 'PK-' . date('dmy', strtotime($tglhariini)) . sprintf('%04s', $nextNoUrut);
            $msg = [
                'faktur' => $nextNoTransaksi
            ];
            echo json_encode($msg);
        }
    }

    function buatnomorpemakaian()
    {
        $tglhariini = date('Y-m-d');
        $query = $this->db->query("SELECT MAX(pakaifaktur) AS faktur FROM pemakaian WHERE DATE_FORMAT(pakaitgl,'%Y-%m-%d') = '$tglhariini'");
        $hasil = $query->row_array();
        $data  = $hasil['faktur'];


        $lastNoUrut = substr($data, -4);

        // nomor urut ditambah 1
        $nextNoUrut = $lastNoUrut + 1;

        // membuat format nomor transaksi berikutnya
        $nextNoTransaksi = 'PK-' . date('dmy', strtotime($tglhariini)) . sprintf('%04s', $nextNoUrut);
        return $nextNoTransaksi;
    }

    public function input()
    {
        $data = [
            'dataakunbiaya' => $this->db->query("SELECT noakun, namaakun FROM neraca_akun WHERE LEFT(noakun,2)='6-' AND kat=1"),
            'faktur' => $this->buatnomorpemakaian()
        ];
        $view = [
            'isi' => $this->load->view('admin/pemakaian/input', $data, true)

        ];
        $this->parser->parse('layoutkasir/main', $view);
    }

    function forminput()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('nofaktur');
            $data = [
                'datatemp' => $this->db->query("SELECT detpakaiid as id,detpakaikodebarcode AS kode,namaproduk,detpakaijml AS jml,detpakaihargabeli AS hargabeli,detpakaisubtotal AS subtotal
                FROM temp_pemakaian JOIN produk ON detpakaikodebarcode=kodebarcode WHERE detpakaifaktur ='$faktur'"),
                'nofaktur' => $faktur
            ];
            $msg = [
                'data' => $this->load->view('admin/pemakaian/forminputitem', $data, true)
            ];
            echo json_encode($msg);
        }
    }
    function formedit()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('nofaktur');
            $data = [
                'datatemp' => $this->db->query("SELECT detpakaiid as id,detpakaikodebarcode AS kode,namaproduk,detpakaijml AS jml,detpakaihargabeli AS hargabeli,detpakaisubtotal AS subtotal
                FROM pemakaian_detail JOIN produk ON detpakaikodebarcode=kodebarcode WHERE detpakaifaktur ='$faktur'"),
                'nofaktur' => $faktur
            ];
            $msg = [
                'data' => $this->load->view('admin/pemakaian/formedititem', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function cariproduk()
    {
        if ($this->input->is_ajax_request()) {
            $data = [
                'aksi' => $this->input->post('aksi', true)
            ];
            $msg = [
                'data' => $this->load->view('admin/pemakaian/modalcariproduk', $data, true)
            ];
            echo json_encode($msg);
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
                $row[] = number_format($field->harga_beli_eceran, 2, ".", ",");
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

    function simpantemp()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('Modelkasir', 'kasir');

            $kodebarcode = $this->input->post('kodebarcode', true);
            $namaproduk = $this->input->post('namaproduk', true);
            $faktur = $this->input->post('faktur', true);
            $jml = $this->input->post('jml', true);
            $aksi = $this->input->post('aksi', true);

            $cekproduk = $this->kasir->cekproduk($kodebarcode, $namaproduk);

            if ($cekproduk->num_rows() > 0) {
                if ($cekproduk->num_rows() === 1) {
                    $row = $cekproduk->row_array();
                    $kodeproduk = $row['kodebarcode'];
                    $hargabeli = $row['harga_beli_eceran'];

                    // Cek data Temp
                    $query_cektemppemakaian = $this->db->get_where('temp_pemakaian', ['detpakaifaktur' => $faktur, 'detpakaikodebarcode' => $kodeproduk]);

                    if ($query_cektemppemakaian->num_rows() > 0) {
                        $row_temp = $query_cektemppemakaian->row_array();
                        $idtemp = $row_temp['detpakaiid'];
                        $jmllama = $row_temp['detpakaijml'];

                        $jmlbaru = $jml + $jmllama;

                        $subtotalbaru = $jmlbaru * $hargabeli;

                        $this->db->where('detpakaiid', $idtemp);
                        $this->db->update('temp_pemakaian', [
                            'detpakaijml' => $jmlbaru,
                            'detpakaisubtotal' => $subtotalbaru
                        ]);
                    } else {
                        // simpan ke temp pemakaian
                        $this->db->insert('temp_pemakaian', [
                            'detpakaifaktur' => $faktur,
                            'detpakaikodebarcode' => $kodeproduk,
                            'detpakaijml' => $jml,
                            'detpakaihargabeli' => $hargabeli,
                            'detpakaisubtotal' => $hargabeli * $jml,
                            'detpakaiuserinput' => $this->session->userdata('username')
                        ]);
                    }
                    $msg = [
                        'sukses' => 'Berhasil'
                    ];
                } else {
                    $data = [
                        'tampildata' => $cekproduk,
                        'keyword' => $kodebarcode,
                        'aksi' => $aksi
                    ];
                    $msg = ['banyakdata' => $this->load->view('admin/pemakaian/modaldatacariproduk', $data, true)];
                }
            } else {
                $msg = [
                    'error' => 'Kode produk tidak ditemukan'
                ];
            }
            echo json_encode($msg);
        }
    }

    function bataltransaksi()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);

            $cektempfaktur = $this->db->get_where('temp_pemakaian', ['detpakaifaktur' => $faktur]);

            if ($cektempfaktur->num_rows() > 0) {
                $this->db->delete('temp_pemakaian', ['detpakaifaktur' => $faktur]);
                $msg = ['sukses' => 'Berhasil di batalkan'];
            } else {
                $msg = ['error' => 'Maaf tidak ada data yg dihapus !'];
            }
            echo json_encode($msg);
        }
    }

    function simpantransaki()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $tgl = $this->input->post('tgl', true);
            $noakun = $this->input->post('noakun', true);
            $total = $this->input->post('total', true);


            $cektempfaktur = $this->db->get_where('temp_pemakaian', ['detpakaifaktur' => $faktur]);
            if ($cektempfaktur->num_rows() > 0) {
                if (strlen($noakun) == 0) {
                    $msg = [
                        'error' => 'Silahkan Pilih Akun Biaya'
                    ];
                } else {
                    // Simpan Pemakaian
                    $this->db->insert('pemakaian', [
                        'pakaifaktur' => $faktur,
                        'pakaitgl' => $tgl,
                        'pakaiuserinput' => $this->session->userdata('username'),
                        'pakaibiayanoakun' => $noakun,
                        'pakaitotal' => $total
                    ]);

                    // Simpan Pemakaian Detail
                    $username = $this->session->userdata('username');
                    $this->db->query("INSERT INTO pemakaian_detail(detpakaifaktur,detpakaikodebarcode,detpakaijml,detpakaihargabeli,detpakaisubtotal,detpakaiuserinput)
                    (SELECT detpakaifaktur,detpakaikodebarcode,detpakaijml,detpakaihargabeli,detpakaisubtotal,detpakaiuserinput FROM temp_pemakaian WHERE detpakaifaktur = '$faktur' AND detpakaiuserinput='$username')");

                    // Hapus temp pemakaian
                    $this->db->delete('temp_pemakaian', ['detpakaifaktur' => $faktur]);

                    // Simpan ke Neraca Biaya
                    $this->neraca->simpanupdateneracabiaya($noakun, $faktur, $total, $tgl);

                    // Kurangi Persediaan Barang Dagang
                    $this->neraca->kurangipersediaanbarangdagang_pemakaian($faktur, $total, $tgl);

                    $msg = [
                        'sukses' => 'Pemakaian Barang Berhasil disimpan'
                    ];
                }
            } else {
                $msg = ['error' => 'Tidak ada transaksi yang disimpan !'];
            }
            echo json_encode($msg);
        }
    }

    function updatetransaki()
    {
        if ($this->input->is_ajax_request() == true) {
            $faktur = $this->input->post('faktur', true);
            $tgl = $this->input->post('tgl', true);
            $noakun = $this->input->post('noakun', true);
            $total = $this->input->post('total', true);


            // Simpan Pemakaian
            $this->db->where('pakaifaktur', $faktur);
            $this->db->update('pemakaian', [
                'pakaitotal' => $total
            ]);
            // Simpan ke Neraca Biaya
            $this->neraca->simpanupdateneracabiaya($noakun, $faktur, $total, $tgl);

            // Kurangi Persediaan Barang Dagang
            $this->neraca->kurangipersediaanbarangdagang_pemakaian($faktur, $total, $tgl);

            $msg = [
                'sukses' => 'Pemakaian Barang Berhasil diupdate'
            ];
            echo json_encode($msg);
        }
    }

    function hapusitemtemp()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $this->db->delete('temp_pemakaian', ['detpakaiid' => $id]);

            $msg = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($msg);
        }
    }

    function hapusitemdetail()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $this->db->delete('pemakaian_detail', ['detpakaiid' => $id]);

            $msg = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($msg);
        }
    }
    function hapustransaksi()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);

            $ambil_datapemakaian = $this->db->get_where('pemakaian', ['pakaifaktur' => $faktur])->row_array();
            $pakaibiayanoakun = $ambil_datapemakaian['pakaibiayanoakun'];

            //Hapus neraca persediaan
            $this->db->delete('neraca_transaksi', ['transno' => $faktur, 'transnoakun' => '1-160', 'transjenis' => 'D']);
            // Hapus neraca Biaya
            $this->db->delete('neraca_transaksi', ['transno' => $faktur, 'transnoakun' => $pakaibiayanoakun, 'transjenis' => 'K']);

            $this->db->delete('pemakaian_detail', ['detpakaifaktur' => $faktur]);
            $this->db->delete('pemakaian', ['pakaifaktur' => $faktur]);

            $msg = [
                'sukses' => 'Transaksi Pemakaian berhasil dihapus'
            ];
            echo json_encode($msg);
        }
    }

    function detailitempemakaian()
    {
        if ($this->input->is_ajax_request()) {
            $faktur = $this->input->post('faktur', true);

            $data = [
                'datadetail' => $this->db->query("SELECT detpakaikodebarcode AS kode,namaproduk,detpakaijml AS jml,detpakaihargabeli AS hargabeli,detpakaisubtotal AS subtotal
                FROM pemakaian_detail JOIN produk ON detpakaikodebarcode=kodebarcode WHERE detpakaifaktur ='$faktur'")
            ];

            $msg = ['data' => $this->load->view('admin/pemakaian/modaldetailitem', $data, true)];

            echo json_encode($msg);
        }
    }

    public function edit($faktur)
    {
        $ambildata_pemakaian = $this->db->get_where('pemakaian', ['sha1(pakaifaktur)' => $faktur]);
        $row = $ambildata_pemakaian->row_array();

        $ambildata_akunneraca = $this->db->get_where('neraca_akun', ['noakun' => $row['pakaibiayanoakun']])->row_array();

        $datadetail_pemakaian = $this->db->query("SELECT detpakaiid as id,detpakaikodebarcode AS kode,namaproduk,detpakaijml AS jml,detpakaihargabeli AS hargabeli,detpakaisubtotal AS subtotal
        FROM temp_pemakaian JOIN produk ON detpakaikodebarcode=kodebarcode WHERE detpakaifaktur ='$row[pakaifaktur]'");

        $data = [
            'faktur' => $row['pakaifaktur'],
            'tgl' => $row['pakaitgl'],
            'noakun' => $row['pakaibiayanoakun'],
            'namaakun' => $ambildata_akunneraca['namaakun'],
            'total' => $row['pakaitotal'],
            'datadetail' => $datadetail_pemakaian
        ];
        $view = [
            'isi' => $this->load->view('admin/pemakaian/edit', $data, true)

        ];
        $this->parser->parse('layoutkasir/main', $view);
    }

    function simpandetail()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('Modelkasir', 'kasir');

            $kodebarcode = $this->input->post('kodebarcode', true);
            $namaproduk = $this->input->post('namaproduk', true);
            $faktur = $this->input->post('faktur', true);
            $jml = $this->input->post('jml', true);
            $aksi = $this->input->post('aksi', true);

            $cekproduk = $this->kasir->cekproduk($kodebarcode, $namaproduk);

            if ($cekproduk->num_rows() > 0) {
                if ($cekproduk->num_rows() === 1) {
                    $row = $cekproduk->row_array();
                    $kodeproduk = $row['kodebarcode'];
                    $hargabeli = $row['harga_beli_eceran'];

                    // Cek data Temp
                    $query_cektemppemakaian = $this->db->get_where('pemakaian_detail', ['detpakaifaktur' => $faktur, 'detpakaikodebarcode' => $kodeproduk]);

                    if ($query_cektemppemakaian->num_rows() > 0) {
                        $row_temp = $query_cektemppemakaian->row_array();
                        $idtemp = $row_temp['detpakaiid'];
                        $jmllama = $row_temp['detpakaijml'];

                        $jmlbaru = $jml + $jmllama;

                        $subtotalbaru = $jmlbaru * $hargabeli;
                        $this->db->where('detpakaiid', $idtemp);
                        $this->db->update('pemakaian_detail', [
                            'detpakaijml' => $jmlbaru,
                            'detpakaisubtotal' => $subtotalbaru
                        ]);
                    } else {
                        // simpan ke temp pemakaian
                        $this->db->insert('pemakaian_detail', [
                            'detpakaifaktur' => $faktur,
                            'detpakaikodebarcode' => $kodeproduk,
                            'detpakaijml' => $jml,
                            'detpakaihargabeli' => $hargabeli,
                            'detpakaisubtotal' => $hargabeli * $jml,
                            'detpakaiuserinput' => $this->session->userdata('username')
                        ]);
                    }
                    $msg = [
                        'sukses' => 'Berhasil'
                    ];
                } else {
                    $data = [
                        'tampildata' => $cekproduk,
                        'keyword' => $kodebarcode,
                        'aksi' => $aksi
                    ];
                    $msg = ['banyakdata' => $this->load->view('admin/pemakaian/modaldatacariproduk', $data, true)];
                }
            } else {
                $msg = [
                    'error' => 'Kode produk tidak ditemukan'
                ];
            }
            echo json_encode($msg);
        }
    }
}