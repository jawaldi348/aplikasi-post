<div class="col-lg-12">
    <div class="card m-b-5">
        <div class="card-header bg-white">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Pilih Bulan dan Tahun</label>
                <div class="col-sm-4">
                    <input type="month" class="form-control" name="bulan" id="bulan">
                </div>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-sm btn-success btncetakincome">
                        <i class="fa fa-print"></i> Cetak
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
    $('.btncetakincome').click(function(e) {

        e.preventDefault();
        let bulan = $('#bulan').val();
        var top = window.screen.height - 600;
        top = top > 0 ? top / 2 : 0;

        var left = window.screen.width - 800;
        left = left > 0 ? left / 2 : 0;

        var url = "<?= site_url('neraca/cetakincome/') ?>" + bulan;
        var uploadWin = window.open(url,
            "Balance Sheet",
            "width=800,height=600" + ",top=" + top +
            ",left=" + left);
        uploadWin.moveTo(left, top);
        uploadWin.focus();
    });
});
</script>