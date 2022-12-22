<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcommon extends CI_Model
{
    public function get_bisnis()
    {
        $sql = $this->db->where('id_bisnis', 1)->get('bisnis')->row_array();
        $data = [
            'toko' => $sql['nama_bisnis'],
            'logo' => $sql['logo_bisnis'] == '' || !file_exists('app_content/' . $sql['logo_bisnis']) ? '' : base_url() . 'app_content/' . $sql['logo_bisnis']
        ];
        return $data;
    }
}

/* End of file Mcommon.php */
