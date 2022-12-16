<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <!-- <button type="button" class="btn btn-sm btn-primary waves-effect waves-light btntambah">
                <i class="fa fa-plus-square"></i>
            </button> -->
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Pilih Bulan</label>
                <div class="col-sm-6">
                    <input type="month" name="bulan" id="bulan" class="form-control-sm form-control"
                        value="<?= date('Y-m'); ?>">
                </div>
                <div class="col-sm-1">
                    <button type="submit" class="btn btn-sm btn-primary btntampil">Tampil</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.btntampil').click(function(e) {
        e.preventDefault();
        let bulan = $('#bulan').val();

        var top = window.screen.height - 600;
        top = top > 0 ? top / 2 : 0;

        var left = window.screen.width - 800;
        left = left > 0 ? left / 2 : 0;

        var url = "<?= site_url('laporan/cetak-tabungan-diskon-member/') ?>" + bulan;
        var uploadWin = window.open(url,
            "Laporan Tabungan Diskon Member",
            "width=800,height=600" + ",top=" + top +
            ",left=" + left);
        uploadWin.moveTo(left, top);
        uploadWin.focus();
    });
});
</script>