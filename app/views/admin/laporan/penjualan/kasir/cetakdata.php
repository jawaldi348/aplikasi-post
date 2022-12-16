<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= strtoupper($namalaporan . ' ' . $toko['nmtoko']) ?></title>
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
                                    <?php
                                    if ($kasir != '') {
                                    ?>
                                    <tr>
                                        <td style="width: 20%;">Kasir</td>
                                        <td style="width: 1%;">:</td>
                                        <td><?= $kasir; ?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </thead>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: left;">
                            <table style="border-collapse: collapse; width: 100%;" border="1" id="data">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Total Kotor(Rp)</th>
                                        <th>Diskon(Rp)</th>
                                        <th>Total Bersih (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $nomor = 0;
                                    if ($penjualan->num_rows() > 0) {
                                        foreach ($penjualan->result_array() as $jual) :
                                            $nomor++;

                                    ?>
                                    <tr>
                                        <td><?= $nomor; ?></td>
                                        <td style="text-align: right;">
                                            <?= number_format($jual['totalkotor'], 0, ",", "."); ?></td>
                                        <td style="text-align: right;">
                                            <?= number_format($jual['diskon'], 0, ",", "."); ?></td>
                                        <td style="text-align: right;">
                                            <?= number_format($jual['totalbersih'], 0, ",", "."); ?></td>
                                    </tr>
                                    <?php
                                        endforeach;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: left;">
                            <br>
                            <table style="border-collapse: collapse; width: 100%;" border="1" id="data">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barcode</th>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Harga (Rp)</th>
                                        <th>Diskon (Rp)</th>
                                        <th>Sub.Total (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    if ($detailpenjualan->num_rows() > 0) {
                                        foreach ($detailpenjualan->result_array() as $detail) :
                                            $no++;

                                    ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?= $detail['detjualkodebarcode']; ?></td>
                                        <td><?= $detail['namaproduk']; ?></td>
                                        <td><?= $detail['detjualjml']; ?></td>
                                        <td style="text-align: right;">
                                            <?= number_format($detail['detjualharga'], 0, ",", "."); ?></td>
                                        <td style="text-align: right;">
                                            <?= number_format($detail['diskon'], 0, ",", "."); ?></td>
                                        <td style="text-align: right;">
                                            <?= number_format($detail['detjualsubtotal'], 0, ",", "."); ?>
                                        </td>
                                    </tr>
                                    <?php
                                        endforeach;
                                    }
                                    ?>
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