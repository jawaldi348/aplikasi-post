<script src="<?= base_url('assets/js/jquery.form.js') ?>"></script>
<div class="col-md-12 col-lg-12 col-xl-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
        </div>
        <div class="card-body">
            <div class="pesan" style="display: none;"></div>
            <?= form_open_multipart('import/doimport', ['class' => 'formimport']) ?>
            <table border="0">
                <tr>
                    <td>
                        Import File Excel
                    </td>
                    <td>
                        <input type="file" name="uploadfile" accept=".xls,.xlsx">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" class="btnimport btn-primary">Import</button>
                        <button type="button" class="btnreload btn-info" style="display: none;">Reload</button>
                    </td>
                </tr>
            </table>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function(e) {
    $('.formimport').ajaxForm({
        // dataType: 'json',
        beforeSend: function() {
            $('.btnimport').attr('disabled', 'disabled');
            $('.btnimport').html('Tunggu Sedang di Proses...');
        },
        success: function(data) {
            $('.pesan').fadeIn('slow');
            $('.pesan').html(data);
        },
        complete: function() {
            $('.btnimport').removeAttr('disabled');
            $('.btnimport').html('Import');
            $('.btnreload').fadeIn('slow');
        },
        error: function(e) {
            alert(e);
        }
    });

    $('.btnreload').click(function(e) {
        window.location.reload();
    });
});
</script>