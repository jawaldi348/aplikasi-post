<?php
class Import extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->model('Modeltoko', 'toko');
            $this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => 'Import Produk',
            'isi' => $this->load->view('import/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function doimport()
    {
        $jmlsukses = 0;
        $jmlgagal = 0;
        $fileName = $_FILES['uploadfile']['name'];

        $config['upload_path'] = './assets/'; //buat folder dengan nama assets di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size'] = 10000;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('uploadfile')) {
            echo $this->upload->display_errors();
        } else {
            $media = $this->upload->data();
            $inputFileName = './assets/' . $media['file_name'];

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
                $hargabeli = $rowData[0][6];


                $cekdata = $this->db->get_where('produk', ['kodebarcode' => $kode]);
                if ($cekdata->num_rows() > 0) {
                    ++$jmlgagal;
                } else {
                    $datasimpan = [
                        'kodebarcode' => $kode,
                        'namaproduk' => $namaproduk,
                        'stok_tersedia' => $stoktersedia,
                        'satid' => $idsatuan,
                        'harga_beli_eceran' => $hargabeli,
                        'harga_jual_eceran' => 0,
                        'katid' => 1,
                        'jml_eceran' => 1,
                        'userinput' => $this->input->session('username')
                    ];

                    $this->db->insert('produk', $datasimpan);
                    ++$jmlsukses;
                }
            }

            echo 'Jumlah Data Yang Berhasil :<strong>' . $jmlsukses . '</strong><br>';
            echo 'Jumlah Data Yang Gagal :<strong>' . $jmlgagal . '</strong><br>';
        }
    }
}