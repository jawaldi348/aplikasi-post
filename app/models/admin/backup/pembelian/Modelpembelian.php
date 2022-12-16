<?php
class Modelpembelian extends CI_Model
{
    function ambildetailproduk($kode)
    {
        return $this->db->get_where('produk', ['kodebarcode' => $kode]);
    }
}