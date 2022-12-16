<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $namalaporan; ?></title>
    <link rel="stylesheet" href="<?= base_url('assets\css\sheets-of-paper-a4.css') ?>">
    <style>
    body {
        font-family: 'Times New Roman', Times, serif;
    }

    .data {
        font-family: sans-serif;
        color: #232323;
        border-collapse: collapse;
        font-size: 9pt;
        width: 100%;
    }

    .data,
    th,
    td {
        border: 1px solid #999;
        padding: 2px 5px;
    }
    </style>
</head>

<body class="document" onload="window.print();">
    <div class="page" contenteditable="true">
        <table style="width: 100%; border-collapse: collapse;" border="0" align="center">
            <tr>
                <td style="text-align: center;">
                    <table align="center" style="width: 100%;">
                        <tr>
                            <td style="width:15%">
                                <img style="width: 100%;" src="<?= base_url($toko['logo']) ?>" alt="">
                            </td>
                            <td style="text-align: center;">
                                <span style="font-size:12pt; font-weight: bold;"><?= strtoupper("kop mart"); ?></span>
                                <br>
                                <span
                                    style="font-size:11pt; font-weight: bold;"><?= strtoupper("dinas pendidikan provinsi sumatera barat"); ?>
                                </span><br>
                                <span
                                    style="font-size:11pt;"><?= 'Jl. Jendral Sudirman No.52 Padang Telp.081276235637'; ?>
                                </span><br>
                                <span style="font-size:12pt; font-weight: bold;"><?= "<u>$namalaporan</u>"; ?></span>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td><br><br>
                    <table align="left" class="data">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Tgl.Kadaluarsa</th>
                                <th>Jumlah</th>
                                <th>Harga Beli(Rp.)</th>
                                <th>Sub.Total(Rp.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $nomor = 0;
                            $totalsubtotal = 0;
                            foreach ($tampildata->result_array() as $row) : $nomor++;
                                $subtotal = $row['hargabeli'] * $row['jmlkadaluarsa'];
                                $totalsubtotal = $totalsubtotal + $subtotal;
                            ?>
                            <tr>
                                <th><?= $nomor; ?></th>
                                <td><?= $row['kodebarcode']; ?></td>
                                <td><?= $row['namaproduk']; ?></td>
                                <td><?= date('d-m-Y', strtotime($row['tglkadaluarsa'])); ?></td>
                                <td style="text-align: right;">
                                    <?= number_format($row['jmlkadaluarsa'], 0, ",", "."); ?></td>
                                <td style="text-align: right;">
                                    <?= number_format($row['hargabeli'], 0, ",", "."); ?></td>
                                <td style="text-align: right;">
                                    <?= number_format($subtotal, 0, ",", "."); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align: center;">Total</th>
                                <th style="text-align: right;">
                                    <?= number_format($totalsubtotal, 0, ",", "."); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>