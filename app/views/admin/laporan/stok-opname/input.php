<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
        </div>
        <?= form_open('laporan/cetak-laporan-stok-opname', ['target' => '_blank']) ?>
        <div class="card-body">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Pilih Tgl.Cetak</label>
                <div class="col-sm-4">
                    <input type="date" name="tgl" id="tgl" class="form-control form-control-sm"
                        value="<?= date('Y-m-d') ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>