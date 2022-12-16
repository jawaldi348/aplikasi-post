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
        <table style="width: 100%; border-collapse: collapse;" border="1" cellpadding="4">
            <thead>
                <tr style="background-color: #d9dbde;">
                    <th rowspan="2" style="width: 3%;">No</th>
                    <th rowspan="2" style="width: 10%;">Tanggal</th>
                    <th colspan="5">Hari ini (Net)</th>
                    <th colspan="2">GM Hari ini</th>
                </tr>
                <tr style="background-color: #d9dbde;">
                    <th style="width: 10%;">HPP</th>
                    <th style="width: 10%;">SALES</th>
                    <th style="width: 10%;">QTY</th>
                    <th style="width: 10%;">STD</th>
                    <th style="width: 10%;">APC</th>
                    <th style="width: 10%;">RP</th>
                    <th style="width: 10%;">%</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                if ($datapenjualan->num_rows() > 0) {
                    foreach ($datapenjualan->result_array() as $row) :
                        $tanggal = date('Y-m-d', strtotime($row['jualtgl']));
                        // Nilai HPP
                        $query_hpppenjualan = $this->db->query("SELECT detjualfaktur,SUM(detjualjml * detjualhargabeli) AS hpp FROM penjualan_detail JOIN penjualan ON detjualfaktur=jualfaktur WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') = '$tanggal'")->row_array();
                        $hpppenjualan = $query_hpppenjualan['hpp'];

                        // Nilai QTY
                        $query_qty = $this->db->query("SELECT SUM(detjualjml * detjualsatqty) AS qty FROM penjualan_detail JOIN penjualan ON detjualfaktur=jualfaktur 
                        WHERE DATE_FORMAT(jualtgl,'%Y-%m-%d') = '$tanggal'")->row_array();

                        $apc = $row['sales'] / $row['jmltransaksi'];
                        $grossmargin = $row['sales'] - $hpppenjualan;
                        $persentase = ($grossmargin / $hpppenjualan) * 100;

                ?>
                <tr style="font-size: 9pt;">
                    <td style="text-align: center;"><?= $nomor++; ?></td>
                    <td style="text-align: center;"><?= $row['tanggal']; ?></td>
                    <td style="text-align: right;"><?= number_format($hpppenjualan, 0, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($row['sales'], 0, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($query_qty['qty'], 0, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($row['jmltransaksi'], 0, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($apc, 0, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($grossmargin, 0, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($persentase, 2, ",", ".") . "%"; ?></td>
                </tr>
                <?php
                    endforeach;
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>