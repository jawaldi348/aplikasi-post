<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <!-- <button type="button" class="btn btn-sm btn-primary waves-effect waves-light btntambah">
                <i class="fa fa-plus-square"></i>
            </button> -->
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Sortir</label>
                <div class="col-sm-2">
                    <input type="date" name="tglawal" id="tglawal" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
                <div class="col-sm-1">
                    s.d
                </div>
                <div class="col-sm-2">
                    <input type="date" name="tglakhir" id="tglakhir" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
                <div class="col-sm-4">
                    <select name="kasir" id="kasir" class="form-control form-control-sm">
                        <option value="">-Semua-</option>
                        <?php foreach ($datakasir->result_array() as $d) : ?>
                        <option value="<?= $d['userid']; ?>"><?= $d['userid'] . '-' . $d['usernama']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-sm btn-primary btntampil">Tampil</button>
                </div>
            </div>
            <div class="row viewtampildata" style="display: none;"></div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.btntampil').click(function(e) {
        e.preventDefault();

        let tglawal = $('#tglawal').val();
        let tglakhir = $('#tglakhir').val();
        let kasir = $('#kasir').val();

        $.ajax({
            type: "post",
            url: "<?= site_url('laporan/tampilpenjualankasir') ?>",
            data: {
                tglawal: tglawal,
                tglakhir: tglakhir,
                kasir: kasir,
            },
            dataType: "json",
            beforeSend: function() {
                $('.viewtampildata').html('<i class="fa fa-spin fa-spinner"></i>').show();
            },
            success: function(response) {
                if (response.data) {
                    $('.viewtampildata').html(response.data).show();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });
});
</script>