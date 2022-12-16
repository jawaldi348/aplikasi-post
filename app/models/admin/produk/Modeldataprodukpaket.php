<?php
class Modeldataprodukpaket extends CI_Model
{
    var $column_order = array(null, 'kodebarcode', 'namaproduk', 'satnama', null, null, null, null); //field yang ada di table user
    var $column_search = array('kodebarcode', 'namaproduk', 'satnama'); //field yang diizin untuk pencarian 
    var $order = array('namaproduk' => 'asc'); // default order 

    private function _get_datatables_query()
    {

        $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran')
            ->from('produk')
            ->join('kategori', 'produk.katid=kategori.katid', 'left')
            ->join('satuan', 'produk.satid=satuan.satid', 'left')
            ->where('stthapus', '0')
            ->where('produkpaket', '1');

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
        $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran')
            ->from('produk')
            ->join('kategori', 'produk.katid=kategori.katid', 'left')
            ->join('satuan', 'produk.satid=satuan.satid', 'left')
            ->where('stthapus', '0')
            ->where('produkpaket', '1');
        return $this->db->count_all_results();
    }
}