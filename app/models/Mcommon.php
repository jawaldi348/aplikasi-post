<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcommon extends CI_Model
{
    public function get_toko()
    {
        $sql = $this->db->where('id_toko', 1)->get('toko')->row_array();
        $data = [
            'toko' => $sql['nama_toko'],
            'logo' => $sql['logo_toko'] == '' || !file_exists('app_content/' . $sql['logo_toko']) ? '' : base_url() . 'app_content/' . $sql['logo_toko']
        ];
        return $data;
    }
}

/* End of file Mcommon.php */
