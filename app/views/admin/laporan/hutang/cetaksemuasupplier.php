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
                <td style="width: 15%;">Periode</td>
                <td style="width: 1%;">:</td>
                <td>
                    <?= $periode; ?>
                </td>
            </tr>
            <tr>
                <td>Tgl.Cetak</td>
                <td>:</td>
                <td><?= date('d-m-Y'); ?></td>
            </tr>
        </table>
        <table border="1" style="width: 100%; font-size: 11pt; border-collapse: collapse; border:1px solid #000;"
            cellpadding="5">
            <thead>
                <tr style="background-color: #bab9b5;">
                    <th style="width: 4%;">No</th>
                    <th colspan="2" style="width: 10%;">Nama Supplier</th>
                    <th style="width: 10%;">Total Hutang (Rp.)</th>
                    <th style="width: 10%;">Total Bayar (Rp.)</th>
                    <th style="width: 10%;">Sisa Hutang (Rp.)</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                $totalsisahutang = 0;
                if ($datahutang->num_rows() > 0) {
                    foreach ($datahutang->result_array() as $row) :
                        $totalsisahutang += $row['sisahutang'];
                        $idpemasok = $row['idpemasok'];
                        $query_detailfaktur = $this->db->query("SELECT nofaktur,tglbeli,tgljatuhtempo,
                    CASE statusbayar WHEN '0' THEN totalbersih ELSE totalbersih END AS jumlahhutang,
                    CASE statusbayar WHEN '1' THEN totalbersih ELSE 0 END AS jumlahbayar 
                    FROM pembelian WHERE idpemasok = '$idpemasok' AND jenisbayar='K' ORDER BY tglbeli ASC");
                ?>
                <tr style="font-weight: bold;">
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td colspan="2"><?= $row['nama']; ?></td>
                    <td style="text-align: right;"><?= number_format($row['totalhutang'], 2, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($row['totalbayar'], 2, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($row['sisahutang'], 2, ",", "."); ?></td>
                </tr>
                <?php foreach ($query_detailfaktur->result_array() as $r) :
                            $sisabayar = $r['jumlahhutang'] - $r['jumlahbayar'];
                        ?>
                <tr>
                    <td style="text-align: center;"><?= date('d-m-Y', strtotime($r['tglbeli'])); ?></td>
                    <td><?= $r['nofaktur']; ?></td>
                    <td><?= date('d-m-Y', strtotime($r['tgljatuhtempo'])); ?></td>
                    <td style="text-align: right;"><?= number_format($r['jumlahhutang'], 2, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($r['jumlahbayar'], 2, ",", "."); ?></td>
                    <td style="text-align: right;"><?= number_format($sisabayar, 2, ",", "."); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endforeach; ?>
                <tr>
                    <th colspan="5" style="background-color: #bab9b5;">
                        Total Sisa Hutang
                    </th>
                    <td style="text-align: right; font-weight: bold;">
                        <?= number_format($totalsisahutang, 2, ",", "."); ?></td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>
</body>

</html>