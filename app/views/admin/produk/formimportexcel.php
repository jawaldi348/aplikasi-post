<script src="<?= base_url('assets/js/jquery.form.js') ?>"></script>
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-warning btn-sm"
                onclick="window.location='<?= site_url('admin/produk/index') ?>'">
                &laquo; Kembali
            </button>
            <a href="<?= base_url('assets/fileimport/template_import_produk.xlsx'); ?>">Download Template</a>
        </div>
        <div class="card-body">
            <?= form_open_multipart('admin/produk/doimport', ['class' => 'formimport']) ?>
            <div class="card-text">
                <div class="pesan" style="display: none;"></div>
                <div class="form-group row">
                    <label for="" class="col-sm-2 col-form-label">Upload File</label>
                    <div class="col-sm-4">
                        <input type="file" accept=".xls,.xlsx" name="uploadfile">
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-sm btn-success btnimport">Import</button>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-sm btn-info btnreload" style="display: none;">Refresh
                            Halaman</button>
                    </div>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function(e) {
    $('.formimport').ajaxForm({
        // dataType: 'json',
        beforeSend: function() {
            $('.btnimport').prop('disabled', true);
            $('.btnimport').html('<i class="fa fa-spin fa-spinner"></i> Tunggu Sedang di Proses');
        },
        success: function(data) {
            $('.pesan').fadeIn('slow');
            $('.pesan').html(data);
        },
        complete: function() {
            $('.btnimport').prop('disabled', false);
            $('.btnimport').html('Import');
            $('.btnreload').fadeIn('slow');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });

    $('.btnreload').click(function(e) {
        window.location.reload();
    });
});
</script>