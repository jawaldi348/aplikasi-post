<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
        </div>
        <?= form_open('laporan/cetak-laporan-produk-kadaluarsa', ['target' => '_blank']) ?>
        <div class="card-body">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Pilih Cetak</label>
                <div class="col-sm-4">
                    <input type="radio" name="pilih" value="nilai" required> Nilai Produk &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="pilih" value="jumlah" required> Jumlah Produk
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Pilih Tanggal Expired</label>
                <div class="col-sm-4">
                    <select name="ed" id="ed" class="form-control form-control-sm" required>
                        <option value="">Pilih</option>
                        <option value="1">
                            < 6 Bulan Tgl.Expired</option>
                        <option value="2">
                            < 3 Bulan Tgl.Expired</option>
                        <option value="3"> Sudah Expired
                        </option>
                    </select>
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