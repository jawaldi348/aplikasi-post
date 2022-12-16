<div class="col-lg-12">
    <div class="card m-b-5">
        <div class="card-header bg-white">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Pilih Bulan</label>
                <div class="col-sm-4">
                    <input type="month" class="form-control" name="bulan" id="bulan">
                </div>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-sm btn-success tampilkangrafik">
                        <i class="fa fa-print"></i> Tampilkan
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body viewtampildata">

        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.tampilkangrafik').click(function(e) {

        e.preventDefault();
        let bulan = $('#bulan').val();
        $.ajax({
            type: "post",
            url: "<?= site_url('laporan/grafikproduklaku') ?>",
            data: {
                bulan: bulan
            },
            cache: false,
            dataType: "json",
            beforeSend: function() {
                $('.viewtampildata').html(
                    '<i class="fa fa-spin fa-spinner"></i> Mohon Tunggu Grafik sedang ditampilkan'
                    ).fadeIn();
            },
            success: function(response) {
                if (response.data) {
                    $('.viewtampildata').html(response.data).show();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                    thrownError);
            }
        });
    });
});
</script>