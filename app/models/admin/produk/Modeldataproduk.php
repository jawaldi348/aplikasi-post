<?php
class Modeldataproduk extends CI_Model
{
    // var $table = 'produk'; //nama tabel dari database
    var $column_order = array(null, null, 'kodebarcode', 'namaproduk', 'satnama', null, null, 'katnama', 'stok_tersedia', null); //field yang ada di table user
    var $column_search = array('kodebarcode', 'namaproduk', 'satnama'); //field yang diizin untuk pencarian 
    var $order = array('namaproduk' => 'asc'); // default order 

    private function _get_datatables_query($sortir, $kategori)
    {

        if ($sortir == "1") {
            $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran,harga_jual_grosir')
                ->from('produk')
                ->join('kategori', 'produk.katid=kategori.katid', 'left')
                ->join('satuan', 'produk.satid=satuan.satid', 'left')
                ->where('stthapus', '0')
                ->where('produkpaket', '0')
                ->where('stok_tersedia >', '0')
                ->where('kategori.katid', $kategori);
        } elseif ($sortir == "2") {
            $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran,harga_jual_grosir')
                ->from('produk')
                ->join('kategori', 'produk.katid=kategori.katid', 'left')
                ->join('satuan', 'produk.satid=satuan.satid', 'left')
                ->where('stthapus', '0')
                ->where('produkpaket', '0')
                ->where('stok_tersedia', '0')
                ->where('kategori.katid', $kategori);
        } elseif ($sortir == "3") {
            $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran,harga_jual_grosir')
                ->from('produk')
                ->join('kategori', 'produk.katid=kategori.katid', 'left')
                ->join('satuan', 'produk.satid=satuan.satid', 'left')
                ->where('stthapus', '0')
                ->where('produkpaket', '0')
                ->where('stok_tersedia <', '0')
                ->where('kategori.katid', $kategori);
        } else {
            if ($kategori == '') {
                $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran,harga_jual_grosir')
                    ->from('produk')
                    ->join('kategori', 'produk.katid=kategori.katid', 'left')
                    ->join('satuan', 'produk.satid=satuan.satid', 'left')
                    ->where('stthapus', '0')
                    ->where('produkpaket', '0');
            } else {
                $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran,harga_jual_grosir')
                    ->from('produk')
                    ->join('kategori', 'produk.katid=kategori.katid', 'left')
                    ->join('satuan', 'produk.satid=satuan.satid', 'left')
                    ->where('stthapus', '0')
                    ->where('produkpaket', '0')
                    ->where('kategori.katid', $kategori);
            }
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

    function get_datatables($sortir, $kategori)
    {
        $this->_get_datatables_query($sortir, $kategori);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($sortir, $kategori)
    {
        $this->_get_datatables_query($sortir, $kategori);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($sortir, $kategori)
    {
        if ($sortir == "1") {
            $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran,harga_jual_grosir')
                ->from('produk')
                ->join('kategori', 'produk.katid=kategori.katid', 'left')
                ->join('satuan', 'produk.satid=satuan.satid', 'left')
                ->where('stthapus', '0')
                ->where('produkpaket', '0')
                ->where('stok_tersedia >', '0')
                ->where('kategori.katid', $kategori);
        } elseif ($sortir == "2") {
            $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran,harga_jual_grosir')
                ->from('produk')
                ->join('kategori', 'produk.katid=kategori.katid', 'left')
                ->join('satuan', 'produk.satid=satuan.satid', 'left')
                ->where('stthapus', '0')
                ->where('produkpaket', '0')
                ->where('stok_tersedia', '0')
                ->where('kategori.katid', $kategori);
        } elseif ($sortir == "3") {
            $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran,harga_jual_grosir')
                ->from('produk')
                ->join('kategori', 'produk.katid=kategori.katid', 'left')
                ->join('satuan', 'produk.satid=satuan.satid', 'left')
                ->where('stthapus', '0')
                ->where('produkpaket', '0')
                ->where('stok_tersedia <', '0')
                ->where('kategori.katid', $kategori);
        } else {
            if ($kategori == '') {
                $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran,harga_jual_grosir')
                    ->from('produk')
                    ->join('kategori', 'produk.katid=kategori.katid', 'left')
                    ->join('satuan', 'produk.satid=satuan.satid', 'left')
                    ->where('stthapus', '0')
                    ->where('produkpaket', '0');
            } else {
                $this->db->select('produk.id,kodebarcode,namaproduk,kategori.katid,kategori.katnama,satuan.satnama,produk.stok_tersedia,harga_beli_eceran,harga_jual_eceran,harga_jual_grosir')
                    ->from('produk')
                    ->join('kategori', 'produk.katid=kategori.katid', 'left')
                    ->join('satuan', 'produk.satid=satuan.satid', 'left')
                    ->where('stthapus', '0')
                    ->where('produkpaket', '0')
                    ->where('kategori.katid', $kategori);
            }
        }
        return $this->db->count_all_results();
    }
    public function simpanproduk($a)
    {
        return $this->db->insert('produk', $a);
    }

    public function updateproduk($datasimpan, $id)
    {
        $this->db->where('sha1(id)', $id);
        return $this->db->update('produk', $datasimpan);
    }

    public function hapusproduk($id)
    {
        // $this->db->where('id', $id);
        // return $this->db->update('produk', [
        //     'stthapus' => 1,
        //     'tglhapus' => date('Y-m-d H:i:s')
        // ]);
        return $this->db->delete('produk', ['id' => $id]);
    }

    public function kembalikanproduk($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('produk', [
            'stthapus' => 0
        ]);
    }

    public function hapusprodukpermanen($id)
    {
        return $this->db->delete('produk', ['id' => $id]);
    }

    public function ambildataproduk_berdasarkankode($kode)
    {
        return $this->db->get_where('produk', ['kodebarcode' => $kode]);
    }

    public function detaildataproduk($id)
    {
        return $this->db->get_where('produk', ['sha1(id)' => $id]);
    }

    public function tampildatahargaproduk($idproduk)
    {
        return $this->db->query("SELECT produk_harga.*,satnama FROM produk_harga JOIN satuan ON satid=idsat WHERE idproduk = '$idproduk'");
    }

    public function tampildatahargadefault($idproduk)
    {
        return $this->db->query("SELECT produk_harga.*,satnama FROM produk_harga JOIN satuan ON satid=idsat WHERE idproduk = $idproduk AND defaultharga='Y'");
    }
}