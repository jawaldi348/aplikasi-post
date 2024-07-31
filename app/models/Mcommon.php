<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mcommon extends CI_Model
{
    public function get_bisnis()
    {
        $sql = $this->db->where('id_bisnis', 1)->get('bisnis')->row_array();
        $data = [
            'idbisnis' => $sql['id_bisnis'],
            'bisnis' => $sql['nama_bisnis'],
            'alamat' => $sql['alamat_bisnis'],
            'telp' => $sql['telp_bisnis'],
            'phone' => $sql['phone_bisnis'],
            'pemilik' => $sql['pemilik_bisnis'],
            'logo' => $sql['logo_bisnis'] == '' || !file_exists('uploads/' . $sql['logo_bisnis']) ? '' : base_url() . 'uploads/' . $sql['logo_bisnis']
        ];
        return $data;
    }
    public function update($post)
    {
        if ($post['path_logo'] != '') :
            $data = [
                'nama_bisnis' => $post['bisnis'],
                'alamat_bisnis' => $post['alamat'],
                'telp_bisnis' => $post['telp'],
                'phone_bisnis' => $post['phone'],
                'pemilik_bisnis' => $post['pemilik'],
                'logo_bisnis' => $post['path_logo']
            ];
        else :
            $data = [
                'nama_bisnis' => $post['bisnis'],
                'alamat_bisnis' => $post['alamat'],
                'telp_bisnis' => $post['telp'],
                'phone_bisnis' => $post['phone'],
                'pemilik_bisnis' => $post['pemilik']
            ];
        endif;
        return $this->db->where('id_bisnis', 1)->update('bisnis', $data);
    }
}

/* End of file Mcommon.php */
