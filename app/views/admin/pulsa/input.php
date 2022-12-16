<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css') ?>">
<script src="<?= base_url('assets/plugins/select2/select2.min.js') ?>"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-warning"
                onclick="window.location='<?= site_url('pulsa/data') ?>'">
                &laquo; Kembali
            </button>
        </div>
        <div class="card-body">
            <?= form_open('admin/pulsa/simpanproduk', ['class' => 'formsimpan']) ?>
            <div class="card-text">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Kode Produk <small>* Otomatis di
                            Generate</small></label>
                    <div class="col-sm-4">
                        <input type="text" readonly class="form-control form-control-sm" id="kode"
                            placeholder="Otomatis di Generate" name="kode">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Pilih Operator</label>
                    <div class="col-sm-4">
                        <select required name="operator" id="operator" class="form-control form-control-sm"
                            style="width: 100%;">
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-primary btn-sm tambahoperator" type="button">
                            <i class="fa fa-plus-square"></i>
                        </button>&nbsp;
                        <button class="btn btn-danger btn-sm hapusoperator" type="button">
                            <i class="fa fa-minus-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-sm-3 col-form-label">Jumlah Voucher</label>
                    <div class="col-sm-4">
                        <select required name="voucher" id="voucher" class="form-control form-control-sm"
                            style="width: 100%;">
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-primary btn-sm tambahvoucher" type="button">
                            <i class="fa fa-plus-square"></i>
                        </button>&nbsp;
                        <button class="btn btn-danger btn-sm hapusvoucher" type="button">
                            <i class="fa fa-minus-circle"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Harga Modal</label>
                    <div class="col-sm-4">
                        <input type="text" required class="form-control form-control-sm" id="hargamodal"
                            name="hargamodal" style="text-align: right;">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Harga Jual</label>
                    <div class="col-sm-4">
                        <input type="text" required class="form-control form-control-sm" id="hargajual" name="hargajual"
                            style="text-align: right;">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-success btnsimpan">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
function tampildata_operator() {
    $.ajax({
        url: "<?= site_url('admin/pulsa/dataoperator') ?>",
        dataType: 'json',
        success: function(response) {
            $('#operator').html(response.data);
            $('#operator').select2({
                tags: true
            });
        }
    });
}

function tampildata_voucher() {
    $.ajax({
        url: "<?= site_url('admin/pulsa/datavoucher') ?>",
        dataType: 'json',
        success: function(response) {
            $('#voucher').html(response.data);
            $('#voucher').select2({
                tags: true
            });
        }
    });
}
$(document).ready(function() {

    tampildata_operator();
    tampildata_voucher();

    $('#hargamodal').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '0'
    });
    $('#hargajual').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '0'
    });

    $('.tambahoperator').click(function(e) {
        e.preventDefault();
        let operator = $('#operator').val();

        if (operator.length == 0) {
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/pulsa/simpanbaru_operator') ?>",
                data: {
                    operator: operator
                },
                dataType: "json",
                success: function(response) {
                    tampildata_operator();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }

    });

    $('.tambahvoucher').click(function(e) {
        e.preventDefault();
        let voucher = $('#voucher').val();

        if (voucher.length == 0) {
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "<?= site_url('admin/pulsa/simpanbaru_voucher') ?>",
                data: {
                    voucher: voucher
                },
                dataType: "json",
                success: function(response) {
                    tampildata_voucher();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }

    });

    $('.hapusoperator').click(function(e) {
        e.preventDefault();
        let operator = $('#operator').val();
        $.ajax({
            type: "post",
            url: "<?= site_url('admin/pulsa/hapus_operator') ?>",
            data: {
                operator: operator
            },
            dataType: "json",
            success: function(response) {
                tampildata_operator();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
    $('.hapusvoucher').click(function(e) {
        e.preventDefault();
        let voucher = $('#voucher').val();
        $.ajax({
            type: "post",
            url: "<?= site_url('admin/pulsa/hapus_voucher') ?>",
            data: {
                voucher: voucher
            },
            dataType: "json",
            success: function(response) {
                tampildata_voucher();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    $('.formsimpan').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $('.btnsimpan').prop('disabled', true);
                $('.btnsimpan').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            complete: function() {
                $('.btnsimpan').prop('disabled', false);
                $('.btnsimpan').html('Simpan');
            },
            success: function(response) {
                // if (response.error) {
                //     if (response.error.tglreturn) {
                //         $('#tglreturn').addClass('is-invalid');
                //         $('.errortglreturn').html(response.error.tglreturn)
                //     } else {
                //         $('#tglreturn').removeClass('is-invalid');
                //         $('.errortglreturn').html('');
                //     }
                //     if (response.error.jmlreturn) {
                //         $('#jmlreturn').addClass('is-invalid');
                //         $('.errorjmlreturn').html(response.error.jmlreturn)
                //     } else {
                //         $('#jmlreturn').removeClass('is-invalid');
                //         $('.errorjmlreturn').html('');
                //     }

                //     if (response.error.stt) {
                //         $('#stt').addClass('is-invalid');
                //         $('.errorstt').html(response.error.stt)
                //     } else {
                //         $('#stt').removeClass('is-invalid');
                //         $('.errorstt').html('');
                //     }
                // }

                if (response.sukses) {
                    Swal.fire({
                        icon: 'success',
                        title: `Berhasil`,
                        text: `${response.sukses}`,
                    }).then((result) => {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: xhr.status,
                    html: xhr.responseText + "\n" + thrownError,
                });
            }
        });
        return false;
    });
});
</script>