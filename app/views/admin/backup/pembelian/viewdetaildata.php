<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <h4 class="card-header mt-0"> <button type="button" class="btn btn-sm btn-round btn-outline-primary"><i
                    class="fa fa-recycle btnreload" style="cursor: pointer;" title="Reload"></i></button>
            Data
            Item
            Pembelian</h4>
        <div class="card-body">
            <?= form_open('admin/pembelian/selesaitransaksi', ['class' => 'formselesaitransaksi']) ?>
            <div class="table-responsive text-nowrap">
                <!--Table-->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%;">#</th>
                            <th>Kode Barcode</th>
                            <th>Item</th>
                            <th style="text-align: right; width: 10%;">Expired Date</th>
                            <th style="text-align: right; width: 15%;">Jml/Satuan</th>
                            <th style="text-align: right; width: 15%;">Hrg.Beli(Rp)</th>
                            <th style="text-align: right; width: 20%;">Sub.Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor = 0;
                        $totalseluruh = 0;
                        foreach ($data->result_array() as $d) : $nomor++;
                            $totalseluruh = $totalseluruh + $d['detsubtotal'] ?>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="hapusitem('<?= $d['detid'] ?>')">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                                <?= $nomor; ?>
                            </td>
                            <td><?= $d['detkodebarcode']; ?></td>
                            <td><?= $d['namaproduk']; ?></td>
                            <td><?= date('d-m-Y', strtotime($d['dettglexpired'])); ?></td>
                            <td style="text-align: right;">
                                <?= number_format($d['detjml'], 2, ",", ".") . ' / ' . $d['satnama'] ?></td>
                            <td style="text-align: right;"><?= number_format($d['dethrgbeli'], 2, ",", "."); ?></td>
                            <td style="text-align: right;"><?= number_format($d['detsubtotal'], 2, ",", "."); ?></td>
                            <td>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" style="text-align: right;">Total (Rp)</th>
                            <th style="text-align: right;">
                                <input type="hidden" value="<?= $faktur ?>" name="faktur" id="faktur">
                                <?= number_format($totalseluruh, 2, ",", "."); ?>
                                <input type="hidden" name="totalseluruh" id="totalseluruh" value="<?= $totalseluruh ?>">
                            </th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align: right;">PHP (%)</th>
                            <td>
                                <input type="text" name="pph" value="0" id="pph" class="form-control"
                                    data-toggle="tooltip" data-placement="top"
                                    data-original-title="Isi 0, jika tidak ada PPH. Tekan Enter untuk menghitung">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align: right;">Diskon (%)</th>
                            <td>
                                <input type="text" name="dispersen" value="0" id="dispersen" class="form-control"
                                    data-toggle="tooltip" data-placement="top"
                                    data-original-title="Isi 0, jika tidak ada Diskon Persen. Tekan Enter untuk menghitung"
                                    readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align: right;">Diskon (Rp)</th>
                            <td>
                                <input type="text" name="disrp" value="0" id="disrp" class="form-control"
                                    data-toggle="tooltip" data-placement="top"
                                    data-original-title="Isi 0, jika tidak ada Diskon Rupiah. Tekan Enter untuk menghitung"
                                    readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align: right;">Total Pembayaran</th>
                            <td>
                                <input type="text" name="totalpembayaran" id="totalpembayaran"
                                    value="<?= number_format($totalseluruh, 2, ",", "."); ?>"
                                    style="text-align: right; font-weight: bold; font-size:14pt;" readonly="readonly"
                                    class="form-control-lg form-control" data-a-dec="," data-a-sep=".">
                                <input type="hidden" name="totalpembayaranx" id="totalpembayaranx"
                                    value="<?= $totalseluruh; ?>">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row justify-content-end">
                <div class="col-sm-3">
                    <label for="">Jenis Pembayaran</label>
                    <select name="jenispembayaran" id="jenispembayaran" class="form-control">
                        <option value="">-Pilih-</option>
                        <option value="T">Tunai</option>
                        <option value="K">Kredit</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="">Jatuh Tempo</label>
                    <select name="jatuhtempo" id="jatuhtempo" class="form-control">
                        <option value="" selected>-Pilih-</option>
                        <option value="1">1 Minggu</option>
                        <option value="2">2 Minggu</option>
                        <option value="3">3 Minggu</option>
                        <option value="4">4 Minggu</option>
                        <option value="5">Custom</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="">Input Tanggal</label>
                    <input type="date" name="tgljatuhtempo" id="tgljatuhtempo" class="form-control"
                        placeholder="Tgl.Jatuh Tempo">
                </div>
                <div class="col-sm-3">
                    <label for="">Aksi</label>
                    <div class="input-group">
                        <button type="submit" class="btn btn-success btnselesaitransaksi">
                            <i class="fa fa-save"></i> Selesai Transaksi
                        </button>
                    </div>
                </div>
            </div>
            <?= form_close() ?>
            <div class="msgdetail" style="display: none;"></div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    $('#totalpembayaran').autoNumeric('init');
    $('#disrp').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '0'
    });

    $('#tgljatuhtempo').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        month: true
    });

    $('#jenispembayaran').change(function(e) {
        e.preventDefault();
        let jenispembayaran = $(this).val();

        if (jenispembayaran == 'T') {
            $('#jatuhtempo').attr('disabled', 'disabled');
            $('#jatuhtempo').val('');
            $('#tgljatuhtempo').attr('disabled', 'disabled');
        }

        if (jenispembayaran == 'K') {
            $('#jatuhtempo').removeAttr('disabled');
            $('#jatuhtempo').val('');
            $('#tgljatuhtempo').removeAttr('disabled');
        }
    });

    $('#jatuhtempo').change(function(e) {
        e.preventDefault();
        let jatuhtempo = $(this).val();

        if (jatuhtempo == '5') {
            $('#tgljatuhtempo').val('');
            $('#tgljatuhtempo').focus();
        } else {
            $('#tgljatuhtempo').val('');
        }

    });

    // Selesai transaksi
    $('.formselesaitransaksi').submit(function(e) {
        Swal.fire({
            title: 'Selesai Transaksi',
            text: "Yakin selesaikan transaksi faktur pembelian ini ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya !',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function() {
                        $('.btnselesaitransaksi').attr('disabled', 'disabled');
                        $('.btnselesaitransaksi').html(
                            '<i class="fa fa-spin fa-spinner"></i>')
                    },
                    success: function(response) {
                        if (response.error) {
                            $('.msgdetail').html(response.error).fadeIn();
                        }

                        if (response.sukses) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.sukses,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok, Lanjut !'
                            }).then((result) => {
                                if (result.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    },
                    complete: function() {
                        $('.btnselesaitransaksi').removeAttr('disabled');
                        $('.btnselesaitransaksi').html(
                            '<i class="fa fa-save"></i> Selesai Transaksi')
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" +
                            thrownError);
                    }
                });
            }
        })
        return false;
    });
});
$(document).on('click', '.btnreload', function(e) {
    tampildatadetail();
});

$(document).on('keydown', '#pph', function(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        // let pph = $(this).val();
        // let totalseluruh = $('#totalseluruh').val();
        // let hitungpph;

        // hitungpph = parseFloat(totalseluruh) + (parseFloat(totalseluruh) * parseFloat(pph) / 100);
        // $('#totalpembayaranx').val(hitungpph.toFixed(2));
        // $('#totalpembayaran').autoNumeric('set', hitungpph.toFixed(2));
        hitungtotalpembayaranbersih();
    }
});

$(document).on('keydown', '#dispersen', function(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        hitungtotalpembayaranbersih();
        // $('#disrp').val('0');
        // let diskonpersen = $(this).val();
        // let pph = $('#pph').val();
        // let totalseluruh = $('#totalseluruh').val();
        // let hitungpph;
        // hitungpph = parseFloat(totalseluruh) + (parseFloat(totalseluruh) * parseFloat(pph) / 100);
        // let hitungdiskonpersen;

        // hitungdiskonpersen = hitungpph - (hitungpph * parseFloat(diskonpersen) / 100);
        // $('#totalpembayaranx').val(hitungdiskonpersen.toFixed(2));
        // $('#totalpembayaran').autoNumeric('set', hitungdiskonpersen.toFixed(2));
    }
});

$(document).on('click', '#dispersen', function() {
    $(this).removeAttr('readonly');
    $('#disrp').val('0');
    $('#disrp').attr('readonly', 'readonly');
});

$(document).on('keydown', '#disrp', function(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        hitungtotalpembayaranbersih();
        // $('#dispersen').val('0');
        // let disrp = $(this).val();
        // let hilang_koma = disrp.replace(",", "");
        // // alert(hilang_koma);

        // let pph = $('#pph').val();
        // let totalseluruh = $('#totalseluruh').val();
        // let hitungpph;
        // hitungpph = parseFloat(totalseluruh) + (parseFloat(totalseluruh) * parseFloat(pph) / 100);
        // let hitungdiskonpersen;

        // hitungdiskonpersen = hitungpph - parseInt(hilang_koma);
        // $('#totalpembayaranx').val(hitungdiskonpersen.toFixed(2));
        // $('#totalpembayaran').autoNumeric('set', hitungdiskonpersen.toFixed(2));
    }
});

$(document).on('click', '#disrp', function() {
    $(this).removeAttr('readonly');
    $('#dispersen').val('0');
    $('#dispersen').attr('readonly', 'readonly');
});

function hapusitem(id) {
    Swal.fire({
        title: 'Hapus Item',
        text: "Yakin di hapus ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya !',
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
                        tampildatadetail();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    })
}

function hitungtotalpembayaranbersih() {
    let totalseluruh = $('#totalseluruh').val();
    let pph = $('#pph').val();
    let dispersen = $('#dispersen').val();
    let disrp = $('#disrp').val();

    let disrp_x = disrp.replace(",", "");
    // console.log(disrp_x);
    let totalbersih;
    let hitungpph;
    hitungpph = parseFloat(totalseluruh) + (parseFloat(totalseluruh) * parseFloat(pph) / 100);
    totalbersih = hitungpph - (hitungpph * parseFloat(dispersen) / 100) - parseFloat(disrp_x);


    // console.log(totalbersih);
    // if (dispersen !== 0 || dispersen !== undefined || dispersen !== null) {
    //     totalbersih = hitungpph - (hitungpph * parseFloat(dispersen) / 100);
    // } else if (disrp !== 0 || disrp !== undefined || disrp !== null) {
    //     totalbersih = hitungpph - (hitungpph - eval(disrp_x));
    // } else {
    //     totalbersih = hitungpph;
    // }

    $('#totalpembayaran').autoNumeric('set', totalbersih.toFixed(2));
    $('#totalpembayaranx').val(totalbersih.toFixed(2));
}
</script>