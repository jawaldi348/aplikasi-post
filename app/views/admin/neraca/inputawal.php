<div class="col-lg-12">
    <div class="card m-b-5">
        <div class="card-header">
            <div class="alert alert-info">
                <strong><i class="fa fa-info-circle"></i> Info !</strong>&nbsp;&nbsp;Silahkan input awal dari akun
                neraca dibawah ini.
            </div>
        </div>
        <div class="card-body viewtampildata">

        </div>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script>
function tampilakun() {
    $.ajax({
        url: "<?= site_url('neraca/tampil-neraca') ?>",
        dataType: "json",
        beforeSend: function(e) {
            $('.viewtampildata').html('<i class="fa fa-spin fa-spinner"></i>').show();
        },
        success: function(response) {
            $('.viewtampildata').html(response.data).show();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    tampilakun();
});
</script>