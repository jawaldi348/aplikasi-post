<?php
class Modeldatasemuapembelian extends CI_Model
{
    // var $table = 'produk'; //nama tabel dari database
    var $column_order = array(null, 'nofaktur', 'tglbeli', 'nama', 'jenisbayar', null, null, null, null, null); //field yang ada di table user
    var $column_search = array('nofaktur', 'tglbeli', 'nama'); //field yang diizin untuk pencarian 
    var $order = array('tglbeli' => 'desc'); // default order 

    private function _get_datatables_query($tglawal, $tglakhir)
    {

        // $akses = $this->session->userdata('idgrup');
        // $username = $this->session->userdata('username');
        // if ($akses == 1) {
        //     $this->db->select('pembelian.*,pemasok.nama')
        //         ->from('pembelian')
        //         ->join('pemasok', 'pemasok.id=idpemasok');
        // } else {
        //     $this->db->select('pembelian.*,pemasok.nama')
        //         ->from('pembelian')
        //         ->join('pemasok', 'pemasok.id=idpemasok')
        //         ->where('userinput', $username);
        // }
        if ($tglawal == '' && $tglakhir == '') {
            $this->db->select('pembelian.*,pemasok.nama')
                ->from('pembelian')
                ->join('pemasok', 'pemasok.id=idpemasok');
        } else {
            $this->db->select('pembelian.*,pemasok.nama')
                ->from('pembelian')
                ->join('pemasok', 'pemasok.id=idpemasok')
                ->where("DATE_FORMAT(tglbeli,'%Y-%m-%d') >=", $tglawal)
                ->where("DATE_FORMAT(tglbeli,'%Y-%m-%d') <=", $tglakhir);
        }


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

    function get_datatables($tglawal, $tglakhir)
    {
        $this->_get_datatables_query($tglawal, $tglakhir);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($tglawal, $tglakhir)
    {
        $this->_get_datatables_query($tglawal, $tglakhir);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($tglawal, $tglakhir)
    {
        // $akses = $this->session->userdata('idgrup');
        // $username = $this->session->userdata('username');
        // if ($akses == 1) {
        //     $this->db->select('pembelian.*,pemasok.nama')
        //         ->from('pembelian')
        //         ->join('pemasok', 'pemasok.id=idpemasok');
        // } else {
        //     $this->db->select('pembelian.*,pemasok.nama')
        //         ->from('pembelian')
        //         ->join('pemasok', 'pemasok.id=idpemasok')
        //         ->where('userinput', $username);
        // }
        if ($tglawal == '' && $tglakhir == '') {
            $this->db->select('pembelian.*,pemasok.nama')
                ->from('pembelian')
                ->join('pemasok', 'pemasok.id=idpemasok');
        } else {
            $this->db->select('pembelian.*,pemasok.nama')
                ->from('pembelian')
                ->join('pemasok', 'pemasok.id=idpemasok')
                ->where("DATE_FORMAT(tglbeli,'%Y-%m-%d') >=", $tglawal)
                ->where("DATE_FORMAT(tglbeli,'%Y-%m-%d') <=", $tglakhir);
        }
        return $this->db->count_all_results();
    }
}