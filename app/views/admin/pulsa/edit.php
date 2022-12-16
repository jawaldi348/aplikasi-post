<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-sm btn-warning"
                onclick="window.location='<?= site_url('pulsa/data') ?>'">
                &laquo; Kembali
            </button>
        </div>
        <div class="card-body">
            <div class="alert alert-info">Anda hanya mengedit harga modal dan harga jualnya saja !!!</div>
            <?= form_open('admin/pulsa/updateproduk', ['class' => 'formsimpan']) ?>
            <input type="hidden" name="idproduk" value="<?= $row['id']; ?>">
            <div class="card-text">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Kode Produk</label>
                    <div class="col-sm-4">
                        <input type="text" readonly class="form-control form-control-sm" id="kode" name="kode"
                            value="<?= $row['kodebarcode']; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Nama Produk</label>
                    <div class="col-sm-6">
                        <input type="text" readonly class="form-control form-control-sm" id="namaproduk"
                            name="namaproduk" value="<?= $row['namaproduk']; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Harga Modal</label>
                    <div class="col-sm-4">
                        <input type="text" required class="form-control form-control-sm" id="hargamodal"
                            name="hargamodal" style="text-align: right;" value="<?= $row['harga_beli_eceran']; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Harga Jual</label>
                    <div class="col-sm-4">
                        <input type="text" required class="form-control form-control-sm" id="hargajual" name="hargajual"
                            style="text-align: right;" value="<?= $row['harga_jual_eceran']; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-success btnsimpan">
                            Update
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
$(document).ready(function() {
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
                $('.btnsimpan').html('Update');
            },
            success: function(response) {
                if (response.sukses) {
                    Swal.fire({
                        icon: 'success',
                        title: `Berhasil`,
                        text: `${response.sukses}`,
                    }).then((result) => {
                        window.location = ('<?= site_url('pulsa/data') ?>');
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