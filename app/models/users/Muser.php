<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Muser extends CI_Model
{
    var $table = 'users';
    var $id = 'id_user';

    var $column_search = array('nama_user', 'username');

    public function _get_data_query()
    {
        $columnIndex = $_POST['order'][0]['column'];
        $columnName = $_POST['columns'][$columnIndex]['data'];
        $columnSortOrder = $_POST['order'][0]['dir'];
        $searchValue = $_POST['search']['value'];

        $this->db->from($this->table)->join('group_user', 'id_group=idgroup_user')->where(['status_data' => 1]);
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
        return $this->db->from($this->table)->join('group_user', 'id_group=idgroup_user')->where('id_user', $id)->get()->row_array();
    }
    public function get_by_username($username, $id)
    {
        return $this->db->from('users')->where('username', $username)->where_not_in('id_user', $id)->count_all_results();
    }
    public function store($post)
    {
        $data = [
            'nama_user' => $post['nama'],
            'username' => $post['username'],
            'password' => password_hash($post['password'], PASSWORD_BCRYPT),
            'idgroup_user' => $post['group'],
            'aktif_user' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        return $this->db->insert($this->table, $data);
    }
    public function update($post)
    {
        if (empty($post['password'])) {
            $data = [
                'nama_user' => $post['nama'],
                'username' => $post['username'],
                'idgroup_user' => $post['group']
            ];
        } else {
            $data = [
                'nama_user' => $post['nama'],
                'username' => $post['username'],
                'password' => password_hash($post['password'], PASSWORD_BCRYPT),
                'idgroup_user' => $post['group']
            ];
        }
        return $this->db->where($this->id, $post['id'])->update($this->table, $data);
    }
    public function destroy($id)
    {
        return $this->db->where($this->id, $id)->update($this->table, ['status_data' => 0]);
    }
}

/* End of file Muser.php */
