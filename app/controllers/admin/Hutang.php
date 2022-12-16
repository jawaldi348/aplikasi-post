<?php
class Hutang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library([
                'form_validation'
            ]);
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function data()
    {
        $view = [
            'menu' => $this->load->view('template/menu', '', TRUE),
            'judul' => '<i class="fa fa-list-alt"></i> Daftar Hutang Pembelian',
            'isi' => $this->load->view('admin/hutang/data', '', true)

        ];
        $this->parser->parse('template/main', $view);
    }

    public function ambildatahutang()
    {
        if ($this->input->is_ajax_request() == true) {
            $this->load->model('admin/hutang/Modeldatahutang', 'hutang');
            $list = $this->hutang->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $field) {
                $tombolaksi = "<button type=\"button\" title=\"Klik Tombol ini, Jika sudah dibayarkan\" class=\"btn btn-sm btn-outline-info\" onclick=\"bayar('" . sha1($field->nofaktur) . "')\">
                        Bayar
                    </button>";

                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $field->nofaktur;
                $row[] = date('d-m-Y', strtotime($field->tglbeli));
                $row[] = date('d-m-Y', strtotime($field->tgljatuhtempo));
                $row[] = number_format($field->totalbersih, 2, ",", ".");
                // mendapatkan jumlah item
                $query = $this->db->get_where('pembelian_detail', ['detfaktur' => $field->nofaktur])->result();
                $row[] = count($query);
                // end

                if ($field->tglpembayarankredit == '' || $field->tglpembayarankredit == '0000-00-00') {
                    $row[] = '';
                } else {
                    $row[] = date('d-m-Y', strtotime($field->tglpembayarankredit));
                }

                if ($field->jmlpembayarankredit == $field->totalbersih) {
                    $status = "<span class=\"badge badge-success\">Lunas</span>";
                } else {
                    $status = "<span class=\"badge badge-warning\" title=\"Jumlah Pembayarannya tidak sama dengan jumlah yang harus dibayarkan\">Belum Lunas</span>";
                }

                if ($field->statusbayar == '0') {
                    $row[] = "<span class=\"badge badge-danger\">Belum Bayar</span>";
                } else {
                    $row[] = "<span class=\"badge badge-success\">Sudah Bayar</span>" . " " . $status;
                }
                $row[] = $tombolaksi;
                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->hutang->count_all(),
                "recordsFiltered" => $this->hutang->count_filtered(),
                "data" => $data,
            );
            //output dalam format JSON
            echo json_encode($output);
        }
    }

    public function bayar()
    {
        $faktur_sha = $this->uri->segment('4');

        //ambildata pembelian
        $query_pembelian = $this->db->get_where('pembelian', ['sha1(nofaktur)' => $faktur_sha]);
        if ($query_pembelian->num_rows() > 0) {
            $row_pembelian = $query_pembelian->row_array();

            if ($row_pembelian['jmlpembayarankredit'] == 0 || $row_pembelian['jmlpembayarankredit'] == null) {
                $jmlpembayarankredit = 0;
            } else {
                $jmlpembayarankredit = $row_pembelian['jmlpembayarankredit'];
            }

            if ($row_pembelian['tglpembayarankredit'] == '' || $row_pembelian['tglpembayarankredit'] == '0000-00-00') {
                $tglpembayaran = date('Y-m-d');
            } else {
                $tglpembayaran = $row_pembelian['tglpembayarankredit'];
            }

            $data = [
                'nofaktur' => $row_pembelian['nofaktur'],
                'tglbeli' => $row_pembelian['tglbeli'],
                'tgljatuhtempo' => $row_pembelian['tgljatuhtempo'],
                'totalbersihx' => 'Rp. ' . number_format($row_pembelian['totalbersih'], 2, ".", ","),
                'totalbersih' => number_format($row_pembelian['totalbersih'], 2, ".", ""),
                'jmlbayar' => $jmlpembayarankredit,
                'jmlbayarx' => $jmlpembayarankredit,
                'tglbayar' => $tglpembayaran
            ];
            $view = [
                'menu' => $this->load->view('template/menu', '', TRUE),
                'judul' => '<i class="fa fa-list-alt"></i> Pembayaran Hutang Pembelian',
                'isi' => $this->load->view('admin/hutang/bayar', $data, true)

            ];
            $this->parser->parse('template/main', $view);
        } else {
            exit('Maaf data tidak ditemukan');
        }
    }

    public function simpanpembayaran()
    {
        if ($this->input->is_ajax_request() == true) {
            $nofaktur = $this->input->post('nofaktur', true);
            $totalbersih = $this->input->post('totalbersih', true);
            $tglbayar = $this->input->post('tglbayar', true);
            $jmlbayar = $this->input->post('jmlbayarx', true);

            $this->form_validation->set_rules('jmlbayar', 'Jumlah Bayar', 'trim|required', [
                'required' => '%s tidak boleh kosong'
            ]);


            if ($this->form_validation->run() == TRUE) {
                $dataupdate = [
                    'statusbayar' => 1,
                    'tglpembayarankredit' => $tglbayar,
                    'jmlpembayarankredit' => $jmlbayar
                ];
                $this->db->where('nofaktur', $nofaktur);
                $this->db->update('pembelian', $dataupdate);

                $msg = [
                    'sukses' => 'Pembayaran berhasil disimpan'
                ];
            } else {
                $msg = [
                    'error' => '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <strong>Error !</strong>' . validation_errors() . '
                                </div>'
                ];
            }
            echo json_encode($msg);
        }
    }
}