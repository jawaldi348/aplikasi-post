<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/select2.min.css') ?>">
<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-header bg-default text-white">
            <button type="button" class="btn btn-warning"
                onclick="window.location='<?= site_url('admin/produk/index') ?>'">
                <i class="fa fa-fw fa-step-backward"></i> Kembali
            </button>
        </div>
        <div class="card-body">
            <p class="card-text">
            <div class="alert alert-warning">
                Silahkan Update Data Produk Melalui Form Berikut :
            </div>
            <?= $this->session->flashdata('msg'); ?>
            <?= form_open('admin/produk/update', ['class' => 'formproduk']) ?>
            <input type="hidden" name="idproduk" id="idproduk" value="<?= $id; ?>">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kode Barcode/Produk<sup style="color: red;">*</sup></label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" autocomplete="off" name="kode" value="<?= $kode ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Produk<sup style="color: red;">*</sup></label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" placeholder="Isikan Dengan Lengkap Nama Produk" name="nama"
                        value="<?= $nama ?>" autofocus="autofocus">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Satuan<sup style="color: red;">*</sup></label>
                <div class="col-sm-4">
                    <select name="satuan" id="satuan" class="form-control" style="width: 100%;">
                        <?php foreach ($datasatuan as $s) : ?>
                        <?php
                            if ($s->satid == $idsatuan) {
                                echo "<option value=\"$s->satid\" selected>" . $s->satnama . "</option>";
                            } else {
                                echo "<option value=\"$s->satid\">" . $s->satnama . "</option>";
                            }
                            ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kategori</label>
                <div class="col-sm-4">
                    <select name="kategori" id="kategori" class="form-control" style="width: 100%;">
                        <?php foreach ($datakategori as $k) : ?>
                        <?php
                            if ($k->katid == $idkategori) {
                                echo "<option value=\"$k->katid\" selected>" . $k->katnama . "</option>";
                            } else {
                                echo "<option value=\"$k->katid\">" . $k->katnama . "</option>";
                            }
                            ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Stok Yang Tersedia</label>
                <div class="col-sm-4">
                    <input type="number" name="stok" id="stok" class="form-control" value="<?= $stok; ?>">
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info" style="font-style: italic;">
                        - Silahkan Isi Ketersedian Stok Secara Keseluruhan
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Harga Modal/Beli (Rp)</label>
                <div class="col-sm-4">
                    <input type="text" name="hargabeli" id="hargabeli" class="form-control" value="<?= $hargabeli; ?>"
                        style="text-align: right;">
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info" style="font-style: italic;">
                        - Input Harga beli dari produk dalam satuan terkecil
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Margin (%)</label>
                <div class="col-sm-2">
                    <input type="text" name="margin" id="margin" value="<?= $margin; ?>" style="text-align: right;"
                        class="form-control">
                </div>
                <div class="col-sm-1">
                    <span class="badge badge-info">%</span>
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info">- Berikan tanda titik (.) untuk bilangan desimal</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Harga Jual (Rp)</label>
                <div class="col-sm-4">
                    <input type="text" name="hargajual" id="hargajual" class="form-control" value="<?= $hargajual; ?>"
                        style="text-align: right;">
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info" style="font-style: italic;">
                        - Input Harga Jual Eceran dari produk dalam satuan terkecil
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Harga Jual Reseller (Rp)</label>
                <div class="col-sm-4">
                    <input type="text" name="hargajualgrosir" id="hargajualgrosir" class="form-control"
                        value="<?= $hargajualgrosir; ?>" style="text-align: right;">
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info" style="font-style: italic;">
                        - Input Harga Jual Reseller dari produk dalam satuan terkecil
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Qty Default Per-Satuan</label>
                <div class="col-sm-4">
                    <input type="number" name="jml" id="jml" class="form-control" value="<?= $jml; ?>">
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info" style="font-style: italic;">
                        - Isi quantity default berdasarkan satuan yang dipilih. Digunakan untuk transaksi penjualan.
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-fw fa-save"></i> Simpan
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
            </p>
        </div>
    </div>
</div>
<div class="viewform" style="display: none"></div>
<script src="<?= base_url('assets/plugins/select2/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script>
$(document).ready(function(e) {
    $('#satuan').select2();
    $('#kategori').select2();

    //setting currency
    $('#hargabeli').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    //setting currency
    $('#hargajual').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });

    $('#hargajualgrosir').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
    //setting currency
    $('#margin').autoNumeric('init', {
        aSep: ',',
        aDec: '.',
        mDec: '2'
    });
});

// Perhitungan Margin

$(document).on('keyup', '#margin', function(e) {
    let margin = $(this).val();
    let hargabeli = $('#hargabeli').val();
    let konversi_hargabeli = hargabeli.replace(",", "");

    hitung_hargajual = parseFloat(konversi_hargabeli) + ((parseFloat(konversi_hargabeli) *
        parseFloat(margin)) / 100);

    $('#hargajual').autoNumeric('set', hitung_hargajual);
});

$(document).on('keyup', '#hargajual', function(e) {
    let hargajual = $(this).autoNumeric('get');
    let hargabeli = $('#hargabeli').autoNumeric('get');

    let hitunglaba;
    hitunglaba = parseFloat(hargajual) - parseFloat(hargabeli);

    let margin;
    margin = (hitunglaba / hargabeli) * 100;

    $('#margin').autoNumeric('set', margin);
});
</script>