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
    }

    @media print {
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
                    <span style="font-size:12pt; font-weight: bold;"><?= strtoupper("$toko[nmtoko]<br>$namalaporan"); ?>
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
                <td style="width: 10%;">Periode :</td>
                <td style="width: 1%;">:</td>
                <td><?= $periode; ?></td>
            </tr>
            <tr>
                <td colspan="3">
                    <table border="1" cellpadding="3">
                        <thead>
                            <tr style="background-color: #d9dbde; ">
                                <th>No</th>
                                <th>Faktur</th>
                                <th>Tanggal</th>
                                <th>Akun</th>
                                <th>Total(Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nomor = 1;
                            $totalseluruh = 0;
                            foreach ($datapemakaian->result_array() as $row) :
                                $totalseluruh += $row['pakaitotal'];
                            ?>
                            <tr>
                                <td align="center"><?= $nomor++; ?></td>
                                <td><?= $row['faktur']; ?></td>
                                <td align="center"><?= date('d-m-Y', strtotime($row['tgl'])); ?></td>
                                <td align="center"><?= $row['pakaibiayanoakun'] . ' ' . $row['namaakun']; ?></td>
                                <td align="right"><?= number_format($row['pakaitotal'], 2, ",", "."); ?></td>
                            </tr>
                            <tr>
                                <th colspan="5" style="background-color: #d9dbde;">Item</th>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <table border="1" cellpadding="3">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Produk</th>
                                            <th>Jml</th>
                                            <th>Harga(Rp)</th>
                                            <th>Sub.Total(Rp)</th>
                                        </tr>
                                        <?php
                                            $query_detail = $this->db->query("SELECT detpakaikodebarcode,namaproduk,detpakaijml AS jml, detpakaihargabeli AS harga,detpakaisubtotal AS subtotal FROM pemakaian_detail JOIN produk ON detpakaikodebarcode=kodebarcode WHERE detpakaifaktur='$row[faktur]'");

                                            foreach ($query_detail->result_array() as $r) :
                                            ?>
                                        <tr>
                                            <td><?= $r['detpakaikodebarcode']; ?></td>
                                            <td><?= $r['namaproduk']; ?></td>
                                            <td align="right"><?= number_format($r['jml'], 0, ",", "."); ?></td>
                                            <td align="right"><?= number_format($r['harga'], 2, ",", "."); ?></td>
                                            <td align="right"><?= number_format($r['subtotal'], 2, ",", "."); ?></td>
                                        </tr>
                                        <?php
                                            endforeach;
                                            ?>
                                    </table>
                                    <br>
                                </td>
                            </tr>
                            <?php
                            endforeach;
                            ?>
                            <tr>
                                <th colspan="4">Total Keseluruhan</th>
                                <th align="right"><?= number_format($totalseluruh, 2, ",", "."); ?></th>

                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>