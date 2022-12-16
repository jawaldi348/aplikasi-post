<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $namalaporan; ?></title>
    <link rel="stylesheet" href="<?= base_url('assets\css\cetak.css') ?>" type="text/css" media="print">
    <style>
    body {
        font-family: 'Times New Roman', Times, serif;
    }
    </style>
</head>

<body onload="window.print();">
    <div id="table-data">
        <table style="width: 100%;">
            <tr>
                <td style="width: 10%; text-align: left;">
                    <img style="width: 100%;" src="<?= base_url($toko['logo']) ?>" alt="">
                </td>
                <td style="width: 80%; text-align: center;">
                    <span
                        style="font-size:12pt; font-weight: bold;"><?= strtoupper("$toko[nmtoko]<br>Laporan Koreksi Stok"); ?>
                    </span>
                </td>
                <td style="width: 10%; text-align: left;">

                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <hr size="2%" color="#000">
                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td style="width: 5%;">No.Koreksi</td>
                <td style="width: 1%;">:</td>
                <td style="width: 19%;"><?= $nokoreksi; ?></td>
                <td style="width: 10%;"></td>
                <td style="width: 5%;">Pemasok</td>
                <td style="width: 1%;">:</td>
                <td style="width: 19%;"><?= $pemasok; ?></td>
            </tr>
            <tr>
                <td style="width: 5%;">Tanggal</td>
                <td style="width: 1%;">:</td>
                <td style="width: 19%;"><?= $tgl; ?></td>
                <td colspan="4"></td>
            </tr>
        </table>
        <table style="width: 100%; border-collapse: collapse;" border="1">
            <thead>
                <tr style="background-color: #d9dbde;">
                    <th style="width: 3%;">No</th>
                    <th style="width: 10%;">Kode</th>
                    <th style="width: 15%;">Nama Produk</th>
                    <th style="width: 5%;">Stok<br>Lalu</th>
                    <th style="width: 5%;">Stok<br>Sekarang</th>
                    <th style="width: 5%;">Selisih</th>
                    <th style="width: 7%;">Alasan</th>
                    <th style="width: 10%;">Harga<br>Beli</th>
                    <th style="width: 10%;">Sub<br>Total</th>
                </tr>
            </thead>
            <tbody>

                <?php $no = 1;
                $totalsubtotal = 0;
                foreach ($datakoreksi->result_array() as $r) :
                    $totalsubtotal += $r['subtotal'];
                ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td><?= $r['kode']; ?></td>
                    <td><?= $r['namaproduk']; ?></td>
                    <td style="text-align: center;"><?= $r['stoklalu']; ?></td>
                    <td style="text-align: center;"><?= $r['stoksekarang']; ?></td>
                    <td style="text-align: center;"><?= preg_replace(
                                                            '/(-)([\d\.\,]+)/ui',
                                                            '($2)',
                                                            number_format($r['selisih'], 0, ',', '.')
                                                        );; ?></td>
                    <td><?= $r['alasan']; ?></td>
                    <td style="text-align: right;"><?= preg_replace(
                                                            '/(-)([\d\.\,]+)/ui',
                                                            '($2)',
                                                            number_format($r['hargabeli'], 2, ',', '.')
                                                        );; ?></td>
                    <td style="text-align: right;"><?= preg_replace(
                                                            '/(-)([\d\.\,]+)/ui',
                                                            '($2)',
                                                            number_format($r['subtotal'], 2, ',', '.')
                                                        );; ?></td>

                </tr>
                <?php
                endforeach;
                ?>
                <tr>
                    <th colspan="8" style="text-align: center; background-color: #d9dbde;">Total</th>
                    <td style="text-align: right; font-weight: bold;"><?= preg_replace(
                                                                            '/(-)([\d\.\,]+)/ui',
                                                                            '($2)',
                                                                            number_format($totalsubtotal, 2, ',', '.')
                                                                        );; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>