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
            <tr style="background-color: #bab9b5;">
                <td style="width: 4%;">No</td>
                <td style="width: 10%;">Tanggal</td>
                <td style="width: 20%;">No.Faktur</td>
                <td style="width: 10%;">Keterangan</td>
                <td style="width: 10%;">H.Jual</td>
                <td style="width: 10%;">HPP</td>
                <td style="width: 10%;">Diskon</td>
                <td style="width: 10%;">Laba Rugi</td>
            </tr>
            <?php
            $nomor = 1;
            $totalhargapenjualan = 0;
            $total_hpp = 0;
            $total_seluruhdiskon = 0;
            $total_seluruhlabarugi = 0;
            foreach ($datapenjualan->result_array() as $row) :
                $faktur = $row['faktur'];

                $ambil_detail_penjualan = $this->db->get_where('penjualan_detail', ['detjualfaktur' => $faktur]);
                $total_hargabeli = 0;
                $total_diskon = 0;
                foreach ($ambil_detail_penjualan->result_array() as $rr) :
                    $total_hargabeli = $total_hargabeli + $rr['detjualhargabeli'];
                    $total_diskon = $total_diskon + $rr['detjualdiskon'];
                endforeach;

                if (strlen($row['jualmemberkode']) > 0) {
                    $hitungdiskonmember = ($diskon * $row['hargapenjualan']) / 100 + $row['jualdiskon'];
                    $tampil_totaldiskon = $hitungdiskonmember + $total_diskon;
                } else {
                    $tampil_totaldiskon = $row['jualdiskon'] + $total_diskon;
                }

                $hitunglabarugi = $row['hargapenjualan'] - $total_hargabeli - $tampil_totaldiskon;

                $totalhargapenjualan = $totalhargapenjualan + $row['hargapenjualan'];
                $total_hpp = $total_hpp + $total_hargabeli;
                $total_seluruhdiskon = $total_seluruhdiskon + $tampil_totaldiskon;
                $total_seluruhlabarugi = $total_seluruhlabarugi + $hitunglabarugi;
            ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['tanggal']; ?></td>
                <td><?= $row['faktur']; ?></td>
                <td><?= $row['ket']; ?></td>
                <td style="text-align: right;"><?= number_format($row['hargapenjualan'], 2, ".", ","); ?></td>
                <td style="text-align: right;"><?= number_format($total_hargabeli, 2, ".", ","); ?></td>
                <td style="text-align: right;"><?= number_format($tampil_totaldiskon, 2, ".", ","); ?></td>
                <td style="text-align: right;"><?= number_format($hitunglabarugi, 2, ".", ","); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr style="background-color: #bab9b5; font-weight: bold;">
                <td colspan="4" style="text-align: center;">Total Keseluruhan</td>
                <td style="text-align: right;"><?= number_format($totalhargapenjualan, 2, ".", ","); ?></td>
                <td style="text-align: right;"><?= number_format($total_hpp, 2, ".", ","); ?></td>
                <td style="text-align: right;"><?= number_format($total_seluruhdiskon, 2, ".", ","); ?></td>
                <td style="text-align: right;"><?= number_format($total_seluruhlabarugi, 2, ".", ","); ?></td>
            </tr>
        </table>
    </div>
</body>

</html>