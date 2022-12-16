<?php
class Member extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library(array(
                'form_validation'
            ));
            $this->load->library('Zend');
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-users"></i> Daftar Member',
            'isi' => $this->load->view('admin/member/index', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    function getdatamemberapi()
    {
        if ($this->input->is_ajax_request()) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'http://kp-ridisdiksumbar.com/api_anggota/anggota');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $content = curl_exec($ch);

            curl_close($ch);

            $result = json_decode($content, true);

            // var_dump($result['data'][0]);

            $sukses_jumlah = 0;
            $error_jumlah = 0;
            foreach ($result['data'] as $m) :
                $cekmember = $this->db->get_where('member', ['memberkode' => $m['anggotakode']]);
                if ($cekmember->num_rows() > 0) {
                    ++$error_jumlah;
                } else {
                    $this->db->insert('member', [
                        'memberkode' => $m['anggotakode'],
                        'membernama' => $m['anggotanama'],
                        'memberinstansi' => $m['anggotainstansi'],
                        'memberalamat' => $m['anggotaalamat']
                    ]);
                    ++$sukses_jumlah;
                }
            endforeach;

            echo '<div class="alert alert-info" role="alert">
            <ul>
                <li>' . $sukses_jumlah . ' data berhasil di tambahkan</li>
                <li>' . $error_jumlah . ' data sudah terdaftar sebagai member </li>
            </ul></div><br>';
            echo "<button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"window.location.reload();\">Refresh Halaman</button>";
        }
    }

    public function detail()
    {
        $kode = $this->uri->segment('4');

        $cekdata = $this->db->get_where('member', ['sha1(memberkode)' => $kode]);


        if ($cekdata->num_rows() > 0) {
            $row = $cekdata->row_array();
            //Membuat Barcode
            $this->zend->load('Zend/Barcode');
            $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $row['memberkode']), array())->draw();
            $imagebarcodename = $row['memberkode'] . '.jpg';
            $imagebarcodepath = './assets/images/barcodemember/';
            imagejpeg($imageResource, $imagebarcodepath . $imagebarcodename);
            $pathbarcode = $imagebarcodepath . $imagebarcodename;

            $update_path_barcode_member = ['memberpathbarcode' => $pathbarcode];
            $this->db->where('memberkode', $row['memberkode']);
            $this->db->update('member', $update_path_barcode_member);
            //end

            $data = [
                'row' => $row,
                'pathbarcode' => $pathbarcode
            ];
            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-id-card"></i> Detail Member',
                'isi' => $this->load->view('admin/member/detail', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            exit('data tidak ditemukan...');
        }
    }

    public function cetak_kartu_anggota($kode)
    {
        $kode = $this->uri->segment('4');

        $cekdata = $this->db->get_where('member', ['sha1(memberkode)' => $kode]);

        if ($cekdata->num_rows() > 0) {
            $data = [
                'row' => $cekdata->row_array()
            ];
            $this->load->view('admin/member/cetakkartuanggota', $data);
        } else {
            exit('data tidak ditemukan...');
        }
    }

    public function ambildata()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/member/Modelmember', 'member');
            $list = $this->member->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $tombolhapus = "<button type=\"button\" title=\"Hapus Data\" class=\"btn btn-sm btn-outline-danger\" onclick=\"hapus($field->memberkode)\">
                        <i class=\"fa fa-trash-alt\"></i>
                    </button>";

                $tomboledit = "<button type=\"button\" title=\"Edit Data\" class=\"btn btn-sm btn-outline-info\" onclick=\"edit($field->memberkode)\">
                    <i class=\"fa fa-tags\"></i>
                </button>";

                $tomboldetail = "<button type=\"button\" title=\"Detail Data\" class=\"btn btn-sm btn-outline-primary\" onclick=\"detaildata('" . sha1($field->memberkode) . "')\">
                    <i class=\"fa fa-id-card\"></i>
                </button>";
                $no++;
                $row = array();
                // if ($field->id == 1) {
                //     $row[] = "";
                // } else {
                //     $row[] = "<input type='checkbox' class='check-item' name='id[]' value='" . $field->id . "'>";
                // }

                $row[] = $no;
                $row[] = $field->memberkode;
                $row[] = $field->membernama;
                $row[] = $field->memberinstansi;
                $row[] = $field->memberalamat;
                // $row[] = number_format($field->membertotaldiskon, 0, ",", ".");
                $row[] = $tombolhapus . '&nbsp;' . $tomboledit . '&nbsp;' . $tomboldetail;
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

    public function tambah()
    {
        if ($this->input->is_ajax_request() == true) {
            $kodeMember = rand(1, 999999);
            $data = [
                'kodemember' => $kodeMember
            ];
            $this->load->view('admin/member/formtambah', $data);
        }
    }

    public function edit()
    {
        if ($this->input->is_ajax_request() == true) {
            $kode = $this->input->post('kode', true);

            $detaildata = $this->db->get_where('member', ['memberkode' => $kode]);
            $r = $detaildata->row_array();

            $data = [
                'kode' => $kode,
                'nama' => $r['membernama'],
                'telp' => $r['membertelp'],
                'alamat' => $r['memberalamat'],
                'tmplahir' => $r['membertmplahir'],
                'tgllahir' => $r['membertgllahir'],
                'jenkel' => $r['memberjenkel'],
            ];

            $this->load->view('admin/member/formedit', $data);
        }
    }

    public function simpandata()
    {
        if ($this->input->is_ajax_request() == true) {
            $kode = $this->input->post('kode', true);
            $nama = $this->input->post('nama', true);
            $alamat = $this->input->post('alamat', true);
            $telp = $this->input->post('telp', true);
            $tmp = $this->input->post('tmp', true);
            $tgl = $this->input->post('tgl', true);
            $jenkel = $this->input->post('jenkel', true);


            $this->form_validation->set_rules('kode', 'Kode Member', 'trim|required|is_unique[member.memberkode]', [
                'required' => 'Setidaknya %s tidak boleh kosong',
                'is_unique' => '%s sudah ada, silahkan coba dengan kode yang lain'
            ]);
            $this->form_validation->set_rules('nama', 'Nama Member', 'trim|required', [
                'required' => 'Setidaknya %s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $simpandata = [
                    'memberkode' => $kode,
                    'membernama' => $nama,
                    'memberalamat' => $alamat,
                    'membertelp' => $telp,
                    'membertmplahir' => $tmp,
                    'membertgllahir' => $tgl,
                    'memberjenkel' => $jenkel
                ];

                $this->db->insert('member', $simpandata);

                $msg = [
                    'sukses' => 'Member berhasil ditambahkan'
                ];
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong>Error !</strong> 
                                <p>' . validation_errors() . '</p>
                            </div>'
                ];
            }

            echo json_encode($msg);
        }
    }
    public function updatedata()
    {
        if ($this->input->is_ajax_request() == true) {
            $nama = $this->input->post('nama', true);
            $alamat = $this->input->post('alamat', true);
            $telp = $this->input->post('telp', true);

            $kode = $this->input->post('kode', true);

            $data = [
                'membernama' => $nama,
                'memberalamat' => $alamat,
                'membertelp' => $telp,
                'membertmplahir' => $tmp,
                'membertgllahir' => $tgl,
                'memberjenkel' => $jenkel
            ];
            $this->db->where('memberkode', $kode);
            $this->db->update('member', $data);

            $msg = [
                'sukses' => 'Member dengan kode <strong>' . $kode . '</strong> berhasil diupdate'
            ];

            echo json_encode($msg);
        }
    }

    public function hapus()
    {
        if ($this->input->is_ajax_request() == true) {
            $kode = $this->input->post('kode', true);

            $this->db->delete('member', ['memberkode' => $kode]);
            $msg = [
                'sukses' => 'Member dengan kode <strong>' . $kode . '</strong> berhasil dihapus'
            ];

            echo json_encode($msg);
        }
    }
}