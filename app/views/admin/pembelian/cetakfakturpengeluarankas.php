<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pengeluaran Kas</title>
</head>

<body onload="window.print();">
    <div contenteditable="true">
        <table style="width: 100%; border-collapse: collapse;" border="0" align="center">
            <tr>
                <td style="text-align: center;">
                    <table align="center" style="width: 100%;">
                        <tr>
                            <td style="width:15%">
                                <img style="width: 100%;" src="<?= base_url($toko['logo']) ?>" alt="">
                            </td>
                            <td style="text-align: center;">
                                <span style="font-size:12pt; font-weight: bold;"><?= $toko['nmtoko']; ?></span>
                                <br>
                                <span style="font-size:11pt;"><?= $toko['alamat'], ", Telp. " . $toko['telp']; ?>
                                </span><br>
                                <span
                                    style="font-size:12pt; font-weight: bold;"><?= strtoupper("<u>bukti pengeluaran kas</u>"); ?></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><br><br>
                    <table align="left" style="width: 100%; font-size:11pt;">
                        <tr style="height: 30px;">
                            <td style="width:25%;">
                                Telah dibayarkan Kepada
                            </td>
                            <td style="width:1%;">
                                :
                            </td>
                            <td colspan="2">
                                <?= $pemasok; ?>
                            </td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="width:25%;">
                                Banyaknya
                            </td>
                            <td style="width:1%;">
                                :
                            </td>
                            <td colspan="2">
                                <?= 'Rp. ' . number_format($totalbersih, 0, ",", "."); ?>
                            </td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="width:25%;">

                            </td>
                            <td style="width:1%;">

                            </td>
                            <td colspan="2">
                                <i><?= "( $terbilang )"; ?></i>
                            </td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="width:25%;">
                                Untuk Pembayaran
                            </td>
                            <td style="width:1%;">

                            </td>
                            <td>
                                <i><?= $untukpembayaran; ?></i>
                            </td>
                            <td style="text-align: right;">
                                <?= 'Rp. ' . number_format($totalbersih, 0, ",", "."); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="text-align: center;">
                    <br><br><br>
                    <table align="center" style="width: 100%;">
                        <tr>
                            <td colspan="3" style="text-align: right;">
                                Padang, <?= $tglbeli; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%;">Mengetahui<br><br><br><br><br><strong>(________________)</strong>
                            </td>
                            <td style="width: 30%;">Pengelola<br><br><br><br><br><strong>(________________)</strong>
                            </td>
                            <td style="width: 30%;">Yang Menerima<br><br><br><br><br><strong><?= $namauser; ?></strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br><br><br>
        <table style="width: 100%; border-collapse: collapse;" border="0" align="center">
            <tr>
                <td style="text-align: center;">
                    <table align="center" style="width: 100%;">
                        <tr>
                            <td style="width:15%">
                                <img style="width: 100%;" src="<?= base_url($toko['logo']) ?>" alt="">
                            </td>
                            <td style="text-align: center;">
                                <span style="font-size:12pt; font-weight: bold;"><?= $toko['nmtoko']; ?></span>
                                <br>
                                <span style="font-size:11pt;"><?= $toko['alamat'], ", Telp. " . $toko['telp']; ?>
                                </span><br>
                                <span
                                    style="font-size:12pt; font-weight: bold;"><?= strtoupper("<u>bukti pengeluaran kas</u>"); ?></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><br><br>
                    <table align="left" style="width: 100%; font-size:11pt;">
                        <tr style="height: 30px;">
                            <td style="width:25%;">
                                Telah dibayarkan Kepada
                            </td>
                            <td style="width:1%;">
                                :
                            </td>
                            <td colspan="2">
                                <?= $pemasok; ?>
                            </td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="width:25%;">
                                Banyaknya
                            </td>
                            <td style="width:1%;">
                                :
                            </td>
                            <td colspan="2">
                                <?= 'Rp. ' . number_format($totalbersih, 0, ",", "."); ?>
                            </td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="width:25%;">

                            </td>
                            <td style="width:1%;">

                            </td>
                            <td colspan="2">
                                <i><?= "( $terbilang )"; ?></i>
                            </td>
                        </tr>
                        <tr style="height: 30px;">
                            <td style="width:25%;">
                                Untuk Pembayaran
                            </td>
                            <td style="width:1%;">

                            </td>
                            <td>
                                <i><?= $untukpembayaran; ?></i>
                            </td>
                            <td style="text-align: right;">
                                <?= 'Rp. ' . number_format($totalbersih, 0, ",", "."); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="text-align: center;">
                    <br><br><br>
                    <table align="center" style="width: 100%;">
                        <tr>
                            <td colspan="3" style="text-align: right;">
                                Padang, <?= $tglbeli; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%;">Mengetahui<br><br><br><br><br><strong>(________________)</strong>
                            </td>
                            <td style="width: 30%;">Pengelola<br><br><br><br><br><strong>(________________)</strong>
                            </td>
                            <td style="width: 30%;">Yang Menerima<br><br><br><br><br><strong><?= $namauser; ?></strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>