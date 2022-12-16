<div class="col-sm-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-outline-warning btn-sm"
                onclick="window.location.href=('<?= site_url('beli/index') ?>')">
                <i class="fa fa-backward"></i> Kembali
            </button>

            <button type="button" class="btn btn-primary btntampilsemua btn-sm">
                <i class="fa fa-tasks"></i> Tampilkan Semua Data
            </button>

            <button type="button" class="btn btn-info btntampildatahutang btn-sm">
                <i class="fa fa-tasks"></i> Tampilkan Data Hutang
            </button>
        </div>
        <div class="card-body viewtampildata">

        </div>
    </div>
</div>

<script>
function tampilsemuadata() {
    $.ajax({
        url: "<?= site_url('beli/semuadata') ?>",
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

function tampildatahutang() {
    $.ajax({
        url: "<?= site_url('beli/datahutang') ?>",
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
    tampilsemuadata();

    $('.btntampilsemua').click(function(e) {
        e.preventDefault();
        tampilsemuadata();
    });
    $('.btntampildatahutang').click(function(e) {
        e.preventDefault();
        tampildatahutang();
    });


});
</script>