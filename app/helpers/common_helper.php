<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('bisnis')) {
    function bisnis()
    {
        $CI = &get_instance();
        $bisnis = $CI->common->get_bisnis();
        return $bisnis['bisnis'];
    }
}
if (!function_exists('logo')) {
    function logo()
    {
        $CI = &get_instance();
        $bisnis = $CI->common->get_bisnis();
        return $bisnis['logo'];
    }
}
