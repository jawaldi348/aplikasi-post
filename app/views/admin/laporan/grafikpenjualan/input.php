<div class="col-lg-12">
    <div class="card m-b-5">
        <div class="card-header bg-white">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Pilih Bulan dan Tahun</label>
                <div class="col-sm-4">
                    <input type="month" class="form-control" name="bulan" id="bulan" value="<?= date('Y-m'); ?>">
                </div>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-sm btn-success tampilkangrafik">
                        <i class="fa fa-print"></i> Tampilkan
                    </button>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Input Tahun</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="tahun" id="tahun" maxlength="4"
                        value="<?= date('Y'); ?>">
                </div>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-sm btn-success tampilkangrafiktahun">
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
            url: "<?= site_url('laporan/tampil-grafik-penjualan-perbulan') ?>",
            data: {
                bulan: bulan
            },
            cache: false,
            dataType: "json",
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
    $('.tampilkangrafiktahun').click(function(e) {

        e.preventDefault();
        let tahun = $('#tahun').val();
        $.ajax({
            type: "post",
            url: "<?= site_url('laporan/tampil-grafik-penjualan-pertahun') ?>",
            data: {
                tahun: tahun
            },
            cache: false,
            dataType: "json",
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