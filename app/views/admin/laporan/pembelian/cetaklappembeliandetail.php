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
                <td style="width: 5%;">Periode</td>
                <td style="width: 1%;">:</td>
                <td style="width: 19%;"><?= $periode; ?></td>
            </tr>
        </table>
        <table style="width: 100%; border-collapse: collapse;" border="1">
            <thead>
                <tr style="background-color: #d9dbde;">
                    <th style="width: 3%;">No</th>
                    <th style="width: 10%;">Faktur</th>
                    <th style="width: 10%;">Tanggal</th>
                    <th style="width: 10%;">Pemasok</th>
                    <th style="width: 7%;">Jenis<br>Bayar</th>
                    <th style="width: 10%;">Total(Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                $total_totalbersih = 0;
                if ($datapembelian->num_rows() > 0) {
                    foreach ($datapembelian->result_array() as $r) :
                        $total_totalbersih += $r['totalbersih'];
                ?>
                <tr style="font-size:10pt;">
                    <td style="text-align: center;"><?= $nomor++; ?></td>
                    <td><?= $r['nofaktur']; ?></td>
                    <td style="text-align: center;"><?= date('d-m-Y', strtotime($r['tglbeli'])); ?></td>
                    <td><?= $r['namapemasok']; ?></td>
                    <td style="text-align: center;">
                        <?php
                                if ($r['jenisbayar'] == 'T') {
                                    echo '<span style="color:green; font-weight:bold;">Tunai</span>';
                                } else {
                                    echo '<span style="color:blue; font-weight:bold;">Kredit</span>';
                                }
                                ?>
                    </td>
                    <td style="text-align: right;">
                        <?= number_format($r['totalbersih'], 2, ",", "."); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <table style="width: 100%; border-collapse: collapse; font-size: 10pt; " border="1"
                            cellpadding="3">
                            <thead>
                                <tr style="background-color: #d9dbde;">
                                    <th style="width: 10%;">Kode</th>
                                    <th style="width: 15%;">Produk</th>
                                    <th style="width: 5%;">Jumlah</th>
                                    <th style="width: 10%;">Harga Beli<br>(Rp.)</th>
                                    <th style="width: 5%;">Diskon</th>
                                    <th style="width: 10%;">Sub.Total<br>(Rp.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                        // Menampilkan Detail Item
                                        $query_detail = $this->db->query("SELECT detkodebarcode AS kode,namaproduk,CONCAT(ROUND(detjml,0),' ',satnama) AS jumlah,dethrgbelikotor AS hargabeli,((dethrgbelikotor * detdispersen / 100)+detdisuang) AS diskon, detsubtotal AS subtotal FROM pembelian_detail JOIN produk ON detkodebarcode=kodebarcode JOIN satuan ON detsatid=satuan.`satid` WHERE detfaktur='$r[nofaktur]'");

                                        foreach ($query_detail->result_array() as $d) :
                                        ?>
                                <tr>
                                    <td><?= $d['kode']; ?></td>
                                    <td><?= $d['namaproduk']; ?></td>
                                    <td><?= $d['jumlah']; ?></td>
                                    <td style="text-align: right;"><?= number_format($d['hargabeli'], 2, ",", "."); ?>
                                    </td>
                                    <td style="text-align: right;"><?= number_format($d['diskon'], 2, ",", "."); ?></td>
                                    <td style="text-align: right;"><?= number_format($d['subtotal'], 2, ",", "."); ?>
                                    </td>

                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <br>
                    </td>
                </tr>


                <?php
                    endforeach;
                    ?>
                <tr>
                    <th colspan="5" style="background-color: #d9dbde;">Total</th>
                    <td style="text-align: right; font-weight: bold;">
                        <?= number_format($total_totalbersih, 2, ",", "."); ?>
                    </td>
                </tr>
                <?php
                } else {
                    echo '<tr><td colspan="6">Data tidak ada...</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>