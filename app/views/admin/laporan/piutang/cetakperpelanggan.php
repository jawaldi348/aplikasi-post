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

    table {
        border-collapse: collapse;
        width: 100%;
    }

    td {
        page-break-inside: avoid !important;
        white-space: nowrap;
        overflow: hidden;
        padding: 1px;
    }

    .item {
        font-size: 10pt;
    }

    @media print {
        td {
            page-break-inside: avoid !important;
            white-space: nowrap;
            overflow: hidden;
            padding: 1px;
        }
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
                        style="font-size:12pt; font-weight: bold;"><?= strtoupper("$toko[nmtoko]<br>Laporan Piutang Pelanggan"); ?>
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
                <td style="width: 5%;">Kode Pelanggan</td>
                <td style="width: 1%;">:</td>
                <td style="width: 19%;"><?= $kodemember; ?></td>
                <td style="width: 10%;"></td>
                <td style="width: 5%;">Alamat</td>
                <td style="width: 1%;">:</td>
                <td style="width: 19%;"><?= $alamat; ?></td>
            </tr>
            <tr>
                <td style="width: 5%;">Nama Pelanggan</td>
                <td style="width: 1%;">:</td>
                <td style="width: 19%;"><?= $namamember; ?></td>
                <td colspan="4"></td>
            </tr>
        </table>
        <table border="1">
            <tr style="background-color: #d9dbde;">
                <th style="width: 3%;">No</th>
                <th style="width: 10%;">Faktur</th>
                <th style="width: 12%;">Tgl.Faktur</th>
                <th style="width: 10%;">Jatuh Tempo</th>
                <th style="width: 10%;">Jumlah Piutang<br>(Rp.)</th>
                <th style="width: 10%;">Sisa</th>
            </tr>
            <?php $no = 1;
            $totalsisa = 0;
            foreach ($fakturpiutang->result_array() as $r) :
                $sisa = $r['jumlahpiutang'] - $r['jumlahbayar'];
                $totalsisa += $sisa;
            ?>
            <tr>
                <td style="text-align: center;"><?= $no++; ?></td>
                <td><?= $r['faktur']; ?></td>
                <td><?= $r['tgl']; ?></td>
                <td><?= $r['tgltempo']; ?></td>
                <td style="text-align: right;"><?= number_format($r['jumlahpiutang'], 0, ",", "."); ?></td>
                <td style="text-align: right;"><?= number_format($sisa, 0, ",", "."); ?></td>
            </tr>
            <tr>
                <th colspan="6" style="background-color: #d9dbde;">Item</th>
            </tr>
            <tr>
                <td colspan="6">
                    <table class="item" border="1" cellpadding="3">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                        <?php
                            $query = $this->db->query("SELECT detjualkodebarcode AS kode,namaproduk,CONCAT(ROUND(detjualjml,0),' ',satnama) AS jml,detjualharga AS harga,detjualsubtotal AS subtotal FROM penjualan_detail JOIN produk ON detjualkodebarcode=kodebarcode JOIN satuan ON detjualsatid=satuan.`satid` WHERE detjualfaktur = '$r[faktur]'")->result();

                            foreach ($query as $d) :
                            ?>
                        <tr>
                            <td><?= $d->kode; ?></td>
                            <td><?= $d->namaproduk; ?></td>
                            <td><?= $d->jml; ?></td>
                            <td style="text-align: right;"><?= number_format($d->harga, 0, ",", "."); ?></td>
                            <td style="text-align: right;"><?= number_format($d->subtotal, 0, ",", "."); ?></td>
                        </tr>
                        <?php
                            endforeach;

                            ?>
                    </table>
                </td>
            </tr>
            <?php
            endforeach;
            ?>
            <tr>
                <th colspan="5" style="text-align: center; background-color: #d9dbde;">Total Keseluruhan</th>
                <td style="text-align: right; font-weight: bold;"><?= number_format($totalsisa, 0, ",", "."); ?>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>