<?php
class Modeldatareturn extends CI_Model
{
    // var $table = 'produk'; //nama tabel dari database
    var $column_order = array(null, 'blreturntgl', 'detfaktur', 'nama', null, null, null, null); //field yang ada di table user
    var $column_search = array('blreturntgl', 'detfaktur', 'nama', 'namaproduk', 'blreturnkodebarcode', 'nmstt'); //field yang diizin untuk pencarian 
    var $order = array('nofaktur' => 'desc'); // default order 

    private function _get_datatables_query()
    {

        $this->db->select('pembelian_return.*,pembelian_detail.*,status_return.*,pemasok.nama,produk.namaproduk')
            ->from('pembelian_return')
            ->join('pembelian_detail', 'detid = blreturndetid')
            ->join('produk', 'produk.`kodebarcode`=blreturnkodebarcode')
            ->join('pembelian', 'detfaktur=nofaktur')
            ->join('status_return', 'blreturnstatusid = status_return.id')
            ->join('pemasok', 'pemasok.`id`=pembelian.`idpemasok`');

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

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->select('pembelian_return.*,pembelian_detail.*,status_return.*,pemasok.nama,produk.namaproduk')
            ->from('pembelian_return')
            ->join('pembelian_detail', 'detid = blreturndetid')
            ->join('produk', 'produk.`kodebarcode`=blreturnkodebarcode')
            ->join('pembelian', 'detfaktur=nofaktur')
            ->join('status_return', 'blreturnstatusid = status_return.id')
            ->join('pemasok', 'pemasok.`id`=pembelian.`idpemasok`');
        return $this->db->count_all_results();
    }
}