<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="window.location='<?= site_url('beli/data') ?>'">
                <i class="fa fa-backward" aria-hidden="true"></i> Kembali
            </button>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-6">
                    <table class="table table-sm table-striped">
                        <tr>
                            <td style="width: 35%;">No.Faktur</td>
                            <td style="width: 1%;">:</td>
                            <td><?= $faktur; ?></td>
                        </tr>
                        <tr>
                            <td>Tgl.Beli</td>
                            <td>:</td>
                            <td><?= $tglbeli; ?></td>
                        </tr>
                        <tr>
                            <td>Pemasok</td>
                            <td>:</td>
                            <td><?= $namapemasok; ?></td>
                        </tr>
                        <tr>
                            <td>Tgl.Jatuh Tempo Pembayaran</td>
                            <td>:</td>
                            <td><?= $tgljatuhtempo; ?></td>
                        </tr>
                        <tr>
                            <td>Total Kotor (Rp)</td>
                            <td>:</td>
                            <td style="text-align: right;"><?= $totalkotor; ?></td>
                        </tr>
                        <tr>
                            <td>PPH (%)</td>
                            <td>:</td>
                            <td style="text-align: right;"><?= $pph; ?></td>
                        </tr>
                        <tr>
                            <td>Diskon (%)</td>
                            <td>:</td>
                            <td style="text-align: right;"><?= $diskonpersen; ?></td>
                        </tr>
                        <tr>
                            <td>Diskon (Rp.)</td>
                            <td>:</td>
                            <td style="text-align: right;"><?= $diskonuang; ?></td>
                        </tr>
                        <tr>
                            <td>Total Bersih (Rp.)</td>
                            <td>:</td>
                            <td style="text-align: right;"><?= $totalbersih; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6">
                    <?= form_open('beli/simpanbayarhutang', ['class' => 'formsimpan']) ?>
                    <input type="hidden" name="faktur" value="<?= $faktur; ?>">
                    <div class="form-group">
                        <label for="">Tgl.Pembayaran</label>
                        <div class="form-row">
                            <div class="col-12">
                                <input type="date" name="tglbayar" id="tglbayar" class="form-control-sm form-control">
                                <div class="invalid-feedback errortglbayar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah Pembayaran (Rp)</label>
                        <div class="form-row">
                            <div class="col-6">
                                <input type="text" name="jmlbayar" id="jmlbayar" value="<?= $totalbersih; ?>"
                                    class="form-control-sm form-control">
                                <div class="invalid-feedback errorjmlbayar">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Isi Pesan</label>
                        <div class="form-row">
                            <div class="col-12">
                                <textarea name="pesan" id="pesan" cols="30" rows="5"
                                    class="form-control form-control-sm" placeholder="Isi pesan jika ada..."></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-sm btnsimpan btn-block">
                            Simpan
                        </button>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function() {
    //setting currency
    $('#jmlbayar').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });

    $('.formsimpan').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('.btnsimpan').prop('disabled', true);
                $('.btnsimpan').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            complete: function() {
                $('.btnsimpan').prop('disabled', false);
                $('.btnsimpan').html('Simpan');
            },
            success: function(response) {
                if (response.error) {
                    let x = response.error;

                    if (x.tglbayar) {
                        $('.errortglbayar').html(x.tglbayar);
                        $('#tglbayar').addClass('is-invalid');
                    } else {
                        $('.errortglbayar').html('');
                        $('#tglbayar').removeClass('is-invalid');
                    }

                    if (x.jmlbayar) {
                        $('.errorjmlbayar').html(x.jmlbayar);
                        $('#jmlbayar').addClass('is-invalid');
                    } else {
                        $('.errorjmlbayar').html('');
                        $('#jmlbayar').removeClass('is-invalid');
                    }
                } else {
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Berhasil',
                    //     html: `${response.sukses}`,
                    // }).then((result) => {
                    //     if (result.value) {
                    //         window.location = response.link;
                    //     }
                    // });

                    Swal.fire({
                        title: `Cetak Konsinyasi`,
                        html: 'Apakah ini Konsinyasi, Jika Iya silahkan cetak struk ?',
                        icon: 'warning',
                        focusConfirm: true,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Cetak !',
                        cancelButtonText: 'Tidak',
                    }).then((result) => {
                        if (result.value) {
                            var top = window.screen.height - 400;
                            top = top > 0 ? top / 2 : 0;

                            var left = window.screen.width - 200;
                            left = left > 0 ? left / 2 : 0;

                            // var url = '.././pemasok/index';
                            var uploadWin = window.open(response.cetakkonsinyasi,
                                "Struk Kasir",
                                "width=200,height=400" + ",top=" + top +
                                ",left=" + left);
                            uploadWin.moveTo(left, top);
                            uploadWin.focus();
                            window.location = response.link;
                        } else {
                            window.location = response.link;
                        }
                    })
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        return false;
    });
});
</script>