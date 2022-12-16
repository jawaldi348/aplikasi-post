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
                                        <th>No</th>
                                        <th>Faktur</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah Sisa(Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $nomor = 0;
                                    $totalsisa = 0;
                                    if ($datapembulatan->num_rows() > 0) {
                                        foreach ($datapembulatan->result_array() as $r) :
                                            $nomor++;
                                            $totalsisa = $totalsisa + $r['jualsisapembulatan'];

                                    ?>
                                    <tr>
                                        <td><?= $nomor; ?></td>
                                        <td><?= $r['jualfaktur']; ?></td>
                                        <td><?= date('d-m-Y', strtotime($r['jualtgl'])); ?></td>
                                        <td style="text-align: right;">
                                            <?= number_format($r['jualsisapembulatan'], 0, ",", "."); ?></td>
                                    </tr>
                                    <?php
                                        endforeach;
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;">
                                        <td colspan="3" style="text-align: center;">Total Sisa Pembulatan</td>
                                        <td style="text-align: right;">
                                            <?= number_format($totalsisa, 0, ",", "."); ?>
                                        </td>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
</body>

</html>