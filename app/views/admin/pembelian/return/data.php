<div class="col-sm-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-outline-warning btn-sm"
                onclick="window.location.href=('<?= site_url('beli/return-input') ?>')">
                <i class="fa fa-backward"></i> Kembali Input
            </button>

        </div>
        <div class="card-body viewtampildata">

        </div>
    </div>
</div>
<script>
function datareturn() {
    $.ajax({
        url: "<?= site_url('beli/return_tabel') ?>",
        dataType: "json",
        beforeSend: function(e) {
            $('.viewtampildata').html(`<i class="fa fa-spin fa-spinner"></i> Tunggu`).show();
        },
        success: function(response) {
            if (response.data) {
                $('.viewtampildata').html(`${response.data}`).show();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

$(document).ready(function() {
    datareturn();
});
</script>