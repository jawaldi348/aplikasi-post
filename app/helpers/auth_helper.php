<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('check_logged_in')) {
    function check_logged_in()
    {
        $CI = &get_instance();
        if ($CI->session->userdata('masuk') != true) :
            redirect('logout');
            exit;
        endif;
    }
}
