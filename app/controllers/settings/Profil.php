<?php
class Profil extends CI_Controller
{
    var $input;
    var $form_validation;
    var $upload;
    var $layouts;
    var $Muser;

    public function __construct()
    {
        parent::__construct();
        check_logged_in();

        $this->load->model('users/Muser');

        $this->form_validation->set_message('required', errorRequired());
        $this->form_validation->set_error_delimiters(errorDelimiter(), errorDelimiter_close());
    }
    public function index()
    {
        $data = [
            'title' => 'Profil',
            'data' => $this->Muser->get_by_param(iduser())
        ];
        $this->load->view('settings/profil/index', $data);
    }
    // Ganti Foto User
    public function change_image()
    {
        $data = [
            'title' => 'Ganti Foto Profil',
            'post' => 'profil/update-image',
            'multipart' => 1
        ];
        $this->layouts->modal_form('settings/profil/image', $data);
    }
    public function update_image()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('image', 'Foto', 'callback_validate_image');
        if ($this->form_validation->run() == TRUE) {
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") :
                $post['name_file_foto'] = $_FILES['image']['name'];
                $config['upload_path'] = './uploads/' . $this->directory_upload();
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('image')) :
                    $data['upload_data'] = $this->upload->data('file_name');
                    $post['path_foto'] = $this->directory_upload() . $data['upload_data'];
                endif;
            else :
                $post['name_file_foto'] = '';
                $post['path_foto'] = '';
            endif;

            $this->Muser->update_image($post);
            $json = array(
                'status' => 'success',
                'message' => 'Foto profil berhasil diganti'
            );
        } else {
            $json['status'] = 'fail';
            foreach ($post as $key => $value) {
                $json['error'][$key] = form_error($key);
            }
            $json['error']['image'] = form_error('image');
        }
        echo json_encode($json);
    }
    public function validate_image()
    {
        $check = TRUE;
        if ((!isset($_FILES['image'])) || $_FILES['image']['size'] == 0) {
            $this->form_validation->set_message('validate_image', '{field} tidak boleh kosong');
            $check = FALSE;
        } else if (isset($_FILES['image']) && $_FILES['image']['size'] != 0) {
            $allowedExts = array('gif', 'jpeg', 'jpg', 'png', 'JPG', 'JPEG', 'GIF', 'PNG');
            $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $detectedType = exif_imagetype($_FILES['image']['tmp_name']);
            $type = $_FILES['image']['type'];
            if (!in_array($detectedType, $allowedTypes)) {
                $this->form_validation->set_message('validate_image', 'Invalid Image Content!');
                $check = FALSE;
            }
            if (filesize($_FILES['image']['tmp_name']) > 2097152) {
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
    // Edit Profil
    public function change_profil()
    {
        $data = [
            'title' => 'Edit Profil',
            'post' => 'profil/update-profil',
            'data' => $this->Muser->get_by_param(iduser())
        ];
        $this->layouts->modal_form('settings/profil/profil', $data);
    }
    public function update_profil()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('nama', 'Nama lengkap', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|callback_username_check_blank|callback_username_check_duplicate[' . iduser() . ']');
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post(null, TRUE);
            $this->Muser->update_profil($post);
            $json = array(
                'status' => 'success',
                'message' => 'Profil berhasil dirubah'
            );
        } else {
            $json['status'] = 'fail';
            foreach ($_POST as $key => $value) {
                $json['error'][$key] = form_error($key);
            }
        }
        echo json_encode($json);
    }
    public function username_check_blank($str)
    {
        $pattern = '/ /';
        $result = preg_match($pattern, $str);
        if ($result) {
            $this->form_validation->set_message('username_check_blank', '%s tidak boleh memiliki spasi');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public function username_check_duplicate($username, $id)
    {
        $query = $this->Muser->get_by_username($username, $id);
        if ($query > 0) :
            $this->form_validation->set_message('username_check_duplicate', '%s sudah digunakan');
            return false;
        else :
            return true;
        endif;
    }
    // Ganti Password
    public function change_password()
    {
        $data = [
            'title' => 'Ganti Password',
            'post' => 'profil/update-password'
        ];
        $this->layouts->modal_form('settings/profil/password', $data);
    }
    public function update_password()
    {
        $post = $this->input->post(null, TRUE);
        $this->form_validation->set_rules('currentPassword', 'Password lama', 'required|callback_check_current_password');
        $this->form_validation->set_rules('NewPassword1', 'Password baru', 'required|min_length[5]');
        $this->form_validation->set_rules('NewPassword2', 'Ulangi password baru', 'required|min_length[5]|matches[NewPassword1]');
        if ($this->form_validation->run() == TRUE) {
            $this->Muser->update_password($post);
            $json = array(
                'status' => 'success',
                'message' => 'Silahkan login kembali'
            );
        } else {
            $json['status'] = 'fail';
            foreach ($_POST as $key => $value) {
                $json['error'][$key] = form_error($key);
            }
        }
        echo json_encode($json);
    }
    public function check_current_password($currentPassword)
    {
        if ($currentPassword == null) :
            $this->form_validation->set_message('check_current_password', 'Password lama tidak boleh kosong');
            return false;
        else :
            $check = $this->Muser->get_by_id(iduser());
            if (!password_verify($currentPassword, $check['password'])) :
                $this->form_validation->set_message('check_current_password', '%s tidak ditemukan');
                return false;
            else :
                return true;
            endif;
        endif;
    }
}
