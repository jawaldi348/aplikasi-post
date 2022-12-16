<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Faktur Penjualan</title>
    <style>
    @page print {
        margin: 0;
        padding: 0;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Calibri';
        font-weight: 500;
        font-size: 12px;
    }

    #toko {
        font-size: 14px;
    }

    td {
        word-break: break-all;
    }

    @media print {
        td {
            word-break: break-all;

        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Calibri';
            font-weight: 500;
            font-size: 11px;
        }

        #toko {
            font-size: 13px;
        }
    }
    </style>
    <script>
    function tutup() {
        if (event.keyCode == 27) {
            event.preventDefault();
            window.close();
        }
    }
    </script>
</head>

<body onload="window.print();" onkeydown="tutup();">
    <table align="center" style="width: 100%;">
        <tr>
            <td>
                <table align="left" style="width: 100%;">
                    <tr>
                        <td colspan="3" align="left" id="toko">
                            <?= $namatoko; ?><br><?= $alamattoko; ?><br> <?= "TELP $telptoko<br>HP $hptoko" ?>


                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" align="left">
                            <table style="width: 100%;">
                                <tr>
                                    <td><?= "NO : " . substr($faktur, 4); ?></td>
                                    <td style="text-align: right;"><?= date('d/m/Y', strtotime($tglfaktur)); ?></td>
                                </tr>
                                <tr>
                                    <td><?= "OPR : " . $namauser; ?></td>
                                    <td style="text-align: right;"><?= date('H:i:s', strtotime($tglfaktur)); ?></td>
                                </tr>
                                <?php
                                if (strlen($kodemember) > 0) :
                                ?>
                                <tr>
                                    <td colspan="2" style="text-align: left;">
                                        <?= "Member :" . $kodemember . ' / ' . $namamember; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: left;">
                                        <?= "Total Tabungan : " . number_format($totaldiskonmember, 0, ",", "."); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table align="left" style="width: 100%; border-top: 2px dotted #000;border-bottom: 2px dotted #000;">
                    <?php
                    $totaljualkotor = 0;
                    $totaljualbersih = 0;
                    $totalitem = 0;
                    foreach ($detailpenjualan->result_array() as $r) :
                        $jmlitem = $detailpenjualan->num_rows();
                        $totalitem = $totalitem + $r['detjualjml'];
                        $totaljualkotor = $totaljualkotor + $r['detjualsubtotalkotor'];
                        $totaljualbersih = $totaljualbersih + $r['detjualsubtotal'];
                    ?>
                    <tr>
                        <td align="left" colspan="3"><?= $r['namaproduk']; ?></td>
                    </tr>
                    <tr>
                        <td align="center"><?= number_format($r['detjualjml'], 0, ",", ".") . ' ' . $r['satnama']; ?>
                        </td>
                        <td align="center"><?= number_format($r['detjualharga'], 0, ",", "."); ?></td>
                        <td align="right"><?= number_format($r['detjualsubtotal'], 0, ",", "."); ?></td>
                    </tr>
                    <?php
                        if ($r['detjualdiskon'] != 0) {
                        ?>
                    <tr>
                        <td></td>
                        <td>#Disc</td>
                        <td align="right"><?= number_format($r['detjualdiskon'], 0, ",", "."); ?></td>
                    </tr>
                    <?php
                        }
                        ?>
                    <?php
                    endforeach;
                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table align="left" style="width: 100%;border-bottom: 2px dotted #000;">
                    <tr>
                        <td><?= "ITEM : $jmlitem ($totalitem)"; ?></td>
                        <td style="text-align: right;">Total :</td>
                        <td style="text-align: right;"><?= number_format($totaljualbersih, 0, ",", "."); ?></td>
                    </tr>
                    <?php
                    if ($jualdispersen != 0 || $jualdispersen != '0.00') {
                    ?>
                    <tr>
                        <td></td>
                        <td style="text-align: right;">#Dis :</td>
                        <td style="text-align: right;"><?= number_format($jualdiskon, 0, ",", "."); ?></td>
                    </tr>

                    <?php
                    }
                    ?>
                    <tr>
                        <td></td>
                        <td style="text-align: right;">Bayar :</td>
                        <td style="text-align: right;">
                            <?php
                            // $ambil_ratusan = substr($totalbersih, -2);
                            // if ($ambil_ratusan >= 01 && $ambil_ratusan <= 99) {
                            //     $total_bersih = $jualtotalbersih + (100 - $ambil_ratusan);
                            // } else {
                            //     $total_bersih = $jualtotalbersih;
                            // }
                            echo number_format($jualjmluangbayar, 0, ",", ".");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: right;">Kembali :</td>
                        <td style="text-align: right;"><?= number_format($jualjmluangsisa, 0, ",", "."); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="left">
                <span style="font-style: italic;">Barang yang sudah dibeli tidak bisa ditukas/dikembalikan<br>Terima
                    kasih telah menjadi pelanggan kami :)</span>
            </td>
        </tr>
    </table>

</html>