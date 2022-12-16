<div class="col-lg-6">
    <div class="card m-b-30">
        <div class="card-header bg-primary text-white">
            Cetak Periode Tanggal Per-Faktur
        </div>
        <div class="card-body">
            <?= form_open('laporan/pembelian-cetak-per-tanggal', ['class' => 'formcetak', 'target' => '_blank']); ?>
            <div class="form-group row">
                <label for="tglawal" class="col-sm-2 col-form-label">Tgl.Awal</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control form-control-sm" id="tglawal" name="tglawal" required
                        value="<?= date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="tglakhir" class="col-sm-2 col-form-label">Tgl.Akhir</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control form-control-sm" id="tglakhir" name="tglakhir" required
                        value="<?= date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success btn-sm" name="cetakfaktur">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                    <button type="submit" class="btn btn-info btn-sm" name="cetakdetail">
                        <i class="fa fa-print"></i> Cetak Detail Item
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Pemasok -->
<div class="col-lg-6">
    <div class="card m-b-30">
        <div class="card-header bg-success text-white">
            Cetak Per-Supplier
        </div>
        <div class="card-body">
            <?= form_open('laporan/pembelian-cetak-per-supplier', ['class' => 'formcetakpersupplier', 'target' => '_blank']); ?>
            <div class="form-group row">
                <label for="tglawal" class="col-sm-2 col-form-label">Tgl.Awal</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control form-control-sm" id="tglawal" name="tglawal" required
                        value="<?= date('Y-m-d'); ?>">
                </div>
                <div class="col-sm-2">
                    <label for="">s.d</label>
                </div>
                <div class="col-sm-4">
                    <input type="date" class="form-control form-control-sm" id="tglakhir" name="tglakhir" required
                        value="<?= date('Y-m-d'); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="tglakhir" class="col-sm-2 col-form-label">Pemasok</label>
                <div class="col-sm-10">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm" name="namapemasok" id="namapemasok"
                            readonly>
                        <input type="hidden" class="form-control form-control-sm" name="idpemasok" id="idpemasok">
                        <div class="input-group-append">
                            <button class="btn btn-outline-info btn-sm" type="button" id="btncaripemasok">
                                <i class="fa fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                </div>
            </div>
            <div class="viewmodalcaripemasok" style="display: none;"></div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#btncaripemasok').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: "<?= site_url('laporan/pembelian-caripemasok') ?>",
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodalcaripemasok').html(response.data).show();
                    $('#modalcaripemasok').modal('show');
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