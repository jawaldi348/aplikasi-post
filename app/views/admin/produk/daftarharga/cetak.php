<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Harga Produk</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/sheets-of-paper-a4.css'); ?>">
    <style>
    .box {
        width: 5.5cm;
        height: auto;
        border: solid 1px black;
        font-style: arial;
        font-weight: bold;
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .nama-produk {
        font-size: 9pt;
        text-overflow: ellipsis;
    }
    </style>
</head>

<body onload="window.print();">
    <div class="page">
        <?php
        for ($i = 0; $i < $jmldata; $i++) :
            $query_dataproduk = $this->db->get_where('produk', ['kodebarcode' => $row[$i]]);

            foreach ($query_dataproduk->result_array() as $d) :
        ?>
        <div class="box">
            <span class="nama-produk"><?= $d['namaproduk']; ?>
            </span><br>

            <span style="font-size:16pt; text-align: center;">
                <center>
                    <?= "Rp. " . number_format($d['harga_jual_eceran'], 0, ",", "."); ?>
                </center>
            </span>
            <!-- <table style="width: 90%; margin: 0px;">
                <tr>
                    <td>
                        
                    </td>
                </tr>
            </table> -->
            <br>
            <table>
                <tr>
                    <td>
                        <img src="<?= base_url($d['pathbarcode']); ?>">
                    </td>
                </tr>
            </table>
        </div>
        <?php
            endforeach;
        endfor;
        ?>
    </div>
</body>

</html>