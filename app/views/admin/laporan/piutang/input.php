<div class="col-lg-6">
    <div class="card m-b-30">
        <div class="card-header bg-primary text-white">
            Laporan Piutang Pelanggan
        </div>
        <div class="card-body">
            <?= form_open('admin/laporan/cetak-piutang-pelanggan', ['target' => '_blank']); ?>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Pilih Tanggal</label>
                <div class="col-sm-4">
                    <input type="date" name="tanggal" id="tanggal" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-sm btn-primary btntampil" name="cetakpiutang">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div class="card m-b-30">
        <div class="card-header bg-success text-white">
            Laporan Piutang Setiap Pelanggan
        </div>
        <div class="card-body">
            <?= form_open('admin/laporan/cetak-piutang-per-pelanggan', ['target' => '_blank']); ?>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Pilih Pelanggan</label>
                <div class="col-sm-8">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm" name="namamember" id="namamember"
                            readonly>
                        <input type="hidden" class="form-control form-control-sm" name="idmember" id="idmember">
                        <div class="input-group-append">
                            <button class="btn btn-outline-info btn-sm" type="button" id="tombolcarimember">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-sm btn-primary btntampil" name="cetakpiutang">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
    <div class="viewmodalcarimember" style="display: none;"></div>
</div>
<script>
$(document).ready(function() {
    $('#tombolcarimember').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('laporan/piutang-carimember') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalcarimember').html(response.data).show();
                    $('#modalcarimember').modal('show');
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