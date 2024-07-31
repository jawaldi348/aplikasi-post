<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bisnis extends CI_Controller
{
    var $input;
    var $form_validation;
    var $upload;
    var $common;
    var $layouts;

    public function __construct()
    {
        parent::__construct();
        check_logged_in();

        $this->form_validation->set_message('required', errorRequired());
        $this->form_validation->set_error_delimiters(errorDelimiter(), errorDelimiter_close());
    }
    public function index()
    {
        $data = [
            'title' => 'Toko Anda',
            'data' => $this->common->get_bisnis()
        ];
        $this->load->view('settings/bisnis/index', $data);
    }
    public function edit()
    {
        $data = [
            'title' => 'Edit Toko Anda',
            'post' => 'profil-bisnis/update',
            'multipart' => 1,
            'data' => $this->common->get_bisnis()
        ];
        $this->layouts->modal_form('settings/bisnis/edit', $data);
    }
    public function update()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('bisnis', 'Nama toko', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('telp', 'No. Telepon', 'required');
        $this->form_validation->set_rules('phone', 'No. Handphone', 'required');
        $this->form_validation->set_rules('pemilik', 'Nama pemilik', 'required');
        $this->form_validation->set_rules('logo', 'Logo', 'callback_validate_image');
        if ($this->form_validation->run() == TRUE) {
            if (isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != "") :
                $post['name_file_foto'] = $_FILES['logo']['name'];
                $config['upload_path'] = './uploads/' . $this->directory_upload();
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('logo')) :
                    $data['upload_data'] = $this->upload->data('file_name');
                    $post['path_logo'] = $this->directory_upload() . $data['upload_data'];
                endif;
            else :
                $post['name_file_foto'] = '';
                $post['path_logo'] = '';
            endif;
            $this->common->update($post);
            $json = array(
                'status' => 'success',
                'message' => 'Toko Anda berhasil diperbaharui'
            );
        } else {
            $json['status'] = 'fail';
            foreach ($_POST as $key => $value) {
                $json['error'][$key] = form_error($key);
            }
            $json['error']['logo'] = form_error('logo');
        }
        echo json_encode($json);
    }
    public function validate_image()
    {
        $check = TRUE;
        // if ((!isset($_FILES['logo'])) || $_FILES['logo']['size'] == 0) {
        //     $this->form_validation->set_message('validate_image', '{field} tidak boleh kosong');
        //     $check = FALSE;
        if (isset($_FILES['logo']) && $_FILES['logo']['size'] != 0) {
            $allowedExts = array('gif', 'jpeg', 'jpg', 'png', 'JPG', 'JPEG', 'GIF', 'PNG');
            $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $detectedType = exif_imagetype($_FILES['logo']['tmp_name']);
            $type = $_FILES['logo']['type'];
            if (!in_array($detectedType, $allowedTypes)) {
                $this->form_validation->set_message('validate_image', 'Invalid Image Content!');
                $check = FALSE;
            }
            if (filesize($_FILES['logo']['tmp_name']) > 2097152) {
                $this->form_validation->set_message('validate_image', 'The Image file size shoud not exceed 2MB');
                $check = FALSE;
            }
            if (!in_array($extension, $allowedExts)) {
                $this->form_validation->set_message('validate_image', "Invalid file extension {$extension}");
                $check = FALSE;
            }
        }
        return $check;
    }
    public function directory_upload()
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $path_year = './uploads/' . $year . '/';
        $path_month = './uploads/' . $year . '/' . $month . '/';
        $path_day = './uploads/' . $year . '/' . $month . '/' . $day . '/';
        if (!is_dir($path_year))
            mkdir($path_year, 0755, true);
        if (!is_dir($path_month))
            mkdir($path_month, 0755, true);
        if (!is_dir($path_day))
            mkdir($path_day, 0755, true);
        return $year . '/' . $month . '/' . $day . '/';
    }
}

/* End of file Toko.php */
