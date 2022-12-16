<?php
class Saldo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->aksesgrup = $this->session->userdata('idgrup');
        if ($this->session->userdata('masuk') == true && ($this->aksesgrup == 1)) {
            $this->load->library(['form_validation']);
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-money-bill"></i> Manajemen Data Pulsa',
            'isi' => $this->load->view('admin/saldo/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function ambildata()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $this->load->model('admin/Modelsaldo', 'saldo');
            $list = $this->saldo->get_datatables();
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
                            <a class=\"dropdown-item\" href=\"#\" onclick=\"hapus('" . $field->kodesaldo . "','" . $field->namaproduk . "')\">
                                <i class=\"fa fa-fw fa-trash-alt\"></i> Hapus
                            </a>
                        </div>
                        </div>";

                $row[] = $no;
                $row[] = date('d-m-Y', strtotime($field->tglsaldo));
                $row[] = number_format($field->jmlsaldo, 2, ",", ".");
                $row[] = $aksi;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->saldo->count_all(),
                "recordsFiltered" => $this->saldo->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    function tambah()
    {
        if ($this->input->is_ajax_request()) {
            $msg = [
                'data' => $this->load->view('admin/saldo/modalformtambah', '', true)
            ];
            echo json_encode($msg);
        }
    }

    function buatkode()
    {
        if ($this->input->is_ajax_request()) {
            $tgl = $this->input->post('tgl', true);

            $query = $this->db->query("SELECT MAX(kodesaldo) AS kode FROM saldopulsa WHERE DATE_FORMAT(tglsaldo,'%Y-%m-%d') = '$tgl'");
            $hasil = $query->row_array();
            $data  = $hasil['kode'];


            $lastNoUrut = substr($data, 10, 4);

            // nomor urut ditambah 1
            $nextNoUrut = $lastNoUrut + 1;

            // membuat format nomor transaksi berikutnya
            $nextNoTransaksi = 'SP-' . date('dmy', strtotime($tgl)) . sprintf('%04s', $nextNoUrut);
            $msg = [
                'sukses' => $nextNoTransaksi
            ];
            echo json_encode($msg);
        }
    }

    function simpan()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);
            $tgl = $this->input->post('tgl', true);
            $jmlsaldo = str_replace(".", "", $this->input->post('jmlsaldo', true));

            $this->db->insert('saldopulsa', [
                'kodesaldo' => $kode,
                'tglsaldo' => $tgl,
                'jmlsaldo' => $jmlsaldo
            ]);

            $ambiltotaldata = $this->db->get('saldopulsa')->num_rows();
            $nomor = $ambiltotaldata + 1;

            // Tambahkan ke Neraca Persediaan Saldo
            $this->db->insert('neraca_transaksi', [
                'transno' => $kode,
                'transtgl' => $tgl,
                'transnoakun' => '1-161',
                'transjenis' => 'K',
                'transjml' => $jmlsaldo,
                'transket' => 'Penambahan Saldo'
            ]);

            $msg = [
                'sukses' => 'Saldo Pulsa berhasil ditambahkan'
            ];
            echo json_encode($msg);
        }
    }

    function hapus()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);

            $this->db->delete('saldopulsa', ['kodesaldo' => $kode]);

            $this->db->delete('neraca_transaksi', ['transno' => $kode]);

            $msg = [
                'sukses' => 'Data berhasil dihapus'
            ];
            echo json_encode($msg);
        }
    }
}