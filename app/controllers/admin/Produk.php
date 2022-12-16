<?php

defined('BASEPATH') or die('No direct script access allowed');

require('./apptot/novinaldi/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library(array(
                'form_validation'
            ));
            $this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
            $this->load->library('Zend');
            $this->load->model('admin/produk/Modeldataproduk', 'dataproduk');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function home()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-fw fa-tasks"></i> Produk',
            'isi' => $this->load->view('admin/produk/home', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }
    public function index()
    {
        $data = [
            'datakategori' => $this->db->get('kategori')
        ];
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-fw fa-tasks"></i> Manajemen Data Produk',
            'isi' => $this->load->view('admin/produk/index', $data, true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function add()
    {

        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-fw fa-plus-circle"></i> Add Produk',
            'isi' => $this->load->view('admin/produk/formtambah', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function simpan()
    {
        $kode = $this->input->post('kode', true);
        $namaproduk = $this->input->post('nama', true);
        $satuan = $this->input->post('satuan', true);
        $kategori = $this->input->post('kategori', true);
        $stok = $this->input->post('stok', true);
        $hargabeli = str_replace(",", "", $this->input->post('hargabeli', true));
        $hargajual = str_replace(",", "", $this->input->post('hargajual', true));
        $hargajualgrosir = str_replace(",", "", $this->input->post('hargajualgrosir', true));
        $margin = str_replace(",", "", $this->input->post('margin', true));
        $jml = $this->input->post('jml', true);


        $this->form_validation->set_rules(
            'kode',
            'Kode Barcode',
            'trim|required|is_unique[produk.kodebarcode]',
            [
                'required' => '%s tidak boleh kosong',
                'is_unique' => '%s sudah ada, silahkan coba dengan kode yang lain'
            ]
        );
        $this->form_validation->set_rules(
            'nama',
            'Nama Produk',
            'trim|required',
            [
                'required' => '%s tidak boleh kosong',
            ]
        );
        $this->form_validation->set_rules(
            'satuan',
            'Pilih Satuan',
            'trim|required',
            [
                'required' => 'Setidaknya %s tidak boleh kosong',
            ]
        );


        if ($this->form_validation->run() == TRUE) {
            $datasimpan = [
                'kodebarcode' => $kode,
                'namaproduk' => $namaproduk,
                'tglinput' => date('Y-m-d H:i:s'),
                'katid' => $kategori,
                'satid' => $satuan,
                'stok_tersedia' => $stok,
                'harga_jual_eceran' => $hargajual,
                'harga_jual_grosir' => $hargajualgrosir,
                'harga_beli_eceran' => $hargabeli,
                'margin' => $margin,
                'jml_eceran' => $jml,
                // 'stokawal' => $this->input->post('stokawal', true),
                'userinput' => $this->session->userdata('username')
            ];

            $simpan = $this->dataproduk->simpanproduk($datasimpan);

            if ($simpan) {
                $button = "<button type=\"button\" class=\"btn btn-success\" onclick=\"window.location='" . site_url('admin/produk/addharga/' . sha1($kode)) . "'\">
                    Tambahkan Harga Per-Satuan Lainnya ?
                </button>";
                $msg = [
                    'msg' => '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong><i class="fa fa-fw fa-check"></i> Sukses ! </strong>
                                <p>
                                    Produk Berhasil ditambahkan, Jika Ingin Menambahkan Harga Per-Satuan Lainnya, silahkan klik Tombol Berikut :
                                    ' . $button . '
                                </p>
                            </div>'
                ];

                $this->session->set_flashdata($msg);
            }
        } else {
            $msg = [
                'msg' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong><i class="fa fa-fw fa-ban"></i> Gagal ! </strong>
                        <p>
                            ' . validation_errors() . '
                        </p>
                    </div>',
                'nama' => $namaproduk,
                'kode' => $kode
            ];

            $this->session->set_flashdata($msg);
        }

        redirect('admin/produk/add');
    }

    public function update()
    {
        $idproduk = $this->input->post('idproduk', true);
        $kode = $this->input->post('kode', true);
        $nama = $this->input->post('nama', true);
        $satuan = $this->input->post('satuan', true);
        $kategori = $this->input->post('kategori', true);
        $stok = $this->input->post('stok', true);
        $hargabeli = str_replace(",", "", $this->input->post('hargabeli', true));
        $hargajual = str_replace(",", "", $this->input->post('hargajual', true));
        $hargajualgrosir = str_replace(",", "", $this->input->post('hargajualgrosir', true));
        $margin = str_replace(",", "", $this->input->post('margin', true));
        $jml = $this->input->post('jml', true);

        $dataupdate = [
            'kodebarcode' => $kode,
            'namaproduk' => $nama, 'satid' => $satuan,
            'katid' => $kategori, 'stok_tersedia' => $stok,
            'harga_jual_eceran' => $hargajual,
            'harga_jual_grosir' => $hargajualgrosir,
            'harga_beli_eceran' => $hargabeli,
            'margin' => $margin,
            'jml_eceran' => $jml
        ];
        $this->db->where('sha1(id)', $idproduk);
        $updatedata = $this->db->update('produk', $dataupdate);

        if ($updatedata) {
            $msg = [
                'msg' => '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong><i class="fa fa-fw fa-check"></i> Sukses ! </strong>
                                <p>
                                    Produk dengan Kode Barcode / Nama Produk : <strong>' . $kode . '/' . $nama . '</strong> berhasil diupdate
                                </p>
                            </div>'
            ];

            $this->session->set_flashdata($msg);
        }

        redirect('admin/produk/index');
    }


    function tampilforminputsatuankategori()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            // Menampilkan data kategori
            $datakategori = $this->db->get('kategori')->result();

            $data = "<option value=\"1\">-Silahkan Pilih-</option>";
            foreach ($datakategori as $d) {
                $data .= "<option value='" . $d->katid . "'>" . $d->katnama . "</option>";
            }

            // Menampilkan Data Satuan
            $datasatuan = $this->db->get('satuan')->result();

            $datax = "<option value=\"\">-Silahkan Pilih-</option>";
            foreach ($datasatuan as $x) {
                $datax .= "<option value='" . $x->satid . "'>" . $x->satnama . "</option>";
            }


            $msg = [
                'datasatuan' => $datax,
                'datakategori' => $data
            ];
            echo json_encode($msg);
        }
    }

    function tambahkategori()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->load->view('admin/kategori/formtambah');
        } else {
            exit('Maaf tidak dapat diakses');
        }
    }

    function hapusproduk()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', true);
            $hapus = $this->dataproduk->hapusproduk($id);

            if ($hapus) {
                $msg = ['sukses' => 'Produk berhasil terhapus !'];
            }
            echo json_encode($msg);
        }
    }

    function kembalikanproduk()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', true);
            $hapus = $this->dataproduk->kembalikanproduk($id);

            if ($hapus) {
                $msg = ['sukses' => 'Produk berhasil di Kembalikan !'];
            }
            echo json_encode($msg);
        }
    }

    function hapusprodukpermanen()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $id = $this->input->post('id', true);
            $hapus = $this->dataproduk->hapusprodukpermanen($id);

            if ($hapus) {
                $msg = ['sukses' => 'Produk berhasil terhapus secara permanen !'];
            }
            echo json_encode($msg);
        }
    }

    function ambildataproduk()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $sortir = $this->input->post('sortir', true);
            $kategori = $this->input->post('kategori', true);
            $list = $this->dataproduk->get_datatables($sortir, $kategori);
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
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"hapusproduk('" . $field->id . "','" . $field->namaproduk . "')\">
                                <i class=\"fa fa-fw fa-trash-alt\"></i> Hapus
                            </a>
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"formedit('" . sha1($field->id) . "')\">
                                <i class=\"fa fa-fw fa-tag\"></i> Edit
                            </a>
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"tambahharga('" . sha1($field->kodebarcode) . "')\">
                                <i class=\"fa fa-fw fa-money-bill-wave\"></i> Tambah Harga
                            </a>
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"cetaklabel('" . $field->kodebarcode . "','" . $field->namaproduk . "')\">
                                <i class=\"fa fa-fw fa-print\"></i> Cetak Label
                            </a>
                        </div>
                        </div>";

                $row[] = "<input type='checkbox' class='check-item' name='id[]' value='" . $field->id . "'>";
                $row[] = $no;
                $row[] = "<a href=\"#\" title=\"$field->namaproduk\" onclick=\"showDetail('" . $field->kodebarcode . "')\">" . $field->kodebarcode . "</a>";
                $row[] = $field->namaproduk;
                $row[] = $field->satnama;
                $row[] = number_format($field->harga_beli_eceran, 2, ",", ".");
                $row[] = number_format($field->harga_jual_eceran, 2, ",", ".");
                $row[] = number_format($field->harga_jual_grosir, 2, ",", ".");
                $row[] = $field->katnama;
                $row[] = number_format($field->stok_tersedia, 2, ",", ".");
                $row[] = $aksi;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->dataproduk->count_all($sortir, $kategori),
                "recordsFiltered" => $this->dataproduk->count_filtered($sortir, $kategori),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function recovery()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-fw fa-recycle"></i> Recovery Data Produk',
            'isi' => $this->load->view('admin/produk/tempproduk', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function ambildataproduktemp()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->load->model('admin/produk/Modeldataproduktemp', 'dataproduktemp');
            $list = $this->dataproduktemp->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $tombolhapus = "<button type=\"button\" class=\"btn btn-danger btn-round btn-sm waves-effect waves-light\" onclick=\"hapuspermanen('" . $field->id . "','" . $field->namaproduk . "')\" title=\"Hapus Produk Secara Permanen\">
                    <i class=\"fa fa-fw fa-trash-alt\"></i>
                </button>";

                $tombolrecovery = "<button type=\"button\" class=\"btn btn-info btn-round btn-sm waves-effect waves-light\" onclick=\"recovery('" . $field->id . "','" . $field->namaproduk . "')\" title=\"Kembalikan Data Produk\">
                <i class=\"fa fa-fw fa-recycle\"></i>
            </button>";
                $row[] = "<input type='checkbox' class='check-item' name='id[]' value='" . $field->id . "'>";
                $row[] = $no;
                $row[] = $field->kodebarcode;
                $row[] = $field->namaproduk;
                $row[] = $field->katnama;
                $row[] = $tombolhapus . '&nbsp;' . $tombolrecovery;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->dataproduktemp->count_all(),
                "recordsFiltered" => $this->dataproduktemp->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function hapus_multiple()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            for ($i = 0; $i < count($id); $i++) {
                // $this->db->delete('produk', ['id' => $id[$i]]);
                $this->db->where('id', $id[$i]);
                $this->db->update('produk', [
                    'stthapus' => 1
                ]);
            }

            $pesan = [
                'msg' => '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                ' . count($id) . ' data produk berhasil terhapus
            </div>'
            ];
            $this->session->set_flashdata($pesan);
        }
    }

    public function hapustemp_multiple()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            for ($i = 0; $i < count($id); $i++) {
                $this->db->delete('produk', ['id' => $id[$i]]);
            }

            $pesan = [
                'msg' => '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                ' . count($id) . ' data produk berhasil terhapus secara permanen
            </div>'
            ];
            $this->session->set_flashdata($pesan);
        }
    }

    public function edit($id)
    {
        $detaildata = $this->dataproduk->detaildataproduk($id);
        $this->load->model('admin/Modelkategori', 'kategori');
        if ($detaildata->num_rows() > 0) {
            $r = $detaildata->row_array();

            $data = [
                'id' => $id,
                'kode' => $r['kodebarcode'],
                'nama' => $r['namaproduk'],
                'stok' => $r['stok_tersedia'],
                'idkategori' => $r['katid'],
                'datakategori' => $this->kategori->datakategori()->result(),
                'idsatuan' => $r['satid'],
                'datasatuan' => $this->db->get('satuan')->result(),
                'hargajual' => $r['harga_jual_eceran'],
                'hargajualgrosir' => $r['harga_jual_grosir'],
                'hargabeli' => $r['harga_beli_eceran'],
                'margin' => $r['margin'],
                'tgledit' => date('Y-m-d H:i:s'),
                'jml' => $r['jml_eceran']
            ];

            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-fw fa-tag"></i> Edit Produk',
                'isi' => $this->load->view('admin/produk/formedit', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            redirect('admin/produk/index');
        }
    }

    public function addharga($kode)
    {
        $detaildata = $this->db->get_where('produk', ['sha1(kodebarcode)' => $kode]);
        if ($detaildata->num_rows() > 0) {
            $r = $detaildata->row_array();
            $idkategori = $r['katid'];
            $idsatuan = $r['satid'];

            $ambildatakategori = $this->db->get_where('kategori', ['katid' => $idkategori]);
            $row_kategori = $ambildatakategori->row_array();

            $ambildatasatuan = $this->db->get_where('satuan', ['satid' => $idsatuan]);
            $row_satuan = $ambildatasatuan->row_array();

            $data = [
                'id' => $r['id'],
                'kode' => $r['kodebarcode'],
                'nama' => $r['namaproduk'],
                'stok' => $r['stok_tersedia'],
                'satuan' => $row_satuan['satnama'],
                'kategori' => $row_kategori['katnama'],
                'hargajual' => number_format($r['harga_jual_eceran'], 2, ".", ","),
                'hargabeli' => number_format($r['harga_beli_eceran'], 2, ".", ","),
                'jml' => $r['jml_eceran']
            ];

            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-fw fa-money-bill-wave"></i> Tambahkan Harga Produk',
                'isi' => $this->load->view('admin/produk/formtambahharga', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            redirect('admin/produk/index');
        }
    }

    public function formtambahharga()
    {
        if ($this->input->is_ajax_request() == true) {
            $kode = $this->input->post('kode', true);
            $detaildata = $this->db->get_where('produk', ['kodebarcode' => $kode]);
            $r = $detaildata->row_array();
            $data = [
                'kode' => $kode,
                'nama' => $r['namaproduk']
            ];

            $this->load->view('admin/produk/modaltambahharga', $data);
        }
    }

    public function formedithargaproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            $ambildata = $this->db->get_where('produk_harga', ['id' => $id]);
            $r = $ambildata->row_array();

            $ambildataproduk = $this->db->get_where('produk', ['kodebarcode' => $r['kodebarcode']]);
            $r_produk = $ambildataproduk->row_array();

            $data = [
                'id'  => $id,
                'idsat' => $r['idsat'],
                'hargamodal' => $r['hargamodal'],
                'hargajual' => $r['hargajual'],
                'jml' => $r['jml_default'],
                'kode' => $r_produk['kodebarcode'],
                'nama' => $r_produk['namaproduk'],
                'margin' => $r['margin'],
                'datasatuan' => $this->db->get('satuan')->result()
            ];

            $this->load->view('admin/produk/modaleditharga', $data);
        }
    }

    function viewinputsatuan()
    {
        if ($this->input->is_ajax_request() == true) {

            $data = [
                'datasatuan' => $this->db->get('satuan')->result()
            ];
            $this->load->view('admin/produk/inputsatuan', $data);
        }
    }

    function ambildatasatuan()
    {
        if ($this->input->is_ajax_request() == true) {
            $datasatuan = $this->db->get('satuan')->result();

            $data = "<option value=\"\">-Silahkan Pilih-</option>";
            foreach ($datasatuan as $d) {
                $data .= "<option value='" . $d->satid . "'>" . $d->satnama . "</option>";
            }

            $msg = [
                'datasatuan' => $data
            ];
            echo json_encode($msg);
        }
    }

    public function simpanhargaproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->form_validation->set_rules('satuan', 'Satuan', 'trim|required', [
                'required' => '%s harus dipilih terlebih dahulu'
            ]);
            $this->form_validation->set_rules('qty', 'Inputan Jumlah', 'trim|required', [
                'required' => '%s harus diisi, Yang diinput harus selain dari jumlah 1'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $datasimpan = [
                    'kodebarcode' => $this->input->post('kode', true),
                    'idsat' => $this->input->post('satuan', true),
                    'hargamodal' => $this->input->post('hrgmodal', true),
                    'hargajual' => $this->input->post('hrgjual', true),
                    'jml_default' => $this->input->post('qty', true),
                    'margin' => $this->input->post('margin', true)
                ];

                $simpan = $this->db->insert('produk_harga', $datasimpan);

                if ($simpan) {
                    $msg = [
                        'sukses' => 'Harga Produk berhasil ditambahkan'
                    ];
                }
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <strong>Error !</strong> 
                                    ' . validation_errors() . '
                                </div>'
                ];
            }
            echo json_encode($msg);
        }
    }

    function updatehargaproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->form_validation->set_rules('satuan', 'Satuan', 'trim|required', [
                'required' => '%s harus dipilih terlebih dahulu'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $datasimpan = [
                    'idsat' => $this->input->post('satuan', true),
                    'hargamodal' => $this->input->post('hrgmodal', true),
                    'hargajual' => $this->input->post('hrgjual', true),
                    'margin' => $this->input->post('margin', true),
                    'jml_default' => $this->input->post('jml', true),
                ];

                $this->db->where('id', $this->input->post('id', true));
                $simpan = $this->db->update('produk_harga', $datasimpan);

                if ($simpan) {
                    $msg = [
                        'sukses' => 'Harga Produk berhasil di-update'
                    ];
                }
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <strong>Error !</strong> 
                                    ' . validation_errors() . '
                                </div>'
                ];
            }
            echo json_encode($msg);
        }
    }

    function tampilhargaproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $kode = $this->input->post('kode', true);

            $ambildataharga = $this->db->query("SELECT * FROM produk_harga LEFT JOIN satuan ON satid=idsat WHERE kodebarcode='$kode'");


            $data = [
                'tampildata' => $ambildataharga->result()
            ];

            $this->load->view('admin/produk/viewtampildatahargaproduk', $data);
        }
    }

    function hapushargaproduk()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            $ambildata = $this->db->get_where('produk_harga', ['id' => $id]);
            $row = $ambildata->row_array();

            $this->db->delete('produk_harga', ['id' => $id]);

            $msg = [
                'sukses' => 'Berhasil di hapus !'
            ];

            echo json_encode($msg);
        }
    }

    function settingdefaultharga()
    {
        if ($this->input->is_ajax_request() == true) {
            $id = $this->input->post('id', true);

            //update jadi N semua
            $this->db->update('produk_harga', [
                'defaultharga' => 'N'
            ]);

            // update yang Y
            $this->db->where('id', $id);
            $this->db->update('produk_harga', [
                'defaultharga' => 'Y'
            ]);

            $msg = [
                'sukses' => 'Berhasil di Eksekusi'
            ];
            echo json_encode($msg);
        }
    }

    public function showdetail()
    {
        if ($this->input->is_ajax_request() == true) {
            $kode = $this->input->post('kode', true);

            $detaildata = $this->db->get_where('produk', ['kodebarcode' => $kode]);
            $r = $detaildata->row_array();

            $ambildatakategori = $this->db->get_where('kategori', ['katid' => $r['katid']]);
            $row_kategori = $ambildatakategori->row_array();

            $ambildatasatuan = $this->db->get_where('satuan', ['satid' => $r['satid']]);
            $row_satuan = $ambildatasatuan->row_array();

            $data = [
                'kode' => $kode,
                'namaproduk' => $r['namaproduk'],
                'satuan' => $row_satuan['satnama'],
                'kategori' => $row_kategori['katnama'],
                'hargajual' => number_format($r['harga_jual_eceran'], 2, ",", "."),
                'hargabeli' => number_format($r['harga_beli_eceran'], 2, ",", "."),
                'jml' => number_format($r['jml_eceran'], 2, ",", "."),
                'stok' => number_format($r['stok_tersedia'], 2, ",", "."),
                'margin' => number_format($r['margin'], 2, ",", ".") . ' %',
            ];
            $this->load->view('admin/produk/modalshowdetail', $data);
        }
    }

    // Manajemen Produk Paket
    public function paket()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-fw fa-tasks"></i> Manajemen Data Produk Paket',
            'isi' => $this->load->view('admin/produk/paket/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }
    function ambildataprodukpaket()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->load->model('admin/produk/Modeldataprodukpaket', 'produkpaket');
            $list = $this->produkpaket->get_datatables();
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
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"hapusproduk('" . $field->id . "','" . $field->namaproduk . "')\">
                                <i class=\"fa fa-fw fa-trash-alt\"></i> Hapus
                            </a>
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"detailpaket('" . sha1($field->kodebarcode) . "')\">
                                <i class=\"fa fa-fw fa-hand-point-right\"></i> Detail
                            </a>
                        </div>
                        </div>";

                $row[] = $no;
                $row[] = $field->kodebarcode;
                $row[] = $field->namaproduk;
                $row[] = number_format($field->harga_beli_eceran, 2, ",", ".");
                $row[] = number_format($field->harga_jual_eceran, 2, ",", ".");
                $row[] = number_format($field->stok_tersedia, 0, ",", ".");
                $row[] = $aksi;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->produkpaket->count_all(),
                "recordsFiltered" => $this->produkpaket->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    function pakettambahdata()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/produk/paket/modaltambahdata', '', true)
            ];

            echo json_encode($msg);
        }
    }

    function paketsimpandata()
    {
        if ($this->input->is_ajax_request()) {
            $kodeproduk = $this->input->post('kodeproduk', true);
            $namapaket = $this->input->post('namapaket', true);

            $this->form_validation->set_rules('kodeproduk', 'Kode', 'trim|required|is_unique[produk.kodebarcode]', [
                'required' => '%s tidak boleh kosong',
                'is_unique' => '%s sudah ada, silahkan coba dengan kode yang lain'
            ]);
            $this->form_validation->set_rules('namapaket', 'Nama Paket', 'trim|required', [
                'required' => '%s tidak boleh kosong',
            ]);


            if ($this->form_validation->run() == TRUE) {
                $this->db->insert('produk', [
                    'kodebarcode' => $kodeproduk,
                    'namaproduk' => $namapaket,
                    'stok_tersedia' => 0,
                    'harga_jual_eceran' => 0,
                    'harga_beli_eceran' => 0,
                    'satid' => 0,
                    'katid' => 1,
                    'tglinput' => date('Y-m-d'),
                    'userinput' => $this->session->userdata('username'),
                    'produkpaket' => 1
                ]);

                $msg = [
                    'sukses' => "Produk paket berhasil tersimpan. Untuk menambahkan item-item silahkan klik pada tombol berikut " . "<button type=\"button\" class=\"btn btn-primary btn-sm\" onclick=\"detailpaket('" . sha1($kodeproduk) . "')\"><i class=\"fa fa-hand-point-right\"></i> Detail</button>"
                ];
            } else {
                $msg = [
                    'error' => [
                        'kodeproduk' => form_error('kodeproduk'),
                        'namapaket' => form_error('namapaket'),
                    ]
                ];
            }
            echo json_encode($msg);
        }
    }

    function hapuspaket()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);

            $this->db->delete('produk', [
                'id' => $id,
                'produkpaket' => 1
            ]);

            $msg = [
                'sukses' => 'Produk paket berhasil terhapus'
            ];
            echo json_encode($msg);
        }
    }

    function detail_paket()
    {
        $kode = $this->uri->segment('4');

        $query_ambildataproduk = $this->db->get_where('produk', ['sha1(kodebarcode)' => $kode]);
        if ($query_ambildataproduk->num_rows() > 0) {
            $row_array = $query_ambildataproduk->row_array();
            $data = [
                'row' => $row_array,
            ];

            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-fw fa-tasks"></i> Detail Item Produk Paket',
                'isi' => $this->load->view('admin/produk/paket/detailitem', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            exit('Data tidak ditemukan...');
        }
    }

    function tampildataitemproduk()
    {
        if ($this->input->is_ajax_request()) {
            $idproduk = $this->input->post('idproduk', true);
            $data = [
                'itemproduk' => $this->db->query("SELECT produk_paket_item.* FROM produk_paket_item JOIN produk ON produk.`id` = paketidproduk WHERE paketidproduk='$idproduk'")
            ];

            $msg = [
                'data' => $this->load->view('admin/produk/paket/tampildataitemproduk', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function paketmodaldataproduk()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/produk/paket/modaldataseluruhproduk', '', true)
            ];
            echo json_encode($msg);
        }
    }
    function paketambildataproduk()
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

    public function paketambildetailproduk()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);
            $namaproduk = $this->input->post('namaproduk', true);

            $query_dataproduk = $this->db->query("SELECT * FROM produk WHERE kodebarcode = '$kode' OR namaproduk LIKE '$kode%'");


            $totaldata = $query_dataproduk->num_rows();
            if ($totaldata === 1) {
                $row = $query_dataproduk->row_array();

                $msg = [
                    'data' => $kode
                ];
                $msg = [
                    'datasatu' => [
                        'kodeproduk' => $row['kodebarcode'],
                        'namaproduk' => $row['namaproduk'],
                        'hargabeli' => number_format($row['harga_beli_eceran'], 2, ",", "."),
                        'hargajual' => number_format($row['harga_jual_eceran'], 2, ",", "."),
                    ]
                ];
                echo json_encode($msg);
            } else {
                $data = [
                    'kode' => $kode,
                    'tampildata' => $query_dataproduk
                ];
                $msg = ['databanyak' => $this->load->view('admin/produk/paket/modaldatacariproduk', $data, true)];
                echo json_encode($msg);
            }
        }
    }

    function paketsimpanitem()
    {
        if ($this->input->is_ajax_request()) {
            $idproduk = $this->input->post('idproduk', true);
            $kodeproduk = $this->input->post('kodeproduk', true);
            $namaproduk = $this->input->post('namaproduk', true);
            $hargabeli = $this->input->post('hargabeli', true);
            $hargajual = $this->input->post('hargajual', true);
            $jml = $this->input->post('jml', true);

            $r_hargabeli = str_replace(",", ".", str_replace(".", "", $hargabeli));
            $r_hargajual = str_replace(",", ".", str_replace(".", "", $hargajual));

            $this->form_validation->set_rules('kodeproduk', 'Kode Produk', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);
            $this->form_validation->set_rules('jml', 'Jumlah Item', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $kodeproduk])->row_array();
                if ($jml > $query_dataproduk['stok_tersedia']) {
                    $msg = [
                        'error' => [
                            'jml' => 'Melampaui batas stok'
                        ]
                    ];
                } else {
                    $this->db->insert('produk_paket_item', [
                        'paketkodebarcode' => $kodeproduk,
                        'paketidproduk' => $idproduk,
                        'paketnamaproduk' => $namaproduk,
                        'pakethargabeli' => $r_hargabeli,
                        'pakethargajual' => $r_hargajual,
                        'paketjml' => $jml
                    ]);

                    $msg = [
                        'sukses' => 'Item berhasil ditambahkan'
                    ];
                }
            } else {
                $msg = [
                    'error' => [
                        'kodeproduk' => form_error('kodeproduk'),
                        'jml' => form_error('jml')
                    ]
                ];
            }
            echo json_encode($msg);
        }
    }

    function hapuspaketitem()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);

            $this->db->delete('produk_paket_item', ['paketid' => $id]);

            $msg = [
                'sukses' => 'Item berhasil dihapus'
            ];
            echo json_encode($msg);
        }
    }

    function paketmodaleditharga()
    {
        if ($this->input->is_ajax_request()) {
            $idproduk = $this->input->post('idproduk', true);

            $ambildataproduk = $this->db->get_where('produk', ['id' => $idproduk]);
            if ($ambildataproduk->num_rows() > 0) {
                $row = $ambildataproduk->row_array();
                $data = [
                    'idproduk' => $idproduk,
                    'kodebarcode' => $row['kodebarcode'],
                    'namaproduk' => $row['namaproduk'],
                    'stok' => $row['stok_tersedia'],
                    'hargabeli' => $row['harga_beli_eceran'],
                    'hargajual' => $row['harga_jual_eceran'],
                    'margin' => $row['margin'],
                ];
                $msg = [
                    'data' => $this->load->view('admin/produk/paket/modaledithargaproduk', $data, true)
                ];
                echo json_encode($msg);
            }
        }
    }

    function paketupdatehargastok()
    {
        if ($this->input->is_ajax_request()) {
            $idproduk = $this->input->post('idproduk', true);
            $hargabeli = str_replace(",", ".", str_replace(".", "", $this->input->post('txthargabeli')));
            $hargajual = str_replace(",", ".", str_replace(".", "", $this->input->post('txthargajual')));
            $margin = str_replace(",", ".", str_replace(".", "", $this->input->post('margin')));
            $stoktersedia = str_replace(".", "", $this->input->post('stoktersedia'));

            $this->db->where('id', $idproduk);
            $this->db->update('produk', [
                'stok_tersedia' => $stoktersedia,
                'harga_beli_eceran' => $hargabeli,
                'harga_jual_eceran' => $hargajual,
                'margin' => $margin
            ]);

            $msg = [
                'sukses' => 'Berhasil'
            ];
            echo json_encode($msg);
        }
    }
    // End Manajemen Produk Paket

    // Import Produk
    public function form_import()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-fw fa-tasks"></i> Detail Item Produk Paket',
            'isi' => $this->load->view('admin/produk/formimportexcel', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }
    function doimport()
    {
        $jmlsukses = 0;
        $jmlgagal = 0;
        $jmlupdatestok = 0;
        $fileName = $_FILES['uploadfile']['name'];

        $config['upload_path'] = './assets/fileimport/'; //buat folder dengan nama assets di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 10000;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('uploadfile')) {
            echo $this->upload->display_errors();
        } else {
            $media = $this->upload->data();
            $inputFileName = './assets/fileimport/' . $media['file_name'];

            try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for ($row = 2; $row <= $highestRow; $row++) {                  //  Read a row of data into an array                 
                $rowData = $sheet->rangeToArray(
                    'A' . $row . ':' . $highestColumn . $row,
                    NULL,
                    TRUE,
                    FALSE
                );

                $kode = $rowData[0][1];
                $namaproduk = $rowData[0][2];
                $stoktersedia = $rowData[0][3];
                $idsatuan = $rowData[0][4];
                $hargabeli = $rowData[0][5];
                $hargajual = $rowData[0][6];
                $hargagrosir = $rowData[0][7];

                $cekdata = $this->db->get_where('produk', ['kodebarcode' => $kode]);
                if ($cekdata->num_rows() > 0) {
                    ++$jmlgagal;
                } else {
                    if ($stoktersedia == '') {
                        $stok = 0;
                    } else {
                        $stok = $stoktersedia;
                    }
                    $datasimpan = [
                        'kodebarcode' => $kode,
                        'namaproduk' => $namaproduk,
                        'stok_tersedia' => $stok,
                        'satid' => $idsatuan,
                        'harga_beli_eceran' => $hargabeli,
                        'harga_jual_eceran' => $hargajual,
                        'harga_jual_grosir' => $hargagrosir,
                        'katid' => 1,
                        'jml_eceran' => 1,
                        'userinput' => 'administrator'
                    ];

                    $this->db->insert('produk', $datasimpan);
                    ++$jmlsukses;
                }
            }
            @unlink('./assets/fileimport/' . $media['file_name']);
            echo 'Jumlah Data Yang Berhasil :<strong>' . $jmlsukses . '</strong><br>';
            echo 'Jumlah Data Yang Gagal Di Import :<strong>' . $jmlgagal . '</strong><br><br>';
        }
    }

    public function export_produk()
    {
        $dataproduk = $this->db->query("SELECT kodebarcode,namaproduk,0 AS stoktersedia,satid AS idsatuan,harga_beli_eceran AS hargabeli,harga_jual_eceran AS hargajual FROM produk")->result();

        $spreadsheet = new Spreadsheet;

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Kode Barcode')
            ->setCellValue('C1', 'Nama Produk')
            ->setCellValue('D1', 'Stok Tersedia')
            ->setCellValue('E1', 'ID Satuan')
            ->setCellValue('F1', 'Harga Beli')
            ->setCellValue('G1', 'Harga Jual');

        $kolom = 2;
        $nomor = 1;
        foreach ($dataproduk as $d) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $d->kodebarcode)
                ->setCellValue('C' . $kolom, $d->namaproduk)
                ->setCellValue('D' . $kolom, $d->stoktersedia)
                ->setCellValue('E' . $kolom, $d->idsatuan)
                ->setCellValue('F' . $kolom, $d->hargabeli)
                ->setCellValue('G' . $kolom, $d->hargajual);

            $kolom++;
            $nomor++;
        }
        $tglhariini = date('dMY');
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="daftar-produk-' . $tglhariini . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function daftar_harga()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-fw fa-print"></i> Cetak List Harga Produk',
            'isi' => $this->load->view('admin/produk/daftarharga/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function ambillistdataproduk()
    {
        $search = $this->input->get('search');
        $sql = $this->db->query("SELECT kodebarcode,namaproduk FROM produk WHERE namaproduk LIKE '%$search%' ORDER BY namaproduk ASC");

        if ($sql->num_rows() > 0) {
            $list = [];
            $key = 0;

            foreach ($sql->result_array() as $row) :
                $list[$key]['id'] = $row['kodebarcode'];
                $list[$key]['text'] = $row['namaproduk'];
                $key++;
            endforeach;
            echo json_encode($list);
        } else {
            echo 'Kosong';
        }
    }

    function cetak_list_harga()
    {
        $list = $this->input->post('list', true);

        $jmldata = count($list);

        for ($i = 0; $i < $jmldata; $i++) :
            //Membuat Barcode
            $this->zend->load('Zend/Barcode');
            $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $list[$i]), array())->draw();
            $imagebarcodename = $list[$i] . '.jpg';
            $imagebarcodepath = './assets/images/barcode-produk/';
            imagejpeg($imageResource, $imagebarcodepath . $imagebarcodename);
            $pathbarcode = $imagebarcodepath . $imagebarcodename;

            $update_path_barcode_member = ['pathbarcode' => $pathbarcode];
            $this->db->where('kodebarcode', $list[$i]);
            $this->db->update('produk', $update_path_barcode_member);
        //end
        endfor;

        $data = [
            'row' => $list,
            'jmldata' => $jmldata
        ];

        $this->load->view('admin/produk/daftarharga/cetak', $data);
    }

    function modalCetakLabel()
    {
        if ($this->input->is_ajax_request()) {
            $kodebarcode = $this->input->post('kodebarcode', true);
            $nama = $this->input->post('nama', true);

            $data = [
                'kodebarcode' => $kodebarcode, 'namaproduk' => $nama
            ];
            $json = [
                'data' => $this->load->view('admin/produk/modalCetakLabel', $data, true)
            ];
            echo json_encode($json);
        }
    }

    public function cetak_barcode_produk()
    {
        $kode = $this->input->post('tkode', true);
        $jmlcetak = $this->input->post('jmlcetak', true);
        $namaproduk = $this->input->post('tnamaproduk', true);

        $this->zend->load('Zend/Barcode');
        $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $kode), array())->draw();
        $imagebarcodename = $kode . '.jpg';
        $imagebarcodepath = './assets/images/barcode-produk/';
        imagejpeg($imageResource, $imagebarcodepath . $imagebarcodename);
        $pathbarcode = $imagebarcodepath . $imagebarcodename;

        $update_path_barcode_member = ['pathbarcode' => $pathbarcode];
        $this->db->where('kodebarcode', $kode);
        $this->db->update('produk', $update_path_barcode_member);

        $ambilProduk = $this->db->get_where('produk', ['kodebarcode' => $kode])->row_array();
        $data = [
            'kodebarcode' => $kode,
            'jmlcetak' => $jmlcetak,
            'pathbarcode' => $ambilProduk['pathbarcode'],
            'harga' => $ambilProduk['harga_jual_eceran']
        ];

        $this->load->view('admin/produk/daftarharga/cetakBarcodePerProduk', $data);
    }
}