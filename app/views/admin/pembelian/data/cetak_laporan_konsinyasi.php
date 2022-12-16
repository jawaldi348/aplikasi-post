<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Konsinyasi - <?= $nofaktur; ?></title>
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

    hr {
        border: 0;
        border-top: 3px double #000;
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
                            <?= $namatoko; ?><br><?= $alamattoko; ?>


                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" align="left">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="width: 15%;">Faktur</td>
                                    <td style="width: 1%;">:</td>
                                    <td><?= $nofaktur; ?></td>
                                </tr>
                                <tr>
                                    <td>Tgl</td>
                                    <td>:</td>
                                    <td><?= $tanggal; ?></td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>:</td>
                                    <td><?= $totalbeli; ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table align="center" cellpadding="0" style="width: 100%;" border="0">
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                    <tr style="border-bottom: 2px dotted #000;">
                        <td align="center">Tanggal<br>Pembayaran</td>
                        <td align="left">Jml<br>Bayar</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td align="center"><?= $tanggalbayarhutang; ?></td>
                        <td align="left"><?= $jumlahbayarhutang; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">Sisa Hutang</td>
                        <td align="lef"><?= $sisabayar; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="left">
                <table style="width: 100%;">
                    <tr>
                        <td><?= $tglbayar; ?><br><br><br><br>
                            <?= "(" . $this->session->userdata('namalengkapuser') . ")"; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>



</html>