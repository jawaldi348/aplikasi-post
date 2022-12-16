<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Balance Sheet - Kopmart</title>
    <style>
    #data td {
        padding-left: 3px;
        padding-right: 3px;
    }
    </style>
</head>

<body onload="window.print();">
    <table style="width: 90%; border-collapse: collapse;" border="1" align="center">
        <tr>
            <td style="text-align: center;">
                <table align="center" style="width: 90%; font-size:11pt;">
                    <tr>
                        <td style="width:10%">
                            <img style="width: 30%;" src="<?= base_url('assets/images/LOGO_KOPMART.png') ?>" alt="">
                        </td>
                        <td style="text-align: center;">
                            <span
                                style="font-size:12pt; font-weight: bold;"><?= strtoupper("kopmart-disdik sumbar<br>balance sheet"); ?><br><?= "Bulan :" . $bulan; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: center;">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: left;">
                            <table style="width: 100%; border-collapse: collapse;" border="1" id="data">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th>No.Perkiraan</th>
                                        <th>Nama Perkiraan</th>
                                        <th>Awal</th>
                                        <th>Akhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_awal_akun1 = 0;
                                    $total_akhir_akun1 = 0;
                                    foreach ($dataakun1->result_array() as $a1) :
                                        $noakun = $a1['noakun'];
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                                if ($a1['kat'] == '0') {
                                                    echo "<strong>$a1[noakun]</strong>";
                                                } else {
                                                    echo "$a1[noakun]";
                                                }
                                                ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($a1['kat'] == '0') {
                                                    echo "<strong>$a1[namaakun]</strong>";
                                                } else {
                                                    echo "$a1[namaakun]";
                                                }
                                                ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php
                                                $query_awal = $this->db->query("SELECT IFNULL(jmlsetdef,0) AS jmlawal FROM neraca_akun WHERE DATE_FORMAT(`tglsetdef`,'%Y-%m') < '$bulan' AND noakun='$noakun'");
                                                $r_query_awal = $query_awal->row_array();

                                                $x_awal = $r_query_awal['jmlawal'];

                                                $query_awal_lagi = $this->db->query("SELECT 
                                                CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                                CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                                FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun' AND DATE_FORMAT(transtgl,'%Y-%m') < '$bulan' ORDER BY transtgl ASC");

                                                if ($query_awal_lagi->num_rows() > 0) {
                                                    $saldo_akhir_lama = 0;
                                                    foreach ($query_awal_lagi->result_array() as $yy) :
                                                        $saldo_akhir_lama = ($saldo_akhir_lama + $yy['masuk']) - $yy['keluar'];
                                                    endforeach;
                                                } else {
                                                    $saldo_akhir_lama = 0;
                                                }

                                                $awal_baru = $saldo_akhir_lama + $x_awal;


                                                if ($a1['kat'] == '1') {
                                                    if ($a1['noakun'] == '1-110') {
                                                        $query_awal_lagi_kaskecil = $this->db->query("SELECT 
                                    CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                    CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                    FROM `neraca_transaksi` a WHERE a.`transnoakun` = '1-110' AND DATE_FORMAT(transtgl,'%Y-%m') < '$bulan' ORDER BY transtgl ASC");

                                                        if ($query_awal_lagi_kaskecil->num_rows() > 0) {
                                                            $saldo_akhir_lama_kaskecil = 0;
                                                            foreach ($query_awal_lagi_kaskecil->result_array() as $ykas) :
                                                                $saldo_akhir_lama_kaskecil = ($saldo_akhir_lama_kaskecil + $ykas['masuk']) - $ykas['keluar'];
                                                            endforeach;
                                                        } else {
                                                            $saldo_akhir_lama_kaskecil = 0;
                                                        }
                                                        echo number_format($saldo_akhir_lama_kaskecil, 0, ",", ".");
                                                        $total_awal_akun1 = $total_awal_akun1 + $saldo_akhir_lama_kaskecil;
                                                    } else {
                                                        echo number_format($awal_baru, 0, ",", ".");
                                                        $total_awal_akun1 = $total_awal_akun1 + $awal_baru;
                                                    }
                                                } else {
                                                    echo '';
                                                }
                                                ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php
                                                $query_akhir = $this->db->query("SELECT 
                                                    CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                                    CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                                    FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun' AND DATE_FORMAT(transtgl,'%Y-%m') = '$bulan' ORDER BY transtgl ASC");

                                                if ($query_akhir->num_rows() > 0) {
                                                    $saldo_akhir = 0;
                                                    foreach ($query_akhir->result_array() as $akhir) :
                                                        $saldo_akhir = ($saldo_akhir + $akhir['masuk']) - $akhir['keluar'];
                                                    endforeach;
                                                } else {
                                                    $saldo_akhir = 0;
                                                }

                                                if ($a1['kat'] == '1') {
                                                    if ($a1['noakun'] == '1-110') {
                                                        $saldo_akhir_kaskecil = $saldo_akhir + $saldo_akhir_lama_kaskecil;
                                                        echo number_format($saldo_akhir_kaskecil, 0, ",", ".");
                                                        $total_akhir_akun1 = $total_akhir_akun1 + $saldo_akhir_kaskecil;
                                                    } else {
                                                        echo number_format($saldo_akhir, 0, ",", ".");
                                                        $total_akhir_akun1 = $total_akhir_akun1 + $saldo_akhir;
                                                    }
                                                } else {
                                                    echo '';
                                                }
                                                ?>
                                        </td>
                                    </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                    <tr>
                                        <th colspan="2" style="text-align: center;">Jumlah</th>
                                        <td style="text-align: right; font-weight: bold;">
                                            <?= number_format($total_awal_akun1, 0, ",", "."); ?>
                                        </td>
                                        <td style="text-align: right; font-weight: bold;">
                                            <?= number_format($total_akhir_akun1, 0, ",", "."); ?>
                                        </td>
                                    </tr>


                                    <?php
                                    $total_awal_akun2 = 0;
                                    $total_akhir_akun2 = 0;
                                    foreach ($dataakun2->result_array() as $a2) :
                                        $noakun2 = $a2['noakun'];
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                                if ($a2['kat'] == '0') {
                                                    echo "<strong>$a2[noakun]</strong>";
                                                } else {
                                                    echo "$a2[noakun]";
                                                }
                                                ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($a2['kat'] == '0') {
                                                    echo "<strong>$a2[namaakun]</strong>";
                                                } else {
                                                    echo "$a2[namaakun]";
                                                }
                                                ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php
                                                $query_awal_akun2 = $this->db->query("SELECT IFNULL(jmlsetdef,0) AS jmlawal FROM neraca_akun WHERE DATE_FORMAT(`tglsetdef`,'%Y-%m') < '$bulan' AND noakun='$noakun2'");
                                                $r_query_awal_akun2 = $query_awal_akun2->row_array();

                                                $x_awal_akun2 = $r_query_awal_akun2['jmlawal'];

                                                $query_awal_akun2_lagi = $this->db->query("SELECT 
                                                CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                                CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                                FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun2' AND DATE_FORMAT(transtgl,'%Y-%m') < '$bulan' ORDER BY transtgl ASC");

                                                if ($query_awal_akun2_lagi->num_rows() > 0) {
                                                    $saldo_akhir_lama_akun2 = 0;
                                                    foreach ($query_awal_akun2_lagi->result_array() as $yyy) :
                                                        $saldo_akhir_lama_akun2 = ($saldo_akhir_lama_akun2 + $yyy['masuk']) - $yyy['keluar'];
                                                    endforeach;
                                                } else {
                                                    $saldo_akhir_lama_akun2 = 0;
                                                }

                                                $awal_baru_akun2 = $saldo_akhir_lama_akun2 + $x_awal_akun2;
                                                $total_awal_akun2 = $total_awal_akun2 + $awal_baru_akun2;
                                                echo number_format($awal_baru_akun2, 0, ",", ".");
                                                ?>

                                        </td>
                                        <td style="text-align: right;">
                                            <?php
                                                $query_akhir_akun2 = $this->db->query("SELECT 
                                                    CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                                    CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                                    FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun2' AND DATE_FORMAT(transtgl,'%Y-%m') = '$bulan' ORDER BY transtgl ASC");

                                                if ($query_akhir_akun2->num_rows() > 0) {
                                                    $saldo_akhir_akun2 = 0;
                                                    foreach ($query_akhir_akun2->result_array() as $akhir2) :
                                                        $saldo_akhir_akun2 = ($saldo_akhir_akun2 + $akhir2['masuk']) - $akhir2['keluar'];
                                                    endforeach;
                                                } else {
                                                    $saldo_akhir_akun2 = 0;
                                                }
                                                $total_akhir_akun2 = $total_akhir_akun2 + $saldo_akhir_akun2;
                                                echo number_format($saldo_akhir_akun2, 0, ",", ".");
                                                ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>

                                    <?php
                                    $total_awal_akun3 = 0;
                                    $total_akhir_akun3 = 0;
                                    foreach ($dataakun3->result_array() as $a3) :
                                        $noakun3 = $a3['noakun'];
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                                if ($a3['kat'] == '0') {
                                                    echo "<strong>$a3[noakun]</strong>";
                                                } else {
                                                    echo "$a3[noakun]";
                                                }
                                                ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($a3['kat'] == '0') {
                                                    echo "<strong>$a3[namaakun]</strong>";
                                                } else {
                                                    echo "$a3[namaakun]";
                                                }
                                                ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php
                                                // $query_awal_akun3 = $this->db->query("SELECT IFNULL(jmlsetdef,0) AS jmlawal FROM neraca_akun WHERE DATE_FORMAT(`tglsetdef`,'%Y-%m') < '$bulan' AND noakun='$noakun3'");
                                                // $r_query_awal_akun3 = $query_awal_akun3->row_array();

                                                // $x_awal_akun3 = $r_query_awal_akun3['jmlawal'];

                                                // $query_awal_akun3_lagi = $this->db->query("SELECT 
                                                // CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                                // CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                                // FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun3' AND DATE_FORMAT(transtgl,'%Y-%m') < '$bulan' ORDER BY transtgl ASC");

                                                // if ($query_awal_akun3_lagi->num_rows() > 0) {
                                                //     $saldo_akhir_lama_akun3 = 0;
                                                //     foreach ($query_awal_akun3_lagi->result_array() as $yyyy) :
                                                //         $saldo_akhir_lama_akun3 = ($saldo_akhir_lama_akun3 + $yyyy['masuk']) - $yyyy['keluar'];
                                                //     endforeach;
                                                // } else {
                                                //     $saldo_akhir_lama_akun3 = 0;
                                                // }

                                                // $awal_baru_akun3 = $saldo_akhir_lama_akun3 + $x_awal_akun3;
                                                // $total_awal_akun3 = $total_awal_akun3 + $awal_baru_akun3;
                                                // echo number_format($awal_baru_akun3, 0, ",", ".");
                                                $modal_awal = $total_awal_akun1 + $total_awal_akun2;
                                                if ($a3['kat'] == 1) {
                                                    echo number_format($modal_awal, 0, ",", ".");
                                                } else {
                                                    echo '';
                                                }
                                                ?>

                                        </td>
                                        <td style="text-align: right;">
                                            <?php
                                                // $query_akhir_akun3 = $this->db->query("SELECT 
                                                //     CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                                //     CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                                //     FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun3' AND DATE_FORMAT(transtgl,'%Y-%m') = '$bulan' ORDER BY transtgl ASC");

                                                // if ($query_akhir_akun3->num_rows() > 0) {
                                                //     $saldo_akhir_akun3 = 0;
                                                //     foreach ($query_akhir_akun3->result_array() as $akhir3) :
                                                //         $saldo_akhir_akun3 = ($saldo_akhir_akun3 + $akhir3['masuk']) - $akhir3['keluar'];
                                                //     endforeach;
                                                // } else {
                                                //     $saldo_akhir_akun3 = 0;
                                                // }
                                                // $total_akhir_akun3 = $total_akhir_akun3 + $saldo_akhir_akun3;
                                                // echo number_format($saldo_akhir_akun3, 0, ",", ".");
                                                $modal_akhir = $total_akhir_akun1 + $total_akhir_akun2;
                                                if ($a3['kat'] == 1) {
                                                    echo number_format($modal_akhir, 0, ",", ".");
                                                } else {
                                                    echo '';
                                                }
                                                ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <th colspan="2" style="text-align: center;">Jumlah</th>
                                        <td style="text-align: right; font-weight: bold;">
                                            <?= number_format($modal_awal, 0, ",", "."); ?>
                                        </td>
                                        <td style="text-align: right; font-weight: bold;">
                                            <?= number_format($modal_akhir, 0, ",", "."); ?>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>