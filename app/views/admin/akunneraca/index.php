<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header">
            <button type="button" class="btn btn-sm btn-primary btnTambahAkun">
                <i class="fa fa-plus-circle"></i> Tambah Akun
            </button>
        </div>
        <div class="card-body tampildata">

        </div>

    </div>

</div>
<div class="viewmodal" style="display: none;"></div>
<script>
function tampildataakun() {
    $.ajax({
        url: "<?= site_url('admin/akunneraca/tampildata') ?>",
        dataType: "json",
        beforeSend: function() {
            $('.tampildata').html('<i class="fa fa-spin fa-spinner"></i>');
        },
        success: function(response) {
            if (response.data) {
                $('.tampildata').html(response.data);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}
$(document).ready(function() {
    tampildataakun();
    $('.btnTambahAkun').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('akunneraca/tambahakun') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modaltambahakun').on('shown.bs.modal', function(e) {
                        $('input[name="kodeakun1"]').focus();
                    });
                    $('#modaltambahakun').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
});
</script>