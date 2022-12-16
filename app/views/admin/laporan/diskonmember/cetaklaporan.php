<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= strtoupper($namalaporan . '-' . $toko['nmtoko']) ?></title>
    <style>
    td {
        padding-left: 3px;
        padding-right: 3px;
    }

    thead {
        text-align: center;
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
                            <img style="width: 40%;" src="<?= base_url($toko['logo']) ?>" alt="">
                        </td>
                        <td style="text-align: center;">
                            <span
                                style="font-size:12pt; font-weight: bold;"><?= strtoupper("$toko[nmtoko]<br>$namalaporan"); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table style="width: 90%; font-size:11pt; text-align: left;">
                                <tr>
                                    <td style="width: 5%;">Bulan</td>
                                    <td style="width: 1%;">:</td>
                                    <td style="width: 15%;"><?= $bulan; ?></td>
                                </tr>
                                <tr>
                                    <td>Tgl.Cetak</td>
                                    <td>:</td>
                                    <td><?= date('d-m-Y'); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table style="width: 100%; font-size:11pt; text-align: left; border-collapse:collapse;"
                                border="1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Member</th>
                                        <th>Nama Member</th>
                                        <th>Instansi</th>
                                        <th>Total Diskon (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $nomor = 0;
                                    $totalsisadiskon = 0;
                                    foreach ($tampildata->result_array() as $row) :
                                        $nomor++;
                                        $jualkodemember = $row['jualmemberkode'];

                                        // Hitung Total Diskon Member
                                        $query_tabungandiskon = $this->db->query("SELECT IFNULL(ROUND(SUM(jualtotalbersih * ($diskonsetting / 100)),0),0) AS totaldiskon FROM penjualan WHERE jualmemberkode='$jualkodemember' AND DATE_FORMAT(jualtgl,'%Y-%m')<='$bulanini' AND (jualstatusbayar='T' OR jualstatusbayar='K')")->row_array();

                                        $query_diskondigunakan = $this->db->query("SELECT IFNULL(SUM(jualtotalbersih),0) AS totaldigunakan FROM penjualan WHERE jualmemberkode='$jualkodemember' AND DATE_FORMAT(jualtgl,'%Y-%m')<='$bulanini' AND jualstatusbayar='M'")->row_array();

                                        $query_diskondiambil = $this->db->query("SELECT IFNULL(SUM(detambiljumlah),0) AS totaldiambil FROM pengambilan_diskon_detail JOIN pengambilan_diskon ON  detambilkode=ambilkode WHERE detambilmemberkode = '$jualkodemember' AND DATE_FORMAT(ambiltgl,'%Y-%m') <= '$bulanini'")->row_array();

                                        $totaldiskon = $query_tabungandiskon['totaldiskon'];
                                        $totaldigunakan = $query_diskondigunakan['totaldigunakan'];
                                        $totaldiambil = $query_diskondiambil['totaldiambil'];
                                        $sisadiskon = $totaldiskon - ($totaldigunakan + $totaldiambil);

                                        $totalsisadiskon = $totalsisadiskon + $sisadiskon;
                                        // end
                                    ?>
                                    <tr>
                                        <td><?= $nomor; ?></td>
                                        <td><?= $row['jualmemberkode']; ?></td>
                                        <td><?= $row['membernama']; ?></td>
                                        <td><?= $row['memberinstansi']; ?></td>
                                        <td style="text-align: right;">
                                            <?= number_format($sisadiskon, 0, ",", "."); ?>
                                        </td>
                                    </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;">
                                        <td colspan="4" style="text-align: center;">
                                            Total Keseluruhan
                                        </td>
                                        <td style="text-align: right;">
                                            <?= number_format($totalsisadiskon, 0, ",", "."); ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>