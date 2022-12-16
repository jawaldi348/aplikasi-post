<!-- DatePicker -->
<link href="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default">
            Input Pembayaran Hutang <button type="button" class="btn btn-instagram"
                onclick="window.location='<?= site_url('admin/hutang/data') ?>'">&laquo; Kembali</button>
        </div>
        <div class="card-body">
            <?= form_open('admin/hutang/simpanpembayaran', ['class' => 'formhutang']); ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="table-responsive text-nowrap">
                        <!--Table-->
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td style="width: 20%;">No.Faktur</td>
                                    <td style="width: 1%;">:</td>
                                    <td><?= $nofaktur; ?> <input type="hidden" name="nofaktur" id="nofaktur"
                                            value="<?= $nofaktur; ?>"></td>
                                </tr>
                                <tr>
                                    <td>Tgl.Faktur</td>
                                    <td>:</td>
                                    <td><?= $tglbeli; ?></td>
                                </tr>
                                <tr>
                                    <td>Tgl.Jatuh Tempo Pembayaran</td>
                                    <td>:</td>
                                    <td><?= $tgljatuhtempo; ?></td>
                                </tr>
                                <tr>
                                    <td>Total Yang Harus di Bayarkan (Rp)</td>
                                    <td>:</td>
                                    <td>
                                        <?= $totalbersihx; ?>
                                        <input type="hidden" name="totalbersih" value="<?= $totalbersih; ?>">
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="msg" style="display: none;"></div>
                    <div class="form-group">
                        <label for="">Tgl.Pembayaran</label>
                        <input type="text" name="tglbayar" id="tglbayar" readonly="readonly" value="<?= $tglbayar; ?>"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah Pembayaran</label>
                        <input type="text" name="jmlbayar" value="<?= $jmlbayar; ?>" id="jmlbayar" class="form-control">
                        <input type="hidden" name="jmlbayarx" id="jmlbayarx" value="<?= $jmlbayar; ?>"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label for=""></label>
                        <button type="submit" class="btn btn-success waves-effect waves-light btnsimpan">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<!-- JQuery DatePicker -->
<script src="<?php echo base_url() ?>assets/plugins/timepicker/moment.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/timepicker/bootstrap-material-datetimepicker.js"></script>

<!-- Numeric Jquery -->
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function() {
    $('#tglbayar').bootstrapMaterialDatePicker({
        weekStart: 0,
        time: false,
        month: true
    });

    $('#jmlbayar').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });

    $('#jmlbayar').keyup(function(e) {
        let jmlbayar = $(this).val();
        ganti = jmlbayar.replace(",", "");

        $('#jmlbayarx').val(ganti);
    });

    $('.formhutang').submit(function(e) {
        Swal.fire({
            title: 'Pembayaran Hutang',
            text: "Yakin disimpan ?",
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
                        $('.btnsimpan').attr('disabled', 'disabled');
                        $('.btnsimpan').html(
                            '<i class="fa fa-spin fa-spinner"></i>')
                    },
                    success: function(response) {
                        if (response.error) {
                            $('.msg').html(response.error).fadeIn();
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
                        $('.btnsimpan').removeAttr('disabled');
                        $('.btnsimpan').html(
                            '<i class="fa fa-save"></i> Simpan')
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
</script>