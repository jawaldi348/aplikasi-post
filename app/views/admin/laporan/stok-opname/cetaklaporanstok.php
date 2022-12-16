<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $namalaporan; ?></title>
    <link rel="stylesheet" href="<?= base_url('assets\css\cetak.css') ?>">
    <style>
    body {
        font-family: 'Times New Roman', Times, serif;
    }
    </style>
</head>


<body onload="window.print()">
    <div id="table-data">
        <table align="center" width="100%">
            <tr>
                <td align="center" width="15%"> <img style="width: 100%;" src="<?= base_url($toko['logo']) ?>" alt="">
                </td>
                <td align="center" width="95%" colspan="3">
                    <span style="font-size:12pt; font-weight: bold;"><?= strtoupper("kop mart"); ?></span>
                    <br>
                    <span
                        style="font-size:11pt; font-weight: bold;"><?= strtoupper("dinas pendidikan provinsi sumatera barat"); ?>
                    </span><br>
                    <span style="font-size:11pt;"><?= 'Jl. Jendral Sudirman No.52 Padang Telp.081276235637'; ?>
                    </span><br>
                    <span style="font-size:12pt; font-weight: bold;"><?= "<u>$namalaporan</u>"; ?></span>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <table border="1" align="center" width="100%" style="font-size: 12pt; font-family: 'Times New Roman';">
            <thead>
                <tr>
                    <th align="center" width="5%">No</th>
                    <th align="center">Kode</th>
                    <th align="center">Nama Produk</th>
                    <th align="center">Stok</th>
                    <th align="center">Fisik</th>
                    <th align="center">Selisih</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($tampildata->num_rows() == 0)
                    echo "<tr><td colspan='6' align='center'> Tidak Ada Data</td></tr>";
                $nomor = 0;
                foreach ($tampildata->result_array() as $r) :
                    $nomor++;
                ?>
                <tr>
                    <td align="center"><?php echo $nomor; ?></td>
                    <td><?= $r['kodebarcode']; ?></td>
                    <td><?= $r['namaproduk']; ?></td>
                    <td align="center"><?= number_format($r['stok'], 0, ".", ","); ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>

</html>