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
    <table border="1" style="border-collapse: collapse; border:1px solid #000">
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
            $nomor = 0;
            foreach ($tampildata->result_array() as $r) :
            ?>
            <tr>
                <td align="center"><?php echo $nomor++; ?></td>
                <td><?= $r['kodebarcode']; ?></td>
                <td><?= $r['namaproduk']; ?></td>
                <td align="center"><?= number_format($r['stok'], 0, ".", ","); ?></td>
                <td></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>