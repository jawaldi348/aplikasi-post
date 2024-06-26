<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('check_logged_in')) {
    function check_logged_in()
    {
        $CI = &get_instance();
        $session = $CI->session->userdata('userData');
        $status = empty($session) ? '' : $session['status_login'];
        if ($status != 'success') :
            redirect('logout');
            exit;
        endif;
    }
}

if (!function_exists('iduser')) {
    function iduser()
    {
        $CI = &get_instance();
        $session = $CI->session->userdata('userData');
        return $session['iduser'];
    }
}
