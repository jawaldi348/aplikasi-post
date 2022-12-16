<?php
class Modelpembelian extends CI_Model
{
    public function tampilnotifhutangjatuhtempo()
    {
        $akses = $this->session->userdata('idgrup');
        $username = $this->session->userdata('username');

        if ($akses == 1) {
            $sql = $this->db->query("SELECT *,pemasok.`nama` AS namapemasok FROM pembelian JOIN pemasok on pemasok.`id`=pembelian.`idpemasok` WHERE (DATEDIFF(tgljatuhtempo,CURDATE()) BETWEEN 0 AND 3) AND jenisbayar='K' AND statusbayar = 0");
        } else {
            $sql = $this->db->query("SELECT *,pemasok.`nama` AS namapemasok FROM pembelian JOIN pemasok on pemasok.`id`=pembelian.`idpemasok` WHERE (DATEDIFF(tgljatuhtempo,CURDATE()) BETWEEN 0 AND 3) AND jenisbayar='K' AND statusbayar = 0 AND userinput = '$username'");
        }

        return $sql;
    }

    public function datahutang()
    {
        $akses = $this->session->userdata('idgrup');
        $username = $this->session->userdata('username');

        if ($akses == 1) {
            $sql = $this->db->query("SELECT * FROM pembelian WHERE jenisbayar='K' and statusbayar=0");
        } else {
            $sql = $this->db->query("SELECT * FROM pembelian WHERE jenisbayar='K' and statusbayar=0 and userinput='$username'");
        }

        return $sql;
    }

    public function querydetailpembelian($faktur)
    {
        return $this->db->query("SELECT detid,detkodebarcode,namaproduk,detjml,detjmlreturn,satnama,dethrgbeli,detsubtotal,dettglexpired,dethrgjual FROM pembelian_detail JOIN produk ON detkodebarcode=kodebarcode
        JOIN satuan ON satuan.satid=detsatid WHERE detfaktur='$faktur'");
    }

    public function caridatapembelian($cari)
    {
        $array_cari = [
            'nofaktur' => $cari,
            'tglbeli' => $cari,
            'nama' => $cari
        ];

        $this->db->join('pemasok', 'pemasok.id = idpemasok');
        $this->db->or_like($array_cari);
        return $this->db->get('pembelian')->result();
    }

    public function ambildata_detailpembelian_pemasok($id)
    {
        $sql = "SELECT pembelian_detail.*,pemasok.`nama`,pembelian.*,produk.`namaproduk`,
        satuan.`satnama` FROM pembelian_detail JOIN pembelian ON pembelian.`nofaktur`=pembelian_detail.`detfaktur` JOIN pemasok ON pemasok.`id`=idpemasok
        JOIN produk ON produk.`kodebarcode` = pembelian_detail.`detkodebarcode` JOIN satuan ON satuan.`satid` = pembelian_detail.`detsatid`
        WHERE detid=?";
        return $this->db->query($sql, [$id]);
    }
}