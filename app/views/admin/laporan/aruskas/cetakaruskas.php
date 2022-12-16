<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= strtoupper($namalaporan . '|' . $toko['nmtoko']) ?></title>
    <style>
    td {
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
                            <img style="width: 50%;" src="<?= base_url($toko['logo']) ?>" alt="">
                        </td>
                        <td style="text-align: center;">
                            <span
                                style="font-size:12pt; font-weight: bold;"><?= strtoupper("$toko[nmtoko]<br>$namalaporan"); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: center;">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: left;">
                            <table style="border:1px; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <td style="width: 20%;">Periode</td>
                                        <td style="width: 1%;">:</td>
                                        <td><?= $periode; ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: left;">
                            <table style="border-collapse: collapse; width: 100%;" border="1" id="data">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th>Transaksi</th>
                                        <th>Masuk (Rp)</th>
                                        <th>Keluar (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Kas</td>
                                        <td style="text-align: right;"><?= number_format($saldokas, 0, ",", "."); ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Penjualan / Pendapatan</td>
                                        <td style="text-align: right;"><?= number_format($pendapatan, 0, ",", "."); ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Pengeluaran</td>
                                        <td></td>
                                        <td style="text-align: right;"><?= number_format($pengeluaran, 0, ",", "."); ?>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" style="text-align: center; font-weight: bold;">
                                            Total Keseluruhan
                                        </td>
                                        <td colspan="2" style="text-align: center; font-weight: bold;">
                                            <?php
                                            $total = ($saldokas + $pendapatan) - $pengeluaran;
                                            echo number_format($total, 0, ",", ".");
                                            ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
</body>

</html>