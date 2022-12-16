<?php
class Modelpenjualan extends CI_Model
{
    function caridata($cari)
    {
        $array_cari = [
            'jualfaktur' => $cari,
            'jualtgl' => $cari,
            'jualnapel' => $cari,
            'membernama' => $cari
        ];

        $this->db->join('member', 'memberkode = jualmemberkode', 'left');
        $this->db->or_like($array_cari);
        return $this->db->get('penjualan')->result();
    }

    function ambildetailpenjualan($faktur)
    {
        $sql = "SELECT * FROM penjualan_detail JOIN produk ON produk.`kodebarcode`=detjualkodebarcode JOIN satuan ON satuan.`satid`=detjualsatid WHERE detjualfaktur='$faktur' ORDER BY detjualid DESC";

        return $this->db->query($sql);
    }

    function ambildetailpenjualan_berdasarkanid($id)
    {
        $sql = "SELECT * FROM penjualan_detail JOIN produk ON produk.`kodebarcode`=detjualkodebarcode JOIN satuan ON satuan.`satid`=detjualsatid WHERE detjualid='$id'";

        return $this->db->query($sql);
    }
}