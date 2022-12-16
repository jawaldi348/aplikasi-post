<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
        </div>
        <?= form_open('laporan/cetak-sisapembulatan', ['target' => '_blank']) ?>
        <div class="card-body">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Pilih Tanggal</label>
                <div class="col-sm-3">
                    <input type="date" name="tglawal" id="tglawal" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
                <div class="col-sm-1">
                    s.d
                </div>
                <div class="col-sm-3">
                    <input type="date" name="tglakhir" id="tglakhir" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
                <div class="col-sm-1">
                    <button type="submit" class="btn btn-sm btn-primary btncetak">Cetak</button>
                </div>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>