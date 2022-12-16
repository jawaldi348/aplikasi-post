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
                    <td style="width: 4%;">No</td>
                    <td style="width: 10%;">Tanggal</td>
                    <td style="width: 10%;">Faktur</td>
                    <td style="width: 10%;">Keterangan</td>
                    <td style="width: 10%;">H.Jual</td>
                    <td style="width: 10%;">HPP</td>
                    <td style="width: 10%;">Diskon</td>
                    <td style="width: 10%;">Laba Rugi</td>
                </tr>
            </thead>
            <tbody>


                <?php
                $nomor = 1;
                $totalhargapenjualan = 0;
                $total_hpppenjualan = 0;
                $total_seluruhdiskon = 0;
                $total_seluruhlabarugi = 0;
                foreach ($datapenjualan->result_array() as $row) :
                    $totalhargapenjualan = $totalhargapenjualan + $row['hargapenjualan'];
                    $tanggal = date('Y-m-d', strtotime($row['jualtgl']));
                    $faktur = $row['faktur'];

                    // Mencari HPP Detail Penjualan
                    $query_Hpp = $this->db->query("SELECT SUM(detjualjml * detjualhargabeli) AS hpp FROM penjualan_detail WHERE detjualfaktur = '$faktur'")->row_array();
                    $hpppenjualan = $query_Hpp['hpp'];

                    $hitung_labarugi = $row['hargapenjualan'] - $hpppenjualan - $row['diskon'];
                    // End
                ?>
                <tr>
                    <td><?= $nomor++; ?></td>
                    <td><?= $row['tanggal']; ?></td>
                    <td><?= $row['faktur']; ?></td>
                    <td><?= $row['ket']; ?></td>
                    <td style="text-align: right;"><?= number_format($row['hargapenjualan'], 2, ",", "."); ?></td>
                    <td style="text-align: right;">
                        <?= number_format($hpppenjualan, 2, ",", "."); ?>
                    </td>
                    <td style="text-align: right;">
                        <?= number_format($row['diskon'], 2, ",", "."); ?>
                    </td>
                    <td style="text-align: right;">
                        <?= number_format($hitung_labarugi, 2, ",", "."); ?>
                    </td>
                </tr>
                <?php
                    $total_hpppenjualan = $total_hpppenjualan + $hpppenjualan;
                    $total_seluruhdiskon += $row['diskon'];
                    $total_seluruhlabarugi += $hitung_labarugi;
                endforeach; ?>
            </tbody>
            <tfoot>

                <tr style="background-color: #bab9b5; font-weight: bold;">
                    <td colspan="4" style="text-align: center;">Total Keseluruhan</td>
                    <td style="text-align: right;"><?= number_format($totalhargapenjualan, 2, ",", "."); ?></td>
                    <td style="text-align: right;">
                        <?= number_format($total_hpppenjualan, 2, ",", "."); ?>
                    </td>
                    <td style="text-align: right;">
                        <?= number_format($total_seluruhdiskon, 2, ",", "."); ?>
                    </td>
                    <td style="text-align: right;">
                        <?= number_format($total_seluruhlabarugi, 2, ",", "."); ?>
                    </td>
                </tr>
            </tfoot>

        </table>
    </div>
</body>

</html>