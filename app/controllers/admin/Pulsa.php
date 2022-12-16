<?php
class Pulsa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->aksesgrup = $this->session->userdata('idgrup');
        if ($this->session->userdata('masuk') == true && $this->aksesgrup == '1') {
            $this->load->library(['form_validation']);
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function data()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-phone-square"></i> Data Produk Pulsa',
            'isi' => $this->load->view('admin/pulsa/data', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function ambildataprodukpulsa()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->load->model('admin/Modelpulsa', 'pulsa');
            $list = $this->pulsa->get_datatables();
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
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"hapusproduk('" . $field->id . "','" . $field->kodebarcode . "','" . $field->namaproduk . "')\">
                                <i class=\"fa fa-fw fa-trash-alt\"></i> Hapus
                            </a>
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"formedit('" . sha1($field->id) . "')\">
                                <i class=\"fa fa-fw fa-tag\"></i> Edit
                            </a>
                        </div>
                        </div>";

                $row[] = $no;
                $row[] = $field->kodebarcode;
                $row[] = $field->namaproduk;
                $row[] = number_format($field->harga_beli_eceran, 2, ",", ".");
                $row[] = number_format($field->harga_jual_eceran, 2, ",", ".");
                $row[] = $aksi;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->pulsa->count_all(),
                "recordsFiltered" => $this->pulsa->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    function hapusproduk()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $kode = $this->input->post('kode', true);

            $cek_transaksi = $this->db->query("SELECT detjualkodebarcode FROM penjualan_detail WHERE detjualkodebarcode ='$kode'")->num_rows();
            if ($cek_transaksi > 0) {
                $msg = [
                    'error' => 'Maaf produk ini tidak bisa dihapus, dikarnakan sudah ada transaksi'
                ];
            } else {
                $this->db->delete('produk', [
                    'id' => $id,
                    'produkpaket' => 2
                ]);
                $msg = [
                    'sukses' => 'Produk berhasil dihapus'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function input_produk()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-phone-square"></i> Input Produk Pulsa',
            'isi' => $this->load->view('admin/pulsa/input', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function dataoperator()
    {
        if ($this->input->is_ajax_request()) {
            // Menampilkan Data Satuan
            $dataop = $this->db->get('operatorpulsa')->result();

            $datax = "<option value=\"\">-Pilih Operator-</option>";
            foreach ($dataop as $x) {
                $datax .= "<option value='" . $x->idoperator . "'>" . $x->namaoperator . "</option>";
            }


            $msg = [
                'data' => $datax,
            ];
            echo json_encode($msg);
        }
    }
    function datavoucher()
    {
        if ($this->input->is_ajax_request()) {
            // Menampilkan Data Satuan
            $dataop = $this->db->get('voucher')->result();

            $datax = "<option value=\"\">-Jumlah Voucher-</option>";
            foreach ($dataop as $x) {
                $datax .= "<option value='" . $x->idvoucher . "'>" . number_format($x->jmlvoucher, 0, ",", ".") . "</option>";
            }


            $msg = [
                'data' => $datax,
            ];
            echo json_encode($msg);
        }
    }

    function simpanbaru_operator()
    {
        if ($this->input->is_ajax_request()) {
            $operator = $this->input->post('operator', true);

            $datasimpan = [
                'namaoperator' => $operator
            ];
            $this->db->insert('operatorpulsa', $datasimpan);

            $msg = [
                'sukses' => ''
            ];
            echo json_encode($msg);
        }
    }
    function simpanbaru_voucher()
    {
        if ($this->input->is_ajax_request()) {
            $voucher = $this->input->post('voucher', true);

            $datasimpan = [
                'jmlvoucher' => $voucher
            ];
            $this->db->insert('voucher', $datasimpan);

            $msg = [
                'sukses' => ''
            ];
            echo json_encode($msg);
        }
    }

    function hapus_operator()
    {
        if ($this->input->is_ajax_request()) {
            $operator = $this->input->post('operator', true);

            $this->db->delete('operatorpulsa', ['idoperator' => $operator]);

            $msg = [
                'sukses' => ''
            ];
            echo json_encode($msg);
        }
    }
    function hapus_voucher()
    {
        if ($this->input->is_ajax_request()) {
            $voucher = $this->input->post('voucher', true);

            $this->db->delete('voucher', ['idvoucher' => $voucher]);

            $msg = [
                'sukses' => ''
            ];
            echo json_encode($msg);
        }
    }

    function simpanproduk()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);
            $op = $this->input->post('operator', true);
            $voucher = $this->input->post('voucher', true);
            $hargamodal = str_replace(".", "", $this->input->post('hargamodal', true));
            $hargajual = str_replace(".", "", $this->input->post('hargajual', true));

            $dataop = $this->db->get_where('operatorpulsa', ['idoperator' => $op])->row_array();
            $datavoucher = $this->db->get_where('voucher', ['idvoucher' => $voucher])->row_array();

            $namaproduk = "$dataop[namaoperator] $datavoucher[jmlvoucher]";

            $this->db->insert('produk', [
                'kodebarcode' => "pulsa$op$voucher",
                'namaproduk' => $namaproduk,
                'satid' => 0,
                'katid' => 1,
                'stok_tersedia' => 0,
                'harga_beli_eceran' => $hargamodal,
                'harga_jual_eceran' => $hargajual,
                'jml_eceran' => 1,
                'userinput' => $this->session->userdata('username'),
                'produkpaket' => 2
            ]);

            $msg = [
                'sukses' => 'Produk Pulsa berhasil ditambahkan'
            ];
            echo json_encode($msg);
        }
    }

    public function edit($id)
    {
        $ambildata = $this->db->get_where('produk', ['sha1(id)' => $id]);

        if ($ambildata->num_rows() > 0) {
            $data = [
                'row' => $ambildata->row_array()
            ];
            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-phone-square"></i> Edit Produk Pulsa',
                'isi' => $this->load->view('admin/pulsa/edit', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            redirect('pulsa/data', 'refresh');
        }
    }

    public function updateproduk()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('idproduk', true);

            $hargamodal = str_replace(".", "", $this->input->post('hargamodal', true));
            $hargajual = str_replace(".", "", $this->input->post('hargajual', true));

            $this->db->where('id', $id);
            $this->db->update('produk', [
                'harga_beli_eceran' => $hargamodal,
                'harga_jual_eceran' => $hargajual
            ]);
            $msg = [
                'sukses' => 'Produk Pulsa berhasi di-Update'
            ];
            echo json_encode($msg);
        }
    }
}