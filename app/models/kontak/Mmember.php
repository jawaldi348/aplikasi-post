<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mmember extends CI_Model
{
    var $table = 'kontak_member';
    var $column_search = array('nama_member');

    public function _get_data_query()
    {
        $columnIndex = $_GET['order'][0]['column'];
        $columnName = $_GET['columns'][$columnIndex]['data'];
        $columnSortOrder = $_GET['order'][0]['dir'];
        $searchValue = $_GET['search']['value'];

        $this->db->from($this->table)->where(['status_data' => 1]);
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($searchValue) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $searchValue);
                } else {
                    $this->db->or_like($item, $searchValue);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }
        $this->db->order_by($columnName, $columnSortOrder);
    }
    public function fetch_all()
    {
        $this->_get_data_query();
        if ($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }
    public function count_filtered()
    {
        $this->_get_data_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all()
    {
        return $this->db->from($this->table)->where(['status_data' => 1])->count_all_results();
    }
    public function store($post)
    {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $post['kode']), array())->draw();
        $imagebarcodename = $post['kode'] . '.jpg';
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $file_path_year = './assets/barcode/member/' . $year . '/';
        $file_path_month = './assets/barcode/member/' . $year . '/' . $month . '/';
        $file_path_day = './assets/barcode/member/' . $year . '/' . $month . '/' . $day . '/';
        if (!is_dir($file_path_year))
            mkdir($file_path_year, 0755, true);
        if (!is_dir($file_path_month))
            mkdir($file_path_month, 0755, true);
        if (!is_dir($file_path_day))
            mkdir($file_path_day, 0755, true);
        $imagebarcodepath = './assets/barcode/member/' . $year . '/' . $month . '/' . $day . '/';
        $imagePath = $year . '/' . $month . '/' . $day . '/';
        imagejpeg($imageResource, $imagebarcodepath . $imagebarcodename);
        $pathbarcode = $imagePath . $imagebarcodename;

        $data = [
            'kode_member' => $post['kode'],
            'nama_member' => $post['nama'],
            'tempat_lahir' => $post['tempat_lahir'],
            'tanggal_lahir' => $post['tanggal'],
            'jenkel_member' => $post['jenkel'],
            'alamat_member' => $post['alamat'],
            'telp_member' => $post['telp'],
            'barcode_member' => $pathbarcode
        ];
        return $this->db->insert($this->table, $data);
    }
    public function get_by_id($id)
    {
        return $this->db->from($this->table)->where('kode_member', $id)->get()->row_array();
    }
    public function update($post)
    {
        $data = [
            'nama_member' => $post['nama'],
            'tempat_lahir' => $post['tempat_lahir'],
            'tanggal_lahir' => $post['tanggal'],
            'jenkel_member' => $post['jenkel'],
            'alamat_member' => $post['alamat'],
            'telp_member' => $post['telp']
        ];
        return $this->db->where('kode_member', $post['id'])->update($this->table, $data);
    }
    public function destroy($id)
    {
        return $this->db->where('kode_member', $id)->update($this->table, ['status_data' => 0]);
    }

    public function update_diskon_member($faktur, $kodemember)
    {
        $ambildatapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
        $jualdiskon = $ambildatapenjualan['jualdiskon'];

        $ambildatamember = $this->db->get_where('member', ['memberkode' => $kodemember])->row_array();
        $membertotaldiskon = $ambildatamember['membertotaldiskon'];

        $update_member = [
            'membertotaldiskon' => $jualdiskon + $membertotaldiskon
        ];

        $this->db->where('memberkode', $kodemember);
        $this->db->update('member', $update_member);
    }
}

/* End of file ModelName.php */
