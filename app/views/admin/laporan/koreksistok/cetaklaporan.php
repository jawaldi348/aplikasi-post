<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= strtoupper($namalaporan . "-" . $toko['nmtoko']); ?></title>
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
    <table style="width: 100%; border-collapse: collapse;" border="1" align="center">
        <tr>
            <td style="text-align: center;">
                <table align="center" style="width: 100%; font-size:11pt;">
                    <tr>
                        <td style="width:5%; text-align: left;">
                            <img style="width: 20%;" src="<?= base_url($toko['logo']) ?>" alt="">
                        </td>
                        <td style="text-align: left;">
                            <span
                                style="font-size:12pt; font-weight: bold;"><?= strtoupper("$toko[nmtoko]<br>laporan $namalaporan"); ?></span>
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
                                    <td style="width: 5%;">Periode</td>
                                    <td style="width: 1%;">:</td>
                                    <td style="width: 15%;"><?= $periode; ?></td>
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
                                    <tr style="background-color: #c0c7d1;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>No.Faktur</th>
                                        <th>Nama Barang</th>
                                        <th>Pemasok</th>
                                        <th>Alasan</th>
                                        <th>Selisih</th>
                                        <th>HPP</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $nomor = 0;
                                    $totalseluruh = 0;
                                    foreach ($tampildata->result_array() as $row) :
                                        $nomor++;
                                        $total = $row['koreksiselisih'] * $row['koreksihargabeli'];
                                        $totalseluruh += $total;
                                        $total_x = preg_replace(
                                            '/(-)([\d\.\,]+)/ui',
                                            '($2)',
                                            number_format($total, 2, ',', '.')
                                        );

                                        $totalseluruh_x = preg_replace(
                                            '/(-)([\d\.\,]+)/ui',
                                            '($2)',
                                            number_format($totalseluruh, 2, ',', '.')
                                        );
                                    ?>
                                    <tr>
                                        <td><?= $nomor; ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['koreksitgl'])); ?></td>
                                        <td><?= $row['koreksino']; ?></td>
                                        <td><?= $row['namaproduk']; ?></td>
                                        <td><?= $row['namapemasok']; ?></td>
                                        <td><?= $row['koreksialasan']; ?></td>
                                        <td>
                                            <?php
                                                $selisih = preg_replace(
                                                    '/(-)([\d\.\,]+)/ui',
                                                    '($2)',
                                                    number_format($row['koreksiselisih'], 0, ',', '.')
                                                );
                                                echo $selisih;
                                                ?>
                                        </td>
                                        <td style="text-align: right;">
                                            <?= number_format($row['koreksihargabeli'], 2, ",", "."); ?></td>
                                        <td style="text-align: right;">
                                            <?= $total_x; ?></td>
                                    </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                    <tr>
                                        <th colspan="8" style="background-color: #c0c7d1; text-align: center;">Total
                                            Keseluruhan</th>
                                        <td style="text-align: right;">
                                            <?= $totalseluruh_x; ?>
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