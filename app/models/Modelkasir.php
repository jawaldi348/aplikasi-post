<?php
class Modelkasir extends CI_Model
{
    function tampildatatemp($faktur)
    {
        $username = $this->session->userdata('username');
        $sql = "SELECT detjualid AS id, detjualfaktur AS faktur,detjualkodebarcode AS kode,produk.`namaproduk` AS namaproduk,detjualsatid AS idsatuan, satuan.`satnama` AS namasatuan,detjualsatqty AS satqty, detjualjml AS jml,detjualharga AS harga,detjualsubtotal AS subtotal,detjualdispersen AS dispersen, detjualdisuang AS disuang,detjualdiskon,detjualsubtotalkotor FROM temp_jual JOIN produk ON temp_jual.`detjualkodebarcode`=produk.`kodebarcode` JOIN satuan ON temp_jual.`detjualsatid`=satuan.`satid` WHERE detjualfaktur='$faktur' and detjualuserinput = '$username' order by detjualid desc";

        return $this->db->query($sql);
    }

    function cekproduk($kode, $namaproduk)
    {
        if (strlen($namaproduk) > 0) {
            $sql = "SELECT * FROM produk JOIN satuan on satuan.`satid`=produk.`satid` WHERE kodebarcode = '$kode' and namaproduk = '$namaproduk'";
        } else {
            $sql = "SELECT * FROM produk JOIN satuan on satuan.`satid`=produk.`satid` WHERE kodebarcode LIKE '%$kode%' OR namaproduk LIKE '%$kode%'";
        }
        return $this->db->query($sql);
    }
}