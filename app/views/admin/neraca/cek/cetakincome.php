<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets\css\cetak.css') ?>" type="text/css" media="print">
    <title><?= strtoupper($namalaporan . '-' . $toko['nmtoko']) ?></title>
    <style>
    body {
        font-family: 'Times New Roman', Times, serif;
    }
    </style>
</head>

<body onload="window.print();">
    <div id="table-data">
        <table style="width: 90%; border-collapse: collapse;" border="1" align="center">
            <tr>
                <td style="text-align: center;">
                    <table align="center" style="width: 90%; font-size:11pt;">
                        <tr>
                            <td style="width:10%">
                                <img style="width: 50%;" src="<?= base_url($toko['logo']) ?>" alt="">
                            </td>
                            <td style="text-align: center;">
                                <span
                                    style="font-size:12pt; font-weight: bold;"><?= strtoupper("$toko[nmtoko]<br>$namalaporan"); ?><br><?= "Bulan :" . $bulan; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center;">
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: left;">
                                <table style="width: 100%; border-collapse: collapse;" border="1" cellpadding="3">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>No.Perkiraan</th>
                                            <th>Nama Perkiraan</th>
                                            <th>Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($dataakun4->result_array() as $a4) :
                                            $noakun4 = $a4['noakun'];
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                    if ($a4['kat'] == '0') {
                                                        echo "<strong>$a4[noakun]</strong>";
                                                    } else {
                                                        echo "$a4[noakun]";
                                                    }
                                                    ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if ($a4['kat'] == '0') {
                                                        echo "<strong>$a4[namaakun]</strong>";
                                                    } else {
                                                        echo "$a4[namaakun]";
                                                    }
                                                    ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <?php
                                                    $query_akhir_akun4 = $this->db->query("SELECT 
                                                    CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                                    CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                                    FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun4' AND DATE_FORMAT(transtgl,'%Y-%m') <= '$bulan' ORDER BY transtgl ASC");

                                                    if ($query_akhir_akun4->num_rows() > 0) {
                                                        $saldo_akhir_akun4 = 0;
                                                        foreach ($query_akhir_akun4->result_array() as $akhir4) :
                                                            $saldo_akhir_akun4 = ($saldo_akhir_akun4 + $akhir4['masuk']) - $akhir4['keluar'];
                                                        endforeach;
                                                    } else {
                                                        $saldo_akhir_akun4 = 0;
                                                    }
                                                    echo number_format($saldo_akhir_akun4, 0, ",", ".");
                                                    ?>
                                            </td>
                                        </tr>
                                        <?php
                                        endforeach;
                                        ?>
                                        <tr>
                                            <th colspan="2" style="text-align: center;">Net Sales</th>
                                            <th style="text-align: right;">
                                                <?php
                                                $query_penjualan = $this->db->query("SELECT CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                            CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                            FROM `neraca_transaksi` a WHERE a.`transnoakun` = '4-100' AND DATE_FORMAT(transtgl,'%Y-%m') <= '$bulan' ORDER BY transtgl ASC");

                                                $query_diskonpenjualan = $this->db->query("SELECT CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                            CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                            FROM `neraca_transaksi` a WHERE a.`transnoakun` = '4-110' AND DATE_FORMAT(transtgl,'%Y-%m') <= '$bulan' ORDER BY transtgl ASC");

                                                $query_pendapatangerai = $this->db->query("SELECT CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                                        CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                                        FROM `neraca_transaksi` a WHERE a.`transnoakun` = '4-120' AND DATE_FORMAT(transtgl,'%Y-%m') <= '$bulan' ORDER BY transtgl ASC");

                                                if ($query_penjualan->num_rows() > 0) {
                                                    $saldo_penjualan = 0;
                                                    foreach ($query_penjualan->result_array() as $penjualan) :
                                                        $saldo_penjualan = ($saldo_penjualan + $penjualan['masuk']) - $penjualan['keluar'];
                                                    endforeach;
                                                } else {
                                                    $saldo_penjualan = 0;
                                                }

                                                if ($query_diskonpenjualan->num_rows() > 0) {
                                                    $saldo_diskonpenjualan = 0;
                                                    foreach ($query_diskonpenjualan->result_array() as $diskon) :
                                                        $saldo_diskonpenjualan = ($saldo_diskonpenjualan + $diskon['masuk']) - $diskon['keluar'];
                                                    endforeach;
                                                } else {
                                                    $saldo_diskonpenjualan = 0;
                                                }

                                                if ($query_pendapatangerai->num_rows() > 0) {
                                                    $saldo_pendapatagerai = 0;
                                                    foreach ($query_pendapatangerai->result_array() as $gerai) :
                                                        $saldo_pendapatagerai = ($saldo_pendapatagerai + $gerai['masuk']) - $gerai['keluar'];
                                                    endforeach;
                                                } else {
                                                    $saldo_pendapatagerai = 0;
                                                }

                                                $netsales = ($saldo_penjualan + $saldo_pendapatagerai) - $saldo_diskonpenjualan;

                                                echo number_format($netsales, 0, ",", ".");

                                                ?>
                                            </th>
                                        </tr>

                                        <?php
                                        foreach ($dataakun5->result_array() as $a5) :
                                            $noakun5 = $a5['noakun'];
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                    if ($a5['kat'] == '0') {
                                                        echo "<strong>$a5[noakun]</strong>";
                                                    } else {
                                                        echo "$a5[noakun]";
                                                    }
                                                    ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if ($a5['kat'] == '0') {
                                                        echo "<strong>$a5[namaakun]</strong>";
                                                    } else {
                                                        echo "$a5[namaakun]";
                                                    }
                                                    ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <?php
                                                    $query_akhir_akun5 = $this->db->query("SELECT 
                                                    CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                                    CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                                    FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun5' AND DATE_FORMAT(transtgl,'%Y-%m') <= '$bulan' ORDER BY transtgl ASC");

                                                    if ($query_akhir_akun5->num_rows() > 0) {
                                                        $saldo_akhir_akun5 = 0;
                                                        foreach ($query_akhir_akun5->result_array() as $akhir5) :
                                                            $saldo_akhir_akun5 = ($saldo_akhir_akun5 + $akhir5['masuk']) - $akhir5['keluar'];
                                                        endforeach;
                                                    } else {
                                                        $saldo_akhir_akun5 = 0;
                                                    }
                                                    echo number_format($saldo_akhir_akun5, 0, ",", ".");
                                                    ?>
                                            </td>

                                        </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <th colspan="2" style="text-align: center;">Gross Profit</th>
                                            <th style="text-align: right;">
                                                <?php
                                                $gross_profit = $netsales - $saldo_akhir_akun5;
                                                echo number_format($gross_profit, 0, ",", ".");
                                                ?>
                                            </th>
                                        </tr>
                                        <!-- Expenses -->
                                        <?php
                                        $total_expenses = 0;
                                        foreach ($dataakun6->result_array() as $a6) :
                                            $noakun6 = $a6['noakun'];
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                    if ($a6['kat'] == '0') {
                                                        echo "<strong>$a6[noakun]</strong>";
                                                    } else {
                                                        echo "$a6[noakun]";
                                                    }
                                                    ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if ($a6['kat'] == '0') {
                                                        echo "<strong>$a6[namaakun]</strong>";
                                                    } else {
                                                        echo "$a6[namaakun]";
                                                    }
                                                    ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <?php
                                                    $query_akhir_akun6 = $this->db->query("SELECT 
                                                    CASE a.`transjenis` WHEN 'K' THEN transjml ELSE 0 END AS masuk,
                                                    CASE a.`transjenis` WHEN 'D' THEN transjml ELSE 0 END AS keluar
                                                    FROM `neraca_transaksi` a WHERE a.`transnoakun` = '$noakun6' AND DATE_FORMAT(transtgl,'%Y-%m') <= '$bulan' ORDER BY transtgl ASC");

                                                    if ($query_akhir_akun6->num_rows() > 0) {
                                                        $saldo_akhir_akun6 = 0;
                                                        foreach ($query_akhir_akun6->result_array() as $akhir6) :
                                                            $saldo_akhir_akun6 = ($saldo_akhir_akun6 + $akhir6['masuk']) - $akhir6['keluar'];
                                                        endforeach;
                                                    } else {
                                                        $saldo_akhir_akun6 = 0;
                                                    }
                                                    $total_expenses = $total_expenses + $saldo_akhir_akun6;
                                                    echo number_format($saldo_akhir_akun6, 0, ",", ".");
                                                    ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <th colspan="2" style="text-align: center;">Total Expenses</th>
                                            <th style="text-align: right;">
                                                <?php
                                                echo number_format($total_expenses, 0, ",", ".");
                                                ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" style="text-align: center;">Net Profit</th>
                                            <th style="text-align: right;">
                                                <?php
                                                $netprofit = $gross_profit - $total_expenses;
                                                echo number_format($netprofit, 0, ",", ".");
                                                ?>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>