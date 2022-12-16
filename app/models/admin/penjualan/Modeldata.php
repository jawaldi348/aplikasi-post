<?php
class Modeldata extends CI_Model
{
	var $column_order = array(null, 'jualfaktur', 'jualtgl', 'jualnapel', null, null, 'jualtotalbersih', 'jualuserinput', null); //field yang ada di table user
	var $column_search = array('jualfaktur', 'jualtgl', 'jualnapel', 'jualuserinput', 'membernama', 'jualmemberkode'); //field yang diizin untuk pencarian 
	var $order = array('jualtgl' => 'desc'); // default order 

	private function _get_datatables_query($tglawal, $tglakhir, $users)
	{

		$username = $this->session->userdata('username');
		$idgrup = $this->session->userdata('idgrup');
		// if ($idgrup == 1) {
		if ($tglawal == '' && $tglakhir == '' && $users == '') {
			$this->db->select('jualfaktur,jualtgl,jualtotalkotor,jualtotalbersih,jualstatusbayar,jualnapel,jualuserinput,jualstatuslunas,jualtglbayarkredit,jualmemberkode,membernama')
				->from('penjualan')
				->join('member', 'jualmemberkode=memberkode', 'left')
				->order_by('jualtgl', 'desc');
		} else {
			if ($users == "") {
				$this->db->select('jualfaktur,jualtgl,jualtotalkotor,jualtotalbersih,jualstatusbayar,jualnapel,jualuserinput,jualstatuslunas,jualtglbayarkredit,jualmemberkode,membernama')
					->from('penjualan')
					->join('member', 'jualmemberkode=memberkode', 'left')
					->where("DATE_FORMAT(jualtgl,'%Y-%m-%d') >=", $tglawal)
					->where("DATE_FORMAT(jualtgl,'%Y-%m-%d') <=", $tglakhir)
					->order_by('jualtgl', 'desc');
			} else {
				$this->db->select('jualfaktur,jualtgl,jualtotalkotor,jualtotalbersih,jualstatusbayar,jualnapel,jualuserinput,jualstatuslunas,jualtglbayarkredit,jualmemberkode,membernama')
					->from('penjualan')
					->join('member', 'jualmemberkode=memberkode', 'left')
					->where("DATE_FORMAT(jualtgl,'%Y-%m-%d') >=", $tglawal)
					->where("DATE_FORMAT(jualtgl,'%Y-%m-%d') <=", $tglakhir)
					->where('jualuserinput', $users)
					->order_by('jualtgl', 'desc');
			}
		}
		// } else {
		//     $this->db->select('jualfaktur,jualtgl,jualtotalkotor,jualtotalbersih,jualstatusbayar,jualnapel,jualuserinput,jualstatuslunas,jualtglbayarkredit,jualmemberkode,membernama')
		//         ->from('penjualan')
		//         ->join('member', 'jualmemberkode=memberkode', 'left')
		//         ->where('jualuserinput', $username)
		//         ->order_by('jualtgl', 'desc');
		// }

		// $this->db->from($this->table);

		$i = 0;

		foreach ($this->column_search as $item) // looping awal
		{
			if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
			{

				if ($i === 0) // looping awal
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
		}

		if (isset($_POST['order'])) {
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($tglawal, $tglakhir, $users)
	{
		$this->_get_datatables_query($tglawal, $tglakhir, $users);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($tglawal, $tglakhir, $users)
	{
		$this->_get_datatables_query($tglawal, $tglakhir, $users);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($tglawal, $tglakhir, $users)
	{
		$username = $this->session->userdata('username');
		$idgrup = $this->session->userdata('idgrup');
		// if ($idgrup == 1) {
		if ($tglawal == '' && $tglakhir == '' && $users == '') {
			$this->db->select('jualfaktur,jualtgl,jualtotalkotor,jualtotalbersih,jualstatusbayar,jualnapel,jualuserinput,jualstatuslunas,jualtglbayarkredit,jualmemberkode,membernama')
				->from('penjualan')
				->join('member', 'jualmemberkode=memberkode', 'left')
				->order_by('jualtgl', 'desc');
		} else {
			if ($users == "") {
				$this->db->select('jualfaktur,jualtgl,jualtotalkotor,jualtotalbersih,jualstatusbayar,jualnapel,jualuserinput,jualstatuslunas,jualtglbayarkredit,jualmemberkode,membernama')
					->from('penjualan')
					->join('member', 'jualmemberkode=memberkode', 'left')
					->where("DATE_FORMAT(jualtgl,'%Y-%m-%d') >=", $tglawal)
					->where("DATE_FORMAT(jualtgl,'%Y-%m-%d') <=", $tglakhir)
					->order_by('jualtgl', 'desc');
			} else {
				$this->db->select('jualfaktur,jualtgl,jualtotalkotor,jualtotalbersih,jualstatusbayar,jualnapel,jualuserinput,jualstatuslunas,jualtglbayarkredit,jualmemberkode,membernama')
					->from('penjualan')
					->join('member', 'jualmemberkode=memberkode', 'left')
					->where("DATE_FORMAT(jualtgl,'%Y-%m-%d') >=", $tglawal)
					->where("DATE_FORMAT(jualtgl,'%Y-%m-%d') <=", $tglakhir)
					->where('jualuserinput', $users)
					->order_by('jualtgl', 'desc');
			}
		}
		// } else {
		//     $this->db->select('jualfaktur,jualtgl,jualtotalkotor,jualtotalbersih,jualstatusbayar,jualnapel,jualuserinput,jualstatuslunas,jualtglbayarkredit,jualmemberkode,membernama')
		//         ->from('penjualan')
		//         ->where('jualuserinput', $username)
		//         ->join('member', 'jualmemberkode=memberkode', 'left')
		//         ->order_by('jualtgl', 'desc');
		// }
		return $this->db->count_all_results();
	}
}