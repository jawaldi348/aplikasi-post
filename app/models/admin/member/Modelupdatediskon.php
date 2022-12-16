<?php
class Modelupdatediskon extends CI_Model
{
    public function updatediskonmember($kodemember, $jualfaktur, $total_detailpenjualan)
    {
        $ambil_datamember = $this->db->get_where('member', ['memberkode' => $kodemember])->row_array();
        $membertotaldiskon = $ambil_datamember['membertotaldiskon'];

        $ambil_datamembersettingdiskon = $this->db->get('member_setting_diskon')->row_array();
        $diskon_setting = $ambil_datamembersettingdiskon['diskon'];

        $ambil_datapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $jualfaktur])->row_array();
        $hitung_diskonlama = $ambil_datapenjualan['jualtotalbersih'] * ($diskon_setting / 100);

        $hitung_tabungandiskonmember = $total_detailpenjualan * ($diskon_setting / 100);

        $this->db->where('memberkode', $kodemember);
        return $this->db->update('member', [
            'membertotaldiskon' => ($membertotaldiskon - $hitung_diskonlama) + $hitung_tabungandiskonmember
        ]);
    }

    public function update_tabungandiskon_member($kodemember, $diskon_setting, $faktur, $totalbersih)
    {
    }
}