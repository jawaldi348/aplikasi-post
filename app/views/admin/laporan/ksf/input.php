<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-primary text-white">
            Laporan Transaksi KSF
        </div>
        <div class="card-body">
            <?= form_open('laporan/cetak-ksf', ['target' => '_blank']); ?>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Pilih Tanggal</label>
                <div class="col-sm-4">
                    <input type="date" name="tglawal" id="tglawal" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
                <div class="col-sm-4">
                    <input type="date" name="tglakhir" id="tglakhir" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>