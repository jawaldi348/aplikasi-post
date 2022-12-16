<?php
class Modeltransaksineraca extends CI_Model
{
    // function kredit_persediaan_barang($tglfaktur, $subtotal)
    // {
    //     $inserdata = [
    //         'transtgl' => $tglfaktur,
    //         'transnoakun' => '1-110',
    //         'transjenis' => 'K',
    //         'transjml' => $subtotal,
    //     ];

    //     return $this->db->insert('neraca_transaksi', $inserdata);
    // }

    // function debit_persediaan_barang($tglfaktur, $subtotal)
    // {
    //     $inserdata = [
    //         'transtgl' => $tglfaktur,
    //         'transnoakun' => '1-110',
    //         'transjenis' => 'K',
    //         'transjml' => $subtotal,
    //     ];

    //     return $this->db->insert('neraca_transaksi', $inserdata);
    // }

    function kredit_hutang_dagang($faktur)
    {
        // ambil data pembelian
        $pembelian = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);
        $row = $pembelian->row_array();

        if ($row['jenisbayar'] == 'K') {
            $cek_neraca_transaksi = $this->db->get_where('neraca_transaksi', ['transno' => $faktur, 'transtgl' => $row['tglbeli'], 'transnoakun' => '2-110']);
            if ($cek_neraca_transaksi->num_rows() > 0) {
                $rr = $cek_neraca_transaksi->row_array();
                $update_neraca = [
                    'transjml' => $row['totalbersih']
                ];
                $this->db->where('transid', $rr['transid']);
                $this->db->update('neraca_transaksi', $update_neraca);
            } else {
                $insert_neraca = [
                    'transtgl' => $row['tglbeli'],
                    'transnoakun' => '2-110',
                    'transjenis' => 'K',
                    'transjml' => $row['totalbersih'],
                    'transno' => $faktur
                ];
                return $this->db->insert('neraca_transaksi', $insert_neraca);
            }
        } else {
            return false;
        }
    }

    function debit_hutang_dagang($faktur, $tglbayar, $jmlbayar)
    {
        $ambildatapembelian = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);
        $row = $ambildatapembelian->row_array();
        if ($row['statusbayar'] == 0) {
            $insertneraca = [
                'transno' => $faktur,
                'transtgl' => $tglbayar,
                'transnoakun' => '2-110',
                'transjenis' => 'D',
                'transjml' => $jmlbayar
            ];
            return $this->db->insert('neraca_transaksi', $insertneraca);
        } else {
            return false;
        }
    }

    function debit_persediaan_dagang($nofaktur, $tgl, $transjmlneraca)
    {
        $insert_neraca = [
            'transtgl' => $tgl,
            'transnoakun' => '1-160',
            'transjenis' => 'D',
            'transjml' => $transjmlneraca,
            'transno' => $nofaktur
        ];
        return $this->db->insert('neraca_transaksi', $insert_neraca);
    }

    function debit_persediaan_dagang_penjualan($nofaktur, $tgl, $transjmlneraca)
    {
        $cek_neraca  = $this->db->get_where('neraca_transaksi', ['transno' => $nofaktur, 'transtgl' => date('Y-m-d', strtotime($tgl)), 'transnoakun' => '1-160']);

        if ($cek_neraca->num_rows() > 0) {
            $row_neraca = $cek_neraca->row_array();
            $update_neraca = [
                'transjml' => $transjmlneraca
            ];
            $this->db->where('transid', $row_neraca['transid']);
            $this->db->update('neraca_transaksi', $update_neraca);
        } else {
            $insert_neraca = [
                'transtgl' => $tgl,
                'transnoakun' => '1-160',
                'transjenis' => 'D',
                'transjml' => $transjmlneraca,
                'transno' => $nofaktur
            ];
            return $this->db->insert('neraca_transaksi', $insert_neraca);
        }
    }
    function debit_persediaan_pulsa($nofaktur, $tgl, $transjmlneraca)
    {
        $cek_neraca  = $this->db->get_where('neraca_transaksi', ['transno' => $nofaktur, 'transtgl' => date('Y-m-d', strtotime($tgl)), 'transnoakun' => '1-161']);

        if ($cek_neraca->num_rows() > 0) {
            $row_neraca = $cek_neraca->row_array();
            $update_neraca = [
                'transjml' => $transjmlneraca
            ];
            $this->db->where('transid', $row_neraca['transid']);
            $this->db->update('neraca_transaksi', $update_neraca);
        } else {
            $insert_neraca = [
                'transtgl' => $tgl,
                'transnoakun' => '1-161',
                'transjenis' => 'D',
                'transjml' => $transjmlneraca,
                'transno' => $nofaktur
            ];
            return $this->db->insert('neraca_transaksi', $insert_neraca);
        }
    }

    function kredit_persediaan_dagang($nofaktur, $tgl, $transjmlneraca)
    {
        $insert_neraca = [
            'transtgl' => $tgl,
            'transnoakun' => '1-160',
            'transjenis' => 'K',
            'transjml' => $transjmlneraca,
            'transno' => $nofaktur
        ];
        return $this->db->insert('neraca_transaksi', $insert_neraca);
    }

    function kredit_piutang_dagang($faktur)
    {
        // ambil data penjualan
        $ambildatapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur]);
        $r_penjualan = $ambildatapenjualan->row_array();

        if ($r_penjualan['jualstatusbayar'] == 'K') {
            $cek_neraca_transaksi = $this->db->get_where('neraca_transaksi', ['transno' => $faktur, 'transtgl' => date('Y-m-d', strtotime($r_penjualan['jualtgl'])), 'transnoakun' => '1-130']);
            if ($cek_neraca_transaksi->num_rows() > 0) {
                $rr = $cek_neraca_transaksi->row_array();
                $update_neraca = [
                    'transjml' => $r_penjualan['jualtotalbersih']
                ];
                $this->db->where('transid', $rr['transid']);
                $this->db->update('neraca_transaksi', $update_neraca);
            } else {
                $insert_neraca = [
                    'transtgl' => date('Y-m-d', strtotime($r_penjualan['jualtgl'])),
                    'transnoakun' => '1-130',
                    'transjenis' => 'K',
                    'transjml' => $r_penjualan['jualtotalbersih'],
                    'transno' => $faktur
                ];
                return $this->db->insert('neraca_transaksi', $insert_neraca);
            }
        } else {
            return false;
        }
    }

    public function hapus_debit_persediaan_barang_dagang($faktur)
    {
        // Ambil data penjualan
        $ambildatapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur]);
        $row = $ambildatapenjualan->row_array();
        $tgljual = date('Y-m-d', strtotime($row['jualtgl']));
        $statusbayar = $row['jualstatusbayar'];

        return $this->db->delete('neraca_transaksi', [
            'transno' => $faktur,
            'transnoakun' => '1-160',
            'transjenis' => 'D'
        ]);
    }

    public function hapus_kredit_piutang_dagang($faktur)
    {
        // Ambil data penjualan
        $ambildatapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur]);
        $row = $ambildatapenjualan->row_array();
        $tgljual = date('Y-m-d', strtotime($row['jualtgl']));
        $statusbayar = $row['jualstatusbayar'];

        if ($statusbayar == 'K') {
            return $this->db->delete('neraca_transaksi', [
                'transno' => $faktur,
                'transnoakun' => '1-130',
                'transjenis' => 'K'
            ]);
        } else {
            return false;
        }
    }

    // Transaksi Pembelian
    function simpan_transaksi_pembelian($faktur)
    {
        $ambildata = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);
        $row = $ambildata->row_array();
        $jenisbayar = $row['jenisbayar'];
        $totalkotor = $row['totalkotor'];

        if ($jenisbayar == 'K') {
            $ambildata_neraca_transaksi_hutang = $this->db->get_where('neraca_transaksi', ['transno' => $faktur, 'transnoakun' => '2-110', 'transjenis' => 'K']);

            if ($ambildata_neraca_transaksi_hutang->num_rows() > 0) {
                $update_neraca = [
                    'transjml' => $row['totalbersih']
                ];
                $this->db->where('transno', $faktur);
                $this->db->where('transnoakun', '2-110');
                $this->db->update('neraca_transaksi', $update_neraca);
            } else {
                $insert_neraca = [
                    'transno' => $faktur,
                    'transtgl' => $row['tglbeli'],
                    'transnoakun' => '2-110',
                    'transjenis' => 'K',
                    'transjml' => $row['totalbersih']
                ];
                $this->db->insert('neraca_transaksi', $insert_neraca);
            }

            $ambildata_neraca_transaksi_persediaan = $this->db->get_where('neraca_transaksi', ['transno' => $faktur, 'transnoakun' => '1-160', 'transjenis' => 'K']);

            if ($ambildata_neraca_transaksi_persediaan->num_rows() > 0) {
                $update_neraca_persediaan = [
                    'transjml' => $row['totalkotor']
                ];
                $this->db->where('transno', $faktur);
                $this->db->where('transnoakun', '1-160');
                $this->db->update('neraca_transaksi', $update_neraca_persediaan);
            } else {
                $this->db->insert('neraca_transaksi', [
                    'transno' => $faktur,
                    'transtgl' => $row['tglbeli'],
                    'transnoakun' => '1-160',
                    'transjenis' => 'K',
                    'transjml' => $row['totalkotor']
                ]);
            }
        } else {
            $ambildata_neraca_transaksi_persediaan = $this->db->get_where('neraca_transaksi', ['transno' => $faktur, 'transnoakun' => '1-160', 'transjenis' => 'K']);

            if ($ambildata_neraca_transaksi_persediaan->num_rows() > 0) {
                $update_neraca_persediaan = [
                    'transjml' => $row['totalkotor']
                ];
                $this->db->where('transno', $faktur);
                $this->db->where('transnoakun', '1-160');
                $this->db->update('neraca_transaksi', $update_neraca_persediaan);
            } else {
                $this->db->insert('neraca_transaksi', [
                    'transno' => $faktur,
                    'transtgl' => $row['tglbeli'],
                    'transnoakun' => '1-160',
                    'transjenis' => 'K',
                    'transjml' => $row['totalkotor']
                ]);
            }
        }
    }

    function hapus_persediaan_dan_hutang($faktur)
    {
        $ambildata = $this->db->get_where('pembelian', ['nofaktur' => $faktur]);
        $row = $ambildata->row_array();
        $jenisbayar = $row['jenisbayar'];
        $totalkotor = $row['totalkotor'];

        if ($jenisbayar == 'K') {
            $this->db->delete('neraca_transaksi', [
                'transno' => $faktur,
                'transnoakun' => '2-110',
                'transjenis' => 'K'
            ]);
            $this->db->delete('neraca_transaksi', [
                'transno' => $faktur,
                'transnoakun' => '1-160',
                'transjenis' => 'K'
            ]);
        } else {
            $this->db->delete('neraca_transaksi', [
                'transno' => $faktur,
                'transnoakun' => '1-160',
                'transjenis' => 'K'
            ]);
        }
    }

    function hapus_hutang_dagang($faktur)
    {
        //cek dulu
        $cekneraca = $this->db->get_where('neraca_transaksi', [
            'transno' => $faktur,
            'transnoakun' => '2-110',
            'transjenis' => 'K'
        ]);

        if ($cekneraca->num_rows() > 0) {
            $this->db->delete('neraca_transaksi', [
                'transno' => $faktur,
                'transnoakun' => '2-110',
                'transjenis' => 'K'
            ]);

            // Tambahkan Persediaan barang dagang
            /*
            $ambil_datapembelian = $this->db->get_where('pembelian', [
                'nofaktur' => $faktur,
                'jenisbayar' => 'K',
                'statusbayar' => 1,
            ])->row_array();
            $totalkotor_pembelian = $ambil_datapembelian['totalkotor'];

            $this->db->insert('neraca_transaksi', [
                'transno' => $faktur,
                'transtgl' => $ambil_datapembelian['tglbeli'],
                'transnoakun' => '1-160',
                'transjenis' => 'K',
                'transjml' => $ambil_datapembelian['totalkotor'],
                'transket' => 'Pembayaran Hutang Dagang'
            ]);
            */
            // End Tambahkan Persediaan barang dagang
            return true;
        } else {
            return false;
        }
    }

    function simpan_return_pembelian($nofaktur, $id, $tglreturn, $transjml)
    {
        // Ambildata
        $data_pembelian = $this->db->get_where('pembelian', ['nofaktur' => $nofaktur]);
        $r = $data_pembelian->row_array();
        if ($r['jenisbayar'] == 'K' && $r['statusbayar'] == 0) {
            $update_neraca = [
                'transjml' => $r['totalbersih']
            ];
            $this->db->where('transno', $nofaktur);
            $this->db->where('transnoakun', '2-110');
            $this->db->where('transjenis', 'K');
            $this->db->update('neraca_transaksi', $update_neraca);
        }

        $this->db->insert('neraca_transaksi', [
            'transno' => $id,
            'transtgl' => $tglreturn,
            'transnoakun' => '1-160',
            'transjenis' => 'D',
            'transjml' => $transjml
        ]);
    }

    function hapus_return_pembelian($nofaktur, $id)
    {
        // Ambildata
        $data_pembelian = $this->db->get_where('pembelian', ['nofaktur' => $nofaktur]);
        $r = $data_pembelian->row_array();
        if ($r['jenisbayar'] == 'K' && $r['statusbayar'] == 0) {
            $update_neraca = [
                'transjml' => $r['totalbersih']
            ];
            $this->db->where('transno', $nofaktur);
            $this->db->where('transnoakun', '2-110');
            $this->db->where('transjenis', 'K');
            $this->db->update('neraca_transaksi', $update_neraca);
        }

        $this->db->delete('neraca_transaksi', [
            'transno' => $id,
            'transnoakun' => '1-160',
            'transjenis' => 'D',
        ]);
    }
    // End Transaksi Pembelian

    // Transaksi Penjualan
    public function simpan_neraca_penjualan($faktur, $total)
    {
        $penjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur]);
        $r = $penjualan->row_array();
        $totalbersih = $r['jualtotalbersih'];
        $totalkotor = $r['jualtotalkotor'];
        $jualmemberkode = $r['jualmemberkode'];
        $jualpembulatan = $r['jualpembulatan'];

        $ambiltotaldiskon_penjualan_detail = $this->db->query("SELECT SUM(detjualdiskon) AS totaldiskon FROM penjualan_detail WHERE detjualfaktur = '$faktur'")->row_array();

        // if (strlen($jualmemberkode) > 0) {
        //     $ambil_settingdiskon = $this->db->get('member_setting_diskon')->row_array();
        //     // $diskonpenjualan = $totalbersih * ($ambil_settingdiskon['diskon'] / 100);
        //     $diskonpenjualan = 0;
        // } else {

        //     // $diskonpenjualan = 0;
        // }
        $diskonpenjualan = ($totalkotor * $r['jualdispersen'] / 100) + $r['jualdisuang'] + $ambiltotaldiskon_penjualan_detail['totaldiskon'];

        // detail Penjualan
        $penjualandetail = $this->db->query("SELECT SUM(detjualjml*detjualhargabeli) AS hpp FROM penjualan_detail WHERE detjualfaktur='$faktur'");
        $rr = $penjualandetail->row_array();
        // End Detail Penjualan

        $cek_neraca_penjualan = $this->db->get_where('neraca_transaksi', [
            'transno' => $faktur, 'transnoakun' => '4-100',
            'transjenis' => 'K'
        ]);
        if ($cek_neraca_penjualan->num_rows() > 0) {
            $this->db->where('transno', $faktur);
            $this->db->where('transnoakun', '4-100');
            $this->db->where('transjenis', 'K');
            $this->db->update('neraca_transaksi', [
                // 'transjml' => $total + $diskonpenjualan,
                // 'transjml' => $total
                'transjml' => $jualpembulatan
            ]);
        } else {
            $this->db->insert('neraca_transaksi', [
                'transno' => $faktur,
                'transtgl' => $r['jualtgl'],
                'transnoakun' => '4-100',
                'transjenis' => 'K',
                'transjml' => $jualpembulatan
                // 'transjml' => $total
                // 'transjml' => $total + $diskonpenjualan
            ]);
        }

        $cek_neraca_diskonpenjualan = $this->db->get_where('neraca_transaksi', [
            'transno' => $faktur, 'transnoakun' => '4-110',
            'transjenis' => 'K'
        ]);

        if ($cek_neraca_diskonpenjualan->num_rows() > 0) {
            $this->db->where('transno', $faktur);
            $this->db->where('transnoakun', '4-110');
            $this->db->where('transjenis', 'K');
            $this->db->update('neraca_transaksi', [
                'transjml' => $diskonpenjualan
            ]);
        } else {
            $this->db->insert('neraca_transaksi', [
                'transno' => $faktur,
                'transtgl' => $r['jualtgl'],
                'transnoakun' => '4-110',
                'transjenis' => 'K',
                'transjml' => $diskonpenjualan
            ]);
        }

        $cek_neraca_hpp = $this->db->get_where('neraca_transaksi', [
            'transno' => $faktur, 'transnoakun' => '5-100',
            'transjenis' => 'K'
        ]);

        if ($cek_neraca_hpp->num_rows() > 0) {
            $this->db->where('transno', $faktur);
            $this->db->where('transnoakun', '5-100');
            $this->db->where('transjenis', 'K');
            $this->db->update('neraca_transaksi', [
                'transjml' => $rr['hpp']
            ]);
        } else {
            $this->db->insert('neraca_transaksi', [
                'transno' => $faktur,
                'transtgl' => $r['jualtgl'],
                'transnoakun' => '5-100',
                'transjenis' => 'K',
                'transjml' => $rr['hpp']
            ]);
        }
    }

    function hapus_neraca_penjualan($faktur)
    {
        $this->db->trans_start();
        $this->db->delete('neraca_transaksi', ['transno' => $faktur, 'transnoakun' => '1-160', 'transjenis' => 'D']);
        $this->db->delete('neraca_transaksi', ['transno' => $faktur, 'transnoakun' => '4-100', 'transjenis' => 'K']);
        $this->db->delete('neraca_transaksi', ['transno' => $faktur, 'transnoakun' => '4-110', 'transjenis' => 'K']);
        $this->db->delete('neraca_transaksi', ['transno' => $faktur, 'transnoakun' => '5-100', 'transjenis' => 'K']);
        $this->db->trans_complete();
    }
    // End Transaksi Penjualan

    function simpan_debit_kaskecil_return_penjualan_dan_hpp($idreturn, $tgl)
    {
        $ambildata_penjualan_return = $this->db->get_where('penjualan_return', ['returnid' => $idreturn])->row_array();
        $returndetjualid = $ambildata_penjualan_return['returndetjualid'];

        $ambildata_penjualan_detail = $this->db->get_where('penjualan_detail', ['detjualid' => $returndetjualid])->row_array();

        $detjualharga = $ambildata_penjualan_detail['detjualharga'];
        $detjualjmlreturn = $ambildata_penjualan_detail['detjualjmlreturn'];
        $detjualdiskon = $ambildata_penjualan_detail['detjualdiskon'];
        $subtotal = $detjualharga * $detjualjmlreturn - $detjualdiskon;

        $this->db->insert('neraca_transaksi', [
            'transno' => $idreturn,
            'transtgl' => $tgl,
            'transnoakun' => '1-110',
            'transjenis' => 'D',
            'transjml' => $subtotal,
            'transket' => 'Return produk penjualan'
        ]);

        // Neraca HPP
        $this->db->insert('neraca_transaksi', [
            'transno' => $idreturn,
            'transtgl' => $tgl,
            'transnoakun' => '5-100',
            'transjenis' => 'D',
            'transjml' => $subtotal
        ]);
        // End HPP
    }

    public function simpanantabunganmember($faktur, $diskonsetting)
    {
        $ambil_datapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
        $jualdiskon = $ambil_datapenjualan['jualdiskon'];
        $jualtgl = date('Y-m-d', strtotime($ambil_datapenjualan['jualtgl']));
        $jualtotalbersih = $ambil_datapenjualan['jualtotalbersih'];

        $cek_neraca_transaksi = $this->db->get_where('neraca_transaksi', [
            'transno' => $faktur,
            'transtgl' => $jualtgl,
            'transnoakun' => '2-130',
            'transjenis' => 'K'
        ]);

        if ($cek_neraca_transaksi->num_rows() > 0) {
            $row_cek_neraca_transaksi = $cek_neraca_transaksi->row_array();
            $transid = $row_cek_neraca_transaksi['transid'];
            $this->db->where('transid', $transid);
            $this->db->update('neraca_transaksi', [
                'transjml' => $jualtotalbersih * $diskonsetting / 100
            ]);
        } else {
            $this->db->insert('neraca_transaksi', [
                'transno' => $faktur,
                'transtgl' => $jualtgl,
                'transnoakun' => '2-130',
                'transjenis' => 'K',
                'transjml' => $jualtotalbersih * $diskonsetting / 100,
                'transket' => 'Transaksi Penjualan Member'
            ]);
        }
    }

    public function debit_simpanantabunganmember($faktur, $totalbersih)
    {
        $ambil_datapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
        $jualstatusbayar = $ambil_datapenjualan['jualstatusbayar'];
        $jualtgl = date('Y-m-d', strtotime($ambil_datapenjualan['jualtgl']));

        if ($jualstatusbayar == 'M') {
            $cek_neraca_transaksi = $this->db->get_where('neraca_transaksi', [
                'transno' => $faktur,
                'transtgl' => $jualtgl,
                'transnoakun' => '2-130',
                'transjenis' => 'D'
            ]);

            if ($cek_neraca_transaksi->num_rows() > 0) {
                $row_cek_neraca_transaksi = $cek_neraca_transaksi->row_array();
                $transid = $row_cek_neraca_transaksi['transid'];
                $this->db->where('transid', $transid);
                $this->db->update('neraca_transaksi', [
                    'transjml' => $totalbersih
                ]);
            } else {
                $this->db->insert('neraca_transaksi', [
                    'transno' => $faktur,
                    'transtgl' => $jualtgl,
                    'transnoakun' => '2-130',
                    'transjenis' => 'D',
                    'transjml' => $totalbersih,
                    'transket' => '-'
                ]);
            }
        }
    }

    function hapussimpanantabunganmember($faktur)
    {
        $ambil_datapenjualan = $this->db->get_where('penjualan', ['jualfaktur' => $faktur])->row_array();
        $jualstatusbayar = $ambil_datapenjualan['jualstatusbayar'];

        $jualtgl = date('Y-m-d', strtotime($ambil_datapenjualan['jualtgl']));

        if ($jualstatusbayar == 'M') {
            $cek_neraca_transaksi = $this->db->get_where('neraca_transaksi', [
                'transno' => $faktur,
                'transtgl' => $jualtgl,
                'transnoakun' => '2-130',
                'transjenis' => 'D'
            ]);
            if ($cek_neraca_transaksi->num_rows() > 0) {
                $row = $cek_neraca_transaksi->row_array();
                $this->db->delete('neraca_transaksi', [
                    'transid' => $row['transid'],
                ]);
            }
        } else {
            $cek_neraca_transaksi = $this->db->get_where('neraca_transaksi', [
                'transno' => $faktur,
                'transtgl' => $jualtgl,
                'transnoakun' => '2-130',
                'transjenis' => 'K'
            ]);

            if ($cek_neraca_transaksi->num_rows() > 0) {
                $row = $cek_neraca_transaksi->row_array();
                $this->db->delete('neraca_transaksi', [
                    'transid' => $row['transid'],
                ]);
            }
        }
    }

    public function simpanupdateneracabiaya($noakun, $faktur, $total, $tgl)
    {
        $cekneraca = $this->db->get_where('neraca_transaksi', [
            'transno' => $faktur,
            'transnoakun' => $noakun,
            'transjenis' => 'K'
        ]);

        if ($cekneraca->num_rows() > 0) {
            $r_neraca = $cekneraca->row_array();
            $transid = $r_neraca['transid'];

            $this->db->where('transid', $transid);
            $this->db->update('neraca_transaksi', [
                'transjml' => $total
            ]);
        } else {
            $this->db->insert('neraca_transaksi', [
                'transno' => $faktur,
                'transtgl' => $tgl,
                'transnoakun' => $noakun,
                'transjenis' => 'K',
                'transjml' => $total
            ]);
        }
    }

    public function kurangipersediaanbarangdagang_pemakaian($faktur, $total, $tgl)
    {
        $cekneraca = $this->db->get_where('neraca_transaksi', [
            'transno' => $faktur,
            'transnoakun' => '1-160',
            'transjenis' => 'D'
        ]);

        if ($cekneraca->num_rows() > 0) {
            $r_neraca = $cekneraca->row_array();
            $transid = $r_neraca['transid'];

            $this->db->where('transid', $transid);
            $this->db->update('neraca_transaksi', [
                'transjml' => $total
            ]);
        } else {
            $this->db->insert('neraca_transaksi', [
                'transno' => $faktur,
                'transtgl' => $tgl,
                'transnoakun' => '1-160',
                'transjenis' => 'D',
                'transjml' => $total,
                'transket' => 'Pemakaian Barang'
            ]);
        }
    }
}