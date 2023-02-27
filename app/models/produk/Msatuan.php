<?php
class Msatuan extends CI_Model
{
    var $table = 'produk_satuan';
    var $column_search = array('nama_satuan');

    public function _get_data_query()
    {
        $columnIndex = $_GET['order'][0]['column'];
        $columnName = $_GET['columns'][$columnIndex]['data'];
        $columnSortOrder = $_GET['order'][0]['dir'];
        $searchValue = $_GET['search']['value'];

        $this->db->from($this->table)->where(['default_satuan' => 0, 'status_data' => 1]);
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
        return $this->db->from($this->table)->where(['default_satuan' => 0, 'status_data' => 1])->count_all_results();
    }
    public function store($post)
    {
        $data = [
            'nama_satuan' => $post['nama'],
            'detail_satuan' => $post['detail']
        ];
        return $this->db->insert($this->table, $data);
    }
    public function get_by_id($id)
    {
        return $this->db->from($this->table)->where('id_satuan', $id)->get()->row_array();
    }
    public function update($post)
    {
        $data = [
            'nama_satuan' => $post['nama'],
            'detail_satuan' => $post['detail']
        ];
        return $this->db->where('id_satuan', $post['id'])->update($this->table, $data);
    }
    public function destroy($id)
    {
        return $this->db->where('id_satuan', $id)->update($this->table, ['status_data' => 0]);
    }
}
