<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kop Mart | <?= $namalaporan; ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/cetak.css') ?>">
</head>

<body onload="window.print();">
    <div class="table-data">
        <table border="0" style="width: 100%;" align="center">
            <tr>
                <td>
                    <table border="0" style="width: 100%; text-align: center;">
                        <tr>
                            <td style="width: 20%; text-align: left;">
                                <img src="<?= base_url($toko['logo']) ?>" alt="Logo Kopmart" style="width: 50%;">
                            </td>
                            <td>
                                <span
                                    style="font-size:12pt; font-weight: bold;"><?= strtoupper("koperasi mart"); ?></span>
                                <br>
                                <span
                                    style="font-size:11pt;"><?= 'Jl. Jendral Sudirman No.52 Padang Telp.081276235637'; ?>
                                </span><br>
                                <span style="font-size:12pt; font-weight: bold;"><?= "<u>$namalaporan</u>"; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <hr>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table border="0" style="width: 100%; font-size: 11pt;">
            <tr>
                <td style="width: 15%;">Tgl.Laporan</td>
                <td style="width: 1%;">:</td>
                <td><?= $tanggal ?></td>
            </tr>
        </table>
        <table border="1" style="width: 100%; font-size: 11pt; border-collapse: collapse; border:1px solid #000;"
            cellpadding="5">
            <thead>
                <tr style="background-color: #bab9b5;">
                    <th style="width: 4%;">No</th>
                    <th style="width: 10%;">Kode</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th style="width: 10%;">Total Piutang (Rp.)</th>
                    <th style="width: 10%;">Total Bayar (Rp.)</th>
                    <th style="width: 10%;">Sisa Piutang (Rp.)</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                $totalseluruhpiutang = 0;
                $totalseluruhbayar = 0;
                $totalseluruhsisa = 0;
                if ($datapiutang->num_rows() > 0) {
                    foreach ($datapiutang->result_array() as $row) :
                        $sisapiutang = $row['totalpiutang'] - $row['totalbayar'];
                        $totalseluruhpiutang += $row['totalpiutang'];
                        $totalseluruhbayar += $row['totalbayar'];
                        $totalseluruhsisa += $sisapiutang;
                ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td><?= $row['memberkode']; ?></td>
                    <td><?= $row['membernama']; ?></td>
                    <td><?= $row['memberalamat']; ?></td>
                    <td style="text-align: right;"><?= number_format($row['totalpiutang'], 2, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($row['totalbayar'], 2, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($sisapiutang, 2, ",", "."); ?></td>
                </tr>
                <?php
                    endforeach;
                }
                ?>
                <tr>
                    <th style="background-color: #bab9b5;" colspan="4">TOTAL</th>
                    <td style="text-align: right; font-weight: bold;">
                        <?= number_format($totalseluruhpiutang, 2, ",", "."); ?></td>
                    <td style="text-align: right; font-weight: bold;">
                        <?= number_format($totalseluruhbayar, 2, ",", "."); ?></td>
                    <td style="text-align: right; font-weight: bold;">
                        <?= number_format($totalseluruhsisa, 2, ",", "."); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>