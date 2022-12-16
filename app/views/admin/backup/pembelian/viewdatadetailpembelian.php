<?= form_open('admin/pembelian/selesaitransaksi', ['class' => 'formselesaitransaksi']) ?>
<input type="hidden" name="nofakturpembelian" id="nofakturpembelian" value="<?= $nofaktur; ?>">
<table class="table table-sm table-striped" style="width: 100%;">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barcode</th>
            <th>Item</th>
            <th>Jumlah</th>
            <th>Hrg.Beli(Rp.)</th>
            <th>Tgl.Kadaluarsa</th>
            <th style="width: 15%;">Sub.Total(Rp.)</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php $nomor = 0;
        $total = 0;
        foreach ($tampildata as $row) : $nomor++;
            $total = $total + $row->detsubtotal; ?>
        <tr>
            <td><?= $nomor; ?></td>
            <td><?= $row->detkodebarcode; ?></td>
            <td><?= $row->namaproduk; ?></td>
            <td><?= $row->detjml . '&nbsp;' . $row->satnama; ?></td>
            <td><?= number_format($row->dethrgbeli, 0, ",", ".") ?></td>
            <td><?= date('d-m-Y', strtotime($row->dettglexpired)); ?></td>
            <td style="text-align: right;"><?= number_format($row->detsubtotal, 0, ",", ".") ?></td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="hapusitem('<?= $row->detid ?>','<?= $row->detkodebarcode ?>','<?= $row->namaproduk ?>');">
                    <i class="fa fa-trash-alt"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="6">Total(Rp.)</th>
            <td style="font-weight: bold; text-align: right;">
                <?= number_format($total, 0, ",", ".") ?>
                <input type="hidden" name="total" id="total" value="<?= $total ?>">
            </td>
        </tr>
        <tr>
            <th colspan="6" style="text-align: right;">Pajak (%)</th>
            <td>
                <input type="number" name="pph" id="pph" class="form-control" value="0">
            </td>
        </tr>
        <tr>
            <th colspan="6" style="text-align: right;">Diskon (%)</th>
            <td>
                <input type="number" name="diskonpersen" id="diskonpersen" class="form-control" value="0">
            </td>
        </tr>
        <tr>
            <th colspan="6" style="text-align: right;">Diskon (Rp)</th>
            <td>
                <input type="number" name="diskonrp" id="diskonrp" class="form-control" value="0">
            </td>
        </tr>
        <tr>
            <th colspan="6" style="text-align: right;">Total Seluruhnya (Rp)</th>
            <td>
                <input type="text" name="totalsemua" id="totalsemua" class="form-control" readonly="readonly"
                    style="text-align: right;" value="<?= number_format($total, 0, ",", ".") ?>">
            </td>
        </tr>
    </tbody>
</table>
<div class="msg" style="display: none;"></div>
<table class="table table-sm table-striped">
    <tr>
        <td>Pembayaran</td>
        <td>Jatuh Tempo</td>
        <td>Tanggal</td>
        <td></td>
    </tr>
    <tr>
        <td>
            <select name="bayar" id="bayar" class="form-control">
                <option value="">-Pilih-</option>
                <option value="T">Tunai</option>
                <option value="K">Kredit</option>
            </select>
        </td>
        <td>
            <select name="jatuhtempo" id="jatuhtempo" class="form-control">
                <option value="">-Pilih-</option>
                <option value="1">1 Minggu</option>
                <option value="2">2 Minggu</option>
                <option value="3">4 Minggu</option>
                <option value="4">Custom</option>
            </select>
        </td>
        <td>
            <input type="text" name="tgljatuhtempo" id="tgljatuhtempo" class="form-control" disabled="disabled">
        </td>
        <td>
            <button type="submit" class="btn btn-success btnselesaipembelian">
                <i class="fa fa-save"></i> Selesai Pembelian
            </button>
        </td>
    </tr>
</table>
<?= form_close(); ?>
<script>
function hapusitem(id, kode, nama) {
    Swal.fire({
        title: 'Hapus Item',
        text: `Yakin item ${kode} / ${nama} dihapus ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Batalkan !',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: "./hapusitem",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $.toast({
                            heading: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            loader: true,
                            position: 'bottom-right'
                        });
                        tampildetailpembelian();
                    }
                }
            });
        }
    });
}

$(document).ready(function() {
    $('#tgljatuhtempo').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false
    });

    $('#jatuhtempo').change(function(e) {
        let d = $('#jatuhtempo').val();

        if (d == "4") {
            $('#tgljatuhtempo').removeAttr('disabled');
            $('#tgljatuhtempo').focus();
        } else {
            $('#tgljatuhtempo').attr('disabled', 'disabled');
            $('#tgljatuhtempo').val('');
        }
    });


    // Selesaikan Transaksi Pembelian 
    $('.formselesaitransaksi').submit(function(e) {
        let nofakturpembelian = $('#nofakturpembelian').val();
        if (nofakturpembelian == "" || nofakturpembelian == NULL) {
            Swal.fire('Tidak ada transaksi yang diselesaikan');
            return false;
        } else {
            Swal.fire({
                title: 'Selesai Transaksi',
                text: `Selesaikan transaki pembelian dengan faktur ${nofakturpembelian} ini ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "post",
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        dataType: "json",
                        cache: false,
                        beforeSend: function() {
                            $('.btnselesaipembelian').attr('disabled', 'disabled');
                            $('.btnselesaipembelian').html(
                                '<i class="fa fa-spin fa-spinner"></i>');
                        },
                        success: function(response) {
                            if (response.error) {
                                $('.msg').fadeIn();
                                $('.msg').html(response.error);
                            }
                        },
                        complete: function() {
                            $('.btnselesaipembelian').removeAttr('disabled');
                            $('.btnselesaipembelian').html('Selesai Transaksi');
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" +
                                thrownError);
                        }
                    });
                }
            });
            return false;
        }
    });
    // $('.btnselesaipembelian').click(function(e) {
    //     let nofakturpembelian = $('#nofaktur').val();
    //     Swal.fire({
    //         title: 'Selesai Transaksi',
    //         text: `Selesaikan transaki pembelian dengan faktur ${nofakturpembelian} ini ?`,
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Ya, Lanjutkan !',
    //         cancelButtonText: 'Tidak'
    //     }).then((result) => {
    //         if (result.value) {
    //             $.ajax({
    //                 type: "post",
    //                 url: "./selesaitransaksi",
    //                 data: {
    //                     nofaktur: nofakturpembelian,
    //                     totalsemua: $('#totalsemua').val(),
    //                     diskonpersen: $('#diskonpersen').val(),
    //                     diskonrp: $('#diskonrp').val(),
    //                     pph: $('#pph').val(),
    //                     tgljatuhtempo: $('#tgljatuhtempo').val(),
    //                     bayar: $('#bayar').val(),
    //                     jatuhtempo: $('#jatuhtempo').val()
    //                 },
    //                 dataType: "json",
    //                 success: function(response) {
    //                     if (response.sukses) {
    //                         Swal.fire({
    //                             title: 'Berhasil',
    //                             text: response.sukses,
    //                             icon: 'warning',
    //                             confirmButtonColor: '#3085d6',
    //                             confirmButtonText: 'Ok'
    //                         }).then((result) => {
    //                             if (result.value) {
    //                                 window.location.reload();
    //                             }
    //                         })
    //                     }
    //                 },
    //                 error: function(e) {
    //                     alert(e);
    //                 }
    //             });
    //         }
    //     });
    // });
});

$(document).on('keyup', '#pph', function(e) {
    let total = document.getElementById('total');
    let totalsemua = document.getElementById('totalsemua');
    let pph = document.getElementById('pph');
    totalsemua.value = parseInt(total.value) + (parseInt(total.value) * parseInt(pph.value) / 100);
});
$(document).on('keyup', '#diskonpersen', function(e) {
    let total = document.getElementById('total');
    let totalsemua = document.getElementById('totalsemua');
    let pph = document.getElementById('pph');
    let diskonpersen = document.getElementById('diskonpersen');

    totalsemua.value = parseInt(total.value) + (parseInt(total.value) * parseInt(pph.value) / 100) - (parseInt(
        total.value) * parseInt(diskonpersen.value) / 100);
});

$(document).on('keyup', '#diskonrp', function(e) {
    let total = document.getElementById('total');
    let totalsemua = document.getElementById('totalsemua');
    let pph = document.getElementById('pph');
    let diskonrp = document.getElementById('diskonrp');

    totalsemua.value = parseInt(total.value) + (parseInt(total.value) * parseInt(pph.value) / 100) - parseInt(
        diskonrp.value);
});
</script>