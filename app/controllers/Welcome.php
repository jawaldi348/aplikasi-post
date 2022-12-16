<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function index()
	{
		if ($this->session->userdata('masuk') == true) {
			if ($this->session->userdata('idgrup') == '1') {
				redirect('admin/home/index');
			} else if ($this->session->userdata('idgrup') == '2') {
				redirect('k/home/index');
			} else {
				redirect('login/logout');
			}
		} else {
			redirect('login/logout');
		}
	}
}
