<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota Member</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        td {
            word-break: break-all;
        }

        .box {
            width: 8.6cm;
            height: 5.4cm;
            border: solid 1px black;
            background-image: url("<?= base_url('assets/images/bg-kartu.jpg') ?>");
            background-repeat: no-repeat;
            background-size: cover;
            color: #fff;
            font-style: arial;
            font-weight: bold;
        }
    </style>
</head>


<body onload="window.print();">
    <center>
        <div class="box">
            <table style="width: 100%; padding-left:10px; padding-top: 10px;">
                <tr>
                    <td colspan="3">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 80%;">
                                    <span style="font-weight: bold; font-style:italic; font-size:12px;"><u>MEMBER
                                            CARD</u></span><br>
                                    <span style="font-size:10px;">KOPERASI MART<br>JL.JENDERAL SUDIRMAN NO.52</span>
                                </td>
                                <td style="width: 20%; text-align: center; background-color: #fff;">
                                    <img src="<?= logo() ?>" style="width: 100%;">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr style="font-size: 10px; font-weight: bold;">
                    <td style="width: 20%;">No.Anggota</td>
                    <td style="width: 1%;">:</td>
                    <td><?= $data['kode_member']; ?></td>
                </tr>
                <tr style="font-size: 10px; font-weight: bold;">
                    <td style="width: 20%;">Nama</td>
                    <td style="width: 1%;">:</td>
                    <td><?= $data['nama_member']; ?></td>
                </tr>
                <tr style="font-size: 10px; font-weight: bold;">
                    <td style="width: 20%;">Alamat</td>
                    <td style="width: 1%;">:</td>
                    <td><?= $data['alamat_member']; ?></td>
                </tr>
                <tr>
                    <td style="text-align: left;" colspan="3">
                        <img src="<?= base_url() . getenv('PATH_BARCODE') . $data['barcode_member'] ?>" style="height: 50px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="font-size:11px; font-style: italic;">
                        <u>Kartu Member Ini Jangan Hilang, Bawalah selalu</u>
                    </td>
                </tr>
            </table>
        </div>
    </center>
</body>

</html>