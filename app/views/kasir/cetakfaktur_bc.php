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
        font-family: 'Courier New', Courier, monospace;
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

<body onkeydown="tutup();">
    <table align="center" style="font-size: 11pt; width: 100%;">
        <tr>
            <td>
                <table align="left" style="font-size: 11pt; width: 100%;">
                    <tr>
                        <td colspan="3" align="left">
                            <?= $namatoko; ?><br><?= $alamattoko; ?><br> <?= "TELP $telptoko<br>HP $hptoko" ?>


                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" align="left">
                            <table style="width: 100%;">
                                <tr>
                                    <td><?= "NO :" . substr($faktur, 4); ?></td>
                                    <td style="text-align: right;"><?= date('d/m/Y', strtotime($tglfaktur)); ?></td>
                                </tr>
                                <tr>
                                    <td><?= "OPR :" . $namauser; ?></td>
                                    <td style="text-align: right;"><?= date('H:i:s', strtotime($tglfaktur)); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table align="left" style="font-size: 7pt; width: 100%;">
                    <tr>
                        <td align="left">Item</td>
                        <td align="center">Qty</td>
                        <td align="right">Harga</td>
                        <td align="right">Sub.Total</td>
                    </tr>
                    <?php
                    $totaljual = 0;
                    $jmlitem = 0;
                    $totalitem = 0;
                    foreach ($detaildata as $r) {
                        $totaljual += $r->subtotal;
                        $totalitem = $totalitem + $r->jml;
                        $jmlitem = $jmlitem + 1;
                    ?>
                    <tr>
                        <td align="left"><?= $r->namaproduk ?></td>
                        <td align="center"><?= $r->jml . '&nbsp;' . $r->namasatuan ?></td>
                        <td align="right"><?= number_format($r->hargajual, 0); ?></td>
                        <td align="right"><?= number_format($r->subtotal, 0); ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td align="right" colspan="3">Total</td>
                        <td align="right"><?= number_format($totaljual, 0, ",", "."); ?></td>
                    </tr>
                    <tr>
                        <td align="right" colspan="3">Disc(%)</td>
                        <td align="right"><?= $dispersen; ?></td>
                    </tr>
                    <tr>
                        <td align="right" colspan="3">Disc(Rp)</td>
                        <td align="right"><?= $disuang; ?></td>
                    </tr>
                    <tr>
                        <td align="right" colspan="3">Bayar</td>
                        <td align="right"><?= $jmluangbayar; ?></td>
                    </tr>
                    <tr>
                        <td align="right" colspan="3">Sisa</td>
                        <td align="right"><?= $jmluangsisa; ?></td>
                    </tr>
                </table>
            </td>

        </tr>
        <tr>
            <td>
                <hr>
                <br>
                <table align="left" style="font-size: 7pt; width: 100%;">
                    <tr>
                        <td style="width: 20%;">Total Qty :</td>
                        <td><?= $totalitem . "( $jmlitem item )"; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">Kode Struk :</td>
                        <td><?= $faktur; ?></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">Cashier :</td>
                        <td><?= $namauser; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center">
                <br>
                <span style="font-style: italic;">Terima Kasih Atas Kunjungannya</span>
            </td>
        </tr>
    </table>



</html>