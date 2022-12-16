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
            <div class="alert alert-info">
                Silahkan Tambahkan Data Produk Melalui Form Berikut :
            </div>
            <?= $this->session->flashdata('msg'); ?>
            <?= form_open('admin/produk/simpan', ['class' => 'formproduk']) ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kode Barcode/Produk<sup style="color: red;">*</sup></label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" autofocus="autofocus" autocomplete="off" name="kode"
                        value="<?= $this->session->flashdata('kode'); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama Produk<sup style="color: red;">*</sup></label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" placeholder="Isikan Dengan Lengkap Nama Produk" name="nama"
                        value="<?= $this->session->flashdata('nama'); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Satuan<sup style="color: red;">*</sup></label>
                <div class="col-sm-4">
                    <select name="satuan" id="satuan" class="form-control" style="width: 100%;">

                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-primary btn-round btn-sm" id="btntambahsatuan"
                        title="Tambah Satuan">
                        <i class="fa fa-plus"></i>
                    </button>

                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Kategori</label>
                <div class="col-sm-4">
                    <select name="kategori" id="kategori" class="form-control" style="width: 100%;">

                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-info btn-round btn-sm" id="btnTambahKategori"
                        title="Tambah Kategori">
                        <i class="fa fa-plus"></i>
                    </button>

                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Stok Yang Tersedia</label>
                <div class="col-sm-4">
                    <input type="number" name="stok" id="stok" class="form-control" value="0">
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
                    <input type="text" name="hargabeli" style="text-align: right;" id="hargabeli" class="form-control"
                        value="0">
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
                    <input type="text" name="margin" id="margin" value="0" class="form-control"
                        style="text-align: right;">
                </div>
                <div class="col-sm-1">
                    <span class="badge badge-info">%</span>
                </div>
                <div class="col-sm-6">
                    <div class="alert alert-info">- Berikan tanda titik (.) untuk bilangan desimal</div>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label">Harga Jual Eceran (Rp)</label>
                <div class="col-sm-4">
                    <input type="text" name="hargajual" id="hargajual" class="form-control" value="0"
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
                    <input type="text" name="hargajualgrosir" id="hargajualgrosir" class="form-control" value="0"
                        style="text-align: right;">
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
                    <input type="number" name="jml" id="jml" class="form-control" value="1">
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
<script src="<?= base_url('assets/novinaldi/produk/formtambah.js'); ?>"></script>