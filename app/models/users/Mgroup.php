<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mgroup extends CI_Model
{
    var $table = 'group_user';
    var $id = 'id_group';

    var $column_search = array('kode_group', 'nama_group');

    public function _get_data_query()
    {
        $columnIndex = $_POST['order'][0]['column'];
        $columnName = $_POST['columns'][$columnIndex]['data'];
        $columnSortOrder = $_POST['order'][0]['dir'];
        $searchValue = $_POST['search']['value'];

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
    public function get_all()
    {
        $this->_get_data_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function count_all()
    {
        $this->db->from($this->table)->where(['status_data' => 1]);
        return $this->db->count_all_results();
    }
    public function count_filtered()
    {
        $this->_get_data_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function get_by_id($id)
    {
        return $this->db->from($this->table)->where('id_group', $id)->get()->row_array();
    }
    public function get_by_kode($kode, $id)
    {
        return $this->db->from($this->table)->where('kode_group', $kode)->where_not_in('id_group', $id)->count_all_results();
    }
    public function store($post)
    {
        $data = [
            'nama_group' => $post['nama'],
            'kode_group' => $post['kode']
        ];
        return $this->db->insert($this->table, $data);
    }
    public function update($post)
    {
        $data = [
            'nama_group' => $post['nama'],
            'kode_group' => $post['kode']
        ];
        return $this->db->where($this->id, $post['id'])->update($this->table, $data);
    }
    public function destroy($id)
    {
        return $this->db->where($this->id, $id)->update($this->table, ['status_data' => 0]);
    }
    public function autocomplete($search)
    {
        if (!empty($search)) :
            $this->db->like('nama_group', $search);
        endif;
        $this->db->limit(50);
        $this->db->from('group_user');
        return $this->db->get()->result_array();
    }
}

/* End of file Mgroup.php */
