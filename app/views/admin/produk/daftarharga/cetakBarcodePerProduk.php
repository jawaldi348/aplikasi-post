<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/sheets-of-paper-a4.css'); ?>">
    <style>
    .box {
        width: 2cm;
        height: 1.1cm;
        border: solid 1px black;
        font-style: arial;
        font-weight: bold;
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 10px;
        font-size: 5pt;
        text-align: center;
    }

    .nama-produk {
        font-size: 9pt;
        text-overflow: ellipsis;
    }
    </style>
</head>

<body>
    <div class="page">
        <?php
        for ($i = 0; $i < $jmlcetak; $i++) :
        ?>
        <div class="box">
            <?= "Rp." . number_format($harga, 0, ",", ".") ?><br>
            <img style="width: 100%;" src="<?= base_url($pathbarcode) ?>">
        </div>
        <?php
        endfor;
        ?>
    </div>
</body>

</html>