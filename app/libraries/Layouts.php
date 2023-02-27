<?php
class Layouts
{
    protected $_ci;
    function __construct()
    {
        $this->_ci = &get_instance();
    }
    function modal_form($form, $data)
    {
        $data['body'] = $this->_ci->load->view($form, $data, true);
        $this->_ci->load->view('layouts/modal/form', $data);
    }
}
