<?php

use Ifsnop\Mysqldump\Mysqldump;

class Utility extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library([
                'form_validation', 'Bcrypt'
            ]);
            return true;
        } else {
            redirect('login/logout');
        }
    }

    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-database"></i> Backup Database',
            'isi' => $this->load->view('admin/utility/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }
    public function backup_db()
    {
        // $this->load->dbutil();
        // $tanggal = date('dmY');
        // $prefs = array(
        //     'tables'        => [
        //         'satuan', 'kategori', 'pemasok', 'koreksi_stok', 'member', 'member_setting_diskon', 'neraca_akun', 'neraca_akun_detail', 'neraca_transaksi', 'nn_grup', 'nn_users', 'nn_namatoko', 'operatorpulsa', 'voucher', 'pemakaian', 'pemakaian_detail', 'temp_pemakaian', 'pembelian', 'pembelian_detail', 'pembelian_return', 'pengambilan_diskon', 'pengambilan_diskon_detail', 'temp_pengambilan_diskon', 'pengaturan', 'penjualan', 'penjualan_detail', 'penjualan_return', 'temp_penjualan', 'produk', 'produk_harga', 'produk_paket_item', 'produk_tglkadaluarsa', 'saldopulsa', 'status_return', ''
        //     ],
        //     'ignore'        => [
        //         'ci_sess_novinaldi'
        //     ],
        //     'format'        => 'zip',
        //     'filename'      => 'dbkopmart' . $tanggal . '.sql',
        //     'add_drop'      => TRUE,
        //     'add_insert'    => TRUE,
        //     'newline'       => "\n",
        //     'foreign_key_checks' => FALSE
        // );
        // $namaFile = 'dbkopmart-' . $tanggal;
        // $backup = $this->dbutil->backup($prefs);

        // $this->load->helper('file');
        // write_file('./db_backup/' . $namaFile . '.zip', $backup);

        // $this->load->helper('download');
        // force_download($namaFile . '.zip', $backup);

        try {
            $tglSekarang = date('dmY');
            $pesanSukses = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Berhasil</strong> Database berhasil di backup
                            </div>';
            $dump = new Mysqldump('mysql:host=localhost;dbname=dbkopmart;port=3306', 'root', '');
            $dump->start('database/backup/dbbackup-' . $tglSekarang . '.sql');
            $this->session->set_flashdata('pesan', $pesanSukses);

            redirect('utility/index', 'refresh');
        } catch (\Exception $e) {
            echo 'mysqldump-php error: ' . $e->getMessage();
        }
    }

    public function restore_database()
    {
        //upload file        
        $this->load->helper('file');

        $config['upload_path'] = './db_backup/';
        $config['allowed_types'] = 'sql';
        $file_name = $_FILES['uploadfile']['name'];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('uploadfile')) {
            echo $this->upload->display_errors();
        } else {
            // $upload_data = $this->upload->data();

            $direktori = './db_backup/' . $file_name;

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            if ($ext == 'sql') {
                $isi_file = file_get_contents($direktori);
                $string_query = rtrim($isi_file, "\n;");
                $array_query = explode(";", $string_query);
                foreach ($array_query as $query) {
                    $this->db->query($query);
                }
                unlink($direktori);
                $pesanSukses = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <strong>Berhasil</strong> Data berhasil di restore
                                </div>';
                $this->session->set_flashdata('pesanrestore', $pesanSukses);
                redirect('utility/index', 'refresh');
            }
        }
    }
}