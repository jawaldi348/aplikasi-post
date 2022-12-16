<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <!-- <button type="button" class="btn btn-sm btn-primary waves-effect waves-light btntambah">
                <i class="fa fa-plus-square"></i>
            </button> -->
        </div>
        <div class="card-body">
            <?= form_open('admin/laporan/cetak-hutang-supplier', ['target' => '_blank']); ?>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Filter</label>
                <div class="col-sm-4">
                    <input type="date" name="tglawal" id="tglawal" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>
                <div class="col-sm-1">
                    s.d
                </div>
                <div class="col-sm-4">
                    <input type="date" name="tglakhir" id="tglakhir" value="<?= date('Y-m-d') ?>"
                        class="form-control-sm form-control">
                </div>

            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-sm btn-primary btntampil" name="cetaksupplier">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>