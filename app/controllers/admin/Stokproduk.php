<?php
class Stokproduk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library(array(
                'form_validation', 'pagination'
            ));
            $this->load->model('admin/produk/Modeldataproduk', 'dataproduk');
            return true;
        } else {
            redirect('errorhalaman');
        }
    }

    // public function index()
    // {
    //     $view = [
    //         'menu' => $this->load->view('template/menu', '', TRUE),
    //         'judul' => '<i class="fa fa-tasks"></i> Stok Produk',
    //         'isi' => $this->load->view('admin/stokproduk/index', '', true)

    //     ];
    //     $this->parser->parse('template/main', $view);
    // }

    public function index()
    {
        $tombol_cari = $this->input->post('btncari', true);
        // $tombol_sortir = $this->input->post('btnsortir', true);

        // if (isset($tombol_sortir)) {
        //     $sortir = $this->input->post('sortir', true);
        //     $this->session->set_userdata('sortirproduk', $sortir);

        //     redirect('admin/stokproduk/index');
        // } else {
        //     $sortir = $this->session->userdata('sortirproduk');
        // }

        if (isset($tombol_cari)) {
            $cari = $this->input->post('cari', true);
            $this->session->set_userdata('cariproduk', $cari);

            redirect('admin/stokproduk/index');
        } else {
            $cari = $this->session->userdata('cariproduk');
        }

        //Query data
        /*
        if ($sortir == '') {
            $q = "SELECT produk.`id` AS idproduk,kodebarcode,namaproduk,satuan.`satnama`,harga_beli_eceran AS hargabeli,harga_jual_eceran AS hargajual,
            stok_tersedia AS stok FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` WHERE (kodebarcode LIKE '%$cari%' OR namaproduk LIKE '%$cari%')";
        }

        if ($sortir == 1) {
            $q = "SELECT produk.`id` AS idproduk,produk.`kodebarcode`,namaproduk,satuan.`satnama`,harga_beli_eceran AS hargabeli,harga_jual_eceran AS hargajual,
            stok_tersedia AS stok,tglkadaluarsa FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` JOIN produk_tglkadaluarsa ON produk.`kodebarcode`=produk_tglkadaluarsa.`kodebarcode` 
            WHERE TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) > 3 AND TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) <= 6 AND (kodebarcode LIKE '%$cari%' OR namaproduk LIKE '%$cari%') GROUP BY produk.`kodebarcode` ORDER BY tglkadaluarsa ASC";
        }*/
        $q = "SELECT produk.`id` AS idproduk,kodebarcode,namaproduk,satuan.`satnama`,harga_beli_eceran AS hargabeli,harga_jual_eceran AS hargajual,
        stok_tersedia AS stok FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` WHERE (kodebarcode LIKE '%$cari%' OR namaproduk LIKE '%$cari%')";

        $query_data = $this->db->query($q);
        //end Query data

        $total_data = $query_data->num_rows();
        //Ini Konfigurasi Pagination
        $config['base_url'] = site_url('admin/stokproduk/index/');
        $config['total_rows'] = $total_data;
        $config['per_page'] = '10';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['first_link'] = 'Awal';
        $config['last_link'] = 'Akhir';
        $config['uri_segment'] = 4;

        //Custom Pagination
        // Membuat Style pagination untuk BootStrap v4
        $config['first_link']       = 'First';
        $config['last_link']        = 'Last';
        $config['next_link']        = 'Next';
        $config['prev_link']        = 'Prev';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close']  = '</span></li>';
        //custom pagination

        $this->pagination->initialize($config);
        //End

        $uri = $this->uri->segment(4);
        $per_page = $config['per_page'];

        if ($uri == null) {
            $start = 0;
        } else {
            $start = $uri;
        }
        //Query data perpage

        /*
        if ($sortir == '') {
            $qx = "SELECT produk.`id` AS idproduk,kodebarcode,namaproduk,satuan.`satnama`,harga_beli_eceran AS hargabeli,harga_jual_eceran AS hargajual,
        stok_tersedia AS stok FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` WHERE (kodebarcode LIKE '%$cari%' OR namaproduk LIKE '%$cari%') ORDER BY namaproduk ASC LIMIT " . $start . ',' . $per_page;
        }
        if ($sortir == '1') {
            $qx = "SELECT produk.`id` AS idproduk,produk.`kodebarcode`,namaproduk,satuan.`satnama`,harga_beli_eceran AS hargabeli,harga_jual_eceran AS hargajual,
            stok_tersedia AS stok,tglkadaluarsa FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` JOIN produk_tglkadaluarsa ON produk.`kodebarcode`=produk_tglkadaluarsa.`kodebarcode` 
            WHERE TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) > 3 AND TIMESTAMPDIFF(MONTH, NOW(), tglkadaluarsa) <= 6 AND (kodebarcode LIKE '%$cari%' OR namaproduk LIKE '%$cari%') GROUP BY produk.`kodebarcode` ORDER BY tglkadaluarsa ASC LIMIT " . $start . ',' . $per_page;
        }*/
        $qx = "SELECT produk.`id` AS idproduk,kodebarcode,namaproduk,satuan.`satnama`,harga_beli_eceran AS hargabeli,harga_jual_eceran AS hargajual,
        stok_tersedia AS stok FROM produk JOIN satuan ON satuan.`satid`=produk.`satid` WHERE (kodebarcode LIKE '%$cari%' OR namaproduk LIKE '%$cari%') ORDER BY namaproduk ASC LIMIT " . $start . ',' . $per_page;
        $query_data_per_page = $this->db->query($qx);
        //end Query data perpage

        $data = array(
            'totaldata' => $config['total_rows'],
            'cari' => $cari,
            'tampildata' => $query_data_per_page,
        );
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-tasks"></i> Stok Produk',
            'isi' => $this->load->view('admin/stokproduk/index', $data, true)
        ];
        $this->parser->parse('template/main', $view);
    }

    public function resetpencarian()
    {
        $this->session->unset_userdata('cariproduk');
        redirect('admin/stokproduk/index');
    }

    function ambildataproduk()
    {
        if ($this->input->is_ajax_request() == TRUE) {
            $list = $this->dataproduk->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $row[] = $no;
                $row[] = "<a href=\"#\" title=\"$field->namaproduk\" onclick=\"showDetail('" . sha1($field->kodebarcode) . "')\">" . $field->kodebarcode . "</a>";
                $row[] = $field->namaproduk;
                $row[] = $field->satnama;
                $row[] = number_format($field->harga_beli_eceran, 2, ",", ".");
                $row[] = number_format($field->harga_jual_eceran, 2, ",", ".");
                $row[] = number_format($field->stok_tersedia, 2, ",", ".");
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->dataproduk->count_all(),
                "recordsFiltered" => $this->dataproduk->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function detailproduk($kode)
    {
        $ambildataproduk = $this->db->query("SELECT produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran FROM produk LEFT JOIN kategori ON produk.katid=kategori.katid LEFT JOIN satuan ON produk.satid=satuan.satid WHERE stthapus =0 AND sha1(kodebarcode) = '$kode'");

        if ($ambildataproduk->num_rows() > 0) {
            $data = [
                'row' => $ambildataproduk->row_array(),
                'datakadaluarsa' => $this->db->get_where('produk_tglkadaluarsa', ['sha1(kodebarcode)' => $kode])
            ];
            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-tasks"></i> Detail Stok Produk',
                'isi' => $this->load->view('admin/stokproduk/detail', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            redirect('stokproduk/index');
        }
    }

    function ambildataproduk_tglkadaluarsa()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/stokproduk/Modelstokproduktglkadaluarsa', 'stok');
            $kode = $this->input->post('kode', true);
            $list = $this->stok->get_datatables($kode);
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $no++;
                $row = array();

                $row[] = $no;
                $row[] = date('d-m-Y', strtotime($field->tglkadaluarsa));
                $row[] = number_format($field->jml, 0, ",", ".");
                $row[] = "";
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->stok->count_all($kode),
                "recordsFiltered" => $this->stok->count_filtered($kode),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    function hapusdatatglkadaluarsa()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $jml = $this->input->post('jml', true);

            if ($jml > 0) {
                $msg = [
                    'error' => 'Maaf tidak bisa dihapus, pastikan jumlahnya bernilai 0.'
                ];
            } else {
                $this->db->delete('produk_tglkadaluarsa', ['id' => $id]);
                $msg = [
                    'sukses' => 'Berhasil di hapus'
                ];
            }
            echo json_encode($msg);
        }
    }

    function hapusstok()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $this->db->delete('produk_tglkadaluarsa', ['id' => $id]);

            $msg = [
                'sukses' => 'Berhasil'
            ];
            echo json_encode($msg);
        }
    }

    function simpanbatchjml()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id', true);
            $jml = $this->input->post('jml', true);

            $jmldata = count($id);

            for ($i = 0; $i < $jmldata; $i++) {
                $updatedata = [
                    'jml' => $jml[$i]
                ];
                $this->db->where('id', $id[$i]);
                $this->db->update('produk_tglkadaluarsa', $updatedata);
            }

            $msg = [
                'sukses' => 'Data berhasil di update'
            ];
            echo json_encode($msg);
        }
    }

    function tambahstok()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);
            $data = [
                'kode' => $kode
            ];
            $msg = [
                'data' => $this->load->view('admin/stokproduk/modaltambahstok', $data, true)
            ];
            echo json_encode($msg);
        }
    }

    function simpanstokkadaluarsa()
    {
        if ($this->input->is_ajax_request()) {
            $kode = $this->input->post('kode', true);
            $tgl = $this->input->post('tgl', true);
            $jml = str_replace(".", "", $this->input->post('jml', true));

            $ambildataproduk = $this->db->get_where('produk', ['kodebarcode' => $kode])->row_array();

            $this->db->insert('produk_tglkadaluarsa', [
                'kodebarcode' => $kode,
                'tglkadaluarsa' => $tgl,
                'jml' => $jml,
                'hargabeli' => $ambildataproduk['harga_beli_eceran'],
                'hargajual' => $ambildataproduk['harga_jual_eceran'],
            ]);

            $msg = [
                'sukses' => 'Produk kadaluarsa berhasil ditambahkan'
            ];
            echo json_encode($msg);
        }
    }
}