<div class="col-lg-12">
    <div class="card border-light mb-1">
        <div class="card-header">

        </div>
        <div class="card-body">
            <?= form_open('pengaturan/simpan'); ?>
            <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">
                    Aktifkan Stok Minus atau kosong dapat masuk di
                    kasir</label>
                <div class="col-sm-8">
                    <input type="radio" value="1" name="stokminus"
                        <?php if ($datapengaturan['stokminus'] == 1) echo 'checked==true' ?>> Aktifkan <br>
                    <input type="radio" value="0" name="stokminus"
                        <?php if ($datapengaturan['stokminus'] == 0) echo 'checked==true' ?>> Non Aktifkan
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-sm btn-success">
                        Simpan
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>